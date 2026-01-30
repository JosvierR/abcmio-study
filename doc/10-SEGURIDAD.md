# 10. Seguridad

## 10.1 Introducción

La seguridad es una prioridad fundamental en ABCmio. El sistema implementa múltiples capas de protección siguiendo las mejores prácticas de Laravel y seguridad web.

### 10.1.1 Principios de Seguridad

- **Defensa en profundidad**: Múltiples capas de seguridad
- **Mínimo privilegio**: Usuarios solo tienen permisos necesarios
- **Validación de entrada**: Toda entrada de usuario es validada
- **Escape de salida**: Prevención de XSS
- **Protección CSRF**: Tokens en formularios
- **Autenticación segura**: Hashing de contraseñas

## 10.2 Autenticación

### 10.2.1 Registro de Usuarios

**Proceso de Registro Seguro:**

```php
// RegisterController
protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'g-recaptcha-response' => ['required', 'captcha'], // Protección anti-bots
    ]);
}

protected function create(array $data)
{
    // Hash seguro de contraseña con bcrypt
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'verification_token' => Str::random(40),
        'credits' => 0,
        'role' => 'user'
    ]);
    
    // Enviar email de verificación
    event(new NewUserRegisteredEvent($user));
    
    return $user;
}
```

**Características de Seguridad:**
- Contraseñas hasheadas con bcrypt (costo 10)
- Validación de email único
- Contraseña mínima de 8 caracteres
- Confirmación de contraseña obligatoria
- Verificación de email obligatoria
- Protección reCAPTCHA

### 10.2.2 Inicio de Sesión

```php
// LoginController
public function login(Request $request)
{
    // Validar credenciales
    $this->validateLogin($request);
    
    // Protección contra fuerza bruta
    if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);
        return $this->sendLockoutResponse($request);
    }
    
    // Intentar autenticación
    if ($this->attemptLogin($request)) {
        return $this->sendLoginResponse($request);
    }
    
    // Incrementar intentos fallidos
    $this->incrementLoginAttempts($request);
    
    return $this->sendFailedLoginResponse($request);
}
```

**Protecciones:**
- Rate limiting: 5 intentos en 1 minuto
- Bloqueo temporal tras intentos fallidos
- Logging de intentos de login
- Regeneración de sesión al autenticarse

### 10.2.3 Gestión de Contraseñas

**Requisitos de Contraseña:**
- Mínimo 8 caracteres
- Debe ser confirmada
- Hasheada con bcrypt

**Reset de Contraseña:**

```php
// ForgotPasswordController
public function sendResetLinkEmail(Request $request)
{
    $this->validateEmail($request);
    
    // Generar token seguro
    $token = Password::createToken($request->only('email'));
    
    // Enviar email con link
    Password::sendResetLink($request->only('email'));
    
    return back()->with('status', 'Link de reset enviado');
}

// ResetPasswordController
protected function resetPassword($user, $password)
{
    // Hash de nueva contraseña
    $user->password = Hash::make($password);
    $user->save();
    
    // Invalidar tokens anteriores
    Password::deleteToken($user);
    
    // Regenerar sesión
    $this->guard()->login($user);
}
```

**Seguridad del Reset:**
- Token único de 60 caracteres
- Expiración de 60 minutos
- Un solo uso por token
- Invalidación al cambiar contraseña

### 10.2.4 Verificación de Email

```php
// User Model
public function hasVerifiedEmail()
{
    return !is_null($this->email_verified_at);
}

public function markEmailAsVerified()
{
    return $this->forceFill([
        'email_verified_at' => $this->freshTimestamp(),
        'verification_token' => null
    ])->save();
}

// Middleware para rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('properties', 'PropertyController');
});
```

## 10.3 Autorización

### 10.3.1 Roles de Usuario

```php
// User Model
public function isAdmin()
{
    return in_array($this->type, ['admin', 'super']);
}

public function hasRole($role)
{
    return $this->type === $role;
}

// Middleware
public function handle($request, Closure $next)
{
    if (!auth()->user()->isAdmin()) {
        abort(403, 'No autorizado');
    }
    
    return $next($request);
}
```

**Roles Disponibles:**
- `user`: Usuario regular
- `admin`: Administrador
- `super`: Súper administrador (futuro)

### 10.3.2 Policies

**PropertyPolicy:**

```php
<?php

namespace App\Policies;

use App\User;
use App\Property;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;
    
    /**
     * Ver propiedad
     */
    public function view(User $user, Property $property)
    {
        // Propietario, admin o si es pública
        return $user->id === $property->user_id 
            || $user->isAdmin() 
            || $property->is_public;
    }
    
    /**
     * Actualizar propiedad
     */
    public function update(User $user, Property $property)
    {
        // Solo propietario o admin
        return $user->id === $property->user_id 
            || $user->type === 'admin' 
            || $user->type === 'super';
    }
    
    /**
     * Eliminar propiedad
     */
    public function delete(User $user, Property $property)
    {
        return $user->id === $property->user_id 
            || $user->type === 'admin' 
            || $user->type === 'super';
    }
    
    /**
     * Restaurar propiedad
     */
    public function restore(User $user, Property $property)
    {
        return $user->type === 'admin' 
            || $user->type === 'super';
    }
    
    /**
     * Eliminar permanentemente
     */
    public function forceDelete(User $user, Property $property)
    {
        return $user->type === 'admin' 
            || $user->type === 'super';
    }
}
```

**Uso en Controladores:**

```php
// PropertyController
public function update(Request $request, Property $property)
{
    // Verificar autorización automáticamente
    $this->authorize('update', $property);
    
    // Si llega aquí, está autorizado
    $property->update($request->validated());
    
    return redirect()->back();
}

// En Blade
@can('update', $property)
    <a href="{{ route('properties.edit', $property) }}">Editar</a>
@endcan

@can('delete', $property)
    <form method="POST" action="{{ route('properties.destroy', $property) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Eliminar</button>
    </form>
@endcan
```

### 10.3.3 Gates

```php
// AuthServiceProvider
public function boot()
{
    $this->registerPolicies();
    
    // Gate para admin
    Gate::define('admin-only', function ($user) {
        return $user->isAdmin();
    });
    
    // Gate para Telescope
    Gate::define('viewTelescope', function ($user) {
        return in_array($user->email, [
            'admin@abcmio.com',
        ]) || $user->isAdmin();
    });
}

// Uso
if (Gate::allows('admin-only')) {
    // Usuario es admin
}

if (Gate::denies('admin-only')) {
    abort(403);
}
```

## 10.4 Protección CSRF

### 10.4.1 Tokens CSRF

Laravel incluye protección CSRF automática para todas las rutas POST, PUT, PATCH, DELETE.

```blade
{{-- Todos los formularios deben incluir token CSRF --}}
<form method="POST" action="{{ route('properties.store') }}">
    @csrf
    <!-- Campos del formulario -->
</form>

{{-- Para DELETE o PUT --}}
<form method="POST" action="{{ route('properties.destroy', $property) }}">
    @csrf
    @method('DELETE')
    <button type="submit">Eliminar</button>
</form>
```

**Configuración:**

```php
// Middleware\VerifyCsrfToken
protected $except = [
    // Rutas excluidas de verificación CSRF
    'webhooks/*',
    'paypal/webhook'
];
```

### 10.4.2 AJAX con CSRF

```javascript
// Configurar Axios con token CSRF
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = 
    document.head.querySelector('meta[name="csrf-token"]').content;

// Ahora todas las peticiones AJAX incluyen el token
axios.post('/api/properties', data)
    .then(response => {
        console.log(response.data);
    });
```

## 10.5 Protección XSS

### 10.5.1 Escape de Datos

Blade escapa automáticamente las variables:

```blade
{{-- Escapado automático --}}
{{ $property->title }}

{{-- Sin escapar (PELIGROSO) --}}
{!! $property->description !!}

{{-- Sanitizar HTML antes de mostrar --}}
{!! clean($property->description) !!}
```

**Sanitización de HTML:**

```php
// Helper
function clean($html)
{
    return strip_tags($html, '<p><br><strong><em><ul><ol><li>');
}

// En controlador
$validated['description'] = strip_tags($request->description, 
    '<p><br><strong><em><ul><ol><li><a>');
```

### 10.5.2 Content Security Policy (CSP)

```php
// Middleware\SetSecurityHeaders
public function handle($request, Closure $next)
{
    $response = $next($request);
    
    $response->headers->set('Content-Security-Policy', 
        "default-src 'self'; " .
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com; " .
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
        "font-src 'self' https://fonts.gstatic.com; " .
        "img-src 'self' data: https:; " .
        "frame-src https://www.google.com;"
    );
    
    return $response;
}
```

## 10.6 Inyección SQL

### 10.6.1 Prevención

Laravel Eloquent y Query Builder previenen inyección SQL automáticamente:

```php
// ✅ SEGURO - Usa prepared statements
$properties = Property::where('city_id', $request->city_id)->get();

// ✅ SEGURO - Parámetros vinculados
$properties = DB::table('properties')
                ->where('price', '>', $minPrice)
                ->get();

// ❌ PELIGROSO - SQL crudo sin binding
$properties = DB::select("SELECT * FROM properties WHERE city_id = " . $request->city_id);

// ✅ SEGURO - SQL crudo con bindings
$properties = DB::select('SELECT * FROM properties WHERE city_id = ?', [$request->city_id]);
```

### 10.6.2 Mass Assignment Protection

```php
// Property Model
class Property extends Model
{
    // Campos permitidos para asignación masiva
    protected $fillable = [
        'title',
        'description',
        'price',
        'category_id',
        'country_id',
        'city_id',
        'user_id',
        'status'
    ];
    
    // Campos protegidos contra asignación masiva
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}

// ✅ SEGURO
$property = Property::create($request->validated());

// ❌ PELIGROSO sin validación
$property = Property::create($request->all());
```

## 10.7 Validación de Entrada

### 10.7.1 Form Requests

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50|max:5000',
            'price' => 'required|numeric|min:0|max:999999999',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'category_id' => 'required|exists:categories,id',
            'property_type' => 'required|in:sale,rent,service',
            'address' => 'nullable|string|max:500',
            'area' => 'nullable|numeric|min:0|max:999999',
            'bedrooms' => 'nullable|integer|min:0|max:50',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio',
            'description.min' => 'La descripción debe tener al menos 50 caracteres',
            'price.numeric' => 'El precio debe ser un número',
            'images.*.image' => 'Solo se permiten imágenes',
            'images.*.max' => 'Las imágenes no pueden superar 5MB'
        ];
    }
}
```

### 10.7.2 Sanitización

```php
// Sanitizar entrada antes de guardar
$validated = $request->validated();

// Limpiar HTML
$validated['description'] = strip_tags($validated['description'], 
    '<p><br><strong><em><ul><ol><li>');

// Eliminar espacios extra
$validated['title'] = trim($validated['title']);

// Normalizar email
$validated['email'] = strtolower($validated['email']);

$property = Property::create($validated);
```

## 10.8 Subida de Archivos

### 10.8.1 Validación de Archivos

```php
// Validación estricta de imágenes
$request->validate([
    'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB
    'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB
]);

// Verificar MIME type real
$file = $request->file('avatar');
$mimeType = $file->getMimeType();

if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
    return back()->withErrors(['avatar' => 'Tipo de archivo no permitido']);
}
```

### 10.8.2 Almacenamiento Seguro

```php
// Generar nombre aleatorio
$filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

// Almacenar fuera de public
$path = $file->store('uploads/properties', 'private');

// O con Spatie Media Library (más seguro)
$property->addMedia($file)
         ->sanitizingFileName(function($fileName) {
             return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
         })
         ->toMediaCollection('images');
```

### 10.8.3 Prevenir Path Traversal

```php
// ❌ VULNERABLE
$filename = $request->filename;
$content = file_get_contents(storage_path('files/' . $filename));

// ✅ SEGURO
$filename = basename($request->filename); // Elimina ../
$safePath = storage_path('files/' . $filename);

// Verificar que el path está dentro del directorio permitido
$realPath = realpath($safePath);
$basePath = realpath(storage_path('files'));

if (strpos($realPath, $basePath) !== 0) {
    abort(403, 'Acceso no autorizado');
}

$content = file_get_contents($realPath);
```

## 10.9 Headers de Seguridad

### 10.9.1 Middleware de Headers

```php
<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Prevenir clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Prevenir MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Activar protección XSS del navegador
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // HSTS - Forzar HTTPS
        $response->headers->set('Strict-Transport-Security', 
            'max-age=31536000; includeSubDomains');
        
        // Content Security Policy
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;");
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }
}

// Registrar en Kernel
protected $middleware = [
    // ...
    \App\Http\Middleware\SecurityHeaders::class,
];
```

## 10.10 Rate Limiting

### 10.10.1 Throttle Middleware

```php
// routes/web.php

// Login - 5 intentos por minuto
Route::post('login', 'Auth\LoginController@login')
     ->middleware('throttle:5,1');

// API - 60 requests por minuto
Route::middleware('throttle:60,1')->group(function () {
    Route::get('properties', 'ApiController@get_properies');
});

// Reportes - 10 por hora
Route::post('reports', 'ReportController@store')
     ->middleware('throttle:10,60');
```

### 10.10.2 Custom Rate Limiters

```php
// RouteServiceProvider
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

public function boot()
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
    
    RateLimiter::for('global', function (Request $request) {
        return Limit::perMinute(1000)->by($request->ip());
    });
}
```

## 10.11 Logging y Auditoría

### 10.11.1 Logging de Seguridad

```php
// Log intentos de login fallidos
if (!Auth::attempt($credentials)) {
    Log::warning('Failed login attempt', [
        'email' => $request->email,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);
}

// Log accesos no autorizados
public function update(Request $request, Property $property)
{
    if (!$this->authorize('update', $property)) {
        Log::warning('Unauthorized property access attempt', [
            'user_id' => auth()->id(),
            'property_id' => $property->id,
            'ip' => $request->ip()
        ]);
        
        abort(403);
    }
}

// Log actividades sospechosas
if ($property->price < 0) {
    Log::alert('Suspicious property price', [
        'property_id' => $property->id,
        'user_id' => auth()->id(),
        'price' => $property->price
    ]);
}
```

### 10.11.2 Auditoría de Cambios

```php
// Observer para auditoría
class PropertyObserver
{
    public function updated(Property $property)
    {
        Log::info('Property updated', [
            'property_id' => $property->id,
            'user_id' => auth()->id(),
            'changes' => $property->getChanges()
        ]);
    }
    
    public function deleted(Property $property)
    {
        Log::warning('Property deleted', [
            'property_id' => $property->id,
            'user_id' => auth()->id(),
            'property_data' => $property->toArray()
        ]);
    }
}
```

## 10.12 Seguridad en Producción

### 10.12.1 Variables de Entorno

```env
# Nunca en control de versiones
APP_KEY=base64:generated_key_here
APP_DEBUG=false
APP_ENV=production

# Claves seguras
DB_PASSWORD=strong_random_password
PAYPAL_SECRET=secure_secret
AWS_SECRET_ACCESS_KEY=secure_key
```

### 10.12.2 Configuración Recomendada

```php
// config/app.php
'debug' => env('APP_DEBUG', false),
'env' => env('APP_ENV', 'production'),

// config/session.php
'secure' => env('SESSION_SECURE_COOKIE', true), // Solo HTTPS
'http_only' => true, // No accesible desde JavaScript
'same_site' => 'lax', // Protección CSRF adicional

// config/database.php
'options' => [
    PDO::ATTR_EMULATE_PREPARES => false, // Prepared statements reales
    PDO::ATTR_STRINGIFY_FETCHES => false,
]
```

### 10.12.3 Checklist de Seguridad

- [ ] Debug mode desactivado en producción
- [ ] HTTPS habilitado y forzado
- [ ] Variables sensibles en .env
- [ ] Backups regulares encriptados
- [ ] Rate limiting configurado
- [ ] Headers de seguridad activos
- [ ] Logs monitoreados
- [ ] Dependencias actualizadas
- [ ] CSRF activado
- [ ] Validación de entrada exhaustiva
- [ ] Policies implementadas
- [ ] Archivos subidos validados
- [ ] Contraseñas hasheadas
- [ ] Email verification activo

## Documentos Relacionados

- **Anterior**: [API](09-API.md)
- **Siguiente**: [Configuración](11-CONFIGURACION.md)
- **Ver también**: [Funcionalidades](04-FUNCIONALIDADES.md) - Autenticación y autorización
- **Ver también**: [Integraciones](08-INTEGRACIONES.md) - reCAPTCHA

---

[← Volver al Índice](README.md) | [Siguiente: Configuración →](11-CONFIGURACION.md)
