# 8. Integraciones Externas

## 8.1 Introducción

ABCmio integra varios servicios externos para extender su funcionalidad, desde procesamiento de pagos hasta almacenamiento en la nube y optimización de imágenes.

### 8.1.1 Servicios Integrados

| Servicio | Propósito | Versión/Paquete |
|----------|-----------|-----------------|
| **PayPal** | Procesamiento de pagos | paypal/rest-api-sdk-php ^1.14 |
| **AWS S3** | Almacenamiento en la nube | league/flysystem-aws-s3-v3 ^1.0 |
| **Spatie Media Library** | Gestión de archivos multimedia | spatie/laravel-medialibrary ^7.0 |
| **Spatie Image Optimizer** | Optimización de imágenes | spatie/laravel-image-optimizer ^1.3 |
| **Google reCAPTCHA** | Protección anti-bots | anhskohbo/no-captcha ^3.4 |
| **Laravel Telescope** | Debugging y monitoring | laravel/telescope ^2.1 |
| **Shetabit Visitor** | Tracking de visitas | shetabit/visitor ^2.2.0 |

## 8.2 PayPal

### 8.2.1 Configuración

```php
// config/paypal.php
<?php

return [
    'client_id' => env('PAYPAL_CLIENT_ID',''),
    'secret' => env('PAYPAL_SECRET',''),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'), // sandbox o live
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];
```

**Variables de Entorno:**
```env
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_client_id_here
PAYPAL_SECRET=your_secret_here
```

### 8.2.2 Implementación en PaymentController

```php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

class PaymentController extends Controller
{
    private $apiContext;
    
    public function __construct()
    {
        $this->middleware('auth');
        
        // Inicializar API Context
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );
        
        $this->apiContext->setConfig(config('paypal.settings'));
    }
    
    /**
     * Crear pago PayPal
     */
    public function payWithpaypal(Request $request)
    {
        $credit = Credit::findOrFail($request->credit_id);
        
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
                    ->setDescription("Compra de {$credit->credits} créditos");
        
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
            
            // Guardar en sesión
            session(['credit_id' => $credit->id]);
            
            // Obtener URL de aprobación
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    return redirect($link->getHref());
                }
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            \Log::error('PayPal Error: ' . $ex->getMessage());
            return redirect()->route('credits.index', app()->getLocale())
                           ->with('error', 'Error al conectar con PayPal');
        }
        
        return redirect()->route('credits.index', app()->getLocale())
                       ->with('error', 'Error desconocido');
    }
    
    /**
     * Callback después de pago
     */
    public function getPaymentStatus(Request $request)
    {
        $paymentId = $request->get('paymentId');
        $payerId = $request->get('PayerID');
        
        if (!$paymentId || !$payerId) {
            session()->forget('credit_id');
            return redirect()->route('credits.index', app()->getLocale())
                           ->with('error', 'Pago cancelado');
        }
        
        try {
            $payment = Payment::get($paymentId, $this->apiContext);
            
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
            
            $result = $payment->execute($execution, $this->apiContext);
            
            if ($result->getState() == 'approved') {
                $this->processSuccessfulPayment($paymentId, $payerId);
                
                return redirect()->route('credits.index', app()->getLocale())
                               ->with('success', 'Pago exitoso. Créditos agregados a tu cuenta.');
            }
        } catch (\Exception $ex) {
            \Log::error('PayPal Execution Error: ' . $ex->getMessage());
        }
        
        session()->forget('credit_id');
        return redirect()->route('credits.index', app()->getLocale())
                       ->with('error', 'Error al procesar el pago');
    }
    
    /**
     * Procesar pago exitoso
     */
    private function processSuccessfulPayment($paymentId, $payerId)
    {
        $user = auth()->user();
        $creditPackage = Credit::find(session('credit_id'));
        
        // Agregar créditos
        $user->increment('credits', $creditPackage->credits);
        
        // Registrar orden
        Order::create([
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
    }
}
```

### 8.2.3 Webhooks (Opcional)

Para implementar webhooks de PayPal:

```php
// routes/web.php
Route::post('paypal/webhook', 'PaymentController@handleWebhook');

// PaymentController
public function handleWebhook(Request $request)
{
    $payload = $request->all();
    
    // Verificar firma
    $headers = $request->header();
    $webhookId = config('paypal.webhook_id');
    
    $isValid = \PayPal\Api\VerifyWebhookSignature::validateWebhookSignature(
        $payload,
        $headers,
        $webhookId,
        $this->apiContext
    );
    
    if ($isValid) {
        // Procesar evento
        switch ($payload['event_type']) {
            case 'PAYMENT.SALE.COMPLETED':
                // Pago completado
                break;
            case 'PAYMENT.SALE.REFUNDED':
                // Pago reembolsado
                break;
        }
    }
    
    return response()->json(['status' => 'ok']);
}
```

## 8.3 AWS S3

### 8.3.1 Configuración

```php
// config/filesystems.php
'disks' => [
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
        'visibility' => 'public',
    ],
],
```

**Variables de Entorno:**
```env
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket-name.s3.amazonaws.com
FILESYSTEM_DRIVER=s3  # o 'public' para local
```

### 8.3.2 Uso con Spatie Media Library

```php
// config/medialibrary.php
return [
    'disk_name' => env('MEDIA_DISK', 'public'), // 's3' para AWS
    
    's3' => [
        'domain' => 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com',
    ],
    
    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
            'ACL' => 'public-read',
        ],
    ],
];
```

**Cambiar a S3:**
```env
MEDIA_DISK=s3
FILESYSTEM_DRIVER=s3
```

### 8.3.3 Implementación en Modelos

```php
// Property Model
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Property extends Model implements HasMedia
{
    use HasMediaTrait;
    
    /**
     * Registrar colecciones de media
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('images')
             ->useDisk(config('medialibrary.disk_name')) // s3 o public
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
    }
}
```

## 8.4 Spatie Media Library

### 8.4.1 Configuración Completa

```php
// config/medialibrary.php
return [
    // Disco de almacenamiento
    'disk_name' => env('MEDIA_DISK', 'public'),
    
    // Tamaño máximo de archivo (10MB)
    'max_file_size' => 1024 * 1024 * 10,
    
    // Cola para generar imágenes
    'queue_name' => '',
    
    // Modelo de media
    'media_model' => Spatie\MediaLibrary\Models\Media::class,
    
    // Conversiones responsive
    'responsive_images' => [
        'use_tiny_placeholders' => true,
        'tiny_placeholder_generator_class' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator::class,
    ],
    
    // Path generator personalizado
    'path_generator' => App\Generators\PropertyCustomPathGenerator::class,
];
```

### 8.4.2 Path Generator Personalizado

```php
<?php

namespace App\Generators;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class PropertyCustomPathGenerator implements PathGenerator
{
    /**
     * Obtener path para archivo original
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . '/';
    }
    
    /**
     * Obtener path para conversiones
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . '/conversions/';
    }
    
    /**
     * Obtener path para responsive images
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . '/responsive/';
    }
    
    /**
     * Path base: properties/{property_id}/{media_id}
     */
    protected function getBasePath(Media $media): string
    {
        $modelId = $media->model_id;
        $mediaId = $media->id;
        
        return "properties/{$modelId}/{$mediaId}";
    }
}
```

### 8.4.3 Conversiones de Imágenes

```php
// Property Model
public function registerMediaConversions(Media $media = null)
{
    $this->addMediaConversion('thumb')
         ->width(300)
         ->height(300)
         ->sharpen(10)
         ->nonQueued();
    
    $this->addMediaConversion('medium')
         ->width(800)
         ->height(600)
         ->sharpen(5)
         ->nonQueued();
    
    $this->addMediaConversion('large')
         ->width(1920)
         ->height(1080)
         ->sharpen(5)
         ->nonQueued();
}
```

### 8.4.4 Uso en Controladores

```php
// Subir imagen
$property->addMedia($request->file('image'))
         ->withCustomProperties(['order' => 1])
         ->toMediaCollection('images');

// Obtener URL
$url = $property->getFirstMediaUrl('images');
$thumbUrl = $property->getFirstMediaUrl('images', 'thumb');

// Obtener todas las imágenes
$images = $property->getMedia('images');

// Eliminar imagen
$property->clearMediaCollection('images');
```

## 8.5 Spatie Image Optimizer

### 8.5.1 Configuración

```php
// config/image-optimizer.php
return [
    'optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '--strip-all',
            '--all-progressive',
            '--max=85',
        ],
        
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force',
            '--quality=85-95',
        ],
        
        Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
            '-O3',
        ],
    ],
    
    'timeout' => 60,
    
    'log_optimizer_activity' => false,
];
```

### 8.5.2 Optimización Automática

Las imágenes se optimizan automáticamente al subirlas con Spatie Media Library:

```php
// Automático con Media Library
$property->addMedia($file)
         ->toMediaCollection('images');
// La imagen se optimiza automáticamente

// Manual
use Spatie\ImageOptimizer\OptimizerChainFactory;

$optimizerChain = OptimizerChainFactory::create();
$optimizerChain->optimize($pathToImage);
```

## 8.6 Google reCAPTCHA

### 8.6.1 Configuración

```php
// config/captcha.php (generado por anhskohbo/no-captcha)
return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 30,
    ],
];
```

**Variables de Entorno:**
```env
NOCAPTCHA_SECRET=your_secret_key
NOCAPTCHA_SITEKEY=your_site_key
```

### 8.6.2 Implementación en Formularios

```blade
{{-- Formulario de registro --}}
<form method="POST" action="{{ route('register') }}">
    @csrf
    
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    
    {{-- reCAPTCHA --}}
    <div class="form-group">
        {!! NoCaptcha::renderJs() !!}
        {!! NoCaptcha::display() !!}
    </div>
    
    <button type="submit" class="btn btn-primary">Registrarse</button>
</form>
```

### 8.6.3 Validación en Controlador

```php
// RegisterController
protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'g-recaptcha-response' => ['required', 'captcha'],
    ]);
}
```

## 8.7 Laravel Telescope

### 8.7.1 Configuración

```php
// config/telescope.php
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
            'size_limit' => env('TELESCOPE_RESPONSE_SIZE_LIMIT', 64),
        ],
        
        Watchers\QueryWatcher::class => [
            'enabled' => env('TELESCOPE_QUERIES_WATCHER', true),
            'slow' => 100, // ms
        ],
        
        Watchers\LogWatcher::class => env('TELESCOPE_LOG_WATCHER', true),
        Watchers\ExceptionWatcher::class => env('TELESCOPE_EXCEPTION_WATCHER', true),
        Watchers\JobWatcher::class => env('TELESCOPE_JOB_WATCHER', true),
        Watchers\MailWatcher::class => env('TELESCOPE_MAIL_WATCHER', true),
    ],
];
```

**Variables de Entorno:**
```env
TELESCOPE_ENABLED=true  # false en producción
TELESCOPE_REQUESTS_WATCHER=true
TELESCOPE_QUERIES_WATCHER=true
TELESCOPE_LOG_WATCHER=true
```

### 8.7.2 Acceso

```
URL: https://your-domain.com/telescope
```

Protección en producción:

```php
// app/Providers/TelescopeServiceProvider.php
protected function gate()
{
    Gate::define('viewTelescope', function ($user) {
        return in_array($user->email, [
            'admin@example.com',
        ]) || $user->isAdmin();
    });
}
```

### 8.7.3 Funcionalidades

- **Requests**: Inspeccionar todas las peticiones HTTP
- **Queries**: Ver todas las queries SQL ejecutadas
- **Exceptions**: Monitorear excepciones
- **Jobs**: Seguimiento de trabajos en cola
- **Mail**: Ver emails enviados
- **Logs**: Revisar logs de la aplicación

## 8.8 Shetabit Visitor

### 8.8.1 Configuración

```php
// config/visitor.php (publicar con php artisan vendor:publish)
return [
    'default_driver' => 'eloquent',
    
    'drivers' => [
        'eloquent' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'table' => 'visitor_traffic',
        ],
    ],
];
```

### 8.8.2 Migración

```bash
php artisan vendor:publish --provider="Shetabit\Visitor\Provider\VisitorServiceProvider" --tag="migrations"
php artisan migrate
```

### 8.8.3 Uso

```php
use Shetabit\Visitor\Traits\Visitable;

class Property extends Model
{
    use Visitable;
}

// Registrar visita
visitor()->visit($property);

// Obtener estadísticas
$visits = visitor($property)->count();
$uniqueVisits = visitor($property)->uniqueVisitor()->count();

// Visitas en período
$todayVisits = visitor($property)->whereDate('created_at', today())->count();
$weekVisits = visitor($property)->whereBetween('created_at', [
    now()->startOfWeek(),
    now()->endOfWeek()
])->count();
```

## 8.9 Intervention Image

### 8.9.1 Configuración

```php
// config/image.php
return [
    'driver' => env('IMAGE_DRIVER', 'gd'), // gd o imagick
];
```

### 8.9.2 Uso

```php
use Intervention\Image\Facades\Image;

// Redimensionar imagen
$image = Image::make($path);
$image->resize(800, 600, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upsize();
});
$image->save($destinationPath);

// Crear thumbnail
$image->fit(300, 300);
$image->save($thumbPath);

// Aplicar marca de agua
$image->insert(public_path('watermark.png'), 'bottom-right', 10, 10);
```

## 8.10 Eloquent Filter

### 8.10.1 Uso

```php
// Model
use EloquentFilter\Filterable;

class Property extends Model
{
    use Filterable;
}

// PropertyFilter
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class PropertyFilter extends ModelFilter
{
    public function category($id)
    {
        return $this->where('category_id', $id);
    }
    
    public function country($id)
    {
        return $this->where('country_id', $id);
    }
    
    public function city($id)
    {
        return $this->where('city_id', $id);
    }
    
    public function priceMin($price)
    {
        return $this->where('price', '>=', $price);
    }
}

// Controlador
$properties = Property::filter($request->all())->paginate();
```

## Documentos Relacionados

- **Anterior**: [Frontend](07-FRONTEND.md)
- **Siguiente**: [API](09-API.md)
- **Ver también**: [Configuración](11-CONFIGURACION.md) - Variables de entorno
- **Ver también**: [Seguridad](10-SEGURIDAD.md) - Aspectos de seguridad

---

[← Volver al Índice](README.md) | [Siguiente: API →](09-API.md)
