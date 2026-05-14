<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';        
require_once __DIR__ . '/includes/functions.php'; 


if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    header("Location: /logbook/login.php");
    exit();
}

$current_year = date('Y');


$next_doc_id = generateDocumentID($conn, $current_year);
$next_issuance_full = generateIssuanceNumber($conn, $current_year);


$parts = explode('-', $next_issuance_full);
$next_issuance_num = end($parts); 

$categories = [];
$cat_query = $conn->query("SELECT category_name FROM category_tb ORDER BY category_id ASC");
if ($cat_query && $cat_query->num_rows > 0) {
    while ($row = $cat_query->fetch_assoc()) {
        $categories[] = $row['category_name'];
    }
}

$divisions = [];
$div_query = $conn->query("SELECT division_name FROM divisions_tb ORDER BY division_name ASC");
if ($div_query && $div_query->num_rows > 0) {
    while ($row = $div_query->fetch_assoc()) {
        $divisions[] = $row['division_name'];
    }
}

include __DIR__ . '/views/add_form.php';
?>