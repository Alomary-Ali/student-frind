# Production Deployment Guide
## رفيق الطالب — دليل نشر الإنتاج

---

## متطلبات الخادم

| المتطلب | الإصدار |
|--------|---------|
| PHP | 8.2+ |
| Laravel | 12.x |
| MySQL | 8.0+ |
| Redis | 7.0+ |
| Node.js | 20+ (للـ build) |
| Nginx | 1.24+ |

---

## خطوات النشر

### 1. إعداد البيئة
```bash
cp .env.example .env
# تعديل القيم الإلزامية أدناه
php artisan key:generate
```

**القيم الإلزامية في `.env`:**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_HOST=your-db-host
DB_DATABASE=student_success_platform
DB_USERNAME=ssp_user
DB_PASSWORD=<STRONG_PASSWORD_16+_CHARS>

REDIS_HOST=your-redis-host
REDIS_PASSWORD=<REDIS_PASSWORD>

SESSION_ENCRYPT=true
QUEUE_CONNECTION=redis
CACHE_STORE=redis
```

### 2. تثبيت الحزم
```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

### 3. قاعدة البيانات
```bash
php artisan migrate --force
# تأكد من ترتيب migrations (users أولاً)
```

### 4. Cache التطبيق
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 5. تشغيل الـ Queue Worker
```bash
# باستخدام Supervisor
php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
```

### 6. أذونات الملفات
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Nginx Configuration

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;
    root /var/www/rafiq-student/public;

    ssl_certificate     /etc/ssl/certs/your-domain.crt;
    ssl_certificate_key /etc/ssl/private/your-domain.key;
    ssl_protocols       TLSv1.2 TLSv1.3;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' fonts.googleapis.com; font-src 'self' fonts.gstatic.com;";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}

# Redirect HTTP → HTTPS
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$host$request_uri;
}
```

---

## Post-Deployment Checks

```bash
# 1. Health check
curl https://your-domain.com/up

# 2. Run tests against staging
php artisan test --env=staging

# 3. Check logs
php artisan pail --timeout=0

# 4. Verify no debug info exposed
curl -I https://your-domain.com/login | grep X-Powered-By
# يجب أن لا يظهر PHP version
```

---

## Rollback

```bash
# إذا فشل النشر
git checkout HEAD~1
composer install --no-dev --optimize-autoloader
php artisan migrate:rollback --step=1
php artisan config:cache
```

---

## Monitoring

| الأداة | الاستخدام |
|--------|----------|
| Laravel Telescope | Dev environment — debug requests |
| Laravel Pulse | Production — performance metrics |
| Sentry | Production — error tracking |
| Supervisor | Queue workers management |
| Logrotate | Log file rotation |
