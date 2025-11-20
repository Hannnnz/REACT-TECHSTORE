document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.getElementById("sidebar-toggle");
    const themeToggle = document.getElementById("theme-toggle");
    const body = document.body;
    const navItems = document.querySelectorAll("#sidebar li[data-section]");
    const contentSections = document.querySelectorAll(".content-section");
    const pageTitleElement = document.querySelector(".page-title");
    const themeIcon = themeToggle.querySelector("i");
    const profileMenu = document.getElementById("profile-menu");
    const profileToggle = document.getElementById("profile-toggle");

    // --- 1. THEME HANDLING ---
    function applyTheme(isDark) {
        if (isDark) {
            body.classList.add("dark-mode");
            body.classList.remove("light-mode");
            themeIcon.classList.replace("fa-moon", "fa-sun");
            localStorage.setItem("theme", "dark-mode");
        } else {
            body.classList.replace("dark-mode", "light-mode");
            themeIcon.classList.replace("fa-sun", "fa-moon");
            localStorage.setItem("theme", "light-mode");
        }
    }
    const storedTheme = localStorage.getItem("theme");
    applyTheme(storedTheme === "dark-mode");
    themeToggle.addEventListener("click", () => applyTheme(!body.classList.contains("dark-mode")));

    // --- 2. NAVIGATION & PERSISTENCE ---
    // Toggle Sidebar
    sidebarToggle.addEventListener("click", () => {
        if (window.innerWidth > 768) sidebar.classList.toggle("collapsed");
        else sidebar.classList.toggle("visible");
    });

    // Switch Sections
    function switchSection(targetId) {
        navItems.forEach(i => i.classList.remove("active"));
        contentSections.forEach(s => s.classList.remove("active"));

        // Activate logic
        const targetNavItem = document.querySelector(`#sidebar li[data-section="${targetId}"]`);
        const targetSection = document.getElementById(targetId);

        if (targetNavItem && targetSection) {
            targetNavItem.classList.add("active");
            targetSection.classList.add("active");
            
            // Update Title (Check if h2 exists first, since we removed some)
            let newTitle = 'Dashboard';
            if(targetSection.id === 'dashboard') newTitle = 'Dashboard Overview';
            if(targetSection.id === 'products') newTitle = 'Product Management';
            if(targetSection.id === 'inventory') newTitle = 'Inventory Management';
            if(targetSection.id === 'users') newTitle = 'User Management';
            if(targetSection.id === 'transactions') newTitle = 'Transactions';
            if(targetSection.id === 'applicants') newTitle = 'Applicant Verification';

            pageTitleElement.textContent = newTitle;
            
            // Save to LocalStorage
            localStorage.setItem('activeSection', targetId);
        }
    }

    navItems.forEach(item => {
        item.addEventListener("click", () => {
            switchSection(item.getAttribute("data-section"));
            if (window.innerWidth <= 768) sidebar.classList.remove("visible");
        });
    });

    // Load saved section on reload
    const savedSection = localStorage.getItem('activeSection');
    if (savedSection) {
        switchSection(savedSection);
    }

    // --- 3. PROFILE MENU ---
    if (profileToggle) {
        profileToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle("active");
        });
    }
    document.addEventListener("click", (e) => {
        if (profileMenu && !profileMenu.contains(e.target)) profileMenu.classList.remove("active");
    });
    document.getElementById('logout-btn-trigger').addEventListener('click', () => openModal('logout-modal'));
    document.getElementById('account-settings-btn').addEventListener('click', () => openModal('settings-modal'));

    // --- 4. MODAL SYSTEM ---
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.remove('hidden');
    }

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.add('hidden');
    }

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModal(overlay.id);
        });
    });

    // --- 5. TOAST NOTIFICATION SYSTEM ---
    const notifSound = document.getElementById('notifSound');
    
    function playSound() {
        notifSound.currentTime = 0;
        notifSound.play().catch(() => {});
    }

    window.showToast = function(message, type = 'success') {
        playSound();
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
        
        const bgClass = type === 'success' ? 'bg-green-600' : 
                        type === 'error' ? 'bg-red-600' : 'bg-blue-600';

        toast.className = `toast ${bgClass}`;
        toast.innerHTML = `
            <div style="display:flex; align-items:center; gap:10px">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            </div>
            <button class="close-toast">&times;</button>
        `;

        toastContainer.appendChild(toast);

        // Close logic
        toast.querySelector('.close-toast').addEventListener('click', () => {
            toast.style.animation = "fadeOut 0.4s forwards";
            setTimeout(() => toast.remove(), 400);
        });

        // Auto dismiss
        setTimeout(() => {
            toast.style.animation = "fadeOut 0.4s forwards";
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // --- 6. SIMULATED FORM SUBMISSIONS ---
    window.handleFormSubmit = function(modalId) {
        const btn = document.querySelector(`#${modalId} .primary-btn`) || document.querySelector(`#${modalId} .delete-btn`);
        const originalText = btn.innerHTML;
        const form = document.querySelector(`#${modalId} form`);
        
        // Loading State
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;

        // Simulate Delay
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            form.submit();
            closeModal(modalId);
            
            // Optional: Reset form inputs if exists
            if(form) form.reset();

        }, 1000); // 1 second delay
    }

    // --- 7. CSV UPLOAD LOGIC (New) ---
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('csv-file-input');
    const fileNameDisplay = document.getElementById('file-name-display');
    const uploadBtn = document.getElementById('btn-upload-csv');

    // Trigger File Input on Click
    if (dropZone) {
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });
    }

    // Handle File Selection (Click Method)
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileNameDisplay.textContent = "Selected: " + e.target.files[0].name;
                dropZone.style.borderColor = "var(--clr-success)";
                dropZone.style.backgroundColor = "rgba(16, 185, 129, 0.05)";
            }
        });
    }

    // Handle Drag & Drop Visuals
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight on drag
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.style.borderColor = "var(--clr-primary)";
                dropZone.style.backgroundColor = "var(--clr-hover-bg)";
            }, false);
        });

        // Un-highlight on leave
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.style.borderColor = "var(--clr-border)";
                dropZone.style.backgroundColor = "transparent";
            }, false);
        });

        // Handle Drop
        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0 && files[0].type === "text/csv") {
                fileInput.files = files; // Assign dropped files to input
                fileNameDisplay.textContent = "Selected: " + files[0].name;
                dropZone.style.borderColor = "var(--clr-success)";
                dropZone.style.backgroundColor = "rgba(16, 185, 129, 0.05)";
            } else {
                showToast("Please upload a valid CSV file.", "error");
            }
        });
    }

    // Handle Upload Button Click
    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => {
            if (!fileInput.files || fileInput.files.length === 0) {
                showToast("Please select a CSV file first.", "error");
                return;
            }
            // Call your existing submit simulation
            handleFormSubmit('modal-import-csv', 'Inventory imported successfully!');
            
            // Reset the form after a slight delay
            setTimeout(() => {
                fileNameDisplay.textContent = "";
                dropZone.style.borderColor = "var(--clr-border)";
                dropZone.style.backgroundColor = "transparent";
                fileInput.value = "";
            }, 1500);
        });
    }
});