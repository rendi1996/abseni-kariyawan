# Deploy ke Vercel

Project ini siap deploy ke Vercel. Ikuti langkah berikut:

## 1. Push ke GitHub Dulu

```powershell
cd C:\xampp\htdocs\attendance-app

git init
git add .
git commit -m "Ready for Vercel deploy"
git remote add origin https://github.com/yourusername/attendance-app.git
git push -u origin main
```

## 2. Deploy via Vercel CLI

```bash
# Install Vercel CLI global (sekali saja)
npm install -g vercel

# Atau download dari https://vercel.com/download

# Verify
vercel --version

# Login ke Vercel
vercel login

# Deploy ke production
cd C:\xampp\htdocs\attendance-app
vercel --prod
```

## 3. Set Environment Variables di Vercel Dashboard

Setelah deploy, Vercel akan kasih URL. Buka dashboard:

1. Project Settings → Environment Variables
2. Add:
   ```
   APP_ENV = production
   APP_DEBUG = false
   APP_KEY = base64:Ss0YPE25VtW6i+vknKKMXnXLsxK/gWey835hWY0W0Hg=
   LOG_CHANNEL = stderr
   CACHE_DRIVER = array
   SESSION_DRIVER = cookie
   VIEW_COMPILED_PATH = /tmp/storage/framework/views
   ```

3. Redeploy: Klik **Deployments** → **Redeploy**

## 4. Custom Domain (Optional)

Kalau punya domain:
1. Di Vercel Project → Settings → Domains
2. Add domain Anda
3. Copy nameservers ke registrar domain
4. Update. Done!

## API Entrypoint

File `api/index.php` adalah entrypoint untuk Vercel serverless, sudah configured dengan benar.

Vercel otomatis baca dari `vercel.json` untuk routing.

## Notes

- Database: SQLite di `/tmp/database.sqlite` (ephemeral, reset setiap deploy baru)
- File upload: Tidak persist (gunakan AWS S3 untuk production)
- Untuk data production, upgrade ke Vercel Pro + integrate database external (MySQL, PostgreSQL)
