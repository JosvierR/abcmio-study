# 12. Despliegue

## 12.1 Requisitos del Servidor

### 12.1.1 Requisitos Mínimos

**Hardware:**
- CPU: 2 cores
- RAM: 2 GB
- Disco: 10 GB SSD
- Ancho de banda: 100 Mbps

**Software:**
- Ubuntu 18.04+ / CentOS 7+ / Debian 9+
- PHP 7.1.3+ (7.4 recomendado)
- MySQL 5.7+ o MariaDB 10.2+
- Nginx o Apache 2.4+
- Composer 2.x
- Node.js 12.x+ y NPM 6.x+
- Git

**Extensiones PHP Requeridas:**
```bash
php -m | grep -E 'openssl|pdo|mbstring|tokenizer|xml|ctype|json|bcmath|zip|gd|curl'
```

### 12.1.2 Extensiones PHP

```bash
# Ubuntu/Debian
sudo apt-get install php7.4-mbstring php7.4-xml php7.4-zip php7.4-bcmath \
php7.4-json php7.4-mysql php7.4-gd php7.4-curl php7.4-tokenizer \
php7.4-ctype php7.4-fileinfo

# CentOS/RHEL
sudo yum install php74-mbstring php74-xml php74-zip php74-bcmath \
php74-json php74-mysqlnd php74-gd php74-curl
```

## 12.2 Instalación Local (Sin DDEV)

### 12.2.1 Clonar Repositorio

```bash
# Clonar proyecto
git clone https://github.com/JosvierR/abcmio-study.git
cd abcmio-study

# Cambiar a rama deseada
git checkout main
```

### 12.2.2 Configurar Dependencias

```bash
# Instalar dependencias PHP
composer install --no-dev --optimize-autoloader

# Instalar dependencias JavaScript
npm install

# Compilar assets
npm run production
```

### 12.2.3 Configurar Entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar APP_KEY
php artisan key:generate

# Editar variables de entorno
nano .env
```

**Variables Esenciales:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://abcmio.com

DB_HOST=localhost
DB_DATABASE=abcmio
DB_USERNAME=abcmio_user
DB_PASSWORD=secure_password

MAIL_HOST=smtp.sendgrid.net
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxx

PAYPAL_MODE=live
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_SECRET=your_secret
```

### 12.2.4 Configurar Base de Datos

```bash
# Crear base de datos
mysql -u root -p <<EOF
CREATE DATABASE abcmio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'abcmio_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON abcmio.* TO 'abcmio_user'@'localhost';
FLUSH PRIVILEGES;
EOF

# Ejecutar migraciones
php artisan migrate --force

# Ejecutar seeders (opcional)
php artisan db:seed --force
```

### 12.2.5 Configurar Permisos

```bash
# Dar permisos de escritura
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Crear link simbólico para archivos públicos
php artisan storage:link
```

### 12.2.6 Optimizar para Producción

```bash
# Cache de configuración
php artisan config:cache

# Cache de rutas
php artisan route:cache

# Cache de vistas
php artisan view:cache

# Optimizar autoload
composer dump-autoload -o
```

## 12.3 Instalación con DDEV

### 12.3.1 Requisitos DDEV

```bash
# Verificar requisitos
docker --version  # 20.10+
ddev version      # 1.18+
```

### 12.3.2 Iniciar Proyecto

```bash
# Clonar repositorio
git clone https://github.com/JosvierR/abcmio-study.git
cd abcmio-study

# Iniciar DDEV
ddev start

# Instalar dependencias
ddev composer install
ddev npm install

# Configurar entorno
ddev exec cp .env.example .env
ddev exec php artisan key:generate

# Migrar base de datos
ddev exec php artisan migrate
ddev exec php artisan db:seed

# Compilar assets
ddev npm run dev

# Acceder a la aplicación
# https://abcmio.ddev.site
```

### 12.3.3 Comandos DDEV Útiles

```bash
# Acceder al contenedor
ddev ssh

# Ejecutar comandos PHP
ddev exec php artisan migrate
ddev exec php artisan tinker

# Ejecutar Composer
ddev composer require package/name
ddev composer update

# Ejecutar NPM
ddev npm install
ddev npm run watch

# Ver logs
ddev logs

# Importar base de datos
ddev import-db --src=dump.sql

# Exportar base de datos
ddev export-db --file=dump.sql

# Detener proyecto
ddev stop

# Eliminar proyecto
ddev delete -O
```

### 12.3.4 Configuración DDEV

```yaml
# .ddev/config.yaml
name: abcmio
type: laravel
docroot: public
php_version: "7.2"
webserver_type: nginx-fpm
database:
    type: mariadb
    version: "10.11"
composer_version: "2"
nodejs_version: "14"
```

## 12.4 Configuración de Nginx

### 12.4.1 Configuración Básica

```nginx
# /etc/nginx/sites-available/abcmio.com
server {
    listen 80;
    listen [::]:80;
    server_name abcmio.com www.abcmio.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name abcmio.com www.abcmio.com;
    
    root /var/www/abcmio/public;
    index index.php index.html;
    
    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/abcmio.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/abcmio.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    # Logs
    access_log /var/log/nginx/abcmio-access.log;
    error_log /var/log/nginx/abcmio-error.log;
    
    # Static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # PHP
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    # Denegar acceso a archivos ocultos
    location ~ /\. {
        deny all;
    }
    
    # Proteger directorio storage
    location ~ ^/storage/(.*)$ {
        deny all;
    }
}
```

### 12.4.2 Habilitar Sitio

```bash
# Crear enlace simbólico
sudo ln -s /etc/nginx/sites-available/abcmio.com /etc/nginx/sites-enabled/

# Verificar configuración
sudo nginx -t

# Recargar Nginx
sudo systemctl reload nginx
```

## 12.5 Configuración de Apache

### 12.5.1 Virtual Host

```apache
# /etc/apache2/sites-available/abcmio.com.conf
<VirtualHost *:80>
    ServerName abcmio.com
    ServerAlias www.abcmio.com
    Redirect permanent / https://abcmio.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName abcmio.com
    ServerAlias www.abcmio.com
    
    DocumentRoot /var/www/abcmio/public
    
    <Directory /var/www/abcmio/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # SSL
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/abcmio.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/abcmio.com/privkey.pem
    
    # Logs
    ErrorLog ${APACHE_LOG_DIR}/abcmio-error.log
    CustomLog ${APACHE_LOG_DIR}/abcmio-access.log combined
</VirtualHost>
```

### 12.5.2 Habilitar Módulos y Sitio

```bash
# Habilitar módulos necesarios
sudo a2enmod rewrite ssl headers

# Habilitar sitio
sudo a2ensite abcmio.com.conf

# Recargar Apache
sudo systemctl reload apache2
```

## 12.6 SSL/TLS con Let's Encrypt

### 12.6.1 Instalación de Certbot

```bash
# Ubuntu/Debian
sudo apt-get install certbot python3-certbot-nginx

# CentOS/RHEL
sudo yum install certbot python3-certbot-nginx
```

### 12.6.2 Obtener Certificado

```bash
# Para Nginx
sudo certbot --nginx -d abcmio.com -d www.abcmio.com

# Para Apache
sudo certbot --apache -d abcmio.com -d www.abcmio.com

# Renovación automática
sudo certbot renew --dry-run
```

### 12.6.3 Cron para Renovación

```bash
# Editar crontab
sudo crontab -e

# Agregar línea para renovación diaria
0 3 * * * certbot renew --quiet --post-hook "systemctl reload nginx"
```

## 12.7 Supervisor para Queue Workers

### 12.7.1 Instalación

```bash
sudo apt-get install supervisor
```

### 12.7.2 Configuración

```ini
# /etc/supervisor/conf.d/abcmio-worker.conf
[program:abcmio-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/abcmio/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/abcmio/storage/logs/worker.log
stopwaitsecs=3600
```

### 12.7.3 Iniciar Worker

```bash
# Recargar configuración
sudo supervisorctl reread
sudo supervisorctl update

# Iniciar worker
sudo supervisorctl start abcmio-worker:*

# Ver estado
sudo supervisorctl status

# Reiniciar después de deploy
sudo supervisorctl restart abcmio-worker:*
```

## 12.8 Cron Jobs

### 12.8.1 Scheduler de Laravel

```bash
# Editar crontab
crontab -e

# Agregar línea para Laravel Scheduler
* * * * * cd /var/www/abcmio && php artisan schedule:run >> /dev/null 2>&1
```

### 12.8.2 Tareas Programadas

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Expirar propiedades diariamente a las 2 AM
    $schedule->call(function () {
        Property::where('status', 'published')
                ->where('expires_at', '<', now())
                ->update(['status' => 'expired']);
    })->dailyAt('02:00');
    
    // Backup de base de datos diario
    $schedule->command('backup:run')->dailyAt('01:00');
    
    // Limpiar logs antiguos semanalmente
    $schedule->command('log:clear')->weekly();
}
```

## 12.9 Deployment Workflow

### 12.9.1 Script de Deploy

```bash
#!/bin/bash
# deploy.sh

set -e

echo "🚀 Iniciando deployment..."

# Activar modo mantenimiento
php artisan down

# Obtener últimos cambios
git pull origin main

# Instalar dependencias
composer install --no-dev --optimize-autoloader
npm install --production

# Compilar assets
npm run production

# Migrar base de datos
php artisan migrate --force

# Limpiar y recrear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimizar autoload
composer dump-autoload -o

# Reiniciar queue workers
sudo supervisorctl restart abcmio-worker:*

# Desactivar modo mantenimiento
php artisan up

echo "✅ Deployment completado!"
```

### 12.9.2 Deploy con Zero Downtime

```bash
#!/bin/bash
# deploy-zero-downtime.sh

DEPLOY_DIR="/var/www"
CURRENT_LINK="/var/www/current"
RELEASE_DIR="/var/www/releases/$(date +%Y%m%d%H%M%S)"

echo "Creando release en $RELEASE_DIR"

# Clonar código
git clone https://github.com/username/abcmio.git $RELEASE_DIR
cd $RELEASE_DIR

# Instalar dependencias
composer install --no-dev --optimize-autoloader
npm install --production
npm run production

# Copiar .env desde current
cp $CURRENT_LINK/.env $RELEASE_DIR/.env

# Linkear storage compartido
rm -rf $RELEASE_DIR/storage
ln -s $DEPLOY_DIR/storage $RELEASE_DIR/storage

# Migrar
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Cambiar symlink
ln -sfn $RELEASE_DIR $CURRENT_LINK

# Reload PHP-FPM
sudo systemctl reload php7.4-fpm

# Restart workers
sudo supervisorctl restart abcmio-worker:*

echo "✅ Deploy completado"

# Limpiar releases antiguas (mantener últimas 5)
cd $DEPLOY_DIR/releases
ls -t | tail -n +6 | xargs rm -rf
```

## 12.10 Monitoreo

### 12.10.1 Uptime Monitoring

```bash
# Usar servicios como:
# - UptimeRobot
# - Pingdom
# - StatusCake

# Endpoints a monitorear:
https://abcmio.com/health
https://abcmio.com/api/health
```

### 12.10.2 Health Check Endpoint

```php
// routes/web.php
Route::get('/health', function () {
    try {
        // Verificar base de datos
        DB::connection()->getPdo();
        
        // Verificar cache
        Cache::has('test');
        
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'error' => $e->getMessage()
        ], 503);
    }
});
```

### 12.10.3 Application Performance Monitoring

```bash
# Opciones recomendadas:
# - New Relic
# - DataDog
# - Laravel Telescope (dev/staging)
# - Sentry (errores)
```

## 12.11 Backup

### 12.11.1 Backup de Base de Datos

```bash
#!/bin/bash
# backup-db.sh

BACKUP_DIR="/var/backups/abcmio"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="abcmio"
DB_USER="abcmio_user"
DB_PASS="password"

mkdir -p $BACKUP_DIR

# Backup con mysqldump
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Mantener solo últimos 30 días
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +30 -delete

echo "Backup completado: db_$DATE.sql.gz"
```

### 12.11.2 Backup de Archivos

```bash
#!/bin/bash
# backup-files.sh

BACKUP_DIR="/var/backups/abcmio"
DATE=$(date +%Y%m%d_%H%M%S)
SOURCE="/var/www/abcmio/storage"

mkdir -p $BACKUP_DIR

# Backup de storage
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C /var/www/abcmio storage

# Mantener solo últimos 7 días
find $BACKUP_DIR -name "storage_*.tar.gz" -mtime +7 -delete

echo "Backup completado: storage_$DATE.tar.gz"
```

### 12.11.3 Backup Automático

```bash
# Crontab
0 2 * * * /path/to/backup-db.sh >> /var/log/backup.log 2>&1
0 3 * * 0 /path/to/backup-files.sh >> /var/log/backup.log 2>&1
```

## 12.12 Rollback

### 12.12.1 Rollback de Código

```bash
# Con releases directory
ln -sfn /var/www/releases/20240115120000 /var/www/current
sudo systemctl reload php7.4-fpm

# Con Git
cd /var/www/abcmio
git reset --hard HEAD~1
php artisan config:cache
```

### 12.12.2 Rollback de Base de Datos

```bash
# Restaurar desde backup
gunzip < /var/backups/abcmio/db_20240115.sql.gz | mysql -u root -p abcmio

# Rollback de migración
php artisan migrate:rollback --step=1
```

## Documentos Relacionados

- **Anterior**: [Configuración](11-CONFIGURACION.md)
- **Siguiente**: [Testing](13-TESTING.md)
- **Ver también**: [Configuración](11-CONFIGURACION.md) - Variables de entorno
- **Ver también**: [Mantenimiento](15-MANTENIMIENTO.md) - Tareas de mantenimiento

---

[← Volver al Índice](README.md) | [Siguiente: Testing →](13-TESTING.md)
