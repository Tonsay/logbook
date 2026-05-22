<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Divisions - DOST-SEI Logbook</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

   <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            window.addEventListener('load', function() {
                if (typeof triggerSuccessLoad === 'function') {
                    triggerSuccessLoad("<?php echo addslashes($_SESSION['success_message']); ?>");
                }
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php include dirname(__DIR__) . '/includes/sidebar.php'; ?>

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

                <h2 style="margin: 0 0 0 20px; font-size: 20px; color: #00A5EF; font-weight: 700;">
                    <img src="/logbook/assets/img/divisions.png" class="sidebar-icon" alt="Divisions" style="width: 40px; height: 40px; vertical-align: middle;"> Manage Divisions
                </h2>
            </div>

            <div class="header-right">
                <button id="themeToggle" class="icon-btn">🌙</button>
                <button onclick="showSettingsModal(event)" class="icon-btn" title="Settings"><img src="/logbook/assets/img/setting.png" alt="Settings" style="width: 30px; height: 30px;"></button>
                <div class="user-profile" onclick="window.location.href='/logbook/profile.php'">
                    <?php 
                        $sess_pic = $_SESSION['profile_picture'] ?? 'avatar.png';
                        $sidebar_img = ($sess_pic === 'avatar.png' || $sess_pic === 'avatar.jpg') ? '/logbook/assets/img/'.$sess_pic : '/logbook/uploads/' . $sess_pic;
                    ?>
                    <img src="<?php echo htmlspecialchars($sidebar_img); ?>" alt="Admin" class="user-avatar">
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></h4>
                    </div>
                </div>
            </div>
        </div>

       <div class="custom-tabs">
            <div class="tab-link active" onclick="switchTab('viewDivisions', this)">Division List</div>
            <div class="tab-link" onclick="switchTab('addDivision', this)">Add New Division</div>
        </div>

        <div id="viewDivisions" class="tab-content">
            <div class="table-container">
                <?php if($error): ?><div class="error-banner">⚠️ <?php echo $error; ?></div><?php endif; ?>
                
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Division Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $row_number = 1; ?> 
                        <?php foreach ($all_divisions as $div): ?>
                            <tr>
                                <td class="font-bold text-blue"><?php echo $row_number++; ?></td>
                                <td class="font-bold text-dark">
                                    <?php echo htmlspecialchars($div['division_name']); ?>
                                </td>
                                <td class="text-center">
                                    <div class="action-btns">
                                        <button class="btn-table-action btn-edit-style" onclick="openEditDivisionModal(<?php echo $div['division_id']; ?>, '<?php echo addslashes(htmlspecialchars($div['division_name'])); ?>')">
                                            <img src="assets/img/edit.png" alt="Edit"> Edit
                                        </button>

                                        <button class="btn-table-action btn-delete-style" onclick="openDeleteModal(<?php echo $div['division_id']; ?>, '<?php echo addslashes(htmlspecialchars($div['division_name'])); ?>')">
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

        <div id="addDivision" class="tab-content" style="display: none;">
            <div class="form-container">
                <h3 class="text-blue">Register New Division</h3>
                <form action="manage_division.php" method="POST">
                    <div class="form-group">
                        <label>Division Name / Acronym</label>
                        <input type="text" name="division_name" required placeholder="e.g. STSD" autocomplete="off">
                    </div>
                    <button type="submit" name="add_division" onclick="showLoader()" class="btn-add">+ Save Division</button>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 400px;">
            <h3 style="margin-top: 0; color: #e74c3c;">Confirm Delete</h3>
            <p style="font-size: 15px; margin-top: 20px; color: #666;">Are you sure you want to delete:<br><strong id="deleteDivName" class="text-blue" style="font-size: 18px; display: block; margin-top: 10px;"></strong></p>
            
            <div class="modal-buttons" style="margin-top: 25px;">
                <button type="button" onclick="closeDeleteModal()" class="btn-cancel" style="flex: 1;">Cancel</button>
                <a id="confirmDeleteBtn" href="#" onclick="showLoader()" class="btn-confirm" style="flex: 1; background: #e74c3c;">Delete</a>
            </div>
        </div>
    </div>

    <div id="editDivisionModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 500px; text-align: left;">
            <h3 class="text-blue" style="text-align: center; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px; margin-top: 0;">Edit Division</h3>
            
            <form action="manage_division.php" method="POST">
                <input type="hidden" name="division_id" id="edit_division_id_hidden">
                <input type="hidden" name="old_division_name" id="edit_old_division_name_hidden">

                <div class="form-group" style="margin-top: 15px;">
                    <label>Division Name</label>
                    <input type="text" name="division_name" id="edit_division_name_input" required>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="closeEditDivisionModal()" class="btn-cancel" style="flex: 1;">Cancel</button>
                    <button type="submit" name="edit_division_btn" onclick="showLoader()" class="btn-confirm" style="flex: 1;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <?php include dirname(__DIR__) . '/includes/modals.php'; ?>
    <script src="/logbook/assets/js/app.js"></script>
    
    <script>
        function switchTab(tabId, element) {
            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            element.classList.add('active');
        }

        function openDeleteModal(id, name) {
            document.getElementById('deleteDivName').innerText = name;
            document.getElementById('confirmDeleteBtn').href = 'manage_division.php?delete_id=' + id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function openEditDivisionModal(id, currentName) {
            document.getElementById('edit_division_id_hidden').value = id;
            document.getElementById('edit_old_division_name_hidden').value = currentName;
            document.getElementById('edit_division_name_input').value = currentName;
            document.getElementById('editDivisionModal').style.display = 'flex';
        }

        function closeEditDivisionModal() {
            document.getElementById('editDivisionModal').style.display = 'none';
        }
    </script>
</body>
</html>