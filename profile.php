<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];
    $user_id = $_SESSION['user_id'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowed_types)) {
            
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = "user_" . $user_id . "_" . time() . "." . $extension;
            $destination = 'uploads/' . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
              
                $stmt = $conn->prepare("UPDATE user_tb SET profile_picture = ? WHERE user_id = ?");
                $stmt->bind_param("si", $new_filename, $user_id);
                
                if ($stmt->execute()) {
                    $_SESSION['profile_picture'] = $new_filename;
                    $message = "Profile picture updated successfully!";
                    $message_type = "success";
                } else {
                    $message = "Database update failed.";
                    $message_type = "error";
                }
            } else {
                $message = "Failed to move file to uploads folder.";
                $message_type = "error";
            }
        } else {
            $message = "Invalid file type. Please use JPG or PNG.";
            $message_type = "error";
        }
    }
}

include 'views/profile_view.php';
?>