<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: index.php");
    exit();
}

$error = '';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    if ($delete_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM user_tb WHERE user_id = ?");
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Admin account deleted successfully!";
            header("Location: manage_admins.php");
            exit();
        }
    } else {
        $error = "You cannot delete your own account while logged in.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $new_role = 'admin'; 

    $check_stmt = $conn->prepare("SELECT user_id FROM user_tb WHERE username = ?");
    $check_stmt->bind_param("s", $new_username);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        $error = "Username already exists. Please choose another.";
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO user_tb (username, password, role) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("sss", $new_username, $hashed_password, $new_role);
        if ($insert_stmt->execute()) {
            $_SESSION['success_message'] = "New Admin '$new_username' created successfully!";
            header("Location: manage_admins.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $edit_user_id = $_POST['edit_user_id'];               
    $superadmin_password = $_POST['current_password'];  
    $new_password = trim($_POST['new_password']);         
    
    $logged_in_user_id = $_SESSION['user_id'];            

    $fetch_stmt = $conn->prepare("SELECT password FROM user_tb WHERE user_id = ?");
    $fetch_stmt->bind_param("i", $logged_in_user_id);
    $fetch_stmt->execute();
    $result = $fetch_stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
 
        if (password_verify($superadmin_password, $row['password'])) {
            
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE user_tb SET password = ? WHERE user_id = ?");
            $update_stmt->bind_param("si", $hashed_password, $edit_user_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = "Password updated successfully!";
                header("Location: manage_admins.php");
                exit();
            }
        } else {
            $error = "Authorization Failed: Your Superadmin password is incorrect.";
        }
    }
}

$users_query = $conn->query("SELECT user_id, username, role FROM user_tb ORDER BY user_id ASC");
$all_users = $users_query->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/views/manage_admins_view.php';
?>