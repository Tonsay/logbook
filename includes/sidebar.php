<?php
$sidebar_categories = [];
$sidebar_total = 0;
$sidebar_cat_counts = [];

if (isset($conn)) {
   
    $cat_result = $conn->query("SELECT category_name FROM category_tb ORDER BY category_id ASC");
    if ($cat_result && $cat_result->num_rows > 0) {
        while ($row = $cat_result->fetch_assoc()) {
            $sidebar_categories[] = $row['category_name'];
        }
    }

    $filter_year = $_GET['year'] ?? '';

    $sidebar_total_query = "SELECT COUNT(*) AS total_docs FROM issuance_tb";
    if (!empty($filter_year)) {
        $escaped_year = mysqli_real_escape_string($conn, $filter_year);
        $sidebar_total_query .= " WHERE YEAR(date_issued) = '$escaped_year'";
    }
    
    $sidebar_total_result = mysqli_query($conn, $sidebar_total_query);
    $sidebar_total = ($sidebar_total_result) ? mysqli_fetch_assoc($sidebar_total_result)['total_docs'] : 0;

    $sidebar_cat_query = "SELECT category, COUNT(*) as total FROM issuance_tb";
    if (!empty($filter_year)) {
        $sidebar_cat_query .= " WHERE YEAR(date_issued) = '$escaped_year'";
    }
    $sidebar_cat_query .= " GROUP BY category ORDER BY category ASC";

    $sidebar_cat_result = mysqli_query($conn, $sidebar_cat_query);
    if ($sidebar_cat_result && mysqli_num_rows($sidebar_cat_result) > 0) {
        while ($row = mysqli_fetch_assoc($sidebar_cat_result)) {
            $sidebar_cat_counts[$row['category']] = $row['total'];
        }
    }
}
?>

<style>
    * {
        scrollbar-width: none !important; 
        -ms-overflow-style: none !important;  
    }

    *::-webkit-scrollbar {
        display: none !important; 
        width: 0 !important;
        height: 0 !important;
    }

    #mySidebar {
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    .main-content, .table-body-scroll {
        overflow-y: auto !important;
        scrollbar-width: none !important;
    }
    .sidebar-icon {
    width: 26px;          
    height: 26px;          
    object-fit: contain;
    margin-right: 10px;
    vertical-align: middle;
    display: inline-block;
}
.superadmin-icon {
    width: 15px;          
    height: 15px;          
    object-fit: contain;
    margin-right: 8px;
    vertical-align: -3px; 
    display: inline-block;
}
</style>

<div class="sidebar" id="mySidebar">
    <div class="sidebar-brand">
        <img src="/logbook/assets/img/logo.png" alt="Logo" onclick="window.location.reload();" style="width: 45px; height: 45px; object-fit: contain; cursor: pointer;">
        <div class="brand-text">
            <h2>Science Education Institute</h2>
            <p>LOGBOOK SYSTEM</p>
        </div>
    </div> 

    <div class="sidebar-nav">
        <div class="sidebar-nav-title"style="color: #00A5EF;">Categories</div>
        <a href="index.php" class="nav-link <?php if(empty($current_category)) echo 'active'; ?>">View All</a>
        
        <?php foreach ($sidebar_categories as $cat_name): ?>
            <a href="index.php?category=<?php echo urlencode($cat_name); ?>" class="nav-link <?php if(($current_category ?? '') == $cat_name) echo 'active'; ?>">
                <?php echo htmlspecialchars($cat_name); ?>
            </a>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin'): ?>

            <div class="sidebar-nav-title" style="margin-top: 25px; color: #00A5EF;">
            <img src="/logbook/assets/img/superadmin1.png" class= "superadmin-icon" alt="Crown"> Admin Controls
    </div>
    <a href="manage_admins.php" class="nav-link">
        <img src="/logbook/assets/img/admin.png" class="sidebar-icon" alt="Admins"> Manage Admins
    </a>
    <a href="manage_categories.php" class="nav-link">
        <img src="/logbook/assets/img/issuances.png" class="sidebar-icon" alt="Folders"> Manage Issuances
    </a> 
    <a href="manage_division.php" class="nav-link">
        <img src="/logbook/assets/img/divisions.png" class="sidebar-icon" alt="Charts"> Manage Divisions
    </a>
        <?php endif; ?>
        <a href="/logbook/reports.php" class="nav-link">
        <img src="/logbook/assets/img/count.png" class="sidebar-icon" alt="Charts"> Document Reports</a>
        <a href="add_issuance.php" class="btn-add" style="margin-top: 20px;">+ Add New Entry</a>
    </div>
    
    <div class="sidebar-footer">
        <a href="#" class="btn-logout" onclick="showLogoutModal(event)">Log Out</a>
    </div>
</div>

