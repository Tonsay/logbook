<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - DOST-SEI Logbook</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-bg">

    <div class="login-card">
        <img src="/logbook/assets/img/logo.png" alt="DOST-SEI Logo">
        <h2>New Password</h2>
        <p>Set a new secure password for your account.</p>

        <?php if (!empty($error_msg)): ?>
            <div style="background: rgba(220, 53, 69, 0.2); color: #ff4d4d; padding: 10px; border-radius: 6px; margin-bottom: 15px; text-align: center; border: 1px solid #dc3545;">
                ❌ <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group" style="text-align: left;">
                <label>New Password</label>
                <div class="password-wrapper">
                    <input type="password" name="new_password" id="reset_new_pass" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePass('reset_new_pass', this)"></button>
                </div>
            </div>
            
            <div class="form-group" style="text-align: left; margin-top: 15px;">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="reset_confirm_pass" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePass('reset_confirm_pass', this)"></button>
                </div>
            </div>
            
            <button type="submit" class="btn-confirm" style="width: 100%; margin-top: 20px; padding: 14px;">
                Update Password
            </button>
        </form>
    </div>

    <script src="/logbook/assets/js/app.js"></script>
</body>
</html>