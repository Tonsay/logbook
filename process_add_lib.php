<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $document_id = trim($_POST['document_id'] ?? '');
    $division = trim($_POST['division'] ?? '');
    $category = trim($_POST['category'] ?? 'Line-Item-Budget (LIB)');
    $date_issued = trim($_POST['date_issued'] ?? '');
    $username = $_SESSION['username'] ?? 'Admin'; 
    
    $year_prefix = trim($_POST['project_year_prefix'] ?? date('Y'));
    $project_selection = trim($_POST['project_number_select'] ?? '');
    $new_project_number = trim($_POST['new_project_number'] ?? '');
    
    if (!empty($new_project_number)) {
        $project_number = $year_prefix . '-' . $new_project_number;
    } else {
        $project_number = $year_prefix . '-' . $project_selection;
    }
    
    $project_desc = trim($_POST['lib_project_desc'] ?? '');
    $start_month = trim($_POST['duration_start_month'] ?? '');
    $end_month = trim($_POST['duration_end_month'] ?? '');
    $duration_year = (int)($_POST['duration_year'] ?? date('Y'));
    
    $action_type = trim($_POST['lib_action_type'] ?? 'Original Budget');
    $action_number = trim($_POST['action_number'] ?? '');
    
    if (!empty($action_number) && $action_type === 'Amendment/Realignment') {
        $action_type = $action_number . ' - ' . $action_type; 
    }
    
    
    $raw_amount = trim($_POST['lib_amount'] ?? '0');
    $clean_amount = str_replace(',', '', $raw_amount); 
    $amount = (float)$clean_amount; 

    if (empty($document_id) || empty($project_number) || empty($date_issued)) {
        $_SESSION['error_message'] = "Please fill in all required fields.";
        header("Location: /logbook/add_issuance.php?category=" . urlencode($category));
        exit();
    }

   
    $check_stmt = $conn->prepare("SELECT document_id FROM lib_details_tb WHERE project_number = ? AND action_type = ?");
    $check_stmt->bind_param("ss", $project_number, $action_type);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
       
        $check_stmt->close();
        $_SESSION['error_message'] = "Duplicate Entry! Project Code '$project_number' already has a record for '$action_type'.";
        header("Location: /logbook/add_issuance.php?category=" . urlencode($category));
        exit();
    }
    $check_stmt->close();
   
    try {
        $conn->begin_transaction();

        $stmt_main = $conn->prepare("INSERT INTO issuance_tb (document_id, issuance_number, date_issued, subject, division, category, added_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_main->bind_param("sssssss", $document_id, $project_number, $date_issued, $project_desc, $division, $category, $username);
        
        if (!$stmt_main->execute()) {
            throw new Exception("Error saving main document: " . $stmt_main->error);
        }

        $stmt_lib = $conn->prepare("INSERT INTO lib_details_tb (document_id, project_number, project_desc, start_month, end_month, duration_year, action_type, amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      
        $stmt_lib->bind_param("sssssisd", $document_id, $project_number, $project_desc, $start_month, $end_month, $duration_year, $action_type, $amount);
        
        if (!$stmt_lib->execute()) {
            throw new Exception("Error saving financial details: " . $stmt_lib->error);
        }

        $stmt_hist = $conn->prepare("INSERT INTO history_tb (document_id, action_type, performed_by, action_details) VALUES (?, ?, ?, ?)");
        $hist_action = "Added Entry";
        $hist_details = "Created Line-Item-Budget for Project: " . $project_number;
        $stmt_hist->bind_param("ssss", $document_id, $hist_action, $username, $hist_details);
        
        if (!$stmt_hist->execute()) {
            throw new Exception("Error saving history log: " . $stmt_hist->error);
        }

        $conn->commit();

        $_SESSION['success_message'] = "LIB transaction saved successfully!";
        header("Location: /logbook/index.php?category=" . urlencode($category));
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: /logbook/add_issuance.php?category=" . urlencode($category));
        exit();
    }
} else {
    header("Location: /logbook/index.php");
    exit();
}
?>