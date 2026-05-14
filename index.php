<?php
session_start();


require_once 'includes/db.php';        
require_once 'includes/functions.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: landing.php");
    exit();
}


$current_category = $_GET['category'] ?? '';
$current_year     = $_GET['year'] ?? '';
$search_query     = $_GET['search'] ?? '';


$issuances = getIssuances($conn, $current_category, $search_query, $current_year);
$available_years = getAvailableYears($conn);

include 'views/dashboard.php';
?>