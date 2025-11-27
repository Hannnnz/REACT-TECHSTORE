document.body.classList.remove("ready");
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
            if(targetSection.id === 'reports') newTitle = 'Reports';
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
            document.body.classList.add("loading");
            
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

document.querySelectorAll('.open-product-delete-modal').forEach(btn => {
    btn.addEventListener('click', () => {
        const productId = btn.dataset.id;
        const form = document.getElementById('product-delete-form');

        form.action = `product/soft-delete/${productId}`;
        openModal('modal-product-delete-confirm');
    });
});

document.querySelectorAll('.open-applicant-reject-modal').forEach(btn => {
    btn.addEventListener('click', () => {
        const userId = btn.dataset.id;
        const form = document.getElementById('applicant-reject-form'); // or another form if you want separate

        form.action = `applicant/reject/${userId}`;
        openModal('modal-applicant-reject-confirm');
    });
});

document.querySelectorAll('.open-deactivate-staff-modal').forEach(btn => {
    btn.addEventListener('click', () => {
        const userId = btn.dataset.id;
        const form = document.getElementById('staff-deactivate-form'); // or another form if you want separate

        form.action = `staff/deactivate/${userId}`;
        openModal('modal-deactivate-staff-confirm');
    });
});

document.querySelectorAll('.open-product-edit-modal').forEach(btn => {
    btn.addEventListener('click', () => {

        const productId = btn.dataset.id;
        const form = document.getElementById('form-edit-product');

        // Auto-fill fields
        document.getElementById('edit_product_name').value = btn.dataset.name;
        document.getElementById('edit_category').value = btn.dataset.category;
        document.getElementById('edit_unit_price').value = btn.dataset.price;
        document.getElementById('edit_product_id').value = btn.dataset.id;
        document.getElementById('edit_id').value = btn.dataset.id;

        // Open modal
        openModal('modal-edit-product');
    });
});

document.querySelectorAll('.open-applicant-verify-modal').forEach(btn => {
    btn.addEventListener('click', () => {
        const applicantId = btn.dataset.id;
        const applicantName = btn.dataset.name;
        const form = document.getElementById('applicant-verify-form');
        const message = document.getElementById('verify-message');

        // Set dynamic action URL with applicant ID
        const baseAction = "applicant/verify";
        form.action = `${baseAction}/${applicantId}`;

        // Update modal message with applicant name
        message.innerHTML = `Approve <strong>${applicantName}</strong> as a new employee? This will move them to the Users list.`;

        // Open modal
        openModal('modal-verify-applicant');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.open-transaction-receipt-modal').forEach(button => {
        button.addEventListener('click', function() {
            const receiptFile = this.dataset.receipt;
            const imgEl = document.getElementById('receipt-img');
            imgEl.src = "/public/uploads/" + receiptFile;
            imgEl.alt = "Receipt " + receiptFile;
            document.getElementById('modal-print-receipt').classList.remove('hidden');
        });
    });
});

function downloadReceiptAsPDF(button) {
    const { jsPDF } = window.jspdf;
    const img = document.getElementById('receipt-img');
    if (!img.src) return alert("Receipt image not loaded!");

    // --- Show processing feedback ---
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    button.disabled = true;

    setTimeout(() => {
        const pdf = new jsPDF({
            orientation: "portrait",
            unit: "px",
            format: [img.width, img.height]
        });

        pdf.addImage(img, 'PNG', 0, 0, img.width, img.height);

        // Filename same as image
        const filename = img.src.split('/').pop().replace(/\.[^/.]+$/, ".pdf");
        pdf.save(filename);

        // Restore button
        button.innerHTML = originalText;
        button.disabled = false;
    }, 100); // slight delay to show spinner
}

document.addEventListener('DOMContentLoaded', () => {

    function exportInventoryCSV() {
        const table = document.querySelector('#inventory .data-table');
        if (!table) return;

        let csvContent = '';
        // Headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => `"${th.innerText}"`);
        csvContent += headers.join(',') + '\n';

        // Rows
        table.querySelectorAll('tbody tr').forEach(row => {
            const rowData = Array.from(row.querySelectorAll('td')).map((td, index) => {
                let text = td.innerText.trim();

                // Prepend single quote only for the ID column (assume it's the first column)
                if (index === 0) text = `'${text}`;

                return `"${text}"`;
            });
            csvContent += rowData.join(',') + '\n';
        });

        // Download CSV
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'inventory_data.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Replace handleFormSubmit for this modal
    const exportBtn = document.querySelector('#modal-export-confirm .primary-btn');
    exportBtn.addEventListener('click', function() {
        const originalText = this.innerText;
        this.innerText = 'Download started...'; // Show feedback
        this.disabled = true;

        exportInventoryCSV(); // Trigger CSV download

        // Restore button after 2 seconds
        setTimeout(() => {
            this.innerText = originalText;
            this.disabled = false;
            closeModal('modal-export-confirm');
        }, 2000);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('csv-file-input');
    const fileNameDisplay = document.getElementById('file-name-display');
    const form = document.getElementById('import-csv-form');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.backgroundColor = '#f0f0f0';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.backgroundColor = 'transparent';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.backgroundColor = 'transparent';
        const files = e.dataTransfer.files;
        if (files.length && files[0].type === 'text/csv') {
            fileInput.files = files; // okay here since user dropped a file
            fileNameDisplay.textContent = files[0].name;
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = fileInput.files[0].name;
        }
    });

    form.addEventListener('submit', (e) => {
        if (!fileInput.files.length) {
            e.preventDefault();
        }
    });
});

function generatePDFReport() {
    // Open the PDF in a new tab
    window.open('/report', '_blank');
    // Close the modal
    closeModal('modal-generate-report');
}

document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('salesPieChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const rawData = Array.isArray(window.cashierSalesData) ? window.cashierSalesData : [];
    const labels = rawData.length ? rawData.map(item => item.cashier) : ['No sales data yet'];
    const totalSales = rawData.length ? rawData.map(item => Number(item.total_sales) || 0) : [0];
    const totalTransactions = rawData.length ? rawData.map(item => Number(item.total_transactions) || 0) : [0];
    let salesChartInstance = null;

    const renderSalesChart = () => {
        const computed = getComputedStyle(document.body);
        const textColor = (computed.getPropertyValue('--clr-text-main') || '#f3f4f6').trim();
        const borderColor = (computed.getPropertyValue('--clr-border') || 'rgba(148,163,184,0.3)').trim();
        const primaryColor = (computed.getPropertyValue('--clr-primary') || '#38bdf8').trim();
        const cardBg = (computed.getPropertyValue('--clr-card-bg') || '#111827').trim();
        const hasData = rawData.length > 0;

        const chartBackgroundPlugin = {
            id: 'chartBackground',
            beforeDraw(chart) {
                const { ctx, chartArea } = chart;
                if (!chartArea) return;
                ctx.save();
                ctx.fillStyle = cardBg;
                ctx.fillRect(chartArea.left, chartArea.top, chartArea.width, chartArea.height);
                ctx.restore();
            }
        };

        const emptyStatePlugin = {
            id: 'emptyStateMessage',
            afterDraw(chart) {
                if (hasData) return;
                const {ctx, chartArea: {width, height}, top, left} = chart;
                ctx.save();
                ctx.fillStyle = textColor || '#9ca3af';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = '600 18px "Poppins", sans-serif';
                ctx.fillText('No sales activity recorded yet', left + width / 2, top + height / 2);
                ctx.restore();
            }
        };

        Chart.defaults.color = textColor || '#f3f4f6';
        Chart.defaults.font.family = 'Poppins, "Segoe UI", sans-serif';

        if (salesChartInstance) {
            salesChartInstance.destroy();
        }

        salesChartInstance = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Sales (₱)',
                        data: totalSales,
                        backgroundColor: hasData ? primaryColor : 'rgba(59,130,246,0.25)',
                        borderColor: primaryColor || '#3b82f6',
                        borderWidth: 2,
                        borderRadius: 8,
                        maxBarThickness: 32
                    },
                    {
                        label: 'Total Transactions',
                        data: totalTransactions,
                        backgroundColor: hasData ? 'rgba(253,186,116,0.55)' : 'rgba(253,186,116,0.2)',
                        borderColor: '#fbbf24',
                        borderWidth: 2,
                        borderRadius: 8,
                        maxBarThickness: 32
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: 16 },
                scales: {
                    x: {
                        ticks: {
                            color: textColor || '#f3f4f6',
                            beginAtZero: true,
                            callback: function(value) {
                                return hasData ? value : '';
                            }
                        },
                        grid: {
                            color: borderColor || 'rgba(148,163,184,0.25)',
                            borderDash: [4, 4]
                        }
                    },
                    y: {
                        ticks: {
                            color: textColor || '#f3f4f6'
                        },
                        grid: { color: 'transparent' }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor || '#f3f4f6', boxWidth: 20, padding: 10 }
                    },
                    tooltip: {
                        enabled: hasData,
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Total Sales (₱)') {
                                    return '₱ ' + Number(context.raw || 0).toLocaleString();
                                }
                                return context.dataset.label + ': ' + Number(context.raw || 0).toLocaleString();
                            }
                        }
                    }
                }
            },
            plugins: [chartBackgroundPlugin, emptyStatePlugin]
        });
    };

    renderSalesChart();

    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            setTimeout(renderSalesChart, 60); // wait for classes to switch
        });
    }
});



document.body.classList.add("ready");
