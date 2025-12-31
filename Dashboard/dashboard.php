<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police CMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="content/addCase/css/add_case.css">
    <link rel="stylesheet" href="content/allCases/css/all_cases.css">
    <script src="js/notifications.js"></script>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-chevron-left" id="toggleIcon"></i>
            </button>

            <div class="sidebar-header">
                <div class="logo-icon">
                    <i class="fa fa-legal"></i>

                </div>
                <div class="logo-text">
                    <h2>Police CMS</h2>
                    <p>Case Management</p>
                </div>
            </div>

            <nav class="nav-section">
                <div class="nav-section-title">Navigation</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a class="nav-link active" onclick="switchTab('dashboard')">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="switchTab('cases')">
                            <i class="fas fa-folder-open"></i>
                            <span>All Cases</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="switchTab('add-case')">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add New Case</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="switchTab('search')">
                            <i class="fas fa-search"></i>
                            <span>Search / Filter</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="switchTab('reports')">
                            <i class="fas fa-file-alt"></i>
                            <span>Reports & Print</span>
                        </a>
                    </li>
                </ul>

                <div class="nav-section-title" style="margin-top: 20px;">Admin</div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a class="nav-link" onclick="switchTab('users')">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div class="footer-text">© 2025 Police CMS</div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION["full_name"], 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <h3><?php echo $_SESSION["full_name"]; ?></h3>
                        <p><?php echo $_SESSION["position"]; ?> • <?php echo $_SESSION["rank_title"]; ?></p>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-content active">
                <h2><i class="fas fa-th-large"></i> Dashboard</h2>

                <!-- Statistics Chart Section -->
                <div class="stats-chart-section">

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value" id="total-cases">0</div>
                                <div class="stat-label">Total Cases</div>
                            </div>
                            <div class="stat-chart">
                                <div class="mini-bar" style="height: 80%;"></div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value" id="pending-cases">0</div>
                                <div class="stat-label">Pending</div>
                            </div>
                            <div class="stat-chart">
                                <div class="mini-bar" style="height: 60%;"></div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon ongoing">
                                <i class="fas fa-spinner"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value" id="ongoing-cases">0</div>
                                <div class="stat-label">Ongoing</div>
                            </div>
                            <div class="stat-chart">
                                <div class="mini-bar" style="height: 70%;"></div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon closed">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value" id="closed-cases">0</div>
                                <div class="stat-label">Closed</div>
                            </div>
                            <div class="stat-chart">
                                <div class="mini-bar" style="height: 50%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Chart -->
                    <div class="progress-chart">
                        <h4>Case Status Distribution</h4>
                        <div class="progress-bar-container">
                            <div class="progress-segment pending" id="pending-bar" style="width: 0%;" data-tooltip="Pending: 0%"></div>
                            <div class="progress-segment ongoing" id="ongoing-bar" style="width: 0%;" data-tooltip="Ongoing: 0%"></div>
                            <div class="progress-segment closed" id="closed-bar" style="width: 0%;" data-tooltip="Closed: 0%"></div>
                        </div>
                        <div class="progress-legend">
                            <div class="legend-item">
                                <span class="legend-color pending"></span>
                                <span class="legend-text">Pending (<span id="pending-percent">0</span>%)</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color ongoing"></span>
                                <span class="legend-text">Ongoing (<span id="ongoing-percent">0</span>%)</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color closed"></span>
                                <span class="legend-text">Closed (<span id="closed-percent">0</span>%)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="summary-section">
                    <!-- Today's Cases Table -->
                    <div class="summary-card">
                        <div class="summary-header">
                            <h3><i class="fas fa-calendar-day"></i> Today's Cases</h3>
                            <span class="badge-count">0</span>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Case Number</th>
                                        <th>Information Book</th>
                                        <th>Register</th>
                                        <th>Opens</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody id="today-cases-tbody">
                                    <!-- Data will be populated from database -->
                                </tbody>
                            </table>
                        </div>
                        <div class="summary-footer">
                            <a href="#" onclick="switchTab('cases'); return false;" class="view-all-link">
                                View All Cases <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Updated Cases Table -->
                    <div class="summary-card">
                        <div class="summary-header">
                            <h3><i class="fas fa-sync-alt"></i> Recent Updates</h3>
                            <span class="badge-count">0</span>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Case Number</th>
                                        <th>Information Book</th>
                                        <th>Register</th>
                                        <th>Opens</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-updates-tbody">
                                    <!-- Data will be populated from database -->
                                </tbody>
                            </table>
                        </div>
                        <div class="summary-footer">
                            <a href="#" onclick="switchTab('reports'); return false;" class="view-all-link">
                                View All Updates <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Cases Tab -->
            <div id="cases" class="tab-content">
                <div id="cases-content">
                    <h2><i class="fas fa-folder-open"></i> All Cases</h2>
                    <p>Loading cases...</p>
                </div>
            </div>

            <!-- Add New Case Tab -->
            <div id="add-case" class="tab-content">
                <!-- Content will be loaded dynamically -->
            </div>

            <!-- Search Tab -->
            <div id="search" class="tab-content">
                <h2><i class="fas fa-search"></i> Search / Filter</h2>
                <p>Search and filter cases by various criteria.</p>
            </div>

            <!-- Reports Tab -->
            <div id="reports" class="tab-content">
                <h2><i class="fas fa-file-alt"></i> Reports & Print</h2>
                <p>Generate and print reports for cases.</p>
            </div>

            <!-- Users Tab -->
            <div id="users" class="tab-content">
                <h2><i class="fas fa-users"></i> Users</h2>
                <p>Manage system users and their permissions.</p>
            </div>
        </main>
    </div>

    <!-- Fixed Footer -->
    <footer class="fixed-footer">
        <a href="https://github.com/ukihunter" target="_blank" rel="noopener noreferrer">Developed by uki</a>
    </footer>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.getElementById('toggleIcon');

            sidebar.classList.toggle('collapsed');

            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-chevron-right';
            } else {
                icon.className = 'fas fa-chevron-left';
            }
        }

        function switchTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
            });

            // Show selected tab
            const selectedTab = document.getElementById(tabName);
            if (selectedTab) {
                selectedTab.classList.add('active');
            } else {
                console.error('Tab not found:', tabName);
                return;
            }

            // Find and activate the correct nav link
            const navLink = document.querySelector(`[onclick*="switchTab('${tabName}')"]`);
            if (navLink) {
                navLink.classList.add('active');
            }

            // Save current tab to localStorage
            localStorage.setItem('activeTab', tabName);

            // Update URL hash without scrolling
            history.replaceState(null, null, '#' + tabName);

            // Auto-collapse sidebar when switching to All Cases tab
            if (tabName === 'cases') {
                const sidebar = document.getElementById('sidebar');
                const icon = document.getElementById('toggleIcon');
                
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                    icon.className = 'fas fa-chevron-right';
                }
                
                loadCasesContent();
            } else if (tabName === 'add-case') {
                loadAddCaseContent();
            }
        }

        function loadCasesContent() {
            const casesContent = document.getElementById('cases-content');

            // Check if content is already loaded with actual data
            const hasTable = casesContent.querySelector('table.modern-table');
            const hasValidContent = casesContent.innerHTML.length > 200; // More than just loading message

            if (hasTable && hasValidContent) {
                console.log('Cases already loaded, skipping reload');
                return;
            }

            // Show loading state
            casesContent.innerHTML = '<h2><i class="fas fa-folder-open"></i> All Cases</h2><p>Loading cases...</p>';

            // Use fetch to load the content
            fetch('content/allCases/all_cases.php')
                .then(response => response.text())
                .then(data => {
                    casesContent.innerHTML = data;

                    // Execute scripts in the loaded content
                    const scripts = casesContent.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        if (script.src) {
                            newScript.src = script.src;
                        } else {
                            newScript.textContent = script.textContent;
                        }
                        document.body.appendChild(newScript);
                    });
                })
                .catch(error => {
                    casesContent.innerHTML = '<h2><i class="fas fa-folder-open"></i> All Cases</h2><p style="color: red;">Error loading cases. Please try again.</p>';
                    console.error('Error loading cases:', error);
                });
        }

        function loadAddCaseContent() {
            const addCaseContent = document.getElementById('add-case');

            // Check if content is already loaded and valid (has actual form content)
            const hasForm = addCaseContent.querySelector('#addCaseForm');
            const hasValidContent = addCaseContent.innerHTML.includes('addCaseForm');

            if (hasForm && hasValidContent) {
                console.log('Form already loaded, skipping reload');
                return;
            }

            // Show loading state
            addCaseContent.innerHTML = '<h2><i class="fas fa-plus-circle"></i> Add New Case</h2><p>Loading form...</p>';

            // Remove old dynamic scripts to prevent conflicts
            document.querySelectorAll('script[data-dynamic-script^="add-case-"]').forEach(s => s.remove());

            // Use fetch to load the content
            fetch('content/addCase/add_case.php')
                .then(response => response.text())
                .then(data => {
                    addCaseContent.innerHTML = data;

                    // Execute scripts in the loaded content
                    const scripts = addCaseContent.querySelectorAll('script');
                    scripts.forEach((script, index) => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        // Add unique identifier to prevent conflicts
                        newScript.setAttribute('data-dynamic-script', 'add-case-' + index);
                        document.body.appendChild(newScript);
                    });
                })
                .catch(error => {
                    addCaseContent.innerHTML = '<h2><i class="fas fa-plus-circle"></i> Add New Case</h2><p style="color: red;">Error loading form. Please try again.</p>';
                    console.error('Error loading add case form:', error);
                });
        }

        // Load dashboard statistics
        function loadDashboardStats() {
            fetch('content/get_dashboard_stats.php')
                .then(response => response.json())
                .then(data => {
                    // Update stat cards
                    const totalCases = data.total || 0;
                    document.getElementById('total-cases').textContent = totalCases;
                    document.getElementById('pending-cases').textContent = data.pending || 0;
                    document.getElementById('ongoing-cases').textContent = data.ongoing || 0;
                    document.getElementById('closed-cases').textContent = data.closed || 0;

                    // Calculate percentages (avoid division by zero)
                    if (totalCases > 0) {
                        const pendingPercent = Math.round((data.pending / totalCases) * 100);
                        const ongoingPercent = Math.round((data.ongoing / totalCases) * 100);
                        const closedPercent = Math.round((data.closed / totalCases) * 100);

                        // Update progress bar
                        document.getElementById('pending-bar').style.width = pendingPercent + '%';
                        document.getElementById('pending-bar').setAttribute('data-tooltip', `Pending: ${pendingPercent}%`);
                        document.getElementById('ongoing-bar').style.width = ongoingPercent + '%';
                        document.getElementById('ongoing-bar').setAttribute('data-tooltip', `Ongoing: ${ongoingPercent}%`);
                        document.getElementById('closed-bar').style.width = closedPercent + '%';
                        document.getElementById('closed-bar').setAttribute('data-tooltip', `Closed: ${closedPercent}%`);

                        // Update legend percentages
                        document.getElementById('pending-percent').textContent = pendingPercent;
                        document.getElementById('ongoing-percent').textContent = ongoingPercent;
                        document.getElementById('closed-percent').textContent = closedPercent;
                    } else {
                        // Show empty state when no cases exist
                        document.getElementById('pending-bar').style.width = '0%';
                        document.getElementById('ongoing-bar').style.width = '0%';
                        document.getElementById('closed-bar').style.width = '0%';

                        document.getElementById('pending-percent').textContent = '0';
                        document.getElementById('ongoing-percent').textContent = '0';
                        document.getElementById('closed-percent').textContent = '0';

                        // Show helpful message in today's cases
                        const todayTbody = document.getElementById('today-cases-tbody');
                        if (todayTbody) {
                            todayTbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #6b7280;"><i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>No cases yet. Click "Add New Case" to get started.</td></tr>';
                        }

                        // Show helpful message in recent updates
                        const recentTbody = document.getElementById('recent-updates-tbody');
                        if (recentTbody) {
                            recentTbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #6b7280;"><i class="fas fa-history" style="font-size: 48px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>No recent updates.</td></tr>';
                        }
                    }

                    // Load today's cases and recent updates
                    loadTodayCases();
                    loadRecentUpdates();
                })
                .catch(error => {
                    console.error('Error loading dashboard stats:', error);
                    // Show error state
                    const todayTbody = document.getElementById('today-cases-tbody');
                    if (todayTbody) {
                        todayTbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px; color: #ef4444;"><i class="fas fa-exclamation-triangle" style="font-size: 48px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>Error loading dashboard data. Please refresh the page.</td></tr>';
                    }
                });
        }

        // Load today's cases
        function loadTodayCases() {
            fetch('content/get_today_cases.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('today-cases-tbody');
                    const badge = document.querySelector('.summary-card:first-child .badge-count');

                    if (badge) badge.textContent = data.count || 0;

                    if (data.success && data.cases.length > 0) {
                        tbody.innerHTML = data.cases.map(c => {
                            const statusClass = c.case_status.toLowerCase();
                            const time = new Date(c.created_at).toLocaleTimeString('en-GB', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            const opens = (c.opens || '-').substring(0, 40) + ((c.opens && c.opens.length > 40) ? '...' : '');
                            return `
                                <tr onclick="switchTab('cases');" style="cursor: pointer;">
                                    <td><strong>${c.case_number}</strong></td>
                                    <td>${c.information_book}</td>
                                    <td>${c.register_number}</td>
                                    <td>${opens}</td>
                                    <td><span class="badge-status badge-${statusClass}">${c.case_status}</span></td>
                                    <td>${time}</td>
                                </tr>
                            `;
                        }).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">No cases created today</td></tr>';
                    }
                })
                .catch(error => console.error('Error loading today cases:', error));
        }

        // Load recent updates
        function loadRecentUpdates() {
            fetch('content/get_recent_updates.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('recent-updates-tbody');
                    const badge = document.querySelector('.summary-card:last-child .badge-count');

                    if (badge) badge.textContent = data.count || 0;

                    if (data.success && data.cases.length > 0) {
                        tbody.innerHTML = data.cases.map(c => {
                            const statusClass = c.case_status.toLowerCase();
                            const time = c.updated_at ? new Date(c.updated_at).toLocaleString('en-GB', {
                                month: 'short',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            }) : '-';
                            const opens = (c.opens || '-').substring(0, 40) + ((c.opens && c.opens.length > 40) ? '...' : '');
                            return `
                                <tr onclick="switchTab('cases');" style="cursor: pointer;">
                                    <td><strong>${c.case_number}</strong></td>
                                    <td>${c.information_book}</td>
                                    <td>${c.register_number}</td>
                                    <td>${opens}</td>
                                    <td><span class="badge-status badge-${statusClass}">${c.case_status}</span></td>
                                    <td>${time}</td>
                                </tr>
                            `;
                        }).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">No recent updates</td></tr>';
                    }
                })
                .catch(error => console.error('Error loading recent updates:', error));
        }

        // Load stats when page loads and restore active tab
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();

            // Restore active tab from localStorage or URL hash after a brief delay
            setTimeout(function() {
                const hash = window.location.hash.substring(1);
                const savedTab = localStorage.getItem('activeTab');
                const activeTab = hash || savedTab || 'dashboard';

                console.log('Restoring tab:', activeTab);

                if (activeTab && activeTab !== 'dashboard') {
                    switchTab(activeTab);
                }
            }, 100); // Small delay to ensure DOM is fully ready
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const hash = window.location.hash.substring(1);
            if (hash) {
                switchTab(hash);
            }
        });
    </script>


</body>

</html>