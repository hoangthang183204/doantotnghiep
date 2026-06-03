<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - HR Flow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        body {
            background: linear-gradient(120deg, #1e3a8a 0%, #312e81 50%, #4c1d95 100%);
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background */
        .bg-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .login-card {
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .input-group {
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within {
            transform: translateX(5px);
        }
        
        .input-group input:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        /* Loading spinner */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="circle" style="width: 300px; height: 300px; top: -100px; left: -100px;"></div>
        <div class="circle" style="width: 200px; height: 200px; bottom: -50px; left: 20%;"></div>
        <div class="circle" style="width: 400px; height: 400px; top: 50%; right: -150px;"></div>
        <div class="circle" style="width: 150px; height: 150px; bottom: 20%; right: 15%;"></div>
        <div class="circle" style="width: 250px; height: 250px; top: 20%; left: -80px;"></div>
    </div>
    
    <div class="login-card w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-lg rounded-2xl flex items-center justify-center mx-auto shadow-2xl border border-white/20">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h2 class="mt-6 text-4xl font-bold text-white tracking-tight">HR Flow</h2>
            <p class="mt-2 text-white/70 text-sm">Hệ thống quản trị nhân sự toàn diện</p>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Error Message -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border-l-4 border-red-500 rounded-lg backdrop-blur-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-red-200 text-sm">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="mb-6">
                    <label class="block text-white/90 text-sm font-semibold mb-2">Địa chỉ Email</label>
                    <div class="input-group">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pl-10 pr-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"
                                   placeholder="admin@hrflow.com">
                        </div>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="mb-6">
                    <label class="block text-white/90 text-sm font-semibold mb-2">Mật khẩu</label>
                    <div class="input-group">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" name="password" required id="password"
                                   class="w-full pl-10 pr-12 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"
                                   placeholder="••••••">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/50 hover:text-white/80">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/30 bg-white/20 text-indigo-500 focus:ring-indigo-400 focus:ring-offset-0">
                        <span class="ml-2 text-sm text-white/70">Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="#" class="text-sm text-indigo-300 hover:text-white transition">Quên mật khẩu?</a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Đăng nhập
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white/50 text-xs">© 2026 HR Flow. Bảo mật & An toàn dữ liệu</p>
        </div>
    </div>
    
    <script>
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('svg').classList.toggle('text-indigo-400');
            });
        }
        
        // Fill demo account
        function fillDemo(email, pwd) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = pwd;
            
            // Highlight effect
            const inputs = document.querySelectorAll('input[name="email"], input[name="password"]');
            inputs.forEach(input => {
                input.classList.add('ring-2', 'ring-indigo-400');
                setTimeout(() => {
                    input.classList.remove('ring-2', 'ring-indigo-400');
                }, 500);
            });
        }
        
        // Loading effect on submit
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form) {
            form.addEventListener('submit', function() {
                submitBtn.classList.add('btn-loading');
                submitBtn.innerHTML = 'Đang đăng nhập...';
            });
        }
    </script>
</body>
</html>