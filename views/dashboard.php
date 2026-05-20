<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/functions.php';

$current_category = $_GET['category'] ?? '';
$current_year = $_GET['year'] ?? '';
$search_query = $_GET['search'] ?? '';

$issuances = getIssuances($conn, $current_category, $search_query, $current_year);
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
            <div class="header-left">
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
                
                <div class="header-branding-dynamic">
                    <img src="/logbook/assets/img/logo.png" alt="Logo" onclick="window.location.reload();" style="width: 45px; height: 45px; object-fit: contain; cursor: pointer;">
                    
                    <div style="display: flex; flex-direction: column; margin-left: 12px;">
                        <h2 style="margin: 0; font-size: 15px; font-weight: 700; line-height: 1.2; color: #000000;">Science Education Institute</h2>
                        <p style="margin: 3px 0 0 0; font-size: 11px; letter-spacing: 1px; color: #00A5EF; font-weight: 600;">LOGBOOK SYSTEM</p>
                    </div>
                </div>

                <form action="/logbook/index.php" method="GET" class="header-search" style="margin-left: 15px;">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($current_category ?? ''); ?>">
                    <input type="hidden" name="year" value="<?php echo htmlspecialchars($current_year ?? ''); ?>">
                    <input type="text" name="search" placeholder="Search subject, ID, or Issuance No..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
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
        
        <div class="dashboard-controls">
            <h2 class="page-title"><?php echo htmlspecialchars($current_category ?: "All Issuances"); ?></h2>
            
            <div class="control-actions">
               <form action="/logbook/index.php" method="GET" style="margin: 0;">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($current_category); ?>">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
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
                            <th class="text-center">Document ID</th>
                            <th class="text-center">Division</th>
                            <th class="text-center">Issuance Number</th>
                            <th class="text-center">Date Issued</th>
                            <th>Subject</th>
                            <th class="text-center">Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($issuances)): ?>
                            <?php foreach ($issuances as $row): ?>
                                <tr onclick='showDetails(<?php echo json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' style="cursor: pointer;">
                                    <td class="text-center"><?php echo htmlspecialchars($row['document_id']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['division']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['issuance_number']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['date_issued']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($row['subject'])); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['category']); ?></td>
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