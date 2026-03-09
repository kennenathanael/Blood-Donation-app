# 🩸 BloodLife — Hospital Blood Donation Campaign App

A full-featured blood donation campaign management system built with **Laravel 12**, **Blade**, **Tailwind CSS v3**, and **MySQL**.

## ✨ Features

### 🏥 Admin Panel
- Dashboard with stats (total campaigns, donors, donations, pending approvals)
- Create, edit, delete blood donation campaigns
- Accept / reject donor registrations (with automatic email notification)
- Mark donations as completed
- Send bulk reminder emails to accepted donors
- Export donor list as CSV
- Manage donor eligibility
- View full donor profiles and history

### 🩸 Donor Portal
- Register & login as a donor
- Browse active campaigns with search & filter
- Register for a campaign (with blood group, health notes, emergency contact)
- Cancel pending registrations
- View registration status (Pending / Accepted / Rejected / Donated)
- Donation history
- In-app notifications
- Edit profile (photo, blood group, medical info)
- Change password

### 🌐 Public Pages
- Home page with active campaigns preview
- Full campaign listing with search & filters
- Campaign detail page

---

## 🚀 Installation

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+

### Step 1 — Clone the repository
```bash
git clone https://github.com/YOUR_USERNAME/blood-donation-app.git
cd blood-donation-app
```

### Step 2 — Install dependencies
```bash
composer install
npm install
```

### Step 3 — Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```env
DB_DATABASE=blood_donation_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4 — Create the database
In MySQL / phpMyAdmin:
```sql
CREATE DATABASE blood_donation_db;
```

### Step 5 — Run migrations & seed
```bash
php artisan migrate --seed
```

This creates all tables and seeds:
- 8 blood groups (A+, A-, B+, B-, AB+, AB-, O+, O-)
- Admin account
- Demo donor account

### Step 6 — Link storage
```bash
php artisan storage:link
```

### Step 7 — Build assets & run
```bash
npm run build
php artisan serve
```

Open **http://localhost:8000** 🎉

---

## 🔐 Default Accounts

| Role  | Email | Password |
|-------|-------|----------|
| Admin | admin@hospital.com | Admin@1234 |
| Donor | donor@example.com | Donor@1234 |

> ⚠️ Change these passwords in production!

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Login, Register
│   │   ├── Admin/          # Admin dashboard, campaigns, donors
│   │   ├── CampaignController.php
│   │   ├── DonorController.php
│   │   └── RegistrationController.php
│   └── Middleware/
│       └── IsAdmin.php
├── Models/
│   ├── User.php
│   ├── Campaign.php
│   ├── CampaignRegistration.php
│   └── BloodGroup.php
└── Notifications/
    ├── RegistrationStatusNotification.php
    └── DonationReminderNotification.php

database/
├── migrations/             # 5 migration files
└── seeders/                # BloodGroup + Admin seeders

resources/views/
├── layouts/                # app.blade.php, admin.blade.php
├── auth/                   # login, register
├── campaigns/              # index, show
├── donor/                  # dashboard, profile, registrations, history, notifications
└── admin/
    ├── dashboard.blade.php
    ├── campaigns/          # index, create, show, edit
    └── donors/             # index, show
```

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12, PHP 8.2
- **Frontend:** Blade Templates, Tailwind CSS v3, Alpine.js
- **Database:** MySQL 8
- **Auth:** Laravel Session Auth
- **Notifications:** Laravel Mail + Database notifications
- **File Storage:** Laravel Storage (local/S3 ready)

---

## 🌐 Deployment (Shared Hosting / cPanel)

1. Upload files to `public_html/bloodlife/` (or subdomain root)
2. Point document root to the `public/` folder
3. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
4. Run `php artisan config:cache && php artisan route:cache`
5. Set up a cron job for scheduled tasks:
   ```
   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
   ```

---

## 📧 Email Configuration

For **local testing** — emails are written to `storage/logs/laravel.log`:
```env
MAIL_MAILER=log
```

For **Mailtrap** (free email testing):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## 📄 License

MIT License — free to use, modify, and distribute.

---

Built with ❤️ to save lives. 🩸
