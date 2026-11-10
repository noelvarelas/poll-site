#!/bin/sh

REPO_HTTPS="https://github.com/noelvarelas/poll-site.git"
REPO_SSH="git@github.com:noelvarelas/poll-site.git"
WEBROOT="/var/www/poll-site"
PHP_V="85"

if [ "$(id -u)" -ne 0 ]; then
  echo "This script must be run as root"
  exit 1
fi

echo "1/6 Setting up packages..."
if grep -q '^#.*\/community' /etc/apk/repositories; then
  sed -i '/\/community/s/^#//' /etc/apk/repositories
  apk update
fi
apk add ufw nginx certbot-nginx sqlite git \
  "php$PHP_V" "php$PHP_V-fpm" "php$PHP_V-sqlite3" "php$PHP_V-pdo_sqlite" "php$PHP_V-mbstring" \
  > /dev/null

echo "2/6 Setting up firewall..."
ufw status | grep -q '22/tcp.*ALLOW' || ufw allow 22/tcp
ufw status | grep -q '80/tcp.*ALLOW' || ufw allow 80/tcp
ufw status | grep -q '443/tcp.*ALLOW' || ufw allow 443/tcp
if ! rc-update show default | grep -q ufw; then
  ufw --force enable
  rc-update add ufw default
  rc-service ufw start
fi

echo "3/6 Setting up site directory..."
if [ ! -d "$WEBROOT" ]; then
  echo "Select GitHub authentication method:"
  while true; do
    echo "1) HTTPS"
    echo "2) SSH"
    read -r option
    case $option in
      1)
        git clone "$REPO_HTTPS" "$WEBROOT" || exit 1
        break;;
      2)
        git clone "$REPO_SSH" "$WEBROOT" || exit 1
        break;;
      *)
        echo "Invalid entry. Exiting..."
        exit 1
        ;;
    esac
  done
  install -m 0664 /dev/null "$WEBROOT/private/requests.txt"
  chmod 775 "$WEBROOT/private"
  chgrp -R nginx "$WEBROOT"
fi

echo "4/6 Setting up SQLite..."
if [ ! -f "$WEBROOT/private/database.sqlite" ]; then
  install -m 0664 -g nginx /dev/null "$WEBROOT/private/database.sqlite"
  sqlite3 "$WEBROOT/private/database.sqlite" <<EOL
CREATE TABLE questions (
  id INTEGER PRIMARY KEY,
  date TEXT UNIQUE NOT NULL,
  question TEXT NOT NULL,
  answer_a TEXT NOT NULL,
  answer_b TEXT NOT NULL,
  results_a INTEGER NOT NULL DEFAULT 0 check (results_a >= 0),
  results_b INTEGER NOT NULL DEFAULT 0 check (results_b >= 0)
);
EOL
fi

echo "5/6 Setting up php..."
if ! rc-update show default | grep -q "php-fpm$PHP_V"; then
  sed -i 's/^user = nobody/user = nginx/g' /etc/"php$PHP_V"/php-fpm.d/www.conf
  sed -i 's/^user = nobody/group = nginx/g' /etc/"php$PHP_V"/php-fpm.d/www.conf
  rc-update add "php-fpm$PHP_V" default
  rc-service "php-fpm$PHP_V" start
fi

echo "6/6 Setting up nginx..."
if ! rc-update show default | grep -q nginx; then
  echo "Enter the domain or IP you want to host poll-site on and press [ENTER]."
  echo "Examples: poll-site.example.com or 192.168.0.1"
  read -r DOMAIN
  if [ -z "$DOMAIN" ]; then
    echo "No input given. Exiting..."
    exit 1
  fi
  cat >/etc/nginx/http.d/poll-site.conf <<EOL
server {
  listen 80;
  listen [::]:80;

  root ${WEBROOT}/public;
  index index.php index.html index.htm;

  server_name ${DOMAIN};

  location / {
    try_files \$uri \$uri/ =404;
  }
  
  location ~ \.php\$ {
    try_files \$uri =404;
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;
    include fastcgi.conf;
  }
}
EOL
  rc-update add nginx default
  rc-service nginx start
fi

echo "Setup complete!"
echo "You can now run 'certbot --nginx' to set up an HTTPS site if desired."