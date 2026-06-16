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

$clicked_category = trim($_GET['category'] ?? ''); 
$suggested_number = '001'; 

if (!empty($clicked_category)) {
    $stmt = $conn->prepare("SELECT issuance_number FROM issuance_tb WHERE category = ?");
    $stmt->bind_param("s", $clicked_category);
} else {
    $stmt = $conn->prepare("SELECT issuance_number FROM issuance_tb");
}

$stmt->execute();
$result = $stmt->get_result();

$existing_numbers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $val = trim($row['issuance_number']);
        $parts = explode('-', $val);
        
        if (isset($parts[1]) && is_numeric($parts[1])) {
            $existing_numbers[] = (int)$parts[1];
        } elseif (is_numeric($val)) {
            $existing_numbers[] = (int)$val;
        }
    }
}
$stmt->close(); 
if (!empty($existing_numbers)) {
    sort($existing_numbers); 
    
    $next_num = 1;
    foreach ($existing_numbers as $num) {
        if ($num == $next_num) {
            $next_num++; 
        } elseif ($num > $next_num) {
            break; 
        }
    }
    $suggested_number = str_pad($next_num, 3, '0', STR_PAD_LEFT);
} else {
    $suggested_number = '001';
}


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


$check_cat = strtolower(trim($clicked_category));

if (strpos($check_cat, 'lib') !== false || strpos($check_cat, 'line-item') !== false) {
    include __DIR__ . '/views/add_form_lib.php';
} else {
    include __DIR__ . '/views/add_form.php';
}
?>