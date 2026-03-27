# Deploy ke Hostinger - Panduan Lengkap

Aplikasi attendance-app siap untuk deploy ke Hostinger shared hosting.

## 1. Persiapan di Hostinger

### 1.1 Setup Database MySQL
1. Login ke **cPanel Hostinger**
2. Cari **MySQL Databases** atau **MariaDB**
3. Buat database baru:
   - Database Name: `username_namamodule` (contoh: `absensiku_attendance`)
   - Catat nama ini
4. Buat user database:
   - Username: `username_user` (contoh: `absensiku_app`)
   - Password: gunakan password yang kuat
5. Tambahkan user ke database dengan privilege ALL

### 1.2 Setup Domain/Subdomain
1. Di cPanel, masuk ke **Addon Domains** atau **Subdomains**
2. Arahkan domain ke folder `public_html/attendance-app/public`
3. Catat URL lengkapnya (misalnya: `https://attendance.yourdomain.com`)

### 1.3 SSH Access (opsional tapi recommended)
1. Di cPanel → SSH Access
2. Generate public key atau pakai password
3. Catat credentials untuk terminal

## 2. Deploy Aplikasi

### Opsi A: Deploy via Git (Recommended)

#### 2A.1 Persiapan di Local
```bash
cd C:\xampp\htdocs\attendance-app

# Pastikan Git sudah initialized
git status

# Kalau belum, init duluan
git init
git add .
git commit -m "Initial commit untuk Hostinger deploy"
```

#### 2A.2 Setup di Hostinger Terminal
```bash
# SSH login ke Hostinger
ssh user@your-hostinger-domain.com

# Masuk ke publik_html
cd public_html

# Clone repository (ganti URL sesuai repo Anda)
git clone [your-git-repo-url] attendance-app
cd attendance-app

# Install dependencies
composer install --optimize-autoloader --no-dev

# Copy file environment template
cp .env.hostinger .env

# Update .env dengan database credential Hostinger Anda
nano .env
# Edit:
# - DB_HOST=localhost (atau hostname dari Hostinger)
# - DB_DATABASE=username_attendance
# - DB_USERNAME=username_user
# - DB_PASSWORD=[catat dari cPanel]
# - APP_URL=https://attendance.yourdomain.com

# Generate app key (jika belum ada)
php artisan key:generate

# Create storage symlink
php artisan storage:link

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/app/public

# Set ownership (jika perlu)
chown -R nobody:nobody storage bootstrap/cache
```

### Opsi B: Deploy via FTP

#### 2B.1 Siapkan file lokal
```powershell
# Di Windows PowerShell/Command Prompt
cd C:\xampp\htdocs\attendance-app

# Install dependencies locally
composer install --optimize-autoloader --no-dev

# Generate app key kalau belum
php artisan key:generate
```

#### 2B.2 Upload via FTP
1. Gunakan FTP client (FileZilla, WinSCP, dll)
2. Connect ke Hostinger FTP:
   - Host: `ftp.yourdomain.com` atau datanya dari Hostinger
   - Username & Password: dari Account Settings
3. Upload seluruh folder ke `public_html/attendance-app`
   - **Perhatian**: Upload `.env` terakhir dengan credential yang benar
   - Jangan upload folder `node_modules`, `tests`, `.git`
4. Edit `.env` langsung di server via FTP atau cPanel File Manager:
   ```
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=username_attendance
   DB_USERNAME=username_user
   DB_PASSWORD=[password dari cPanel]
   APP_URL=https://attendance.yourdomain.com
   APP_ENV=production
   APP_DEBUG=false
   ```

#### 2B.3 Jalankan setup di Hostinger File Manager atau SSH
```bash
# Kalau ada SSH, login dan jalankan:
cd public_html/attendance-app

# Jalankan migrations
php artisan migrate --force

# Create storage symlink
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 3. Konfigurasi File & Permission

### .htaccess sudah benar
File `public/.htaccess` sudah di-generate dengan benar oleh Laravel untuk routing.

### Permission di Linux
```bash
# Login via SSH ke Hostinger
cd public_html/attendance-app

# Set read permission ke semua dir
find . -type d -exec chmod 755 {} \;

# Set writable untuk storage dan bootstrap
chmod -R 775 storage bootstrap/cache

# Kalau perlu write ke upload files
chmod -R 775 storage/app/public
```

## 4. Database Initial Setup

Setelah `php artisan migrate` sukses, pilih salah satu:

### Option 1: Seed data test
```bash
php artisan db:seed --class=UserSeeder
```

### Option 2: Setup user manual di App
Buka aplikasi Anda, register user baru langsung.

## 5. Verifikasi Deployment

1. Buka browser: `https://attendance.yourdomain.com`
2. Cek halaman loading normal (bukan Error 500)
3. Login dengan user yang sudah terdaftar
4. Test attendance check-in dengan lokasi GPS

### Kalau Error 500:
```bash
# SSH login ke Hostinger
cd public_html/attendance-app

# Cek log error
tail -f storage/logs/laravel.log

# Pastikan permission storage & bootstrap writable
chmod -R 777 storage bootstrap/cache

# Clear cache dan try regenerate
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate
php artisan config:cache
php artisan route:cache
```

## 6. Update Kode Kemudian

### Jika pakai Git:
```bash
cd public_html/attendance-app

# Pull perubahan
git pull origin main

# Install update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Jika pakai FTP:
1. Download file yang berubah dari Git
2. Upload ulang via FTP
3. Jalankan ulang `php artisan migrate` (aman, migrasi idempotent)
4. Rebuild cache via SSH atau clear manual

## 7. Backup Rutin

Hostinger biasanya sudah backup otomatis, tapi untuk aman:

```bash
# Backup database dari cPanel → Backup Wizard
# atau via SSH:
mysqldump -u [username] -p [database_name] > backup-$(date +%Y%m%d).sql

# Backup file aplikasi
tar czf attendance-app-backup-$(date +%Y%m%d).tar.gz ~/public_html/attendance-app
```

## Troubleshooting

| Error | Solusi |
|-------|--------|
| 500 Internal Server | Cek `storage/logs/laravel.log`, pastikan permission correct |
| Database connection refused | Verify DB credentials di `.env`, cek MySQL running di cPanel |
| Composer not found | Contact Hostinger support, minta enable SSH & Composer |
| Missing functions/extensions | Cek PHP version di cPanel, aktifkan extensions yang diperlukan |
| Upload file error | Set permission `storage/app/public` ke 775-777 |

## Support

Dukungan Hostinger: support.hostinger.com atau live chat di dashboard.

Good luck! 🚀
