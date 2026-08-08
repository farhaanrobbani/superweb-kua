# Surat Digital KUA

Aplikasi web untuk pembuatan surat digital di Kantor Urusan Agama (KUA) berbasis **Laravel 13** dengan alur persetujuan, ekspor PDF berkop KUA, laporan kinerja pegawai (PDF & Word), permohonan surat secara online, dan fitur layanan publik.

## Fitur

- **Autentikasi & role**: staf KUA (membuat surat), Operator KUA (mengelola konten & data master), dan Kepala KUA (persetujuan, tanda tangan, & pengelolaan user)
- **Master data**: 12 jenis surat dengan field dinamis (SPN, SKU, SPC, SUP, SIN, SP, SPD, SPA, SPM, SKN, PNL), template surat, pengaturan KUA (kop, alamat, kepala KUA, penanda posisi TTD), halaman statis & menu navbar dinamis
- **Modul surat**: alur `draft → diajukan → disetujui → terbit`, PDF berkop KUA, nomor surat diisi manual (contoh: `B.001/KUA.35.07.06/PW.01/01/2026`)
- **Laporan kinerja pegawai (lapkin)**: pencatatan kegiatan harian, master data harian & tema pekerjaan, template kalimat, ekspor **PDF & Word** (laporan per pegawai dan rekap per bulan/tahun)
- **Permohonan online**: masyarakat mengisi form tanpa login (SPD, SPA, SKN, PNL); staf/operator memverifikasi dan membuat surat dari data permohonan (terisi otomatis)
- **Layanan publik**: pengumuman, daftar pegawai, pusat unduhan, layanan pernikahan, dan kritik & saran
- **Dashboard**: statistik surat & permohonan
- **Email**: notifikasi lupa password via SMTP Gmail

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
php artisan storage:link           # symlink public/storage -> storage/app/public (untuk logo/upload)
npm install && npm run build
```

Halaman login berada di **`/yukmasuk`**.

User awal (ubah via `.env` sebelum seed):

| Role | Email default | Password default |
|---|---|---|
| Staf | `staf@kua.local` | `password` |
| Operator | `operator@kua.local` | `password` |
| Kepala | `kepala@kua.local` | `password` |

⚠️ Wajib mengganti password default setelah deploy pertama.

### Email (SMTP Gmail)

Aplikasi mengirim email untuk fitur lupa password. Default `.env.example` sudah diarahkan ke SMTP Gmail (`MAIL_MAILER=smtp`) — cukup isi di `.env`:

| Variabel | Isi |
|---|---|
| `MAIL_USERNAME` | alamat Gmail pengirim (mis. `kuaampelgading83@gmail.com`) |
| `MAIL_PASSWORD` | App Password Gmail 16 karakter |
| `MAIL_FROM_ADDRESS` | alamat pengirim (sama dengan `MAIL_USERNAME`) |

Cara membuat App Password:
1. Aktifkan **2-Step Verification**: `https://myaccount.google.com/security`
2. Buat App Password: Security → App passwords → pilih "Mail"

Atau jalankan sekali (backup `.env`, set konfigurasi SMTP, kirim email test):

```bash
bash scripts/setup-mail.sh kuaampelgading83@gmail.com 'xxxx xxxx xxxx xxxx'
```

> `MAIL_SCHEME` opsional — kosongkan, otomatis mengikuti port (`587` → smtp, `465` → smtps).

## Nomor Surat

Nomor surat **diisi manual** oleh pengguna di form pembuatan/penyuntingan surat (tidak ada penomoran otomatis). Contoh format:

```
B.001/KUA.35.07.06/PW.01/01/2026
```

Surat baru dimulai sebagai `draft`; setelah dilengkapi nomor & tanggal, surat dapat diajukan, disetujui, lalu diterbitkan.

## Kop & Tanda Tangan PDF

- Kop surat dapat dikonfigurasi di **Pengaturan KUA** (`/kua-settings`): logo, teks kop (judul/sub-judul), ukuran kop, dan alamat.
- Penanda posisi tanda tangan memakai simbol **anchor `^`** (dapat dimatikan via pengaturan `kop_anchor`); Kepala KUA menandatangani surat fisik di posisi tersebut.

## Deploy Otomatis ke VPS (GitHub Actions)

Workflow di `.github/workflows/deploy.yml` berisi 2 job:

1. **Test** — menjalankan seluruh test (PHPUnit + SQLite) di setiap push ke `main`.
2. **Deploy** — jika test lulus **dan** Repository variable `DEPLOY_ENABLED=true`, mengirim webhook `POST` ke VPS.

### Cara kerja

Push ke `main` → job `test` (matrix PHP 8.3) → jika sukses & `vars.DEPLOY_ENABLED == 'true'`, GitHub Actions mengirim `POST` ke `DEPLOY_WEBHOOK_URL` dengan header:

- `X-Deploy-Token: <DEPLOY_TOKEN>`
- `X-GitHub-Event: push`
- `X-GitHub-Delivery: <run_id>-<run_attempt>`

Di VPS, endpoint `POST /deploy` (`DeployController`) memverifikasi token dari `.env` (`DEPLOY_TOKEN`), lalu menjalankan `scripts/deploy.sh`.

### `scripts/deploy.sh` (di VPS)

Path aplikasi di VPS: **`/ai/proyek`**. Langkah yang dijalankan:

1. `git pull origin main`
2. Install & salin font Liberation ke `storage/fonts/` (font PDF surat)
3. `composer install --no-dev`
4. `php artisan migrate --force`
5. `npm install` + `npm run build`
6. `php artisan storage:link --force`
7. `config:cache`, `route:cache`, `view:cache`
8. Set `upload_max_filesize`/`post_max_size` php-fpm
9. `chown` storage & bootstrap/cache ke `www-data`, restart `php-fpm`

### Setup VPS (sekali saja)

```bash
sudo apt update
sudo apt install -y nginx php8.3-fpm php8.3-cli php8.3-mbstring php8.3-xml \
  php8.3-curl php8.3-mysql php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl composer git mysql-server fonts-liberation
```

```bash
sudo mysql -e "CREATE DATABASE surdig CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'surdig'@'localhost' IDENTIFIED BY 'GANTI_PASSWORD'; GRANT ALL ON surdig.* TO 'surdig'@'localhost'; FLUSH PRIVILEGES;"
```

```bash
sudo mkdir -p /ai/proyek
sudo chown -R $USER /ai/proyek
cd /ai/proyek
git clone https://github.com/farhaanrobbani/surdig.git .
composer install --no-dev --no-interaction
cp .env.example .env
php artisan key:generate
# atur .env: APP_URL, APP_ENV=production, APP_DEBUG=false, DB_*, DEPLOY_TOKEN, MAIL_*, STAFF_EMAIL, STAFF_PASSWORD, KEPALA_EMAIL, KEPALA_PASSWORD
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link           # symlink untuk logo/upload (sekali saja)
npm install && npm run build
sudo chown -R www-data:www-data storage bootstrap/cache
```

Config Nginx (`/etc/nginx/sites-available/surdig`):

```nginx
server {
    listen 80;
    server_name kua.example.com;
    root /ai/proyek/public;

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

### Konfigurasi GitHub Actions

Di repo `surdig` → **Settings → Secrets and variables → Actions**:

| Tipe | Nama | Nilai |
|---|---|---|
| Secret | `DEPLOY_WEBHOOK_URL` | URL endpoint deploy di VPS (mis. `https://domainkua.example.com/deploy`) |
| Secret | `DEPLOY_TOKEN` | token yang sama dengan `DEPLOY_TOKEN` di `.env` VPS |
| Variable | `DEPLOY_ENABLED` | `true` untuk mengaktifkan job deploy |

Selama `DEPLOY_ENABLED` belum diaktifkan, hanya job `test` yang berjalan. Setelah terpasang, setiap `git push origin main` otomatis men-deploy ke VPS.

## Keamanan

- Eloquent ORM (parameter binding) untuk seluruh query — bebas SQL Injection
- Escape output di Blade — bebas XSS
- Validasi form di sisi server untuk semua input
- Rate limit pada form permohonan publik + honeypot anti-bot
- Kredensial & token hanya di `.env` (tidak pernah di-commit)
- Verifikasi token pada endpoint webhook `/deploy`

## Test

```bash
php artisan test
```
