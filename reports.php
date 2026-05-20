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


$current_year = $_GET['year'] ?? '';
$available_years = getAvailableYears($conn);


$total_query = "SELECT COUNT(*) AS total_docs FROM issuance_tb";
if (!empty($current_year)) {
    $escaped_year = mysqli_real_escape_string($conn, $current_year);
    $total_query .= " WHERE YEAR(date_issued) = '$escaped_year'";
}
$total_result = mysqli_query($conn, $total_query);
$total_documents = ($total_result) ? mysqli_fetch_assoc($total_result)['total_docs'] : 0;


$category_counts = [];
$cat_query = "SELECT category, COUNT(*) as total FROM issuance_tb";
if (!empty($current_year)) {
    $cat_query .= " WHERE YEAR(date_issued) = '$escaped_year'";
}
$cat_query .= " GROUP BY category ORDER BY category ASC";

$cat_result = mysqli_query($conn, $cat_query);
if ($cat_result && mysqli_num_rows($cat_result) > 0) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $category_counts[$row['category']] = $row['total'];
    }
}


require_once __DIR__ . '/views/reports_view.php';
?>