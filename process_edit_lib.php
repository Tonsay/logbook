<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $document_id = trim($_POST['document_id'] ?? '');
    $date_issued = trim($_POST['date_issued'] ?? '');
    $project_desc = trim($_POST['lib_project_desc'] ?? '');
    
    $start_month = trim($_POST['duration_start_month'] ?? '');
    $end_month = trim($_POST['duration_end_month'] ?? '');
    $duration_year = (int)($_POST['duration_year'] ?? date('Y'));
    
    $action_type = trim($_POST['lib_action_type'] ?? '');
    $action_number = trim($_POST['action_number'] ?? '');
    
   
    if (!empty($action_number) && $action_type === 'Amendment/Realignment') {
        $action_type = $action_number . ' - ' . $action_type; 
    }

    $raw_amount = trim($_POST['lib_amount'] ?? '0');
    $clean_amount = str_replace(',', '', $raw_amount); 
    $amount = (float)$clean_amount; 

    
    $category = trim($_POST['category'] ?? 'Line-Item-Budget (LIB)');
    $username = $_SESSION['username'] ?? 'Admin'; 

    if (empty($document_id) || empty($date_issued)) {
        $_SESSION['error_message'] = "Error: Missing required fields.";
        header("Location: /logbook/index.php?category=" . urlencode($category));
        exit();
    }

    try {
        $conn->begin_transaction();

        $stmt_main = $conn->prepare("UPDATE issuance_tb SET date_issued = ?, subject = ? WHERE document_id = ?");
        $stmt_main->bind_param("sss", $date_issued, $project_desc, $document_id);
        
        if (!$stmt_main->execute()) {
            throw new Exception("Error updating main document: " . $stmt_main->error);
        }

        $stmt_lib = $conn->prepare("UPDATE lib_details_tb SET project_desc = ?, start_month = ?, end_month = ?, duration_year = ?, action_type = ?, amount = ? WHERE document_id = ?");
       
        $stmt_lib->bind_param("sssisds", $project_desc, $start_month, $end_month, $duration_year, $action_type, $amount, $document_id);
        
        if (!$stmt_lib->execute()) {
            throw new Exception("Error updating financial details: " . $stmt_lib->error);
        }

        $stmt_hist = $conn->prepare("INSERT INTO history_tb (document_id, action_type, performed_by, action_details) VALUES (?, ?, ?, ?)");
        $hist_action = "Edited Document";
        $hist_details = "Updated Financial details and Project timeline.";
        $stmt_hist->bind_param("ssss", $document_id, $hist_action, $username, $hist_details);
        
        if (!$stmt_hist->execute()) {
            throw new Exception("Error saving history log: " . $stmt_hist->error);
        }

        $conn->commit();

        $_SESSION['success_message'] = "Line-Item-Budget updated successfully!";
        header("Location: /logbook/index.php?category=" . urlencode($category));
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: /logbook/index.php?category=" . urlencode($category));
        exit();
    }
} else {
    header("Location: /logbook/index.php");
    exit();
}
?>