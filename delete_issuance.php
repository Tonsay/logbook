<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    header("Location: /logbook/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $doc_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM issuance_tb WHERE document_id = ?");
    $stmt->bind_param("s", $doc_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Document '$doc_id' deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete the document.";
    }
    $stmt->close();
}


header("Location: /logbook/index.php");
exit();
?>