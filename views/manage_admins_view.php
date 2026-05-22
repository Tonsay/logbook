<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins - DOST-SEI Logbook</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
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

    <?php if ($error): ?>
        <div id="errorNotification" class="toast-popup" style="background-color: #dc3545;">❌ <?php echo $error; ?></div>
        <script>setTimeout(() => { const t = document.getElementById("errorNotification"); if(t){t.style.opacity="0"; setTimeout(()=>t.remove(),500);} }, 3000);</script>
    <?php endif; ?>

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

                <h2 style="margin: 0 0 0 20px; font-size: 20px; color: #00A5EF; font-weight: 700;">
                    <img src="/logbook/assets/img/admin.png" class="sidebar-icon" alt="Admins" style="width: 40px; height: 40px; vertical-align: middle;"> Manage Admins
                </h2>
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

        <div class="custom-tabs">
            <button class="tab-link active" onclick="switchTab(event, 'viewAdmins')">Admin List</button>
            <button class="tab-link" onclick="switchTab(event, 'addAdmin')">Add New Admin</button>
        </div>

        <div id="viewAdmins" class="tab-content">
           <div class="table-container" style="width: 100%;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_users as $u): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($u['user_id']); ?></td>
                                <td>
                                    <strong class="clickable-name" onclick="openDetailsModal('<?php echo $u['user_id']; ?>', '<?php echo addslashes($u['username']); ?>', '<?php echo $u['role']; ?>')">
                                        <?php echo htmlspecialchars($u['username']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php if($u['role'] === 'superadmin'): ?>
                                        <span class="badge-superadmin"><img src="assets/img/superadmin.png" style="width: 12px; margin-right: 4px;">Superadmin</span>
                                    <?php else: ?>
                                        <span class="badge-admin"><img src="assets/img/admin1.png" style="width: 12px; margin-right: 4px;">Admin</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
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
            <div class="form-container">
                <h3 style="color: #00A5EF; margin-top: 0; margin-bottom: 25px;">Create a New Account</h3>
                <form action="manage_admins.php" method="POST">
                    
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required autocomplete="new-password">
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="add_pass" required autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePass('add_pass', this)"></button>
                        </div>
                    </div>
          
                    <button type="submit" name="add_admin" onclick="showLoader()" class="btn-add" style="width: 100%; margin-top: 25px;">Create Admin</button>
                </form>
            </div>
        </div>
    </div>

    <div id="detailsModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 450px;">
            <div style="background: rgba(0, 165, 239, 0.1); width: 70px; height: 70px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 30px;">👤</div>
            <h3 id="detName" style="color: #00A5EF; margin-bottom: 5px;">Admin Name</h3>
            <p id="detRoleLabel" style="font-size: 13px; font-weight: 600; text-transform: uppercase; color: #888;">Admin Role</p>
            
            <div style="margin-top: 25px; text-align: left;">
                <div class="detail-row">
                    <span style="font-weight: 600; color: #888;">User ID:</span>
                    <span id="detId" style="font-weight: 700; color: #00A5EF;">#001</span>
                </div>
                <div class="detail-row">
                    <span style="font-weight: 600; color: #888;">Status:</span>
                    <span style="color: #2ecc71; font-weight: 700;">Active</span>
                </div>
            </div>
            
            <div class="modal-buttons" style="margin-top: 25px;">
                <button type="button" onclick="closeDetailsModal()" class="btn-cancel" style="width: 100%;">Close Details</button>
            </div>
        </div>
    </div>

    <div id="passwordModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 400px; text-align: left;">
            <h3 style="margin-top: 0; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;">Change Password</h3>
            <p style="font-size: 14px; color: #888; margin-bottom: 20px;">Updating for: <strong id="editUserNameDisplay" style="color: #00A5EF;"></strong></p>
            
            <form action="manage_admins.php" method="POST">
                <input type="hidden" name="edit_user_id" id="editUserId">
                
                <div class="form-group">
                    <label>Current Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="current_password" id="curr_pass" required>
                        <button type="button" class="password-toggle" onclick="togglePass('curr_pass', this)"></button>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 15px;">
                    <label>New Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="new_password" id="new_pass" required minlength="4">
                        <button type="button" class="password-toggle" onclick="togglePass('new_pass', this)"></button>
                    </div>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" onclick="closePasswordModal()" class="btn-cancel" style="flex: 1;">Cancel</button>
                    <button type="submit" name="update_password" onclick="showLoader()" class="btn-confirm" style="flex: 1;">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 400px;">
            <h3 style="margin-top: 0; color: #e74c3c;">Confirm Delete</h3>
            <p style="font-size: 15px; margin-top: 20px; color: #666;">Are you sure you want to delete admin account:<br><strong id="deleteAdminName" style="color: #00A5EF; font-size: 18px; display: block; margin-top: 10px;"></strong></p>
            
            <div class="modal-buttons" style="margin-top: 25px;">
                <button type="button" onclick="closeDeleteModal()" class="btn-cancel" style="flex: 1;">Cancel</button>
                <a id="confirmDeleteBtn" href="#" onclick="showLoader()" class="btn-confirm" style="flex: 1; background: #e74c3c;">Yes, Delete</a>
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
            document.getElementById('detailsModal').style.display = 'flex';
        }
        function closeDetailsModal() { document.getElementById('detailsModal').style.display = 'none'; }
        
        function openPasswordModal(userId, username) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserNameDisplay').innerText = username;
            document.getElementById('passwordModal').style.display = 'flex';
        }
        function closePasswordModal() { document.getElementById('passwordModal').style.display = 'none'; }

        function openDeleteModal(id, name) {
            document.getElementById('deleteAdminName').innerText = name;
            document.getElementById('confirmDeleteBtn').href = 'manage_admins.php?delete_id=' + id;
            document.getElementById('deleteModal').style.display = 'flex';
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