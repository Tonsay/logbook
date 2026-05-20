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
    $stmt = $conn->prepare("DELETE FROM category_tb WHERE category_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Category deleted successfully!";
        header("Location: manage_categories.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $new_category = trim($_POST['category_name']);
    
    $check = $conn->prepare("SELECT category_id FROM category_tb WHERE category_name = ?");
    $check->bind_param("s", $new_category);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        $error = "This category already exists!";
    } else {
        $insert = $conn->prepare("INSERT INTO category_tb (category_name) VALUES (?)");
        $insert->bind_param("s", $new_category);
        if ($insert->execute()) {
            $_SESSION['success_message'] = "Category '$new_category' added successfully!";
            header("Location: manage_categories.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category_btn'])) {
    $cat_id = $_POST['category_id'];
    $old_name = $_POST['old_category_name'];
    $new_name = trim($_POST['category_name']);

    $stmt1 = $conn->prepare("UPDATE category_tb SET category_name=? WHERE category_id=?");
    $stmt1->bind_param("si", $new_name, $cat_id);
    
    if ($stmt1->execute()) {
    
        $stmt2 = $conn->prepare("UPDATE issuance_tb SET category=? WHERE category=?");
        if ($stmt2) {
            $stmt2->bind_param("ss", $new_name, $old_name);
            $stmt2->execute();
            $stmt2->close();
        }
        
        $_SESSION['success_message'] = "Category updated successfully!";
        header("Location: manage_categories.php");
        exit();
    } else {
        $error = "Error updating category!";
    }
}

$all_categories = $conn->query("SELECT * FROM category_tb ORDER BY category_id ASC")->fetch_all(MYSQLI_ASSOC);


require_once __DIR__ . '/views/manage_categories_view.php';
?>