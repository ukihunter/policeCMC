<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<p>Please log in to access this page.</p>';
    exit;
}

// Get current user info including role
require_once __DIR__ . '/../../../config/db.php';

$current_user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();
$is_admin = ($current_user['role'] === 'admin');
?>

<div class="users-container">
    <!-- Tab Navigation -->
    <div class="users-tab-nav">
        <button class="users-tab-btn active" onclick="switchUserTab('change-password')">
            <i class="fas fa-key"></i> Change Password
        </button>
        <?php if ($is_admin): ?>
            <button class="users-tab-btn" onclick="switchUserTab('manage-users')">
                <i class="fas fa-users-cog"></i> Manage Users
            </button>
            <button class="users-tab-btn" onclick="switchUserTab('backup')">
                <i class="fas fa-database"></i> Backup
            </button>
            <button class="users-tab-btn" onclick="switchUserTab('activity-log')">
                <i class="fas fa-history"></i> Activity Log
            </button>
            <button class="users-tab-btn" onclick="switchUserTab('system-details')">
                <i class="fas fa-cog"></i> Details
            </button>
        <?php endif; ?>
    </div>

    <!-- Change Password Tab -->
    <div id="change-password-tab" class="users-tab-content active">
        <div class="section-card">
            <h3><i class="fas fa-key"></i> Change Your Password</h3>
            <p class="section-description">Update your account password for security</p>

            <form id="changePasswordForm" class="user-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="current_password">
                            <i class="fas fa-lock"></i> Current Password
                        </label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="new_password">
                            <i class="fas fa-lock"></i> New Password
                        </label>
                        <input type="password" id="new_password" name="new_password" required minlength="6">
                        <small>Minimum 6 characters</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-lock"></i> Confirm New Password
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($is_admin): ?>
        <!-- Manage Users Tab (Admin Only) -->
        <div id="manage-users-tab" class="users-tab-content">
            <div class="section-card">
                <div class="section-header">
                    <div>
                        <h3><i class="fas fa-users-cog"></i> User Management</h3>
                        <p class="section-description">Add and manage system users</p>
                    </div>
                    <button class="btn-primary" onclick="openAddUserModal()">
                        <i class="fas fa-user-plus"></i> Add New User
                    </button>
                </div>

                <div class="users-table-container">
                    <table class="users-table" id="usersTable">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Rank</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr>
                                <td colspan="7" class="loading-cell">Loading users...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Backup Tab (Admin Only) -->
<?php if ($is_admin): ?>
    <div id="backup-tab" class="users-tab-content">
        <div class="section-card">
            <!-- <h3><i class="fas fa-database"></i> Database Backup</h3>
            <p class="section-description">Create backups of your database to OneDrive or Desktop</p>

            <div class="backup-options">
                <div class="backup-option-card">
                    <div class="backup-icon" style="background: linear-gradient(135deg, #0078d4 0%, #005a9e 100%);">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="backup-info">
                        <h4>Quick Backup</h4>
                        <p>Download database backup file to your computer</p>
                        <button class="btn-backup-onedrive" onclick="backupToOneDrive()">
                            <i class="fas fa-download"></i> Download Backup
                        </button>
                    </div>
                </div>  -->

            <div class="backup-option-card">
                <div class="backup-icon" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                    <i class="fas fa-hdd"></i>
                </div>
                <div class="backup-info">
                    <h4>Alternative Backup</h4>
                    <p>Download database backup with alternate method</p>
                    <button class="btn-backup-local" onclick="backupToDesktop()">
                        <i class="fas fa-download"></i> Download Backup
                    </button>
                </div>
            </div>
        </div>

        <div class="backup-info-section">
            <h4><i class="fas fa-info-circle"></i> Backup Information</h4>
            <ul class="backup-tips">
                <li><i class="fas fa-check-circle"></i> Backups include all cases, users, and system settings</li>
                <li><i class="fas fa-check-circle"></i> Backup files are timestamped for easy identification</li>
                <li><i class="fas fa-check-circle"></i> Files are automatically downloaded to your Downloads folder</li>
                <li><i class="fas fa-check-circle"></i> You can then save backups to OneDrive, Desktop, or any location</li>
                <li><i class="fas fa-check-circle"></i> Regular backups are recommended for data safety</li>
            </ul>
        </div>
    </div>
    </div>
<?php endif; ?>

<!-- Activity Log Tab (Admin Only) -->
<?php if ($is_admin): ?>
    <div id="activity-log-tab" class="users-tab-content">
        <?php include 'activity_log.php'; ?>
    </div>

    <!-- System Details Tab (Admin Only) -->
    <div id="system-details-tab" class="users-tab-content">
        <div class="section-card">
            <h3><i class="fas fa-cog"></i> System Details</h3>
            <p class="section-description">Configure system-wide settings</p>

            <form id="systemDetailsForm" class="user-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="police_station">
                            <i class="fas fa-building"></i> Police Station *
                        </label>
                        <select id="police_station" name="police_station" required>
                            <option value="">Select Police Station</option>
                            <option value="Panadura south">Panadura south</option>
                            <option value="Panadura north">Panadura north</option>
                            <option value="Alubomulla">Alubomulla</option>
                            <option value="Hirana">Hirana</option>
                            <option value="Bandaragama">Bandaragama</option>
                            <option value="Anguruwathota">Anguruwathota</option>
                            <option value="Pinwatta">Pinwatta</option>
                            <option value="Wadduwa">Wadduwa</option>
                            <option value="Moronthuduwa">Moronthuduwa</option>
                            <option value="Millaniya">Millaniya</option>
                            <option value="Moragahahena">Moragahahena</option>
                            <option value="Ingiriya">Ingiriya</option>
                            <option value="Horana">Horana</option>
                            <option value="CUSTOM">Other (Type Custom Value)</option>
                        </select>
                        <input type="text" id="police_station_custom" name="police_station_custom" style="display:none; margin-top: 10px;" placeholder="Type custom Police Station">
                        <small>This police station name will appear on all printed documents</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Add User Modal (Admin Only) -->
<?php if ($is_admin): ?>
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus"></i> Add New User</h2>
                <span class="close-modal" onclick="closeAddUserModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="addUserForm" class="user-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="add_full_name">
                                <i class="fas fa-user"></i> Full Name *
                            </label>
                            <input type="text" id="add_full_name" name="full_name" required>
                        </div>

                        <div class="form-group">
                            <label for="add_email">
                                <i class="fas fa-envelope"></i> Email *
                            </label>
                            <input type="email" id="add_email" name="email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="add_position">
                                <i class="fas fa-briefcase"></i> Position
                            </label>
                            <select id="add_position" name="position">
                                <option value="">Select Position</option>
                                <option value="HQI">HQI</option>
                                <option value="OIC">OIC</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="add_rank_title">
                                <i class="fas fa-medal"></i> Rank Title
                            </label>
                            <select id="add_rank_title" name="rank_title">
                                <option value="">Select Rank</option>
                                <option value="CI">CI</option>
                                <option value="WCI">WCI</option>
                                <option value="IP">IP</option>
                                <option value="WIP">WIP</option>
                                <option value="SI">SI</option>
                                <option value="WSI">WSI</option>
                                <option value="PS">PS</option>
                                <option value="WPS">WPS</option>
                                <option value="PC">PC</option>
                                <option value="WPC">WPC</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="add_password">
                                <i class="fas fa-lock"></i> Password *
                            </label>
                            <input type="password" id="add_password" name="password" required minlength="6">
                            <small>Minimum 6 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="add_role">
                                <i class="fas fa-user-shield"></i> Role *
                            </label>
                            <select id="add_role" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <small>Admin can manage users, User can only change password</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeAddUserModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-edit"></i> Edit User</h2>
                <span class="close-modal" onclick="closeEditUserModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editUserForm" class="user-form">
                    <input type="hidden" id="edit_user_id" name="user_id">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_full_name">
                                <i class="fas fa-user"></i> Full Name *
                            </label>
                            <input type="text" id="edit_full_name" name="full_name" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_email">
                                <i class="fas fa-envelope"></i> Email *
                            </label>
                            <input type="email" id="edit_email" name="email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_position">
                                <i class="fas fa-briefcase"></i> Position
                            </label>
                            <select id="edit_position" name="position">
                                <option value="">Select Position</option>
                                <option value="HQI">HQI</option>
                                <option value="OIC">OIC</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_rank_title">
                                <i class="fas fa-medal"></i> Rank Title
                            </label>
                            <select id="edit_rank_title" name="rank_title">
                                <option value="">Select Rank</option>
                                <option value="CI">CI</option>
                                <option value="WCI">WCI</option>
                                <option value="IP">IP</option>
                                <option value="WIP">WIP</option>
                                <option value="SI">SI</option>
                                <option value="WSI">WSI</option>
                                <option value="PS">PS</option>
                                <option value="WPS">WPS</option>
                                <option value="PC">PC</option>
                                <option value="WPC">WPC</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_role">
                                <i class="fas fa-user-shield"></i> Role *
                            </label>
                            <select id="edit_role" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_status">
                                <i class="fas fa-toggle-on"></i> Status *
                            </label>
                            <select id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeEditUserModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset User Password Modal -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-key"></i> Reset User Password</h2>
                <span class="close-modal" onclick="closeResetPasswordModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="resetPasswordForm" class="user-form">
                    <input type="hidden" id="reset_user_id" name="user_id">

                    <div class="alert-info" style="margin-bottom: 20px; padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
                        <i class="fas fa-info-circle"></i>
                        Resetting password for: <strong id="reset_user_name"></strong>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="reset_new_password">
                                <i class="fas fa-lock"></i> New Password *
                            </label>
                            <input type="password" id="reset_new_password" name="new_password" required minlength="6">
                            <small>Minimum 6 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="reset_confirm_password">
                                <i class="fas fa-lock"></i> Confirm New Password *
                            </label>
                            <input type="password" id="reset_confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeResetPasswordModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Tab Switching
    function switchUserTab(tabName) {
        // Remove active class from all tabs
        document.querySelectorAll('.users-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.users-tab-content').forEach(content => content.classList.remove('active'));

        // Add active class to selected tab
        if (tabName === 'change-password') {
            document.querySelectorAll('.users-tab-btn')[0].classList.add('active');
            document.getElementById('change-password-tab').classList.add('active');
        } else if (tabName === 'manage-users') {
            document.querySelectorAll('.users-tab-btn')[1].classList.add('active');
            document.getElementById('manage-users-tab').classList.add('active');
            loadUsers(); // Load users when tab is opened
        } else if (tabName === 'backup') {
            document.querySelectorAll('.users-tab-btn')[2].classList.add('active');
            document.getElementById('backup-tab').classList.add('active');
        } else if (tabName === 'activity-log') {
            document.querySelectorAll('.users-tab-btn')[3].classList.add('active');
            document.getElementById('activity-log-tab').classList.add('active');
            if (typeof loadActivities === 'function') {
                loadActivities(); // Load activities when tab is opened
            }
        } else if (tabName === 'system-details') {
            document.querySelectorAll('.users-tab-btn')[4].classList.add('active');
            document.getElementById('system-details-tab').classList.add('active');
            loadSystemSettings(); // Load system settings when tab is opened
        }
    }

    // Change Password
    document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword !== confirmPassword) {
            showNotification('Passwords do not match', 'error');
            return;
        }

        if (newPassword.length < 6) {
            showNotification('Password must be at least 6 characters', 'error');
            return;
        }

        try {
            const response = await fetch('content/users/change_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    new_password: newPassword
                })
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                this.reset();
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            showNotification('Error changing password', 'error');
        }
    });

    <?php if ($is_admin): ?>
        // Backup Database to OneDrive
        async function backupToOneDrive() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;

            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Backing up...';

            try {
                const response = await fetch('content/users/backup_database.php', {
                    method: 'POST',
                    credentials: 'same-origin'
                });
                const data = await response.json();

                console.log('OneDrive Backup Response:', data);

                if (data.success) {
                    // Auto-download the backup file
                    const downloadLink = document.createElement('a');
                    downloadLink.href = data.download_url;
                    downloadLink.download = data.filename;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    showNotification(
                        `✅ Backup Successful!<br><br>` +
                        `<strong>File:</strong> ${data.filename}<br>` +
                        `<strong>Size:</strong> ${data.size}<br>` +
                        `<strong>Download started automatically</strong>`,
                        'success'
                    );
                } else {
                    console.error('Backup failed:', data);
                    showNotification(
                        '❌ Backup Failed<br><br>' + (data.message || 'Unknown error'),
                        'error'
                    );
                }
            } catch (error) {
                console.error('Backup error:', error);
                showNotification('❌ Error creating backup: ' + error.message, 'error');
            } finally {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        }

        // Backup Database to Desktop
        async function backupToDesktop() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;

            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Backing up...';

            try {
                const response = await fetch('content/users/backup_desktop.php', {
                    method: 'POST',
                    credentials: 'same-origin'
                });
                const data = await response.json();

                console.log('Desktop Backup Response:', data);

                if (data.success) {
                    // Auto-download the backup file
                    const downloadLink = document.createElement('a');
                    downloadLink.href = data.download_url;
                    downloadLink.download = data.filename;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    showNotification(
                        `✅ Backup Successful!<br><br>` +
                        `<strong>File:</strong> ${data.filename}<br>` +
                        `<strong>Size:</strong> ${data.size}<br>` +
                        `<strong>Download started automatically</strong>`,
                        'success'
                    );
                } else {
                    console.error('Backup failed:', data);
                    showNotification(
                        '❌ Backup Failed<br><br>' + (data.message || 'Unknown error'),
                        'error'
                    );
                }
            } catch (error) {
                console.error('Backup error:', error);
                showNotification('❌ Error creating backup: ' + error.message, 'error');
            } finally {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        }

        // Load Users (Admin only)
        async function loadUsers() {
            try {
                const response = await fetch('content/users/get_users.php');
                const data = await response.json();

                if (data.success) {
                    const tbody = document.getElementById('usersTableBody');

                    if (data.users.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="7" class="no-data">No users found</td></tr>';
                        return;
                    }

                    tbody.innerHTML = data.users.map(user => `
                <tr>
                    <td>${escapeHtml(user.full_name)}</td>
                    <td>${escapeHtml(user.email)}</td>
                    <td>${escapeHtml(user.position || '-')}</td>
                    <td>${escapeHtml(user.rank_title || '-')}</td>
                    <td>
                        <span class="badge badge-${user.role === 'admin' ? 'admin' : 'user'}">
                            ${user.role === 'admin' ? 'Admin' : 'User'}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-${user.status === 'active' ? 'active' : 'inactive'}">
                            ${user.status === 'active' ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <button class="btn-icon" onclick="editUser(${user.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-warning" onclick="resetUserPassword(${user.id}, '${escapeHtml(user.full_name)}')" title="Reset Password">
                            <i class="fas fa-key"></i>
                        </button>
                        ${user.id !== <?php echo $current_user_id; ?> ? `
                        <button class="btn-icon btn-danger" onclick="deleteUser(${user.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        ` : ''}
                    </td>
                </tr>
            `).join('');
                } else {
                    showNotification('Error loading users', 'error');
                }
            } catch (error) {
                showNotification('Error loading users', 'error');
            }
        }

        // Load System Settings
        async function loadSystemSettings() {
            try {
                const response = await fetch('content/users/get_system_settings.php');
                const data = await response.json();

                if (data.success) {
                    const policeStationSelect = document.getElementById('police_station');
                    const policeStationCustom = document.getElementById('police_station_custom');
                    const savedValue = data.police_station;

                    // Check if saved value exists in the dropdown options
                    const optionExists = Array.from(policeStationSelect.options).some(
                        option => option.value === savedValue && option.value !== 'CUSTOM'
                    );

                    if (optionExists) {
                        // Standard value - just select it
                        policeStationSelect.value = savedValue;
                    } else if (savedValue) {
                        // Custom value - select CUSTOM and populate custom input
                        policeStationSelect.value = 'CUSTOM';
                        policeStationCustom.value = savedValue;
                        policeStationCustom.style.display = 'block';
                        policeStationCustom.required = true;
                        policeStationSelect.required = false;
                    }
                }
            } catch (error) {
                console.error('Error loading system settings:', error);
            }
        }

        // System Details Form Submit
        document.getElementById('systemDetailsForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Handle custom police station
            const policeStation = formData.get('police_station');
            const customValue = formData.get('police_station_custom');

            if (policeStation === 'CUSTOM' && customValue) {
                formData.set('police_station', customValue);
            }
            formData.delete('police_station_custom');

            try {
                const response = await fetch('content/users/save_system_settings.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Error saving settings', 'error');
            }
        });

        // Add User Modal Functions
        function openAddUserModal() {
            document.getElementById('addUserModal').style.display = 'flex';
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
            document.getElementById('addUserForm').reset();
        }

        // Police Station Custom Input Handler
        document.getElementById('police_station').addEventListener('change', function() {
            const customInput = document.getElementById('police_station_custom');
            if (this.value === 'CUSTOM') {
                customInput.style.display = 'block';
                customInput.required = true;
                this.required = false;
            } else {
                customInput.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
                this.required = true;
            }
        });

        // Add User Form Submit
        document.getElementById('addUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Handle custom police station
            if (data.police_station === 'CUSTOM') {
                data.police_station = data.police_station_custom;
            }
            delete data.police_station_custom;

            try {
                const response = await fetch('content/users/add_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                    closeAddUserModal();
                    loadUsers();
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Error adding user', 'error');
            }
        });

        // Edit User
        async function editUser(userId) {
            try {
                const response = await fetch(`content/users/get_user.php?id=${userId}`);
                const data = await response.json();

                if (data.success) {
                    document.getElementById('edit_user_id').value = data.user.id;
                    document.getElementById('edit_full_name').value = data.user.full_name;
                    document.getElementById('edit_email').value = data.user.email;
                    document.getElementById('edit_position').value = data.user.position || '';
                    document.getElementById('edit_rank_title').value = data.user.rank_title || '';
                    document.getElementById('edit_role').value = data.user.role;
                    document.getElementById('edit_status').value = data.user.status;

                    document.getElementById('editUserModal').style.display = 'flex';
                } else {
                    showNotification('Error loading user data', 'error');
                }
            } catch (error) {
                showNotification('Error loading user data', 'error');
            }
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').style.display = 'none';
            document.getElementById('editUserForm').reset();
        }

        // Edit User Form Submit
        document.getElementById('editUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('content/users/update_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                    closeEditUserModal();
                    loadUsers();
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Error updating user', 'error');
            }
        });

        // Delete User
        async function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch('content/users/delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification(data.message, 'success');
                    loadUsers();
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                showNotification('Error deleting user', 'error');
            }
        }

        // Reset User Password
        function resetUserPassword(userId, userName) {
            document.getElementById('reset_user_id').value = userId;
            document.getElementById('reset_user_name').textContent = userName;
            document.getElementById('resetPasswordModal').style.display = 'flex';
        }

        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').style.display = 'none';
            document.getElementById('resetPasswordForm').reset();
        }

        // Reset Password Form Submit
        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const newPassword = document.getElementById('reset_new_password').value;
            const confirmPassword = document.getElementById('reset_confirm_password').value;

            if (newPassword !== confirmPassword) {
                showNotification('Passwords do not match', 'error');
                return;
            }

            if (newPassword.length < 6) {
                showNotification('Password must be at least 6 characters', 'error');
                return;
            }

            const userId = document.getElementById('reset_user_id').value;

            try {
                const response = await fetch('content/users/reset_user_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        new_password: newPassword
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                    closeResetPasswordModal();
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Error resetting password', 'error');
            }
        });
    <?php endif; ?>

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
</script>