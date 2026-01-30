# 17. Dependencias

## 17.1 Dependencias PHP (Composer)

### 17.1.1 Dependencias de Producción

Las siguientes dependencias son requeridas para ejecutar ABCmio en producción:

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **php** | ^7.1.3 | Lenguaje de programación |
| **laravel/framework** | 5.8.* | Framework PHP MVC |
| **laravel/tinker** | ^1.0 | REPL para Laravel |

#### Autenticación y Seguridad

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **anhskohbo/no-captcha** | ^3.4 | Google reCAPTCHA integración |
| **fideloper/proxy** | ^4.0 | Proxy de confianza para load balancers |

#### Base de Datos y Modelos

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **tucker-eric/eloquentfilter** | ^2.4 | Filtrado avanzado de modelos Eloquent |
| **cviebrock/eloquent-sluggable** | 4.8.1 | Generación automática de slugs |
| **spatie/laravel-sluggable** | ^2.2 | Generación de slugs (alternativo) |

#### Multimedia y Archivos

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **spatie/laravel-medialibrary** | ^7.0.0 | Gestión de archivos multimedia |
| **intervention/image** | ^2.5 | Manipulación y procesamiento de imágenes |
| **spatie/laravel-image-optimizer** | ^1.3 | Optimización automática de imágenes |
| **league/flysystem-aws-s3-v3** | ^1.0 | Integración con AWS S3 |
| **aws/aws-sdk-php** | ~3.0 | SDK de AWS para PHP |

#### Pagos y Transacciones

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **paypal/rest-api-sdk-php** | ^1.14 | API de PayPal para procesar pagos |

#### Comunicación y Mensajería

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **inani/messager** | ^1.0 | Sistema de mensajería (futuro) |

#### Utilidades

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **guzzlehttp/guzzle** | ^6.3 | Cliente HTTP para APIs externas |
| **jorenvanhocht/laravel-share** | ^3.1 | Compartir en redes sociales |
| **php-parallel-lint/php-console-color** | ^0.2.0 | Colores en consola PHP |
| **php-parallel-lint/php-console-highlighter** | ^0.4.0 | Resaltado de sintaxis en consola |

#### Estadísticas y Tracking

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **shetabit/visitor** | ^2.2.0 | Tracking de visitas a propiedades |

#### DataTables

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **yajra/laravel-datatables-oracle** | ^9.7 | DataTables para panel admin |

#### Debugging y Desarrollo

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **laravel/telescope** | ^2.1 | Debugging y monitoring (dev/staging) |

### 17.1.2 Dependencias de Desarrollo

Paquetes utilizados solo en entorno de desarrollo:

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **barryvdh/laravel-debugbar** | ^3.2 | Barra de debugging para Laravel |
| **beyondcode/laravel-dump-server** | ^1.0 | Servidor para dumps de Laravel |
| **filp/whoops** | ^2.0 | Manejo de errores mejorado |
| **fzaninotto/faker** | ^1.4 | Generador de datos falsos |
| **mockery/mockery** | ^1.0 | Mocking para tests |
| **nunomaduro/collision** | ^3.0 | Manejo de errores CLI |
| **phpunit/phpunit** | ^7.5 | Framework de testing |

### 17.1.3 Instalación de Dependencias PHP

```bash
# Producción
composer install --no-dev --optimize-autoloader

# Desarrollo
composer install

# Actualizar dependencia específica
composer update spatie/laravel-medialibrary

# Agregar nueva dependencia
composer require vendor/package

# Remover dependencia
composer remove vendor/package
```

## 17.2 Dependencias JavaScript (NPM)

### 17.2.1 DevDependencies (Desarrollo)

Herramientas de compilación y frameworks:

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **@babel/preset-react** | ^7.0.0 | Preset de Babel para React |
| **axios** | ^0.19.0 | Cliente HTTP para JavaScript |
| **bootstrap** | ^4.6.1 | Framework CSS |
| **cross-env** | ^5.1 | Scripts multiplataforma |
| **jquery** | ^3.3.1 | Biblioteca JavaScript |
| **jquery-ui** | ^1.12.1 | Componentes UI para jQuery |
| **laravel-mix** | ^4.0.7 | Build tool (wrapper de Webpack) |
| **lodash** | ^4.17.15 | Utilidades JavaScript |
| **popper.js** | ^1.14.7 | Motor de posicionamiento para tooltips |
| **react** | ^16.2.0 | Biblioteca para interfaces de usuario |
| **react-dom** | ^16.2.0 | DOM de React |
| **resolve-url-loader** | ^2.3.1 | Resolver URLs relativas en Sass |
| **sass** | ^1.15.2 | Preprocesador CSS |
| **sass-loader** | ^7.1.0 | Loader de Sass para Webpack |
| **summernote** | ^0.8.1 | Editor WYSIWYG |

### 17.2.2 Dependencies (Producción)

Librerías utilizadas en runtime:

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **@fortawesome/fontawesome-free** | ^5.15.4 | Iconos Font Awesome |
| **bootstrap-select** | ^1.13.18 | Selectores mejorados para Bootstrap |
| **datatables.net-dt** | ^1.10.20 | Tablas de datos interactivas |
| **dropify** | ^0.2.2 | Input de archivo mejorado |
| **dropzone** | ^5.9.3 | Subida de archivos drag-and-drop |
| **get-google-fonts** | ^1.2.2 | Descargar fuentes de Google |
| **lightbox2** | ^2.11.1 | Galería de imágenes lightbox |

### 17.2.3 Instalación de Dependencias JavaScript

```bash
# Producción
npm install --production

# Desarrollo
npm install

# Actualizar dependencia específica
npm update bootstrap

# Agregar nueva dependencia
npm install --save package-name

# Agregar dependencia de desarrollo
npm install --save-dev package-name

# Remover dependencia
npm uninstall package-name
```

### 17.2.4 Scripts NPM

```json
{
  "scripts": {
    "dev": "npm run development",
    "development": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
    "watch": "npm run development -- --watch",
    "watch-poll": "npm run watch -- --watch-poll",
    "hot": "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --config=node_modules/laravel-mix/setup/webpack.config.js",
    "prod": "npm run production",
    "production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js"
  }
}
```

**Uso:**
```bash
npm run dev          # Compilar para desarrollo
npm run watch        # Compilar y observar cambios
npm run hot          # Hot module replacement
npm run production   # Compilar para producción (optimizado)
```

## 17.3 Extensiones PHP Requeridas

```bash
# Verificar extensiones instaladas
php -m

# Extensiones requeridas:
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD
- cURL
- Zip
```

### 17.3.1 Instalación de Extensiones

**Ubuntu/Debian:**
```bash
sudo apt-get install \
    php7.4-mbstring \
    php7.4-xml \
    php7.4-zip \
    php7.4-bcmath \
    php7.4-json \
    php7.4-mysql \
    php7.4-gd \
    php7.4-curl \
    php7.4-tokenizer \
    php7.4-ctype \
    php7.4-fileinfo
```

**CentOS/RHEL:**
```bash
sudo yum install \
    php74-mbstring \
    php74-xml \
    php74-zip \
    php74-bcmath \
    php74-json \
    php74-mysqlnd \
    php74-gd \
    php74-curl
```

## 17.4 Requisitos del Sistema

### 17.4.1 Software Base

| Software | Versión Mínima | Versión Recomendada |
|----------|----------------|---------------------|
| **PHP** | 7.1.3 | 7.4 |
| **MySQL** | 5.7 | 8.0 |
| **MariaDB** | 10.2 | 10.5 |
| **Nginx** | 1.14 | 1.18+ |
| **Apache** | 2.4 | 2.4+ |
| **Composer** | 1.0 | 2.x |
| **Node.js** | 12.x | 14.x+ |
| **NPM** | 6.x | 7.x+ |

### 17.4.2 Herramientas Opcionales

| Herramienta | Propósito |
|-------------|-----------|
| **Redis** | Cache y colas |
| **Supervisor** | Gestión de queue workers |
| **Certbot** | Certificados SSL gratuitos |
| **Git** | Control de versiones |

## 17.5 Actualización de Dependencias

### 17.5.1 Verificar Actualizaciones Disponibles

```bash
# PHP
composer outdated

# JavaScript
npm outdated

# Ver dependencias obsoletas
composer show --outdated --direct

# Ver dependencias con vulnerabilidades
composer audit
npm audit
```

### 17.5.2 Actualizar Dependencias de Forma Segura

```bash
# Actualizar paquete específico (patch/minor)
composer update vendor/package --with-dependencies

# Actualizar todos los paquetes (cuidado!)
composer update

# Para JavaScript
npm update package-name

# Actualizar todas las dependencias JS
npm update
```

### 17.5.3 Estrategia de Actualización

**1. Revisar Changelog:**
- Leer notas de versión
- Identificar breaking changes
- Planificar migración si es necesario

**2. Actualizar en Desarrollo:**
```bash
# Crear rama
git checkout -b update-dependencies

# Actualizar
composer update
npm update

# Probar
composer test
npm run dev
```

**3. Verificar Tests:**
```bash
vendor/bin/phpunit
```

**4. Desplegar en Staging:**
```bash
# Probar en entorno similar a producción
```

**5. Desplegar en Producción:**
```bash
# Solo después de pruebas exitosas
```

## 17.6 Gestión de Vulnerabilidades

### 17.6.1 Escaneo de Seguridad

```bash
# PHP
composer audit

# JavaScript
npm audit

# Reparar vulnerabilidades automáticamente
npm audit fix

# Forzar actualización
npm audit fix --force
```

### 17.6.2 Dependencias con Vulnerabilidades Conocidas

Consultar regularmente:
- [CVE Details](https://www.cvedetails.com/)
- [Snyk Vulnerability Database](https://snyk.io/vuln/)
- [GitHub Security Advisories](https://github.com/advisories)

### 17.6.3 Política de Actualización

**Actualizaciones de Seguridad:**
- Aplicar inmediatamente
- Probar en staging
- Desplegar en producción ASAP

**Actualizaciones Menores:**
- Revisar semanalmente
- Actualizar mensualmente
- Probar exhaustivamente

**Actualizaciones Mayores:**
- Revisar breaking changes
- Planificar migración
- Actualizar en ventana de mantenimiento

## 17.7 Compatibilidad de Versiones

### 17.7.1 Matriz de Compatibilidad

| Laravel | PHP | MySQL | Nginx |
|---------|-----|-------|-------|
| 5.8 | 7.1.3-7.4 | 5.7+ | 1.14+ |

### 17.7.2 Incompatibilidades Conocidas

**PHP 8.0+:**
- Laravel 5.8 no es compatible con PHP 8.0
- Requiere actualización a Laravel 8.x

**MySQL 8.0:**
- Cambio en autenticación por defecto
- Configurar: `default_authentication_plugin=mysql_native_password`

## Documentos Relacionados

- **Anterior**: [Glosario](16-GLOSARIO.md)
- **Siguiente**: [Contribución](18-CONTRIBUCION.md)
- **Ver también**: [Despliegue](12-DESPLIEGUE.md) - Instalación de dependencias
- **Ver también**: [Mantenimiento](15-MANTENIMIENTO.md) - Actualización de dependencias

---

[← Volver al Índice](README.md) | [Siguiente: Contribución →](18-CONTRIBUCION.md)
