# PROFILE_NEWBIEK - Personal Website & Digital Product Store

## 🚀 Overview
Website cá nhân của **Nguyễn Văn Kiệt** - Digital Marketing Specialist tại Đà Nẵng. Bao gồm:
- 📝 Blog chia sẻ kiến thức Digital Marketing
- 🛒 Shop sản phẩm số (tài khoản Premium, Ebook, Template)
- 👤 Trang giới thiệu bản thân (CV online)
- 📧 Hệ thống liên hệ

## 📋 Requirements
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache/Nginx with URL rewriting

## 🔧 Installation

### 1. Clone & Setup
```bash
git clone <repository-url>
cd PROFILE_NEWBIEK
```

### 2. Database Setup
```sql
-- Import schema
mysql -u username -p database_name < database/schema.sql
```

### 3. Configuration
Copy and edit config file:
```bash
cp config/config.example.php config/config.php
```

Edit `config/config.php` with your settings:
- Database credentials
- Site URL
- Payment gateway (SePay) settings
- Social media links

### 4. Run Development Server
```bash
php -S localhost:8000 router.php
```

## 📁 Project Structure
```
PROFILE_NEWBIEK/
├── admin/           # Admin panel pages
├── api/             # API endpoints (cart, checkout, contact...)
├── assets/          # CSS, JS, images
├── config/          # Configuration files
├── database/        # SQL schema
├── includes/        # Header, footer, functions
├── pages/           # Public pages
├── uploads/         # User uploaded files
├── index.php        # Main router
├── router.php       # PHP dev server router
├── robots.txt       # SEO robots file
└── README.md        # This file
```

## 🌐 SEO Features
- Dynamic sitemap (`/sitemap.xml`)
- Meta tags optimization
- Open Graph support
- Structured data (JSON-LD)
- Mobile-responsive design

## 🔐 Admin Access
Default admin login:
- Email: `admin@newbiek.id.vn`
- Password: `password` (change after first login!)

## 📄 License
Private project - All rights reserved.

## 👤 Author
**Nguyễn Văn Kiệt**
- Website: [newbiek.id.vn](https://newbiek.id.vn)
- Email: kiet@newbiek.id.vn
