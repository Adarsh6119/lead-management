# 🚀 Hostinger VPS Deployment Guide — TaxiCRM (Laravel)

This step-by-step guide will walk you through hosting your **TaxiCRM / Lead Management** application on a **Hostinger VPS** (Ubuntu 22.04 or 24.04 LTS) using **Nginx**, **PHP 8.2/8.3**, **SQLite/MySQL**, and free **SSL (Certbot)**.

---

## 📋 Prerequisites
1. Hostinger VPS running **Ubuntu 22.04 or 24.04 LTS**.
2. Root SSH Access to your VPS IP address (`ssh root@your-vps-ip`).
3. Domain name pointed to Hostinger VPS IP address (DNS A Record: `@` -> `YOUR_VPS_IP`).

---

## 🛠️ Step 1: Connect & Update Server

Connect to your VPS via SSH terminal:
```bash
ssh root@your-vps-ip
```

Update package lists and upgrade server software:
```bash
sudo apt update && sudo apt upgrade -y
```

---

## 📦 Step 2: Install Nginx, PHP 8.2/8.3, Composer & Git

Install Nginx web server, Git, Unzip, and required PHP extensions:
```bash
sudo apt install -y nginx git unzip curl software-properties-common

# Add PHP Repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 & extensions
sudo apt install -y php8.2-cli php8.2-fpm php8.2-sqlite3 php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-intl
```

Install Composer globally:
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

Install Node.js & NPM (for frontend assets build):
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 📁 Step 3: Clone Project Code & Set Permissions

Navigate to web root directory and clone/upload your project:
```bash
cd /var/www
# Option A: Clone from Git
git clone https://github.com/your-username/lead-management.git lead-management

# Move into project directory
cd /var/www/lead-management
```

Set proper directory permissions for Laravel:
```bash
sudo chown -R www-data:www-data /var/www/lead-management
sudo chmod -R 775 /var/www/lead-management/storage
sudo chmod -R 775 /var/www/lead-management/bootstrap/cache
sudo chmod -R 775 /var/www/lead-management/database
```

---

## ⚙️ Step 4: Install Dependencies & Setup Environment

Install PHP dependencies without dev packages:
```bash
composer install --no-dev --optimize-autoloader
```

Install Node dependencies & build production assets:
```bash
npm install
npm run build
```

Configure `.env` file:
```bash
cp .env.example .env
nano .env
```

Update your `.env` settings:
```ini
APP_NAME="TaxiCRM"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=sqlite
# If using SQLite, ensure storage/database.sqlite path is valid:
DB_DATABASE=/var/www/lead-management/database/database.sqlite

BUSINESS_STATE="Uttar Pradesh"
```

Generate Laravel Application Key & run fresh database migrations:
```bash
php artisan key:generate
php artisan migrate:fresh --seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set database permissions:
```bash
sudo chown -R www-data:www-data /var/www/lead-management/database
sudo chmod -R 775 /var/www/lead-management/database
```

---

## 🌐 Step 5: Configure Nginx Web Server

Create Nginx server block configuration for your domain:
```bash
sudo nano /etc/nginx/sites-available/taxicrm
```

Paste the following configuration (replace `yourdomain.com` with your actual domain):
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/lead-management/public;

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
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site configuration & restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/taxicrm /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🔒 Step 6: Install Free SSL Certificate (Certbot Let's Encrypt)

Install Certbot for HTTPS:
```bash
sudo apt install -y certbot python3-certbot-nginx
```

Obtain and install SSL certificate automatically:
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```
*(Select Option 2 to auto-redirect HTTP to HTTPS).*

---

## 🔑 Step 7: Initial Login Credentials

Once deployment completes, open `https://yourdomain.com` in your browser:

- **Team Lead Account (TL)**:
  - Login ID: `TL001`
  - Password: `tl123`
  - *(Exclusive privilege to create new employees & monitor team performance)*

- **System Admin**:
  - Login ID: `ADMIN01`
  - Password: `admin123`

- **Accountant**:
  - Login ID: `ACCT01`
  - Password: `accounts123`

---

## 🔄 Handy Deployment Maintenance Commands

Whenever you pull new updates to your VPS:
```bash
cd /var/www/lead-management
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
```
