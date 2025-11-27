<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Cashier POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,400;0,600;0,700;1,100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url();?>public/css/pos.css">
</head>
<body class="light-mode pos-body">

    <audio id="beepSound" src="<?= base_url(); ?>public/resources/notif.mp3" preload="auto"></audio>
    <div id="toast-container"></div>

    <header id="pos-navbar">
        <div class="logo-area">
            <img src="<?= base_url();?>public/resources/logolight.jpg" alt="Logo" class="pos-logo light-logo">
            <img src="<?= base_url();?>public/resources/logodark.jpg" alt="Logo" class="pos-logo dark-logo">
            <div>
                <h1>TechStore POS</h1>
                <span id="live-clock">--:-- --</span>
            </div>
        </div>

        <div class="pos-controls">
            <div class="cashier-info">
                <i class="fas fa-user-circle"></i>
                <span><?= get_username(get_user_id()); ?></span>
                <span class="status-dot offline" id="status-indicator" title="Clocked Out"></span>
            </div>
            <button id="theme-toggle" class="icon-btn"><i class="fas fa-moon"></i></button>
            <button id="btn-time-in" class="action-btn primary-btn" onclick="toggleAttendance()">Time In</button>
            </div>
    </header>

    <main id="pos-container" class="locked"> 
        
        <section class="product-section">
            <div class="search-bar-container">
                <div class="input-group search-group">
                    <i class="fas fa-barcode"></i>
                    <input type="text" id="scan-input" placeholder="Scan Barcode or Search Product..." autocomplete="off">
                </div>
                <div class="category-tabs">
                    <button class="cat-btn active" onclick="filterCategory('all')">All</button>
                    <button class="cat-btn" onclick="filterCategory('Electronics')">Electronics</button>
                    <button class="cat-btn" onclick="filterCategory('Keyboard')">Keyboard</button>
                    <button class="cat-btn" onclick="filterCategory('Mouse')">Mouse</button>
                    <button class="cat-btn" onclick="filterCategory('Controller')">Controller</button>
                    <button class="cat-btn" onclick="filterCategory('Speaker')">Speaker</button>
                    <button class="cat-btn" onclick="filterCategory('Headset')">Headset</button>
                    <button class="cat-btn" onclick="filterCategory('Microphone')">Microphone</button>
                    <button class="cat-btn" onclick="filterCategory('Webcam')">Webcam</button>
                    <button class="cat-btn" onclick="filterCategory('Accessories')">Accessories</button>
                </div>
            </div>

            <div id="product-grid" class="product-grid">
            </div>
        </section>

        <aside class="cart-section">
            <div class="cart-header">
                <h3>Current Order</h3>
                <button class="clear-btn" onclick="confirmClearCart()">Clear All</button>
            </div>

            <div class="cart-items" id="cart-items-container">
                <div class="empty-cart-msg">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Cart is empty</p>
                </div>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summary-subtotal">₱ 0.00</span>
                </div>
                <div class="summary-row">
                    <span>VAT (12%)</span>
                    <span id="summary-vat">₱ 0.00</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="summary-total">₱ 0.00</span>
                </div>
                <button class="pay-btn" onclick="openPaymentModal()">
                    CHARGE <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </aside>
    </main>

    <div id="lock-screen">
        <div class="lock-content">
            <i class="fas fa-lock"></i>
            <h2>Terminal Locked</h2>
            <p>Please <strong>Time In</strong> to start selling.</p>
        </div>
    </div>

    <div id="modal-payment" class="modal-overlay hidden">
        <div class="modal-content payment-modal-content">
            <div class="modal-header">
                <h2>Process Payment</h2>
                <button class="modal-close-btn" onclick="closeModal('modal-payment')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="payment-summary">
                    <small>Total Amount Due</small>
                    <h1 id="pay-modal-total">₱ 0.00</h1>
                </div>
                
                <div class="form-group">
                    <label>Cash Received (₱)</label>
                    <div class="input-group big-input">
                        <input type="number" id="cash-received" placeholder="0.00" oninput="calculateChange()">
                    </div>
                </div>

                <div class="quick-cash-btns">
                    <button onclick="setCash(100)">100</button>
                    <button onclick="setCash(500)">500</button>
                    <button onclick="setCash(1000)">1000</button>
                    <button onclick="setExact()">Exact</button>
                </div>

                <div class="change-display">
                    <span>Change:</span>
                    <span id="change-amount">₱ 0.00</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-payment')">Cancel</button>
                <button class="action-btn primary-btn" id="btn-confirm-pay" onclick="processTransaction(CASHIER_NAME)" disabled>
                    <i class="fas fa-receipt"></i> Print & Complete
                </button>
            </div>
        </div>
    </div>

    <div id="modal-receipt" class="modal-overlay hidden">
        <div class="modal-content receipt-content">
            <div class="receipt-paper">
                <div class="receipt-header">
                    <h3>TechStore</h3>
                    <p>Camilmil, Calapan City</p>
                    <p>Date: <span id="rec-date"></span></p>
                    <p>Cashier: <?= get_username(get_user_id()); ?></p>
                </div>
                <hr class="dashed-line">
                <div id="receipt-items"></div>
                <hr class="dashed-line">
                <div class="receipt-totals">
                    <div class="flex-between"><span>Total</span><span id="rec-total"></span></div>
                    <div class="flex-between"><span>Cash</span><span id="rec-cash"></span></div>
                    <div class="flex-between"><span>Change</span><span id="rec-change"></span></div>
                </div>
                <div class="receipt-footer">
                    <p>Thank you for your purchase!</p>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-clear-cart" class="modal-overlay hidden">
        <div class="modal-content confirmation-content">
            <div class="modal-header">
                <h2>Clear Cart</h2>
                <button class="modal-close-btn" onclick="closeModal('modal-clear-cart')">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to remove all items?</p>
            </div>
            <div class="modal-footer">
                <button class="action-btn" onclick="closeModal('modal-clear-cart')">Cancel</button>
                <button class="action-btn delete-btn" onclick="executeClearCart()">Yes, Clear All</button>
            </div>
        </div>
    </div>

    <div id="modal-delete-item" class="modal-overlay hidden">
        <div class="modal-content confirmation-content">
            <div class="modal-header">
                <h2>Remove Item</h2>
                <button class="modal-close-btn" onclick="closeModal('modal-delete-item')">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p>Remove <strong id="del-item-name"></strong> from cart?</p>
            </div>
            <div class="modal-footer">
                <button class="action-btn" onclick="closeModal('modal-delete-item')">Cancel</button>
                <button class="action-btn delete-btn" onclick="executeDeleteItem()">Remove</button>
            </div>
        </div>
    </div>

    <form id="transaction-form" action="<?= site_url('pos/transaction'); ?>" method="POST" style="display:none;">
        <input type="hidden" name="total" id="total">
        <input type="hidden" name="cashier" id="cashier">
        <input type="hidden" id="transaction-time" name="transaction_time">
        <input type="hidden" name="items" id="items"> <!-- JSON string of items -->
        <input type="hidden" name="receipt_image" id="receipt_image">
    </form>

    <script>
        let products = <?= json_encode($products); ?>;
        const CASHIER_NAME = "<?= get_username(get_user_id()); ?>";
    </script>

    <script src="<?= base_url();?>public/js/pos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

</body>
</html>