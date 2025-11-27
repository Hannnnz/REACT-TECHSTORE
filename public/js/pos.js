document.addEventListener("DOMContentLoaded", () => {
    const productGrid = document.getElementById('product-grid');
    const cartContainer = document.getElementById('cart-items-container');
    const scanInput = document.getElementById('scan-input');
    const beepSound = document.getElementById('beepSound');
    const posContainer = document.getElementById('pos-container');
    const btnTimeIn = document.getElementById('btn-time-in');
    const statusIndicator = document.getElementById('status-indicator');

    // --- CATEGORY ICONS ---
    const categoryIcons = {
        "Electronics": "fa-plug",
        "Keyboard": "fa-keyboard",
        "Mouse": "fa-computer-mouse",
        "Controller": "fa-gamepad",
        "Speaker": "fa-volume-high",
        "Headset": "fa-headphones",
        "Microphone": "fa-microphone",
        "Webcam": "fa-video",
        "Accessories": "fa-box"
    };

    products = products.map(p => ({ ...p, icon: categoryIcons[p.category] || "fa-box" }));
    renderProducts("all");

    let cart = [];
    let itemToDeleteId = null;

    // --- Clock / Attendance ---
    let isClockedIn = localStorage.getItem('isClockedIn') === 'true';
    updateLockUI();

    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    window.toggleAttendance = function() {
        isClockedIn = !isClockedIn;
        localStorage.setItem('isClockedIn', isClockedIn);
        updateLockUI();
        if (isClockedIn) showToast("Time In Successful. Terminal Unlocked.", "success");
        else showToast("Time Out Successful. Redirecting to Login Page", "info");
        if (!isClockedIn) setTimeout(() => { window.location.href = "https://l-and-d-tech-store.gamer.gd/auth/logout"; }, 1000);
    }

    function updateLockUI() {
        if(isClockedIn){
            posContainer.classList.remove('locked');
            btnTimeIn.textContent = "Time Out";
            btnTimeIn.classList.replace('primary-btn', 'delete-btn');
            statusIndicator.classList.replace('offline','online');
            statusIndicator.title = "Clocked In";
        } else {
            posContainer.classList.add('locked');
            statusIndicator.classList.replace('online','offline');
            statusIndicator.title = "Clocked Out";
        }
    }

    // --- PRODUCT RENDERER ---
    window.filterCategory = function(category) {
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
                <div class="prod-stock">Stock: ${prod.stock}</div>
            `;
            productGrid.appendChild(card);
        });
    }

    // --- CART LOGIC ---
    window.addToCart = function(id) {
        if (!isClockedIn) return;

        const prod = products.find(p => p.id === id);
        if (!prod) return showToast("Product not found!", "error");

        const existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.qty < prod.stock) existing.qty++;
            else return showToast(`Cannot exceed stock (${prod.stock})`, "error");
        } else {
            if (prod.stock > 0) cart.unshift({ ...prod, qty: 1 });
            else return showToast("Out of stock", "error");
        }

        beepSound.currentTime = 0;
        beepSound.play().catch(()=>{});
        updateCartUI();
        scanInput.value = '';
        scanInput.focus();
    }

    window.adjustQty = function(id, change) {
        const item = cart.find(x => x.id === id);
        if (item) {
            const prod = products.find(p => p.id === id);
            const newQty = item.qty + change;
            if (newQty > prod.stock) return showToast(`Cannot exceed stock (${prod.stock})`, "error");
            item.qty = newQty;
            if (item.qty <= 0) initiateDeleteItem(id);
            else updateCartUI();
        }
    }

    function updateCartUI() {
        cartContainer.innerHTML = '';
        let subtotal = 0;

        if (cart.length === 0) {
            cartContainer.innerHTML = `<div class="empty-cart-msg"><i class="fas fa-shopping-basket"></i><p>Cart is empty</p></div>`;
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
                        <small>Stock: ${item.stock}</small>
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

        const vat = subtotal * 0.12;
        const total = subtotal + vat;
        document.getElementById('summary-subtotal').innerText = `₱ ${subtotal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('summary-vat').innerText = `₱ ${vat.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('summary-total').innerText = `₱ ${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('pay-modal-total').innerText = `₱ ${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    }

    // --- DELETE ITEM / CLEAR CART ---
    window.initiateDeleteItem = function(id) {
        itemToDeleteId = id;
        const item = cart.find(x => x.id === id);
        if(item) {
            document.getElementById('del-item-name').innerText = item.name;
            document.getElementById('modal-delete-item').classList.remove('hidden');
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
    window.confirmClearCart = function() {
        if(cart.length === 0) return showToast("Cart is already empty", "error");
        document.getElementById('modal-clear-cart').classList.remove('hidden');
    }
    window.executeClearCart = function() {
        cart = [];
        updateCartUI();
        closeModal('modal-clear-cart');
        showToast("Cart cleared", "info");
    }

    // --- SCANNER ---
    scanInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            if (query) {
                const directMatch = products.find(p => p.id === query);
                if (directMatch) addToCart(directMatch.id);
                else showToast("Item not found", "info");
            }
        }
    });

    // --- PAYMENT & RECEIPT ---
    window.openPaymentModal = function() {
        if (cart.length === 0) return showToast("Cart is empty!", "error");
        document.getElementById('cash-received').value = '';
        document.getElementById('change-amount').innerText = '₱ 0.00';
        document.getElementById('btn-confirm-pay').disabled = true;
        document.getElementById('modal-payment').classList.remove('hidden');
        setTimeout(() => document.getElementById('cash-received').focus(), 100);
    }

    window.calculateChange = function() {
        const cash = parseFloat(document.getElementById('cash-received').value) || 0;
        const subtotal = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        const total = parseFloat((subtotal * 1.12).toFixed(2));
        const change = parseFloat((cash - total).toFixed(2));

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
        const subtotal = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        setCash(parseFloat((subtotal * 1.12).toFixed(2)));
    }

window.processTransaction = function(cashierName) {
    closeModal('modal-payment');

    // --- Receipt timestamp ---
    const recDate = new Date().toLocaleString();
    document.getElementById('rec-date').innerText = recDate;

    // --- Cart items ---
    let itemsHtml = '';
    let subtotal = 0;
    cart.forEach(item => {
        const totalItem = item.price * item.qty;
        subtotal += totalItem;
        itemsHtml += `<div class="flex-between"><span>${item.name} x${item.qty}</span><span>₱${totalItem.toLocaleString()}</span></div>`;
    });

    const total = parseFloat((subtotal * 1.12).toFixed(2));
    const cash = parseFloat(document.getElementById('cash-received').value) || 0;
    const change = parseFloat((cash - total).toFixed(2));

    // --- Update the receipt UI ---
    document.getElementById('receipt-items').innerHTML = itemsHtml;
    document.getElementById('rec-total').innerText = `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    document.getElementById('rec-cash').innerText = `₱${cash.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    document.getElementById('rec-change').innerText = `₱${change.toLocaleString(undefined, {minimumFractionDigits: 2})}`;

    document.getElementById('modal-receipt').classList.remove('hidden');

    // --- Generate the real timestamp for DB ---
    const now = new Date();
    const timestamp =
        now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0') + ' ' +
        String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');

    // --- Fill hidden form fields BEFORE screenshot ---
    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('cashier').value = cashierName || 'Unknown';
    document.getElementById('transaction-time').value = timestamp;
    document.getElementById('items').value = JSON.stringify(
        cart.map(item => ({
            product_id: item.id,
            qty: item.qty
        }))
    );

    // --- Generate receipt PNG via html2canvas ---
    html2canvas(document.querySelector(".receipt-paper"), { scale: 3 })
        .then(canvas => {

            // Convert to base64 string
            const imageData = canvas.toDataURL("image/png");

            // Put into hidden input for PHP
            document.getElementById('receipt_image').value = imageData;

            // --- Submit AFTER screenshot is ready ---
            setTimeout(() => {
                document.getElementById('transaction-form').submit();
            }, 5000); // shorter delay; image is already ready
        });

    // --- Clear cart after sending ---
    cart = [];
    updateCartUI();
}


    // --- UTILITIES ---
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

    // Theme toggle
    const themeToggle = document.getElementById("theme-toggle");
    themeToggle.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        document.body.classList.toggle("light-mode");
        themeToggle.innerHTML = document.body.classList.contains("dark-mode")
            ? '<i class="fas fa-sun"></i>'
            : '<i class="fas fa-moon"></i>';
    });
});
