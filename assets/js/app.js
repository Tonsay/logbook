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
    document.getElementById('modalIssNo').innerText    = data.issuance_number || "N/A";
    document.getElementById('modalDivision').innerText = data.division || "N/A";
    document.getElementById('modalDate').innerText     = data.date_issued || "N/A";
    document.getElementById('modalSubject').innerText  = data.subject || "N/A";
    document.getElementById('modalCreatedBy').innerText = data.added_by || "Admin"; 
    document.getElementById('modalCreatedAt').innerText = data.created_at || "N/A";

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

/* EDIT MODAL LOGIC*/
function openEditModal() {
    if (!currentIssuanceData) {
        alert("Error loading data. Please refresh and try again.");
        return;
    }
    
    if(document.getElementById('edit_display_id')) document.getElementById('edit_display_id').innerText = currentIssuanceData.document_id;
    if(document.getElementById('edit_doc_id_hidden')) document.getElementById('edit_doc_id_hidden').value  = currentIssuanceData.document_id;
    if(document.getElementById('edit_division')) document.getElementById('edit_division').value       = currentIssuanceData.division;
    if(document.getElementById('edit_date_issued')) document.getElementById('edit_date_issued').value    = currentIssuanceData.date_issued;
    if(document.getElementById('edit_category')) document.getElementById('edit_category').value       = currentIssuanceData.category;
    if(document.getElementById('edit_subject')) document.getElementById('edit_subject').value        = currentIssuanceData.subject;
    if(document.getElementById('edit_issuance_number')) document.getElementById('edit_issuance_number').value = currentIssuanceData.issuance_number;
    closeDetailsModal();
    const editModal = document.getElementById('editIssuanceModal');
    if(editModal) editModal.style.display = 'flex';
}

function closeEditModal() {
    const modal = document.getElementById('editIssuanceModal');
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