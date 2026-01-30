# 15. Mantenimiento

## 15.1 Introducción

Este documento describe las tareas de mantenimiento rutinarias, monitoreo, backup, y troubleshooting para mantener ABCmio funcionando de manera óptima.

## 15.2 Tareas de Mantenimiento Rutinarias

### 15.2.1 Diarias

**Verificar Estado del Servidor**
```bash
# Verificar servicios
sudo systemctl status nginx
sudo systemctl status php7.4-fpm
sudo systemctl status mysql
sudo systemctl status supervisor

# Verificar espacio en disco
df -h

# Verificar uso de memoria
free -h

# Verificar procesos
top
```

**Revisar Logs**
```bash
# Logs de Laravel
tail -n 100 /var/www/abcmio/storage/logs/laravel.log

# Logs de Nginx
tail -n 100 /var/log/nginx/abcmio-error.log

# Logs de PHP-FPM
tail -n 100 /var/log/php7.4-fpm.log

# Logs de MySQL
sudo tail -n 100 /var/log/mysql/error.log
```

**Verificar Queue Workers**
```bash
# Estado de workers
sudo supervisorctl status abcmio-worker:*

# Reiniciar si es necesario
sudo supervisorctl restart abcmio-worker:*
```

**Backup de Base de Datos**
```bash
# Ejecutar backup diario
/path/to/backup-db.sh
```

### 15.2.2 Semanales

**Limpiar Logs Antiguos**
```bash
# Limpiar logs de Laravel (mayores a 7 días)
find /var/www/abcmio/storage/logs -name "*.log" -mtime +7 -delete

# Limpiar logs de Nginx (mayores a 30 días)
find /var/log/nginx -name "*.log" -mtime +30 -delete
```

**Optimizar Base de Datos**
```bash
# Optimizar tablas
mysql -u root -p abcmio -e "OPTIMIZE TABLE properties, users, orders, media;"

# Analizar tablas
mysql -u root -p abcmio -e "ANALYZE TABLE properties, users, orders;"
```

**Limpiar Cache**
```bash
cd /var/www/abcmio

# Limpiar cache de aplicación
php artisan cache:clear

# Limpiar cache de vistas
php artisan view:clear

# Limpiar sesiones expiradas
php artisan session:gc
```

**Actualizar Dependencias**
```bash
# Verificar actualizaciones disponibles
composer outdated

# Actualizar dependencias menores
composer update --prefer-stable
```

### 15.2.3 Mensuales

**Auditoría de Seguridad**
```bash
# Verificar permisos de archivos
find /var/www/abcmio -type f -not -path "*/storage/*" -not -path "*/bootstrap/cache/*" -perm /o+w -ls

# Verificar usuarios con acceso
mysql -u root -p -e "SELECT User, Host FROM mysql.user;"

# Revisar intentos de login fallidos
grep "Failed login" /var/www/abcmio/storage/logs/laravel.log | tail -n 50
```

**Revisar Certificados SSL**
```bash
# Verificar expiración de certificados
sudo certbot certificates

# Renovar si es necesario
sudo certbot renew
```

**Análisis de Performance**
```bash
# Queries lentas
mysql -u root -p -e "SELECT * FROM mysql.slow_log ORDER BY query_time DESC LIMIT 10;"

# Tamaño de tablas
mysql -u root -p abcmio -e "
SELECT 
    table_name AS 'Table',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES 
WHERE table_schema = 'abcmio'
ORDER BY (data_length + index_length) DESC;"
```

**Backup Completo**
```bash
# Backup de archivos
/path/to/backup-files.sh

# Backup de base de datos
/path/to/backup-db.sh

# Verificar backups
ls -lh /var/backups/abcmio/
```

## 15.3 Monitoring

### 15.3.1 Monitoreo de Aplicación

**Health Check Endpoint**
```php
// routes/web.php
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        Cache::has('test');
        
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => [
                'database' => 'ok',
                'cache' => 'ok'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'error' => $e->getMessage()
        ], 503);
    }
});
```

**Script de Monitoreo**
```bash
#!/bin/bash
# monitor.sh

# Verificar endpoint
HEALTH=$(curl -s -o /dev/null -w "%{http_code}" https://abcmio.com/health)

if [ $HEALTH -ne 200 ]; then
    echo "ALERT: Health check failed with code $HEALTH"
    # Enviar alerta (email, Slack, etc.)
fi

# Verificar espacio en disco
DISK_USAGE=$(df / | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "ALERT: Disk usage is at ${DISK_USAGE}%"
fi

# Verificar memoria
MEM_USAGE=$(free | grep Mem | awk '{print ($3/$2) * 100.0}' | cut -d. -f1)
if [ $MEM_USAGE -gt 90 ]; then
    echo "ALERT: Memory usage is at ${MEM_USAGE}%"
fi

# Verificar workers
WORKERS=$(sudo supervisorctl status abcmio-worker:* | grep RUNNING | wc -l)
if [ $WORKERS -lt 2 ]; then
    echo "ALERT: Only $WORKERS workers running"
    sudo supervisorctl restart abcmio-worker:*
fi
```

**Cron para Monitoreo**
```bash
# Ejecutar cada 5 minutos
*/5 * * * * /path/to/monitor.sh >> /var/log/monitor.log 2>&1
```

### 15.3.2 Métricas Importantes

**Base de Datos**
```sql
-- Conexiones activas
SHOW PROCESSLIST;

-- Tamaño de base de datos
SELECT 
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'abcmio'
GROUP BY table_schema;

-- Tablas más grandes
SELECT 
    table_name,
    table_rows,
    ROUND(data_length / 1024 / 1024, 2) AS 'Data Size (MB)',
    ROUND(index_length / 1024 / 1024, 2) AS 'Index Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'abcmio'
ORDER BY data_length DESC
LIMIT 10;
```

**Aplicación**
```bash
# Propiedades activas
php artisan tinker
>>> Property::where('status', 'published')->count()

# Usuarios registrados
>>> User::count()

# Órdenes hoy
>>> Order::whereDate('created_at', today())->count()

# Créditos vendidos este mes
>>> Order::whereMonth('created_at', now()->month)->sum('credits')
```

### 15.3.3 Alertas

**Configurar Alertas por Email**
```php
// app/Console/Commands/MonitorCommand.php
public function handle()
{
    // Verificar propiedades por expirar en 3 días
    $expiring = Property::where('status', 'published')
                       ->whereBetween('expires_at', [
                           now(),
                           now()->addDays(3)
                       ])
                       ->count();
    
    if ($expiring > 100) {
        Mail::to('admin@abcmio.com')
            ->send(new AlertMail("$expiring propiedades expiran pronto"));
    }
    
    // Verificar errores en logs
    $errors = Log::whereDate('created_at', today())
                 ->where('level', 'error')
                 ->count();
    
    if ($errors > 50) {
        Mail::to('admin@abcmio.com')
            ->send(new AlertMail("$errors errores hoy"));
    }
}
```

## 15.4 Logs

### 15.4.1 Ubicación de Logs

```
/var/www/abcmio/storage/logs/
├── laravel.log              # Log principal de Laravel
├── laravel-2024-01-15.log   # Logs rotativos diarios
├── worker.log               # Queue workers
└── paypal.log               # Transacciones PayPal

/var/log/
├── nginx/
│   ├── abcmio-access.log
│   └── abcmio-error.log
├── php7.4-fpm.log
└── mysql/
    ├── error.log
    └── slow.log
```

### 15.4.2 Gestión de Logs

**Rotación de Logs**
```bash
# /etc/logrotate.d/abcmio
/var/www/abcmio/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
    postrotate
        /usr/sbin/service php7.4-fpm reload > /dev/null
    endscript
}
```

**Limpiar Logs Antiguos**
```bash
#!/bin/bash
# clean-logs.sh

# Limpiar logs de Laravel más antiguos de 30 días
find /var/www/abcmio/storage/logs -name "laravel-*.log" -mtime +30 -delete

# Limpiar logs de Nginx más antiguos de 60 días
find /var/log/nginx -name "*.log.*.gz" -mtime +60 -delete

echo "Logs limpiados: $(date)"
```

**Buscar Errores Comunes**
```bash
# Buscar errores de base de datos
grep -i "SQLSTATE" /var/www/abcmio/storage/logs/laravel.log

# Buscar errores 500
grep " 500 " /var/log/nginx/abcmio-access.log

# Buscar intentos de login fallidos
grep "Failed login" /var/www/abcmio/storage/logs/laravel.log

# Buscar errores de PayPal
grep "ERROR" /var/www/abcmio/storage/logs/paypal.log
```

### 15.4.3 Niveles de Log

```php
// config/logging.php
'channels' => [
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
],

// Uso
Log::emergency($message);  // Sistema inutilizable
Log::alert($message);      // Acción inmediata requerida
Log::critical($message);   // Condiciones críticas
Log::error($message);      // Errores que no requieren acción inmediata
Log::warning($message);    // Advertencias
Log::notice($message);     // Eventos normales pero significativos
Log::info($message);       // Información general
Log::debug($message);      // Información de debugging
```

## 15.5 Backup y Recuperación

### 15.5.1 Estrategia de Backup

**Backup Diario de Base de Datos**
```bash
#!/bin/bash
# /usr/local/bin/backup-db-daily.sh

BACKUP_DIR="/var/backups/abcmio/daily"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="abcmio"
DB_USER="abcmio_user"
DB_PASS="password"

mkdir -p $BACKUP_DIR

# Backup con compresión
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Mantener solo últimos 7 días
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +7 -delete

# Log
echo "Backup completado: db_$DATE.sql.gz ($(date))" >> /var/log/backup.log
```

**Backup Semanal Completo**
```bash
#!/bin/bash
# /usr/local/bin/backup-weekly.sh

BACKUP_DIR="/var/backups/abcmio/weekly"
DATE=$(date +%Y%m%d)
APP_DIR="/var/www/abcmio"

mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup de storage
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C $APP_DIR storage

# Backup de .env
cp $APP_DIR/.env $BACKUP_DIR/env_$DATE

# Mantener solo últimas 4 semanas
find $BACKUP_DIR -name "*_*.sql.gz" -mtime +28 -delete
find $BACKUP_DIR -name "*_*.tar.gz" -mtime +28 -delete

echo "Backup semanal completado: $DATE ($(date))" >> /var/log/backup.log
```

**Cron Jobs de Backup**
```bash
# Diario a las 2 AM
0 2 * * * /usr/local/bin/backup-db-daily.sh

# Semanal los domingos a las 3 AM
0 3 * * 0 /usr/local/bin/backup-weekly.sh
```

### 15.5.2 Procedimientos de Recuperación

**Restaurar Base de Datos**
```bash
# Desde backup más reciente
LATEST_BACKUP=$(ls -t /var/backups/abcmio/daily/db_*.sql.gz | head -1)

# Descomprimir y restaurar
gunzip < $LATEST_BACKUP | mysql -u root -p abcmio

# Verificar
mysql -u root -p abcmio -e "SELECT COUNT(*) FROM properties;"
```

**Restaurar Archivos de Storage**
```bash
# Desde backup semanal
LATEST_STORAGE=$(ls -t /var/backups/abcmio/weekly/storage_*.tar.gz | head -1)

# Extraer
cd /var/www/abcmio
tar -xzf $LATEST_STORAGE

# Restaurar permisos
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage
```

**Restaurar .env**
```bash
LATEST_ENV=$(ls -t /var/backups/abcmio/weekly/env_* | head -1)
cp $LATEST_ENV /var/www/abcmio/.env
```

### 15.5.3 Backup Offsite

**Sincronizar con S3**
```bash
#!/bin/bash
# sync-to-s3.sh

AWS_BUCKET="abcmio-backups"
BACKUP_DIR="/var/backups/abcmio"

# Sincronizar backups a S3
aws s3 sync $BACKUP_DIR s3://$AWS_BUCKET/backups/ \
    --storage-class STANDARD_IA \
    --exclude "*" \
    --include "*.sql.gz" \
    --include "*.tar.gz"

echo "Backups sincronizados a S3: $(date)" >> /var/log/s3-sync.log
```

## 15.6 Optimización de Performance

### 15.6.1 Optimización de Base de Datos

**Índices Importantes**
```sql
-- Verificar índices faltantes
SELECT * FROM sys.schema_tables_with_full_table_scans;

-- Agregar índices según uso
CREATE INDEX idx_properties_status_expires ON properties(status, expires_at);
CREATE INDEX idx_properties_published ON properties(published_at);
CREATE INDEX idx_properties_user ON properties(user_id);
```

**Query Cache**
```ini
# /etc/mysql/my.cnf
[mysqld]
query_cache_type = 1
query_cache_size = 128M
query_cache_limit = 2M
```

**Optimizar Configuración MySQL**
```ini
# /etc/mysql/my.cnf
[mysqld]
innodb_buffer_pool_size = 2G
innodb_log_file_size = 512M
max_connections = 200
```

### 15.6.2 Optimización de PHP

**OPcache**
```ini
# /etc/php/7.4/fpm/conf.d/10-opcache.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

**PHP-FPM Pool**
```ini
# /etc/php/7.4/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

### 15.6.3 Optimización de Nginx

**Gzip Compression**
```nginx
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_types text/plain text/css text/xml text/javascript 
           application/x-javascript application/xml+rss application/json;
```

**Browser Caching**
```nginx
location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

**FastCGI Cache**
```nginx
fastcgi_cache_path /var/cache/nginx levels=1:2 keys_zone=abcmio:100m inactive=60m;

location ~ \.php$ {
    fastcgi_cache abcmio;
    fastcgi_cache_valid 200 60m;
    add_header X-Cache-Status $upstream_cache_status;
}
```

## 15.7 Troubleshooting

### 15.7.1 Problemas Comunes

**Error 500 - Internal Server Error**
```bash
# Verificar logs
tail -f /var/www/abcmio/storage/logs/laravel.log
tail -f /var/log/nginx/abcmio-error.log

# Causas comunes:
1. Permisos incorrectos en storage/
2. Error en código PHP
3. .env mal configurado
4. Composer autoload desactualizado

# Soluciones:
sudo chown -R www-data:www-data storage bootstrap/cache
composer dump-autoload
php artisan config:clear
```

**Error 503 - Service Unavailable**
```bash
# Verificar PHP-FPM
sudo systemctl status php7.4-fpm

# Reiniciar si es necesario
sudo systemctl restart php7.4-fpm

# Verificar sockets
sudo ls -la /var/run/php/

# Verificar límites de conexión
grep "max_children" /etc/php/7.4/fpm/pool.d/www.conf
```

**Queue Workers No Funcionan**
```bash
# Verificar supervisor
sudo supervisorctl status abcmio-worker:*

# Ver logs de worker
tail -f /var/www/abcmio/storage/logs/worker.log

# Reiniciar workers
sudo supervisorctl restart abcmio-worker:*
```

**Base de Datos Lenta**
```bash
# Verificar queries lentas
mysql -u root -p -e "SHOW PROCESSLIST;"

# Ver slow query log
tail -f /var/log/mysql/slow.log

# Optimizar tablas
mysql -u root -p abcmio -e "OPTIMIZE TABLE properties, users;"
```

## Documentos Relacionados

- **Anterior**: [Flujos de Trabajo](14-FLUJOS-DE-TRABAJO.md)
- **Siguiente**: [Glosario](16-GLOSARIO.md)
- **Ver también**: [Despliegue](12-DESPLIEGUE.md) - Configuración inicial
- **Ver también**: [Configuración](11-CONFIGURACION.md) - Variables de entorno

---

[← Volver al Índice](README.md) | [Siguiente: Glosario →](16-GLOSARIO.md)
