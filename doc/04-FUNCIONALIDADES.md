# 4. Funcionalidades del Sistema

## 4.1 Gestión de Usuarios

### 4.1.1 Registro y Autenticación

**Registro de Nuevos Usuarios**

El sistema implementa un proceso completo de registro con verificación de email:

```php
// RegisterController
public function register(Request $request)
{
    // Validación de datos
    $this->validator($request->all())->validate();
    
    // Creación del usuario
    $user = $this->create($request->all());
    
    // Envío de email de verificación
    event(new NewUserRegisteredEvent($user));
    
    // Inicio de sesión automático
    $this->guard()->login($user);
    
    return redirect($this->redirectPath());
}
```

**Características del Registro:**
- Email único requerido
- Validación de contraseña (mínimo 8 caracteres)
- Verificación de email obligatoria
- Protección reCAPTCHA contra bots
- Envío automático de email de bienvenida

**Inicio de Sesión**

```php
// LoginController
public function login(Request $request)
{
    // Validación de credenciales
    $credentials = $request->only('email', 'password');
    
    // Intento de autenticación
    if (Auth::attempt($credentials, $request->remember)) {
        return redirect()->intended('home');
    }
    
    return back()->withErrors(['email' => 'Credenciales incorrectas']);
}
```

**Recuperación de Contraseña**

El sistema incluye flujo completo de reset de contraseña:
- Solicitud de reset por email
- Token temporal de seguridad
- Enlace de reset con expiración
- Actualización segura de contraseña

### 4.1.2 Gestión de Perfil

**Edición de Perfil**

Los usuarios pueden actualizar su información personal:

```php
// UserController@profile_update
public function profile_update(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'avatar' => 'nullable|image|max:2048'
    ]);
    
    // Actualización de datos
    $user->update($validated);
    
    // Procesamiento de avatar si existe
    if ($request->hasFile('avatar')) {
        $user->addMedia($request->file('avatar'))
             ->toMediaCollection('avatars');
    }
    
    return redirect()->route('profile')->with('success', 'Perfil actualizado');
}
```

**Datos del Perfil:**
- Nombre completo
- Email (único en el sistema)
- Teléfono de contacto
- Avatar/foto de perfil
- Información adicional opcional

### 4.1.3 Sistema de Roles

**Roles Disponibles:**

1. **Usuario Regular (user)**: Usuario estándar con permisos básicos
2. **Administrador (admin)**: Acceso completo al panel de administración

```php
// Middleware para verificar rol admin
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::prefix('admin')->group(function () {
        // Rutas de administración
    });
});
```

## 4.2 Gestión de Propiedades

### 4.2.1 Creación de Propiedades

**Formulario de Creación**

Los usuarios autenticados pueden crear anuncios de propiedades:

```php
// PropertyController@create
public function create()
{
    $countries = Country::with('cities')->get();
    $categories = Category::whereNull('parent_id')
                          ->with('children')
                          ->get();
    
    return view('properties.create', compact('countries', 'categories'));
}

// PropertyController@store
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'country_id' => 'required|exists:countries,id',
        'city_id' => 'required|exists:cities,id',
        'category_id' => 'required|exists:categories,id',
        'property_type' => 'required|in:sale,rent,service',
        'address' => 'nullable|string|max:500',
        'area' => 'nullable|numeric',
        'bedrooms' => 'nullable|integer',
        'bathrooms' => 'nullable|integer'
    ]);
    
    // Crear propiedad asociada al usuario
    $property = Auth::user()->properties()->create($validated);
    
    // Generar slug único
    $property->generateSlug();
    
    return redirect()->route('properties.show', $property)
                     ->with('success', 'Propiedad creada exitosamente');
}
```

**Campos Obligatorios:**
- Título descriptivo
- Descripción completa
- Precio
- País y ciudad
- Categoría
- Tipo (venta/alquiler/servicio)

**Campos Opcionales:**
- Dirección exacta
- Área/superficie (m²)
- Número de habitaciones
- Número de baños
- Características adicionales

### 4.2.2 Gestión de Imágenes

**Sistema de Galería**

Integración con Spatie Media Library para gestión de imágenes:

```php
// PropertyController@upload
public function upload(Request $request, Property $property)
{
    $request->validate([
        'file' => 'required|image|max:5120' // Max 5MB
    ]);
    
    // Agregar imagen a la colección
    $media = $property->addMedia($request->file('file'))
                      ->withCustomProperties(['order' => $property->media->count()])
                      ->toMediaCollection('images');
    
    // Optimización automática de imagen
    // (manejado por spatie/laravel-image-optimizer)
    
    return response()->json([
        'success' => true,
        'media_id' => $media->id,
        'url' => $media->getUrl()
    ]);
}

// PhotoController@delete
public function delete(Request $request, Property $property, $key)
{
    // Autorización
    $this->authorize('update', $property);
    
    // Eliminar imagen
    $media = $property->media()->find($key);
    if ($media) {
        $media->delete();
    }
    
    return response()->json(['success' => true]);
}
```

**Características de Imágenes:**
- Formatos: JPG, PNG, GIF
- Tamaño máximo: 5MB por imagen
- Optimización automática
- Thumbnails generados automáticamente
- Orden personalizable
- Eliminación individual

### 4.2.3 Publicación y Estados

**Estados de Propiedad:**

1. **Borrador (draft)**: Creada pero no publicada
2. **Publicada (published)**: Visible públicamente
3. **Expirada (expired)**: Período de publicación terminado
4. **Privada (private)**: Oculta temporalmente

**Proceso de Publicación:**

```php
// PropertyController@publishing
public function publishing(Request $request, Property $property)
{
    // Verificar que el usuario tenga créditos
    if (Auth::user()->credits < 1) {
        return back()->with('error', 'No tienes créditos suficientes');
    }
    
    // Validar duración de publicación
    $request->validate([
        'duration' => 'required|integer|in:30,60,90' // días
    ]);
    
    // Descontar crédito
    Auth::user()->decrement('credits', 1);
    
    // Actualizar propiedad
    $property->update([
        'status' => 'published',
        'published_at' => now(),
        'expires_at' => now()->addDays($request->duration)
    ]);
    
    // Registrar transacción
    Order::create([
        'user_id' => Auth::id(),
        'property_id' => $property->id,
        'credits' => 1,
        'action' => 'publish',
        'duration' => $request->duration
    ]);
    
    return redirect()->route('properties.show', $property)
                     ->with('success', 'Propiedad publicada exitosamente');
}
```

**Extensión de Publicación:**

```php
// PropertyController@extending
public function extending(Request $request, Property $property)
{
    // Verificar créditos
    if (Auth::user()->credits < 1) {
        return back()->with('error', 'No tienes créditos suficientes');
    }
    
    // Extender período
    $property->update([
        'expires_at' => $property->expires_at->addDays(30)
    ]);
    
    // Descontar crédito
    Auth::user()->decrement('credits', 1);
    
    return redirect()->back()
                     ->with('success', 'Publicación extendida por 30 días');
}
```

### 4.2.4 Edición y Eliminación

**Edición de Propiedades:**

```php
// PropertyController@update
public function update(Request $request, Property $property)
{
    // Autorización mediante Policy
    $this->authorize('update', $property);
    
    // Validación
    $validated = $request->validate([
        // Reglas de validación...
    ]);
    
    // Actualización
    $property->update($validated);
    
    return redirect()->route('properties.show', $property)
                     ->with('success', 'Propiedad actualizada');
}
```

**Eliminación:**

```php
// PropertyController@destroy
public function destroy(Property $property)
{
    // Autorización
    $this->authorize('delete', $property);
    
    // Eliminar imágenes asociadas
    $property->clearMediaCollection('images');
    
    // Eliminar propiedad
    $property->delete();
    
    return redirect()->route('properties.index')
                     ->with('success', 'Propiedad eliminada');
}
```

## 4.3 Sistema de Créditos

### 4.3.1 Concepto de Créditos

Los créditos son la moneda virtual del sistema que permite:
- Publicar nuevas propiedades (1 crédito)
- Extender publicaciones existentes (1 crédito)
- Destacar anuncios (funcionalidad futura)

### 4.3.2 Compra de Créditos

**Paquetes Disponibles:**

Los administradores configuran paquetes de créditos:

```php
// Estructura de paquete en base de datos
$credit_package = [
    'name' => 'Paquete Básico',
    'credits' => 5,
    'price' => 10.00, // USD
    'discount' => 0,
    'is_active' => true
];
```

**Proceso de Compra vía PayPal:**

```php
// PaymentController@payWithpaypal
public function payWithpaypal(Request $request)
{
    $credit = Credit::findOrFail($request->credit_id);
    
    // Configurar pago PayPal
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    
    $amount = new Amount();
    $amount->setCurrency('USD')
           ->setTotal($credit->price);
    
    $transaction = new Transaction();
    $transaction->setAmount($amount)
                ->setDescription("Compra de {$credit->credits} créditos");
    
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl(route('paypal.status'))
                 ->setCancelUrl(route('paypal.status'));
    
    $payment = new Payment();
    $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
    
    // Crear pago
    try {
        $payment->create($this->apiContext);
    } catch (\Exception $ex) {
        return redirect()->route('credits.index')
                         ->with('error', 'Error en PayPal');
    }
    
    // Redirigir a PayPal
    return redirect($payment->getApprovalLink());
}
```

**Confirmación de Pago:**

```php
// PaymentController@getPaymentStatus
public function getPaymentStatus(Request $request)
{
    $paymentId = $request->paymentId;
    $payerId = $request->PayerID;
    
    if (!$paymentId || !$payerId) {
        return redirect()->route('credits.index')
                         ->with('error', 'Pago cancelado');
    }
    
    // Ejecutar pago
    $payment = Payment::get($paymentId, $this->apiContext);
    $execution = new PaymentExecution();
    $execution->setPayerId($payerId);
    
    $result = $payment->execute($execution, $this->apiContext);
    
    if ($result->getState() == 'approved') {
        // Agregar créditos al usuario
        $user = Auth::user();
        $creditPackage = Credit::find(session('credit_id'));
        
        $user->increment('credits', $creditPackage->credits);
        
        // Registrar orden
        Order::create([
            'user_id' => $user->id,
            'credit_id' => $creditPackage->id,
            'amount' => $creditPackage->price,
            'status' => 'completed',
            'transaction_id' => $paymentId
        ]);
        
        return redirect()->route('credits.index')
                         ->with('success', 'Créditos agregados exitosamente');
    }
    
    return redirect()->route('credits.index')
                     ->with('error', 'Error en el pago');
}
```

### 4.3.3 Transferencia de Créditos

**Transferencia entre Usuarios:**

```php
// UserController@creditsSent
public function creditsSent(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email|exists:users,email',
        'credits' => 'required|integer|min:1'
    ]);
    
    $sender = Auth::user();
    $receiver = User::where('email', $validated['email'])->first();
    
    // Validar que no sea el mismo usuario
    if ($sender->id === $receiver->id) {
        return back()->with('error', 'No puedes enviarte créditos a ti mismo');
    }
    
    // Validar créditos suficientes
    if ($sender->credits < $validated['credits']) {
        return back()->with('error', 'No tienes suficientes créditos');
    }
    
    // Realizar transferencia
    DB::transaction(function () use ($sender, $receiver, $validated) {
        $sender->decrement('credits', $validated['credits']);
        $receiver->increment('credits', $validated['credits']);
        
        // Registrar transacción
        Order::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'credits' => $validated['credits'],
            'action' => 'transfer'
        ]);
        
        // Notificar por email
        Mail::to($receiver)->send(new UserSentCreditMail($sender, $validated['credits']));
    });
    
    return redirect()->route('credits.index')
                     ->with('success', 'Créditos transferidos exitosamente');
}
```

### 4.3.4 Historial de Transacciones

Los usuarios pueden ver su historial de créditos:
- Compras realizadas
- Créditos usados en publicaciones
- Transferencias enviadas/recibidas
- Fecha y estado de cada transacción

## 4.4 Búsqueda y Filtrado

### 4.4.1 Búsqueda Básica

**Búsqueda por Texto:**

```php
// DirectoryController@index
public function index(Request $request)
{
    $query = Property::where('status', 'published')
                     ->where('expires_at', '>', now());
    
    // Búsqueda por palabra clave
    if ($request->filled('q')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', "%{$request->q}%")
              ->orWhere('description', 'like', "%{$request->q}%");
        });
    }
    
    $properties = $query->paginate(12);
    
    return view('frontend.directory.index', compact('properties'));
}
```

### 4.4.2 Filtros Avanzados

**Implementación con Eloquent Filter:**

```php
// PropertyFilter (ModelFilter)
class PropertyFilter extends ModelFilter
{
    // Filtro por categoría
    public function category($id)
    {
        return $this->where('category_id', $id);
    }
    
    // Filtro por país
    public function country($id)
    {
        return $this->where('country_id', $id);
    }
    
    // Filtro por ciudad
    public function city($id)
    {
        return $this->where('city_id', $id);
    }
    
    // Filtro por tipo
    public function propertyType($type)
    {
        return $this->where('property_type', $type);
    }
    
    // Filtro por rango de precio
    public function priceMin($price)
    {
        return $this->where('price', '>=', $price);
    }
    
    public function priceMax($price)
    {
        return $this->where('price', '<=', $price);
    }
    
    // Filtro por área
    public function areaMin($area)
    {
        return $this->where('area', '>=', $area);
    }
    
    public function areaMax($area)
    {
        return $this->where('area', '<=', $area);
    }
}

// Uso en el controlador
$properties = Property::filter($request->all())
                      ->where('status', 'published')
                      ->paginate(12);
```

**Filtros Disponibles:**
- **Ubicación**: País, ciudad
- **Categoría**: Categoría y subcategorías
- **Tipo**: Venta, alquiler, servicio
- **Precio**: Rango mínimo y máximo
- **Área**: Superficie mínima y máxima
- **Habitaciones**: Número de habitaciones
- **Baños**: Número de baños

### 4.4.3 Ordenamiento

```php
// Opciones de ordenamiento
$sort_options = [
    'newest' => ['created_at', 'desc'],
    'oldest' => ['created_at', 'asc'],
    'price_low' => ['price', 'asc'],
    'price_high' => ['price', 'desc'],
    'area_large' => ['area', 'desc'],
    'area_small' => ['area', 'asc']
];

if ($request->filled('sort') && isset($sort_options[$request->sort])) {
    [$column, $direction] = $sort_options[$request->sort];
    $query->orderBy($column, $direction);
}
```

## 4.5 Sistema de Reportes

### 4.5.1 Reportar Contenido Inapropiado

**Formulario de Reporte:**

```php
// ReportController@store
public function store(Request $request)
{
    $validated = $request->validate([
        'property_id' => 'required|exists:properties,id',
        'report_option_id' => 'required|exists:report_options,id',
        'description' => 'nullable|string|max:1000'
    ]);
    
    // Crear reporte
    $report = Report::create([
        'user_id' => Auth::id(),
        'property_id' => $validated['property_id'],
        'report_option_id' => $validated['report_option_id'],
        'description' => $validated['description'],
        'status' => 'pending'
    ]);
    
    return redirect()->back()
                     ->with('success', 'Reporte enviado. Lo revisaremos pronto.');
}
```

**Opciones de Reporte:**
- Contenido fraudulento
- Información incorrecta
- Imágenes inapropiadas
- Precio incorrecto
- Propiedad duplicada
- Otros (con descripción)

### 4.5.2 Gestión de Reportes (Admin)

Los administradores pueden:
- Ver todos los reportes pendientes
- Revisar detalles del reporte y propiedad
- Marcar como resuelto o rechazado
- Tomar acciones sobre la propiedad reportada
- Contactar al usuario reportante

```php
// Admin\ReportController@update
public function update(Request $request, Report $report)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,resolved,rejected',
        'admin_notes' => 'nullable|string'
    ]);
    
    $report->update($validated);
    
    // Si se resuelve, se pueden tomar acciones
    if ($validated['status'] === 'resolved') {
        // Opciones: despublicar propiedad, contactar usuario, etc.
    }
    
    return redirect()->route('admin.reports.index')
                     ->with('success', 'Reporte actualizado');
}
```

## 4.6 Internacionalización

### 4.6.1 Idiomas Soportados

- **Español (es)**: Idioma por defecto
- **Inglés (en)**: English
- **Francés (fr)**: Français

### 4.6.2 Cambio de Idioma

**Middleware SetLocale:**

```php
// Middleware\SetLocale
public function handle($request, Closure $next)
{
    $locale = $request->segment(1);
    
    if (in_array($locale, ['es', 'en', 'fr'])) {
        App::setLocale($locale);
        session(['locale' => $locale]);
    } else {
        $locale = session('locale', 'es');
        App::setLocale($locale);
    }
    
    return $next($request);
}
```

**URLs Localizadas:**
```
/es/propiedades
/en/properties
/fr/proprietes
```

### 4.6.3 Archivos de Traducción

```php
// resources/lang/es/messages.php
return [
    'welcome' => 'Bienvenido',
    'properties' => 'Propiedades',
    'search' => 'Buscar',
    // ...
];

// resources/lang/en/messages.php
return [
    'welcome' => 'Welcome',
    'properties' => 'Properties',
    'search' => 'Search',
    // ...
];

// Uso en vistas
{{ __('messages.welcome') }}
@lang('messages.properties')
```

## 4.7 Sistema de Visitas

### 4.7.1 Tracking de Visitas

**Integración con Shetabit Visitor:**

```php
// DirectoryController@show
public function show($id)
{
    $property = Property::findOrFail($id);
    
    // Registrar visita
    visitor()->visit($property);
    
    // Obtener estadísticas
    $visits_count = visitor($property)->count();
    
    return view('frontend.directory.show', compact('property', 'visits_count'));
}
```

**Datos Capturados:**
- IP del visitante
- User agent (navegador)
- Fecha y hora
- Página visitada
- Referrer (de dónde viene)

### 4.7.2 Estadísticas de Visitas

Los propietarios pueden ver:
- Total de visitas
- Visitas únicas
- Visitas por día/semana/mes
- Gráficos de tendencia (funcionalidad futura)

## 4.8 Panel de Administración

### 4.8.1 Gestión de Países y Ciudades

**CRUD de Países:**

```php
// Admin\CountryController
class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('cities')->paginate(20);
        return view('admin.countries.index', compact('countries'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries',
            'code' => 'required|string|size:2|unique:countries',
            'is_active' => 'boolean'
        ]);
        
        Country::create($validated);
        
        return redirect()->route('admin.countries.index')
                         ->with('success', 'País creado');
    }
}
```

**CRUD de Ciudades:**

Similar al de países, con relación a país padre.

### 4.8.2 Gestión de Categorías

**Categorías Jerárquicas:**

```php
// Admin\CategoryController
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'parent_id' => 'nullable|exists:categories,id',
        'icon' => 'nullable|string|max:50',
        'is_active' => 'boolean'
    ]);
    
    $category = Category::create($validated);
    
    return redirect()->route('admin.categories.index')
                     ->with('success', 'Categoría creada');
}
```

**Estructura:**
- Categorías padre (ej: Residencial, Comercial)
- Subcategorías (ej: Casas, Apartamentos, Oficinas)

### 4.8.3 Gestión de Paquetes de Créditos

Los administradores configuran:
- Nombre del paquete
- Cantidad de créditos
- Precio en USD
- Descuento (opcional)
- Estado activo/inactivo

### 4.8.4 Gestión de Usuarios

**Funciones Administrativas:**
- Listar todos los usuarios
- Ver detalles de usuario
- Editar información de usuario
- Cambiar rol (usuario/admin)
- Desactivar/activar cuenta
- Ver propiedades del usuario
- Ajustar créditos manualmente

### 4.8.5 Gestión de Propiedades

Los administradores pueden:
- Ver todas las propiedades (incluidas no publicadas)
- Editar cualquier propiedad
- Despublicar propiedades inapropiadas
- Eliminar propiedades
- Ver estadísticas de visitas

## 4.9 Notificaciones por Email

### 4.9.1 Emails Automáticos

**Tipos de Emails:**

1. **Bienvenida**: Al registrarse nuevo usuario
2. **Verificación**: Link de verificación de email
3. **Reset de Contraseña**: Token para resetear contraseña
4. **Orden Completada**: Confirmación de compra de créditos
5. **Créditos Recibidos**: Notificación de transferencia
6. **Propiedad Creada**: Confirmación de creación
7. **Propiedad Publicada**: Confirmación de publicación

**Ejemplo de Mailable:**

```php
// Mail\WelcomeNewUserMail
class WelcomeNewUserMail extends Mailable
{
    public $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function build()
    {
        return $this->subject('Bienvenido a ABCmio')
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->user->name,
                        'email' => $this->user->email
                    ]);
    }
}

// Envío
Mail::to($user)->send(new WelcomeNewUserMail($user));
```

## 4.10 Jobs y Tareas Programadas

### 4.10.1 Jobs Asíncronos

**SendPropertyStoreJob:**

```php
// Jobs\SendPropertyStoreJob
class SendPropertyStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $property;
    
    public function handle()
    {
        // Enviar email de confirmación
        Mail::to($this->property->user)
            ->send(new PropertyCreatedMail($this->property));
    }
}

// Despacho
SendPropertyStoreJob::dispatch($property);
```

### 4.10.2 Tareas Programadas

**ExpirePropertyJob:**

```php
// Console\Kernel
protected function schedule(Schedule $schedule)
{
    // Expirar propiedades diariamente
    $schedule->call(function () {
        Property::where('status', 'published')
                ->where('expires_at', '<', now())
                ->update(['status' => 'expired']);
    })->daily();
}
```

## 4.11 Funcionalidades de Seguridad

### 4.11.1 Protección CSRF

Todos los formularios incluyen token CSRF:

```blade
<form method="POST" action="{{ route('properties.store') }}">
    @csrf
    <!-- Campos del formulario -->
</form>
```

### 4.11.2 Validación de Entrada

Validación exhaustiva en todos los endpoints:
- Tipos de datos correctos
- Valores dentro de rangos permitidos
- Sanitización de HTML
- Validación de archivos

### 4.11.3 Autorización con Policies

```php
// Policies\PropertyPolicy
public function update(User $user, Property $property)
{
    // Solo el propietario o admin puede editar
    return $user->id === $property->user_id || $user->isAdmin();
}

// Uso en controlador
$this->authorize('update', $property);
```

### 4.11.4 Rate Limiting

```php
// Protección contra spam en rutas
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/reports', 'ReportController@store');
});
```

## Documentos Relacionados

- **Anterior**: [Base de Datos](03-BASE-DE-DATOS.md)
- **Siguiente**: [Controladores](05-CONTROLADORES.md)
- **Ver también**: [API](09-API.md) - Endpoints de la API
- **Ver también**: [Seguridad](10-SEGURIDAD.md) - Detalles de seguridad

---

[← Volver al Índice](README.md) | [Siguiente: Controladores →](05-CONTROLADORES.md)
