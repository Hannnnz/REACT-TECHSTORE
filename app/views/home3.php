<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore Admin - POS System</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url();?>public/css/home.css">
</head>
<body class="light-mode">
    <aside id="sidebar">
    <div class="logo-section">
        <div class="logo-left">
        <i class="fas fa-microchip"></i>
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
                <h6 style="visibility: hidden;">Dashboard Overview</h6>
            </div>

            <div id="products" class="content-section">
                <div class="toolbar">
                <button class="action-btn primary-btn" id="add-product-btn">
                <i class="fas fa-plus-circle"></i> Add Product
                </button>
                    <button class="action-btn"><i class="fas fa-barcode"></i> Print Barcode</button>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search Products...">
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
                                        <a href="<?= site_url('soft-delete/'.$product['id']); ?>" class="action-icon delete-btn"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <h6 style="visibility: hidden;">Product Management</h6>
            </div>

            <div id="inventory" class="content-section">
                <div class="toolbar">
                    <button class="action-btn primary-btn"><i class="fas fa-truck-loading"></i> Record New Stock</button>
                    <button class="action-btn" id="import-inventory-btn"><i class="fas fa-upload"></i> Import (CSV)</button>
                    <button class="action-btn"><i class="fas fa-download"></i> Export Data</button>
                    <div class="search-box">
                        <i class="fas fa-filter search-icon"></i>
                        <input type="text" placeholder="Filter by Stock Status...">
                    </div>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Current Stock</th>
                                <th>Last Restock</th>
                                <th>Threshold</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Smartwatch X30</td>
                                <td>45</td>
                                <td>2025-10-15</td>
                                <td>20</td>
                                <td><span class="status-badge success">In Stock</span></td>
                                <td><button class="action-icon view-btn" title="Update Stock" id="update-inventory-btn"><i class="fas fa-history"></i></button></td>
                            </tr>
                            <tr>
                                <td>Gaming Mouse G9</td>
                                <td>15</td>
                                <td>2025-10-20</td>
                                <td>10</td>
                                <td><span class="status-badge warning">Low Stock</span></td>
                                <td><button class="action-icon view-btn" title="View History"><i class="fas fa-history"></i></button></td>
                            </tr>
                            <tr class="low-stock">
                                <td>Power Adapter 65W</td>
                                <td>7</td>
                                <td>2025-09-01</td>
                                <td>10</td>
                                <td><span class="status-badge critical">Critical!</span></td>
                                <td><button class="action-icon view-btn" title="View History"><i class="fas fa-history"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6 style="visibility: hidden;">Inventory Management</h6>
            </div>

            <div id="users" class="content-section">
                <div class="toolbar">
                    <button class="action-btn primary-btn" onclick="handleCrudAction('CREATE/Add New User')"><i class="fas fa-user-plus"></i> Add New User</button>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search Users...">
                    </div>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Last Login</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Lance Kianne Brito</td>
                                <td>lance.ad</td>
                                <td>Admin</td>
                                <td>2025-10-22 10:00 AM</td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="action-icon edit-btn" title="Edit Permissions"><i class="fas fa-user-cog"></i></button>
                                    <button class="action-icon delete-btn" title="Deactivate"><i class="fas fa-user-slash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Fyra Nika Dudas</td>
                                <td>fyra.cs</td>
                                <td>Cashier</td>
                                <td>2025-10-22 11:30 AM</td>
                                <td><span class="status-badge success">Active</span></td>
                                <td>
                                    <button class="action-icon edit-btn" title="Edit Permissions"><i class="fas fa-user-cog"></i></button>
                                    <button class="action-icon delete-btn" title="Deactivate"><i class="fas fa-user-slash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dennis Silleza</td>
                                <td>dennis.inv</td>
                                <td>Inventory Manager</td>
                                <td>2025-10-18 02:45 PM</td>
                                <td><span class="status-badge inactive">Inactive</span></td>
                                <td>
                                    <button class="action-icon edit-btn" title="Edit Permissions"><i class="fas fa-user-cog"></i></button>
                                    <button class="action-icon delete-btn" title="Deactivate"><i class="fas fa-user-slash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6 style="visibility: hidden;">Staff Management</h6>
            </div>

            <div id="transactions" class="content-section">
                <div class="toolbar">
                    <input type="date" value="2025-10-22">
                    <select><option>All Cashiers</option><option>Fyra Nika Dudas</option><option>Lance Kianne Brito</option></select>
                    <button class="action-btn"><i class="fas fa-filter"></i> Filter</button>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date/Time</th>
                                <th>Cashier</th>
                                <th>Total Amount (₱)</th>
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
                                    <button class="action-icon view-btn" title="View Details"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon refund-btn" title="Refund/Void"><i class="fas fa-undo-alt"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>TRX-00102</td>
                                <td>2025-10-22 10:15 AM</td>
                                <td>Lance Kianne Brito</td>
                                <td>500.00</td>
                                <td><span class="status-badge critical">Voided</span></td>
                                <td>
                                    <button class="action-icon view-btn" title="View Details"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon refund-btn" title="Refund/Void" disabled><i class="fas fa-undo-alt"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>TRX-00103</td>
                                <td>2025-10-21 04:30 PM</td>
                                <td>Dennis Silleza</td>
                                <td>15,200.00</td>
                                <td><span class="status-badge success">Completed</span></td>
                                <td>
                                    <button class="action-icon view-btn" title="View Details"><i class="fas fa-eye"></i></button>
                                    <button class="action-icon refund-btn" title="Refund/Void"><i class="fas fa-undo-alt"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6 style="visibility: hidden;">Transaction Log</h6>
            </div>

            <div id="reports" class="content-section">
                <h2>Reporting & Analytics</h2>
                <div class="toolbar">
                    <select><option>Sales Summary (Monthly)</option><option>Inventory Movement</option><option>Cashier Performance</option><option>Top Selling Products</option></select>
                    <input type="date" value="2025-10-01">
                    <input type="date" value="2025-10-31">
                    <button class="action-btn primary-btn"><i class="fas fa-chart-bar"></i> Generate Report</button>
                    <button class="action-btn"><i class="fas fa-download"></i> Export Data</button>
                </div>
        
                <div class="report-box-grid">
                    <div class="report-box">
                        <h3><i class="fas fa-coins"></i> Sales Summary: October 2025</h3>
                        <p>Total Revenue: ₱ 1,850,200.00</p>
                        <p>Net Profit: ₱ 750,200.00</p>
                        <p>Total Transactions: 1,250</p>
                        <p class="trend up"><i class="fas fa-arrow-up"></i> 15% increase vs Sept</p>
                    </div>
                    <div class="report-box">
                        <h3><i class="fas fa-truck-loading"></i> Inventory Value & Status</h3>
                        <p>Total Stock Value: ₱ 5,450,000.00</p>
                        <p>Items in Stock: 1,250 SKUs</p>
                        <p>Stock Turn: 3.5x/year</p>
                        <p class="trend alert"><i class="fas fa-exclamation-triangle"></i> 12 Low Stock Alerts</p>
                    </div>
                    <div class="report-box">
                        <h3><i class="fas fa-user-tie"></i> Top Cashier (Oct)</h3>
                        <p>Cashier: Fyra Nika Dudas</p>
                        <p>Total Transactions: 152</p>
                        <p>Total Sales Handled: ₱ 450,000.00</p>
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
                                    <th>Revenue (₱)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>1</td><td>Smartwatch X30</td><td>150</td><td>1,349,850.00</td></tr>
                                <tr><td>2</td><td>Fast-Charging Cable (USB-C)</td><td>450</td><td>135,000.00</td></tr>
                                <tr><td>3</td><td>Gaming Mouse G9</td><td>80</td><td>120,000.00</td></tr>
                                <tr><td>4</td><td>Power Adapter 65W</td><td>65</td><td>97,175.00</td></tr>
                                <tr><td>5</td><td>Bluetooth Earbuds</td><td>120</td><td>47,880.00</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <h6 style="visibility: hidden;">Reports</h6>
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