@echo off
REM Automatic Database Backup Script for Police CMS
REM This script runs the PHP auto backup script

cd /d "c:\xampp\htdocs\police\Dashboard\content\users"
"C:\xampp\php\php.exe" auto_backup.php

REM Exit with the PHP script's exit code
exit /b %ERRORLEVEL%
