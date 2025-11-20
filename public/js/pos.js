document.addEventListener("DOMContentLoaded", () => {
    const productGrid = document.getElementById('product-grid');
    const cartContainer = document.getElementById('cart-items-container');
    const scanInput = document.getElementById('scan-input');
    const beepSound = document.getElementById('beepSound');
    const posContainer = document.getElementById('pos-container');
    const btnTimeIn = document.getElementById('btn-time-in');
    const statusIndicator = document.getElementById('status-indicator');
    
    // --- 1. MOCK DATA ---
    const products = [
        { id: '728725969005', name: 'Origami Mouse', price: 8999.00, category: 'Mouse', icon: 'fa-mouse' },
        { id: '649275804502', name: 'Redragon Gloria Pro', price: 1299.00, category: 'Keyboard', icon: 'fa-keyboard' },
        { id: 'KFF1T2358', name: 'VR Shinecon', price: 3450.00, category: 'Electronics', icon: 'fa-tv' },
        { id: 'RDHA300-W22022500515', name: 'Redragon Scepter Pro', price: 1850.00, category: 'Accessories', icon: 'fa-cog' },
        { id: '438360C260A0554', name: 'Rapoo C260', price: 1135.00, category: 'Webcam', icon: 'fa-video' },
        { id: '6931407602891', name: 'Flydigi Vader 4 Pro', price: 3265.00, category: 'Controller', icon: 'fa-gamepad' },
        { id: '6922621507086', name: '8bitdo Ultimate 2 Bluetooth Controller', price: 2192.00, category: 'Controller', icon: 'fa-gamepad' },
        { id: '12503224001426', name: 'Gamesir Cyclone 2 Charging Dock', price: 559.00, category: 'Accessories', icon: 'fa-cog' },
        { id: '6922621505068', name: '8bitdo Case', price: 405.00, category: 'Accessories', icon: 'fa-cog' },
        { id: '6931407602426', name: 'Flydigi Vader 4 Pro Dock', price: 500.00, category: 'Accessories', icon: 'fa-cog' }
    ];

    let cart = [];
    let isClockedIn = false;
    let itemToDeleteId = null; // Store ID for delete confirmation

    // --- 2. INITIALIZATION ---
    renderProducts('all');
    updateClock();
    setInterval(updateClock, 1000);
    scanInput.focus(); // Auto focus on load

    // --- 3. ATTENDANCE SYSTEM ---
    window.toggleAttendance = function() {
        isClockedIn = !isClockedIn;
        
        if (isClockedIn) {
            posContainer.classList.remove('locked');
            btnTimeIn.textContent = "Time Out";
            btnTimeIn.classList.replace('primary-btn', 'delete-btn');
            statusIndicator.classList.replace('offline', 'online');
            statusIndicator.title = "Clocked In";
            showToast("Time In Successful. Terminal Unlocked.", "success");
            scanInput.focus();
        } else {
            btnTimeIn.onclick = function () {
                showToast("Time Out Successful. Redirecting to Login Page", "info");
                window.location.href = "https://l-and-d-tech-store.gamer.gd/auth/logout";
            };
        }
    }

    // --- 4. PRODUCT RENDERER ---
    window.filterCategory = function(category) {
        // Update Tabs
        document.querySelectorAll('.cat-btn').forEach(btn => {
            btn.classList.remove('active');
            if(btn.innerText === (category === 'all' ? 'All' : category)) btn.classList.add('active');
        });
        renderProducts(category);
    }

    function renderProducts(category) {
        productGrid.innerHTML = '';
        const filtered = category === 'all' ? products : products.filter(p => p.category === category);
        
        filtered.forEach(prod => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.onclick = () => addToCart(prod.id);
            card.innerHTML = `
                <i class="fas ${prod.icon} prod-icon"></i>
                <div class="prod-name">${prod.name}</div>
                <div class="prod-price">₱${prod.price.toLocaleString()}</div>
                <div class="prod-stock">ID: ${prod.id}</div>
            `;
            productGrid.appendChild(card);
        });
    }

    // --- 5. CART LOGIC ---
    window.addToCart = function(id) {
        if (!isClockedIn) return;

        const prod = products.find(p => p.id === id);
        if (!prod) {
            showToast("Product not found!", "error");
            return;
        }

        // Play Beep
        beepSound.currentTime = 0;
        beepSound.play().catch(()=>{});

        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ ...prod, qty: 1 });
        }
        
        updateCartUI();
        scanInput.value = '';
        scanInput.focus();
    }

    function updateCartUI() {
        cartContainer.innerHTML = '';
        let subtotal = 0;

        if (cart.length === 0) {
            cartContainer.innerHTML = `
                <div class="empty-cart-msg">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Cart is empty</p>
                </div>`;
        } else {
            cart.forEach(item => {
                const total = item.price * item.qty;
                subtotal += total;
                
                const el = document.createElement('div');
                el.className = 'cart-item';
                el.innerHTML = `
                    <div class="item-info">
                        <h4>${item.name}</h4>
                        <small>@ ₱${item.price.toLocaleString()}</small>
                    </div>
                    <div class="item-controls">
                        <div class="qty-control">
                            <button class="qty-btn" onclick="adjustQty('${item.id}', -1)">-</button>
                            <div class="qty-val">${item.qty}</div>
                            <button class="qty-btn" onclick="adjustQty('${item.id}', 1)">+</button>
                        </div>
                        <div class="item-total">₱${total.toLocaleString()}</div>
                        <i class="fas fa-trash remove-item-btn" onclick="initiateDeleteItem('${item.id}')"></i>
                    </div>
                `;
                cartContainer.appendChild(el);
            });
        }

        // Math
        const vat = subtotal * 0.12;
        const total = subtotal + vat;

        document.getElementById('summary-subtotal').innerText = `₱ ${subtotal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('summary-vat').innerText = `₱ ${vat.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('summary-total').innerText = `₱ ${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        
        // Update Payment Modal Total
        document.getElementById('pay-modal-total').innerText = `₱ ${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    }

    window.adjustQty = function(id, change) {
        const item = cart.find(x => x.id === id);
        if (item) {
            item.qty += change;
            if (item.qty <= 0) initiateDeleteItem(id); // If qty drops to 0, ask to delete
            else updateCartUI();
        }
    }

    // --- NEW: CONFIRMATION MODALS ---

    // 1. Delete Single Item
    window.initiateDeleteItem = function(id) {
        itemToDeleteId = id;
        const item = cart.find(x => x.id === id);
        if(item) {
            document.getElementById('del-item-name').innerText = item.name;
            const modal = document.getElementById('modal-delete-item');
            modal.classList.remove('hidden');
        }
    }

    window.executeDeleteItem = function() {
        if(itemToDeleteId) {
            cart = cart.filter(x => x.id !== itemToDeleteId);
            updateCartUI();
            closeModal('modal-delete-item');
            showToast("Item removed from cart", "info");
            itemToDeleteId = null;
        }
    }

    // 2. Clear Entire Cart
    window.confirmClearCart = function() {
        if(cart.length === 0) {
            showToast("Cart is already empty", "error");
            return;
        }
        const modal = document.getElementById('modal-clear-cart');
        modal.classList.remove('hidden');
    }

    window.executeClearCart = function() {
        cart = [];
        updateCartUI();
        closeModal('modal-clear-cart');
        showToast("Cart cleared", "info");
    }

    // --- 6. SCANNER SIMULATION ---
    scanInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            if (query) {
                // Check if it matches an ID exactly (Barcode scan behavior)
                const directMatch = products.find(p => p.id === query);
                if (directMatch) {
                    addToCart(directMatch.id);
                } else {
                    // FIXED: Use Toast instead of standard alert
                    showToast("Simulation: In real app, this searches db.", "info");
                }
            }
        }
    });

    // --- 7. PAYMENT & RECEIPT ---
    window.openPaymentModal = function() {
        if (cart.length === 0) {
            showToast("Cart is empty!", "error");
            return;
        }
        document.getElementById('cash-received').value = '';
        document.getElementById('change-amount').innerText = '₱ 0.00';
        document.getElementById('btn-confirm-pay').disabled = true;
        
        // Explicitly show modal
        const modal = document.getElementById('modal-payment');
        modal.classList.remove('hidden');
        
        // Focus input
        setTimeout(() => document.getElementById('cash-received').focus(), 100);
    }

    window.calculateChange = function() {
        const cash = parseFloat(document.getElementById('cash-received').value) || 0;
        
        // Calculate total from cart again to be safe
        const sub = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        const total = sub + (sub * 0.12);

        const change = cash - total;
        const changeEl = document.getElementById('change-amount');
        const btn = document.getElementById('btn-confirm-pay');

        changeEl.innerText = `₱ ${change.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        
        if (change >= 0) {
            changeEl.style.color = "var(--clr-success)";
            btn.disabled = false;
        } else {
            changeEl.style.color = "var(--clr-danger)";
            btn.disabled = true;
        }
    }

    window.setCash = function(amount) {
        document.getElementById('cash-received').value = amount;
        calculateChange();
    }

    window.setExact = function() {
        const sub = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        const total = sub + (sub * 0.12);
        setCash(total.toFixed(2));
    }

    window.processTransaction = function() {
        closeModal('modal-payment');
        
        // Generate Receipt
        const recDate = new Date().toLocaleString();
        document.getElementById('rec-date').innerText = recDate;
        
        let itemsHtml = '';
        let sub = 0;
        cart.forEach(item => {
            const t = item.price * item.qty;
            sub += t;
            itemsHtml += `
                <div class="flex-between">
                    <span>${item.name} x${item.qty}</span>
                    <span>${t.toLocaleString()}</span>
                </div>`;
        });
        const total = sub * 1.12;
        const cash = parseFloat(document.getElementById('cash-received').value);
        
        document.getElementById('receipt-items').innerHTML = itemsHtml;
        document.getElementById('rec-total').innerText = total.toLocaleString(undefined, {minimumFractionDigits:2});
        document.getElementById('rec-cash').innerText = cash.toLocaleString(undefined, {minimumFractionDigits:2});
        document.getElementById('rec-change').innerText = (cash - total).toLocaleString(undefined, {minimumFractionDigits:2});

        const modal = document.getElementById('modal-receipt');
        modal.classList.remove('hidden');

        // Reset Cart
        cart = [];
        updateCartUI();
    }

    // --- UTILITIES ---
    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    window.showToast = function(msg, type) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check' : 'fa-info-circle'}"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    window.closeModal = function(id) {
        document.getElementById(id).classList.add('hidden');
    }
    
    // Theme Toggle (Reusing logic)
    const themeToggle = document.getElementById("theme-toggle");
    themeToggle.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        document.body.classList.toggle("light-mode");
        const isDark = document.body.classList.contains("dark-mode");
        themeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    });
});