# 5. Controladores

## 5.1 Introducción

Los controladores en ABCmio siguen el patrón MVC de Laravel y están organizados en diferentes namespaces según su función. El sistema implementa controladores RESTful con métodos estándar para operaciones CRUD.

### 5.1.1 Estructura de Controladores

```
app/Http/Controllers/
├── Controller.php                    # Controlador base
├── HomeController.php                # Página principal
├── PropertyController.php            # Gestión de propiedades (usuario)
├── DirectoryController.php           # Directorio público
├── CreditController.php              # Gestión de créditos
├── PaymentController.php             # Procesamiento de pagos
├── UserController.php                # Perfil de usuario
├── ReportController.php              # Sistema de reportes
├── SearchController.php              # Búsqueda avanzada
├── CategoryController.php            # Categorías públicas
├── PhotoController.php               # Gestión de fotos
├── Admin/                            # Controladores administrativos
│   ├── CountryController.php
│   ├── CityController.php
│   ├── CategoryController.php
│   ├── UserController.php
│   ├── CreditController.php
│   └── OrderController.php
├── Auth/                             # Autenticación
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── ForgotPasswordController.php
│   ├── ResetPasswordController.php
│   └── VerificationController.php
└── Api/                              # API Controllers
    ├── ApiController.php
    ├── CountryController.php
    └── CityController.php
```

## 5.2 Controlador Base

### 5.2.1 Controller.php

Controlador base del que heredan todos los demás:

```php
<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Country;
use App\Property;
use App\Services\FilterService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $section_name;
    protected $search_url;
    
    /**
     * Establecer nombre de sección
     */
    protected function setSectionName($name)
    {
        $this->section_name = $name;
    }
    
    /**
     * Establecer URL de búsqueda
     */
    protected function setSearchUrl($route)
    {
        $this->search_url = route($route, app()->getLocale());
    }
    
    /**
     * Obtener datos comunes para todas las vistas
     */
    protected function get_content_site($request = null, $filter = false, 
                                       $user_ads = false, $extra = [])
    {
        $countries = Country::with('cities')->get();
        $categories = Category::whereNull('parent_id')
                              ->with('children')
                              ->get();
        
        $data = [
            'countries' => $countries,
            'categories' => $categories,
            'section_name' => $this->section_name,
            'search_url' => $this->search_url
        ];
        
        // Agregar filtros si se solicitan
        if ($filter && $request) {
            $properties = (new FilterService())->filter($request, $user_ads);
            $data['properties'] = $properties;
        }
        
        return array_merge($data, $extra);
    }
}
```

**Responsabilidades:**
- Traits de Laravel (AuthorizesRequests, ValidatesRequests, etc.)
- Datos comunes para vistas (países, categorías)
- Métodos helper compartidos
- Configuración de sección y búsqueda

## 5.3 Controladores Públicos

### 5.3.1 HomeController

Gestiona la página principal y redirecciones:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    /**
     * Redirección desde raíz al locale por defecto
     */
    public function redirectHome()
    {
        $locale = session('locale', 'es');
        return redirect()->route('home', $locale);
    }
    
    /**
     * Verificación de email
     */
    public function verify_email($token)
    {
        $user = User::where('verification_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('home', app()->getLocale())
                           ->with('error', 'Token de verificación inválido');
        }
        
        if ($user->email_verified_at) {
            return redirect()->route('home', app()->getLocale())
                           ->with('info', 'Email ya verificado');
        }
        
        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null
        ]);
        
        return redirect()->route('home', app()->getLocale())
                       ->with('success', 'Email verificado exitosamente');
    }
}
```

### 5.3.2 DirectoryController

Gestiona el directorio público de propiedades:

```php
<?php

namespace App\Http\Controllers;

use App\Property;
use App\Services\FilterService;
use App\Services\PropertyService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    /**
     * Listado público de propiedades
     */
    public function index($locale, Request $request)
    {
        $this->setSectionName(trans('nav.header.nav.directory'));
        $this->setSearchUrl('search.results');
        
        $reportOptions = (new ReportService)->getOptions();
        
        return view('frontend.directories.index', compact('reportOptions'))
                   ->with($this->get_content_site($request, true));
    }
    
    /**
     * Detalle de propiedad por slug
     */
    public function get_property_by_slug($locale, $slug = null)
    {
        if (is_null($slug)) {
            return view('errors.404');
        }
        
        $property = Property::where('slug', $slug)->first();
        
        if (!$property) {
            return view('errors.404');
        }
        
        // Verificar si la propiedad es pública
        if (!$property->is_public) {
            // Solo el propietario o admin pueden ver propiedades privadas
            if (!auth()->check() || 
                (auth()->user()->id != $property->user_id && !auth()->user()->isAdmin())) {
                return redirect()->route('home', app()->getLocale())
                               ->with('warning', 'Este anuncio no está publicado');
            }
        }
        
        // Registrar visita
        (new PropertyService())->addNewVisitor($property);
        
        // Obtener opciones de reporte
        $reportOptions = (new PropertyService())->getReportOptions();
        
        return view('frontend.properties.show', compact('property', 'reportOptions'))
                   ->with($this->get_content_site(null, null, null, ['property' => $property]));
    }
    
    /**
     * Redirección desde ID a slug
     */
    public function show($id)
    {
        $property = Property::findOrFail($id);
        return redirect("/{$property->slug}");
    }
}
```

**Responsabilidades:**
- Mostrar listado público de propiedades
- Aplicar filtros de búsqueda
- Mostrar detalle de propiedad
- Registrar visitas
- Control de visibilidad (público/privado)

### 5.3.3 SearchController

Gestiona búsquedas avanzadas:

```php
<?php

namespace App\Http\Controllers;

use App\Services\FilterService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Búsqueda avanzada de propiedades
     */
    public function search(Request $request)
    {
        $this->setSectionName(trans('nav.header.nav.search'));
        $this->setSearchUrl('search.results');
        
        // Validar parámetros de búsqueda
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'property_type' => 'nullable|in:sale,rent,service',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'area_min' => 'nullable|numeric|min:0',
            'area_max' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0'
        ]);
        
        return view('frontend.search.results')
                   ->with($this->get_content_site($request, true));
    }
}
```

## 5.4 Controladores de Usuario

### 5.4.1 PropertyController

Gestiona propiedades del usuario autenticado:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Requests\PublishingPropertyRequest;
use App\Http\Requests\ExtendingPropertyRequest;
use App\Property;
use App\Repositories\PropertyRepository;
use App\Services\FilterService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    protected $limit = 20;
    
    /**
     * Mis anuncios - listado de propiedades del usuario
     */
    public function index(Request $request)
    {
        $this->setSectionName(trans('pages.my_ads.header.title'));
        $this->setSearchUrl('search.property');
        
        return view('frontend.properties.index')
                   ->with($this->get_content_site($request, true, true));
    }
    
    /**
     * Formulario de creación
     */
    public function create()
    {
        $this->setSectionName(trans('pages.forms.ads.create.title'));
        
        return view('frontend.properties.create')
                   ->with($this->get_content_site());
    }
    
    /**
     * Guardar nueva propiedad
     */
    public function store($locale, StorePropertyRequest $request)
    {
        $response = (new PropertyRepository)->create($request);
        
        return redirect()->route('properties.index', app()->getLocale())
                       ->with('success', trans('pages.forms.ads.messages.created'));
    }
    
    /**
     * Mostrar detalle
     */
    public function show($locale, Property $property)
    {
        // Autorizar acceso
        $this->authorize('view', $property);
        
        $this->setSectionName($property->title);
        
        return view('frontend.properties.show', compact('property'))
                   ->with($this->get_content_site());
    }
    
    /**
     * Formulario de edición
     */
    public function edit($locale, Property $property)
    {
        // Autorizar edición
        $this->authorize('update', $property);
        
        $this->setSectionName(trans('pages.forms.ads.edit.title'));
        
        return view('frontend.properties.edit', compact('property'))
                   ->with($this->get_content_site());
    }
    
    /**
     * Actualizar propiedad
     */
    public function update($locale, UpdatePropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);
        
        $response = (new PropertyRepository)->update($request, $property);
        
        return redirect()->route('properties.show', [app()->getLocale(), $property])
                       ->with('success', trans('pages.forms.ads.messages.updated'));
    }
    
    /**
     * Eliminar propiedad
     */
    public function destroy($locale, Property $property)
    {
        $this->authorize('delete', $property);
        
        // Eliminar imágenes
        $property->clearMediaCollection('images');
        
        // Eliminar propiedad
        $property->delete();
        
        return redirect()->route('properties.index', app()->getLocale())
                       ->with('success', trans('pages.forms.ads.messages.deleted'));
    }
    
    /**
     * Formulario de publicación
     */
    public function publish($locale, Property $property)
    {
        $this->authorize('update', $property);
        
        $this->setSectionName(trans('pages.forms.publish.title'));
        
        return view('frontend.properties.publish', compact('property'))
                   ->with($this->get_content_site());
    }
    
    /**
     * Publicar propiedad (descontar crédito)
     */
    public function publishing($locale, PublishingPropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);
        
        $user = auth()->user();
        
        // Verificar créditos
        if ($user->credits < 1) {
            return back()->with('error', trans('pages.forms.publish.messages.no_credits'));
        }
        
        // Descontar crédito
        $user->decrement('credits', 1);
        
        // Publicar propiedad
        $property->update([
            'status' => 'published',
            'is_public' => true,
            'published_at' => now(),
            'expires_at' => now()->addDays($request->duration ?? 30)
        ]);
        
        // Registrar orden
        \App\Order::create([
            'user_id' => $user->id,
            'property_id' => $property->id,
            'credits' => 1,
            'action' => 'publish',
            'duration' => $request->duration ?? 30
        ]);
        
        return redirect()->route('properties.show', [app()->getLocale(), $property])
                       ->with('success', trans('pages.forms.publish.messages.success'));
    }
    
    /**
     * Formulario de extensión
     */
    public function extended($locale, Property $property)
    {
        $this->authorize('update', $property);
        
        return view('frontend.properties.extend', compact('property'))
                   ->with($this->get_content_site());
    }
    
    /**
     * Extender publicación
     */
    public function extending($locale, ExtendingPropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);
        
        $user = auth()->user();
        
        // Verificar créditos
        if ($user->credits < 1) {
            return back()->with('error', trans('pages.forms.extend.messages.no_credits'));
        }
        
        // Descontar crédito
        $user->decrement('credits', 1);
        
        // Extender período
        $property->update([
            'expires_at' => $property->expires_at->addDays(30)
        ]);
        
        return redirect()->route('properties.show', [app()->getLocale(), $property])
                       ->with('success', trans('pages.forms.extend.messages.success'));
    }
    
    /**
     * Hacer propiedad privada (despublicar)
     */
    public function privating($locale, Property $property)
    {
        $this->authorize('update', $property);
        
        $property->update([
            'status' => 'private',
            'is_public' => false
        ]);
        
        return redirect()->back()
                       ->with('success', 'Propiedad despublicada');
    }
    
    /**
     * Admin: despublicar propiedad
     */
    public function adminPrivate($locale, Property $property)
    {
        $this->authorize('admin');
        
        $property->update([
            'status' => 'private',
            'is_public' => false
        ]);
        
        return redirect()->back()
                       ->with('success', 'Propiedad despublicada por administrador');
    }
    
    /**
     * Subir imagen
     */
    public function upload(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        
        $request->validate([
            'file' => 'required|image|max:5120'
        ]);
        
        $media = $property->addMedia($request->file('file'))
                         ->withCustomProperties(['order' => $property->media->count()])
                         ->toMediaCollection('images');
        
        return response()->json([
            'success' => true,
            'media_id' => $media->id,
            'url' => $media->getUrl()
        ]);
    }
    
    /**
     * Eliminar imagen
     */
    public function delete(Request $request, $id)
    {
        $media = \Spatie\MediaLibrary\Models\Media::findOrFail($id);
        $property = $media->model;
        
        $this->authorize('update', $property);
        
        $media->delete();
        
        return response()->json(['success' => true]);
    }
}
```

**Responsabilidades:**
- CRUD de propiedades del usuario
- Publicación y despublicación
- Extensión de período
- Gestión de imágenes
- Autorización de acciones

### 5.4.2 UserController

Gestiona perfil y acciones del usuario:

```php
<?php

namespace App\Http\Controllers;

use App\Mail\SentCreditMail;
use App\Mail\UserSentCreditMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Ver perfil
     */
    public function profile()
    {
        $user = auth()->user();
        
        return view('frontend.profile.show', compact('user'))
                   ->with($this->get_content_site());
    }
    
    /**
     * Actualizar perfil
     */
    public function profile_update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048'
        ]);
        
        // Actualizar datos básicos
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null
        ]);
        
        // Procesar avatar
        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatars');
            $user->addMedia($request->file('avatar'))
                 ->toMediaCollection('avatars');
        }
        
        return redirect()->route('profile')
                       ->with('success', 'Perfil actualizado exitosamente');
    }
    
    /**
     * Formulario de envío de créditos
     */
    public function sendCreditForm()
    {
        return view('frontend.credits.send')
                   ->with($this->get_content_site());
    }
    
    /**
     * Enviar créditos a otro usuario
     */
    public function creditsSent(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'credits' => 'required|integer|min:1'
        ]);
        
        $sender = auth()->user();
        $receiver = User::where('email', $validated['email'])->first();
        
        // Validaciones
        if ($sender->id === $receiver->id) {
            return back()->with('error', 'No puedes enviarte créditos a ti mismo');
        }
        
        if ($sender->credits < $validated['credits']) {
            return back()->with('error', 'No tienes suficientes créditos');
        }
        
        // Transferencia en transacción
        DB::transaction(function () use ($sender, $receiver, $validated) {
            $sender->decrement('credits', $validated['credits']);
            $receiver->increment('credits', $validated['credits']);
            
            // Registrar orden
            \App\Order::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'credits' => $validated['credits'],
                'action' => 'transfer'
            ]);
            
            // Enviar emails
            Mail::to($receiver)->send(new UserSentCreditMail($sender, $validated['credits']));
            Mail::to($sender)->send(new SentCreditMail($receiver, $validated['credits']));
        });
        
        return redirect()->route('credits.index', app()->getLocale())
                       ->with('success', 'Créditos transferidos exitosamente');
    }
}
```

### 5.4.3 CreditController

Gestiona paquetes de créditos y compras:

```php
<?php

namespace App\Http\Controllers;

use App\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Listar paquetes disponibles
     */
    public function index()
    {
        $credits = Credit::where('is_active', true)
                        ->orderBy('credits', 'asc')
                        ->get();
        
        $user = auth()->user();
        
        return view('frontend.credits.index', compact('credits', 'user'))
                   ->with($this->get_content_site());
    }
    
    /**
     * Ver detalle de paquete
     */
    public function show(Credit $credit)
    {
        return view('frontend.credits.show', compact('credit'))
                   ->with($this->get_content_site());
    }
}
```

### 5.4.4 PaymentController

Procesa pagos con PayPal:

```php
<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Mail\OrderCompleteMail;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaymentController extends Controller
{
    private $apiContext;
    
    public function __construct()
    {
        $this->middleware('auth');
        
        // Configurar PayPal API Context
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );
        
        $this->apiContext->setConfig(config('paypal.settings'));
    }
    
    /**
     * Formulario de pago
     */
    public function payform($locale, Credit $credit)
    {
        return view('frontend.payments.form', compact('credit'))
                   ->with($this->get_content_site());
    }
    
    /**
     * Procesar pago con PayPal
     */
    public function payWithpaypal(Request $request)
    {
        $credit = Credit::findOrFail($request->credit_id);
        
        // Guardar en sesión para posterior confirmación
        session(['credit_id' => $credit->id]);
        
        // Configurar payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        
        // Configurar monto
        $amount = new Amount();
        $amount->setCurrency('USD')
               ->setTotal($credit->price);
        
        // Configurar transacción
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setDescription("Compra de {$credit->credits} créditos - ABCmio");
        
        // URLs de retorno
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.status', app()->getLocale()))
                     ->setCancelUrl(route('paypal.status', app()->getLocale()));
        
        // Crear pago
        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));
        
        try {
            $payment->create($this->apiContext);
        } catch (\Exception $ex) {
            return redirect()->route('credits.index', app()->getLocale())
                           ->with('error', 'Error al procesar el pago con PayPal');
        }
        
        // Redirigir a PayPal
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                return redirect($link->getHref());
            }
        }
        
        return redirect()->route('credits.index', app()->getLocale())
                       ->with('error', 'Error desconocido');
    }
    
    /**
     * Callback de PayPal
     */
    public function getPaymentStatus(Request $request)
    {
        $paymentId = $request->get('paymentId');
        $payerId = $request->get('PayerID');
        
        // Verificar si fue cancelado
        if (!$paymentId || !$payerId) {
            session()->forget('credit_id');
            return redirect()->route('credits.index', app()->getLocale())
                           ->with('error', 'Pago cancelado');
        }
        
        // Obtener pago
        $payment = Payment::get($paymentId, $this->apiContext);
        
        // Ejecutar pago
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        
        try {
            $result = $payment->execute($execution, $this->apiContext);
        } catch (\Exception $ex) {
            session()->forget('credit_id');
            return redirect()->route('credits.index', app()->getLocale())
                           ->with('error', 'Error al confirmar el pago');
        }
        
        // Verificar estado
        if ($result->getState() == 'approved') {
            $user = auth()->user();
            $creditPackage = Credit::find(session('credit_id'));
            
            // Agregar créditos al usuario
            $user->increment('credits', $creditPackage->credits);
            
            // Registrar orden
            $order = Order::create([
                'user_id' => $user->id,
                'credit_id' => $creditPackage->id,
                'amount' => $creditPackage->price,
                'status' => 'completed',
                'transaction_id' => $paymentId,
                'payer_id' => $payerId
            ]);
            
            // Enviar email de confirmación
            Mail::to($user)->send(new OrderCompleteMail($order));
            
            session()->forget('credit_id');
            
            return redirect()->route('credits.index', app()->getLocale())
                           ->with('success', "¡Pago exitoso! {$creditPackage->credits} créditos agregados a tu cuenta");
        }
        
        session()->forget('credit_id');
        
        return redirect()->route('credits.index', app()->getLocale())
                       ->with('error', 'El pago no pudo ser procesado');
    }
}
```

**Responsabilidades:**
- Integración con PayPal API
- Creación de pagos
- Confirmación de transacciones
- Actualización de créditos del usuario
- Registro de órdenes

### 5.4.5 ReportController

Gestiona reportes de propiedades:

```php
<?php

namespace App\Http\Controllers;

use App\Report;
use App\Property;
use App\ReportOption;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Crear reporte
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'report_option_id' => 'required|exists:report_options,id',
            'description' => 'nullable|string|max:1000'
        ]);
        
        // Verificar que no haya reportado antes la misma propiedad
        $existing = Report::where('user_id', auth()->id())
                         ->where('property_id', $validated['property_id'])
                         ->first();
        
        if ($existing) {
            return back()->with('error', 'Ya has reportado esta propiedad');
        }
        
        // Crear reporte
        Report::create([
            'user_id' => auth()->id(),
            'property_id' => $validated['property_id'],
            'report_option_id' => $validated['report_option_id'],
            'description' => $validated['description'],
            'status' => 'pending'
        ]);
        
        return back()->with('success', 'Reporte enviado. Lo revisaremos pronto.');
    }
    
    /**
     * Eliminar reporte (solo el creador)
     */
    public function remover($id)
    {
        $report = Report::findOrFail($id);
        
        // Solo el creador puede eliminar
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }
        
        $report->delete();
        
        return back()->with('success', 'Reporte eliminado');
    }
}
```

### 5.4.6 PhotoController

Gestiona fotos de propiedades:

```php
<?php

namespace App\Http\Controllers;

use App\Property;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Actualizar orden de foto
     */
    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $property = $media->model;
        
        // Autorizar
        $this->authorize('update', $property);
        
        $validated = $request->validate([
            'order' => 'required|integer|min:0'
        ]);
        
        $media->setCustomProperty('order', $validated['order']);
        $media->save();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Eliminar foto
     */
    public function delete(Request $request, Property $property, $key)
    {
        $this->authorize('update', $property);
        
        $media = $property->media()->find($key);
        
        if ($media) {
            $media->delete();
        }
        
        return response()->json(['success' => true]);
    }
}
```

## 5.5 Controladores de Autenticación

### 5.5.1 LoginController

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    
    protected $redirectTo = '/';
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Sobrescribir redirección después de login
     */
    protected function redirectTo()
    {
        return route('home', app()->getLocale());
    }
}
```

### 5.5.2 RegisterController

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserRegisteredEvent;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers;
    
    protected $redirectTo = '/';
    
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Validador
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    
    /**
     * Crear usuario
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_token' => Str::random(40),
            'credits' => 0,
            'role' => 'user'
        ]);
        
        // Disparar evento
        event(new NewUserRegisteredEvent($user));
        
        return $user;
    }
    
    /**
     * Redirección después de registro
     */
    protected function redirectTo()
    {
        return route('home', app()->getLocale());
    }
}
```

### 5.5.3 ForgotPasswordController

Gestiona solicitud de reset de contraseña.

### 5.5.4 ResetPasswordController

Gestiona el reset de contraseña con token.

### 5.5.5 VerificationController

Gestiona verificación de email.

## 5.6 Controladores de Administración

### 5.6.1 Admin\CountryController

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Listar países
     */
    public function index()
    {
        $countries = Country::withCount('cities')
                           ->paginate(20);
        
        return view('admin.countries.index', compact('countries'));
    }
    
    /**
     * Crear país
     */
    public function create()
    {
        return view('admin.countries.create');
    }
    
    /**
     * Guardar país
     */
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
    
    /**
     * Editar país
     */
    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }
    
    /**
     * Actualizar país
     */
    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:countries,name,' . $country->id,
            'code' => 'required|string|size:2|unique:countries,code,' . $country->id,
            'is_active' => 'boolean'
        ]);
        
        $country->update($validated);
        
        return redirect()->route('admin.countries.index')
                       ->with('success', 'País actualizado');
    }
    
    /**
     * Eliminar país
     */
    public function destroy(Country $country)
    {
        // Verificar que no tenga ciudades
        if ($country->cities()->count() > 0) {
            return back()->with('error', 'No se puede eliminar. El país tiene ciudades asociadas.');
        }
        
        $country->delete();
        
        return redirect()->route('admin.countries.index')
                       ->with('success', 'País eliminado');
    }
}
```

### 5.6.2 Admin\CityController

Similar a CountryController, gestiona ciudades.

### 5.6.3 Admin\CategoryController

Gestiona categorías y subcategorías.

### 5.6.4 Admin\UserController

Gestiona usuarios del sistema.

### 5.6.5 Admin\CreditController

Gestiona paquetes de créditos.

### 5.6.6 Admin\OrderController

Gestiona órdenes y transacciones.

## 5.7 Controladores API

### 5.7.1 ApiController

```php
<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Property;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Obtener ciudades por país
     */
    public function get_city_by_country_id($id)
    {
        $cities = City::where('country_id', $id)
                     ->where('is_active', true)
                     ->select('id', 'name')
                     ->get();
        
        return response()->json($cities);
    }
    
    /**
     * Obtener subcategorías
     */
    public function get_sub_categories_by_parent_id($id)
    {
        $categories = Category::where('parent_id', $id)
                             ->where('is_active', true)
                             ->select('id', 'name', 'icon')
                             ->get();
        
        return response()->json($categories);
    }
    
    /**
     * Obtener propiedades para directorio
     */
    public function get_properies(Request $request)
    {
        $query = Property::where('status', 'published')
                        ->where('is_public', true)
                        ->where('expires_at', '>', now());
        
        // Aplicar filtros si existen
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        
        $properties = $query->with(['category', 'city', 'country', 'user'])
                           ->paginate(12);
        
        return response()->json($properties);
    }
}
```

### 5.7.2 Api\CountryController

API para países (admin).

### 5.7.3 Api\CityController

API para ciudades (admin).

## 5.8 Form Requests

### 5.8.1 StorePropertyRequest

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
            'description' => 'required|string|min:50',
            'price' => 'required|numeric|min:0',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'category_id' => 'required|exists:categories,id',
            'property_type' => 'required|in:sale,rent,service',
            'address' => 'nullable|string|max:500',
            'area' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'features' => 'nullable|array'
        ];
    }
}
```

### 5.8.2 UpdatePropertyRequest

Similar a StorePropertyRequest.

### 5.8.3 PublishingPropertyRequest

```php
public function rules()
{
    return [
        'duration' => 'required|integer|in:30,60,90'
    ];
}
```

### 5.8.4 ExtendingPropertyRequest

Validación para extender publicación.

## Documentos Relacionados

- **Anterior**: [Funcionalidades](04-FUNCIONALIDADES.md)
- **Siguiente**: [Servicios](06-SERVICIOS.md)
- **Ver también**: [API](09-API.md) - Endpoints de API
- **Ver también**: [Seguridad](10-SEGURIDAD.md) - Autorización y validación

---

[← Volver al Índice](README.md) | [Siguiente: Servicios →](06-SERVICIOS.md)
