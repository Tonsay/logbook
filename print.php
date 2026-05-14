<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';
require_once 'includes/functions.php';

$current_category = $_GET['category'] ?? '';
$current_year = $_GET['year'] ?? '';     
$search_query = $_GET['search'] ?? '';  

$issuances = getIssuances($conn, $current_category, $search_query, $current_year);


if (!empty($issuances)) {
    usort($issuances, function($a, $b) {
        return $a['issuance_number'] <=> $b['issuance_number'];
    });
}

include 'views/print_view.php';
?>