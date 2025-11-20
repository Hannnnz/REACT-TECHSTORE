<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore Admin - POS System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url();?>public/css/home.css">
</head>
<body class="light-mode">
    <div id="toast-container"></div>
    <audio id="notifSound" src="notif.mp3" preload="auto"></audio>

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
                <!--<li data-section="applicants"><i class="fas fa-id-card"></i> <span>Applicants</span></li>-->
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
                            <small>admin@techstore.com</small>
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
                                <h3>Total Sales (Today)</h3>
                                <p class="stat-value">₱ 78,540.50</p>
                            </div>
                            <i class="fas fa-chart-line stat-icon"></i>
                        </div>
                        <span class="trend up"><i class="fas fa-arrow-up"></i> 12% vs yesterday</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <h3>Net Profit</h3>
                                <p class="stat-value">₱ 25,480.25</p>
                            </div>
                            <i class="fas fa-coins stat-icon"></i>
                        </div>
                        <span class="trend up"><i class="fas fa-arrow-up"></i> 8% vs yesterday</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <h3>Products Sold</h3>
                                <p class="stat-value">215</p>
                            </div>
                            <i class="fas fa-shopping-bag stat-icon"></i>
                        </div>
                        <span class="trend down"><i class="fas fa-arrow-down"></i> 3% vs yesterday</span>
                    </div>
                    <div class="stat-card inventory-alert">
                        <div class="stat-header">
                            <div>
                                <h3>Low Stock Items</h3>
                                <p class="stat-value">12</p>
                            </div>
                            <i class="fas fa-exclamation-circle stat-icon"></i>
                        </div>
                        <span class="trend alert">Action Needed</span>
                    </div>
                </div>
                <div class="chart-container">
                    <div class="placeholder-chart">
                        <h3>Weekly Sales Chart (₱'000)</h3>
                        <div class="bar-chart-visual">
                            <div style="height: 50%;" data-label="₱35k">Mon</div> <div style="height: 80%;" data-label="₱56k">Tue</div> <div style="height: 30%;" data-label="₱21k">Wed</div> <div style="height: 95%;" data-label="₱66k">Thu</div> <div style="height: 70%;" data-label="₱49k">Fri</div> <div style="height: 60%;" data-label="₱42k">Sat</div> <div style="height: 45%;" data-label="₱31k">Sun</div> </div>
                    </div>
                </div>
            </div>

            <div id="products" class="content-section">
                <div class="toolbar">
                    <button class="action-btn primary-btn" onclick="openModal('modal-add-product')">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </button>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search Products...">
                        <button class="action-btn search-btn">Search</button>
                    </div>
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
                                        <a href="<?= site_url('update/'.$product['id']); ?>" class="action-icon edit-btn" title="Update Stock" id="update-inventory-btn"><i class="fas fa-pen"></i></a>
                                        <a href="<?= site_url('soft-delete/'.$product['id']); ?>" title="Delete Product" class="action-icon delete-btn"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="inventory" class="content-section">
                <div class="toolbar">
                    <button class="action-btn primary-btn" onclick="openModal('modal-record-stock')"><i class="fas fa-truck-loading"></i> Record New Stock</button>
                    <button class="action-btn" onclick="openModal('modal-import-csv')"><i class="fas fa-upload"></i> Import (CSV)</button>
                    <button class="action-btn" onclick="openModal('modal-export-confirm')"><i class="fas fa-download"></i> Export Data</button>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search Stock...">
                         <button class="action-btn search-btn">Search</button>
                    </div>
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
                                <th>History</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(html_escape($all) as $inventory): ?>
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
                                    <td><button class="action-icon view-btn" title="View History"><i class="fas fa-history"></i></button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="users" class="content-section">
                <div class="toolbar">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search Users...">
                         <button class="action-btn search-btn">Search</button>
                    </div>
                </div>
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
                                        <button class="action-icon view-btn" title="Print User ID" onclick="openModal('modal-user-barcode')"><i class="fas fa-id-card"></i></button>
                                        <a href="<?= site_url('user-delete/'.$user['id']); ?>" title="Delete User" class="action-icon delete-btn"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="transactions" class="content-section">
                <div class="toolbar">
                    <input type="date" value="2025-10-22">
                    <select><option>All Cashiers</option><option>Fyra Nika Dudas</option></select>
                    <button class="action-btn"><i class="fas fa-filter"></i> Filter</button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date/Time</th>
                                <th>Cashier</th>
                                <th>Total (₱)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TRX-00101</td>
                                <td>2025-10-22 11:45 AM</td>
                                <td>Fyra Nika Dudas</td>
                                <td>3,500.00</td>
                                <td><span class="status-badge success">Completed</span></td>
                                <td>
                                    <button class="action-icon view-btn" title="Print Receipt" onclick="openModal('modal-print-receipt')"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon refund-btn" title="Revert/Void" onclick="openModal('modal-revert-confirm')"><i class="fas fa-undo-alt"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="applicants" class="content-section">
                <div class="toolbar">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search Applicants...">
                         <button class="action-btn search-btn">Search</button>
                    </div>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Applicant Name</th>
                                <th>Position Applied</th>
                                <th>Date Applied</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe</td>
                                <td>Inventory Clerk</td>
                                <td>2025-11-18</td>
                                <td><span class="status-badge warning">Pending</span></td>
                                <td>
                                    <button class="action-icon success-btn" title="Verify/Approve" onclick="openModal('modal-verify-applicant')"><i class="fas fa-check"></i></button>
                                    <button class="action-icon delete-btn" title="Reject/Delete" onclick="openModal('modal-delete-confirm')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </main>   
    <?php
      include APP_DIR.'views/modals/_account.php';
      include APP_DIR.'views/modals/_logout.php';
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
    ?>

    <script src="<?= base_url();?>public/js/script.js"></script>
</body>
</html>