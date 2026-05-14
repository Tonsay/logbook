<?php
session_start();
require_once __DIR__ . '/includes/db.php'; 

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM user_tb WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $db_password = $user['password'];
            
          
            if (password_verify($password, $db_password)) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['profile_picture'] = $user['profile_picture'];
                $_SESSION['success_message'] = "Logged in successfully!";
                header("Location: index.php"); 
exit();
            } 
            
            elseif ($password === $db_password) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; 
                $_SESSION['profile_picture'] = $user['profile_picture'];
                header("Location: index.php");
                exit();
            } else {
                $length = strlen($db_password);
                $error = "Incorrect. (DB Password Length: $length characters)";
            }
        } else {
            $error = "Username not found.";
        }
    }
}
include __DIR__ . '/views/login_view.php';
?>