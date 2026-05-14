<?php
session_start();
require_once __DIR__ . '/includes/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $document_id = trim($_POST['document_id'] ?? '');
    $division    = trim($_POST['division'] ?? '');
    $date_issued = trim($_POST['date_issued'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $subject     = trim($_POST['subject'] ?? '');
    $issuance_number = trim($_POST['issuance_number'] ?? '');

    
    if (empty($document_id) || empty($division) || empty($date_issued) || empty($category) || empty($subject)) {
        http_response_code(400);
        die("Error: Missing document data. Please fill out all fields.");
    }

   
    $stmt = $conn->prepare("UPDATE issuance_tb SET issuance_number=?, division=?, date_issued=?, category=?, subject=? WHERE document_id=?");
    
    if (!$stmt) {
        http_response_code(500);
        die("SQL Error: " . $conn->error);
    }
    
    
    $stmt->bind_param("ssssss", $issuance_number, $division, $date_issued, $category, $subject, $document_id);
   
    if ($stmt->execute()) {
        
      
        $username = $_SESSION['username'] ?? 'Admin'; 
        $action = "Document Edited";
        $details = "Updated issuance number, division, category, or subject.";

        $history_stmt = $conn->prepare("INSERT INTO history_tb (document_id, action_type, action_details, performed_by) VALUES (?, ?, ?, ?)");
      
        $history_stmt->bind_param("ssss", $document_id, $action, $details, $username);
        $history_stmt->execute();
        $history_stmt->close();
       

        $_SESSION['success_message'] = "Document updated successfully!";
        echo "SUCCESS";
        
    } else {
        http_response_code(500);
        echo "SQL Execute Error: " . $stmt->error;
    }
    
    $stmt->close();
    exit();
} else {
    http_response_code(405);
    echo "No POST data received.";
    exit();
}
?>