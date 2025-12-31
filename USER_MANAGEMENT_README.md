# User Management System - Setup Instructions

## Overview

The user management system has been created with two separate roles:

- **Admin**: Can manage users (add, edit, delete) and change their own password
- **User**: Can only change their own password

## Installation Steps

### Step 1: Run the Database Migration

Execute the SQL migration to add the role column to the users table:

```sql
-- Run this file in your MySQL/MariaDB database
c:\xampp\htdocs\police\add_user_roles.sql
```

This will:

- Add a `role` column to the users table
- Set the first user (ID = 1) as admin
- Add an index for better performance

**Alternative**: If you want to run it via command line:

```bash
mysql -u root -p police_cms < c:\xampp\htdocs\police\add_user_roles.sql
```

### Step 2: Verify Database Changes

Check that the role column was added successfully:

```sql
USE police_cms;
DESCRIBE users;
SELECT id, full_name, email, role FROM users;
```

### Step 3: Clear Browser Cache and Login

1. Clear your browser cache or do a hard refresh (Ctrl + Shift + R)
2. Log out if you're currently logged in
3. Log back in with your admin credentials

## Features

### For All Users

- **Change Password**: All users can change their own password securely
  - Requires current password verification
  - Minimum 6 characters for new password
  - Password confirmation

### For Admin Users Only

- **View All Users**: See a table of all system users with their details
- **Add New User**: Create new user accounts with:

  - Full Name
  - Email
  - Position
  - Rank Title
  - Password
  - Role (Admin or User)
  - Status is automatically set to Active

- **Edit Users**: Update user information including:

  - Personal details
  - Role assignment
  - Account status (Active/Inactive)

- **Delete Users**: Remove users from the system
  - Cannot delete your own account
  - Confirmation required before deletion

## File Structure

```
Dashboard/
└── content/
    └── users/
        ├── users_management.php   (Main interface)
        ├── change_password.php    (Password change endpoint)
        ├── get_users.php          (Get all users - admin only)
        ├── get_user.php           (Get single user - admin only)
        ├── add_user.php           (Add new user - admin only)
        ├── update_user.php        (Update user - admin only)
        ├── delete_user.php        (Delete user - admin only)
        └── css/
            └── users.css          (User management styles)
```

## Security Features

1. **Session-Based Authentication**: All endpoints check for valid user session
2. **Role-Based Access Control**: Admin-only features are protected
3. **Password Hashing**: All passwords are hashed using PHP's password_hash()
4. **Input Validation**: All inputs are validated and sanitized
5. **Email Uniqueness**: System prevents duplicate email addresses
6. **Self-Protection**: Users cannot delete or deactivate their own accounts
7. **Current Password Verification**: Password changes require current password

## Usage

### Accessing User Management

1. Log in to the dashboard
2. Click on "Users" in the sidebar
3. You'll see two tabs:
   - **Change Password**: Available to all users
   - **Manage Users**: Only visible to admin users

### Changing Your Password

1. Go to Users → Change Password tab
2. Enter your current password
3. Enter and confirm your new password (min 6 characters)
4. Click "Update Password"

### Adding a New User (Admin Only)

1. Go to Users → Manage Users tab
2. Click "Add New User" button
3. Fill in the user details
4. Select role (Admin or User)
5. Click "Add User"

### Editing a User (Admin Only)

1. Go to Users → Manage Users tab
2. Click the edit icon next to the user
3. Update the information
4. Click "Update User"

### Deleting a User (Admin Only)

1. Go to Users → Manage Users tab
2. Click the delete icon next to the user
3. Confirm the deletion

## Default Credentials

After running the migration, your first user will be set as admin:

- Email: admin@police.gov
- Password: (your existing password)
- Role: Admin

## Troubleshooting

### "Unauthorized" Error

- Make sure you're logged in
- Check that your session is active
- Try logging out and logging back in

### Admin Features Not Visible

- Verify your user role in the database:
  ```sql
  SELECT id, full_name, role FROM users WHERE email = 'your@email.com';
  ```
- If role is not 'admin', update it:
  ```sql
  UPDATE users SET role = 'admin' WHERE id = YOUR_USER_ID;
  ```

### Cannot Change Password

- Ensure you're entering the correct current password
- Check that new password is at least 6 characters
- Verify passwords match

## Notes

- At least one admin user should always exist in the system
- Inactive users cannot log in but their data remains in the system
- All user actions are logged with timestamps
