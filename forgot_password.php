<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/includes/db.php'; 

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']); 

    $stmt = $conn->prepare("SELECT user_id FROM user_tb WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(32)); 
        
        $expires = date("Y-m-d H:i:s", time() + 1800); 

        $update_stmt = $conn->prepare("UPDATE user_tb SET reset_token = ?, reset_expires = ? WHERE username = ?");
        $update_stmt->bind_param("sss", $token, $expires, $username);
        $update_stmt->execute();

        $reset_link = "http://localhost/logbook/reset_password.php?token=" . $token;
        
        $message = "<strong>TESTING MODE:</strong> Link generated!<br>";
        $message .= "<a href='" . $reset_link . "' style='color: #0056b3; font-weight:bold;'>Click here to Reset Password</a>";
    } else {
        $error = "If that account exists, a reset link has been generated."; 
    }
}
include __DIR__ . '/views/forgot_password.php';
?>