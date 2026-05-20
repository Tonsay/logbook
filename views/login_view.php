<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - DOST-SEI Logbook</title>
    
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-bg">

    <div id="globalLoader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #0f172a; z-index: 999999; display: flex; flex-direction: column; justify-content: center; align-items: center; transition: opacity 0.4s ease; display: none; opacity: 0;">
        <div id="loaderSpinner" style="width: 50px; height: 50px; border: 5px solid rgba(0, 165, 239, 0.2); border-top-color: #00A5EF; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <div id="loaderSuccess" style="display: none; width: 50px; height: 50px; background: #10b981; border-radius: 50%; justify-content: center; align-items: center; color: white; font-size: 24px; font-weight: bold; animation: popIn 0.3s ease-out;">✓</div>
        <p id="loaderText" style="color: #b9e6ff; margin-top: 15px; font-weight: bold; font-size: 14px; letter-spacing: 1px; animation: pulseText 1.5s infinite;">Authenticating...</p>
    </div>

    <style>
        @keyframes spin { 100% { transform: rotate(360deg); } }
        @keyframes pulseText { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        @keyframes popIn { 0% { transform: scale(0); } 80% { transform: scale(1.2); } 100% { transform: scale(1); } }
    </style>

    <div class="login-card">
        <img src="/logbook/assets/img/logo.png" alt="Logo" onclick="window.location.reload();" style="width: 45px; height: 45px; object-fit: contain; cursor: pointer;">
        <h2>Welcome Back</h2>
        <p>DOST-SEI Logbook System</p>

        <?php if(!empty($error)): ?>
            <div class="error-banner" style="font-size: 13px; padding: 12px; margin-bottom: 15px; background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #ff6b6b; border-radius: 8px; text-align: center; font-weight: bold;">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" action="login.php" method="POST" onsubmit="handleLoginSubmit(event)">
            <div class="form-group" style="text-align: left;">
                <label>Username</label>
                <input type="text" name="username" required autocomplete="off" placeholder="Enter username">
            </div>
            
            <div class="form-group" style="text-align: left; margin-top: 15px;">
                <label>Password</label>
                
                <div class="password-wrapper" style="position: relative;">
                    <input type="password" name="password" id="login_pass" required placeholder="Enter password" style="width: 100%; padding-right: 40px; box-sizing: border-box;">
                    <button type="button" class="password-toggle" onclick="togglePass('login_pass', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8;"></button>
                </div>
                
                <div style="text-align: right; margin-top: 8px;">
                   <a href="/logbook/forgot_password.php" style="color: #00A5EF; font-size: 13px; text-decoration: none;">Forgot Password?</a>
                </div>
            </div>

            <button type="submit" class="btn-confirm" style="width: 100%; margin-top: 20px; padding: 14px; background: #00A5EF; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                Log In
            </button>
        </form>
    </div>

    <script>
        const eyeOpen = `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
        const eyeSlash = `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"></path></svg>`;

        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.innerHTML = eyeOpen;
        });

        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                btn.innerHTML = eyeSlash;
            } else {
                input.type = "password";
                btn.innerHTML = eyeOpen;
            }
        }
    </script>

    <script>
        function handleLoginSubmit(event) {
            const form = event.target;
          
            if (!form.checkValidity()) {
                return; 
            }

            if (typeof showLoader === 'function') {
                showLoader();
            }
        }
    </script>
    <script src="/logbook/assets/js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>