<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Forêt d'Aného</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #1B5E20;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-container {
            max-width: 420px;
            width: 100%;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .login-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            z-index: 1;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: white;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(46,125,50,0.1);
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(46,125,50,0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.2);
            transition: left 0.3s;
        }
        
        .btn-login:hover:before {
            left: 0;
        }
        
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        .login-footer a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .login-footer i {
            margin-right: 5px;
        }
        
        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
        }
        
        /* Mot de passe oublié */
        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .forgot-password a {
            color: #666;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }
        
        .forgot-password a:hover {
            color: var(--primary-color);
        }
        
        /* Checkbox remember */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .form-check-label {
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Messages flash -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Carte de connexion -->
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-tree"></i>
                </div>
                <h1>Administration</h1>
                <p>Forêt Urbaine d'Aného</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email">
                        <i class="far fa-envelope"></i> Email
                    </label>
                    <div class="input-group">
                        <i class="far fa-envelope"></i>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="admin@foret-aneho.tg"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mot de passe
                    </label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••"
                               required>
                        <button type="button" 
                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-3" 
                                style="z-index: 10; color: #999;"
                                onclick="togglePassword()">
                            <i class="far fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Se souvenir de moi
                    </label>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">
                    <span class="btn-text">Se connecter</span>
                    <span class="spinner" style="display: none;"></span>
                </button>
            </form>

            <div class="login-footer">
                <a href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    Retour au site
                </a>
            </div>
        </div>

        <!-- Version info -->
        <div style="text-align: center; margin-top: 20px; color: rgba(255,255,255,0.7); font-size: 0.8rem;">
            <i class="fas fa-shield-alt me-1"></i>
            Administration v1.0
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Loading spinner on submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner');
            
            submitBtn.disabled = true;
            btnText.style.opacity = '0';
            spinner.style.display = 'inline-block';
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.login-card').style.opacity = '1';
        });
    </script>
</body>
</html>