<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';


if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    header("Location: /logbook/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $date_issued = trim($_POST['date_issued']);
    $category    = trim($_POST['category']);
    $subject     = trim($_POST['subject']);
    $division    = trim($_POST['division']); 

    $selected_year = date('Y', strtotime($date_issued));
    $num_only = trim($_POST['issuance_num_only']);
    $issuance_number = $selected_year . '-' . $num_only;
    $added_by = $_SESSION['username'] ?? 'Admin';
    $doc_id = generateDocumentID($conn, $selected_year);

    $sql = "INSERT INTO issuance_tb (document_id, division, issuance_number, date_issued, category, subject, added_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
      

$stmt->bind_param("sssssss", $doc_id, $division, $issuance_number, $date_issued, $category, $subject, $added_by);       
        try {
            
            $stmt->execute(); 
            
           
            $_SESSION['success_message'] = "New entry added successfully!";
            header("Location: /logbook/index.php");
            exit();
            
        } catch (mysqli_sql_exception $e) {
           
            if ($e->getCode() == 1062) {
                $_SESSION['error_message'] = "Error: The entry 22already exists!";
            } else {
                $_SESSION['error_message'] = "Database Error: " . $e->getMessage();
            }
            
           
            header("Location: /logbook/add_issuance.php");
            exit();
        }
       

        $stmt->close();
    } else {
        die("Prepare Error: " . $conn->error);
    }
} else {
    header("Location: /logbook/add_issuance.php");
    exit();
}
?>