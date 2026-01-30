# 14. Flujos de Trabajo

## 14.1 Introducción

Este documento describe los flujos de trabajo principales del sistema ABCmio, proporcionando una visión clara de cómo interactúan los diferentes componentes para completar tareas específicas.

## 14.2 Flujo de Registro de Usuario

### 14.2.1 Diagrama

```
Usuario → Formulario Registro → Validación → Crear Usuario → Enviar Email → Verificar Email → Usuario Activo
```

### 14.2.2 Descripción Detallada

**Paso 1: Usuario accede al formulario de registro**
- URL: `/es/register`
- Vista: `resources/views/auth/register.blade.php`
- Incluye protección reCAPTCHA

**Paso 2: Usuario envía formulario**
```php
POST /register
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "g-recaptcha-response": "token..."
}
```

**Paso 3: Validación (RegisterController)**
```php
- name: required|string|max:255
- email: required|email|unique:users
- password: required|min:8|confirmed
- reCAPTCHA: required|captcha
```

**Paso 4: Creación de usuario**
```php
User::create([
    'name' => $data['name'],
    'email' => $data['email'],
    'password' => Hash::make($data['password']),
    'verification_token' => Str::random(40),
    'credits' => 0
]);
```

**Paso 5: Envío de email de verificación**
```php
event(new NewUserRegisteredEvent($user));
→ Listener envía WelcomeNewUserMail
```

**Paso 6: Usuario verifica email**
- Click en link: `/v/{token}`
- HomeController@verify_email
- Marca email_verified_at = now()

**Paso 7: Usuario autenticado y activo**

### 14.2.3 Posibles Escenarios

| Escenario | Resultado |
|-----------|-----------|
| Email ya existe | Error de validación |
| Contraseña débil | Error de validación |
| reCAPTCHA inválido | Error de validación |
| Email no verificado | Puede iniciar sesión pero features limitadas |
| Todo correcto | Usuario activo completo |

## 14.3 Flujo de Publicación de Propiedad

### 14.3.1 Diagrama

```
Usuario → Crear Propiedad → Subir Imágenes → Verificar Créditos → Publicar → Descontar Crédito → Propiedad Activa
```

### 14.3.2 Descripción Detallada

**Paso 1: Crear propiedad en estado draft**
```
Usuario autenticado → /properties/create
PropertyController@create
```

**Paso 2: Completar formulario**
```php
POST /properties
{
    "title": "Apartamento en Madrid",
    "description": "Hermoso apartamento...",
    "price": 150000,
    "country_id": 1,
    "city_id": 5,
    "category_id": 10,
    "property_type": "sale",
    "area": 85,
    "bedrooms": 2,
    "bathrooms": 1
}
```

**Paso 3: Validación y creación**
```php
// StorePropertyRequest valida datos
$property = Property::create([
    ...
    'user_id' => auth()->id(),
    'status' => 'draft',
    'is_public' => false
]);
```

**Paso 4: Subir imágenes**
```php
// Dropzone AJAX
POST /property/gallery/{property}/upload

$property->addMedia($file)
         ->toMediaCollection('images');
```

**Paso 5: Decidir publicar**
```
/publish/{property} → PropertyController@publish
Mostrar formulario de publicación
```

**Paso 6: Verificar créditos**
```php
if (auth()->user()->credits < 1) {
    return back()->with('error', 'Sin créditos');
}
```

**Paso 7: Publicar y descontar crédito**
```php
POST /publish/{property}

// Descontar crédito
auth()->user()->decrement('credits', 1);

// Actualizar propiedad
$property->update([
    'status' => 'published',
    'is_public' => true,
    'published_at' => now(),
    'expires_at' => now()->addDays(30)
]);

// Registrar orden
Order::create([
    'user_id' => auth()->id(),
    'property_id' => $property->id,
    'credits' => 1,
    'action' => 'publish'
]);
```

**Paso 8: Propiedad visible públicamente**

### 14.3.3 Estados de Propiedad

```
draft → published → expired
  ↓         ↓
private ← ────┘
```

## 14.4 Flujo de Compra de Créditos

### 14.4.1 Diagrama

```
Usuario → Seleccionar Paquete → PayPal → Aprobar Pago → Callback → Agregar Créditos → Email Confirmación
```

### 14.4.2 Descripción Detallada

**Paso 1: Ver paquetes disponibles**
```
/credits → CreditController@index
Muestra: Credit::where('is_active', true)->get()
```

**Paso 2: Seleccionar paquete y confirmar**
```
/paypal/pay/{credit} → PaymentController@payform
```

**Paso 3: Crear pago en PayPal**
```php
POST /paypal/pay

// Configurar pago
$payer = new Payer();
$amount = new Amount();
$amount->setCurrency('USD')->setTotal($credit->price);

// Crear transacción
$payment = new Payment();
$payment->create($apiContext);

// Guardar en sesión
session(['credit_id' => $credit->id]);

// Redirigir a PayPal
return redirect($payment->getApprovalLink());
```

**Paso 4: Usuario aprueba en PayPal**
- Usuario en página de PayPal
- Inicia sesión y aprueba pago
- PayPal redirige a callback

**Paso 5: Callback de PayPal**
```php
GET /paypal/status?paymentId=xxx&PayerID=yyy

PaymentController@getPaymentStatus

// Obtener pago
$payment = Payment::get($paymentId, $apiContext);

// Ejecutar pago
$execution = new PaymentExecution();
$execution->setPayerId($payerId);
$result = $payment->execute($execution, $apiContext);
```

**Paso 6: Verificar estado y agregar créditos**
```php
if ($result->getState() == 'approved') {
    $creditPackage = Credit::find(session('credit_id'));
    
    // Agregar créditos
    auth()->user()->increment('credits', $creditPackage->credits);
    
    // Registrar orden
    Order::create([
        'user_id' => auth()->id(),
        'credit_id' => $creditPackage->id,
        'amount' => $creditPackage->price,
        'status' => 'completed',
        'transaction_id' => $paymentId
    ]);
    
    // Enviar email
    Mail::to(auth()->user())->send(new OrderCompleteMail($order));
}
```

**Paso 7: Confirmación al usuario**
```
Redirect → /credits con mensaje de éxito
```

### 14.4.3 Manejo de Errores

| Error | Acción |
|-------|--------|
| Pago cancelado | Redirect con mensaje de cancelación |
| PayPal timeout | Retry o contactar soporte |
| Créditos no agregados | Verificar en logs, agregar manualmente |
| Email no enviado | Usuario puede ver en perfil |

## 14.5 Flujo de Búsqueda de Propiedades

### 14.5.1 Diagrama

```
Usuario → Filtros → FilterService → Eloquent Filter → Query → Resultados Paginados
```

### 14.5.2 Descripción Detallada

**Paso 1: Usuario accede al directorio**
```
GET / → DirectoryController@index
```

**Paso 2: Usuario aplica filtros**
```html
<form action="/search" method="POST">
    <select name="country_id">...</select>
    <select name="city_id">...</select>
    <select name="category_id">...</select>
    <input name="price_min">
    <input name="price_max">
    <button type="submit">Buscar</button>
</form>
```

**Paso 3: ProcessarFiltros**
```php
POST /search

DirectoryController@index($locale, Request $request)
{
    // Generar datos de filtro
    $filters = FilterService::generateDataFilter($request->all());
    
    // Aplicar filtros
    $properties = (new FilterService())->filter($request, false);
    
    return view('frontend.directories.index', compact('properties'));
}
```

**Paso 4: FilterService procesa**
```php
public function filter($request, $userAds = false)
{
    $query = Property::where('status', 'published')
                     ->where('is_public', true)
                     ->where('expires_at', '>', now());
    
    // Aplicar filtros usando EloquentFilter
    $query->filter($request->all());
    
    return $query->orderBy('created_at', 'desc')
                 ->paginate(12);
}
```

**Paso 5: PropertyFilter (ModelFilter)**
```php
class PropertyFilter extends ModelFilter
{
    public function country($id)
    {
        return $this->where('country_id', $id);
    }
    
    public function city($id)
    {
        return $this->where('city_id', $id);
    }
    
    public function category($id)
    {
        return $this->where('category_id', $id);
    }
    
    public function priceMin($price)
    {
        return $this->where('price', '>=', $price);
    }
    
    public function priceMax($price)
    {
        return $this->where('price', '<=', $price);
    }
}
```

**Paso 6: Renderizar resultados**
```blade
@forelse($properties as $property)
    @include('components.property-card', ['property' => $property])
@empty
    <p>No se encontraron propiedades</p>
@endforelse

{{ $properties->links() }}
```

## 14.6 Flujo de Reporte de Propiedad

### 14.6.1 Diagrama

```
Usuario → Ver Propiedad → Reportar → Seleccionar Motivo → Enviar → Admin Revisa → Acción
```

### 14.6.2 Descripción Detallada

**Paso 1: Usuario ve propiedad sospechosa**
```
GET /{slug} → DirectoryController@get_property_by_slug
```

**Paso 2: Click en "Reportar"**
```html
<button data-toggle="modal" data-target="#reportModal">
    Reportar
</button>
```

**Paso 3: Modal con opciones**
```php
$reportOptions = ReportService->getOptions()

// Opciones: Fraude, Info incorrecta, Imágenes inapropiadas, etc.
```

**Paso 4: Enviar reporte**
```php
POST /reports

ReportController@store
{
    'property_id' => 123,
    'report_option_id' => 1,
    'description' => 'Esta propiedad es fraudulenta porque...'
}
```

**Paso 5: Validar y crear reporte**
```php
// Verificar que no haya reportado antes
$existing = Report::where('user_id', auth()->id())
                  ->where('property_id', $propertyId)
                  ->first();

if ($existing) {
    return back()->with('error', 'Ya reportaste esta propiedad');
}

// Crear reporte
Report::create([
    'user_id' => auth()->id(),
    'property_id' => $propertyId,
    'report_option_id' => $optionId,
    'description' => $description,
    'status' => 'pending'
]);
```

**Paso 6: Admin revisa reportes**
```
GET /admin/reports → Admin\ReportController@index

// Ver reportes pendientes
$reports = Report::where('status', 'pending')
                ->with(['user', 'property', 'reportOption'])
                ->get();
```

**Paso 7: Admin toma acción**
```php
// Opciones:
1. Marcar como resuelto → report->status = 'resolved'
2. Marcar como rechazado → report->status = 'rejected'
3. Despublicar propiedad → property->is_public = false
4. Eliminar propiedad → property->delete()
5. Contactar usuario → enviar email
```

## 14.7 Flujo de Transferencia de Créditos

### 14.7.1 Diagrama

```
Usuario A → Formulario Envío → Validar Email → Verificar Créditos → Transferir → Notificar Usuario B
```

### 14.7.2 Descripción Detallada

**Paso 1: Acceder a envío de créditos**
```
GET /send/credits → UserController@sendCreditForm
```

**Paso 2: Completar formulario**
```html
<form action="/send/credits" method="POST">
    <input name="email" placeholder="Email del receptor">
    <input name="credits" type="number" min="1">
    <button type="submit">Enviar</button>
</form>
```

**Paso 3: Validar y verificar**
```php
POST /send/credits

UserController@creditsSent
{
    'email' => 'receptor@example.com',
    'credits' => 5
}

// Validar
$validated = $request->validate([
    'email' => 'required|email|exists:users,email',
    'credits' => 'required|integer|min:1'
]);

// Verificar que no sea el mismo usuario
if ($sender->id === $receiver->id) {
    return error();
}

// Verificar créditos suficientes
if ($sender->credits < $validated['credits']) {
    return error();
}
```

**Paso 4: Realizar transferencia**
```php
DB::transaction(function () use ($sender, $receiver, $credits) {
    // Descontar de emisor
    $sender->decrement('credits', $credits);
    
    // Agregar a receptor
    $receiver->increment('credits', $credits);
    
    // Registrar transacción
    Order::create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'credits' => $credits,
        'action' => 'transfer'
    ]);
    
    // Notificar por email
    Mail::to($receiver)->send(new UserSentCreditMail($sender, $credits));
    Mail::to($sender)->send(new SentCreditMail($receiver, $credits));
});
```

**Paso 5: Confirmación**
```
Redirect → /credits con mensaje de éxito
```

## 14.8 Flujo de Expiración de Propiedades

### 14.8.1 Diagrama

```
Cron Job → Scheduler → ExpirePropertyJob → Actualizar Estado → Notificar Usuario (opcional)
```

### 14.8.2 Descripción Detallada

**Paso 1: Cron ejecuta scheduler**
```bash
* * * * * cd /var/www/abcmio && php artisan schedule:run
```

**Paso 2: Scheduler ejecuta comando**
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        Property::where('status', 'published')
                ->where('expires_at', '<', now())
                ->update(['status' => 'expired']);
    })->daily();
}
```

**Paso 3: Propiedades actualizadas**
```sql
UPDATE properties 
SET status = 'expired' 
WHERE status = 'published' 
AND expires_at < NOW()
```

**Paso 4: (Opcional) Notificar usuarios**
```php
// Enviar email a propietarios
$expiredProperties = Property::where('status', 'expired')
                            ->where('notified', false)
                            ->get();

foreach ($expiredProperties as $property) {
    Mail::to($property->user)
        ->send(new PropertyExpiredMail($property));
    
    $property->update(['notified' => true]);
}
```

## 14.9 Flujo de Visitas a Propiedades

### 14.9.1 Diagrama

```
Usuario → Ver Propiedad → Registrar Visita → Incrementar Contador → Estadísticas
```

### 14.9.2 Descripción Detallada

**Paso 1: Usuario accede a propiedad**
```
GET /{slug} → DirectoryController@get_property_by_slug
```

**Paso 2: Registrar visita**
```php
// Usando shetabit/visitor
(new PropertyService())->addNewVisitor($property);

// Internamente
public function addNewVisitor(Property $property)
{
    visitor()->visit($property);
    
    return visitor($property)->count();
}
```

**Paso 3: Almacenar en BD**
```sql
INSERT INTO visitor_traffic (
    visitable_type,
    visitable_id,
    visitor,
    ip,
    method,
    created_at
) VALUES (
    'App\Property',
    123,
    'unique_visitor_hash',
    '192.168.1.1',
    'GET',
    NOW()
)
```

**Paso 4: Obtener estadísticas**
```php
// Total de visitas
$totalVisits = visitor($property)->count();

// Visitas únicas
$uniqueVisits = visitor($property)->uniqueVisitor()->count();

// Visitas hoy
$todayVisits = visitor($property)
    ->whereDate('created_at', today())
    ->count();

// Visitas esta semana
$weekVisits = visitor($property)
    ->whereBetween('created_at', [
        now()->startOfWeek(),
        now()->endOfWeek()
    ])->count();
```

## 14.10 Flujo de Carga Dinámica de Ciudades

### 14.10.1 Diagrama

```
Usuario → Seleccionar País → AJAX Request → API → Respuesta JSON → Actualizar Select
```

### 14.10.2 Descripción Detallada

**Paso 1: Usuario selecciona país**
```html
<select id="country_id" name="country_id">
    <option value="">Seleccione país</option>
    <option value="1">España</option>
    <option value="2">Francia</option>
</select>

<select id="city_id" name="city_id">
    <option value="">Seleccione ciudad</option>
</select>
```

**Paso 2: Evento change**
```javascript
$('#country_id').on('change', function() {
    const countryId = $(this).val();
    
    if (!countryId) {
        $('#city_id').html('<option value="">Seleccione ciudad</option>');
        return;
    }
    
    loadCities(countryId);
});
```

**Paso 3: AJAX request**
```javascript
function loadCities(countryId) {
    $.ajax({
        url: `/api/cities/${countryId}`,
        type: 'GET',
        success: function(cities) {
            let options = '<option value="">Seleccione ciudad</option>';
            
            cities.forEach(city => {
                options += `<option value="${city.id}">${city.name}</option>`;
            });
            
            $('#city_id').html(options);
        },
        error: function(error) {
            console.error('Error:', error);
            alert('Error al cargar ciudades');
        }
    });
}
```

**Paso 4: API responde**
```php
// ApiController
public function get_city_by_country_id($id)
{
    $cities = City::where('country_id', $id)
                  ->where('is_active', true)
                  ->select('id', 'name')
                  ->orderBy('name')
                  ->get();
    
    return response()->json($cities);
}

// Respuesta
[
    {"id": 1, "name": "Madrid"},
    {"id": 2, "name": "Barcelona"},
    {"id": 3, "name": "Valencia"}
]
```

**Paso 5: Actualizar UI**
```
Select de ciudades se llena con opciones recibidas
```

## Documentos Relacionados

- **Anterior**: [Testing](13-TESTING.md)
- **Siguiente**: [Mantenimiento](15-MANTENIMIENTO.md)
- **Ver también**: [Funcionalidades](04-FUNCIONALIDADES.md) - Descripción de funciones
- **Ver también**: [Controladores](05-CONTROLADORES.md) - Implementación

---

[← Volver al Índice](README.md) | [Siguiente: Mantenimiento →](15-MANTENIMIENTO.md)
