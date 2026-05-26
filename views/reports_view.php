<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Reports - Logbook System</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php include dirname(__DIR__) . '/includes/sidebar.php'; ?>

    <div class="main-content" id="mainContent">
        
        <div class="top-header">
            <div class="header-left">
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
                <div class="header-branding-dynamic">
                <img src="/logbook/assets/img/logo.png" alt="Logo" onclick="window.location.reload();" style="width: 45px; height: 45px; object-fit: contain; cursor: pointer;">                  
                 <div style="display: flex; flex-direction: column; margin-left: 12px;">
                        <h2 style="margin: 0; font-size: 15px; font-weight: 700; line-height: 1.2;">Science Education Institute</h2>
                        <p style="margin: 3px 0 0 0; font-size: 11px; letter-spacing: 1px; color: #00A5EF; font-weight: 600;">LOGBOOK SYSTEM</p>
                    </div>
                </div>
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
        
        <div class="dashboard-controls" style="margin-bottom: 30px;">
            <h2 class="page-title"> Document Reports</h2>
            
            <div class="control-actions">
               <form action="/logbook/reports.php" method="GET" style="margin: 0;">
                    <select name="year" class="year-dropdown" onchange="this.form.submit()" style="padding: 10px 15px; font-size: 16px;">
                        <option value="">All-Time Records</option>
                        <?php if(!empty($available_years)): ?>
                            <?php foreach ($available_years as $yr): ?>
                                <option value="<?php echo htmlspecialchars($yr); ?>" <?php if($current_year == $yr) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($yr); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="reports-container" style="width: 100%; display: flex; justify-content: center; margin-top: 30px;">
            <div class="minimal-card">
                
                <div class="minimal-title">Document Types</div>
                
                <ul class="minimal-list">
                    <?php if (!empty($category_counts)): ?>
                        <?php foreach ($category_counts as $cat_name => $count): ?>
                            <li class="minimal-item">
                                <span class="minimal-name"><?php echo htmlspecialchars($cat_name); ?></span>
                                <span class="minimal-count"><?php echo htmlspecialchars($count); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="minimal-item" style="justify-content: center; padding: 20px 0;">
                            <span class="minimal-name" style="font-style: italic;">No records found.</span>
                        </li>
                    <?php endif; ?>

                    <li class="minimal-item minimal-total-row">
                        <span class="minimal-name">Total Documents <?php echo empty($current_year) ? '' : "($current_year)"; ?></span>
                        <span class="minimal-count"><?php echo $total_documents; ?></span>
                    </li>
                </ul>

            </div>
        </div>
        
    </div>

    <?php include dirname(__DIR__) . '/includes/modals.php'; ?>
    <script src="/logbook/assets/js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>