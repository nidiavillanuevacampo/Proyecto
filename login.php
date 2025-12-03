<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Gestión</title>
    <meta name="description" content="Inicia sesión en tu cuenta para acceder al sistema de gestión">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Login Form -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <!-- Logo/Icon -->
                <div class="logo-container">
                    <svg class="logo-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="32" width="8" height="24" fill="#4285F4"/>
                        <rect x="20" y="24" width="8" height="32" fill="#EA4335"/>
                        <rect x="32" y="16" width="8" height="40" fill="#FBBC04"/>
                        <path d="M44 8 L52 8 L52 56 L44 56 Z" fill="#34A853"/>
                        <path d="M48 8 L56 4 L56 8" stroke="#FF6B00" stroke-width="2" fill="none"/>
                        <path d="M48 12 L56 8 L56 12" stroke="#FF6B00" stroke-width="2" fill="none"/>
                        <path d="M48 16 L56 12 L56 16" stroke="#FF6B00" stroke-width="2" fill="none"/>
                    </svg>
                </div>

                <!-- Welcome Text -->
                <h1 class="welcome-title">Bienvenido</h1>
                <p class="welcome-subtitle">Por favor introduzca su información</p>

                <!-- Login Form -->
                <form id="loginForm" class="login-form" method="POST" action="ventas.php">
                    <div class="form-group">
                        <label for="email" class="form-label">Email o Usuario</label>
                        <input 
                            type="text" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            required
                            autocomplete="username"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            required
                            autocomplete="current-password"
                        >
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember">
                            <span class="checkbox-label">Recordar Usuario</span>
                        </label>
                        <a href="#" class="forgot-password">Olvidó Contraseña?</a>
                    </div>

                    <button type="submit" class="btn-primary">Log In</button>
                    
                    <button type="button" class="btn-secondary">
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" fill="currentColor"/>
                            <circle cx="12" cy="12" r="6" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        Log in with Email
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side - Illustration -->
        <div class="illustration-section">
            <div class="illustration-wrapper">
                <img src="login_illustration.png" alt="Business Analytics Illustration" class="illustration-image">
            </div>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>
