<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    protected string $pythonPath;
    protected string $scriptPath;
    protected string $serverScript;
    protected string $baseUrl = 'http://127.0.0.1:18765';

    public function __construct()
    {
        $this->pythonPath = $this->getPythonPath();
        $this->scriptPath = base_path('scripts/face_recognition.py');
        $this->serverScript = base_path('scripts/face_server.py');
    }

    public function warmup(): void
    {
        $this->ensureServer();
    }

    /**
     * So khớp 1 lần: phát hiện khuôn mặt + embedding + cosine.
     * Trả về confidence 0..1 và face_count.
     */
    public function verifyBinary(string $imageBinary, array $savedEmbedding): array
    {
        $this->ensureServer();

        $response = Http::timeout(12)
            ->withBody(json_encode([
                'action' => 'verify',
                'image_base64' => base64_encode($imageBinary),
                'embedding' => array_values($savedEmbedding),
            ]), 'application/json')
            ->post($this->baseUrl . '/');

        $result = $response->json();
        if (!is_array($result) || empty($result['success'])) {
            throw new Exception($result['error'] ?? 'Không nhận diện được khuôn mặt');
        }

        return [
            'face_count' => (int) ($result['face_count'] ?? 0),
            'confidence' => (float) ($result['confidence'] ?? 0),
        ];
    }

    public function getFaceEmbedding($imagePath)
    {
        if (!file_exists($imagePath)) {
            throw new Exception('File không tồn tại: ' . $imagePath);
        }

        try {
            $this->ensureServer();
            $response = Http::timeout(12)
                ->withBody(json_encode([
                    'action' => 'embedding',
                    'image_path' => realpath($imagePath),
                ]), 'application/json')
                ->post($this->baseUrl . '/');
            $result = $response->json();
            if (!is_array($result) || empty($result['success'])) {
                return null;
            }
            return $result['embedding'] ?? null;
        } catch (Exception $e) {
            Log::warning('Face server embedding fallback: ' . $e->getMessage());
            $result = $this->callPythonScript('embedding', [
                'image_path' => realpath($imagePath),
            ]);
            return $result['embedding'] ?? null;
        }
    }

    public function isValidFaceImage($imagePath)
    {
        try {
            return $this->getFaceEmbedding($imagePath) !== null;
        } catch (Exception $e) {
            Log::error('Face detection failed: ' . $e->getMessage());
            return false;
        }
    }

    public function detectFace($imagePath)
    {
        $embedding = $this->getFaceEmbedding($imagePath);
        return [
            'success' => true,
            'face_count' => $embedding ? 1 : 0,
        ];
    }

    public function saveEmbedding($embedding, $path)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($path, json_encode($embedding));
        return true;
    }

    public function loadEmbedding($path)
    {
        if (!file_exists($path)) {
            throw new Exception('File not found: ' . $path);
        }
        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    public function compareFaces($embedding1, $embedding2, $threshold = 0.5)
    {
        $emb1 = array_values($embedding1);
        $emb2 = array_values($embedding2);
        $dot = 0;
        $norm1 = 0;
        $norm2 = 0;
        $n = min(count($emb1), count($emb2));
        for ($i = 0; $i < $n; $i++) {
            $dot += $emb1[$i] * $emb2[$i];
            $norm1 += $emb1[$i] * $emb1[$i];
            $norm2 += $emb2[$i] * $emb2[$i];
        }
        $norm1 = sqrt($norm1);
        $norm2 = sqrt($norm2);
        if ($norm1 == 0 || $norm2 == 0) {
            return 0.0;
        }
        return (float) ($dot / ($norm1 * $norm2));
    }

    protected function ensureServer(): void
    {
        if ($this->isReady()) {
            return;
        }
        if (!file_exists($this->serverScript)) {
            throw new Exception('Không tìm thấy face server');
        }

        $lockPath = storage_path('framework/face-server.lock');
        $lockDir = dirname($lockPath);
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755, true);
        }
        $lock = fopen($lockPath, 'c');
        if ($lock && flock($lock, LOCK_EX)) {
            if (!$this->isReady()) {
                $this->spawnServer();
                $deadline = microtime(true) + 60;
                while (microtime(true) < $deadline) {
                    if ($this->isReady()) {
                        break;
                    }
                    usleep(250000);
                }
            }
            flock($lock, LOCK_UN);
        }
        if (is_resource($lock)) {
            fclose($lock);
        }

        if (!$this->isReady()) {
            throw new Exception('Bộ nhận diện khuôn mặt chưa sẵn sàng. Hãy thử lại sau vài giây.');
        }
    }

    protected function isReady(): bool
    {
        try {
            $response = Http::timeout(1)->get($this->baseUrl . '/health');
            return $response->ok() && $response->json('ready') === true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function spawnServer(): void
    {
        $log = storage_path('logs/face-server.log');
        $python = $this->pythonPath;
        $script = $this->serverScript;

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = 'cmd /C start "" /B "' . $python . '" "' . $script . '" >> "' . $log . '" 2>&1';
            pclose(popen($command, 'r'));
            return;
        }

        $command = sprintf('"%s" "%s" >> "%s" 2>&1 &', $python, $script, $log);
        exec($command);
    }

    protected function getPythonPath()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $paths = [
                'D:\\laragon\\bin\\python\\python-3.10\\python.exe',
                'python',
                'python3',
            ];
            foreach ($paths as $path) {
                $test = shell_exec('"' . $path . '" --version 2>nul');
                if ($test !== null && trim($test) !== '') {
                    return $path;
                }
            }
            return 'python';
        }
        return 'python3';
    }

    protected function callPythonScript($action, $data = [])
    {
        $data['action'] = $action;
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $escapedJson = str_replace('"', '\\"', $jsonData);
        $command = sprintf(
            '"%s" "%s" "%s" 2>&1',
            $this->pythonPath,
            $this->scriptPath,
            $escapedJson
        );
        $output = shell_exec($command);
        if ($output === null) {
            throw new Exception('Không thể thực thi Python script');
        }
        $lines = explode("\n", $output);
        $jsonLine = '';
        foreach (array_reverse($lines) as $line) {
            $line = trim($line);
            if ($line !== '' && ($line[0] === '{' || $line[0] === '[')) {
                $jsonLine = $line;
                break;
            }
        }
        if ($jsonLine === '') {
            throw new Exception('Không tìm thấy JSON trong output');
        }
        $result = json_decode($jsonLine, true);
        if (!$result || empty($result['success'])) {
            throw new Exception($result['error'] ?? 'Unknown error');
        }
        return $result;
    }
}
