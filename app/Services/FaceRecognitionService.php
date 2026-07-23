<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class FaceRecognitionService
{
    protected $pythonPath;
    protected $scriptPath;

    public function __construct()
    {
        $this->pythonPath = $this->getPythonPath();
        $this->scriptPath = base_path('scripts/face_recognition.py');
        
        if (!file_exists($this->scriptPath)) {
            throw new Exception('Python script not found: ' . $this->scriptPath);
        }
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
                if ($test !== null) {
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

        Log::info('Face Python command: ' . $command);
        
        $output = shell_exec($command);
        Log::info('Face Python raw output: ' . $output);

        if ($output === null) {
            throw new Exception('Không thể thực thi Python script');
        }

        // 🔥 LỌC CHỈ LẤY DÒNG JSON CUỐI CÙNG
        $lines = explode("\n", $output);
        $jsonLine = '';
        foreach (array_reverse($lines) as $line) {
            $line = trim($line);
            // Tìm dòng bắt đầu bằng { hoặc [
            if (!empty($line) && ($line[0] === '{' || $line[0] === '[')) {
                $jsonLine = $line;
                break;
            }
        }

        if (empty($jsonLine)) {
            Log::error('Raw Python output: ' . $output);
            throw new Exception('Không tìm thấy JSON trong output');
        }

        Log::info('Extracted JSON: ' . $jsonLine);

        $result = json_decode($jsonLine, true);
        if (!$result) {
            throw new Exception('Lỗi parse JSON: ' . $jsonLine);
        }

        if (!isset($result['success']) || !$result['success']) {
            throw new Exception($result['error'] ?? 'Unknown error');
        }

        return $result;
    }

    public function detectFace($imagePath)
    {
        if (!file_exists($imagePath)) {
            throw new Exception('File không tồn tại: ' . $imagePath);
        }
        
        return $this->callPythonScript('detect', [
            'image_path' => realpath($imagePath)
        ]);
    }

    public function getFaceEmbedding($imagePath)
    {
        if (!file_exists($imagePath)) {
            throw new Exception('File không tồn tại: ' . $imagePath);
        }
        
        $result = $this->callPythonScript('embedding', [
            'image_path' => realpath($imagePath)
        ]);
        return $result['embedding'] ?? null;
    }

    public function isValidFaceImage($imagePath)
    {
        try {
            if (!file_exists($imagePath)) {
                Log::error('File not found: ' . $imagePath);
                return false;
            }
            
            $result = $this->detectFace($imagePath);
            $faceCount = $result['face_count'] ?? 0;
            
            return $faceCount > 0;
        } catch (Exception $e) {
            Log::error('Face detection failed: ' . $e->getMessage());
            return false;
        }
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
        
        for ($i = 0; $i < count($emb1); $i++) {
            $dot += $emb1[$i] * $emb2[$i];
            $norm1 += $emb1[$i] * $emb1[$i];
            $norm2 += $emb2[$i] * $emb2[$i];
        }
        
        $norm1 = sqrt($norm1);
        $norm2 = sqrt($norm2);
        
        if ($norm1 == 0 || $norm2 == 0) {
            return 0;
        }
        
        $similarity = $dot / ($norm1 * $norm2);
        
        // ✅ SỬA LỖI: float() -> (float)
        return (float) $similarity;
    }

    public function testConnection()
    {
        try {
            $testImage = storage_path('app/test.jpg');
            if (!file_exists($testImage)) {
                return false;
            }
            $result = $this->detectFace($testImage);
            return isset($result['face_count']);
        } catch (Exception $e) {
            Log::error('Connection test failed: ' . $e->getMessage());
            return false;
        }
    }
}