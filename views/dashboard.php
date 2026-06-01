<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/functions.php';

$current_category = $_GET['category'] ?? '';
$current_year = $_GET['year'] ?? '';
$search_query = $_GET['search'] ?? '';

$current_sort = $_GET['sort'] ?? 'issuance_asc';

$issuances = getIssuances($conn, $current_category, $search_query, $current_year, $current_sort);
$available_years = getAvailableYears($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logbook System Dashboard</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof triggerSuccessLoad === "function") {
                    triggerSuccessLoad("<?php echo addslashes(htmlspecialchars($_SESSION['success_message'])); ?>");
                }
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="main-content" id="mainContent">
        
        <div class="top-header">
            <div class="header-left" style="display: flex; align-items: center;">
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
                
                <div class="header-branding-dynamic">
                    <img src="/logbook/assets/img/logo.png" alt="Logo" onclick="window.location.reload();" style="width: 45px; height: 45px; object-fit: contain; cursor: pointer;">
                    
                    <div style="display: flex; flex-direction: column; margin-left: 12px;">
                        <h2 style="margin: 0; font-size: 15px; font-weight: 700; line-height: 1.2;">Science Education Institute</h2>
                        <p style="margin: 3px 0 0 0; font-size: 11px; letter-spacing: 1px; color: #00A5EF; font-weight: 600;">LOGBOOK SYSTEM</p>
                    </div>
                </div>
                
                <form action="/logbook/index.php" method="GET" class="header-search" style="margin-left: 15px; display: flex; align-items: center; gap: 10px;">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($current_category ?? ''); ?>">
                    <input type="hidden" name="year" value="<?php echo htmlspecialchars($current_year ?? ''); ?>">
                    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($current_sort ?? ''); ?>">
                    
                    <input type="text" name="search" placeholder="Search subject, ID, or Issuance No..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>" style="margin: 0; min-width: 250px;">
                </form>
            </div>

            <div class="header-right">
                <button id="themeToggle" class="icon-btn" title="Toggle Dark Mode">🌙</button>
                <button onclick="showSettingsModal(event)" class="icon-btn" title="Settings"><img src="/logbook/assets/img/setting.png" alt="Settings" style="width: 30px; height: 30px;"></button>
                <div class="user-profile" onclick="window.location.href='/logbook/profile.php'">
                    <?php 
                        $sess_pic = $_SESSION['profile_picture'] ?? 'avatar.jpg';
                        $sidebar_img = ($sess_pic === 'avatar.jpg' || $sess_pic === 'avatar.png') ? '/logbook/assets/img/'.$sess_pic : '/logbook/uploads/' . $sess_pic;
                    ?>
                    <img src="<?php echo htmlspecialchars($sidebar_img); ?>" alt="Admin" class="user-avatar">
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-controls" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            
            <div style="display: flex; align-items: center;">
                <h2 class="page-title" style="margin: 0; padding-right: 25px; white-space: nowrap;">
                    <?php echo htmlspecialchars($current_category ?: "All Issuances"); ?>
                </h2>
                
                <form action="/logbook/index.php" method="GET" style="margin: 0; display: flex; align-items: center; gap: 12px; border-left: 2px solid #e0e0e0; padding-left: 25px;">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($current_category); ?>">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="hidden" name="year" value="<?php echo htmlspecialchars($current_year); ?>">
                    
                    <span style="font-size: 13px; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.5px;">Sort:</span>
                    <select name="sort" class="year-dropdown" onchange="this.form.submit()" style="cursor: pointer; padding: 8px;">
                        <option value="issuance_asc" <?php if($current_sort == 'issuance_asc' || empty($current_sort)) echo 'selected'; ?>>
                            Issuance No.
                        </option>
                          <option value="id_asc" <?php if($current_sort == 'id_asc') echo 'selected'; ?>>
                            Document ID
                        </option>
                        <option value="newest" <?php if($current_sort == 'newest') echo 'selected'; ?>>
                            Newest First
                        </option>
                    </select>
                </form>
            </div>
            
            <div class="control-actions">
                <form action="/logbook/index.php" method="GET" style="margin: 0; display: flex; gap: 15px; align-items: center;">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($current_category); ?>">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($current_sort); ?>">
                    
                    <button type="button" class="btn-add-entry" onclick="openAddEntryModal('<?php echo addslashes(htmlspecialchars($current_category)); ?>')">
                        + Add Entry
                    </button>
                    
                    <select name="year" class="year-dropdown" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        <?php if(!empty($available_years)): ?>
                            <?php foreach ($available_years as $yr): ?>
                                <option value="<?php echo htmlspecialchars($yr); ?>" <?php if($current_year == $yr) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($yr); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </form>

                <a href="print.php?category=<?php echo urlencode($current_category); ?>&year=<?php echo urlencode($current_year); ?>&search=<?php echo urlencode($search_query); ?>" class="btn-print">
                    <img src="assets/img/print.png" alt="Print">
                    <span>Print PDF</span>
                </a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-body-scroll">
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 13%;">Document ID</th>
                            <th style="text-align: center; width: 10%;">Issuance Number</th>
                            <th style="text-align: center; width: 13%; white-space: nowrap;">Date Issued</th>
                            <th style="text-align: left; width: auto;">Subject</th> 
                            <th style="text-align: center; width: 10%;">Division</th>
                            <th style="text-align: center; width: 10%;">Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($issuances)): ?>
                            <?php foreach ($issuances as $row): ?>
                                <tr onclick='showDetails(<?php echo json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' style="cursor: pointer;">
                                    <td style="text-align: center; width: 13%;"><?php echo htmlspecialchars($row['document_id']); ?></td>
                                       <td style="text-align: center; width: 10%;"><?php echo htmlspecialchars($row['issuance_number']); ?></td>
                                    <td style="text-align: center; width: 13%;"><?php echo date('m-d-Y', strtotime($row['date_issued'])); ?></td>
                                    <td style="text-align: left; width: auto;"><?php echo nl2br(htmlspecialchars($row['subject'])); ?></td>
                                    <td style="text-align: center; width: 10%;"><?php echo htmlspecialchars($row['division']); ?></td>
                                    <td style="text-align: center; width: 10%;"><?php echo htmlspecialchars($row['category']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center" style="padding: 40px; color: #ccc;">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/modals.php'; ?>

    <script src="/logbook/assets/js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>