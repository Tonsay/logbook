<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Reports - Logbook System</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
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
                        <h2 style="margin: 0; font-size: 15px; font-weight: 700; line-height: 1.2; color: #000000;">Science Education Institute</h2>
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

        <div style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 25px; align-items: stretch; margin-top: 20px;">
    
            <div style="width: 100%; background: #b9e6ff ; padding: 30px; border-radius: 16px; border: 1px solid rgba(0, 165, 239, 0.3); text-align: center; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; justify-content: center;">
                <h3 style="margin: 0; color: #00A5EF; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">
                    Total Documents <?php echo empty($current_year) ? '(All Time)' : "($current_year)"; ?>
                </h3>
                <p style="margin: 15px 0 0 0; color: #00A5EF; font-size: 64px; font-weight: bold; line-height: 1;">
                    <?php echo $total_documents; ?>
                </p>
            </div>
            
            <?php if (!empty($category_counts)): ?>
                <?php foreach ($category_counts as $cat_name => $count): ?>
                    <div style="flex: 1; min-width: 200px; background: #b9e6ff; padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: transform 0.2s; display: flex; flex-direction: column; justify-content: center;">
                        <h3 style="margin: 0; color: #00A5EF; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <?php echo htmlspecialchars($cat_name); ?>
                        </h3>
                        <p style="margin: 15px 0 0 0; color: #00A5EF; font-size: 48px; font-weight: bold;">
                            <?php echo $count; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="width: 100%; text-align: center; padding: 50px; color: #aaa; background: #1e293b; border-radius: 12px;">
                    No documents found for this year.
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php include dirname(__DIR__) . '/includes/modals.php'; ?>
    <script src="/logbook/assets/js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>