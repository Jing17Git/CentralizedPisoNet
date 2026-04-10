# CentralizedPisoNet Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-5.7+-orange" alt="MySQL 5.7+">
  <img src="https://img.shields.io/badge/License-Proprietary-green" alt="License">
</p>

## 📋 Overview

CentralizedPisoNet is a comprehensive management system for PisoNet (Internet Café) businesses. It provides real-time monitoring, session management, earnings tracking, and detailed reporting capabilities.

## ✨ Features

### Core Features
- 🎯 **Real-time Dashboard** - Monitor all machines and earnings in real-time
- 💻 **PC Units Management** - Add, edit, and manage all PC stations
- ⏱️ **Session Monitoring** - Track active sessions with time remaining
- 💰 **Earnings Tracking** - Comprehensive earnings and revenue tracking
- 📊 **Advanced Reports** - Generate detailed PDF/Excel reports
- 🪙 **Coin Insert Tracking** - Monitor all coin insertions and payments
- 👤 **Profile Management** - User profile with avatar upload
- 🔐 **Role-Based Access** - Admin and user role management
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile

### API Features
- 🔌 **RESTful API** - Complete API for machine integration
- 🔒 **Token Authentication** - Secure API access with tokens
- 💓 **Machine Heartbeat** - Keep-alive monitoring for machines
- 📡 **Real-time Updates** - Live session and earnings updates

### Reporting Features
- 📈 **Earnings Reports** - Daily, weekly, monthly earnings
- 🖥️ **Machine Usage Reports** - Track machine performance
- 📝 **Session History** - Complete session logs
- 📄 **PDF Export** - Professional PDF reports
- 📊 **Excel Export** - Spreadsheet exports for analysis

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer 2.x
- Node.js 18+ and NPM

### Installation

#### Windows (XAMPP)
```bash
# Navigate to htdocs
cd c:\xampp\htdocs\CentralizedPisoNet

# Run installation script
install.bat

# Configure database in .env
# Then run migrations
php artisan migrate --seed

# Start server
php artisan serve
```

#### Linux/Mac
```bash
# Navigate to project directory
cd /var/www/centralizedpisonet

# Run installation script
chmod +x install.sh
sudo ./install.sh

# Configure database in .env
# Then run migrations
php artisan migrate --seed

# Start server
php artisan serve
```

### Default Login
- **Email:** admin@pisonet.com
- **Password:** password

**⚠️ Change password immediately after first login!**

## 📚 Documentation

- **[User Manual](USER_MANUAL.md)** - Complete guide for system users
- **[API Documentation](API_DOCUMENTATION.md)** - API endpoints and usage
- **[Deployment Guide](DEPLOYMENT_GUIDE.md)** - Production deployment instructions
- **[Profile Documentation](PROFILE_DOCUMENTATION.md)** - Profile management guide
- **[Reports Module](REPORTS_MODULE.md)** - Reports system documentation

## 🏗️ System Architecture

### Technology Stack
- **Backend:** Laravel 12, PHP 8.2+
- **Database:** MySQL/MariaDB
- **Frontend:** Blade Templates, Custom CSS
- **Charts:** Chart.js
- **PDF Generation:** DomPDF
- **Excel Export:** Maatwebsite Excel

### Database Tables
- `users` - System users and administrators
- `machines` - PisoNet machine/PC information
- `pc_units` - PC unit configurations
- `pc_sessions` - Active and historical sessions
- `coin_transactions` - Coin insertion records
- `coin_inserts` - Detailed coin tracking
- `transactions` - Transaction records
- `earnings` - Earnings and revenue data

## 🔧 Configuration

### Environment Variables
```env
APP_NAME=CentralizedPisoNet
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_API_TOKEN=your_secure_token_here

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=centralizedpisonet
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### API Configuration
Set your API token in `.env`:
```env
APP_API_TOKEN=your_secure_random_token
```

Use in API requests:
```bash
curl -H "X-API-Token: your_token" http://localhost:8000/api/machines
```

## 📊 Screenshots

### Dashboard
Real-time monitoring of all machines, earnings, and activities.

### PC Units Management
Add, edit, and manage all PC stations with status indicators.

### Reports
Generate comprehensive reports with PDF/Excel export.

## 🧪 Testing

Run the test suite:
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter AuthenticationTest

# Run with coverage
php artisan test --coverage
```

## 🔒 Security

- ✅ Role-based access control (RBAC)
- ✅ API token authentication
- ✅ CSRF protection
- ✅ Password hashing (bcrypt)
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ Secure session management

### Security Best Practices
1. Change default admin password
2. Use strong API tokens
3. Enable HTTPS in production
4. Regular security updates
5. Monitor access logs

## 📈 Performance

### Optimization Features
- Database query optimization
- Route caching
- View caching
- Config caching
- Lazy loading relationships
- Efficient pagination

### Caching Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔄 Updates

### Updating the System
```bash
# Backup database first!
php artisan down
git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

## 🐛 Troubleshooting

### Common Issues

**Storage Permission Error**
```bash
chmod -R 775 storage bootstrap/cache
```

**Database Connection Error**
- Check `.env` database credentials
- Verify MySQL service is running
- Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

**API Not Working**
- Verify API token in `.env`
- Check API routes: `php artisan route:list`
- Clear cache: `php artisan config:clear`

## 📞 Support

### Getting Help
- **Email:** support@yourdomain.com
- **Documentation:** See docs folder
- **Issues:** Report bugs via email

### System Requirements
- PHP 8.2+
- MySQL 5.7+ / MariaDB 10.3+
- 2GB RAM minimum
- 10GB disk space
- Modern web browser

## 🤝 Contributing

This is proprietary software. Contributions are managed internally.

## 📄 License

Proprietary License - All rights reserved.
Unauthorized copying, distribution, or modification is prohibited.

## 🎯 Roadmap

### Planned Features
- [ ] Real-time WebSocket updates
- [ ] Mobile app for monitoring
- [ ] Email notifications
- [ ] SMS alerts
- [ ] Advanced analytics
- [ ] Multi-branch support
- [ ] Customer loyalty program
- [ ] Automated backups

## 👥 Credits

### Development Team
- System Architecture & Backend Development
- Frontend Design & Implementation
- Database Design & Optimization
- API Development & Integration
- Testing & Quality Assurance

### Technologies Used
- Laravel Framework
- Chart.js for visualizations
- DomPDF for PDF generation
- Maatwebsite Excel for exports

## 📝 Changelog

### Version 1.0.0 (Current)
- ✅ Initial release
- ✅ Dashboard with real-time monitoring
- ✅ PC Units management (CRUD)
- ✅ Session tracking and monitoring
- ✅ Earnings and transaction tracking
- ✅ Comprehensive reporting system
- ✅ PDF/Excel export functionality
- ✅ Profile management with avatar
- ✅ RESTful API for machines
- ✅ Role-based access control
- ✅ Complete documentation

---

**Built with ❤️ for PisoNet Business Owners**

For more information, see the [User Manual](USER_MANUAL.md) or [Deployment Guide](DEPLOYMENT_GUIDE.md).
