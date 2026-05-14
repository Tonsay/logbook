<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/includes/db.php';

$error_msg = '';
$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Access Denied.");
}


$stmt = $conn->prepare("SELECT user_id, reset_expires FROM user_tb WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user) {
  
    die("<h2>Invalid Token</h2><p>The token in the link does not match our database.</p>");
}

$expiry_timestamp = strtotime($user['reset_expires']);
$current_timestamp = time();

if ($current_timestamp > $expiry_timestamp) {
    
    die("<h2>Link Expired</h2>
         <p>Current Time: " . date("Y-m-d H:i:s", $current_timestamp) . "</p>
         <p>Expiry Time: " . date("Y-m-d H:i:s", $expiry_timestamp) . "</p>
         <a href='/logbook/forgot_password.php'>Request a new link</a>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE user_tb SET password = ?, reset_token = NULL, reset_expires = NULL WHERE user_id = ?");
        $update_stmt->bind_param("si", $hashed_password, $user['user_id']);
        
        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Password reset successfully!";
            header("Location: /logbook/login.php");
            exit(); 
        }
    } else {
        $error_msg = "Passwords do not match!";
    }
}

include __DIR__ . '/views/reset_password.php';
?>