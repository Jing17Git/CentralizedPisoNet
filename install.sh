#!/bin/bash

# CentralizedPisoNet Installation Script
# This script automates the installation process

echo "========================================="
echo "CentralizedPisoNet Installation Script"
echo "========================================="
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "Please run as root (use sudo)"
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;" 2>/dev/null)
if [ -z "$PHP_VERSION" ]; then
    echo "❌ PHP is not installed"
    exit 1
fi

echo "✓ PHP Version: $PHP_VERSION"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed"
    exit 1
fi
echo "✓ Composer is installed"

# Check Node.js
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed"
    exit 1
fi
echo "✓ Node.js Version: $(node -v)"

echo ""
echo "Starting installation..."
echo ""

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev
if [ $? -ne 0 ]; then
    echo "❌ Failed to install PHP dependencies"
    exit 1
fi
echo "✓ PHP dependencies installed"

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo "❌ Failed to install Node dependencies"
    exit 1
fi
echo "✓ Node dependencies installed"

# Build frontend assets
echo "🔨 Building frontend assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "❌ Failed to build frontend assets"
    exit 1
fi
echo "✓ Frontend assets built"

# Copy .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✓ .env file created"
else
    echo "✓ .env file already exists"
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force
echo "✓ Application key generated"

# Set permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data .
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
echo "✓ Permissions set"

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link
echo "✓ Storage symlink created"

echo ""
echo "========================================="
echo "Installation Complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Configure your .env file with database credentials"
echo "2. Run: php artisan migrate --seed"
echo "3. Configure your web server (Apache/Nginx)"
echo "4. Access your application"
echo ""
echo "For detailed instructions, see DEPLOYMENT_GUIDE.md"
echo ""
