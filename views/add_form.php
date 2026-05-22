<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Logbook Entry</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof triggerSuccessLoad === "function") {
                    triggerSuccessLoad("<?php echo addslashes(htmlspecialchars($_SESSION['success_message'])); ?>");
                } else {
                    console.error("Success loader function still missing. Check app.js!");
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
                    <h2 style="margin: 0; font-size: 15px; font-weight: 700; line-height: 1.2;">Science Education Institute</h2>
                    <p style="margin: 3px 0 0 0; font-size: 11px; letter-spacing: 1px; color: #00A5EF; font-weight: 600;">LOGBOOK SYSTEM</p>
                </div>
            </div>

            <h2 style="margin: 0 0 0 20px; font-size: 20px; color: #00A5EF; font-weight: 700;">
                    <img src="/logbook/assets/img/admin.png" class="sidebar-icon" alt="Entry" style="width: 40px; height: 40px; vertical-align: middle;"> Add Issuance
            </h2>
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
                    <h4><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin User'); ?></h4>
                </div>
            </div>
        </div>
    </div> 

    <div class="form-wrapper">
        <div class="form-container">
            <h2>Logbook Details</h2>
            
         
            <?php if (isset($_SESSION['error_message'])): ?>
                <div style="background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #ff6b6b; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold;">
                    ⚠️ <?php echo $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
     
            <form action="/logbook/process_add.php" method="POST" onsubmit="showLoader()">
                <div class="form-row">
                    <div class="form-group">
                        <label>Document ID</label>
                        <input type="text" id="preview_doc_id" name="document_id" value="<?php echo htmlspecialchars($next_doc_id); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                          <select name="division" required>
                        <option value="" disabled selected>-- Select Division --</option>
                        <?php if (!empty($divisions)): ?>
                            <?php foreach ($divisions as $div): ?>
                                <option value="<?php echo htmlspecialchars($div); ?>" style="color: white;">
                                    <?php echo htmlspecialchars($div); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled style="color: red;">No divisions found</option>
                        <?php endif; ?>
                        
                    </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Issuance Number</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="year_prefix" name="year_prefix" style="width: 90px; text-align: center; font-weight: bold; background: rgba(0, 0, 0, 0.2);" value="<?php echo date('Y'); ?>" readonly>
                            <span style="color: white; font-weight: bold; font-size: 1.2rem;">-</span>
                            <input type="text" id="issuance_num_only" name="issuance_num_only" value="<?php echo htmlspecialchars($next_issuance_num); ?>" required maxlength="5" pattern="[0-9]{3}(-[a-zA-Z])?" title="Enter 3 numbers, optionally followed by a dash and a letter (e.g., 001 or 001-A)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date Issued</label>
                        <input type="date" id="date_issued_input" name="date_issued" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="" disabled selected>-- Select Type of Document --</option>
                        
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" style="color: white;">
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled style="color: red;">No categories found</option>
                        <?php endif; ?>
                        
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Subject</label>
                    <textarea name="subject" placeholder="Enter the subject here..." 
          oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                </div>
                
                <button type="submit" class="btn-confirm" style="width: 100%;">Save to Logbook</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/modals.php'; ?>

<script src="/logbook/assets/js/app.js?v=<?php echo time(); ?>"></script>

</body>
</html>