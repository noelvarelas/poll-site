#!/bin/bash

DB_PASS=''
REPO_HTTPS="https://github.com/noelvarelas/poll-site.git"
REPO_SSH="git@github.com:noelvarelas/poll-site.git"
WEBROOT="/var/www/poll-site"

if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root"
  exit 1
fi

echo "1/5 Installing required packages..."
dnf install -qy httpd mariadb-server php php-mysqlnd git

echo; echo "2/5 Opening HTTP and HTTPS in firewall..."
for SERVICE in http https; do
  if ! firewall-cmd -q --permanent --query-service="$SERVICE"; then
    firewall-cmd --permanent --add-service="$SERVICE"
    firewall-cmd --reload
  fi
done

echo; echo "3/5 Setting up site directory..."
if [[ ! -d "$WEBROOT" ]]; then
  echo "Select GitHub authentication method:"
  select option in "HTTPS" "SSH"; do
    case $option in
      "HTTPS")
        git clone "$REPO_HTTPS" "$WEBROOT" || exit 1
        break;;
      "SSH")
        git clone "$REPO_SSH" "$WEBROOT" || exit 1
        break;;
      *)
        echo "Invalid entry. Exiting..."
        exit 1
        ;;
    esac
  done
fi

echo; echo "4/5 Setting up MariaDB..."
systemctl enable --now mariadb.service

mariadb -u root -e "CREATE DATABASE IF NOT EXISTS poll_site;"
mariadb -u root poll_site <<'SQL'
CREATE TABLE IF NOT EXISTS questions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  date DATE UNIQUE NOT NULL,
  question VARCHAR(255) NOT NULL,
  answer_a VARCHAR(255) NOT NULL,
  answer_b VARCHAR(255) NOT NULL,
  results_a INT UNSIGNED NOT NULL DEFAULT 0,
  results_b INT UNSIGNED NOT NULL DEFAULT 0
);
SQL

if ! mariadb -u root -Nse "SELECT 1 FROM mysql.user \
  WHERE User='poll_site' AND Host='localhost';" \
  | grep -q 1; then

  echo "Enter a password (only letters and numbers) for the poll_site database:"
  read -rs DB_PASS; echo
  echo "Enter it again:"
  read -rs DB_PASS_CHECK; echo
  if [[ "$DB_PASS" != "$DB_PASS_CHECK" ]]; then
    echo "Passwords do not match."
    exit 1
  fi
  if [[ ! "$DB_PASS" =~ ^[A-Za-z0-9]+$ ]]; then
    echo "Password must contain only letters and numbers."
    exit 1
  fi
  
  mariadb -u root -e "CREATE USER 'poll_site'@'localhost' \
    IDENTIFIED BY '$DB_PASS';"
  mariadb -u root -e "GRANT SELECT, UPDATE ON poll_site.questions \
    TO 'poll_site'@'localhost';"

  if [[ ! -f "$WEBROOT"/private/db-credentials.php ]]; then
    cp "$WEBROOT"/private/db-credentials-example.php "$WEBROOT"/private/db-credentials.php
    sed -i '/^\/\/ EXAMPLE/d' "$WEBROOT"/private/db-credentials.php
    sed -E -i "s|CHANGE_ME_PLEASE|$DB_PASS|" "$WEBROOT"/private/db-credentials.php
  fi
fi

echo; echo "5/5 Setting up httpd..."
if [[ ! -f /etc/httpd/conf.d/poll-site.conf ]]; then
  echo "Enter the domain or IP you want to host poll-site on and press [ENTER]."
  echo "Examples: poll-site.example.com or 192.168.0.1"
  read -r DOMAIN
  if [[ -z "$DOMAIN" ]]; then
    echo "No input given. Exiting..."
    exit 1
  fi
  cat >/etc/httpd/conf.d/poll-site.conf <<EOL
<VirtualHost *:80>
  ServerName "${DOMAIN}"
  DocumentRoot "${WEBROOT}/public"

  <Directory "${WEBROOT}/public">
    Options -Indexes
    AllowOverride None
    Require all granted
  </Directory>

  ErrorLog /var/log/httpd/poll-site-error.log
  CustomLog /var/log/httpd/poll-site-access.log combined
</VirtualHost>
EOL
  systemctl enable --now httpd.service
  systemctl reload httpd.service
fi

echo; echo "Setup complete!"
echo "Note: this script does not handle your SSH or HTTPS setup."
echo "Consider requiring SSH keys for login and using Certbot for HTTPS if desired."