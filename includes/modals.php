<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Logbook Entry</title>
    <link rel="stylesheet" href="/logbook/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<div id="globalLoader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); z-index: 999999; display: flex; flex-direction: column; justify-content: center; align-items: center; transition: opacity 0.4s ease; display: none; opacity: 0; backdrop-filter: blur(5px);">
    
    <div id="loaderSpinner" style="width: 50px; height: 50px; border: 5px solid rgba(0, 165, 239, 0.2); border-top-color: #00A5EF; border-radius: 50%; animation: spin 1s linear infinite;"></div>
    
    <div id="loaderSuccess" style="display: none; width: 50px; height: 50px; background: #10b981; border-radius: 50%; justify-content: center; align-items: center; color: white; font-size: 24px; font-weight: bold; animation: popIn 0.3s ease-out;">✓</div>

    <p id="loaderText" style="color: #00A5EF; margin-top: 15px; font-weight: bold; font-size: 14px; letter-spacing: 1px; animation: pulseText 1.5s infinite;">Loading...</p>

</div>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes pulseText { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    @keyframes popIn { 0% { transform: scale(0); } 80% { transform: scale(1.2); } 100% { transform: scale(1); } }
</style>


<div id="detailsModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 600px; text-align: left; background: #ffffff; color: #333333;">
        <h3 id="modalCategory" style="text-align: center; border-bottom: 1px solid #e0e0e0; padding-bottom: 15px; color: #00A5EF;">Document Details</h3>
        
        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="color: #666666; font-size: 11px; font-weight: bold; text-transform: uppercase;">Document ID</label>
                <p id="modalDocID" style="margin: 5px 0 10px 0; font-size: 15px; font-weight: 600; color: #333333;"></p>
            </div>
            <div>
                <label style="color: #666666; font-size: 11px; font-weight: bold; text-transform: uppercase;">Issuance Number</label>
                <p id="modalIssNo" style="margin: 5px 0 10px 0; font-size: 15px; font-weight: 600; color: #333333;"></p>
            </div>
            <div>
                <label style="color: #666666; font-size: 11px; font-weight: bold; text-transform: uppercase;">Division</label>
                <p id="modalDivision" style="margin: 5px 0 10px 0; font-size: 15px; font-weight: 600; color: #333333;"></p>
            </div>
            <div>
                <label style="color: #666666; font-size: 11px; font-weight: bold; text-transform: uppercase;">Date Issued</label>
                <p id="modalDate" style="margin: 5px 0 10px 0; font-size: 15px; font-weight: 600; color: #333333;"></p>
            </div>
            <div>
                <label style="color: #666666; font-size: 11px; font-weight: bold; text-transform: uppercase;">Created By</label>
                <p id="modalCreatedBy" style="margin: 5px 0 10px 0; font-size: 15px; font-weight: 600; color: #333333;"></p>
            </div>
            <div>
                <label style="color: #666666; font-size: 11px; font-weight: bold; text-transform: uppercase;">Date Created</label>
                <p id="modalCreatedAt" style="margin: 5px 0 10px 0; font-size: 15px; font-weight: 600; color: #333333;"></p>
            </div>
        </div>

        <div style="margin-top: 10px;">
            <label style="color: #666666; font-size: 11px; font-weight: bold; text-transform: uppercase;">Subject</label>
            <p id="modalSubject" style="margin: 5px 0 0 0; line-height: 1.6; color: #333333; padding: 15px; border-radius: 12px; border: 1px solid #e0e0e0; font-weight: 500;"></p>
        </div>

        <div id="lib_financial_section" style="display: none; background: rgba(0, 165, 239, 0.05); padding: 15px; border-radius: 8px; margin-top: 15px; border: 1px solid rgba(0, 165, 239, 0.2);">
            <h4 style="margin-top: 0; color: #00A5EF; font-size: 14px; text-transform: uppercase;">Financial & Project Details</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div><span style="font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase;">Project No:</span> <br><strong id="detail_project_num"></strong></div>
                <div><span style="font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase;">Duration:</span> <br><strong id="detail_duration"></strong></div>
                <div><span style="font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase;">Action:</span> <br><strong id="detail_action"></strong></div>
                <div><span style="font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase;">Amount:</span> <br><strong id="detail_amount" style="color: #28a745; font-size: 16px;"></strong></div>
            </div>
        </div>

        <div style="margin-top: 25px; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px;">
            
        <a id="editBtn" href="javascript:void(0)" onclick="openEditModal()" class="modal-action-btn" 
   style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; 
          background: rgba(0, 165, 239, 0.1); padding: 10px 20px; border-radius: 8px; 
          text-decoration: none; color: #00A5EF; font-weight: 700; transition: all 0.3s ease;">
    <img src="assets/img/edit.png" style="width: 18px; height: 18px; object-fit: contain; filter: brightness(0) saturate(100%) invert(39%) sepia(87%) saturate(2335%) hue-rotate(178deg) brightness(97%) contrast(101%);">
    <span>Edit</span>
</a>            

<a id="attachBtn" href="javascript:void(0)" onclick="triggerAttachModal()" class="modal-action-btn" 
   style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; 
          background: rgba(46, 204, 113, 0.1); padding: 10px 20px; border-radius: 8px; 
          text-decoration: none; color: #27ae60; font-weight: 700; transition: all 0.3s ease;">
    <img src="assets/img/attach.png" style="width: 18px; height: 18px; object-fit: contain; filter: brightness(0) saturate(100%) invert(56%) sepia(50%) saturate(497%) hue-rotate(93deg) brightness(93%) contrast(89%);">
    <span>Attach</span>
</a>

<a id="historyBtn" href="javascript:void(0)" onclick="openHistoryModal()" class="modal-action-btn" 
   style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; 
          background: rgba(241, 196, 15, 0.1); padding: 10px 20px; border-radius: 8px; 
          text-decoration: none; color: #d4ac0d; font-weight: 700; transition: all 0.3s ease;">
    <img src="assets/img/history.png" style="width: 18px; height: 18px; object-fit: contain; filter: brightness(0) saturate(100%) invert(69%) sepia(61%) saturate(913%) hue-rotate(357deg) brightness(101%) contrast(92%);">
    <span>History</span>
</a>      

<a id="deleteBtn" href="javascript:void(0)" onclick="triggerDeleteFromModal()" class="modal-action-btn" 
   style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; 
          background: rgba(231, 76, 60, 0.1); padding: 10px 20px; border-radius: 8px; 
          text-decoration: none; color: #e74c3c; font-weight: 700; transition: all 0.3s ease;">
    <img src="assets/img/delete.png" style="width: 18px; height: 18px; object-fit: contain; filter: brightness(0) saturate(100%) invert(39%) sepia(82%) saturate(3015%) hue-rotate(340deg) brightness(93%) contrast(98%);">
    <span>Delete</span>
</a> 
        </div>

        <div class="modal-buttons" style="margin-top: 15px;">
            <button onclick="closeDetailsModal()" class="btn-cancel" style="width: 100%; padding: 12px; background: #e0e0e0; color: #333333; border: none;">Close Details</button>
        </div>
    </div>
</div>

<div id="settingsModal" class="modal-overlay">
    <div class="modal-card">
        <h3>System Settings</h3>
        <form action="process_settings.php" method="POST" onsubmit="showLoader()">
            <div class="form-group" style="text-align: left;">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" autocomplete="off">
            </div>
            
            <div class="form-group" style="text-align: left; margin-top: 15px;">
                <label>New Password <small style="font-weight:normal; color:#888;">(Leave blank to keep)</small></label>
                
                <div class="password-wrapper">
                    <input type="password" name="new_password" id="modal_settings_pass" placeholder="Enter new password...">
                    <button type="button" class="password-toggle" onclick="togglePass('modal_settings_pass', this)"></button>
                </div>
            </div>
            
            <div class="modal-buttons" style="margin-top: 20px;">
                <button type="button" onclick="closeSettingsModal()" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-confirm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div id="logoutModal" class="modal-overlay">
    <div class="modal-card">
        <h3 style="color: #e74c3c;">Log Out?</h3>
        <p style="color: #555555; margin-bottom: 20px;">Are you sure you want to log out of the system?</p>
        <div class="modal-buttons">
            <button onclick="closeLogoutModal()" class="btn-cancel">Cancel</button>
            <a href="logout.php" onclick="showLoader()" class="btn-confirm" style="background: #e74c3c; text-decoration: none;">Yes, Log Out</a>
        </div>
    </div>
</div>

<div id="deleteConfirmModal" class="modal-overlay" style="display:none; z-index: 9999;">
    <div class="modal-card" style="max-width: 400px; text-align: center; border: 1px solid #fcc2c3;">
        <h3 style="color: #e74c3c; margin-bottom: 15px;">Delete Entry?</h3>
        <p style="color: #555555; margin-bottom: 25px; line-height: 1.5;">
            Are you sure you want to permanently delete document:<br>
            <strong id="deleteTargetID" style="color: #00A5EF; font-size: 18px;"></strong>
        </p>
        <div class="modal-buttons" style="display: flex; gap: 10px;">
            <button onclick="closeDeleteConfirm()" class="btn-cancel" style="flex: 1;">Cancel</button>
            <button id="finalDeleteBtn" class="btn-confirm" style="flex: 1; background: #e74c3c;" onclick="showLoader()">Yes, Delete</button>
        </div>
    </div>
</div>

<div id="editIssuanceModal" class="modal-overlay" style="display: none; z-index: 10000; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
    
    <div class="edit-card" style="position: relative; max-width: 700px; width: 100%; margin: auto; background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 30px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); text-align: left; max-height: 90vh; overflow-y: auto;">
        
        <h3 class="edit-title" style="color: #00A5EF; text-align: center; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px; margin-top: 0; font-size: 20px;">Edit Document Details</h3>
        <p class="edit-subtitle" style="color: #666666; text-align: center; margin-bottom: 20px; font-size: 13px;">Updating: <strong id="edit_display_id" style="color: #333333;"></strong></p>

        <form id="editIssuanceForm" onsubmit="handleEditSubmit(event)" style="display: flex; flex-direction: column; gap: 20px; margin-top: 10px;">
            <input type="hidden" name="document_id" id="edit_doc_id_hidden">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Division</label>
                    <select name="division" id="edit_division" required style="width: 100%; box-sizing: border-box; background: #ffffff; color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; outline: none; transition: border-color 0.2s;">
                        <option value="" disabled selected>-- Select Division --</option>
                        <?php 
                        if(isset($conn)){
                            $div_query = mysqli_query($conn, "SELECT * FROM divisions_tb ORDER BY division_name ASC"); 
                            if ($div_query && mysqli_num_rows($div_query) > 0) {
                                while ($row = mysqli_fetch_assoc($div_query)) {
                                    $divName = htmlspecialchars($row['division_name']);
                                    echo "<option value=\"$divName\">$divName</option>";
                                }
                            } else {
                                echo "<option value=\"\">No divisions found</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Date Issued</label>
                     <input 
                        type="text" 
                        name="date_issued" 
                        id="edit_date_issued" 
                        class="custom-date-picker" 
                        placeholder="YYYY-MM-DD" 
                        required 
                        style="width: 100%; box-sizing: border-box; background: #ffffff; color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; outline: none; transition: border-color 0.2s;" >
                </div>
            </div>

            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Issuance Number</label>
                    <input type="text" name="issuance_number" id="edit_issuance_number" required style="width: 100%; box-sizing: border-box; background: #ffffff; color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; outline: none; transition: border-color 0.2s;">
                </div>
                <div>
                    <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Category</label>
                    <select name="category" id="edit_category" required style="width: 100%; box-sizing: border-box; background: #88d5da; color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; outline: none; transition: border-color 0.2s;">
                        <option value="" disabled selected>-- Select Category --</option>
                        <?php 
                        if(isset($conn)){
                            $cat_query = mysqli_query($conn, "SELECT * FROM category_tb ORDER BY category_name ASC"); 
                            if ($cat_query && mysqli_num_rows($cat_query) > 0) {
                                while ($row = mysqli_fetch_assoc($cat_query)) {
                                    $catName = htmlspecialchars($row['category_name']);
                                    echo "<option value=\"$catName\">$catName</option>";
                                }
                            } else {
                                echo "<option value=\"\">No categories found</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            
            <div>
                <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Subject</label>
                <textarea name="subject" id="edit_subject" rows="4" required style="width: 100%; box-sizing: border-box; background: #ffffff; color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; font-family: inherit; resize: vertical; outline: none; transition: border-color 0.2s;"></textarea>
            </div>

            
            <div style="display: flex; gap: 15px; margin-top: 5px;">
                <button type="button" onclick="closeEditModal()" style="flex: 1; padding: 14px; background: #e0e0e0; color: #333333; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.2s;">Cancel</button>
                <button type="submit" style="flex: 1; padding: 14px; background: #00A5EF; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.2s;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div id="editLibModal" class="modal-overlay" style="display: none; z-index: 10000; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
    
    <div class="edit-card" style="position: relative; max-width: 700px; width: 100%; margin: auto; background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 30px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); text-align: left; max-height: 90vh; overflow-y: auto;">
        
        <h3 class="edit-title" style="color: #00A5EF; text-align: center; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px; margin-top: 0; font-size: 20px;">Edit Line-Item-Budget</h3>
        <p class="edit-subtitle" style="color: #666666; text-align: center; margin-bottom: 20px; font-size: 13px;">Updating Document: <strong id="edit_lib_display_id" style="color: #333333;"></strong></p>
        
        <form action="/logbook/process_edit_lib.php" method="POST" onsubmit="showLoader()" style="display: flex; flex-direction: column; gap: 20px; margin-top: 10px;">
            <input type="hidden" name="document_id" id="edit_lib_doc_id">
            <input type="hidden" name="category" value="Line-Item-Budget (LIB)">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Project Number</label>
                    <input type="text" name="project_number" id="edit_lib_project_number" readonly style="width: 100%; box-sizing: border-box; background: rgba(0,0,0,0.05); color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; outline: none; font-weight: bold;">
                </div>
                <div>
                    <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Date Issued</label>
                    <input type="date" name="date_issued" id="edit_lib_date" required style="width: 100%; box-sizing: border-box; background: #ffffff; color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; outline: none;">
                </div>
            </div>

            <div>
                <label style="color: #555555; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Project Description</label>
                <textarea name="lib_project_desc" id="edit_lib_desc" rows="3" required style="width: 100%; box-sizing: border-box; background: #ffffff; color: #333333; border: 1px solid #cccccc; padding: 12px; border-radius: 8px; font-family: inherit; resize: vertical; outline: none;"></textarea>
            </div>

            <div style="border-top: 1px dashed #ccc; padding-top: 15px;">
                <label style="color: #00A5EF; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 12px;">Project Duration</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: #555555; font-size: 10px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Start Month</label>
                        <select name="duration_start_month" id="edit_lib_start" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                            <option value="January">January</option> <option value="February">February</option>
                            <option value="March">March</option> <option value="April">April</option>
                            <option value="May">May</option> <option value="June">June</option>
                            <option value="July">July</option> <option value="August">August</option>
                            <option value="September">September</option> <option value="October">October</option>
                            <option value="November">November</option> <option value="December">December</option>
                        </select>
                    </div>
                    <div>
                        <label style="color: #555555; font-size: 10px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">End Month</label>
                        <select name="duration_end_month" id="edit_lib_end" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                            <option value="January">January</option> <option value="February">February</option>
                            <option value="March">March</option> <option value="April">April</option>
                            <option value="May">May</option> <option value="June">June</option>
                            <option value="July">July</option> <option value="August">August</option>
                            <option value="September">September</option> <option value="October">October</option>
                            <option value="November">November</option> <option value="December">December</option>
                        </select>
                    </div>
                    <div>
                        <label style="color: #555555; font-size: 10px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Year</label>
                        <input type="number" name="duration_year" id="edit_lib_year" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; box-sizing: border-box;">
                    </div>
                </div>
            </div>

            <div style="border-top: 1px dashed #ccc; padding-top: 15px;">
                <label style="color: #00A5EF; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 12px;">Financial Details</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: #555555; font-size: 10px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Budget Action</label>
                        <select name="lib_action_type" id="edit_lib_action" required onchange="toggleEditActionNumber()" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #00A5EF; color: #00A5EF; font-weight: bold;">
                            <option value="Original Budget">Original Budget</option>
                            <option value="Amendment/Realignment">Amendment/Realignment</option>
                        </select>

                        <div id="edit_action_number_container" style="display: none; margin-top: 10px; background: rgba(0,165,239,0.05); padding: 8px; border-radius: 6px; border: 1px dashed #00A5EF;">
                            <label style="font-size: 10px; font-weight: bold; color: #00A5EF; display: block; margin-bottom: 5px; text-transform: uppercase;">Specify Order:</label>
                            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                <label style="font-size: 12px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="1st" id="edit_radio_1st"> 1st
                                </label>
                                <label style="font-size: 12px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="2nd" id="edit_radio_2nd"> 2nd
                                </label>
                                <label style="font-size: 12px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="3rd" id="edit_radio_3rd"> 3rd
                                </label>
                                <label style="font-size: 12px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="4th" id="edit_radio_4th"> 4th
                                </label>
                                <label style="font-size: 12px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 4px;">
                                    <input type="radio" name="action_number" value="5th" id="edit_radio_5th"> 5th
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label style="color: #555555; font-size: 10px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Budget Amount (Php)</label>
                        <input type="number" step="0.01" name="lib_amount" id="edit_lib_amount" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; box-sizing: border-box;">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 15px;">
                <button type="button" class="btn-cancel" onclick="closeEditLibModal()" style="flex: 1; padding: 14px; background: #e0e0e0; color: #333333; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn-confirm" style="flex: 1; padding: 14px; background: #00A5EF; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Save Updates</button>
            </div>
        </form>
    </div>
</div>

<div id="historyModal" class="modal-overlay" style="display: none; z-index: 10000; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
    
    <div class="edit-card" style="position: relative; max-width: 600px; width: 100%; margin: auto; background: #ffffff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 30px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); text-align: left; max-height: 85vh; display: flex; flex-direction: column;">
        
        <h3 style="color: #00A5EF; text-align: center; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px; margin-top: 0; font-size: 20px;">Document History</h3>
        <p style="color: #666666; text-align: center; margin-bottom: 20px; font-size: 13px;">Tracking timeline for: <strong id="history_display_id" style="color: #333333;"></strong></p>

        
        <div id="historyContentContainer" style="flex: 1; overflow-y: auto; padding-right: 10px; margin-bottom: 15px;">
            <div style="text-align: center; color: #888888;">Loading timeline...</div>
        </div>

        <button type="button" onclick="closeHistoryModal()" style="width: 100%; padding: 14px; background: #e0e0e0; color: #333333; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.2s;">Close History</button>
    </div>
</div>

<script>
function handleEditSubmit(event) {
    event.preventDefault(); 
   
    if(typeof showLoader === 'function') showLoader();
    
    const form = document.getElementById('editIssuanceForm');
    const formData = new FormData(form);

    fetch('/logbook/process_edit.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data.includes("SUCCESS")) {
            
            if (typeof triggerSuccessLoad === 'function') {
                triggerSuccessLoad("Document updated successfully!");
            } else {
                document.getElementById('loaderSpinner').style.display = 'none';
                document.getElementById('loaderSuccess').style.display = 'flex';
                document.getElementById('loaderText').innerText = "Updated Successfully!";
                document.getElementById('loaderText').style.color = "#10b981";
            }
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            if(typeof hideLoader === 'function') hideLoader();
            alert("Database Error: " + data);
        }
    })
    .catch(err => {
        if(typeof hideLoader === 'function') hideLoader();
        alert("Connection failed. Please check your network.");
    });
}
</script>