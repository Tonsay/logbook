<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - DOST-SEI Logbook</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
      .custom-tabs { margin-bottom: 0 !important; }
        .tab-link { border-bottom-left-radius: 0 !important; border-bottom-right-radius: 0 !important; }
        .tab-content .table-container, .tab-content .form-container { border-top-left-radius: 0 !important; margin-top: 0 !important; }
        .dark-modal-box { background: #1e293b !important; color: #f8fafc !important; border: 1px solid #334155; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5); }
        .dark-modal-box h3 { color: #ff4d4d !important; border-bottom-color: #334155 !important; }
        .btn-edit-cat { background: rgba(0, 165, 239, 0.2); color: #b9e6ff; border: 1px solid rgba(0, 165, 239, 0.3); padding: 6px 15px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 12px; transition: all 0.2s ease; }
        .btn-edit-cat:hover { background: #00A5EF; color: white; }
        .btn-delete-cat { background: #ff4d4d; color: #ffffff; border: 1px solid rgba(255, 77, 77, 0.2); padding: 6px 15px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 12px; transition: all 0.2s ease; }
        .btn-delete-cat:hover { background: #e74c3c; color: white; }

        /* Button Styles */
        .btn-cat-action {
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

        .btn-edit-cat-style {
            background: rgba(241, 196, 15, 0.15);
            color: #f1c40f;
            border-color: rgba(241, 196, 15, 0.2);
        }
        .btn-edit-cat-style img {
            width: 16px;
            height: 16px;
            filter: invert(81%) sepia(61%) saturate(913%) hue-rotate(357deg) brightness(101%) contrast(92%);
        }

        .btn-delete-cat-style {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
            border-color: rgba(231, 76, 60, 0.2);
        }
        .btn-delete-cat-style img {
            width: 16px;
            filter: invert(39%) sepia(82%) saturate(3015%) hue-rotate(340deg) brightness(93%) contrast(98%);
        }


        .btn-edit-cat-style:hover { background: #f1c40f; color: white; }
        .btn-delete-cat-style:hover { background: #e74c3c; color: white; }

        .btn-cat-action:hover img {
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
                <h2 style="margin: 0 0 0 20px; font-size: 20px; color: #ffffff; font-weight: 700;"><img src="/logbook/assets/img/issuances.png" class="sidebar-icon" alt="Issuances" style="width: 34px; height: 34px;">Manage Categories</h2>
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

        <div class="custom-tabs" style="display: flex; justify-content: flex-start; width: 100%; margin-top: 10px;">
            <button class="tab-link active" onclick="switchTab(event, 'viewCategories')">Category List</button>
            <button class="tab-link" onclick="switchTab(event, 'addCategory')">Add New Category</button>
        </div>

        <div id="viewCategories" class="tab-content">
            <div class="table-container" style="width: 100%;">
                <?php if($error): ?><div style="background: rgba(255, 77, 77, 0.1); border: 1px solid #ff4d4d; color: #ff4d4d; padding: 15px; margin-bottom: 20px; border-radius: 8px;">⚠️ <?php echo $error; ?></div><?php endif; ?>
                
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                   <tbody>
                        <?php $row_number = 1; ?> 
                        
                        <?php foreach ($all_categories as $cat): ?>
                            <tr>
                                <td style="font-weight: bold;"><?php echo $row_number++; ?></td>
                                
                                <td style="font-weight: bold;">
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                    
              <button class="btn-cat-action btn-edit-cat-style" 
        onclick="openEditCategoryModal(<?php echo $cat['category_id']; ?>, '<?php echo addslashes(htmlspecialchars($cat['category_name'])); ?>')">
    <img src="assets/img/edit.png" alt="Edit"> 
    <span>Edit</span>
</button>
                    <button class="btn-cat-action btn-delete-cat-style" 
                            onclick="openDeleteModal(<?php echo $cat['category_id']; ?>, '<?php echo addslashes(htmlspecialchars($cat['category_name'])); ?>')">
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
            <div class="form-container" style="max-width: 600px;">
                <h3 style="color: #ffffff; margin-top: 0;">Register New Category</h3>
                <form action="manage_categories.php" method="POST">
                    <div class="form-group">
                        <label>Document Name</label>
                        <input type="text" name="category_name" required placeholder="e.g. Special Order" autocomplete="off">
                    </div>
                  
                    <button type="submit" name="add_category" onclick="showLoader()" class="btn-add" style="width: 100%; margin-top: 25px;">+ Save Category</button>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; backdrop-filter: blur(4px);">
        <div class="form-container dark-modal-box" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 400px; padding: 25px; border-radius: 12px; text-align: center;">
            <h3 style="margin-top: 0; border-bottom: 1px solid #334155; padding-bottom: 10px;">Confirm Delete</h3>
            <p style="font-size: 18px; margin-top: 20px;">Are you sure you want to delete<br><strong id="deleteCatName" style="color: #f8fafc; font-size: 16px;"></strong>?</p>
            
            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="button" onclick="closeDeleteModal()" style="flex: 1; padding: 12px; background: #334155; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; font-weight: bold;">Cancel</button>
                <a id="confirmDeleteBtn" href="#" onclick="showLoader()" style="flex: 1; margin: 0; padding: 12px; background: #ff4d4d; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; font-weight: bold; text-decoration: none; display: flex; align-items: center; justify-content: center;">Delete</a>
            </div>
        </div>
    </div>

    <div id="editCategoryModal" class="modal-overlay" style="display: none; z-index: 10000; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
        <div class="edit-card" style="position: relative; max-width: 500px; width: 100%; margin: auto; background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 30px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); text-align: left;">
            <h3 class="edit-title" style="color: #00A5EF; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; margin-top: 0; font-size: 20px;">Edit Category</h3>
            
            <form action="manage_categories.php" method="POST">
                <input type="hidden" name="category_id" id="edit_category_id_hidden">
                <input type="hidden" name="old_category_name" id="edit_old_category_name_hidden">

                <div style="margin-top: 15px;">
                    <label style="color: #b9e6ff; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 6px;">Category Name</label>
                    <input type="text" name="category_name" id="edit_category_name_input" required style="width: 100%; background: rgba(0,0,0,0.3); color: white; border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 8px;">
                </div>

                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="button" onclick="closeEditCategoryModal()" style="flex: 1; padding: 12px; background: #334155; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; font-weight: bold; cursor: pointer;">Cancel</button>
                  
                    <button type="submit" name="edit_category_btn" onclick="showLoader()" style="flex: 1; padding: 12px; background: #00A5EF; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Save Changes</button>
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
            document.getElementById('deleteModal').style.display = 'block';
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