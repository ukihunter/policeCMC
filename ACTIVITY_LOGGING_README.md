# Activity Logging System - Documentation

## Overview

A comprehensive activity logging system has been implemented to track all user actions in the Police Case Management System. This feature is **admin-only** and provides complete visibility into system usage.

## Features Implemented

### 1. ✅ Activity Logging

Automatically tracks and logs:

- **Case Activities**

  - Case Added (who, when, IP address)
  - Case Edited (who, when, IP address)
  - Case Printed (who, when, IP address, single/bulk)
  - Case Deleted (who, when, IP address)

- **User Management Activities**
  - User Added (who added, user details)
  - User Edited (who edited, changes made)
  - User Deleted (who deleted, user details)
  - Password Changed (who changed their password)

### 2. ✅ Information Captured

For each activity, the system logs:

- User ID and Full Name
- Activity Type
- Case ID and Case Number (if applicable)
- Description of the action
- IP Address of the user
- User Agent (browser information)
- Timestamp (exact date and time)

### 3. ✅ Activity Dashboard (Admin Only)

**Location:** Dashboard → Users → Activity Log tab

**Features:**

- **Real-time Activity Timeline**: Visual timeline of all system activities
- **Activity Statistics**: Quick overview cards showing:

  - Total Activities
  - Cases Added
  - Cases Edited
  - Cases Printed

- **Advanced Filtering**:

  - Filter by Activity Type
  - Filter by User
  - Filter by Date
  - Reset Filters option

- **Pagination**: Load more activities as needed (20 per page)

- **Activity Details**: Each entry shows:
  - User who performed the action
  - Type of activity (with color-coded badges)
  - Description of what was done
  - Case number (if applicable)
  - IP address
  - Exact timestamp
  - Time ago (e.g., "2 hours ago")

### 4. ✅ Role-Based Access Control

- **Admin Users**: Can see:

  - Change Password tab
  - Manage Users tab
  - **Activity Log tab** ← NEW
  - Admin section in sidebar

- **Regular Users**: Can only see:
  - Change Password tab
  - NO admin section in sidebar
  - NO access to activity logs

## Database Structure

### activity_logs Table

```sql
CREATE TABLE activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  user_name VARCHAR(100),
  activity_type ENUM('case_added', 'case_edited', 'case_printed', 'case_deleted',
                     'user_added', 'user_edited', 'user_deleted', 'password_changed'),
  case_id INT,
  case_number VARCHAR(100),
  description TEXT,
  ip_address VARCHAR(45),
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE SET NULL
);
```

## Files Created/Modified

### New Files Created:

1. **Database**

   - `create_activity_logs.sql` - Database migration

2. **Backend**

   - `config/activity_logger.php` - Activity logging helper functions
   - `Dashboard/content/users/activity_log.php` - Activity log viewer interface
   - `Dashboard/content/users/get_activities.php` - API to fetch activities
   - `Dashboard/content/allCases/log_print.php` - Log print activities

3. **Modified Files**
   - `Dashboard/dashboard.php` - Added role-based navigation
   - `Dashboard/content/users/users_management.php` - Added Activity Log tab
   - `Dashboard/content/addCase/save_case.php` - Added logging
   - `Dashboard/content/allCases/update_case.php` - Added logging
   - `Dashboard/content/allCases/all_cases.php` - Added print logging
   - `Dashboard/content/users/add_user.php` - Added logging
   - `Dashboard/content/users/update_user.php` - Added logging
   - `Dashboard/content/users/delete_user.php` - Added logging
   - `Dashboard/content/users/change_password.php` - Added logging

## Usage Guide

### For Administrators

#### Accessing Activity Logs

1. Log in with admin credentials
2. Click **Users** in the sidebar
3. Click the **Activity Log** tab
4. View real-time system activities

#### Filtering Activities

1. **By Type**: Select from dropdown (Case Added, Edited, Printed, etc.)
2. **By User**: Select user from dropdown
3. **By Date**: Pick a specific date
4. **Reset**: Click "Reset" to clear all filters

#### Reading Activity Entries

Each activity shows:

- **Icon & Badge**: Color-coded activity type

  - Green: Added actions
  - Yellow: Edit actions
  - Blue: Print actions
  - Red: Delete actions
  - Purple: Password changes

- **User Name**: Who performed the action
- **Time**: How long ago + exact timestamp
- **Description**: What was done
- **Case Number**: Which case (if applicable)
- **IP Address**: From where the action was performed

#### Statistics Dashboard

At the top, view quick stats:

- **Total Activities**: All tracked actions
- **Cases Added**: New cases created
- **Cases Edited**: Cases modified
- **Cases Printed**: Print activities

### For Regular Users

- Regular users do NOT see the Activity Log tab
- Regular users do NOT see the Admin section in sidebar
- They can only access "Change Password"

## Security Features

1. **Access Control**

   - Only users with role='admin' can view activity logs
   - Non-admin users get "Access Denied" message
   - Session validation on all endpoints

2. **IP Address Tracking**

   - Captures real IP address (handles proxies)
   - Supports both IPv4 and IPv6

3. **Comprehensive Logging**

   - All critical actions are logged
   - Cannot be deleted by users
   - Foreign key constraints preserve data integrity

4. **Privacy Protection**
   - Passwords are never logged
   - Only indicates "Password Changed" without details

## Technical Implementation

### Activity Logger Helper Functions

```php
// Log any activity
logActivity($conn, 'case_added', 'Description', $case_id, $case_number);

// Get client IP (handles proxies)
$ip = getClientIP();

// Get browser info
$browser = getBrowserInfo($user_agent);
```

### Automatic Logging

Activities are automatically logged when:

- A case is added (save_case.php)
- A case is edited (update_case.php)
- A case is printed (via log_print.php)
- A user is added (add_user.php)
- A user is edited (update_user.php)
- A user is deleted (delete_user.php)
- A password is changed (change_password.php)

## Installation

✅ **Already Completed:**

1. Database table `activity_logs` created
2. All code files deployed
3. Activity logging integrated into all relevant endpoints
4. Role-based access control implemented
5. UI components added

## Testing the System

### Test as Admin:

1. Log in as admin
2. Go to Users → Activity Log
3. You should see activities logged
4. Try filtering by different criteria
5. Add a new case → Check if it appears in activity log
6. Edit a case → Check if edit is logged
7. Print a case → Check if print is logged

### Test as Regular User:

1. Create a regular user (role='user')
2. Log in with that account
3. Verify:
   - ✅ Can access Change Password
   - ❌ Cannot see Admin section in sidebar
   - ❌ Cannot access Activity Log
   - ❌ Cannot access Manage Users

## Troubleshooting

### Activity Log Not Showing

1. Verify you're logged in as admin:

   ```sql
   SELECT id, full_name, role FROM users WHERE email = 'your@email.com';
   ```

2. Check if activities exist:

   ```sql
   SELECT COUNT(*) FROM activity_logs;
   ```

3. Clear browser cache and refresh

### Activities Not Being Logged

1. Check if activity_logger.php is included:

   ```php
   require_once '../../../config/activity_logger.php';
   ```

2. Verify database permissions

3. Check PHP error logs for any issues

### "Access Denied" for Admin

1. Verify role in database:

   ```sql
   UPDATE users SET role = 'admin' WHERE id = YOUR_ID;
   ```

2. Log out and log back in to refresh session

## Benefits

1. **Accountability**: Track who did what and when
2. **Security**: Monitor suspicious activities
3. **Audit Trail**: Complete history of all changes
4. **Troubleshooting**: Identify when and by whom changes were made
5. **Compliance**: Meet audit and compliance requirements
6. **Analytics**: Understand system usage patterns

## Color Coding Guide

- 🟢 **Green** (#28a745): Added actions (Case/User Added)
- 🟡 **Yellow** (#ffc107): Edit actions (Case/User Edited)
- 🔵 **Blue** (#17a2b8): Print actions (Case Printed)
- 🔴 **Red** (#dc3545): Delete actions (Case/User Deleted)
- 🟣 **Purple** (#6f42c1): Password Changes

## Future Enhancements (Optional)

- Export activity logs to CSV/PDF
- Email notifications for critical activities
- Activity retention policies
- Advanced search and analytics
- Activity charts and graphs
- Login/Logout tracking
- Failed login attempts tracking

---

The system is now fully operational and ready to track all system activities!
