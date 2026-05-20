<?php
session_start();
require_once __DIR__ . '/includes/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $document_id     = trim($_POST['document_id'] ?? '');
    $division        = trim($_POST['division'] ?? '');
    $date_issued     = trim($_POST['date_issued'] ?? '');
    $category        = trim($_POST['category'] ?? '');
    $subject         = trim($_POST['subject'] ?? '');
    $issuance_number = trim($_POST['issuance_number'] ?? '');

    
    if (empty($document_id) || empty($division) || empty($date_issued) || empty($category) || empty($subject)) {
        http_response_code(400);
        die("Error: Missing document data. Please fill out all fields.");
    }

    $fetch_old_stmt = $conn->prepare("SELECT issuance_number, division, date_issued, category, subject FROM issuance_tb WHERE document_id = ?");
    $fetch_old_stmt->bind_param("s", $document_id);
    $fetch_old_stmt->execute();
    $old_data = $fetch_old_stmt->get_result()->fetch_assoc();
    $fetch_old_stmt->close();


    $changes_made = [];
    
    if ($old_data['issuance_number'] !== $issuance_number) {
        $changes_made[] = "Issuance No. from '{$old_data['issuance_number']}' to '{$issuance_number}'";
    }
    if ($old_data['division'] !== $division) {
        $changes_made[] = "Division from '{$old_data['division']}' to '{$division}'";
    }
    if ($old_data['date_issued'] !== $date_issued) {
        $changes_made[] = "Date from '{$old_data['date_issued']}' to '{$date_issued}'";
    }
    if ($old_data['category'] !== $category) {
        $changes_made[] = "Category from '{$old_data['category']}' to '{$category}'";
    }
    if ($old_data['subject'] !== $subject) {
        $changes_made[] = "Subject from '{$old_data['subject']}' to '{$subject}'";
    }


    $stmt = $conn->prepare("UPDATE issuance_tb SET issuance_number=?, division=?, date_issued=?, category=?, subject=? WHERE document_id=?");
    
    if (!$stmt) {
        http_response_code(500);
        die("SQL Error: " . $conn->error);
    }
    
    $stmt->bind_param("ssssss", $issuance_number, $division, $date_issued, $category, $subject, $document_id);
   
    if ($stmt->execute()) {
        

        if (!empty($changes_made)) {
            $username = $_SESSION['username'] ?? 'Admin'; 
            $action = "Document Edited";
            
 
            $details = "Updated " . implode(", ", $changes_made) . ".";

            $history_stmt = $conn->prepare("INSERT INTO history_tb (document_id, action_type, action_details, performed_by) VALUES (?, ?, ?, ?)");
            $history_stmt->bind_param("ssss", $document_id, $action, $details, $username);
            $history_stmt->execute();
            $history_stmt->close();
        }

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