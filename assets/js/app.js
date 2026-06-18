/* GLOBAL VARIABLES */
let currentIssuanceData = null; 

/* SIDEBAR */
function toggleSidebar() {
    document.body.classList.toggle('sidebar-collapsed');
}

/* DETAILS MODAL */
function showDetails(data) {
    currentIssuanceData = data;
    
    document.getElementById('modalCategory').innerText = data.category || "N/A";
    document.getElementById('modalDocID').innerText    = data.document_id || "N/A";
    
    let isLib = data.category && (data.category.includes('LIB') || data.category.includes('Line-Item'));
    if (isLib) {
        document.getElementById('modalIssNo').innerText = data.project_number || "N/A";
    } else {
        document.getElementById('modalIssNo').innerText = data.issuance_number || "N/A";
    }
    
    document.getElementById('modalDivision').innerText = data.division || "N/A";
    document.getElementById('modalDate').innerText     = data.date_issued || "N/A";
    
    document.getElementById('modalSubject').innerText  = (isLib && data.project_desc) ? data.project_desc : (data.subject || "N/A");
    
    document.getElementById('modalCreatedBy').innerText = data.added_by || "Admin"; 
    document.getElementById('modalCreatedAt').innerText = data.created_at || "N/A";

    const libSection = document.getElementById('lib_financial_section');
    if (libSection) {
        if (isLib) {
            libSection.style.display = 'block';
            document.getElementById('detail_project_num').innerText = data.project_number || 'N/A';
            document.getElementById('detail_action').innerText = data.action_type || 'N/A';
            
            const start = data.start_month || '';
            const end = data.end_month || '';
            const year = data.duration_year || '';
            document.getElementById('detail_duration').innerText = `${start} to ${end} ${year}`;
            
            const amount = parseFloat(data.amount) || 0;
            document.getElementById('detail_amount').innerText = "₱ " + amount.toLocaleString('en-US', {minimumFractionDigits: 2});
        } else {
            libSection.style.display = 'none';
        }
    }

    const attachBtn = document.getElementById('attachBtn');
    if(attachBtn) attachBtn.href = "attach_file.php?id=" + data.document_id;
    
    const historyBtn = document.getElementById('historyBtn');
    if(historyBtn) {
        historyBtn.href = "javascript:void(0);"; 
        historyBtn.onclick = function(event) {
            event.preventDefault();
            openHistoryModal(data.document_id);
        };
    }
    const editBtn = document.getElementById('editBtn');
    if(editBtn) {
        editBtn.href = "javascript:void(0);"; 
        editBtn.onclick = function(event) {
            event.preventDefault();
            openEditModal();
        };
    }

    const detailsModal = document.getElementById('detailsModal');
    if(detailsModal) {
        detailsModal.classList.add('active');
        detailsModal.style.display = 'flex';
    }
}

function closeDetailsModal() {
    const modal = document.getElementById('detailsModal');
    if (modal) {
        modal.classList.remove('active');
        modal.style.display = 'none'; 
    }
}

/* EDIT MODAL LOGIC (THE SMART ROUTER) */
function openEditModal() {
    if (!currentIssuanceData) {
        alert("Error loading data. Please refresh and try again.");
        return;
    }
    
    const data = currentIssuanceData;
    let isLib = data.category && (data.category.includes('LIB') || data.category.includes('Line-Item'));

    if (isLib) {
        if(document.getElementById('edit_lib_display_id')) document.getElementById('edit_lib_display_id').innerText = data.document_id;
        if(document.getElementById('edit_lib_doc_id')) document.getElementById('edit_lib_doc_id').value = data.document_id || '';
        if(document.getElementById('edit_lib_project_number')) document.getElementById('edit_lib_project_number').value = data.project_number || data.issuance_number || '';
        
        if (data.date_issued && document.getElementById('edit_lib_date')) {
            let d = new Date(data.date_issued);
            document.getElementById('edit_lib_date').value = d.toISOString().split('T')[0];
        }

        if(document.getElementById('edit_lib_desc')) document.getElementById('edit_lib_desc').value = data.project_desc || data.subject || '';
        if(document.getElementById('edit_lib_start')) document.getElementById('edit_lib_start').value = data.start_month || 'January';
        if(document.getElementById('edit_lib_end')) document.getElementById('edit_lib_end').value = data.end_month || 'December';
        if(document.getElementById('edit_lib_year')) document.getElementById('edit_lib_year').value = data.duration_year || new Date().getFullYear();
        if(document.getElementById('edit_lib_amount')) document.getElementById('edit_lib_amount').value = data.amount || 0;

        let actionVal = data.action_type || 'Original Budget';
        let orderVal = '';

        const match = actionVal.match(/^(1st|2nd|3rd|4th|5th)\s+(Amendment\/Realignment)$/);
        if (match) {
            orderVal = match[1]; 
            actionVal = match[2]; 
        }

        if(document.getElementById('edit_lib_action')) {
            document.getElementById('edit_lib_action').value = actionVal;
            toggleEditActionNumber(); 
        }

        if (orderVal) {
            let targetRadio = document.querySelector(`#edit_action_number_container input[value="${orderVal}"]`);
            if (targetRadio) targetRadio.checked = true;
        }

        closeDetailsModal();
        const editLibModal = document.getElementById('editLibModal');
        if(editLibModal) editLibModal.style.display = 'flex';

    } else {
        if(document.getElementById('edit_display_id')) document.getElementById('edit_display_id').innerText = data.document_id;
        if(document.getElementById('edit_doc_id_hidden')) document.getElementById('edit_doc_id_hidden').value  = data.document_id;
        if(document.getElementById('edit_division')) document.getElementById('edit_division').value       = data.division;
        if(document.getElementById('edit_date_issued')) document.getElementById('edit_date_issued').value    = data.date_issued;
        if(document.getElementById('edit_category')) document.getElementById('edit_category').value       = data.category;
        if(document.getElementById('edit_subject')) document.getElementById('edit_subject').value        = data.subject;
        if(document.getElementById('edit_issuance_number')) document.getElementById('edit_issuance_number').value = data.issuance_number;
        
        closeDetailsModal();
        const editModal = document.getElementById('editIssuanceModal');
        if(editModal) editModal.style.display = 'flex';
    }
}

function closeEditModal() {
    const modal = document.getElementById('editIssuanceModal');
    if(modal) modal.style.display = 'none';
}

function closeEditLibModal() {
    const modal = document.getElementById('editLibModal');
    if(modal) modal.style.display = 'none';
}

function executeEditSave() {
    const form = document.getElementById('editIssuanceForm');
    if(!form) return;
    const formData = new FormData(form);

    fetch('/logbook/process_edit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data.includes("SUCCESS")) {
            window.location.reload(true); 
        } else {
            alert("Database Error: " + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Failed to connect to the server.");
    });
}

/* DELETE CONFIRMATION MODAL */
function triggerDeleteFromModal() {
    const docIdEl = document.getElementById('modalDocID');
    if(!docIdEl) return;
    
    const docId = docIdEl.innerText.trim();
    if(document.getElementById('deleteTargetID')) document.getElementById('deleteTargetID').innerText = docId;
    
    const finalBtn = document.getElementById('finalDeleteBtn');
    if(finalBtn) {
        finalBtn.onclick = function() {
            window.location.href = '/logbook/delete_issuance.php?id=' + encodeURIComponent(docId);
        };
    }

    closeDetailsModal();
    const delModal = document.getElementById('deleteConfirmModal');
    if(delModal) delModal.style.display = 'flex';
}

function closeDeleteConfirm() {
    const modal = document.getElementById('deleteConfirmModal');
    if(modal) modal.style.display = 'none';
}

/* SETTINGS & LOGOUT MODALS */
function showSettingsModal(event) {
    if(event) event.preventDefault();
    const m = document.getElementById('settingsModal');
    if(m) m.classList.add('active');
}
function closeSettingsModal() {
    const m = document.getElementById('settingsModal');
    if(m) m.classList.remove('active');
}
function showLogoutModal(event) {
    if(event) event.preventDefault();
    const m = document.getElementById('logoutModal');
    if(m) m.classList.add('active');
}
function closeLogoutModal() {
    const m = document.getElementById('logoutModal');
    if(m) m.classList.remove('active');
}

/* CLOSE OVERLAYS ON BACKGROUND CLICK */
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        closeDetailsModal();
        closeSettingsModal();
        closeLogoutModal();
        closeEditModal(); 
        closeEditLibModal();
        closeDeleteConfirm();
        closeHistoryModal(); 
        
        if(typeof closeDeleteModal === 'function') closeDeleteModal();
        if(typeof closeEditDivisionModal === 'function') closeEditDivisionModal();
    }
});

/* DARK MODE TOGGLE */
document.addEventListener("DOMContentLoaded", () => {
    const themeToggleBtn = document.getElementById("themeToggle");
    if (themeToggleBtn) {
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
            themeToggleBtn.innerHTML = "☀️";
        }
        themeToggleBtn.onclick = () => {
            document.body.classList.toggle("dark-mode");
            const isDark = document.body.classList.contains("dark-mode");
            localStorage.setItem("theme", isDark ? "dark" : "light");
            themeToggleBtn.innerHTML = isDark ? "☀️" : "🌙";
        };
    }
});

/* DATE PICKER LOGIC */
document.addEventListener("DOMContentLoaded", () => {
    const datePicker = document.getElementById('date_issued_input');
    const yearPrefix = document.getElementById('year_prefix');
    const numInput = document.getElementById('issuance_num_only');

    if (datePicker) {
        datePicker.addEventListener('change', function() {
            const dateVal = this.value; 
            if (dateVal) {
                const year = dateVal.split('-')[0];
                if (yearPrefix) yearPrefix.value = year;
                if (numInput) numInput.focus();
            }
        });
    }
});

/* TAB SWITCHER LOGIC*/
function switchTab(arg1, arg2) {
    document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
    document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));

    let tabId = typeof arg1 === 'string' ? arg1 : arg2;
    let activeElement = typeof arg1 === 'string' ? arg2 : arg1.currentTarget;

    const targetTab = document.getElementById(tabId);
    if (targetTab) targetTab.style.display = 'block';
    if (activeElement) activeElement.classList.add('active');
}

/* PASSWORD TOGGLE LOGIC & SVGS */
const eyeOpenSVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
const eyeSlashSVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"></path></svg>`;

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.password-toggle').forEach(btn => {
        btn.innerHTML = eyeOpenSVG;
    });
});

function togglePass(inputId, btn) {
    const input = document.getElementById(inputId);
    if(!input) return;
    if (input.type === "password") {
        input.type = "text";
        btn.innerHTML = eyeSlashSVG;
    } else {
        input.type = "password";
        btn.innerHTML = eyeOpenSVG;
    }
}

/* NOTIFICATION TOAST FADE OUT */
document.addEventListener("DOMContentLoaded", () => {
    const toast = document.getElementById("toastNotification");
    if (toast) {
        setTimeout(() => {
            toast.style.transition = "opacity 0.5s ease";
            toast.style.opacity = "0";
            setTimeout(() => { toast.remove(); }, 500); 
        }, 1500); 
    }
});

/* GLOBAL LOADER LOGIC */
let isShowingSuccess = false; 

window.addEventListener('load', function() {
    if (!isShowingSuccess) {
        hideLoader();
    }
});

function hideLoader() {
    const loader = document.getElementById('globalLoader');
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(() => { loader.style.display = 'none'; }, 400); 
    }
}

function showLoader() {
    const loader = document.getElementById('globalLoader');
    if (loader) {
        const spinner = document.getElementById('loaderSpinner');
        const success = document.getElementById('loaderSuccess');
        const text = document.getElementById('loaderText');
        
        if(spinner) spinner.style.display = 'block';
        if(success) success.style.display = 'none';
        if(text) {
            text.innerText = 'Processing...';
            text.style.color = '#b9e6ff';
            text.style.animation = 'pulseText 1.5s infinite';
        }

        loader.style.display = 'flex';
        setTimeout(() => { loader.style.opacity = '1'; }, 10);
        
        // Escape Hatch: Force close loader if stuck for 8 seconds
        setTimeout(() => { hideLoader(); }, 8000);
    }
}

function triggerSuccessLoad(message) {
    isShowingSuccess = true; 
    const loader = document.getElementById('globalLoader');
    if (loader) {
        loader.style.display = 'flex';
        loader.style.opacity = '1';

        const spinner = document.getElementById('loaderSpinner');
        const success = document.getElementById('loaderSuccess');
        const text = document.getElementById('loaderText');

        if(spinner) spinner.style.display = 'none';
        if(success) success.style.display = 'flex';
        if(text) {
            text.innerText = message || "Success!";
            text.style.color = '#10b981'; 
            text.style.animation = 'none';
        }

        setTimeout(() => {
            hideLoader();
            isShowingSuccess = false;
        }, 1500);
    }
}

/* HISTORY MODAL */
function openHistoryModal(docId) {
    closeDetailsModal(); 
    
    document.getElementById('history_display_id').innerText = docId;
    document.getElementById('historyContentContainer').innerHTML = '<div style="text-align: center; color: #00A5EF; margin-top: 20px; animation: pulseText 1.5s infinite;">Loading timeline...</div>';
    
    document.getElementById('historyModal').style.display = 'flex';
    
    fetch('/logbook/fetch_history.php?id=' + encodeURIComponent(docId))
    .then(response => response.text())
    .then(html => {
        document.getElementById('historyContentContainer').innerHTML = html;
    })
    .catch(error => {
        document.getElementById('historyContentContainer').innerHTML = '<div style="text-align: center; color: #ff4d4d;">Failed to load history connection.</div>';
    });
}

function closeHistoryModal() {
    const histModal = document.getElementById('historyModal');
    if (histModal) histModal.style.display = 'none';
}

/* ADD ENTRY LOGIC */
function openAddEntryModal(categoryName) {
    window.location.href = '/logbook/add_issuance.php?category=' + encodeURIComponent(categoryName);
}

document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const urlCategory = urlParams.get('category');

    if (urlCategory) {
        const categoryDropdown = document.querySelector('select[name="category"], select[name="category_name"]');
        if (categoryDropdown) {
            for (let i = 0; i < categoryDropdown.options.length; i++) {
                if (categoryDropdown.options[i].value === urlCategory || categoryDropdown.options[i].text === urlCategory) {
                    categoryDropdown.selectedIndex = i;
                    break;
                }
            }
        }
    }
});

document.addEventListener("DOMContentLoaded", () => {
    flatpickr(".custom-date-picker", {
        allowInput: true,       
        dateFormat: "Y-m-d",  
        disableMobile: "true"   
    });
});

/* =========================================
   MAGIC ENYE SHORTCUT (Alt + N)
   ========================================= */
document.addEventListener('keydown', function(event) {
    if (event.altKey && (event.key === 'n' || event.key === 'N' || event.code === 'KeyN')) {
        const activeBox = document.activeElement;
        if (activeBox.tagName === 'INPUT' || activeBox.tagName === 'TEXTAREA') {
            event.preventDefault(); 
            const enye = event.shiftKey ? 'Ñ' : 'ñ';
            const start = activeBox.selectionStart;
            const end = activeBox.selectionEnd;
            const currentText = activeBox.value;
            activeBox.value = currentText.slice(0, start) + enye + currentText.slice(end);
            activeBox.selectionStart = activeBox.selectionEnd = start + 1;
        }
    }
});

/* =========================================
   LIB FORM LOGIC (Auto-Fill & Amendment Modification)
   ========================================= */
function autoFillProject() {
    var selector = document.getElementById("projectSelector");
    var newProjInput = document.getElementById("newProjectInput");
    var descBox = document.getElementById("projectDesc");
    
    if (!selector || !newProjInput || !descBox) return; 
    var selectedOption = selector.options[selector.selectedIndex];
    
    newProjInput.style.display = "block";
    newProjInput.required = true;
    
    if (selector.value === "NEW") {
        newProjInput.value = "";
        newProjInput.placeholder = "Format: XX-XX-XX or XX-XX-XX-AA";
        descBox.value = "";
        descBox.readOnly = false;
        descBox.style.background = "#ffffff";
        descBox.placeholder = "Type the new project description here...";
    } else {
        newProjInput.value = selector.value;
        descBox.value = selectedOption.getAttribute("data-desc");
        descBox.readOnly = true;
        descBox.style.background = "rgba(0,0,0,0.05)"; 
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const newProjectInput = document.getElementById('newProjectInput');
    
    if (newProjectInput) {
        newProjectInput.addEventListener('input', function (e) {
            let val = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase(); 
            
            val = val.substring(0, 9); 
            
            let numbers = val.replace(/[A-Z]/g, '').substring(0, 7); 
            let letters = val.replace(/[0-9]/g, '').substring(0, 2);  
            
            let formatted = '';
            
            if (numbers.length > 0) formatted += numbers.substring(0, 2);
            if (numbers.length > 2) formatted += '-' + numbers.substring(2, 5);
            if (numbers.length > 5) formatted += '-' + numbers.substring(5, 7);
            
            if (letters) {
                if (formatted.length > 0) {
                    formatted += '-' + letters;
                } else {
                    formatted += letters;
                }
            }
            
            this.value = formatted;
        });
    }
});

/* =========================================
   EDIT FORM SUBMISSION LOGIC
   ========================================= */
function handleEditSubmit(event) {
    event.preventDefault(); 
   
    if(typeof showLoader === 'function') showLoader();
    
    const standardForm = document.getElementById('editIssuanceForm');
    const libForm = document.getElementById('editLibForm'); 
 
    const form = event.target;
    const formData = new FormData(form);
    
    const fetchUrl = formData.get('category') && formData.get('category').includes('LIB') 
        ? '/logbook/process_edit_lib.php' 
        : '/logbook/process_edit.php';

    fetch(fetchUrl, {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data.includes("SUCCESS") || data.includes("successfully")) {
            if (typeof triggerSuccessLoad === 'function') {
                triggerSuccessLoad("Document updated successfully!");
            } else {
                document.getElementById('loaderSpinner').style.display = 'none';
                document.getElementById('loaderSuccess').style.display = 'flex';
                document.getElementById('loaderText').innerText = "Updated Successfully!";
                document.getElementById('loaderText').style.color = "#10b981";
            }
            setTimeout(() => { window.location.reload(); }, 1500);
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

/* =========================================
   BUDGET ACTION TOGGLE LOGIC
   ========================================= */
function toggleActionNumber() {
    const actionSelect = document.getElementById('lib_action_type');
    const numberContainer = document.getElementById('action_number_container');
    
    if (!actionSelect || !numberContainer) return;

    if (actionSelect.value === 'Amendment/Realignment') {
        numberContainer.style.display = 'block';
    } else {
        numberContainer.style.display = 'none';
        
        document.querySelectorAll('input[name="action_number"]').forEach(radio => {
            radio.checked = false;
        });
    }
}

/* FOR EDIT MODAL TOGGLE */
function toggleEditActionNumber() {
    const actionSelect = document.getElementById('edit_lib_action');
    const numberContainer = document.getElementById('edit_action_number_container');
    
    if (!actionSelect || !numberContainer) return;

    if (actionSelect.value === 'Amendment/Realignment') {
        numberContainer.style.display = 'block';
    } else {
        numberContainer.style.display = 'none';
        
        const radios = numberContainer.querySelectorAll('input[name="action_number"]');
        radios.forEach(radio => radio.checked = false);
    }
}

/* =========================================
   STANDARD ISSUANCE NUMBER FORMATTER
   ========================================= */
document.addEventListener("DOMContentLoaded", () => {
    const issuanceInputs = [
        document.getElementById('edit_issuance_number'),
        document.getElementById('issuance_num_only') 
    ];

    issuanceInputs.forEach(input => {
        if (input) {
            input.removeAttribute('maxlength');
            
            input.addEventListener('input', function (e) {
                let val = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase(); 
                let formatted = '';
                
                if (val.startsWith("20") && val.length >= 4) {
                    let year = val.substring(0, 4);
                    let remaining = val.substring(4);
                    
                    let numbers = remaining.replace(/[A-Z]/g, '').substring(0, 3);
                    let letters = remaining.replace(/[0-9]/g, '').substring(0, 2);
                    
                    formatted += year;
                    if (numbers.length > 0) formatted += '-' + numbers;
                    if (letters.length > 0) formatted += '-' + letters;
                } 
                else {
                    let numbers = val.replace(/[A-Z]/g, '').substring(0, 3);
                    let letters = val.replace(/[0-9]/g, '').substring(0, 2);
                    
                    if (numbers.length > 0) formatted += numbers;
                    if (letters.length > 0) formatted += '-' + letters;
                }
                
                this.value = formatted;
            });
        }
    });
});

/* =========================================
   SMART CATEGORY DROPDOWN REDIRECT
   ========================================= */
document.addEventListener("DOMContentLoaded", () => {
    const categoryDropdown = document.querySelector('select[name="category"]');

    if (categoryDropdown) {
        categoryDropdown.addEventListener('change', function() {
            const selectedValue = this.value;

            if (selectedValue.includes('LIB') || selectedValue.includes('Line-Item')) {
                window.location.href = '/logbook/add_issuance.php?category=' + encodeURIComponent(selectedValue);
            }
        });
    }
});

/* =========================================
   CURRENCY FORMATTER (Auto-Commas)
   ========================================= */
document.addEventListener("DOMContentLoaded", () => {
    
    const amountInputs = document.querySelectorAll('input[name="lib_amount"]');

    amountInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            
            let value = this.value.replace(/[^0-9.]/g, '');

           
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            
            if (value !== '') {
                let formattedParts = value.split('.');
               
                formattedParts[0] = formattedParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                this.value = formattedParts.join('.');
            } else {
                this.value = '';
            }
        });
    });
});