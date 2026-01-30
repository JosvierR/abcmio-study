# 3. Base de Datos

## 3.1 Esquema de Base de Datos

ABCmio utiliza MySQL 5.7+ como sistema de gestión de base de datos. El esquema está diseñado para soportar un sistema de listados de propiedades con gestión de usuarios, créditos, y multimedia.

### 3.1.1 Diagrama de Relaciones (ER)

```
┌─────────────┐        ┌──────────────┐        ┌─────────────┐
│   users     │───┐    │  properties  │────────│ categories  │
│             │   │    │              │        │             │
│ id (PK)     │   │    │ id (PK)      │        │ id (PK)     │
│ name        │   │    │ title        │        │ name        │
│ email       │   │    │ slug         │        │ slug        │
│ password    │   │    │ user_id (FK) │        │ parent_id   │
│ credits     │   │    │ category_id  │        │ is_free     │
│ type        │   │    │ country_id   │        └─────────────┘
│ country_id  │   │    │ city         │
└─────┬───────┘   │    │ is_public    │        ┌─────────────┐
      │           │    │ status       │        │  countries  │
      │           └────│ description  │────────│             │
      │                │ price        │        │ id (PK)     │
      │                │ phone        │        │ name        │
      │                │ expire_date  │        │ code        │
      │                └──────┬───────┘        └──────┬──────┘
      │                       │                       │
      │                ┌──────▼──────┐                │
      │                │   photos    │                │
      │                │             │                │
      │                │ id (PK)     │         ┌──────▼──────┐
      │                │ property_id │         │   cities    │
      │                │ path        │         │             │
      │                │ is_main     │         │ id (PK)     │
      │                └─────────────┘         │ name        │
      │                                        │ country_id  │
      ├──────────────────┐                     └─────────────┘
      │                  │
┌─────▼───────┐    ┌────▼──────┐        ┌─────────────┐
│   orders    │    │  credits  │        │   reports   │
│             │    │           │        │             │
│ id (PK)     │    │ id (PK)   │        │ id (PK)     │
│ user_id (FK)│    │ name      │        │ property_id │
│ credit_id   │    │ total     │        │ user_id     │
│ payment_id  │    │ price     │        │ option_id   │
│ amount      │    │ status    │        │ description │
│ status      │    └───────────┘        └─────────────┘
└─────────────┘
                    ┌─────────────┐
                    │    media    │
                    │  (Spatie)   │
                    │             │
                    │ id (PK)     │
                    │ model_type  │
                    │ model_id    │
                    │ file_name   │
                    │ disk        │
                    └─────────────┘
```

## 3.2 Tablas Principales

### 3.2.1 Tabla `users`

Almacena información de usuarios del sistema.

**Estructura**:
```sql
CREATE TABLE `users` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `email_verified_at` timestamp NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('admin','user') DEFAULT 'user',
  `country_id` int UNSIGNED NULL,
  `birth_date` date NULL,
  `gender` enum('male','female') DEFAULT 'male',
  `confirmed` tinyint(1) DEFAULT 0,
  `token` varchar(255) NULL,
  `credits` int DEFAULT 0,
  `remember_token` varchar(100) NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Columnas Importantes**:
- `id`: Identificador único del usuario
- `email`: Email único para autenticación
- `password`: Contraseña hasheada (bcrypt)
- `type`: Rol del usuario (admin, user)
- `credits`: Créditos disponibles del usuario
- `confirmed`: Si el email ha sido verificado
- `token`: Token de verificación de email

**Relaciones**:
- `hasMany` → properties (un usuario tiene muchas propiedades)
- `hasMany` → orders (un usuario tiene muchas órdenes)
- `belongsTo` → country (un usuario pertenece a un país)

### 3.2.2 Tabla `properties`

Almacena los anuncios/listados de propiedades.

**Estructura**:
```sql
CREATE TABLE `properties` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `slug` varchar(255) NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int UNSIGNED NULL,
  `category_id` int UNSIGNED NULL,
  `country_id` int UNSIGNED NULL,
  `city` varchar(255) NULL,
  `state` varchar(255) NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `action_id` int NULL,
  `status` enum('enable','disable','banned','reported') DEFAULT 'enable',
  `visitors` bigint DEFAULT 0,
  `website` varchar(255) NULL,
  `business_name` varchar(255) NULL,
  `social_network` varchar(255) NULL,
  `short_description` varchar(255) NULL,
  `description` text NULL,
  `comment` text NULL,
  `phone` varchar(255) NULL,
  `email` varchar(255) NULL,
  `address` varchar(255) NULL,
  `show_email` tinyint(1) DEFAULT 0,
  `show_website` tinyint(1) DEFAULT 0,
  `show_phone` tinyint(1) DEFAULT 1,
  `serial_number` varchar(255) NULL,
  `google_map` varchar(255) NULL,
  `whatsapp_number` varchar(255) NULL,
  `send_message` tinyint(1) DEFAULT 0,
  `start_date` timestamp NULL,
  `expire_date` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  INDEX `idx_slug` (`slug`),
  INDEX `idx_is_public` (`is_public`)
);
```

**Columnas Importantes**:
- `slug`: URL amigable única
- `title`: Título del anuncio
- `is_public`: Si el anuncio está publicado (visible públicamente)
- `status`: Estado del anuncio (enable, disable, banned, reported)
- `expire_date`: Fecha de expiración de la publicación
- `visitors`: Contador de visitas
- `show_*`: Flags para mostrar/ocultar información de contacto

**Relaciones**:
- `belongsTo` → user (una propiedad pertenece a un usuario)
- `belongsTo` → category (una propiedad pertenece a una categoría)
- `belongsTo` → country (una propiedad pertenece a un país)
- `hasMany` → photos (una propiedad tiene muchas fotos)
- `hasMany` → reports (una propiedad puede tener reportes)
- `morphMany` → media (una propiedad tiene archivos multimedia)

### 3.2.3 Tabla `categories`

Categorías y subcategorías de propiedades (jerárquica).

**Estructura**:
```sql
CREATE TABLE `categories` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `slug` varchar(255) UNIQUE NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int UNSIGNED NULL,
  `is_free` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Columnas Importantes**:
- `parent_id`: Referencia a categoría padre (NULL = categoría principal)
- `is_free`: Si la publicación en esta categoría es gratuita
- `slug`: Identificador URL-friendly

**Relaciones**:
- `hasMany` → categories (subcategorías)
- `belongsTo` → category (categoría padre)
- `hasMany` → properties (propiedades de esta categoría)

**Ejemplo de Jerarquía**:
```
Venta (parent_id: null)
  ├─ Casas (parent_id: 1)
  ├─ Apartamentos (parent_id: 1)
  └─ Terrenos (parent_id: 1)
Alquiler (parent_id: null)
  ├─ Casas (parent_id: 2)
  └─ Apartamentos (parent_id: 2)
```

### 3.2.4 Tabla `countries`

Países disponibles en el sistema.

**Estructura**:
```sql
CREATE TABLE `countries` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Relaciones**:
- `hasMany` → cities (un país tiene muchas ciudades)
- `hasMany` → properties (propiedades en este país)
- `hasMany` → users (usuarios de este país)

### 3.2.5 Tabla `cities`

Ciudades por país.

**Estructura**:
```sql
CREATE TABLE `cities` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Relaciones**:
- `belongsTo` → country (una ciudad pertenece a un país)

### 3.2.6 Tabla `credits`

Paquetes de créditos disponibles para compra.

**Estructura**:
```sql
CREATE TABLE `credits` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `total` int DEFAULT 0,
  `price` float NOT NULL,
  `status` enum('enabled','disabled') DEFAULT 'enabled',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Columnas**:
- `total`: Cantidad de créditos en el paquete
- `price`: Precio en USD del paquete
- `status`: Si el paquete está disponible para compra

**Ejemplo de Paquetes**:
```
| name        | total | price |
|-------------|-------|-------|
| Básico      | 10    | 5.00  |
| Estándar    | 50    | 20.00 |
| Premium     | 100   | 35.00 |
```

### 3.2.7 Tabla `orders`

Órdenes de compra de créditos.

**Estructura**:
```sql
CREATE TABLE `orders` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `user_id` int UNSIGNED NULL,
  `credit_id` int UNSIGNED NULL,
  `payment_id` varchar(255) NULL,
  `payer_id` varchar(255) NULL,
  `amount` float NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Columnas**:
- `payment_id`: ID de transacción de PayPal
- `payer_id`: ID del pagador en PayPal
- `status`: Estado de la orden (pending, completed, failed)

**Relaciones**:
- `belongsTo` → user (orden pertenece a un usuario)
- `belongsTo` → credit (paquete de créditos comprado)

### 3.2.8 Tabla `photos`

Fotos de propiedades (legacy, ahora se usa media library).

**Estructura**:
```sql
CREATE TABLE `photos` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `property_id` int UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Relaciones**:
- `belongsTo` → property

### 3.2.9 Tabla `reports`

Reportes de anuncios inapropiados.

**Estructura**:
```sql
CREATE TABLE `reports` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `property_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NULL,
  `report_option_id` int UNSIGNED NULL,
  `description` text NULL,
  `status` enum('pending','reviewed','resolved') DEFAULT 'pending',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Columnas**:
- `report_option_id`: Motivo del reporte (FK a report_options)
- `status`: Estado del reporte

**Relaciones**:
- `belongsTo` → property (reporte de una propiedad)
- `belongsTo` → user (usuario que reporta)
- `belongsTo` → reportOption (motivo del reporte)

### 3.2.10 Tabla `report_options`

Opciones/motivos de reporte.

**Estructura**:
```sql
CREATE TABLE `report_options` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Ejemplos**:
- Contenido inapropiado
- Información falsa
- Spam
- Duplicado
- Fraude

### 3.2.11 Tabla `media` (Spatie Media Library)

Archivos multimedia gestionados por Spatie Media Library.

**Estructura**:
```sql
CREATE TABLE `media` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) NULL,
  `disk` varchar(255) NOT NULL,
  `size` int UNSIGNED NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int UNSIGNED NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  INDEX `media_model_type_model_id_index` (`model_type`, `model_id`)
);
```

**Uso**: Almacena todas las imágenes de propiedades con conversiones automáticas.

### 3.2.12 Tabla `visitors`

Tracking de visitas a propiedades.

**Estructura**:
```sql
CREATE TABLE `visitors` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `ip` varchar(45) NULL,
  `method` varchar(10) NULL,
  `request` text NULL,
  `url` text NULL,
  `referer` text NULL,
  `languages` text NULL,
  `useragent` text NULL,
  `headers` text NULL,
  `device` text NULL,
  `platform` text NULL,
  `browser` text NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
);
```

**Propósito**: Analítica y estadísticas de visitas.

## 3.3 Modelos Principales

### 3.3.1 Modelo `User`

**Ubicación**: `app/User.php`

```php
<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'type', 'country_id',
        'birth_date', 'gender', 'confirmed', 'token', 'credits'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones
    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function properties() {
        return $this->hasMany(Property::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    // Mutators
    public function setPasswordAttribute($input) {
        if ($input) {
            $this->attributes['password'] = bcrypt($input);
        }
    }
}
```

**Características**:
- Autenticación integrada de Laravel
- Encriptación automática de contraseñas
- Verificación de email (MustVerifyEmail)
- Sistema de tokens para recuperación

### 3.3.2 Modelo `Property`

**Ubicación**: `app/Property.php`

```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Sluggable\HasSlug;
use EloquentFilter\Filterable;

class Property extends Model implements HasMedia
{
    use HasMediaTrait, Filterable, HasSlug;

    protected $fillable = [
        'slug', 'title', 'category_id', 'country_id', 'is_public',
        'city', 'state', 'business_name', 'social_network',
        'action_id', 'status', 'visitors', 'website', 'description',
        'phone', 'email', 'address', 'show_email', 'show_website',
        'show_phone', 'google_map', 'send_message', 'start_date',
        'expire_date', 'whatsapp_number'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'show_phone' => 'boolean',
        'show_website' => 'boolean',
        'send_message' => 'boolean',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'expire_date', 'start_date'
    ];

    protected $with = ['media']; // Eager loading

    // Relaciones
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function photos() {
        return $this->hasMany(Photo::class);
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }

    // Scopes
    public function scopePublic($query) {
        return $query->where('is_public', true)
                     ->where('status', 'enable');
    }

    public function scopeActive($query) {
        return $query->where('expire_date', '>', now())
                     ->orWhereNull('expire_date');
    }
}
```

**Características**:
- Slugs automáticos (URL-friendly)
- Media library para imágenes
- Filtros mediante EloquentFilter
- Scopes para queries comunes
- Eager loading de relaciones

### 3.3.3 Modelo `Category`

**Ubicación**: `app/Category.php`

```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'slug', 'name', 'parent_id', 'is_free'
    ];

    // Relación auto-referencial
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function properties() {
        return $this->hasMany(Property::class);
    }

    // Scope para categorías principales
    public function scopeParents($query) {
        return $query->whereNull('parent_id');
    }
}
```

### 3.3.4 Modelo `Credit`

```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'name', 'total', 'price', 'status'
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    // Scope para créditos activos
    public function scopeEnabled($query) {
        return $query->where('status', 'enabled');
    }
}
```

### 3.3.5 Modelo `Order`

```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'credit_id', 'payment_id', 'payer_id',
        'amount', 'currency', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function credit() {
        return $this->belongsTo(Credit::class);
    }
}
```

## 3.4 Relaciones entre Modelos

### 3.4.1 Tipos de Relaciones

**One-to-Many (hasMany / belongsTo)**
```php
// User tiene muchas Properties
User::find(1)->properties; // Collection de propiedades

// Property pertenece a User
Property::find(1)->user; // Instancia de User
```

**One-to-One (hasOne / belongsTo)**
```php
// User pertenece a Country
User::find(1)->country; // Instancia de Country
```

**Many-to-Many (belongsToMany)**
- No implementado directamente en el esquema actual
- Podría usarse para favoritos o guardados

**Polymorphic (morphMany / morphTo)**
```php
// Property tiene muchos Media (polymorphic)
Property::find(1)->media; // Collection de archivos
```

**Self-Referential**
```php
// Category tiene parent y children
Category::find(1)->parent; // Categoría padre
Category::find(1)->children; // Subcategorías
```

### 3.4.2 Diagrama de Relaciones Detallado

```
User (1) ──────────── (N) Property
  │                        │
  │                        ├─ (N) Photo
  │                        ├─ (N) Media (polymorphic)
  │                        ├─ (N) Report
  │                        ├─ (1) Category
  │                        └─ (1) Country
  │
  ├─ (N) Order
  │      └─ (1) Credit
  │
  └─ (1) Country
         └─ (N) City

Category (1) ──────── (N) Category (children)
         └── (1) Category (parent)
```

### 3.4.3 Eager Loading

Para optimizar queries y evitar el problema N+1:

```php
// Sin eager loading (N+1 problem)
$properties = Property::all();
foreach ($properties as $property) {
    echo $property->user->name; // Query por cada propiedad
}

// Con eager loading
$properties = Property::with(['user', 'category', 'country', 'media'])->get();
foreach ($properties as $property) {
    echo $property->user->name; // Sin queries adicionales
}
```

## 3.5 Migraciones y Seeders

### 3.5.1 Sistema de Migraciones

Las migraciones son versionamiento de base de datos. Cada cambio al esquema se hace mediante una migración.

**Crear una migración**:
```bash
php artisan make:migration create_properties_table
php artisan make:migration add_location_to_properties_table
```

**Ejecutar migraciones**:
```bash
php artisan migrate              # Ejecutar pendientes
php artisan migrate:fresh        # Drop all + migrate
php artisan migrate:fresh --seed # Drop all + migrate + seed
php artisan migrate:rollback     # Revertir último batch
php artisan migrate:reset        # Revertir todas
```

### 3.5.2 Seeders

Los seeders permiten poblar la base de datos con datos iniciales o de prueba.

**Ubicación**: `database/seeds/`

**Ejemplo de Seeder**:
```php
<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Venta',
                'slug' => 'venta',
                'parent_id' => null,
                'is_free' => false
            ],
            [
                'name' => 'Alquiler',
                'slug' => 'alquiler',
                'parent_id' => null,
                'is_free' => false
            ]
        ]);
    }
}
```

**Ejecutar seeders**:
```bash
php artisan db:seed                      # Ejecutar DatabaseSeeder
php artisan db:seed --class=UserSeeder   # Ejecutar seeder específico
```

### 3.5.3 Gestión de Cambios de Esquema

**Workflow**:
1. Crear migración para nuevo cambio
2. Definir cambios en método `up()`
3. Definir rollback en método `down()`
4. Ejecutar migración
5. Verificar cambios

**Ejemplo - Agregar columna**:
```php
// database/migrations/2021_xx_xx_add_whatsapp_to_properties_table.php
public function up() {
    Schema::table('properties', function (Blueprint $table) {
        $table->string('whatsapp_number')->nullable()->after('phone');
    });
}

public function down() {
    Schema::table('properties', function (Blueprint $table) {
        $table->dropColumn('whatsapp_number');
    });
}
```

## 3.6 Índices e Optimización

### 3.6.1 Índices Existentes

```sql
-- Properties
INDEX `idx_slug` ON properties(slug)
INDEX `idx_is_public` ON properties(is_public)
INDEX `properties_category_id_index` ON properties(category_id)
INDEX `properties_country_id_index` ON properties(country_id)

-- Users
UNIQUE INDEX `users_email_unique` ON users(email)

-- Categories
UNIQUE INDEX `categories_slug_unique` ON categories(slug)

-- Media
INDEX `media_model_type_model_id_index` ON media(model_type, model_id)
```

### 3.6.2 Queries Optimizadas

**Búsqueda de Propiedades Públicas**:
```php
Property::where('is_public', true) // Usa índice
        ->where('status', 'enable')
        ->where('expire_date', '>', now())
        ->with(['user', 'category', 'country', 'media'])
        ->paginate(15);
```

**Uso de EloquentFilter**:
```php
Property::filter($request->all())
        ->with(['user', 'category'])
        ->paginate(15);
```

## 3.7 Transacciones

Para operaciones que requieren consistencia (ej: compra de créditos):

```php
DB::transaction(function () use ($user, $credit) {
    // Crear orden
    $order = Order::create([...]);
    
    // Actualizar créditos del usuario
    $user->increment('credits', $credit->total);
    
    // Log de la transacción
    Log::info('Credits purchased', ['user' => $user->id]);
});
```

## 3.8 Documentos Relacionados

- **Anterior**: [Arquitectura](02-ARQUITECTURA.md)
- **Siguiente**: [Funcionalidades](04-FUNCIONALIDADES.md)
- **Ver también**: [Controladores](05-CONTROLADORES.md)

---

[← Volver al Índice](README.md) | [Anterior: Arquitectura](02-ARQUITECTURA.md) | [Siguiente: Funcionalidades →](04-FUNCIONALIDADES.md)
