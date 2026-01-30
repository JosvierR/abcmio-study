# 1. Introducción

## 1.1 Descripción del Proyecto

**ABCmio** es una plataforma web de listado y gestión de propiedades inmobiliarias que permite a usuarios publicar, buscar y gestionar anuncios de propiedades (venta, alquiler, servicios, etc.). La plataforma funciona bajo un modelo basado en créditos donde los usuarios pueden comprar créditos para publicar y promover sus anuncios.

### Características Principales

- **Gestión de Propiedades**: Publicación, edición y eliminación de anuncios inmobiliarios
- **Sistema de Créditos**: Modelo de negocio basado en créditos para publicaciones
- **Búsqueda Avanzada**: Filtrado por ubicación, categoría, precio y más
- **Multiidioma**: Soporte para Español, Inglés y Francés
- **Gestión de Multimedia**: Subida y optimización de imágenes
- **Sistema de Reportes**: Reportar contenido inapropiado
- **Integración de Pagos**: PayPal para compra de créditos
- **Panel de Administración**: Gestión completa del sistema

## 1.2 Objetivos del Sistema

### Problema que Resuelve

ABCmio resuelve la necesidad de:

1. **Conectar oferta y demanda**: Facilitar la conexión entre propietarios/agentes y potenciales compradores/inquilinos
2. **Centralizar información**: Proveer una plataforma única para múltiples tipos de propiedades y servicios inmobiliarios
3. **Gestión eficiente**: Permitir a usuarios gestionar sus anuncios de forma autónoma
4. **Monetización justa**: Sistema de créditos que permite un modelo de negocio equilibrado
5. **Accesibilidad multilingüe**: Servir a audiencias de diferentes idiomas

### Objetivos de Negocio

- Proveer una plataforma escalable para listados inmobiliarios
- Generar ingresos mediante venta de créditos
- Facilitar la interacción entre usuarios y propiedades
- Mantener la calidad del contenido mediante sistema de reportes
- Ofrecer una experiencia de usuario fluida y moderna

## 1.3 Audiencia Objetivo

### Usuarios Finales

1. **Propietarios Individuales**: Personas que desean publicar propiedades en venta o alquiler
2. **Agentes Inmobiliarios**: Profesionales que gestionan múltiples propiedades
3. **Compradores/Inquilinos**: Usuarios que buscan propiedades
4. **Visitantes**: Usuarios sin cuenta que navegan la plataforma

### Equipo Técnico

1. **Desarrolladores Backend**: PHP/Laravel developers
2. **Desarrolladores Frontend**: React/Vue.js developers
3. **Administradores del Sistema**: Personal que gestiona contenido y usuarios
4. **DevOps**: Equipo encargado del despliegue y mantenimiento

## 1.4 Alcance Funcional

### Qué Hace el Sistema

✅ **Gestión de Usuarios**
- Registro y autenticación con verificación de email
- Perfil de usuario personalizable
- Sistema de roles (usuario, administrador)
- Gestión de créditos propios

✅ **Gestión de Propiedades**
- Crear, editar y eliminar anuncios
- Publicar/despublicar propiedades
- Extender período de publicación
- Galería de imágenes con múltiples fotos
- Categorización y geolocalización

✅ **Sistema de Créditos**
- Compra de créditos mediante PayPal
- Transferencia de créditos entre usuarios
- Consumo de créditos para publicaciones
- Historial de transacciones

✅ **Búsqueda y Filtrado**
- Búsqueda por texto
- Filtros por país, ciudad, categoría
- Ordenamiento de resultados
- Paginación de resultados

✅ **Internacionalización**
- Soporte para Español, Inglés y Francés
- Cambio dinámico de idioma
- URLs localizadas

✅ **Administración**
- Panel de administración completo
- Gestión de países, ciudades, categorías
- Revisión de reportes
- Gestión de usuarios y propiedades

### Qué NO Hace el Sistema

❌ **Fuera del Alcance Actual**
- Sistema de mensajería directa entre usuarios
- Integración con redes sociales para publicación automática
- Sistema de citas o tours virtuales
- Integración con servicios de valoración automática
- Sistema de subastas
- App móvil nativa (solo web responsive)
- Integración con múltiples pasarelas de pago (solo PayPal)
- Sistema de reviews/calificaciones
- Comparador de propiedades

## 1.5 Stack Tecnológico

### Backend

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **PHP** | 7.1.3+ | Lenguaje principal del backend |
| **Laravel** | 5.8.* | Framework PHP MVC |
| **MySQL** | 5.7+ | Sistema de base de datos |
| **Composer** | 2.x | Gestor de dependencias PHP |

### Frontend

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **Blade** | 5.8 | Motor de plantillas de Laravel |
| **React** | 16.2.0 | Componentes interactivos UI |
| **Vue.js** | 2.x | Componentes reactivos |
| **Bootstrap** | 4.6.1 | Framework CSS |
| **jQuery** | 3.3.1 | Manipulación DOM y AJAX |
| **Sass** | 1.15.2 | Preprocesador CSS |
| **Laravel Mix** | 4.0.7 | Build tool (webpack wrapper) |

### Librerías y Paquetes Principales

#### PHP (Composer)
- **spatie/laravel-medialibrary**: Gestión de archivos multimedia
- **intervention/image**: Procesamiento de imágenes
- **paypal/rest-api-sdk-php**: Integración con PayPal
- **tucker-eric/eloquentfilter**: Filtrado avanzado de modelos
- **yajra/laravel-datatables-oracle**: DataTables para Laravel
- **laravel/telescope**: Debugging y monitoring
- **shetabit/visitor**: Tracking de visitas

#### JavaScript (NPM)
- **dropzone**: Subida de archivos drag & drop
- **summernote**: Editor WYSIWYG
- **lightbox2**: Galería de imágenes
- **datatables.net**: Tablas interactivas
- **bootstrap-select**: Selectores mejorados

### Servicios Externos

- **PayPal**: Procesamiento de pagos
- **AWS S3** (opcional): Almacenamiento de archivos
- **Google reCAPTCHA**: Protección contra bots
- **Mailtrap/SMTP**: Envío de emails

### Herramientas de Desarrollo

- **DDEV**: Entorno de desarrollo Docker
- **Git**: Control de versiones
- **NPM**: Gestor de paquetes JavaScript
- **Laravel Debugbar**: Debugging en desarrollo
- **Laravel Telescope**: Monitoring de requests y queries

## 1.6 Requisitos del Sistema

### Requisitos de Hardware (Producción)

**Mínimos**
- CPU: 2 cores
- RAM: 2 GB
- Disco: 10 GB SSD
- Ancho de banda: 100 Mbps

**Recomendados**
- CPU: 4+ cores
- RAM: 4+ GB
- Disco: 20+ GB SSD
- Ancho de banda: 1 Gbps

### Requisitos de Software

#### Servidor de Producción

**Sistema Operativo**
- Linux (Ubuntu 18.04+, CentOS 7+, Debian 9+)
- Windows Server (compatible pero no recomendado)

**Servicios Requeridos**
- PHP 7.1.3 o superior (7.4 recomendado)
- MySQL 5.7+ o MariaDB 10.2+
- Nginx o Apache 2.4+
- Composer 2.x
- Node.js 12.x+ y NPM 6.x+

**Extensiones PHP Requeridas**
```
php-mbstring
php-xml
php-zip
php-bcmath
php-json
php-mysql
php-gd
php-curl
php-tokenizer
php-ctype
php-fileinfo
php-openssl
```

#### Entorno de Desarrollo

**DDEV (Recomendado)**
- Docker Desktop 20.10+
- DDEV 1.18+
- 8 GB RAM mínimo

**Sin DDEV**
- PHP 7.1.3+
- MySQL 5.7+
- Composer
- Node.js y NPM
- Git

### Dependencias Externas

**Obligatorias**
- Cuenta PayPal Business (para pagos)
- Servidor SMTP (para emails)

**Opcionales**
- Cuenta AWS S3 (para almacenamiento en la nube)
- Google reCAPTCHA keys

## 1.7 Arquitectura de Alto Nivel

```
┌─────────────────────────────────────────────────────────────┐
│                        USUARIOS                              │
│         (Navegadores, Apps, Clientes API)                    │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│                    CAPA WEB/API                              │
│         (Nginx/Apache, SSL, Load Balancer)                   │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│                 APLICACIÓN LARAVEL                           │
│  ┌──────────────┬──────────────┬─────────────────────────┐  │
│  │ Controllers  │  Middleware  │   Views (Blade)         │  │
│  └──────┬───────┴──────┬───────┴─────────────────────────┘  │
│         │              │                                     │
│  ┌──────▼───────┬──────▼───────┬─────────────────────────┐  │
│  │  Services    │  Validators  │   Repositories          │  │
│  └──────┬───────┴──────────────┴──────────┬──────────────┘  │
│         │                                  │                 │
│  ┌──────▼──────────────────────────────────▼──────────────┐  │
│  │              Eloquent ORM (Models)                     │  │
│  └──────────────────────┬─────────────────────────────────┘  │
└─────────────────────────┼─────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                    BASE DE DATOS                             │
│                    MySQL 5.7+                                │
└─────────────────────────────────────────────────────────────┘

                 Servicios Externos
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│    PayPal    │  │    AWS S3    │  │     SMTP     │
└──────────────┘  └──────────────┘  └──────────────┘
```

## 1.8 Convenciones y Estándares

### Código PHP
- **PSR-12**: Extended Coding Style Guide
- **PSR-4**: Autoloading Standard
- **Laravel Best Practices**: Convenciones del framework

### Código JavaScript
- **ES6+**: Modern JavaScript syntax
- **React Best Practices**: Para componentes React
- **Vue.js Style Guide**: Para componentes Vue

### Base de Datos
- **Nombres en plural**: Tablas en plural (users, properties)
- **Snake case**: Nombres de columnas (created_at, user_id)
- **Foreign keys**: nombre_tabla_id (country_id, user_id)

### Git
- **Commits descriptivos**: Mensajes claros en español o inglés
- **Feature branches**: Trabajo en ramas separadas
- **Pull requests**: Revisión de código antes de merge

## 1.9 Documentos Relacionados

- **Siguiente**: [Arquitectura](02-ARQUITECTURA.md) - Estructura detallada del sistema
- **Ver también**: [Despliegue](12-DESPLIEGUE.md) - Instalación y configuración
- **Referencia**: [Stack Tecnológico](17-DEPENDENCIAS.md) - Listado completo de dependencias

---

[← Volver al Índice](README.md) | [Siguiente: Arquitectura →](02-ARQUITECTURA.md)
