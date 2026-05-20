<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - DOST-SEI Logbook</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-bg">

    <div class="login-card">
        <img src="/logbook/assets/img/logo.png" alt="Logo" onclick="window.location.reload();" style="width: 45px; height: 45px; object-fit: contain; cursor: pointer;">
        <h2>Forgot Password</h2>
        <p>Enter your username to get a reset link.</p>

        <?php if (!empty($message)): ?>
            <div style="background: rgba(40, 167, 69, 0.2); color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; border: 1px solid #28a745;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(220, 53, 69, 0.2); color: #ff4d4d; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; border: 1px solid #dc3545;">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group" style="text-align: left;">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required autocomplete="off">
            </div>
            
            <button type="submit" class="btn-confirm" style="width: 100%; margin-top: 20px; padding: 14px;">
                Generate Reset Link
            </button>
        </form>

        <div style="margin-top: 20px;">
            <a href="/logbook/login.php" style="color: #ccc; font-size: 13px; text-decoration: none; opacity: 0.8;">
                ← Back to Login
            </a>
        </div>
    </div>

</body>
</html>