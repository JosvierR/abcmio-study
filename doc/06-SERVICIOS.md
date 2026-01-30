# 6. Servicios

## 6.1 Introducción

La capa de servicios en ABCmio encapsula la lógica de negocio compleja que se utiliza en múltiples controladores. Esta arquitectura promueve la reutilización de código, facilita el testing y mantiene los controladores delgados y enfocados en manejar HTTP requests.

### 6.1.1 Ubicación y Estructura

```
app/Services/
├── FilterService.php           # Servicio de filtrado de propiedades
├── PropertyService.php         # Lógica de negocio de propiedades
├── ProductService.php          # Servicios de productos
├── ReportService.php           # Gestión de reportes
└── Property.php                # Servicio adicional de propiedades
```

## 6.2 PropertyService

Gestiona la lógica de negocio relacionada con propiedades.

### 6.2.1 Código Completo

```php
<?php

namespace App\Services;

use App\Property;
use App\ReportOption;
use App\Repositories\PropertyRepository;

class PropertyService
{
    /**
     * Agregar nueva visita a una propiedad
     * 
     * @param Property $property
     * @return int Total de visitas
     */
    public function addNewVisitor(Property $property)
    {
        $visitors = (new PropertyRepository())->getPropertyVisitors($property);
        $visitors += 1;
        (new PropertyRepository())->updatePropertyVisitors($property, $visitors);
        
        return $visitors;
    }
    
    /**
     * Obtener total de visitantes de una propiedad
     * 
     * @param Property $property
     * @return int
     */
    public static function getPropertyVisitors(Property $property)
    {
        return (new PropertyRepository())->getPropertyVisitors($property);
    }
    
    /**
     * Obtener opciones de reporte disponibles
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getReportOptions()
    {
        return ReportOption::orderBy('name', 'DESC')->get();
    }
}
```

### 6.2.2 Uso en Controladores

```php
// DirectoryController
use App\Services\PropertyService;

public function get_property_by_slug($locale, $slug)
{
    $property = Property::where('slug', $slug)->first();
    
    // Registrar visita usando el servicio
    (new PropertyService())->addNewVisitor($property);
    
    // Obtener opciones de reporte
    $reportOptions = (new PropertyService())->getReportOptions();
    
    return view('frontend.properties.show', compact('property', 'reportOptions'));
}
```

### 6.2.3 Métodos Principales

**addNewVisitor()**
- Incrementa contador de visitas
- Utiliza PropertyRepository para persistencia
- Retorna total actualizado

**getPropertyVisitors()**
- Método estático
- Obtiene total de visitas
- Utilizado para estadísticas

**getReportOptions()**
- Obtiene opciones para reportar propiedades
- Ordenadas por nombre descendente
- Usado en formularios de reporte

## 6.3 FilterService

Servicio especializado en filtrado y búsqueda de propiedades.

### 6.3.1 Código Completo

```php
<?php

namespace App\Services;

use App\Property;

class FilterService
{
    /**
     * Generar array de datos de filtrado
     * 
     * @param array $get_request Parámetros de la petición
     * @param mixed $user Usuario actual (opcional)
     * @return array
     */
    public static function generateDataFilter($get_request = [], $user = null): array
    {
        $dataFilter = [
            // Búsqueda por texto
            'query' => $get_request["query"] ?? null,
            
            // Filtro por ciudad
            'city' => $get_request["city"] ?? null,
            
            // Coincidencia exacta
            'exact_match' => $get_request['exact_match'] ?? null,
            
            // Filtro de publicación
            'published' => $get_request['is_publish'] ?? false,
            
            // Filtro por país
            'country' => $get_request['country_id'] ?? null,
            
            // Filtro de visibilidad pública
            'is_public' => $get_request['is_public'] ?? 
                          $get_request['is_publish'] ?? null,
            
            // Filtro por usuario
            'user' => $user->id ?? null,
            
            // Filtros de categoría jerárquicos
            'category' => [
                "parent" => $get_request['category_id'] ?? null,
                "category" => $get_request['sub_category_id'] ?? null
            ],
        ];
        
        return $dataFilter;
    }
    
    /**
     * Aplicar filtros a query de propiedades
     * 
     * @param \Illuminate\Http\Request $request
     * @param bool $userAds Si es true, filtra por propiedades del usuario
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filter($request, $userAds = false)
    {
        $user = auth()->user();
        
        // Generar datos de filtro
        $filters = self::generateDataFilter($request->all(), $user);
        
        // Query base
        $query = Property::query();
        
        // Filtrar por usuario si es necesario
        if ($userAds && $user) {
            $query->where('user_id', $user->id);
        }
        
        // Aplicar filtro usando Eloquent Filter
        $query->filter($filters);
        
        // Si no son anuncios del usuario, solo mostrar públicos
        if (!$userAds) {
            $query->where('status', 'published')
                  ->where('is_public', true)
                  ->where('expires_at', '>', now());
        }
        
        // Ordenar y paginar
        return $query->orderBy('created_at', 'desc')
                    ->paginate(12);
    }
}
```

### 6.3.2 Uso en Controladores

```php
// PropertyController - Mis anuncios
use App\Services\FilterService;

public function index(Request $request)
{
    // Filtrar propiedades del usuario autenticado
    $properties = (new FilterService())->filter($request, true);
    
    return view('frontend.properties.index', compact('properties'));
}

// DirectoryController - Directorio público
public function index($locale, Request $request)
{
    // Filtrar propiedades públicas
    $properties = (new FilterService())->filter($request, false);
    
    return view('frontend.directories.index', compact('properties'));
}
```

### 6.3.3 Estructura de Filtros

El servicio procesa múltiples tipos de filtros:

**Filtros Básicos:**
- `query`: Búsqueda de texto en título/descripción
- `city`: ID de ciudad
- `country`: ID de país
- `exact_match`: Coincidencia exacta en búsqueda

**Filtros de Estado:**
- `published`: Solo propiedades publicadas
- `is_public`: Solo propiedades públicas

**Filtros de Usuario:**
- `user`: ID del usuario propietario

**Filtros de Categoría:**
- `parent`: Categoría padre
- `category`: Subcategoría

## 6.4 ReportService

Gestiona la lógica de reportes de contenido inapropiado.

### 6.4.1 Implementación

```php
<?php

namespace App\Services;

use App\Report;
use App\ReportOption;
use App\Property;

class ReportService
{
    /**
     * Obtener todas las opciones de reporte
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOptions()
    {
        return ReportOption::orderBy('name', 'asc')->get();
    }
    
    /**
     * Crear nuevo reporte
     * 
     * @param array $data
     * @return Report
     */
    public function createReport(array $data)
    {
        return Report::create([
            'user_id' => auth()->id(),
            'property_id' => $data['property_id'],
            'report_option_id' => $data['report_option_id'],
            'description' => $data['description'] ?? null,
            'status' => 'pending'
        ]);
    }
    
    /**
     * Verificar si usuario ya reportó una propiedad
     * 
     * @param int $propertyId
     * @param int $userId
     * @return bool
     */
    public function hasUserReported($propertyId, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        return Report::where('property_id', $propertyId)
                    ->where('user_id', $userId)
                    ->exists();
    }
    
    /**
     * Obtener reportes pendientes
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingReports()
    {
        return Report::where('status', 'pending')
                    ->with(['user', 'property', 'reportOption'])
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
    
    /**
     * Resolver reporte
     * 
     * @param Report $report
     * @param string $action (resolved|rejected)
     * @param string $notes
     * @return Report
     */
    public function resolveReport(Report $report, $action, $notes = null)
    {
        $report->update([
            'status' => $action,
            'admin_notes' => $notes,
            'resolved_at' => now()
        ]);
        
        return $report;
    }
}
```

### 6.4.2 Uso

```php
// ReportController
use App\Services\ReportService;

public function store(Request $request)
{
    $reportService = new ReportService();
    
    // Verificar si ya reportó
    if ($reportService->hasUserReported($request->property_id)) {
        return back()->with('error', 'Ya has reportado esta propiedad');
    }
    
    // Crear reporte
    $reportService->createReport($request->all());
    
    return back()->with('success', 'Reporte enviado');
}

// Admin\ReportController
public function index()
{
    $reports = (new ReportService())->getPendingReports();
    
    return view('admin.reports.index', compact('reports'));
}
```

## 6.5 ProductService

Servicio para lógica de negocio de productos/propiedades.

### 6.5.1 Implementación Estimada

```php
<?php

namespace App\Services;

use App\Property;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Crear slug único para propiedad
     * 
     * @param string $title
     * @return string
     */
    public function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = 1;
        
        while (Property::where('slug', $slug)->exists()) {
            $slug = Str::slug($title) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
    
    /**
     * Calcular precio con descuento
     * 
     * @param float $price
     * @param float $discount
     * @return float
     */
    public function calculateDiscountedPrice($price, $discount)
    {
        return $price - ($price * ($discount / 100));
    }
    
    /**
     * Verificar si propiedad está expirada
     * 
     * @param Property $property
     * @return bool
     */
    public function isExpired(Property $property)
    {
        return $property->expires_at && 
               $property->expires_at->isPast();
    }
    
    /**
     * Obtener propiedades destacadas
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedProperties($limit = 6)
    {
        return Property::where('status', 'published')
                      ->where('is_public', true)
                      ->where('expires_at', '>', now())
                      ->orderBy('created_at', 'desc')
                      ->limit($limit)
                      ->get();
    }
}
```

## 6.6 Repositories

Aunque técnicamente no son servicios, los repositories complementan la capa de servicios manejando el acceso a datos.

### 6.6.1 PropertyRepository

```php
<?php

namespace App\Repositories;

use App\Property;
use App\Jobs\SendPropertyStoreJob;
use App\Jobs\SendPropertyUpdateJob;
use Illuminate\Support\Str;

class PropertyRepository
{
    /**
     * Crear nueva propiedad
     * 
     * @param \Illuminate\Http\Request $request
     * @return Property
     */
    public function create($request)
    {
        $data = $request->validated();
        
        // Crear propiedad
        $property = auth()->user()->properties()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'country_id' => $data['country_id'],
            'city_id' => $data['city_id'],
            'category_id' => $data['category_id'],
            'property_type' => $data['property_type'],
            'address' => $data['address'] ?? null,
            'area' => $data['area'] ?? null,
            'bedrooms' => $data['bedrooms'] ?? null,
            'bathrooms' => $data['bathrooms'] ?? null,
            'status' => 'draft',
            'is_public' => false,
            'slug' => $this->generateSlug($data['title'])
        ]);
        
        // Despachar job de email
        SendPropertyStoreJob::dispatch($property);
        
        return $property;
    }
    
    /**
     * Actualizar propiedad
     * 
     * @param \Illuminate\Http\Request $request
     * @param Property $property
     * @return Property
     */
    public function update($request, Property $property)
    {
        $data = $request->validated();
        
        // Actualizar datos
        $property->update($data);
        
        // Regenerar slug si cambió el título
        if (isset($data['title']) && $data['title'] !== $property->getOriginal('title')) {
            $property->update([
                'slug' => $this->generateSlug($data['title'])
            ]);
        }
        
        // Despachar job de actualización
        SendPropertyUpdateJob::dispatch($property);
        
        return $property->fresh();
    }
    
    /**
     * Generar slug único
     * 
     * @param string $title
     * @return string
     */
    protected function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = 1;
        
        while (Property::where('slug', $slug)->exists()) {
            $slug = Str::slug($title) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
    
    /**
     * Obtener visitantes de propiedad
     * 
     * @param Property $property
     * @return int
     */
    public function getPropertyVisitors(Property $property)
    {
        return visitor($property)->count();
    }
    
    /**
     * Actualizar contador de visitantes
     * 
     * @param Property $property
     * @param int $count
     */
    public function updatePropertyVisitors(Property $property, $count)
    {
        // El paquete shetabit/visitor maneja esto automáticamente
        // Este método existe para compatibilidad
        visitor()->visit($property);
    }
}
```

### 6.6.2 Uso de Repositories

```php
// PropertyController
use App\Repositories\PropertyRepository;

public function store($locale, StorePropertyRequest $request)
{
    $property = (new PropertyRepository)->create($request);
    
    return redirect()->route('properties.index', app()->getLocale())
                   ->with('success', 'Propiedad creada exitosamente');
}

public function update($locale, UpdatePropertyRequest $request, Property $property)
{
    $this->authorize('update', $property);
    
    (new PropertyRepository)->update($request, $property);
    
    return redirect()->route('properties.show', [app()->getLocale(), $property])
                   ->with('success', 'Propiedad actualizada');
}
```

## 6.7 Managers

### 6.7.1 VisitorManager

Gestiona el tracking de visitas utilizando el paquete shetabit/visitor:

```php
<?php

namespace App\Managers;

use App\Property;

class VisitorManager
{
    /**
     * Registrar visita a propiedad
     * 
     * @param Property $property
     */
    public function recordVisit(Property $property)
    {
        visitor()->visit($property);
    }
    
    /**
     * Obtener total de visitas
     * 
     * @param Property $property
     * @return int
     */
    public function getVisitCount(Property $property)
    {
        return visitor($property)->count();
    }
    
    /**
     * Obtener visitas únicas
     * 
     * @param Property $property
     * @return int
     */
    public function getUniqueVisits(Property $property)
    {
        return visitor($property)->uniqueVisitor()->count();
    }
    
    /**
     * Obtener visitas en período
     * 
     * @param Property $property
     * @param string $period (day|week|month)
     * @return int
     */
    public function getVisitsInPeriod(Property $property, $period = 'day')
    {
        switch ($period) {
            case 'day':
                return visitor($property)->whereDate('created_at', today())->count();
            case 'week':
                return visitor($property)->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count();
            case 'month':
                return visitor($property)->whereMonth('created_at', now()->month)->count();
            default:
                return 0;
        }
    }
}
```

## 6.8 Helpers

### 6.8.1 RouteHelper

```php
<?php

namespace App\Helpers;

class RouteHelper
{
    /**
     * Generar URL con locale
     * 
     * @param string $route
     * @param array $params
     * @return string
     */
    public static function localeRoute($route, $params = [])
    {
        $locale = app()->getLocale();
        
        return route($route, array_merge(['locale' => $locale], $params));
    }
    
    /**
     * Obtener locale desde URL
     * 
     * @param string $url
     * @return string|null
     */
    public static function extractLocale($url)
    {
        $segments = explode('/', parse_url($url, PHP_URL_PATH));
        
        if (count($segments) > 1 && in_array($segments[1], ['es', 'en', 'fr'])) {
            return $segments[1];
        }
        
        return null;
    }
}
```

### 6.8.2 Util

```php
<?php

namespace App\Helpers;

class Util
{
    /**
     * Formatear precio
     * 
     * @param float $price
     * @param string $currency
     * @return string
     */
    public static function formatPrice($price, $currency = 'USD')
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£'
        ];
        
        $symbol = $symbols[$currency] ?? '$';
        
        return $symbol . number_format($price, 2);
    }
    
    /**
     * Formatear área
     * 
     * @param float $area
     * @return string
     */
    public static function formatArea($area)
    {
        return number_format($area, 2) . ' m²';
    }
    
    /**
     * Truncar texto
     * 
     * @param string $text
     * @param int $length
     * @return string
     */
    public static function truncate($text, $length = 100)
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }
    
    /**
     * Sanitizar HTML
     * 
     * @param string $html
     * @return string
     */
    public static function sanitizeHtml($html)
    {
        return strip_tags($html, '<p><br><strong><em><ul><ol><li>');
    }
}
```

## 6.9 Patrones y Mejores Prácticas

### 6.9.1 Inyección de Dependencias

Preferir inyección en constructor sobre instanciación directa:

```php
// ❌ No recomendado
public function index()
{
    $properties = (new FilterService())->filter($request);
}

// ✅ Recomendado
protected $filterService;

public function __construct(FilterService $filterService)
{
    $this->filterService = $filterService;
}

public function index()
{
    $properties = $this->filterService->filter($request);
}
```

### 6.9.2 Single Responsibility

Cada servicio debe tener una responsabilidad clara:

```php
// ✅ Correcto - Servicios separados
PropertyService       // Lógica de propiedades
FilterService        // Filtrado y búsqueda
ReportService        // Gestión de reportes
PaymentService       // Procesamiento de pagos

// ❌ Incorrecto - Servicio demasiado amplio
ApplicationService   // Hace demasiadas cosas
```

### 6.9.3 Métodos Estáticos vs Instancias

Usar métodos estáticos solo para utilidades puras:

```php
// ✅ Método estático apropiado (sin estado)
public static function formatPrice($price)
{
    return '$' . number_format($price, 2);
}

// ✅ Método de instancia apropiado (usa estado/dependencias)
public function createProperty($data)
{
    return $this->repository->create($data);
}
```

### 6.9.4 Retorno de Valores

Ser consistente con tipos de retorno:

```php
// ✅ Con type hints
public function getProperties(): Collection
{
    return Property::all();
}

public function createProperty(array $data): Property
{
    return Property::create($data);
}

public function hasPermission(User $user): bool
{
    return $user->isAdmin();
}
```

## 6.10 Testing de Servicios

### 6.10.1 Unit Tests

```php
<?php

namespace Tests\Unit\Services;

use App\Services\PropertyService;
use App\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyServiceTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_can_add_visitor_to_property()
    {
        $property = factory(Property::class)->create();
        $service = new PropertyService();
        
        $visitors = $service->addNewVisitor($property);
        
        $this->assertEquals(1, $visitors);
    }
    
    /** @test */
    public function it_can_get_report_options()
    {
        $service = new PropertyService();
        $options = $service->getReportOptions();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $options);
    }
}
```

## Documentos Relacionados

- **Anterior**: [Controladores](05-CONTROLADORES.md)
- **Siguiente**: [Frontend](07-FRONTEND.md)
- **Ver también**: [Arquitectura](02-ARQUITECTURA.md) - Estructura del sistema
- **Ver también**: [Testing](13-TESTING.md) - Estrategia de testing

---

[← Volver al Índice](README.md) | [Siguiente: Frontend →](07-FRONTEND.md)
