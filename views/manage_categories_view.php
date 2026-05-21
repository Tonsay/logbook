<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - DOST-SEI Logbook</title>
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
        <div id="errorNotification" class="toast-popup">❌ <?php echo $error; ?></div>
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
                        <h2 style="margin: 0; font-size: 15px; font-weight: 700; line-height: 1.2; color: #000000;">Science Education Institute</h2>
                        <p style="margin: 3px 0 0 0; font-size: 11px; letter-spacing: 1px; color: #00A5EF; font-weight: 600;">LOGBOOK SYSTEM</p>
                    </div>
                </div>

                <h2 style="margin: 0 0 0 20px; font-size: 20px; color: #00A5EF; font-weight: 700;">
                    <img src="/logbook/assets/img/issuances.png" class="sidebar-icon" alt="Issuances" style="width: 40px; height: 40px; vertical-align: middle;"> Manage Issuances
                </h2>            </div>

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

        <div class="custom-tabs">
            <button class="tab-link active" onclick="switchTab(event, 'viewCategories')">Category List</button>
            <button class="tab-link" onclick="switchTab(event, 'addCategory')">Add New Category</button>
        </div>

        <div id="viewCategories" class="tab-content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $row_number = 1; ?> 
                        <?php foreach ($all_categories as $cat): ?>
                            <tr>
                                <td class="font-bold text-blue"><?php echo $row_number++; ?></td>
                                <td class="font-bold text-dark"><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                <td class="text-center">
                                    <div class="action-btns">
                                        <button class="btn-table-action btn-edit-style" onclick="openEditCategoryModal(<?php echo $cat['category_id']; ?>, '<?php echo addslashes(htmlspecialchars($cat['category_name'])); ?>')">
                                            <img src="assets/img/edit.png" alt="Edit"> Edit
                                        </button>
                                        <button class="btn-table-action btn-delete-style" onclick="openDeleteModal(<?php echo $cat['category_id']; ?>, '<?php echo addslashes(htmlspecialchars($cat['category_name'])); ?>')">
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

        <div id="addCategory" class="tab-content" style="display: none;">
            <div class="form-container">
                <h3 class="text-blue">Register New Category</h3>
                <form action="manage_categories.php" method="POST">
                    <div class="form-group">
                        <label>Document Name</label>
                        <input type="text" name="category_name" required placeholder="e.g. Special Order" autocomplete="off">
                    </div>
                    <button type="submit" name="add_category" onclick="showLoader()" class="btn-add">+ Save Category</button>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 400px;">
            <h3 style="margin-top: 0; color: #e74c3c;">Confirm Delete</h3>
            <p style="font-size: 15px; margin-top: 20px; color: #666;">Are you sure you want to delete:<br><strong id="deleteCatName" class="text-blue" style="font-size: 18px; display: block; margin-top: 10px;"></strong></p>
            
            <div class="modal-buttons" style="margin-top: 25px;">
                <button type="button" onclick="closeDeleteModal()" class="btn-cancel" style="flex: 1;">Cancel</button>
                <a id="confirmDeleteBtn" href="#" onclick="showLoader()" class="btn-confirm" style="flex: 1; background: #e74c3c;">Delete</a>
            </div>
        </div>
    </div>

    <div id="editCategoryModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 500px; text-align: left;">
            <h3 class="text-blue" style="text-align: center; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px; margin-top: 0;">Edit Category</h3>
            
            <form action="manage_categories.php" method="POST">
                <input type="hidden" name="category_id" id="edit_category_id_hidden">
                <input type="hidden" name="old_category_name" id="edit_old_category_name_hidden">

                <div class="form-group" style="margin-top: 15px;">
                    <label>Category Name</label>
                    <input type="text" name="category_name" id="edit_category_name_input" required>
                </div>

                <div class="modal-buttons">
                    <button type="button" onclick="closeEditCategoryModal()" class="btn-cancel" style="flex: 1;">Cancel</button>
                    <button type="submit" name="edit_category_btn" onclick="showLoader()" class="btn-confirm" style="flex: 1;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <?php include dirname(__DIR__) . '/includes/modals.php'; ?>
    <script src="/logbook/assets/js/app.js"></script>
    
    <script>
        function switchTab(event, tabId) {
            document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            event.currentTarget.classList.add('active');
        }

        function openDeleteModal(id, name) {
            document.getElementById('deleteCatName').innerText = name;
            document.getElementById('confirmDeleteBtn').href = 'manage_categories.php?delete_id=' + id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function openEditCategoryModal(id, currentName) {
            document.getElementById('edit_category_id_hidden').value = id;
            document.getElementById('edit_old_category_name_hidden').value = currentName;
            document.getElementById('edit_category_name_input').value = currentName;
            document.getElementById('editCategoryModal').style.display = 'flex';
        }

        function closeEditCategoryModal() {
            document.getElementById('editCategoryModal').style.display = 'none';
        }
    </script>
</body>
</html>