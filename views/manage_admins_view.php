<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins - DOST-SEI Logbook</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .custom-tabs { margin-bottom: 0 !important; }
        .tab-link { border-bottom-left-radius: 0 !important; border-bottom-right-radius: 0 !important; }
        .tab-content .table-container,
        .tab-content .form-container {
            border-top-left-radius: 0 !important;
            margin-top: 0 !important;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            transition: background-color 5000s ease-in-out 0s;
            -webkit-text-fill-color: #ffffff !important;
        }

        .btn-delete-admin {
            background: #ff4d4d;
            color: #ffffff;
            border: 1px solid rgba(255, 77, 77, 0.2);
            padding: 6px 15px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }
        .btn-delete-admin:hover {
            background: #ff4d4d;
            color: white;
        }

        .dark-modal-box {
            background: #1e293b !important; 
            color: #f8fafc !important; 
            border: 1px solid #334155;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
        }
        .dark-modal-box h3 { color: #00A5EF !important; border-bottom-color: #334155 !important; }
        .dark-modal-box p, .dark-modal-box .detail-label { color: #94a3b8 !important; }
        .dark-modal-box .detail-value { color: #f8fafc !important; }
        .dark-modal-box .detail-row { border-bottom: 1px solid #334155 !important; }
        .dark-modal-box label { color: #e2e8f0 !important; }
        .dark-modal-box input { background-color: #0f172a !important; color: #f8fafc !important; border: 1px solid #334155 !important; }
        .dark-modal-box input:focus { border-color: #00A5EF !important; outline: none; }
        .dark-modal-box .password-toggle { color: #94a3b8 !important; }
        .dark-modal-box .password-toggle:hover { color: #00A5EF !important; }
      
        /* buttons */
        .btn-table-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-view-style {
            background: rgba(0, 165, 239, 0.15);
            color: #00A5EF;
            border-color: rgba(0, 165, 239, 0.2);
        }
        .btn-view-style img {
            width: 18px;
            filter: invert(48%) sepia(87%) saturate(2335%) hue-rotate(178deg) brightness(97%) contrast(101%) !important;
        }

        .btn-edit-style {
            background: rgba(241, 196, 15, 0.15);
            color: #f1c40f;
            border-color: rgba(241, 196, 15, 0.2);
        }
        .btn-edit-style img {
            width: 18px;
            filter: invert(81%) sepia(61%) saturate(913%) hue-rotate(357deg) brightness(101%) contrast(92%) !important;
        }

        .btn-delete-style {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
            border-color: rgba(231, 76, 60, 0.2);
        }
        .btn-delete-style img {
            width: 18px;
            filter: invert(39%) sepia(82%) saturate(3015%) hue-rotate(340deg) brightness(93%) contrast(98%) !important;
        }


        .btn-table-action:hover { color: white !important; }
        .btn-view-style:hover { background: #00A5EF; }
        .btn-edit-style:hover { background: #f1c40f; }
        .btn-delete-style:hover { background: #e74c3c; }

        .btn-table-action:hover img {
            filter: brightness(0) invert(1) !important;
        }
    </style>
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

    <?php if ($error): ?>
        <div id="errorNotification" class="toast-popup" style="background-color: #dc3545;">❌ <?php echo $error; ?></div>
        <script>setTimeout(() => { const t = document.getElementById("errorNotification"); if(t){t.style.opacity="0"; setTimeout(()=>t.remove(),500);} }, 3000);</script>
    <?php endif; ?>

    <?php include dirname(__DIR__) . '/includes/sidebar.php'; ?>

    <div class="main-content" id="mainContent">
        
        <div class="top-header">
            <div class="header-left" style="display: flex; align-items: center;">
                <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
                <div class="header-branding-dynamic">
<img src="/logbook/assets/img/logo.png" alt="Logo" onclick="window.location.reload();" style="width: 45px; height: 45px; object-fit: contain; cursor: pointer;">                    <div style="display: flex; flex-direction: column; margin-left: 12px;">
                        <h2 style="margin: 0; font-size: 15px; font-weight: 700; color: #ffffff;">Science Education Institute</h2>
                        <p style="margin: 3px 0 0 0; font-size: 11px; color: #b9e6ff;">LOGBOOK SYSTEM</p>
                    </div>
                </div>
                <h2 style="margin: 0 0 0 20px; font-size: 20px; color: #ffffff; font-weight: 700;"><img src="/logbook/assets/img/admin.png" class="sidebar-icon" alt="Admins" style="width: 34px; height: 34px;"> Manage System Admins</h2>
            </div>

            <div class="header-right">
                <button id="themeToggle" class="icon-btn">🌙</button>
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

        <div class="custom-tabs" style="display: flex; justify-content: flex-start; width: 100%; margin-top: 10px;">
            <button class="tab-link active" onclick="switchTab(event, 'viewAdmins')">Admin List</button>
            <button class="tab-link" onclick="switchTab(event, 'addAdmin')">Add New Admin</button>
        </div>

        <div id="viewAdmins" class="tab-content">
            <div class="table-container" style="width: 100%;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_users as $u): ?>
                            <tr>
                                <td style="color: #b9e6ff; font-weight: bold;">#<?php echo htmlspecialchars($u['user_id']); ?></td>
                                <td>
                                    <strong class="clickable-name" style="color: #ffffff; cursor: pointer; transition: 0.2s;" onmouseover="this.style.color='#00A5EF'" onmouseout="this.style.color='#ffffff'" onclick="openDetailsModal('<?php echo $u['user_id']; ?>', '<?php echo addslashes($u['username']); ?>', '<?php echo $u['role']; ?>')">
                                        <?php echo htmlspecialchars($u['username']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php if($u['role'] === 'superadmin'): ?>
                            <span style="display: inline-flex; align-items: center; background: rgba(241, 196, 15, 0.2); color: #f1c40f; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; line-height: 1;">
                                <img src="assets/img/superadmin.png" style="width: 15px; height: auto; margin-right: 6px;">
                                Superadmin
                            </span> <?php else: ?>
<span style="display: inline-flex; align-items: center; background: rgba(0, 165, 239, 0.2); color: #00A5EF; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; line-height: 1;">
    <img src="assets/img/admin1.png" style="width: 14px; height: auto; margin-right: 6px;"> 
    Admin
</span>                                    <?php endif; ?>
                              <td style="text-align: center;">
    <div style="display: flex; gap: 8px; justify-content: center;">
        
   
        <button class="btn-table-action btn-view-style" onclick="openDetailsModal('<?php echo $u['user_id']; ?>', '<?php echo addslashes($u['username']); ?>', '<?php echo $u['role']; ?>')">
            <img src="assets/img/view.png" alt="View"> View
        </button>
        
        <button class="btn-table-action btn-edit-style" onclick="openPasswordModal(<?php echo $u['user_id']; ?>, '<?php echo addslashes($u['username']); ?>')">
            <img src="assets/img/edit.png" alt="Edit"> Edit
        </button>
        
        <button class="btn-table-action btn-delete-style" onclick="openDeleteModal(<?php echo $u['user_id']; ?>, '<?php echo addslashes($u['username']); ?>')">
            <img src="assets/img/delete.png" alt="Delete"> Delete
        </button>
        
    </div>
</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="addAdmin" class="tab-content" style="display: none;">
            <div class="form-container" style="max-width: 600px; padding: 35px !important; background: rgba(0,0,0,0.2) !important; border: 1px solid rgba(255,255,255,0.05) !important; border-radius: 12px !important;">
                <h3 style="color: #ffffff; margin-top: 0; margin-bottom: 25px;">Create a New Account</h3>
                <form action="manage_admins.php" method="POST">
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="color: #b9e6ff !important; font-weight: 700 !important; margin-bottom: 10px !important; display: block !important;">Username</label>
                        <input type="text" name="username" required autocomplete="new-password" style="width: 100% !important; padding: 14px !important; background: rgba(0,0,0,0.3) !important; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 8px !important; color: #ffffff !important; box-sizing: border-box !important;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="color: #b9e6ff !important; font-weight: 700 !important; margin-bottom: 10px !important; display: block !important;">Password</label>
                        <div class="password-wrapper" style="position: relative;">
                            <input type="password" name="password" id="add_pass" required autocomplete="new-password" style="width: 100% !important; padding: 14px !important; padding-right: 40px !important; background: rgba(0,0,0,0.3) !important; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 8px !important; color: #ffffff !important; box-sizing: border-box !important;">
                            <button type="button" class="password-toggle" onclick="togglePass('add_pass', this)" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8;"></button>
                        </div>
                    </div>
          
                    <button type="submit" name="add_admin" onclick="showLoader()" class="btn-add" style="width: 100% !important; margin-top: 25px !important; background: #00A5EF !important; color: white !important; padding: 14px !important; border: none !important; border-radius: 8px !important; font-weight: bold !important; cursor: pointer !important;">Create Admin</button>
                </form>
            </div>
        </div>
    </div>

    <div id="detailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; backdrop-filter: blur(4px);">
        <div class="form-container dark-modal-box" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 450px; padding: 30px; border-radius: 15px; text-align: center;">
            <div style="background: #00A5EF; width: 70px; height: 70px; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 30px; color: white;">👤</div>
            <h3 id="detName">Admin Name</h3>
            <p id="detRoleLabel" style="font-size: 13px; font-weight: 600; text-transform: uppercase;">Admin Role</p>
            <div style="margin-top: 25px; text-align: left;">
                <div class="detail-row"><span class="detail-label">User ID:</span><span id="detId" class="detail-value" style="font-weight: 700;">#001</span></div>
                <div class="detail-row"><span class="detail-label">Status:</span><span class="detail-value" style="color: #2ecc71; font-weight: 700;">Active</span></div>
            </div>
            <button type="button" onclick="closeDetailsModal()" style="width: 100%; margin-top: 25px; padding: 12px; background: #334155; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s;">Close Details</button>
        </div>
    </div>

    <div id="passwordModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; backdrop-filter: blur(3px);">
        <div class="form-container dark-modal-box" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 400px; padding: 25px; border-radius: 12px;">
            <h3 style="margin-top: 0; border-bottom: 1px solid #334155; padding-bottom: 10px;">Change Password</h3>
            <p style="font-size: 14px;">Updating for: <strong id="editUserNameDisplay" style="color: #f8fafc;"></strong></p>
            <form action="manage_admins.php" method="POST">
                <input type="hidden" name="edit_user_id" id="editUserId">
                <div class="form-group">
                    <label>Current Password</label>
                    <div class="password-wrapper" style="position: relative;">
                        <input type="password" name="current_password" id="curr_pass" required style="width: 100%; padding-right: 40px !important;">
                        <button type="button" class="password-toggle" onclick="togglePass('curr_pass', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;"></button>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label>New Password</label>
                    <div class="password-wrapper" style="position: relative;">
                        <input type="password" name="new_password" id="new_pass" required minlength="4" style="width: 100%; padding-right: 40px !important;">
                        <button type="button" class="password-toggle" onclick="togglePass('new_pass', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;"></button>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="button" onclick="closePasswordModal()" style="flex: 1; padding: 12px; background: #334155; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s;">Cancel</button>
             
                    <button type="submit" name="update_password" onclick="showLoader()" style="flex: 1; margin: 0; padding: 12px; background: #00A5EF; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s;">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; backdrop-filter: blur(4px);">
        <div class="form-container dark-modal-box" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 400px; padding: 25px; border-radius: 12px; text-align: center;">
            <h3 style="margin-top: 0; border-bottom: 1px solid #334155; padding-bottom: 10px; color: #ff4d4d !important;">Confirm Delete</h3>
            <p style="font-size: 14px; margin-top: 20px;">Are you sure you want to delete admin account:<br><strong id="deleteAdminName" style="color: #f8fafc; font-size: 18px;"></strong>?</p>
            
            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="button" onclick="closeDeleteModal()" style="flex: 1; padding: 12px; background: #334155; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s;">Cancel</button>
                
                <a id="confirmDeleteBtn" href="#" onclick="showLoader()" style="flex: 1; margin: 0; padding: 12px; background: #ff4d4d; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s; text-decoration: none; display: flex; align-items: center; justify-content: center;">Delete</a>
            </div>
        </div>
    </div>

    <?php include dirname(__DIR__) . '/includes/modals.php'; ?>
    <script src="/logbook/assets/js/app.js"></script>
    
    <script>
        const eyeOpen = `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
        const eyeSlash = `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"></path></svg>`;

        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.innerHTML = eyeOpen;
        });

        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                btn.innerHTML = eyeSlash;
            } else {
                input.type = "password";
                btn.innerHTML = eyeOpen;
            }
        }

        function switchTab(event, tabId) {
            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            event.currentTarget.classList.add('active');
        }

        function openDetailsModal(id, name, role) {
            document.getElementById('detId').innerText = "#" + id;
            document.getElementById('detName').innerText = name;
            document.getElementById('detRoleLabel').innerText = role;
            document.getElementById('detailsModal').style.display = 'block';
        }
        function closeDetailsModal() { document.getElementById('detailsModal').style.display = 'none'; }
        
        function openPasswordModal(userId, username) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserNameDisplay').innerText = username;
            document.getElementById('passwordModal').style.display = 'block';
        }
        function closePasswordModal() { document.getElementById('passwordModal').style.display = 'none'; }

        function openDeleteModal(id, name) {
            document.getElementById('deleteAdminName').innerText = name;
            document.getElementById('confirmDeleteBtn').href = 'manage_admins.php?delete_id=' + id;
            document.getElementById('deleteModal').style.display = 'block';
        }
        function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target == document.getElementById('detailsModal')) closeDetailsModal();
            if (event.target == document.getElementById('passwordModal')) closePasswordModal();
            if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
        }
    </script>
</body>
</html>