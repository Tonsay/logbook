<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New LIB Entry</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div id="errorToastUI" style="position: fixed; top: 20px; right: 20px; background: #fff0f0; border-left: 6px solid #ff4d4d; color: #d32f2f; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); z-index: 9999; font-weight: 600; display: flex; align-items: center; gap: 12px; font-family: 'Plus Jakarta Sans', sans-serif;">
            <span style="font-size: 20px;">⚠️</span>
            <span><?php echo htmlspecialchars($_SESSION['error_message']); ?></span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('errorToastUI');
                if(toast) {
                    toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(20px)';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 5000);
        </script>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php 
    
    $existing_projects = [];
    if (isset($conn)) {
        $proj_query = $conn->query("SELECT project_number, project_desc FROM lib_details_tb GROUP BY project_number ORDER BY project_number DESC");
        
        if ($proj_query && $proj_query->num_rows > 0) {
            while ($row = $proj_query->fetch_assoc()) {
                $full_num = $row['project_number'];
              
                $parts = explode('-', $full_num, 2);
                if (count($parts) === 2) {
                    $suffix = $parts[1]; 
                    $existing_projects[$suffix] = $row['project_desc'];
                }
            }
        }
    }

    $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    ?>

    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

<div class="main-content" id="mainContent">
    <div class="top-header"> 
        <div class="header-left">
            <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
            <div class="header-branding-dynamic">
                <img src="/logbook/assets/img/logo.png" alt="Logo" style="width: 45px; height: 45px; object-fit: contain;">                
                <div style="display: flex; flex-direction: column; margin-left: 12px;">
                    <h2 style="margin: 0; font-size: 15px; font-weight: 700;">Science Education Institute</h2>
                    <p style="margin: 3px 0 0 0; font-size: 11px; color: #00A5EF; font-weight: 600;">LOGBOOK SYSTEM</p>
                </div>
            </div>

            <h2 style="margin: 0 0 0 20px; font-size: 20px; color: #00A5EF; font-weight: 700;">
                Line-Item-Budget (LIB) 
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
            <h2>Line Item Budget (LIB) Details</h2>
    
            <form action="/logbook/process_add_lib.php" method="POST" onsubmit="if(!this.checkValidity()){ return false; } showLoader();">
                <input type="hidden" name="category" value="Line-Item-Budget (LIB)">

                <div class="form-row">
                    <div class="form-group">
                        <label>Document ID</label>
                        <input type="text" name="document_id" value="<?php echo htmlspecialchars($next_doc_id ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <select name="division" required>
                            <option value="" disabled selected>-- Select Division --</option>
                            <?php if (!empty($divisions)): ?>
                                <?php foreach ($divisions as $div): ?>
                                    <option value="<?php echo htmlspecialchars($div); ?>"><?php echo htmlspecialchars($div); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Project Code</label>
                        <div style="display: flex; gap: 10px; align-items: flex-start;">
                            <input type="text" name="project_year_prefix" id="year_prefix" value="<?php echo date('Y'); ?>" readonly style="width: 80px; padding: 12px; background: rgba(0,0,0,0.05); text-align: center; border: 1px solid #ccc; border-radius: 8px; font-weight: bold; color: #555; height: 45px; box-sizing: border-box; outline: none;">

                            <div style="flex: 1;">
                                <select name="project_number_select" id="projectSelector" required onchange="autoFillProject()" style="width: 100%; height: 45px; border-radius: 8px; border: 1px solid #ccc; padding: 0 12px; box-sizing: border-box; outline: none;">
                                    <option value="" disabled selected>-- Select Project Code --</option>
                                    <?php foreach ($existing_projects as $suffix => $desc): ?>
                                        <option value="<?php echo htmlspecialchars($suffix); ?>" data-desc="<?php echo htmlspecialchars($desc); ?>">
                                            <?php echo htmlspecialchars($suffix); ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="NEW" style="font-weight: bold; color: #00A5EF;">+ Create New Project Code</option>
                                </select>

                                <input type="text" name="new_project_number" id="newProjectInput" placeholder="Format: XX-XX-XX" style="display: none; margin-top: 10px; width: 100%; padding: 12px; border: 1px solid #00A5EF; border-radius: 8px; box-sizing: border-box; box-shadow: 0 0 5px rgba(0,165,239,0.3); outline: none;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date Issued</label>
                        <input type="text" name="date_issued" id="date_issued_input" class="custom-date-picker" placeholder="YYYY-MM-DD" required style="width: 100%; box-sizing: border-box; padding: 12px 45px 12px 12px; border-radius: 8px; border: 1px solid #ccc; background-position: right 15px center; background-repeat: no-repeat; outline: none;">
                   </div>
                </div>

                <div class="form-group">
                    <label>Project Description</label>
                    <textarea name="lib_project_desc" id="projectDesc" placeholder="Select a project number above to auto-fill details..." required style="background: rgba(0,0,0,0.02);"></textarea>
                </div>

                <hr style="border: none; border-top: 1px dashed #ccc; margin: 25px 0;">
                <h3 style="margin-top: 0; color: #333; font-size: 16px;">Project Duration</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Start Month</label>
                        <select name="duration_start_month" required>
                            <option value="" disabled selected>-- Start Month --</option>
                            <?php foreach($months as $month): ?>
                                <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>End Month</label>
                        <select name="duration_end_month" required>
                            <option value="" disabled selected>-- End Month --</option>
                            <?php foreach($months as $month): ?>
                                <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Year</label>
                        <select name="duration_year" required>
                            <option value="" disabled>-- Year --</option>
                            <?php 
                            $current_y = date('Y');
                            for($y = $current_y - 1; $y <= $current_y + 3; $y++): 
                                $selected = ($y == $current_y) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $y; ?>" <?php echo $selected; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px dashed #ccc; margin: 25px 0;">
                <h3 style="margin-top: 0; color: #333; font-size: 16px;">Financial Details</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Budget Action</label>
                        <select name="lib_action_type" id="lib_action_type" required style="font-weight: bold; color: #00A5EF;" onchange="toggleActionNumber()">
                            <option value="" disabled selected>-- Select Action --</option>
                            <option value="Original Budget">Original Budget</option>
                            <option value="Amendment/Realignment">Amendment/Realignment</option>
                        </select>

                        <div id="action_number_container" style="display: none; margin-top: 10px; background: rgba(0,165,239,0.05); padding: 10px; border-radius: 8px; border: 1px dashed #00A5EF;">
                            <label style="font-size: 11px; font-weight: bold; color: #00A5EF; display: block; margin-bottom: 8px; text-transform: uppercase;">Specify Order:</label>
                            <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                                <label style="font-size: 13px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="1st"> 1st
                                </label>
                                <label style="font-size: 13px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="2nd"> 2nd
                                </label>
                                <label style="font-size: 13px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="3rd"> 3rd
                                </label>
                                <label style="font-size: 13px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="4th"> 4th
                                </label>
                                <label style="font-size: 13px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="5th"> 5th
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Budget Amount (Php)</label>
                        <input type="text" name="lib_amount" placeholder="0.00" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-confirm" style="width: 100%; margin-top: 20px;">Save Line-Item-Budget</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/modals.php'; ?>
<script src="/logbook/assets/js/app.js?v=<?php echo time(); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</body>
</html>