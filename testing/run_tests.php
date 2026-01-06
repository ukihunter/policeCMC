<?php

/**
 * Quick Test Runner - Run All Tests
 * Simple script to execute all tests and display results
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police CMS - Test Runner</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 16px;
        }

        .test-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .test-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .test-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .test-card h2 {
            color: #34495e;
            margin-bottom: 15px;
            font-size: 22px;
        }

        .test-card p {
            color: #7f8c8d;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .test-card .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: opacity 0.3s;
        }

        .test-card .btn:hover {
            opacity: 0.9;
        }

        .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .instructions {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .instructions h2 {
            color: #34495e;
            margin-bottom: 20px;
        }

        .instructions ol {
            color: #555;
            line-height: 2;
            padding-left: 20px;
        }

        .instructions li {
            margin-bottom: 10px;
        }

        .instructions code {
            background: #f8f9fa;
            padding: 2px 8px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #e74c3c;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #f39c12;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .warning-box strong {
            color: #856404;
        }

        .info-box {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .info-box strong {
            color: #0c5460;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Police CMS Test Suite</h1>
            <p>Automated testing for all system functions and endpoints</p>
        </div>

        <div class="test-cards">
            <div class="test-card" onclick="window.open('test_system.php', '_blank')">
                <div class="icon">🔍</div>
                <h2>Basic System Test</h2>
                <p>Tests file existence, syntax errors, security checks, and basic configuration validation.</p>
                <a href="test_system.php" target="_blank" class="btn">Run Basic Test</a>
            </div>

            <div class="test-card" onclick="window.open('test_detailed.php', '_blank')">
                <div class="icon">🔬</div>
                <h2>Detailed Function Test</h2>
                <p>Executes actual endpoints with data, tests database queries, and validates responses.</p>
                <a href="test_detailed.php" target="_blank" class="btn">Run Detailed Test</a>
            </div>
        </div>

        <div class="instructions">
            <h2>📋 How to Use</h2>

            <div class="info-box">
                <strong>ℹ️ Before Running Tests:</strong> Make sure your XAMPP server (Apache & MySQL) is running and you have at least one admin user in the database.
            </div>

            <ol>
                <li><strong>Basic System Test:</strong> Click "Run Basic Test" to check:
                    <ul style="margin-top: 10px;">
                        <li>✓ Database connectivity</li>
                        <li>✓ All required files exist</li>
                        <li>✓ PHP syntax validation</li>
                        <li>✓ Security checks (SQL injection, XSS)</li>
                        <li>✓ Session handling</li>
                    </ul>
                </li>

                <li><strong>Detailed Function Test:</strong> Click "Run Detailed Test" to:
                    <ul style="margin-top: 10px;">
                        <li>✓ Execute actual endpoint functions</li>
                        <li>✓ Validate database operations</li>
                        <li>✓ Test API responses</li>
                        <li>✓ Check data integrity</li>
                    </ul>
                </li>

                <li><strong>Review Results:</strong> Each test will open in a new tab showing:
                    <ul style="margin-top: 10px;">
                        <li>✅ <strong>Pass:</strong> Function works correctly</li>
                        <li>❌ <strong>Fail:</strong> Function has errors</li>
                        <li>⚠️ <strong>Warning:</strong> Function works but needs attention</li>
                    </ul>
                </li>

                <li><strong>Fix Issues:</strong> Review failed tests and fix the corresponding files</li>

                <li><strong>Re-run Tests:</strong> After fixes, run tests again to verify</li>
            </ol>

            <div class="warning-box">
                <strong>⚠️ Important Notes:</strong>
                <ul style="margin-top: 10px; padding-left: 20px;">
                    <li>These tests require an active session. Make sure you have a user with ID=1 in your database, or edit the test files to use a different user ID.</li>
                    <li>The detailed test will actually execute functions and may create test data.</li>
                    <li>Run tests on a development environment, not on production!</li>
                </ul>
            </div>

            <h2 style="margin-top: 30px;">🛠️ Configuration</h2>
            <p style="color: #555; margin-top: 10px;">If you need to customize the tests, edit these files:</p>
            <ul style="color: #555; margin-top: 10px; line-height: 2;">
                <li><code>test_system.php</code> - Basic system tests configuration</li>
                <li><code>test_detailed.php</code> - Detailed function tests configuration</li>
            </ul>
            <p style="color: #555; margin-top: 15px;">
                In each file, look for the line: <code>$_SESSION['user_id'] = 1;</code><br>
                Change the number to match a valid admin user ID in your database.
            </p>
        </div>
    </div>
</body>

</html>