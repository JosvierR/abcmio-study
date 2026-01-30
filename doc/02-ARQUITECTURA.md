# 2. Arquitectura

## 2.1 Arquitectura General del Sistema

ABCmio está construido siguiendo el patrón **MVC (Model-View-Controller)** de Laravel, con capas adicionales de servicios y repositorios para separar la lógica de negocio.

### 2.1.1 Diagrama de Arquitectura

```
┌─────────────────────────────────────────────────────────────────────┐
│                           FRONTEND LAYER                             │
│  ┌──────────────┐  ┌──────────────┐  ┌─────────────────────────┐   │
│  │    Blade     │  │    React     │  │       Vue.js            │   │
│  │  Templates   │  │  Components  │  │     Components          │   │
│  └──────┬───────┘  └──────┬───────┘  └──────────┬──────────────┘   │
│         │                 │                      │                   │
│         └─────────────────┴──────────────────────┘                   │
│                            │                                         │
└────────────────────────────┼─────────────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────────────┐
│                        MIDDLEWARE LAYER                              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌────────────────────┐  │
│  │   Auth   │  │  Locale  │  │   CSRF   │  │  AdminMiddleware   │  │
│  └──────────┘  └──────────┘  └──────────┘  └────────────────────┘  │
└────────────────────────────┬─────────────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────────────┐
│                       CONTROLLER LAYER                               │
│  ┌────────────────┐  ┌─────────────┐  ┌──────────────────────────┐  │
│  │  HomeController│  │  Property   │  │  DirectoryController     │  │
│  │  UserController│  │  Controller │  │  PaymentController       │  │
│  │  CreditController  │  PhotoController  │  ReportController    │  │
│  └────────┬───────┘  └──────┬──────┘  └────────┬─────────────────┘  │
└───────────┼──────────────────┼──────────────────┼─────────────────────┘
            │                  │                  │
┌───────────▼──────────────────▼──────────────────▼─────────────────────┐
│                         SERVICE LAYER                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌─────────────────────────┐    │
│  │FilterService │  │PropertyService  │ ReportService           │    │
│  │ProductService│  │VisitorManager│  │ (Business Logic)        │    │
│  └──────┬───────┘  └──────┬───────┘  └──────────┬──────────────┘    │
└─────────┼──────────────────┼─────────────────────┼────────────────────┘
          │                  │                     │
┌─────────▼──────────────────▼─────────────────────▼────────────────────┐
│                       REPOSITORY LAYER (Optional)                     │
│                  (Data Access Abstraction)                            │
└─────────┬─────────────────────────────────────────────────────────────┘
          │
┌─────────▼─────────────────────────────────────────────────────────────┐
│                          MODEL LAYER                                  │
│  ┌──────┐  ┌─────────┐  ┌─────────┐  ┌────────┐  ┌────────────────┐ │
│  │ User │  │Property │  │Category │  │ Credit │  │     Order      │ │
│  └──────┘  └─────────┘  └─────────┘  └────────┘  └────────────────┘ │
│  ┌──────┐  ┌─────────┐  ┌─────────┐  ┌────────┐  ┌────────────────┐ │
│  │Photo │  │ Report  │  │ Country │  │  City  │  │  ReportOption  │ │
│  └──────┘  └─────────┘  └─────────┘  └────────┘  └────────────────┘ │
└─────────┬─────────────────────────────────────────────────────────────┘
          │  (Eloquent ORM)
┌─────────▼─────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                                   │
│                         MySQL 5.7+                                    │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────────┐   │
│  │    users     │  │  properties  │  │      categories          │   │
│  │   credits    │  │    orders    │  │        reports           │   │
│  │   photos     │  │  countries   │  │         cities           │   │
│  └──────────────┘  └──────────────┘  └──────────────────────────┘   │
└───────────────────────────────────────────────────────────────────────┘

                    EXTERNAL SERVICES
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│   PayPal     │  │    AWS S3    │  │     SMTP     │  │  reCAPTCHA   │
│   Payment    │  │   Storage    │  │    Email     │  │   Security   │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
```

### 2.1.2 Patrón MVC de Laravel

**Model (Modelo)**
- Representa la estructura de datos y lógica de negocio
- Interactúa con la base de datos mediante Eloquent ORM
- Define relaciones entre entidades
- Ubicación: `app/*.php` y `app/Models/`

**View (Vista)**
- Presenta datos al usuario
- Blade templates + React/Vue components
- Ubicación: `resources/views/`

**Controller (Controlador)**
- Maneja las peticiones HTTP
- Coordina modelos y vistas
- Delega lógica compleja a servicios
- Ubicación: `app/Http/Controllers/`

### 2.1.3 Capas Adicionales

**Service Layer**
- Contiene lógica de negocio compleja
- Reutilizable entre controladores
- Mantiene controllers delgados
- Ubicación: `app/Services/`

**Repository Layer**
- Abstracción de acceso a datos
- Facilita testing y cambios de persistencia
- Ubicación: `app/Repositories/`

**Middleware Layer**
- Filtros de peticiones HTTP
- Autenticación, autorización, localización
- Ubicación: `app/Http/Middleware/`

## 2.2 Estructura de Directorios

```
abcmio-study/
│
├── app/                          # Código de la aplicación
│   ├── Console/                  # Comandos Artisan
│   ├── Events/                   # Eventos del sistema
│   ├── Exceptions/               # Manejo de excepciones
│   ├── Helpers/                  # Funciones helper
│   ├── Http/                     # Capa HTTP
│   │   ├── Controllers/          # Controladores
│   │   │   ├── Admin/            # Controladores admin
│   │   │   ├── Api/              # Controladores API
│   │   │   └── Auth/             # Autenticación
│   │   ├── Middleware/           # Middleware HTTP
│   │   └── Requests/             # Form Requests
│   ├── Mail/                     # Clases de email
│   ├── Managers/                 # Managers (VisitorManager)
│   ├── ModelFilters/             # Filtros de modelos
│   ├── Notifications/            # Notificaciones
│   ├── Observers/                # Observers de modelos
│   ├── Policies/                 # Políticas de autorización
│   ├── Providers/                # Service Providers
│   ├── Repositories/             # Repositorios
│   ├── Rules/                    # Reglas de validación
│   ├── Services/                 # Servicios de negocio
│   ├── *.php                     # Modelos principales
│   └── User.php                  # Modelo Usuario
│
├── bootstrap/                    # Bootstrap de la aplicación
│   └── app.php                   # Inicialización de Laravel
│
├── config/                       # Archivos de configuración
│   ├── app.php                   # Configuración principal
│   ├── database.php              # Configuración BD
│   ├── mail.php                  # Configuración email
│   ├── filesystems.php           # Almacenamiento
│   └── paypal.php                # Configuración PayPal
│
├── database/                     # Base de datos
│   ├── factories/                # Model factories
│   ├── migrations/               # Migraciones
│   └── seeds/                    # Seeders
│
├── public/                       # Archivos públicos
│   ├── css/                      # CSS compilado
│   ├── js/                       # JavaScript compilado
│   ├── images/                   # Imágenes públicas
│   ├── storage/                  # Link simbólico a storage
│   └── index.php                 # Entry point
│
├── resources/                    # Recursos sin compilar
│   ├── js/                       # JavaScript source
│   │   ├── components/           # React/Vue components
│   │   └── app.js                # Entry point JS
│   ├── lang/                     # Traducciones
│   │   ├── en/                   # Inglés
│   │   ├── es/                   # Español
│   │   └── fr/                   # Francés
│   ├── sass/                     # Sass/SCSS source
│   │   └── app.scss              # Entry point CSS
│   └── views/                    # Blade templates
│       ├── admin/                # Vistas admin
│       ├── auth/                 # Vistas autenticación
│       ├── frontend/             # Vistas públicas
│       └── layouts/              # Layouts base
│
├── routes/                       # Definición de rutas
│   ├── web.php                   # Rutas web
│   ├── api.php                   # Rutas API
│   ├── channels.php              # Broadcasting
│   └── console.php               # Comandos consola
│
├── storage/                      # Almacenamiento
│   ├── app/                      # Archivos de aplicación
│   │   ├── public/               # Archivos públicos
│   │   └── media/                # Media library
│   ├── framework/                # Archivos del framework
│   │   ├── cache/                # Cache
│   │   ├── sessions/             # Sesiones
│   │   └── views/                # Vistas compiladas
│   └── logs/                     # Logs de aplicación
│
├── tests/                        # Tests
│   ├── Feature/                  # Tests de features
│   └── Unit/                     # Tests unitarios
│
├── .env                          # Variables de entorno
├── .env.example                  # Ejemplo de variables
├── artisan                       # CLI de Laravel
├── composer.json                 # Dependencias PHP
├── package.json                  # Dependencias JS
└── webpack.mix.js                # Configuración Laravel Mix
```

### 2.2.1 Directorio `app/`

**Propósito**: Contiene el core de la aplicación

**Subdirectorios Importantes**:

- **Console/**: Comandos Artisan personalizados
- **Http/Controllers/**: Lógica de manejo de requests
  - `Admin/`: Gestión administrativa
  - `Api/`: Endpoints API
  - `Auth/`: Login, registro, contraseñas
- **Services/**: Lógica de negocio reutilizable
- **Policies/**: Autorización basada en modelos
- **Managers/**: Clases de gestión (ej: VisitorManager)

### 2.2.2 Directorio `resources/`

**Propósito**: Archivos de recursos sin compilar

**Subdirectorios**:

- **js/**: Código JavaScript/React/Vue
  - `components/`: Componentes reutilizables
- **sass/**: Estilos SCSS/Sass
- **views/**: Plantillas Blade
  - `layouts/`: Layouts base (app.blade.php, admin.blade.php)
  - `frontend/`: Vistas públicas
  - `admin/`: Panel de administración
- **lang/**: Archivos de traducción (es, en, fr)

### 2.2.3 Directorio `database/`

**Propósito**: Todo relacionado con base de datos

- **migrations/**: Esquema de base de datos versionado
- **seeds/**: Datos de prueba y iniciales
- **factories/**: Generadores de datos para testing

### 2.2.4 Directorio `public/`

**Propósito**: Punto de entrada y archivos públicos

- **index.php**: Entry point de la aplicación
- **css/**, **js/**: Assets compilados por Laravel Mix
- **storage/**: Link simbólico a `storage/app/public`

### 2.2.5 Directorio `routes/`

**Propósito**: Definición de todas las rutas

- **web.php**: Rutas web con sesión y CSRF
- **api.php**: Rutas API (stateless)
- **console.php**: Comandos de consola
- **channels.php**: Broadcasting channels

## 2.3 Flujo de Request-Response

### 2.3.1 Ciclo de Vida de una Petición HTTP

```
1. ENTRADA
   ↓
   public/index.php
   ↓
2. BOOTSTRAP
   ↓
   bootstrap/app.php
   │
   ├─→ Cargar service providers
   ├─→ Registrar bindings
   └─→ Configurar aplicación
   ↓
3. KERNEL HTTP
   ↓
   app/Http/Kernel.php
   │
   ├─→ Middleware Global
   ├─→ Middleware de Grupos
   └─→ Middleware de Rutas
   ↓
4. ROUTING
   ↓
   routes/web.php o routes/api.php
   │
   ├─→ Coincidencia de ruta
   └─→ Resolución de parámetros
   ↓
5. MIDDLEWARE
   ↓
   ├─→ Authenticate
   ├─→ SetLocale
   ├─→ VerifyCsrfToken
   └─→ Custom middleware
   ↓
6. CONTROLLER
   ↓
   app/Http/Controllers/*Controller.php
   │
   ├─→ Request validation
   ├─→ Llamadas a servicios
   └─→ Preparación de respuesta
   ↓
7. SERVICE LAYER (si aplica)
   ↓
   app/Services/*Service.php
   │
   └─→ Lógica de negocio
   ↓
8. MODEL / DATABASE
   ↓
   app/*.php (Eloquent ORM)
   │
   ├─→ Queries
   ├─→ Relaciones
   └─→ Mutators/Accessors
   ↓
9. VIEW
   ↓
   resources/views/*.blade.php
   │
   ├─→ Compilación Blade
   ├─→ Componentes React/Vue
   └─→ Rendering HTML
   ↓
10. RESPONSE
    ↓
    HTTP Response al cliente
```

### 2.3.2 Ejemplo Práctico: Visualización de Propiedad

```php
// 1. Usuario solicita: GET /es/propiedad-ejemplo-123

// 2. Router (routes/web.php)
Route::get('/{slug}', [DirectoryController::class, 'get_property_by_slug'])
    ->name('get.property.by.slug');

// 3. Middleware (SetLocale)
// Establece idioma 'es' basado en URL

// 4. Controller (DirectoryController)
public function get_property_by_slug($slug)
{
    // 5. Model query
    $property = Property::where('slug', $slug)
        ->with(['user', 'category', 'country', 'media'])
        ->firstOrFail();
    
    // 6. Service (VisitorManager)
    $this->visitorManager->trackVisit($property);
    
    // 7. View
    return view('frontend.directory.show', compact('property'));
}

// 8. Blade template renderiza la vista
// 9. Response enviada al navegador
```

### 2.3.3 Middleware Involucrado

**Global Middleware** (todas las requests):
```php
// app/Http/Kernel.php
protected $middleware = [
    \App\Http\Middleware\CheckForMaintenanceMode::class,
    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
    \App\Http\Middleware\TrimStrings::class,
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    \App\Http\Middleware\TrustProxies::class,
];
```

**Web Middleware Group**:
```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```

**Route Middleware** (específico):
```php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'setLocale' => \App\Http\Middleware\SetLocale::class,
    'nonLogged' => \App\Http\Middleware\NonLoggetOnly::class,
];
```

### 2.3.4 Procesamiento de Rutas

**Rutas con Locale**:
```php
// Grupo de rutas con prefijo de idioma
Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => 'setLocale'
], function () {
    // Rutas aquí tendrán formato: /es/ruta, /en/ruta, /fr/ruta
    Route::get('/home', 'DirectoryController@index')->name('home');
    Route::resource('properties', 'PropertyController');
});
```

**Rutas API** (sin estado):
```php
// routes/api.php
Route::get('cities/{id}', 'ApiController@get_city_by_country_id');
Route::get('properties', 'ApiController@get_properies');
```

## 2.4 Componentes Principales

### 2.4.1 Frontend

**Blade Templates**
- Motor de plantillas de Laravel
- Sintaxis limpia y expresiva
- Herencia de layouts
- Componentes y directivas

**React Components**
- Componentes interactivos
- Gestión de estado local
- Ubicación: `resources/js/components/`

**Vue.js Components**
- Componentes reactivos
- Two-way data binding
- Uso mixto con React

**Laravel Mix (Webpack)**
- Compilación de assets
- Minificación y optimización
- Hot module replacement

### 2.4.2 Backend

**Laravel Framework 5.8**
- Routing robusto
- Eloquent ORM
- Blade templating
- Middleware system
- Authentication
- Validation
- Queue system
- Broadcasting
- Cache
- Events

**Componentes Personalizados**
- Services: Lógica de negocio
- Repositories: Abstracción de datos
- Managers: Gestión especializada
- Helpers: Funciones utilitarias

### 2.4.3 Base de Datos

**MySQL 5.7+**
- Motor InnoDB
- Transacciones ACID
- Foreign keys
- Índices optimizados

**Eloquent ORM**
- Active Record pattern
- Relationships
- Query builder
- Migrations
- Seeders

### 2.4.4 Almacenamiento

**Local Storage** (por defecto)
- `storage/app/public/`
- Link simbólico a `public/storage/`

**AWS S3** (opcional)
- Almacenamiento en la nube
- CDN integrado
- Escalabilidad

**Spatie Media Library**
- Gestión de archivos multimedia
- Conversiones automáticas
- Responsive images
- Optimización

## 2.5 Patrones de Diseño Utilizados

### 2.5.1 MVC (Model-View-Controller)
```php
// Model
class Property extends Model { }

// View
<!-- resources/views/properties/show.blade.php -->

// Controller
class PropertyController extends Controller {
    public function show($id) {
        $property = Property::findOrFail($id);
        return view('properties.show', compact('property'));
    }
}
```

### 2.5.2 Repository Pattern
```php
interface PropertyRepositoryInterface {
    public function findBySlug($slug);
    public function searchWithFilters($filters);
}

class PropertyRepository implements PropertyRepositoryInterface {
    public function findBySlug($slug) {
        return Property::where('slug', $slug)->firstOrFail();
    }
}
```

### 2.5.3 Service Layer Pattern
```php
class PropertyService {
    public function publishProperty(Property $property, $credits) {
        // Lógica de negocio compleja
        // Validaciones
        // Transacciones
        // Eventos
    }
}
```

### 2.5.4 Observer Pattern
```php
class PropertyObserver {
    public function creating(Property $property) {
        // Lógica antes de crear
    }
    
    public function created(Property $property) {
        // Lógica después de crear
    }
}
```

### 2.5.5 Dependency Injection
```php
class PropertyController extends Controller {
    protected $propertyService;
    
    public function __construct(PropertyService $propertyService) {
        $this->propertyService = $propertyService;
    }
}
```

## 2.6 Comunicación entre Componentes

### Frontend ↔ Backend

**Blade + Backend**
```php
// Controller pasa datos a vista
return view('properties.index', [
    'properties' => $properties,
    'categories' => $categories
]);
```

**React/Vue ↔ API**
```javascript
// Petición AJAX desde componente
axios.get('/api/cities/' + countryId)
    .then(response => {
        this.setState({ cities: response.data });
    });
```

**Form Submission**
```html
<!-- Blade form -->
<form method="POST" action="{{ route('properties.store') }}">
    @csrf
    <!-- campos -->
</form>
```

### Backend ↔ Database

**Eloquent ORM**
```php
// Query simple
$properties = Property::where('is_public', true)->get();

// Query con relaciones
$property = Property::with(['user', 'category', 'media'])->find($id);

// Query con filtros
$properties = Property::filter($request->all())->paginate(15);
```

### Backend ↔ Servicios Externos

**PayPal**
```php
use PayPal\Rest\ApiContext;

$apiContext = new ApiContext(
    new OAuthTokenCredential(
        config('paypal.client_id'),
        config('paypal.secret')
    )
);
```

**AWS S3**
```php
Storage::disk('s3')->put($path, $file);
```

## 2.7 Documentos Relacionados

- **Siguiente**: [Base de Datos](03-BASE-DE-DATOS.md) - Esquema y modelos
- **Ver también**: [Controladores](05-CONTROLADORES.md) - Detalle de controllers
- **Referencia**: [Servicios](06-SERVICIOS.md) - Service Layer

---

[← Volver al Índice](README.md) | [Anterior: Introducción](01-INTRODUCCION.md) | [Siguiente: Base de Datos →](03-BASE-DE-DATOS.md)
