<?php
session_start();
require 'includes/db.php'; 

if (!isset($conn)) {
    die("Fatal Error: Database connection variable 'conn' is not defined. Check includes/db.php");
}

if (!isset($_SESSION['username'])) {
    header("Location: views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_username = $_SESSION['username'];
    $new_username = trim($_POST['username']);
    $new_password = $_POST['new_password'];

    if (!empty($new_username) && $new_username !== $current_username) {
        $stmt = $conn->prepare("UPDATE user_tb SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_username, $current_username);
        
        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username; 
            $current_username = $new_username; 
        }
    }

    if (!empty($new_password)) {
        $stmt = $conn->prepare("UPDATE user_tb SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $current_username);
        $stmt->execute();
    }

    $_SESSION['success_message'] = "Settings updated successfully!";
    header("Location: /logbook/index.php");
    exit();
}
?>