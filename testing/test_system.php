<?php

/**
 * Automated System Test Runner
 * Tests all endpoints and functions in the Police CMS
 */

session_start();
require_once 'config/db.php';

// Set test user session (you can modify this with actual admin credentials)
// For testing purposes, we'll create a temporary session
$_SESSION['user_id'] = 1; // Change this to a valid admin user ID
$_SESSION['role'] = 'admin';

class SystemTester
{
    private $conn;
    private $results = [];
    private $passed = 0;
    private $failed = 0;
    private $warnings = 0;
    private $phpPath;

    public function __construct($connection)
    {
        $this->conn = $connection;
        $this->phpPath = $this->findPhpExecutable();
    }

    /**
     * Find PHP executable path
     */
    private function findPhpExecutable()
    {
        // Try common XAMPP locations first
        $possiblePaths = [
            'C:/xampp/php/php.exe',
            'C:\\xampp\\php\\php.exe',
            '/xampp/php/php',
            PHP_BINARY, // The current PHP binary
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Fallback to 'php' and hope it's in PATH
        return 'php';
    }

    /**
     * Run all tests
     */
    public function runAllTests()
    {
        echo "<html><head><title>System Test Results</title>";
        echo "<style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .header { background: #2c3e50; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
            .summary { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .test-section { background: white; padding: 15px; margin-bottom: 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .test-item { padding: 10px; margin: 5px 0; border-left: 4px solid #ccc; background: #f9f9f9; }
            .pass { border-left-color: #27ae60; }
            .fail { border-left-color: #e74c3c; }
            .warning { border-left-color: #f39c12; }
            .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
            .badge-pass { background: #27ae60; color: white; }
            .badge-fail { background: #e74c3c; color: white; }
            .badge-warning { background: #f39c12; color: white; }
            .stats { display: flex; gap: 20px; }
            .stat-box { flex: 1; padding: 15px; text-align: center; border-radius: 5px; }
            .stat-pass { background: #d4edda; color: #155724; }
            .stat-fail { background: #f8d7da; color: #721c24; }
            .stat-warning { background: #fff3cd; color: #856404; }
            .details { font-size: 12px; color: #666; margin-top: 5px; }
            .timestamp { color: #999; font-size: 11px; }
        </style></head><body>";

        echo "<div class='header'><h1>🔍 Police CMS - Automated System Test</h1>";
        echo "<p class='timestamp'>Test Run: " . date('Y-m-d H:i:s') . "</p></div>";

        // Run different test categories
        $this->testDatabaseConnection();
        $this->testConfigFiles();
        $this->testUserManagementEndpoints();
        $this->testCaseManagementEndpoints();
        $this->testAuthenticationEndpoints();
        $this->testDashboardEndpoints();
        $this->testActivityLogger();

        // Display summary
        $this->displaySummary();

        echo "</body></html>";
    }

    /**
     * Test database connection
     */
    private function testDatabaseConnection()
    {
        echo "<div class='test-section'><h2>📊 Database Connection Tests</h2>";

        // Test connection
        if ($this->conn && !$this->conn->connect_error) {
            $this->addResult('Database Connection', 'pass', 'Connected successfully');

            // Test database exists
            $result = $this->conn->query("SELECT DATABASE()");
            if ($result) {
                $db = $result->fetch_row();
                $this->addResult('Database Selection', 'pass', "Database: {$db[0]}");
            }

            // Test required tables
            $tables = ['users', 'cases', 'activity_logs'];
            foreach ($tables as $table) {
                $result = $this->conn->query("SHOW TABLES LIKE '$table'");
                if ($result && $result->num_rows > 0) {
                    $this->addResult("Table: $table", 'pass', 'Table exists');
                } else {
                    $this->addResult("Table: $table", 'fail', 'Table not found');
                }
            }
        } else {
            $this->addResult('Database Connection', 'fail', 'Connection failed');
        }

        echo "</div>";
    }

    /**
     * Test configuration files
     */
    private function testConfigFiles()
    {
        echo "<div class='test-section'><h2>⚙️ Configuration Files Tests</h2>";

        $configFiles = [
            'config/db.php' => 'Database Configuration',
            'config/activity_logger.php' => 'Activity Logger',
        ];

        foreach ($configFiles as $file => $name) {
            $fullPath = __DIR__ . '/' . $file;
            if (file_exists($fullPath)) {
                $this->addResult($name, 'pass', "File exists: $file");

                // Check if file is readable
                if (is_readable($fullPath)) {
                    $this->addResult($name . ' - Readable', 'pass', 'File is readable');
                } else {
                    $this->addResult($name . ' - Readable', 'fail', 'File is not readable');
                }
            } else {
                $this->addResult($name, 'fail', "File not found: $file");
            }
        }

        echo "</div>";
    }

    /**
     * Test user management endpoints
     */
    private function testUserManagementEndpoints()
    {
        echo "<div class='test-section'><h2>👥 User Management Endpoints</h2>";

        $endpoints = [
            'Dashboard/content/users/get_users.php' => 'Get Users List',
            'Dashboard/content/users/get_user.php' => 'Get Single User',
            'Dashboard/content/users/add_user.php' => 'Add User',
            'Dashboard/content/users/update_user.php' => 'Update User',
            'Dashboard/content/users/delete_user.php' => 'Delete User',
            'Dashboard/content/users/reset_user_password.php' => 'Reset User Password',
            'Dashboard/content/users/change_password.php' => 'Change Password',
            'Dashboard/content/users/get_activities.php' => 'Get Activities',
            'Dashboard/content/users/get_system_settings.php' => 'Get System Settings',
            'Dashboard/content/users/save_system_settings.php' => 'Save System Settings',
        ];

        foreach ($endpoints as $file => $name) {
            $this->testEndpoint($file, $name);
        }

        echo "</div>";
    }

    /**
     * Test case management endpoints
     */
    private function testCaseManagementEndpoints()
    {
        echo "<div class='test-section'><h2>📁 Case Management Endpoints</h2>";

        $endpoints = [
            'Dashboard/content/addCase/save_case.php' => 'Save Case',
            'Dashboard/content/addCase/check_case_number.php' => 'Check Case Number',
            'Dashboard/content/allCases/get_case_details.php' => 'Get Case Details',
            'Dashboard/content/allCases/update_case.php' => 'Update Case',
            'Dashboard/content/allCases/get_next_date_history.php' => 'Get Next Date History',
            'Dashboard/content/allCases/log_print.php' => 'Log Print Action',
        ];

        foreach ($endpoints as $file => $name) {
            $this->testEndpoint($file, $name);
        }

        echo "</div>";
    }

    /**
     * Test authentication endpoints
     */
    private function testAuthenticationEndpoints()
    {
        echo "<div class='test-section'><h2>🔐 Authentication Endpoints</h2>";

        $endpoints = [
            'login/login.php' => 'Login Page',
            'Dashboard/logout.php' => 'Logout',
        ];

        foreach ($endpoints as $file => $name) {
            $this->testEndpoint($file, $name);
        }

        echo "</div>";
    }

    /**
     * Test dashboard endpoints
     */
    private function testDashboardEndpoints()
    {
        echo "<div class='test-section'><h2>📈 Dashboard Endpoints</h2>";

        $endpoints = [
            'Dashboard/content/get_dashboard_stats.php' => 'Dashboard Statistics',
            'Dashboard/content/get_today_cases.php' => 'Today\'s Cases',
            'Dashboard/content/get_recent_updates.php' => 'Recent Updates',
        ];

        foreach ($endpoints as $file => $name) {
            $this->testEndpoint($file, $name);
        }

        echo "</div>";
    }

    /**
     * Test activity logger functions
     */
    private function testActivityLogger()
    {
        echo "<div class='test-section'><h2>📝 Activity Logger Tests</h2>";

        // Test if activity_logger.php functions exist
        if (file_exists(__DIR__ . '/config/activity_logger.php')) {
            require_once __DIR__ . '/config/activity_logger.php';

            // Test function existence
            $functions = [
                'logActivity' => 'Log Activity Function',
                'getClientIP' => 'Get Client IP Function',
                'getBrowserInfo' => 'Get Browser Info Function',
                'getActivityIcon' => 'Get Activity Icon Function',
                'getActivityColor' => 'Get Activity Color Function',
                'getActivityLabel' => 'Get Activity Label Function',
            ];

            foreach ($functions as $func => $name) {
                if (function_exists($func)) {
                    $this->addResult($name, 'pass', "Function '$func' exists");
                } else {
                    $this->addResult($name, 'fail', "Function '$func' not found");
                }
            }
        } else {
            $this->addResult('Activity Logger File', 'fail', 'File not found');
        }

        echo "</div>";
    }

    /**
     * Test individual endpoint
     */
    private function testEndpoint($file, $name)
    {
        $fullPath = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);

        // Check if file exists
        if (file_exists($fullPath)) {
            $this->addResult($name, 'pass', "Endpoint exists: $file");

            // Check for syntax errors using PHP's built-in tokenizer
            $content = file_get_contents($fullPath);
            $syntaxCheck = $this->checkPhpSyntax($content, $fullPath);

            if ($syntaxCheck === true) {
                $this->addResult($name . ' - Syntax', 'pass', 'No syntax errors');
            } else {
                $this->addResult($name . ' - Syntax', 'fail', 'Syntax error: ' . $syntaxCheck);
            }

            // Check for common security issues
            $content = file_get_contents($fullPath);

            // Check for session_start
            if (strpos($file, 'login.php') === false && strpos($content, 'session_start()') !== false) {
                $this->addResult($name . ' - Session', 'pass', 'Session handling present');
            } elseif (strpos($file, 'login.php') === false) {
                $this->addResult($name . ' - Session', 'warning', 'No session handling found');
            }

            // Check for SQL injection prevention (prepared statements)
            if (strpos($content, 'prepare(') !== false) {
                $this->addResult($name . ' - SQL Security', 'pass', 'Uses prepared statements');
            } elseif (strpos($content, '$conn->query') !== false || strpos($content, 'mysql_query') !== false) {
                $this->addResult($name . ' - SQL Security', 'warning', 'May have SQL injection risk');
            }

            // Check for XSS prevention
            if (strpos($content, 'htmlspecialchars') !== false || strpos($content, 'strip_tags') !== false) {
                $this->addResult($name . ' - XSS Protection', 'pass', 'Has XSS protection');
            } elseif (strpos($content, 'echo') !== false && strpos($content, '$_') !== false) {
                $this->addResult($name . ' - XSS Protection', 'warning', 'Potential XSS vulnerability');
            }
        } else {
            $this->addResult($name, 'fail', "Endpoint not found: $file");
        }
    }

    /**
     * Check PHP syntax using token_get_all (more reliable than exec)
     */
    private function checkPhpSyntax($code, $filename)
    {
        $result = true;

        // Method 1: Try using PHP lint if available
        if ($this->phpPath && file_exists($this->phpPath)) {
            $tempFile = tempnam(sys_get_temp_dir(), 'php_check_');
            file_put_contents($tempFile, $code);

            $escapedPhp = escapeshellarg($this->phpPath);
            $escapedFile = escapeshellarg($tempFile);
            $output = [];
            $returnVar = 0;

            exec("\"$this->phpPath\" -l \"$tempFile\" 2>&1", $output, $returnVar);

            @unlink($tempFile);

            if ($returnVar === 0) {
                return true;
            } else {
                // Parse error message
                $errorMsg = implode(' ', $output);
                $errorMsg = str_replace($tempFile, basename($filename), $errorMsg);
                return $errorMsg;
            }
        }

        // Method 2: Fallback to tokenizer
        set_error_handler(function ($errno, $errstr) use (&$result) {
            $result = $errstr;
        });

        try {
            $tokens = @token_get_all($code);

            // If tokenization succeeded, check for common issues
            if ($result === true) {
                $codeToCheck = $code;

                // Remove comments and strings
                $codeToCheck = preg_replace('/\/\*.*?\*\//s', '', $codeToCheck);
                $codeToCheck = preg_replace('/\/\/.*$/m', '', $codeToCheck);

                // Check for unclosed braces
                $braceCount = substr_count($codeToCheck, '{') - substr_count($codeToCheck, '}');
                $bracketCount = substr_count($codeToCheck, '[') - substr_count($codeToCheck, ']');
                $parenCount = substr_count($codeToCheck, '(') - substr_count($codeToCheck, ')');

                if ($braceCount !== 0) {
                    $result = "Mismatched braces (difference: $braceCount)";
                } elseif ($bracketCount !== 0) {
                    $result = "Mismatched brackets (difference: $bracketCount)";
                } elseif ($parenCount !== 0) {
                    $result = "Mismatched parentheses (difference: $parenCount)";
                }
            }
        } catch (ParseError $e) {
            $result = $e->getMessage();
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        restore_error_handler();
        return $result;
    }

    /**
     * Add test result
     */
    private function addResult($test, $status, $message)
    {
        $this->results[] = [
            'test' => $test,
            'status' => $status,
            'message' => $message
        ];

        if ($status === 'pass') {
            $this->passed++;
        } elseif ($status === 'fail') {
            $this->failed++;
        } elseif ($status === 'warning') {
            $this->warnings++;
        }

        $badgeClass = "badge-$status";
        $itemClass = $status;
        $badge = strtoupper($status);

        echo "<div class='test-item $itemClass'>";
        echo "<span class='badge $badgeClass'>$badge</span> ";
        echo "<strong>$test</strong>";
        echo "<div class='details'>$message</div>";
        echo "</div>";
    }

    /**
     * Display summary
     */
    private function displaySummary()
    {
        $total = $this->passed + $this->failed + $this->warnings;
        $passRate = $total > 0 ? round(($this->passed / $total) * 100, 2) : 0;

        echo "<div class='summary'>";
        echo "<h2>📊 Test Summary</h2>";
        echo "<div class='stats'>";
        echo "<div class='stat-box stat-pass'><h3>{$this->passed}</h3><p>Passed</p></div>";
        echo "<div class='stat-box stat-fail'><h3>{$this->failed}</h3><p>Failed</p></div>";
        echo "<div class='stat-box stat-warning'><h3>{$this->warnings}</h3><p>Warnings</p></div>";
        echo "</div>";
        echo "<p style='margin-top: 20px; font-size: 18px;'><strong>Total Tests:</strong> $total | <strong>Pass Rate:</strong> $passRate%</p>";

        if ($this->failed > 0) {
            echo "<p style='color: #e74c3c; font-weight: bold;'>⚠️ Some tests failed. Please review the results above.</p>";
        } elseif ($this->warnings > 0) {
            echo "<p style='color: #f39c12; font-weight: bold;'>⚠️ All tests passed but there are some warnings to review.</p>";
        } else {
            echo "<p style='color: #27ae60; font-weight: bold;'>✅ All tests passed successfully!</p>";
        }

        echo "</div>";
    }
}

// Run tests
$tester = new SystemTester($conn);
$tester->runAllTests();
