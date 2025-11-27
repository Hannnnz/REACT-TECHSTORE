<?php
$salesTotal = $data['sales']['total'] ?? 0;
$soldTotal = $data['sold']['sold'] ?? 0;
$lowStockTotal = $data['low_stock']['total'] ?? 0;
$topCashier = $data['top_cashier'] ?? [];
$topCashierName = $topCashier['cashier'] ?? 'N/A';
$topCashierTransactions = $topCashier['total_transactions'] ?? 0;
$topCashierSales = $topCashier['total_sales'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore Admin - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url();?>public/css/home.css">
</head>
<body class="light-mode">
    <div id="toast-container"></div>
    <audio id="notifSound" src="<?= base_url();?>public/resources/notif.mp3" preload="auto"></audio>

    <aside id="sidebar">
        <div class="logo-section">
            <div class="logo-left">
                <img src="<?= base_url();?>public/resources/logolight.jpg" alt="TechStore Logo" class="logo-img light-logo">
                <img src="<?= base_url();?>public/resources/logodark.jpg" alt="TechStore Logo" class="logo-img dark-logo">
                <span>TechStore</span>
            </div>
        </div>

        <nav class="main-menu">
            <ul>
                <li class="active" data-section="dashboard"><i class="fas fa-chart-line"></i> <span>Dashboard</span></li>
                <li data-section="products"><i class="fas fa-box-open"></i> <span>Products</span></li>
                <li data-section="inventory"><i class="fas fa-boxes"></i> <span>Inventory</span></li>
                <li data-section="users"><i class="fas fa-users"></i> <span>Users</span></li>
                <li data-section="transactions"><i class="fas fa-receipt"></i> <span>Transactions</span></li>
                <li data-section="reports"><i class="fas fa-file-alt"></i> <span>Reports</span></li>
                <li data-section="applicants"><i class="fas fa-id-card"></i> <span>Applicants</span></li>
            </ul>
        </nav>
    </aside>

    <main id="main-content">
        <header id="top-navbar">
            <div class="nav-left">
                <button id="sidebar-toggle" title="Toggle Menu"><i class="fas fa-bars"></i></button>
                <span class="page-title">Dashboard Overview</span>
            </div>
            <div class="nav-right nav-icons">
                <button id="theme-toggle" title="Toggle Theme">
                    <i class="fas fa-moon"></i>
                </button>
                
                <div class="profile-menu" id="profile-menu"> 
                    <div class="user-profile" id="profile-toggle">
                        <span class="user-name">Admin</span>
                        <i class="fas fa-user-circle"></i>
                        <i class="fas fa-chevron-down profile-chevron"></i>
                    </div>
                    
                    <div class="settings-menu profile-dropdown">
                        <div class="profile-dropdown-header">
                            <h4>Admin</h4>
                            <small><?= get_email(get_user_id()) ?></small>
                        </div>
                        <ul>
                            <li id="account-settings-btn">
                                <i class="fas fa-cog"></i> <span>Account Settings</span>
                            </li>
                            <li id="logout-btn-trigger">
                                <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <section id="content-area">
            <div id="dashboard" class="content-section active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <h3>Total Sales</h3>
                                <p class="stat-value">₱ <?= $salesTotal; ?></p>
                            </div>
                            <i class="fas fa-chart-line stat-icon"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <h3>Products Sold</h3>
                                <p class="stat-value"><?= $soldTotal; ?></p>
                            </div>
                            <i class="fas fa-shopping-bag stat-icon"></i>
                        </div>
                    </div>
                    <div class="stat-card inventory-alert">
                        <div class="stat-header">
                            <div>
                                <h3>Low Stock Items</h3>
                                <p class="stat-value"><?= $lowStockTotal; ?></p>
                            </div>
                            <i class="fas fa-exclamation-circle stat-icon"></i>
                        </div>
                        <span class="trend alert">Action Needed</span>
                    </div>
                </div>
                <div class="chart-container max-w-full mx-auto mt-8 p-2 bg-white rounded-lg shadow-md" style="height: 600px;">
                    <canvas id="salesPieChart" style="width:100%; height:100%;"></canvas>
                </div>
            </div>

            <div id="products" class="content-section">
                <div class="toolbar">
                    <button class="action-btn primary-btn" onclick="openModal('modal-add-product')">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </button>
                        <form action="<?=site_url('');?>" method="get">
                        <?php $q = isset($_GET['q']) ? $_GET['q'] : ''; ?>
                        <select id="category" name="q">
                                    <option value="" selected>All Products</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Keyboard">Keyboard</option>
                                    <option value="Mouse">Mouse</option>
                                    <option value="Controller">Controller</option>
                                    <option value="Speaker">Speaker</option>
                                    <option value="Headset">Headset</option>
                                    <option value="Microphone">Microphone</option>
                                    <option value="Webcam">Webcam</option>
                                    <option value="Accessories">Accessories</option>
                        </select>
                        <button type="submit" class="action-btn"><i class="fas fa-filter"></i> Filter</button>
                        </form>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Unit Price (₱)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(html_escape($all) as $product): ?>
                                <tr data-id="<?= $product['id']; ?>">
                                    <td><?= $product['id']; ?></td>
                                    <td><?= $product['name']; ?></td>
                                    <td><?= $product['category']; ?></td>
                                    <td>₱<?= $product['price']; ?></td>
                                    <td>
                                        <button 
                                            type="button"
                                            class="action-icon edit-btn open-product-edit-modal"
                                            title="Edit Product"
                                            data-id="<?= $product['id']; ?>"
                                            data-name="<?= $product['name']; ?>"
                                            data-category="<?= $product['category']; ?>"
                                            data-price="<?= $product['price']; ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button title="Delete Product" data-id="<?= $product['id']; ?>" class="action-icon delete-btn open-product-delete-modal"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo $page_products;?>
            </div>

            <div id="inventory" class="content-section">
                <div class="toolbar">
                    <button class="action-btn primary-btn" onclick="openModal('modal-record-stock')"><i class="fas fa-truck-loading"></i> Record New Stock</button>
                    <button class="action-btn" onclick="openModal('modal-import-csv')"><i class="fas fa-upload"></i> CSV Entry</button>
                    <button class="action-btn" onclick="openModal('modal-export-confirm')"><i class="fas fa-download"></i> Download Data</button>
                    <form action="<?=site_url('');?>" method="get">
                        <?php $q = isset($_GET['q']) ? $_GET['q'] : ''; ?>
                        <select id="category" name="q">
                                    <option value="" selected>All Products</option>
                                    <option value="in">In Stock</option>
                                    <option value="low">Low Stock</option>
                                    <option value="no">No Stock</option>
                        </select>
                        <button type="submit" class="action-btn"><i class="fas fa-filter"></i> Filter</button>
                    </form>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Current Stock</th>
                                <th>Last Restock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(html_escape($inventory) as $inventory): ?>
                                <tr data-id="<?= $inventory['id']; ?>">
                                    <td><?= $inventory['id']; ?></td>
                                    <td><?= $inventory['name']; ?></td>
                                    <td><?= $inventory['stock']; ?></td>
                                    <td><?= $inventory['last_restock']; ?></td>
                                    <?php if ($inventory['stock'] == 0): ?>
                                        <td><span class="status-badge critical">Out of Stock</span></td>

                                    <?php elseif ($inventory['stock'] >= 1 && $inventory['stock'] <= 4): ?>
                                        <td><span class="status-badge warning">Low Stock</span></td>

                                    <?php else: ?>
                                        <td><span class="status-badge success">In Stock</span></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo $page_inventory;?>
            </div>

            <div id="users" class="content-section">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verified At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(html_escape($users) as $user): ?>
                                <tr data-id="<?= $user['id']; ?>">
                                    <td><?= $user['id']; ?></td>
                                    <td><?= $user['username']; ?></td>
                                    <td><?= $user['email']; ?></td>
                                    <td><?= $user['updated_at']; ?></td>
                                    <td>
                                        <button class="action-icon delete-btn open-deactivate-staff-modal" title="Deactivate/Delete" data-id="<?= $user['id']; ?>"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </tbody>
                    </table>
                </div>
                <?php echo $page_users;?>
            </div>

            <div id="transactions" class="content-section">
                <div class="toolbar">
                    <form action="<?=site_url('');?>" method="get">
                        <?php $q = isset($_GET['q']) ? $_GET['q'] : ''; ?>
                        <select id="category" name="q">
                            <option value="" selected>All Cashiers</option>
                            <?php foreach(html_escape($users) as $user): ?>
                                <option value="<?= $user['username']; ?>"><?= $user['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="action-btn"><i class="fas fa-filter"></i> Filter</button>
                    </form>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Timestamp</th>
                                <th>Cashier</th>
                                <th>Total (₱)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(html_escape($transactions) as $transaction): ?>
                                <tr data-id="<?= $transaction['id']; ?>">
                                    <td><?= $transaction['id']; ?></td>
                                    <td><?= $transaction['date']; ?></td>
                                    <td><?= $transaction['cashier']; ?></td>
                                    <td><?= $transaction['total']; ?></td>
                                    <td>
                                        <button class="action-icon edit-btn open-transaction-receipt-modal" title="Receipt" data-receipt="<?= $transaction['receipt']; ?>"><i class="fas fa-receipt"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo $page_transactions;?>
            </div>

            <div id="reports" class="content-section">
                <div class="toolbar">
                    <button class="action-btn primary-btn" onclick="openModal('modal-generate-report')"><i class="fas fa-chart-bar"></i> Generate Report</button>
                </div>
        
                <div class="report-box-grid">
                    <div class="report-box">
                        <h3><i class="fas fa-coins"></i> Sales Summary: <?= date('F Y'); ?></h3>
                        <p>Total Sales: ₱ <?= $salesTotal; ?></p>
                        <p>Total Transactions: <?= $data['transacts']['total'] ?? 0; ?></p>
                        <p>Products Sold: <?= $soldTotal; ?></p>
                        <p class="trend up"><i class="fas fa-arrow-up"></i>Monthly Totals</p>
                    </div>
                    <div class="report-box">
                        <h3><i class="fas fa-user-tie"></i> Top Cashier (<?= date('M'); ?>)</h3>
                        <p>Cashier: <?= $topCashierName; ?></p>
                        <p>Total Transactions: <?= $topCashierTransactions; ?></p>
                        <p>Total Sales Handled: ₱ <?= number_format($topCashierSales, 2); ?></p>
                        <p class="trend up"><i class="fas fa-trophy"></i> Best Performer</p>
                    </div>
                </div>

                <div class="placeholder-chart">
                    <h3 style="font-family: 'Playfair Display', serif; margin-top: 3px; text-align: center;">Top 5 Selling Products (Units Sold)</h3>
                    <div class="table-container">
                        <table class="data-table top-products-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Product Name</th>
                                    <th>Units Sold</th>
                                    <th>Sales (₱)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rank = 1;
                                foreach ($data['top_products'] as $product): ?>
                                    <tr>
                                        <td><?= $rank++; ?></td>
                                        <td><?= $product['name']; ?></td>
                                        <td><?= $product['units_sold']; ?></td>
                                        <td><?= number_format($product['revenue'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="applicants" class="content-section">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Applicant Name</th>
                                <th>Email</th>
                                <th>Date Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(html_escape($applicants) as $applicant): ?>
                                <tr data-id="<?= $user['id']; ?>">
                                    <td><?= $applicant['id']; ?></td>
                                    <td><?= $applicant['username']; ?></td>
                                    <td><?= $applicant['email']; ?></td>
                                    <td><?= $applicant['updated_at']; ?></td>
                                    <td>
                                        <button class="action-icon success-btn open-applicant-verify-modal" title="Verify/Approve" data-id="<?= $applicant['id']; ?>" data-name="<?= htmlspecialchars($applicant['username']); ?>"><i class="fas fa-check"></i></button>
                                        <button class="action-icon delete-btn open-applicant-reject-modal" title="Reject/Delete" data-id="<?= $applicant['id']; ?>"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php echo $page_applicants;?>
            </div>

        </section>
    </main>   
    <?php
      include APP_DIR.'views/modals/_account.php';
      include APP_DIR.'views/modals/_logout.php';
      include APP_DIR.'views/modals/_report.php';
      include APP_DIR.'views/modals/applicant_Delete.php';
      include APP_DIR.'views/modals/applicant_Verify.php';
      include APP_DIR.'views/modals/inventory_Export.php';
      include APP_DIR.'views/modals/inventory_Import.php';
      include APP_DIR.'views/modals/inventory_Update.php';
      include APP_DIR.'views/modals/product_Add.php';
      include APP_DIR.'views/modals/product_Delete.php';
      include APP_DIR.'views/modals/product_Update.php';
      include APP_DIR.'views/modals/staff_Barcode.php';
      include APP_DIR.'views/modals/staff_Delete.php';
      include APP_DIR.'views/modals/transaction_Receipt.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        window.cashierSalesData = <?php echo json_encode($data['cashier_sales'] ?? []); ?>;
    </script>
    <script src="<?= base_url();?>public/js/script.js"></script>
    <?php toast_alert(); ?>
</body>
</html>