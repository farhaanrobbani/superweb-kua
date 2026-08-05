# Surat Digital KUA

Aplikasi web untuk pembuatan surat digital di Kantor Urusan Agama (KUA) berbasis **Laravel 13** dengan alur persetujuan, penomoran otomatis, ekspor PDF berkop KUA, tanda tangan digital, dan permohonan surat secara online.

## Fitur

- **Autentikasi & role**: staf KUA (membuat surat) dan Kepala KUA (persetujuan & tanda tangan)
- **Master data**: jenis surat dengan field dinamis, template surat, pengaturan KUA (kop, alamat, kepala KUA, TTD)
- **Modul surat**: alur `draft → diajukan → disetujui → terbit`, penomoran otomatis per jenis per tahun (contoh: `SKU.001/KUA.VIII/2026`)
- **PDF**: ekspor surat berkop KUA dengan tanda tangan digital (gambar PNG transparan)
- **Arsip**: daftar, filter (jenis/status/tahun), pencarian, unduh ulang PDF
- **Permohonan online**: masyarakat mengisi form tanpa login; staf memverifikasi dan membuat surat dari data permohonan (terisi otomatis)
- **Dashboard**: statistik surat & permohonan

## Persyaratan

- PHP 8.3+, Composer, MySQL 8 / MariaDB
- Node.js + NPM (untuk aset frontend)

## Instalasi (Lokal / VPS)

```bash
composer install
cp .env.example .env
php artisan key:generate
# atur kredensial MySQL di .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
php artisan migrate --force
php artisan db:seed --force        # membuat user awal & data master
npm install && npm run build
```

User awal (ubah via `.env` sebelum seed):

| Role | Email default | Password default |
|---|---|---|
| Staf | `staf@kua.local` | `password` |
| Kepala | `kepala@kua.local` | `password` |

⚠️ Wajib mengganti password default setelah deploy pertama.

## Deploy Otomatis ke VPS (GitHub Actions)

Workflow di `.github/workflows/deploy.yml`:

1. **Test** — menjalankan seluruh test (PHPUnit + SQLite) di setiap push ke `main`.
2. **Deploy** — jika test lulus, menarik kode ke VPS via SSH, menjalankan `composer install`, `migrate`, dan cache.

### Setup VPS (sekali saja)

```bash
sudo apt update
sudo apt install -y nginx php8.3-fpm php8.3-cli php8.3-mbstring php8.3-xml \
  php8.3-curl php8.3-mysql php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl composer git mysql-server
```

```bash
sudo mysql -e "CREATE DATABASE surdig CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'surdig'@'localhost' IDENTIFIED BY 'GANTI_PASSWORD'; GRANT ALL ON surdig.* TO 'surdig'@'localhost'; FLUSH PRIVILEGES;"
```

```bash
sudo mkdir -p /var/www/surdig
sudo chown -R $USER /var/www/surdig
cd /var/www/surdig
git clone https://github.com/farhaanrobbani/surdig.git .
composer install --no-dev --no-interaction
cp .env.example .env
php artisan key:generate
# atur .env: APP_URL, DB_*, STAFF_EMAIL, STAFF_PASSWORD, KEPALA_EMAIL, KEPALA_PASSWORD
php artisan migrate --force
php artisan db:seed --force
npm install && npm run build
sudo chown -R www-data:www-data storage bootstrap/cache
```

Config Nginx (`/etc/nginx/sites-available/surdig`):

```nginx
server {
    listen 80;
    server_name kua.example.com;
    root /var/www/surdig/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/surdig /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### Set GitHub Secrets

Di repo `surdig` → **Settings → Secrets and variables → Actions**, tambahkan:

| Secret | Nilai |
|---|---|
| `DEPLOY_HOST` | IP/domain VPS |
| `DEPLOY_USER` | user SSH di VPS (mis. `deploy`) |
| `DEPLOY_SSH_KEY` | private SSH key yang terpasang di VPS (authorized_keys) |
| `DEPLOY_PORT` | port SSH (`22`) |
| `DEPLOY_PATH` | path aplikasi (`/var/www/surdig`) |

Lalu aktifkan deploy dengan menambahkan **Repository variable** `DEPLOY_ENABLED=true` (Settings → Secrets and variables → Actions → Variables). Job deploy akan berjalan otomatis setelah test lulus; selama variable belum diaktifkan, hanya test yang berjalan.

Setelah secrets terpasang, setiap `git push origin main` akan otomatis men-deploy ke VPS.

## Struktur Nomor Surat

Format: `{KODE}.{urutan:3}/KUA.{bulan-romawi}/{tahun}` — contoh `SKU.001/KUA.VIII/2026`. Counter dihitung per jenis surat dan direset setiap tahun.

## Keamanan

- Eloquent ORM (parameter binding) untuk seluruh query — bebas SQL Injection
- Escape output di Blade — bebas XSS
- Validasi form di sisi server untuk semua input
- Rate limit pada form permohonan publik + honeypot anti-bot
- Kredensial hanya di `.env` (tidak pernah di-commit)

## Test

```bash
php artisan test
```
