# Quick Start Guide - CentralizedPisoNet

## 🚀 Get Started in 5 Minutes

### Step 1: Install Dependencies (2 minutes)

**Windows (XAMPP):**
```bash
cd c:\xampp\htdocs\CentralizedPisoNet
install.bat
```

**Linux/Mac:**
```bash
cd /path/to/CentralizedPisoNet
chmod +x install.sh
sudo ./install.sh
```

### Step 2: Configure Database (1 minute)

Edit `.env` file:
```env
DB_DATABASE=centralizedpisonet
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create database:
```sql
CREATE DATABASE centralizedpisonet;
```

### Step 3: Run Migrations (1 minute)

```bash
php artisan migrate --seed
```

This creates all tables and sample data including:
- Admin user (admin@pisonet.com / password)
- 5 sample machines
- Sample transactions and earnings

### Step 4: Start Server (30 seconds)

```bash
php artisan serve
```

### Step 5: Login (30 seconds)

1. Open browser: http://localhost:8000
2. Click "Login"
3. Email: `admin@pisonet.com`
4. Password: `password`

## ✅ You're Ready!

### What's Next?

1. **Change Password**
   - Click gear icon → Edit Profile
   - Update password

2. **Add Your PC Units**
   - Go to PC Units
   - Click "Add New PC Unit"
   - Enter PC details

3. **Configure Machines**
   - Go to Reports → Machine Usage
   - Verify machines are listed

4. **Test API** (Optional)
   ```bash
   curl -H "X-API-Token: pisonet_secure_token_2024_change_in_production" \
        http://localhost:8000/api/system/status
   ```

## 📱 Quick Feature Tour

### Dashboard
- View real-time earnings
- Monitor active machines
- See recent activities

### PC Units
- Add/Edit/Delete PC stations
- Toggle active status
- View machine details

### Sessions
- Start new sessions
- Monitor active sessions
- End or extend sessions

### Reports
- Generate earnings reports
- Export to PDF/Excel
- View machine usage stats

## 🔧 Common Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create storage link
php artisan storage:link

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Run tests
php artisan test
```

## 🆘 Quick Troubleshooting

**Can't login?**
- Reset password: `php artisan tinker` → `User::first()->update(['password' => bcrypt('newpassword')]);`

**Database error?**
- Check `.env` credentials
- Verify MySQL is running
- Run: `php artisan migrate:fresh --seed`

**Permission error?**
```bash
# Windows
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T

# Linux/Mac
chmod -R 775 storage bootstrap/cache
```

**API not working?**
- Check API token in `.env`
- Verify: `php artisan config:cache`

## 📚 Full Documentation

- [User Manual](USER_MANUAL.md) - Complete user guide
- [API Documentation](API_DOCUMENTATION.md) - API reference
- [Deployment Guide](DEPLOYMENT_GUIDE.md) - Production setup

## 💡 Tips

1. **Backup regularly** - Use the backup script in Deployment Guide
2. **Monitor logs** - Check `storage/logs/laravel.log`
3. **Update regularly** - Keep Laravel and dependencies updated
4. **Secure API token** - Change default token in production
5. **Use HTTPS** - Always use SSL in production

## 🎯 Next Steps

### For Development
1. Read [User Manual](USER_MANUAL.md)
2. Explore dashboard features
3. Test API endpoints
4. Customize as needed

### For Production
1. Follow [Deployment Guide](DEPLOYMENT_GUIDE.md)
2. Configure SSL certificate
3. Set up backups
4. Configure monitoring

## 📞 Need Help?

- **Documentation:** Check the docs folder
- **Email:** support@yourdomain.com
- **Logs:** `storage/logs/laravel.log`

---

**Happy Managing! 🎉**

Your PisoNet business is now centralized and automated!
