(function () {
    function joinUrl(base, path) {
        if (!base) return path || '#';
        var normalizedBase = base.endsWith('/') ? base : base + '/';
        if (!path) return normalizedBase;
        var normalizedPath = path.startsWith('/') ? path.substring(1) : path;
        return normalizedBase + normalizedPath;
    }

    function setupModalUtilities() {
        var notifSound = document.getElementById('notifSound');

        function playSound() {
            if (!notifSound) return;
            try {
                notifSound.currentTime = 0;
                notifSound.play().catch(function () {});
            } catch (err) {
                /* noop */
            }
        }

        window.showToast = function (message, type) {
            if (type === void 0) type = 'success';
            playSound();
            var toastContainer = document.getElementById('toast-container');
            if (!toastContainer) return;

            var icon = type === 'success'
                ? 'fa-check-circle'
                : type === 'error'
                    ? 'fa-times-circle'
                    : 'fa-info-circle';

            var bgClass = type === 'success'
                ? 'bg-green-600'
                : type === 'error'
                    ? 'bg-red-600'
                    : 'bg-blue-600';

            var toast = document.createElement('div');
            toast.className = 'toast ' + bgClass;
            toast.innerHTML = '<div style="display:flex; align-items:center; gap:10px">' +
                '<i class="fas ' + icon + '"></i>' +
                '<span>' + message + '</span>' +
                '</div>' +
                '<button class="close-toast">&times;</button>';

            toastContainer.appendChild(toast);

            toast.querySelector('.close-toast').addEventListener('click', function () {
                toast.style.animation = 'fadeOut 0.4s forwards';
                setTimeout(function () { toast.remove(); }, 400);
            });

            setTimeout(function () {
                toast.style.animation = 'fadeOut 0.4s forwards';
                setTimeout(function () { toast.remove(); }, 400);
            }, 4000);
        };

        window.openModal = function (modalId) {
            var modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        };

        window.closeModal = function (modalId) {
            var modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        };

        document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) {
                    window.closeModal(overlay.id);
                }
            });
        });

        window.handleFormSubmit = function (modalId) {
            var btn = document.querySelector('#' + modalId + ' .primary-btn') ||
                      document.querySelector('#' + modalId + ' .delete-btn');
            var form = document.querySelector('#' + modalId + ' form');
            if (!btn || !form) return;

            var originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;

            setTimeout(function () {
                btn.innerHTML = originalText;
                btn.disabled = false;
                form.submit();
                window.closeModal(modalId);
                if (form.reset) form.reset();
            }, 1000);
        };

        var dropZone = document.getElementById('drop-zone');
        var fileInput = document.getElementById('csv-file-input');
        var fileNameDisplay = document.getElementById('file-name-display');
        var uploadBtn = document.getElementById('btn-upload-csv');

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        if (dropZone) {
            dropZone.addEventListener('click', function () {
                if (fileInput) fileInput.click();
            });

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function (eventName) {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(function (eventName) {
                dropZone.addEventListener(eventName, function () {
                    dropZone.style.borderColor = 'var(--clr-primary)';
                    dropZone.style.backgroundColor = 'var(--clr-hover-bg)';
                }, false);
            });

            ['dragleave', 'drop'].forEach(function (eventName) {
                dropZone.addEventListener(eventName, function () {
                    dropZone.style.borderColor = 'var(--clr-border)';
                    dropZone.style.backgroundColor = 'transparent';
                }, false);
            });

            dropZone.addEventListener('drop', function (e) {
                var dt = e.dataTransfer;
                if (!dt || !dt.files || !dt.files.length) return;

                var file = dt.files[0];
                if (file.type === 'text/csv') {
                    if (fileInput) fileInput.files = dt.files;
                    if (fileNameDisplay) fileNameDisplay.textContent = 'Selected: ' + file.name;
                    dropZone.style.borderColor = 'var(--clr-success)';
                    dropZone.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
                } else {
                    window.showToast('Please upload a valid CSV file.', 'error');
                }
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function (e) {
                if (!e.target.files || !e.target.files.length) return;
                var file = e.target.files[0];
                if (fileNameDisplay) fileNameDisplay.textContent = 'Selected: ' + file.name;
                if (dropZone) {
                    dropZone.style.borderColor = 'var(--clr-success)';
                    dropZone.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
                }
            });
        }

        if (uploadBtn) {
            uploadBtn.addEventListener('click', function () {
                if (!fileInput || !fileInput.files || !fileInput.files.length) {
                    window.showToast('Please select a CSV file first.', 'error');
                    return;
                }
                window.handleFormSubmit('modal-import-csv');
                setTimeout(function () {
                    if (fileNameDisplay) fileNameDisplay.textContent = '';
                    if (dropZone) {
                        dropZone.style.borderColor = 'var(--clr-border)';
                        dropZone.style.backgroundColor = 'transparent';
                    }
                    fileInput.value = '';
                }, 1500);
            });
        }
    }

    function DashboardApp(props) {
        var data = props.data || {};
        var products = Array.isArray(data.products) ? data.products : [];
        var users = Array.isArray(data.users) ? data.users : [];
        var baseUrl = typeof data.baseUrl === 'string' ? data.baseUrl : '';
        var siteUrl = typeof data.siteUrl === 'string' ? data.siteUrl : '';
        var summary = data.summary || {};
        var e = React.createElement;

        var useState = React.useState;
        var useEffect = React.useEffect;
        var useRef = React.useRef;
        var useMemo = React.useMemo;

        var getStoredTheme = function () {
            var stored = '';
            try {
                stored = localStorage.getItem('theme') || 'light-mode';
            } catch (err) {
                stored = 'light-mode';
            }
            return stored;
        };

        var getStoredSection = function () {
            var stored = '';
            try {
                stored = localStorage.getItem('activeSection') || 'dashboard';
            } catch (err) {
                stored = 'dashboard';
            }
            return stored;
        };

        var _a = useState(getStoredTheme()), theme = _a[0], setTheme = _a[1];
        var _b = useState(getStoredSection()), activeSection = _b[0], setActiveSection = _b[1];
        var _c = useState(false), sidebarCollapsed = _c[0], setSidebarCollapsed = _c[1];
        var _d = useState(false), sidebarVisible = _d[0], setSidebarVisible = _d[1];
        var _e = useState(false), profileOpen = _e[0], setProfileOpen = _e[1];
        var _f = useState(''), productSearch = _f[0], setProductSearch = _f[1];
        var _g = useState('all'), inventoryFilter = _g[0], setInventoryFilter = _g[1];
        var profileRef = useRef(null);

        useEffect(function () {
            document.body.classList.remove('light-mode', 'dark-mode');
            document.body.classList.add(theme);
            try {
                localStorage.setItem('theme', theme);
            } catch (err) {}
        }, [theme]);

        useEffect(function () {
            try {
                localStorage.setItem('activeSection', activeSection);
            } catch (err) {}
        }, [activeSection]);

        useEffect(function () {
            function handleClick(e) {
                if (profileRef.current && !profileRef.current.contains(e.target)) {
                    setProfileOpen(false);
                }
            }
            document.addEventListener('click', handleClick);
            return function () {
                document.removeEventListener('click', handleClick);
            };
        }, []);

        useEffect(function () {
            function handleResize() {
                if (window.innerWidth > 768) {
                    setSidebarVisible(false);
                }
            }
            window.addEventListener('resize', handleResize);
            return function () {
                window.removeEventListener('resize', handleResize);
            };
        }, []);

        var lowStockCount = products.reduce(function (count, item) {
            var stock = Number(item.stock);
            if (!isNaN(stock) && stock >= 0 && stock <= 4) {
                return count + 1;
            }
            return count;
        }, 0);

        var verifiedUsers = users.reduce(function (count, user) {
            return user.updated_at ? count + 1 : count;
        }, 0);

        var outOfStockCount = products.reduce(function (count, item) {
            var stock = Number(item.stock);
            return (!isNaN(stock) && stock === 0) ? count + 1 : count;
        }, 0);

        var filteredProducts = useMemo(function () {
            if (!productSearch.trim()) return products;
            var term = productSearch.trim().toLowerCase();
            return products.filter(function (product) {
                return String(product.name).toLowerCase().includes(term) ||
                       String(product.category).toLowerCase().includes(term) ||
                       String(product.id || product.product_id || '').toLowerCase().includes(term);
            });
        }, [products, productSearch]);

        var filteredInventory = useMemo(function () {
            if (inventoryFilter === 'all') return products;
            return products.filter(function (product) {
                var stock = Number(product.stock);
                if (inventoryFilter === 'low') {
                    return !isNaN(stock) && stock >= 1 && stock <= 4;
                }
                if (inventoryFilter === 'out') {
                    return !isNaN(stock) && stock === 0;
                }
                if (inventoryFilter === 'healthy') {
                    return !isNaN(stock) && stock > 4;
                }
                return true;
            });
        }, [products, inventoryFilter]);

        var productCategories = useMemo(function () {
            var categoryMap = {};
            products.forEach(function (product) {
                var cat = product.category || 'Uncategorized';
                categoryMap[cat] = (categoryMap[cat] || 0) + 1;
            });
            return Object.keys(categoryMap).map(function (key) {
                return { name: key, count: categoryMap[key] };
            });
        }, [products]);

        var navItems = [
            { id: 'dashboard', label: 'Dashboard', icon: 'fas fa-chart-line' },
            { id: 'products', label: 'Products', icon: 'fas fa-box-open' },
            { id: 'inventory', label: 'Inventory', icon: 'fas fa-boxes' },
            { id: 'users', label: 'Users', icon: 'fas fa-users' },
            { id: 'transactions', label: 'Transactions', icon: 'fas fa-receipt' },
            { id: 'applicants', label: 'Applicants', icon: 'fas fa-id-card' }
        ];

        var sectionTitles = {
            dashboard: 'Dashboard Overview',
            products: 'Product Management',
            inventory: 'Inventory Management',
            users: 'User Management',
            transactions: 'Transactions',
            applicants: 'Applicant Verification'
        };

        var stats = [
            {
                title: 'Total Sales (Today)',
                value: summary.sales || '₱ 0.00',
                trendClass: 'up',
                trendIcon: 'fas fa-arrow-up',
                trendText: 'Live Snapshot',
                icon: 'fas fa-chart-line'
            },
            {
                title: 'Net Profit',
                value: summary.profit || '₱ 0.00',
                trendClass: 'up',
                trendIcon: 'fas fa-arrow-up',
                trendText: 'Daily Net',
                icon: 'fas fa-coins'
            },
            {
                title: 'Products Sold',
                value: summary.sold || '0',
                trendClass: 'down',
                trendIcon: 'fas fa-arrow-down',
                trendText: 'vs Yesterday',
                icon: 'fas fa-shopping-bag'
            },
            {
                title: 'Low Stock Items',
                value: String(lowStockCount),
                trendClass: 'alert',
                trendIcon: 'fas fa-exclamation-circle',
                trendText: 'Action Needed',
                icon: 'fas fa-exclamation-circle',
                extraClass: 'inventory-alert'
            },
            {
                title: 'Out of Stock',
                value: String(outOfStockCount),
                trendClass: 'down',
                trendIcon: 'fas fa-box',
                trendText: 'Restock immediately',
                icon: 'fas fa-box-open'
            }
        ];

        var barChartData = [
            { label: 'Mon', value: "₱35k", height: '50%' },
            { label: 'Tue', value: "₱56k", height: '80%' },
            { label: 'Wed', value: "₱21k", height: '30%' },
            { label: 'Thu', value: "₱66k", height: '95%' },
            { label: 'Fri', value: "₱49k", height: '70%' },
            { label: 'Sat', value: "₱42k", height: '60%' },
            { label: 'Sun', value: "₱31k", height: '45%' }
        ];

        var transactions = Array.isArray(data.transactions) ? data.transactions : [];
        var applicants = Array.isArray(data.applicants) ? data.applicants : [];

        var pageTitle = sectionTitles[activeSection] || 'Dashboard Overview';
        var sidebarClassName = [''
            , sidebarCollapsed ? 'collapsed' : ''
            , sidebarVisible ? 'visible' : ''
        ].filter(Boolean).join(' ').trim();

        var themeIcon = theme === 'dark-mode' ? 'fas fa-sun' : 'fas fa-moon';
        var logoLight = joinUrl(baseUrl, 'public/resources/logolight.jpg');
        var logoDark = joinUrl(baseUrl, 'public/resources/logodark.jpg');

        function handleNavClick(id) {
            setActiveSection(id);
            setSidebarVisible(false);
        }

        function handleSidebarToggle() {
            if (window.innerWidth > 768) {
                setSidebarCollapsed(function (prev) { return !prev; });
            } else {
                setSidebarVisible(function (prev) { return !prev; });
            }
        }

        function handleModalOpen(modalId) {
            if (window.openModal) {
                window.openModal(modalId);
            }
        }

        function productLink(path) {
            return joinUrl(siteUrl, path);
        }

        function stockStatusBadge(product) {
            var stock = Number(product.stock);
            var badgeClass = 'status-badge success';
            var label = 'In Stock';

            if (isNaN(stock) || stock === 0) {
                badgeClass = 'status-badge critical';
                label = 'Out of Stock';
            } else if (stock >= 1 && stock <= 4) {
                badgeClass = 'status-badge warning';
                label = 'Low Stock';
            }

            return e('span', { className: badgeClass }, label);
        }

        function renderStats() {
            return stats.map(function (stat) {
                return e('div', {
                    key: stat.title,
                    className: ('stat-card ' + (stat.extraClass || '')).trim()
                }, [
                    e('div', { className: 'stat-header', key: 'header' }, [
                        e('div', { key: 'text' }, [
                            e('h3', { key: 'title' }, stat.title),
                            e('p', { className: 'stat-value', key: 'value' }, stat.value)
                        ]),
                        e('i', { className: stat.icon + ' stat-icon', key: 'icon' })
                    ]),
                    e('span', { className: 'trend ' + stat.trendClass, key: 'trend' }, [
                        e('i', { className: stat.trendIcon, key: 'trendIcon' }),
                        ' ' + stat.trendText
                    ])
                ]);
            });
        }

        function renderBarChart() {
            return e('div', { className: 'chart-container' }, [
                e('div', { className: 'placeholder-chart', key: 'chart' }, [
                    e('h3', { key: 'title' }, "Weekly Sales Chart (₱'000)"),
                    e('div', { className: 'bar-chart-visual', key: 'bars' }, barChartData.map(function (item) {
                        return e('div', {
                            key: item.label,
                            style: { height: item.height },
                            'data-label': item.value
                        }, item.label);
                    }))
                ])
            ]);
        }

        function renderDashboardSection(isActive) {
            return e('div', {
                id: 'dashboard',
                className: 'content-section' + (isActive ? ' active' : '')
            }, [
                e('div', { className: 'stats-grid', key: 'stats-grid' }, renderStats()),
                renderBarChart()
            ]);
        }

        function renderProductSummary() {
            return e('div', { className: 'react-widget-shell product-summary' }, [
                e('div', { className: 'stat-card react-card', key: 'counts' }, [
                    e('div', { className: 'stat-header' }, [
                        e('div', null, [
                            e('h3', null, 'Catalog Snapshot'),
                            e('p', { className: 'stat-value' }, products.length || 0)
                        ]),
                        e('i', { className: 'fas fa-layer-group stat-icon' })
                    ]),
                    e('div', { className: 'react-chip-group' }, productCategories.slice(0, 4).map(function (cat) {
                        return e('span', { className: 'react-chip', key: cat.name }, [
                            e('i', { className: 'fas fa-tag', style: { marginRight: '6px' } }),
                            cat.name + ' · ' + cat.count
                        ]);
                    }))
                ])
            ]);
        }

        function renderProductsSection(isActive) {
            return e('div', {
                id: 'products',
                className: 'content-section' + (isActive ? ' active' : '')
            }, [
                renderProductSummary(),
                e('div', { className: 'toolbar', key: 'toolbar' }, [
                    e('button', {
                        className: 'action-btn primary-btn',
                        type: 'button',
                        onClick: function () { return handleModalOpen('modal-add-product'); }
                    }, [
                        e('i', { className: 'fas fa-plus-circle', key: 'icon' }),
                        ' Add Product'
                    ]),
                    e('div', { className: 'search-box', key: 'search' }, [
                        e('i', { className: 'fas fa-search search-icon', key: 'icon' }),
                        e('input', {
                            type: 'text',
                            value: productSearch,
                            onChange: function (event) { return setProductSearch(event.target.value); },
                            placeholder: 'Search by name, category, or ID...'
                        }),
                        productSearch ? e('button', {
                            className: 'action-btn',
                            type: 'button',
                            onClick: function () { return setProductSearch(''); }
                        }, 'Clear') : null
                    ])
                ]),
                e('div', { className: 'table-container', key: 'table' }, [
                    e('table', { className: 'data-table' }, [
                        e('thead', { key: 'thead' }, [
                            e('tr', null, [
                                e('th', null, 'ID'),
                                e('th', null, 'Name'),
                                e('th', null, 'Category'),
                                e('th', null, 'Unit Price (₱)'),
                                e('th', null, 'Actions')
                            ])
                        ]),
                        e('tbody', { key: 'tbody' }, filteredProducts.length ? filteredProducts.map(function (product) {
                            var productId = product.id || product.product_id || '';
                            var editHref = productLink('update/' + productId);
                            var deleteHref = productLink('soft-delete/' + productId);
                            return e('tr', { key: productId || product.name }, [
                                e('td', null, productId),
                                e('td', null, product.name),
                                e('td', null, product.category),
                                e('td', null, '₱' + (product.price || '0')),
                                e('td', null, [
                                    e('a', {
                                        href: editHref,
                                        className: 'action-icon edit-btn',
                                        title: 'Update Stock'
                                    }, e('i', { className: 'fas fa-pen' })),
                                    e('a', {
                                        href: deleteHref,
                                        className: 'action-icon delete-btn',
                                        title: 'Delete Product'
                                    }, e('i', { className: 'fas fa-trash' }))
                                ])
                            ]);
                        }) : e('tr', { key: 'empty-products' }, [
                            e('td', { colSpan: 5, style: { textAlign: 'center', padding: '40px 0' } }, 'No products match your search.')
                        ]))
                    ])
                ])
            ]);
        }

        function renderInventorySection(isActive) {
            return e('div', {
                id: 'inventory',
                className: 'content-section' + (isActive ? ' active' : '')
            }, [
                e('div', { className: 'toolbar inventory-toolbar', key: 'toolbar' }, [
                    e('button', {
                        className: 'action-btn primary-btn',
                        type: 'button',
                        onClick: function () { return handleModalOpen('modal-record-stock'); }
                    }, [
                        e('i', { className: 'fas fa-truck-loading', key: 'icon' }),
                        ' Record New Stock'
                    ]),
                    e('button', {
                        className: 'action-btn',
                        type: 'button',
                        onClick: function () { return handleModalOpen('modal-import-csv'); }
                    }, [
                        e('i', { className: 'fas fa-upload', key: 'icon' }),
                        ' Import (CSV)'
                    ]),
                    e('button', {
                        className: 'action-btn',
                        type: 'button',
                        onClick: function () { return handleModalOpen('modal-export-confirm'); }
                    }, [
                        e('i', { className: 'fas fa-download', key: 'icon' }),
                        ' Export Data'
                    ]),
                    e('div', { className: 'search-box', key: 'search' }, [
                        e('i', { className: 'fas fa-filter search-icon', key: 'icon' }),
                        e('div', { className: 'react-chip-group inventory-filters' }, [
                            ['all', 'All'],
                            ['healthy', 'Healthy'],
                            ['low', 'Low Stock'],
                            ['out', 'Out of Stock']
                        ].map(function (filter) {
                            var id = filter[0];
                            var label = filter[1];
                            var active = inventoryFilter === id;
                            return e('button', {
                                key: id,
                                type: 'button',
                                className: 'react-chip inventory-chip' + (active ? ' active' : ''),
                                onClick: function () { return setInventoryFilter(id); }
                            }, label);
                        }))
                    ])
                ]),
                e('div', { className: 'table-container', key: 'table' }, [
                    e('table', { className: 'data-table' }, [
                        e('thead', { key: 'thead' }, [
                            e('tr', null, [
                                e('th', null, 'ID'),
                                e('th', null, 'Product Name'),
                                e('th', null, 'Current Stock'),
                                e('th', null, 'Last Restock'),
                                e('th', null, 'Status'),
                                e('th', null, 'History')
                            ])
                        ]),
                        e('tbody', { key: 'tbody' }, filteredInventory.length ? filteredInventory.map(function (product) {
                            var productId = product.id || product.product_id || '';
                            return e('tr', { key: 'inventory-' + productId }, [
                                e('td', null, productId),
                                e('td', null, product.name),
                                e('td', null, product.stock),
                                e('td', null, product.last_restock),
                                e('td', null, stockStatusBadge(product)),
                                e('td', null, e('button', {
                                    className: 'action-icon view-btn',
                                    type: 'button',
                                    title: 'View History'
                                }, e('i', { className: 'fas fa-history' })))
                            ]);
                        }) : e('tr', { key: 'empty-inventory' }, [
                            e('td', { colSpan: 6, style: { textAlign: 'center', padding: '40px 0' } }, 'No inventory records for this filter.')
                        ]))
                    ])
                ])
            ]);
        }

        function renderUsersSection(isActive) {
            return e('div', {
                id: 'users',
                className: 'content-section' + (isActive ? ' active' : '')
            }, [
                e('div', { className: 'toolbar', key: 'toolbar' }, [
                    e('div', { className: 'search-box', key: 'search' }, [
                        e('i', { className: 'fas fa-search search-icon', key: 'icon' }),
                        e('input', {
                            type: 'text',
                            placeholder: 'Search Users...'
                        }),
                        e('button', { className: 'action-btn search-btn', type: 'button' }, 'Search')
                    ])
                ]),
                e('div', { className: 'table-container', key: 'table' }, [
                    e('table', { className: 'data-table' }, [
                        e('thead', { key: 'thead' }, [
                            e('tr', null, [
                                e('th', null, 'ID'),
                                e('th', null, 'Name'),
                                e('th', null, 'Email'),
                                e('th', null, 'Verified At'),
                                e('th', null, 'Actions')
                            ])
                        ]),
                        e('tbody', { key: 'tbody' }, users.map(function (user) {
                            var userId = user.id || user.user_id || '';
                            var deleteHref = productLink('user-delete/' + userId);
                            return e('tr', { key: userId || user.username }, [
                                e('td', null, userId),
                                e('td', null, user.username),
                                e('td', null, user.email),
                                e('td', null, user.updated_at),
                                e('td', null, [
                                    e('button', {
                                        className: 'action-icon view-btn',
                                        type: 'button',
                                        title: 'Print User ID',
                                        onClick: function () { return handleModalOpen('modal-user-barcode'); }
                                    }, e('i', { className: 'fas fa-id-card' })),
                                    e('a', {
                                        href: deleteHref,
                                        className: 'action-icon delete-btn',
                                        title: 'Delete User'
                                    }, e('i', { className: 'fas fa-trash' }))
                                ])
                            ]);
                        }))
                    ])
                ])
            ]);
        }

        function renderTransactionsSection(isActive) {
            return e('div', {
                id: 'transactions',
                className: 'content-section' + (isActive ? ' active' : '')
            }, [
                e('div', { className: 'toolbar', key: 'toolbar' }, [
                    e('input', { type: 'date', defaultValue: '2025-10-22' }),
                    e('select', null, [
                        e('option', { key: 'all' }, 'All Cashiers'),
                        e('option', { key: 'fyra' }, 'Fyra Nika Dudas')
                    ]),
                    e('button', { className: 'action-btn', type: 'button' }, [
                        e('i', { className: 'fas fa-filter', key: 'icon' }),
                        ' Filter'
                    ])
                ]),
                e('div', { className: 'table-container', key: 'table' }, [
                    e('table', { className: 'data-table' }, [
                        e('thead', { key: 'thead' }, [
                            e('tr', null, [
                                e('th', null, 'ID'),
                                e('th', null, 'Date/Time'),
                                e('th', null, 'Cashier'),
                                e('th', null, 'Total (₱)'),
                                e('th', null, 'Status'),
                                e('th', null, 'Actions')
                            ])
                        ]),
                        e('tbody', { key: 'tbody' }, transactions.map(function (transaction) {
                            return e('tr', { key: transaction.id }, [
                                e('td', null, transaction.id),
                                e('td', null, transaction.datetime || transaction.date),
                                e('td', null, transaction.cashier),
                                e('td', null, transaction.total),
                                e('td', null, e('span', { className: 'status-badge success' }, transaction.status || 'Completed')),
                                e('td', null, [
                                    e('button', {
                                        className: 'action-icon view-btn',
                                        type: 'button',
                                        title: 'Print Receipt',
                                        onClick: function () { return handleModalOpen('modal-print-receipt'); }
                                    }, e('i', { className: 'fas fa-eye' })),
                                    e('button', {
                                        className: 'action-icon refund-btn',
                                        type: 'button',
                                        title: 'Revert/Void',
                                        onClick: function () { return handleModalOpen('modal-revert-confirm'); }
                                    }, e('i', { className: 'fas fa-undo-alt' }))
                                ])
                            ]);
                        }))
                    ])
                ])
            ]);
        }

        function renderApplicantsSection(isActive) {
            return e('div', {
                id: 'applicants',
                className: 'content-section' + (isActive ? ' active' : '')
            }, [
                e('div', { className: 'toolbar', key: 'toolbar' }, [
                    e('div', { className: 'search-box', key: 'search' }, [
                        e('i', { className: 'fas fa-search search-icon', key: 'icon' }),
                        e('input', {
                            type: 'text',
                            placeholder: 'Search Applicants...'
                        }),
                        e('button', { className: 'action-btn search-btn', type: 'button' }, 'Search')
                    ])
                ]),
                e('div', { className: 'table-container', key: 'table' }, [
                    e('table', { className: 'data-table' }, [
                        e('thead', { key: 'thead' }, [
                            e('tr', null, [
                                e('th', null, 'Applicant Name'),
                                e('th', null, 'Position Applied'),
                                e('th', null, 'Date Applied'),
                                e('th', null, 'Status'),
                                e('th', null, 'Actions')
                            ])
                        ]),
                        e('tbody', { key: 'tbody' }, applicants.map(function (applicant, idx) {
                            return e('tr', { key: idx }, [
                                e('td', null, applicant.name || applicant.username),
                                e('td', null, applicant.position || 'Pending Role'),
                                e('td', null, applicant.date || applicant.updated_at),
                                e('td', null, e('span', { className: 'status-badge warning' }, applicant.status || 'Pending')),
                                e('td', null, [
                                    e('button', {
                                        className: 'action-icon success-btn',
                                        type: 'button',
                                        title: 'Verify/Approve',
                                        onClick: function () { return handleModalOpen('modal-verify-applicant'); }
                                    }, e('i', { className: 'fas fa-check' })),
                                    e('button', {
                                        className: 'action-icon delete-btn',
                                        type: 'button',
                                        title: 'Reject/Delete',
                                        onClick: function () { return handleModalOpen('modal-delete-confirm'); }
                                    }, e('i', { className: 'fas fa-trash' }))
                                ])
                            ]);
                        }))
                    ])
                ])
            ]);
        }

        var sections = [
            renderDashboardSection(activeSection === 'dashboard'),
            renderProductsSection(activeSection === 'products'),
            renderInventorySection(activeSection === 'inventory'),
            renderUsersSection(activeSection === 'users'),
            renderTransactionsSection(activeSection === 'transactions'),
            renderApplicantsSection(activeSection === 'applicants')
        ];

        return e('div', { className: 'react-dashboard-app' }, [
            e('aside', { id: 'sidebar', className: sidebarClassName }, [
                e('div', { className: 'logo-section' }, [
                    e('div', { className: 'logo-left' }, [
                        e('img', {
                            src: logoLight,
                            alt: 'TechStore Logo',
                            className: 'logo-img light-logo'
                        }),
                        e('img', {
                            src: logoDark,
                            alt: 'TechStore Logo',
                            className: 'logo-img dark-logo'
                        }),
                        e('span', null, 'TechStore')
                    ])
                ]),
                e('nav', { className: 'main-menu' }, [
                    e('ul', null, navItems.map(function (item) {
                        return e('li', {
                            key: item.id,
                            'data-section': item.id,
                            className: activeSection === item.id ? 'active' : '',
                            onClick: function () { return handleNavClick(item.id); }
                        }, [
                            e('i', { className: item.icon }),
                            e('span', null, item.label)
                        ]);
                    }))
                ])
            ]),
            e('main', { id: 'main-content' }, [
                e('header', { id: 'top-navbar' }, [
                    e('div', { className: 'nav-left' }, [
                        e('button', {
                            id: 'sidebar-toggle',
                            type: 'button',
                            title: 'Toggle Menu',
                            onClick: handleSidebarToggle
                        }, e('i', { className: 'fas fa-bars' })),
                        e('span', { className: 'page-title' }, pageTitle)
                    ]),
                    e('div', { className: 'nav-right nav-icons' }, [
                        e('button', {
                            id: 'theme-toggle',
                            type: 'button',
                            title: 'Toggle Theme',
                            onClick: function () {
                                setTheme(theme === 'dark-mode' ? 'light-mode' : 'dark-mode');
                            }
                        }, e('i', { className: themeIcon })),
                        e('div', {
                            className: 'profile-menu' + (profileOpen ? ' active' : ''),
                            id: 'profile-menu',
                            ref: profileRef
                        }, [
                            e('div', {
                                className: 'user-profile',
                                id: 'profile-toggle',
                                onClick: function (event) {
                                    event.stopPropagation();
                                    setProfileOpen(function (prev) { return !prev; });
                                }
                            }, [
                                e('span', { className: 'user-name' }, 'Admin'),
                                e('i', { className: 'fas fa-user-circle' }),
                                e('i', { className: 'fas fa-chevron-down profile-chevron' })
                            ]),
                            e('div', { className: 'settings-menu profile-dropdown' }, [
                                e('div', { className: 'profile-dropdown-header' }, [
                                    e('h4', null, 'Admin'),
                                    e('small', null, 'admin@techstore.com')
                                ]),
                                e('ul', null, [
                                    e('li', {
                                        onClick: function () { return handleModalOpen('settings-modal'); },
                                        id: 'account-settings-btn'
                                    }, [
                                        e('i', { className: 'fas fa-cog' }),
                                        e('span', null, 'Account Settings')
                                    ]),
                                    e('li', {
                                        onClick: function () { return handleModalOpen('logout-modal'); },
                                        id: 'logout-btn-trigger'
                                    }, [
                                        e('i', { className: 'fas fa-sign-out-alt' }),
                                        e('span', null, 'Logout')
                                    ])
                                ])
                            ])
                        ])
                    ])
                ]),
                e('section', { id: 'content-area' }, sections)
            ])
        ]);
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupModalUtilities();
        var mountNode = document.getElementById('react-dashboard-root');
        if (!mountNode || !window.React || !window.ReactDOM || !ReactDOM.createRoot) {
            return;
        }

        var dashboardData = {};
        try {
            var payload = mountNode.getAttribute('data-dashboard') || '{}';
            dashboardData = JSON.parse(payload);
        } catch (err) {
            dashboardData = {};
        }

        var root = ReactDOM.createRoot(mountNode);
        root.render(React.createElement(DashboardApp, { data: dashboardData }));
    });
})();
