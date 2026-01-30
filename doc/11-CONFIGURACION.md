# 11. Configuración

## 11.1 Variables de Entorno

### 11.1.1 Archivo .env

El archivo `.env` contiene todas las variables de configuración sensibles y específicas del entorno.

**Estructura Completa:**

```env
# Aplicación
APP_NAME=ABCmio
APP_ENV=local
APP_KEY=base64:generated_key_here
APP_DEBUG=true
APP_URL=http://localhost

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=abcmio
DB_USERNAME=root
DB_PASSWORD=secret

# Broadcasting
BROADCAST_DRIVER=log

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@abcmio.com
MAIL_FROM_NAME="${APP_NAME}"

# AWS S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_URL=

# Media Library
MEDIA_DISK=public
FILESYSTEM_DRIVER=public

# PayPal
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=
PAYPAL_SECRET=

# Google reCAPTCHA
NOCAPTCHA_SECRET=
NOCAPTCHA_SITEKEY=

# Laravel Telescope
TELESCOPE_ENABLED=true
TELESCOPE_REQUESTS_WATCHER=true
TELESCOPE_QUERIES_WATCHER=true
TELESCOPE_LOG_WATCHER=true

# Pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 11.1.2 Variables por Entorno

**Desarrollo Local (.env.local):**
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_HOST=127.0.0.1
DB_DATABASE=abcmio_dev

MAIL_MAILER=log
PAYPAL_MODE=sandbox
TELESCOPE_ENABLED=true
```

**Desarrollo DDEV (.env.ddev):**
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=https://abcmio.ddev.site

DB_HOST=db
DB_PORT=3306
DB_DATABASE=db
DB_USERNAME=db
DB_PASSWORD=db

MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025

TELESCOPE_ENABLED=true
```

**Staging (.env.staging):**
```env
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.abcmio.com

DB_HOST=staging-db-host
DB_DATABASE=abcmio_staging

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net

PAYPAL_MODE=sandbox
TELESCOPE_ENABLED=true

FILESYSTEM_DRIVER=s3
MEDIA_DISK=s3
```

**Producción (.env.production):**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://abcmio.com

DB_HOST=production-db-host
DB_DATABASE=abcmio_production

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net

PAYPAL_MODE=live
TELESCOPE_ENABLED=false

FILESYSTEM_DRIVER=s3
MEDIA_DISK=s3

SESSION_SECURE_COOKIE=true
```

## 11.2 Archivos de Configuración

### 11.2.1 config/app.php

```php
return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'es',
    'fallback_locale' => 'es',
    'faker_locale' => 'es_ES',
    
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    
    'providers' => [
        // Laravel Service Providers
        Illuminate\Auth\AuthServiceProvider::class,
        // ...
        
        // Package Service Providers
        Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
        Shetabit\Visitor\Provider\VisitorServiceProvider::class,
        
        // Application Service Providers
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ],
    
    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        // ...
    ],
];
```

### 11.2.2 config/database.php

```php
return [
    'default' => env('DB_CONNECTION', 'mysql'),
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],
    
    'migrations' => 'migrations',
];
```

### 11.2.3 config/filesystems.php

```php
return [
    'default' => env('FILESYSTEM_DRIVER', 'local'),
    'cloud' => env('FILESYSTEM_CLOUD', 's3'),
    
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],
        
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],
    ],
];
```

### 11.2.4 config/mail.php

```php
return [
    'default' => env('MAIL_MAILER', 'smtp'),
    
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
        ],
        
        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],
    ],
    
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],
];
```

### 11.2.5 config/paypal.php

```php
return [
    'client_id' => env('PAYPAL_CLIENT_ID',''),
    'secret' => env('PAYPAL_SECRET',''),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
```

### 11.2.6 config/medialibrary.php

```php
return [
    'disk_name' => env('MEDIA_DISK', 'public'),
    'max_file_size' => 1024 * 1024 * 10, // 10MB
    'queue_name' => '',
    'media_model' => Spatie\MediaLibrary\Models\Media::class,
    
    's3' => [
        'domain' => 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com',
    ],
    
    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
            'ACL' => 'public-read',
        ],
    ],
    
    'path_generator' => App\Generators\PropertyCustomPathGenerator::class,
];
```

### 11.2.7 config/telescope.php

```php
return [
    'enabled' => env('TELESCOPE_ENABLED', true),
    'path' => 'telescope',
    
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],
    
    'watchers' => [
        Watchers\RequestWatcher::class => [
            'enabled' => env('TELESCOPE_REQUESTS_WATCHER', true),
        ],
        Watchers\QueryWatcher::class => [
            'enabled' => env('TELESCOPE_QUERIES_WATCHER', true),
            'slow' => 100,
        ],
        Watchers\LogWatcher::class => env('TELESCOPE_LOG_WATCHER', true),
        Watchers\ExceptionWatcher::class => env('TELESCOPE_EXCEPTION_WATCHER', true),
    ],
];
```

### 11.2.8 config/session.php

```php
return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('framework/sessions'),
    'connection' => null,
    'table' => 'sessions',
    'store' => null,
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),
    'secure' => env('SESSION_SECURE_COOKIE', false),
    'http_only' => true,
    'same_site' => 'lax',
];
```

## 11.3 Servicios Externos

### 11.3.1 PayPal

**Modo Sandbox (Desarrollo):**
```env
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_sandbox_client_id
PAYPAL_SECRET=your_sandbox_secret
```

**Modo Live (Producción):**
```env
PAYPAL_MODE=live
PAYPAL_CLIENT_ID=your_live_client_id
PAYPAL_SECRET=your_live_secret
```

**Obtener Credenciales:**
1. Crear cuenta en https://developer.paypal.com
2. Ir a "My Apps & Credentials"
3. Crear nueva app
4. Copiar Client ID y Secret

### 11.3.2 AWS S3

```env
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=wJalrXUtn...
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=abcmio-media
AWS_URL=https://abcmio-media.s3.amazonaws.com

# Usar S3 para media
MEDIA_DISK=s3
FILESYSTEM_DRIVER=s3
```

**Configurar Bucket:**
1. Crear bucket en AWS S3
2. Configurar CORS:
```json
[
    {
        "AllowedHeaders": ["*"],
        "AllowedMethods": ["GET", "HEAD", "PUT", "POST", "DELETE"],
        "AllowedOrigins": ["https://abcmio.com"],
        "ExposeHeaders": []
    }
]
```
3. Configurar política de acceso público para lectura

### 11.3.3 Google reCAPTCHA

```env
NOCAPTCHA_SECRET=6LeXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
NOCAPTCHA_SITEKEY=6LeXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```

**Obtener Claves:**
1. Ir a https://www.google.com/recaptcha/admin
2. Registrar nuevo sitio
3. Seleccionar reCAPTCHA v2 o v3
4. Agregar dominios permitidos
5. Copiar Site Key y Secret Key

### 11.3.4 Email (SendGrid ejemplo)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxxxxxxxxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@abcmio.com
MAIL_FROM_NAME="ABCmio"
```

## 11.4 Configuración de Cache

### 11.4.1 Drivers de Cache

**File (Desarrollo):**
```env
CACHE_DRIVER=file
```

**Redis (Producción):**
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Memcached:**
```env
CACHE_DRIVER=memcached
MEMCACHED_HOST=127.0.0.1
MEMCACHED_PORT=11211
```

### 11.4.2 Configurar Redis

```php
// config/database.php
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    
    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
    ],
    
    'cache' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_CACHE_DB', '1'),
    ],
],
```

## 11.5 Configuración de Queue

### 11.5.1 Drivers de Queue

**Sync (Desarrollo):**
```env
QUEUE_CONNECTION=sync
```

**Database:**
```env
QUEUE_CONNECTION=database
```

**Redis (Producción):**
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 11.5.2 Configuración de Queues

```php
// config/queue.php
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
    ],
    
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

## 11.6 Optimización

### 11.6.1 Comandos de Optimización

```bash
# Cache de configuración
php artisan config:cache

# Cache de rutas
php artisan route:cache

# Cache de vistas
php artisan view:cache

# Autoload optimizado
composer dump-autoload -o

# Limpiar todos los caches
php artisan optimize:clear

# Optimizar para producción
php artisan optimize
```

### 11.6.2 Config Cache en Producción

```bash
# Antes de desplegar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Después de cambios en .env o config
php artisan config:clear
php artisan config:cache
```

## 11.7 Logging

### 11.7.1 Configuración de Logs

```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
        'ignore_exceptions' => false,
    ],
    
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],
    
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
    
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'ABCmio Logger',
        'emoji' => ':boom:',
        'level' => env('LOG_LEVEL', 'critical'),
    ],
],
```

### 11.7.2 Variables de Log

```env
LOG_CHANNEL=stack
LOG_LEVEL=debug
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/...
```

## 11.8 Múltiples Entornos

### 11.8.1 Estructura de Archivos

```
/
├── .env                    # Producción
├── .env.example           # Template
├── .env.local             # Desarrollo local
├── .env.ddev              # DDEV
├── .env.staging           # Staging
└── .env.testing           # Testing
```

### 11.8.2 Cargar Entorno Específico

```bash
# Desarrollo local
cp .env.local .env

# DDEV
cp .env.ddev .env

# Staging
cp .env.staging .env

# Testing
cp .env.testing .env
```

## 11.9 Verificación de Configuración

### 11.9.1 Comando de Verificación

```bash
# Ver configuración actual
php artisan config:show app
php artisan config:show database
php artisan config:show mail

# Ver todas las variables de entorno
php artisan tinker
> config('app')
> config('database.connections.mysql')
> env('APP_ENV')
```

### 11.9.2 Script de Verificación

```bash
#!/bin/bash
# verify-config.sh

echo "Verificando configuración..."

# Verificar APP_KEY
if grep -q "APP_KEY=base64:" .env; then
    echo "✓ APP_KEY configurada"
else
    echo "✗ APP_KEY no configurada"
    php artisan key:generate
fi

# Verificar permisos
if [ -w storage/ ] && [ -w bootstrap/cache/ ]; then
    echo "✓ Permisos de escritura OK"
else
    echo "✗ Permisos incorrectos"
    chmod -R 775 storage bootstrap/cache
fi

# Verificar conexión a base de datos
php artisan migrate:status > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✓ Conexión a base de datos OK"
else
    echo "✗ Error en conexión a base de datos"
fi

echo "Verificación completa."
```

## Documentos Relacionados

- **Anterior**: [Seguridad](10-SEGURIDAD.md)
- **Siguiente**: [Despliegue](12-DESPLIEGUE.md)
- **Ver también**: [Integraciones](08-INTEGRACIONES.md) - Configuración de servicios externos
- **Ver también**: [Mantenimiento](15-MANTENIMIENTO.md) - Optimización y monitoreo

---

[← Volver al Índice](README.md) | [Siguiente: Despliegue →](12-DESPLIEGUE.md)
