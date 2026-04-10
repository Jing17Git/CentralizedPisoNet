@echo off
REM CentralizedPisoNet Installation Script for Windows
REM This script automates the installation process on Windows

echo =========================================
echo CentralizedPisoNet Installation Script
echo =========================================
echo.

REM Check if Composer exists
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is not installed
    echo Please install Composer from https://getcomposer.org/
    pause
    exit /b 1
)
echo [OK] Composer is installed

REM Check if Node.js exists
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Node.js is not installed
    echo Please install Node.js from https://nodejs.org/
    pause
    exit /b 1
)
echo [OK] Node.js is installed

echo.
echo Starting installation...
echo.

REM Install PHP dependencies
echo Installing PHP dependencies...
call composer install --optimize-autoloader --no-dev
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to install PHP dependencies
    pause
    exit /b 1
)
echo [OK] PHP dependencies installed

REM Install Node dependencies
echo Installing Node dependencies...
call npm install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to install Node dependencies
    pause
    exit /b 1
)
echo [OK] Node dependencies installed

REM Build frontend assets
echo Building frontend assets...
call npm run build
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Failed to build frontend assets
    pause
    exit /b 1
)
echo [OK] Frontend assets built

REM Copy .env file if it doesn't exist
if not exist .env (
    echo Creating .env file...
    copy .env.example .env
    echo [OK] .env file created
) else (
    echo [OK] .env file already exists
)

REM Generate application key
echo Generating application key...
php artisan key:generate --force
echo [OK] Application key generated

REM Create storage symlink
echo Creating storage symlink...
php artisan storage:link
echo [OK] Storage symlink created

echo.
echo =========================================
echo Installation Complete!
echo =========================================
echo.
echo Next steps:
echo 1. Configure your .env file with database credentials
echo 2. Run: php artisan migrate --seed
echo 3. Start the server: php artisan serve
echo 4. Access your application at http://localhost:8000
echo.
echo For detailed instructions, see DEPLOYMENT_GUIDE.md
echo.
pause
