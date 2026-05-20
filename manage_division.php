<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: index.php");
    exit();
}

$error = '';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $stmt = $conn->prepare("DELETE FROM divisions_tb WHERE division_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Division deleted successfully!";
        header("Location: manage_division.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_division'])) {
    $new_division = trim($_POST['division_name']);
    
    $check = $conn->prepare("SELECT division_id FROM divisions_tb WHERE division_name = ?");
    $check->bind_param("s", $new_division);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        $error = "This division already exists!";
    } else {
        $insert = $conn->prepare("INSERT INTO divisions_tb (division_name) VALUES (?)");
        $insert->bind_param("s", $new_division);
        if ($insert->execute()) {
            $_SESSION['success_message'] = "Division '$new_division' added successfully!";
            header("Location: manage_division.php");
            exit();
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_division_btn'])) {
    $div_id = $_POST['division_id'];
    $old_name = $_POST['old_division_name'];
    $new_name = trim($_POST['division_name']);

    $stmt1 = $conn->prepare("UPDATE divisions_tb SET division_name=? WHERE division_id=?");
    $stmt1->bind_param("si", $new_name, $div_id);
    
    if ($stmt1->execute()) {
        
        $stmt2 = $conn->prepare("UPDATE issuance_tb SET division=? WHERE division=?");
        if ($stmt2) {
            $stmt2->bind_param("ss", $new_name, $old_name);
            $stmt2->execute();
            $stmt2->close();
        }
        
        $_SESSION['success_message'] = "Division updated successfully!";
        header("Location: manage_division.php");
        exit();
    } else {
        $error = "Error updating division!";
    }
}

$all_divisions = $conn->query("SELECT * FROM divisions_tb ORDER BY division_name ASC")->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/views/manage_divisions_view.php';
?>