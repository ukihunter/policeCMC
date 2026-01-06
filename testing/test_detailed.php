<?php

/**
 * Detailed Function Testing with Execution Tests
 * This script performs actual execution tests on endpoints
 */

session_start();
require_once 'config/db.php';

// Set admin session for testing
$_SESSION['user_id'] = 1; // Make sure this user exists in your database
$_SESSION['role'] = 'admin';

class DetailedTester
{
    private $conn;
    private $testResults = [];
    private $baseUrl;

    public function __construct($connection)
    {
        $this->conn = $connection;
        $this->baseUrl = 'http://localhost/police/'; // Adjust this to your local URL
    }

    public function runDetailedTests()
    {
        $this->outputHeader();

        // Test each category
        $this->testDatabaseHealth();
        $this->testUserEndpointsWithData();
        $this->testCaseEndpointsWithData();
        $this->testDashboardDataRetrieval();

        $this->outputSummary();
        $this->outputFooter();
    }

    private function outputHeader()
    {
        echo "<!DOCTYPE html><html><head><title>Detailed System Test</title>";
        echo "<style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; }
            .container { max-width: 1200px; margin: 0 auto; }
            .header { background: white; padding: 30px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
            .header h1 { color: #2c3e50; margin-bottom: 10px; }
            .test-category { background: white; padding: 20px; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
            .test-category h2 { color: #34495e; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px solid #3498db; }
            .test-item { margin: 10px 0; padding: 15px; border-radius: 5px; background: #f8f9fa; }
            .test-item.pass { border-left: 5px solid #27ae60; background: #d5f4e6; }
            .test-item.fail { border-left: 5px solid #e74c3c; background: #fadbd8; }
            .test-item.warning { border-left: 5px solid #f39c12; background: #fcf3cf; }
            .test-title { font-weight: bold; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; }
            .test-details { font-size: 13px; color: #555; line-height: 1.6; }
            .badge { padding: 4px 10px; border-radius: 3px; font-size: 11px; font-weight: bold; color: white; }
            .badge-pass { background: #27ae60; }
            .badge-fail { background: #e74c3c; }
            .badge-warning { background: #f39c12; }
            .code-block { background: #2c3e50; color: #ecf0f1; padding: 10px; border-radius: 3px; margin-top: 8px; font-family: 'Courier New', monospace; font-size: 12px; overflow-x: auto; }
            .summary { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin-top: 20px; }
            .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
            .summary-card { padding: 20px; border-radius: 8px; text-align: center; }
            .summary-card h3 { font-size: 36px; margin-bottom: 10px; }
            .summary-card.pass { background: #d5f4e6; color: #27ae60; }
            .summary-card.fail { background: #fadbd8; color: #e74c3c; }
            .summary-card.warning { background: #fcf3cf; color: #f39c12; }
            .timestamp { color: #7f8c8d; font-size: 14px; }
        </style></head><body><div class='container'>";

        echo "<div class='header'>";
        echo "<h1>🔬 Detailed System Function Test</h1>";
        echo "<p class='timestamp'>Test executed: " . date('Y-m-d H:i:s') . "</p>";
        echo "<p style='margin-top: 10px; color: #7f8c8d;'>This test performs actual execution and data validation on all system functions.</p>";
        echo "</div>";
    }

    private function testDatabaseHealth()
    {
        echo "<div class='test-category'><h2>🗄️ Database Health Check</h2>";

        // Test connection
        if ($this->conn && !$this->conn->connect_error) {
            $this->logTest('Database Connection', 'pass', 'Successfully connected to database');

            // Count users
            $result = $this->conn->query("SELECT COUNT(*) as count FROM users");
            if ($result) {
                $row = $result->fetch_assoc();
                $this->logTest('Users Table', 'pass', "Found {$row['count']} users in database");
            } else {
                $this->logTest('Users Table', 'fail', 'Unable to query users table: ' . $this->conn->error);
            }

            // Count cases
            $result = $this->conn->query("SELECT COUNT(*) as count FROM cases");
            if ($result) {
                $row = $result->fetch_assoc();
                $this->logTest('Cases Table', 'pass', "Found {$row['count']} cases in database");
            } else {
                $this->logTest('Cases Table', 'fail', 'Unable to query cases table: ' . $this->conn->error);
            }

            // Count activity logs
            $result = $this->conn->query("SELECT COUNT(*) as count FROM activity_logs");
            if ($result) {
                $row = $result->fetch_assoc();
                $this->logTest('Activity Logs Table', 'pass', "Found {$row['count']} activity logs");
            } else {
                $this->logTest('Activity Logs Table', 'fail', 'Unable to query activity_logs table: ' . $this->conn->error);
            }

            // Test indexes
            $result = $this->conn->query("SHOW INDEX FROM cases");
            if ($result) {
                $indexCount = $result->num_rows;
                $this->logTest(
                    'Cases Table Indexes',
                    $indexCount > 0 ? 'pass' : 'warning',
                    $indexCount > 0 ? "Found $indexCount indexes" : "No indexes found - performance may be affected"
                );
            }
        } else {
            $this->logTest('Database Connection', 'fail', 'Failed to connect: ' . $this->conn->connect_error);
        }

        echo "</div>";
    }

    private function testUserEndpointsWithData()
    {
        echo "<div class='test-category'><h2>👤 User Management Function Tests</h2>";

        // Test get_users.php
        $getUsersFile = __DIR__ . '/Dashboard/content/users/get_users.php';
        if (file_exists($getUsersFile)) {
            ob_start();
            try {
                include $getUsersFile;
                $output = ob_get_clean();
                $data = json_decode($output, true);

                if ($data && isset($data['success'])) {
                    if ($data['success']) {
                        $userCount = count($data['users'] ?? []);
                        $this->logTest('Get Users Endpoint', 'pass', "Successfully retrieved $userCount users<div class='code-block'>" . htmlspecialchars(substr($output, 0, 200)) . "...</div>");
                    } else {
                        $this->logTest('Get Users Endpoint', 'warning', "Endpoint responded but returned success=false: " . ($data['message'] ?? 'Unknown error'));
                    }
                } else {
                    $this->logTest('Get Users Endpoint', 'fail', "Invalid JSON response<div class='code-block'>" . htmlspecialchars(substr($output, 0, 200)) . "</div>");
                }
            } catch (Exception $e) {
                ob_end_clean();
                $this->logTest('Get Users Endpoint', 'fail', 'Exception: ' . $e->getMessage());
            }
        } else {
            $this->logTest('Get Users Endpoint', 'fail', 'File not found: ' . $getUsersFile);
        }

        // Test get_system_settings.php
        $settingsFile = __DIR__ . '/Dashboard/content/users/get_system_settings.php';
        if (file_exists($settingsFile)) {
            ob_start();
            try {
                include $settingsFile;
                $output = ob_get_clean();
                $data = json_decode($output, true);

                if ($data && isset($data['success'])) {
                    $this->logTest(
                        'Get System Settings',
                        $data['success'] ? 'pass' : 'warning',
                        $data['success'] ? 'System settings retrieved successfully' : ($data['message'] ?? 'Failed')
                    );
                } else {
                    $this->logTest('Get System Settings', 'fail', 'Invalid response format');
                }
            } catch (Exception $e) {
                ob_end_clean();
                $this->logTest('Get System Settings', 'fail', 'Exception: ' . $e->getMessage());
            }
        }

        echo "</div>";
    }

    private function testCaseEndpointsWithData()
    {
        echo "<div class='test-category'><h2>📋 Case Management Function Tests</h2>";

        // Test check_case_number.php
        $checkFile = __DIR__ . '/Dashboard/content/addCase/check_case_number.php';
        if (file_exists($checkFile)) {
            $_POST['case_number'] = 'TEST-' . time(); // Test with a unique case number
            ob_start();
            try {
                include $checkFile;
                $output = ob_get_clean();
                $data = json_decode($output, true);

                if ($data && isset($data['exists'])) {
                    $this->logTest('Check Case Number', 'pass', 'Case number validation working - returned: ' . ($data['exists'] ? 'exists' : 'available'));
                } else {
                    $this->logTest('Check Case Number', 'warning', 'Unexpected response format<div class="code-block">' . htmlspecialchars($output) . '</div>');
                }
            } catch (Exception $e) {
                ob_end_clean();
                $this->logTest('Check Case Number', 'fail', 'Exception: ' . $e->getMessage());
            }
            unset($_POST['case_number']);
        }

        // Test if we can query cases
        $result = $this->conn->query("SELECT * FROM cases LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $case = $result->fetch_assoc();
            $this->logTest('Case Data Structure', 'pass', 'Successfully retrieved case data. Sample case number: ' . ($case['case_number'] ?? 'N/A'));

            // Test get_case_details.php with real case
            $getDetailsFile = __DIR__ . '/Dashboard/content/allCases/get_case_details.php';
            if (file_exists($getDetailsFile)) {
                $_GET['case_id'] = $case['id'];
                ob_start();
                try {
                    include $getDetailsFile;
                    $output = ob_get_clean();
                    $data = json_decode($output, true);

                    if ($data && isset($data['success'])) {
                        $this->logTest(
                            'Get Case Details',
                            $data['success'] ? 'pass' : 'warning',
                            $data['success'] ? 'Case details retrieved for case ID: ' . $case['id'] : ($data['message'] ?? 'Failed')
                        );
                    }
                } catch (Exception $e) {
                    ob_end_clean();
                    $this->logTest('Get Case Details', 'fail', 'Exception: ' . $e->getMessage());
                }
                unset($_GET['case_id']);
            }
        } else {
            $this->logTest('Case Data Structure', 'warning', 'No cases found in database - some tests skipped');
        }

        echo "</div>";
    }

    private function testDashboardDataRetrieval()
    {
        echo "<div class='test-category'><h2>📊 Dashboard Data Functions</h2>";

        // Test get_dashboard_stats.php
        $statsFile = __DIR__ . '/Dashboard/content/get_dashboard_stats.php';
        if (file_exists($statsFile)) {
            ob_start();
            try {
                include $statsFile;
                $output = ob_get_clean();
                $data = json_decode($output, true);

                if ($data && isset($data['success'])) {
                    if ($data['success']) {
                        $stats = $data['stats'] ?? [];
                        $this->logTest(
                            'Dashboard Statistics',
                            'pass',
                            "Statistics retrieved: Total Cases: " . ($stats['total_cases'] ?? 'N/A') .
                                ", Pending: " . ($stats['pending_cases'] ?? 'N/A') .
                                ", Closed: " . ($stats['closed_cases'] ?? 'N/A')
                        );
                    } else {
                        $this->logTest('Dashboard Statistics', 'warning', $data['message'] ?? 'Failed to retrieve stats');
                    }
                } else {
                    $this->logTest('Dashboard Statistics', 'fail', 'Invalid response format');
                }
            } catch (Exception $e) {
                ob_end_clean();
                $this->logTest('Dashboard Statistics', 'fail', 'Exception: ' . $e->getMessage());
            }
        }

        // Test get_today_cases.php
        $todayFile = __DIR__ . '/Dashboard/content/get_today_cases.php';
        if (file_exists($todayFile)) {
            ob_start();
            try {
                include $todayFile;
                $output = ob_get_clean();
                $data = json_decode($output, true);

                if ($data && isset($data['success'])) {
                    $caseCount = is_array($data['cases'] ?? null) ? count($data['cases']) : 0;
                    $this->logTest('Today\'s Cases', 'pass', "Retrieved $caseCount cases for today");
                } else {
                    $this->logTest('Today\'s Cases', 'fail', 'Invalid response');
                }
            } catch (Exception $e) {
                ob_end_clean();
                $this->logTest('Today\'s Cases', 'fail', 'Exception: ' . $e->getMessage());
            }
        }

        // Test get_recent_updates.php
        $recentFile = __DIR__ . '/Dashboard/content/get_recent_updates.php';
        if (file_exists($recentFile)) {
            ob_start();
            try {
                include $recentFile;
                $output = ob_get_clean();
                $data = json_decode($output, true);

                if ($data && isset($data['success'])) {
                    $updateCount = is_array($data['cases'] ?? null) ? count($data['cases']) : 0;
                    $this->logTest('Recent Updates', 'pass', "Retrieved $updateCount recent updates");
                } else {
                    $this->logTest('Recent Updates', 'fail', 'Invalid response');
                }
            } catch (Exception $e) {
                ob_end_clean();
                $this->logTest('Recent Updates', 'fail', 'Exception: ' . $e->getMessage());
            }
        }

        echo "</div>";
    }

    private function logTest($title, $status, $details)
    {
        $this->testResults[] = ['title' => $title, 'status' => $status, 'details' => $details];

        $badgeClass = "badge-$status";
        echo "<div class='test-item $status'>";
        echo "<div class='test-title'>";
        echo "<span>$title</span>";
        echo "<span class='badge $badgeClass'>" . strtoupper($status) . "</span>";
        echo "</div>";
        echo "<div class='test-details'>$details</div>";
        echo "</div>";
    }

    private function outputSummary()
    {
        $passed = 0;
        $failed = 0;
        $warnings = 0;

        foreach ($this->testResults as $result) {
            if ($result['status'] === 'pass') $passed++;
            elseif ($result['status'] === 'fail') $failed++;
            elseif ($result['status'] === 'warning') $warnings++;
        }

        $total = count($this->testResults);
        $passRate = $total > 0 ? round(($passed / $total) * 100, 1) : 0;

        echo "<div class='summary'>";
        echo "<h2>📈 Test Execution Summary</h2>";
        echo "<div class='summary-grid'>";
        echo "<div class='summary-card pass'><h3>$passed</h3><p>Passed</p></div>";
        echo "<div class='summary-card fail'><h3>$failed</h3><p>Failed</p></div>";
        echo "<div class='summary-card warning'><h3>$warnings</h3><p>Warnings</p></div>";
        echo "<div class='summary-card' style='background: #e8f4f8; color: #3498db;'><h3>$passRate%</h3><p>Success Rate</p></div>";
        echo "</div>";

        echo "<div style='margin-top: 30px; padding: 20px; background: " .
            ($failed > 0 ? '#fadbd8' : ($warnings > 0 ? '#fcf3cf' : '#d5f4e6')) .
            "; border-radius: 5px;'>";

        if ($failed > 0) {
            echo "<h3 style='color: #e74c3c;'>⚠️ Action Required</h3>";
            echo "<p style='color: #c0392b;'>Some critical tests failed. Please review and fix the issues above.</p>";
        } elseif ($warnings > 0) {
            echo "<h3 style='color: #f39c12;'>⚠️ Review Recommended</h3>";
            echo "<p style='color: #d68910;'>All tests passed but there are warnings that should be reviewed.</p>";
        } else {
            echo "<h3 style='color: #27ae60;'>✅ All Systems Operational</h3>";
            echo "<p style='color: #229954;'>All tests passed successfully! Your system is functioning correctly.</p>";
        }

        echo "</div></div>";
    }

    private function outputFooter()
    {
        echo "</div></body></html>";
    }
}

// Run detailed tests
$tester = new DetailedTester($conn);
$tester->runDetailedTests();
