#!/bin/bash
set -e

if [ -z "$1" ] || [ -z "$2" ]; then
  echo "Usage: bash scripts/setup-mail.sh <gmail-address> '<app-password>'"
  echo "Example: bash scripts/setup-mail.sh kuaampelgading83@gmail.com 'xxxx xxxx xxxx xxxx'"
  exit 1
fi

GMAIL="$1"
APP_PASSWORD="$2"

export PATH="/root/.local/bin:/tools/node/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"
export HOME=/root

if [ -f /ai/proyek/.env ]; then
  cd /ai/proyek
else
  cd "$(dirname "$0")/.."
fi

if [ ! -f .env ]; then
  echo "ERROR: .env not found in $(pwd)"
  exit 1
fi

cp .env .env.backup
echo "Backup .env -> .env.backup"

append_or_replace() {
  KEY="$1"
  VALUE="$2"
  if grep -q "^${KEY}=" .env; then
    sed -i "s|^${KEY}=.*|${KEY}=${VALUE}|" .env
  else
    echo "${KEY}=${VALUE}" >> .env
  fi
}

append_or_replace MAIL_MAILER smtp
append_or_replace MAIL_HOST smtp.gmail.com
append_or_replace MAIL_PORT 587
append_or_replace MAIL_USERNAME "${GMAIL}"
append_or_replace MAIL_PASSWORD "${APP_PASSWORD}"
append_or_replace MAIL_ENCRYPTION tls
append_or_replace MAIL_FROM_ADDRESS "${GMAIL}"
append_or_replace MAIL_FROM_NAME "KUA Ampelgading"

php artisan config:clear
php artisan config:cache

echo "Sending test email to ${GMAIL}..."
php artisan tinker --execute="Mail::raw('Test email dari KUA Ampelgading - konfigurasi SMTP Gmail berhasil.', function (\$message) { \$message->to('${GMAIL}')->subject('Test SMTP KUA Ampelgading'); });"
echo "Test email sent. Check inbox of ${GMAIL}."
