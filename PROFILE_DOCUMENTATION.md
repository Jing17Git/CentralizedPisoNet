# Administrator Profile Page - Documentation

## Overview
Complete Administrator Profile management system for the Centralized PisoNet Management System.

## Features Implemented

### 1. Profile View Page (`/admin/profile`)
- Display current administrator information
- Profile picture with avatar placeholder
- Administrator name, role, and email
- Detailed profile information card
- Edit Profile button

### 2. Profile Edit Page (`/admin/profile/edit`)
- Edit all profile information
- Upload and preview profile picture
- Update personal details:
  - Full Name
  - Username
  - Email Address
  - Phone Number
  - Address
- Optional password change
- Image preview on upload
- Form validation with error messages

### 3. Database Fields
All fields are already in the `users` table:
- `id` - Primary key
- `name` - Full name
- `username` - Username (nullable)
- `email` - Email address
- `phone` - Phone number (nullable)
- `address` - Address (nullable)
- `profile_picture` - Profile picture path (nullable)
- `password` - Hashed password
- `role` - User role (default: 'admin')

### 4. Routes
```php
GET  /admin/profile         → View profile
GET  /admin/profile/edit    → Edit profile form
PUT  /admin/profile/update  → Update profile
```

### 5. Controller Methods
**ProfileController:**
- `show()` - Display admin profile
- `edit()` - Show edit form
- `update()` - Update profile information

### 6. Features
✅ Profile picture upload with preview
✅ Image validation (JPG, PNG, GIF, max 2MB)
✅ Optional password change (only updates if filled)
✅ Success message after update
✅ Form validation with error messages
✅ Responsive design matching PisoNet theme
✅ Profile link in sidebar (gear icon)
✅ Dynamic user name in sidebar

## Usage

### Accessing Profile
1. Click the gear icon (⚙️) in the sidebar footer
2. Or navigate to `/admin/profile`

### Editing Profile
1. Click "Edit Profile" button on profile page
2. Update desired fields
3. Upload new profile picture (optional)
4. Change password (optional - leave blank to keep current)
5. Click "Update Profile"

### Profile Picture
- Supported formats: JPG, PNG, GIF
- Maximum size: 2MB
- Stored in: `storage/app/public/profile_pictures/`
- Accessible via: `public/storage/profile_pictures/`

## File Structure
```
app/
├── Http/Controllers/
│   └── ProfileController.php
├── Models/
│   └── User.php (updated fillable fields)

resources/views/
└── admin/profile/
    ├── show.blade.php (view profile)
    └── edit.blade.php (edit profile)

routes/
└── web.php (profile routes added)

storage/app/public/
└── profile_pictures/ (auto-created on upload)

public/
└── storage/ (symbolic link)
```

## Security
- Authentication required (middleware: 'auth')
- Password hashing with bcrypt
- Email uniqueness validation
- Username uniqueness validation
- CSRF protection on forms
- Image validation and sanitization

## Styling
- Matches existing PisoNet design system
- Uses CSS variables from admin layout
- Neon blue theme with glassmorphism
- Responsive and mobile-friendly
- Smooth transitions and hover effects

## Notes
- Password field is optional - leave blank to keep current password
- Profile picture is optional - shows initial letter if not uploaded
- All fields except name and email are optional
- Success messages appear after successful update
- Validation errors display below each field
