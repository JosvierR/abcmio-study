# 9. API

## 9.1 Introducción

ABCmio proporciona una API RESTful para interactuar con recursos del sistema programáticamente. La API sigue convenciones REST y devuelve respuestas en formato JSON.

### 9.1.1 Base URL

```
Desarrollo: http://localhost:8000/api
Producción: https://abcmio.com/api
```

### 9.1.2 Características

- **Formato**: JSON
- **Autenticación**: No requerida para endpoints públicos
- **Rate Limiting**: 60 requests/minuto
- **Versionado**: Actualmente v1 (implícito)

## 9.2 Endpoints Públicos

### 9.2.1 Obtener Ciudades por País

Obtiene la lista de ciudades de un país específico.

**Endpoint:**
```
GET /api/cities/{country_id}
```

**Parámetros:**
| Parámetro | Tipo | Ubicación | Descripción |
|-----------|------|-----------|-------------|
| country_id | integer | path | ID del país |

**Respuesta Exitosa (200):**
```json
[
    {
        "id": 1,
        "name": "Madrid",
        "country_id": 1,
        "is_active": true
    },
    {
        "id": 2,
        "name": "Barcelona",
        "country_id": 1,
        "is_active": true
    }
]
```

**Ejemplo de Uso:**
```javascript
// Usando Axios
axios.get('/api/cities/1')
    .then(response => {
        console.log(response.data);
    })
    .catch(error => {
        console.error('Error:', error);
    });

// Usando Fetch
fetch('/api/cities/1')
    .then(response => response.json())
    .then(data => {
        console.log(data);
    });
```

**Ejemplo en PHP:**
```php
// ApiController
public function get_city_by_country_id($id)
{
    $cities = City::where('country_id', $id)
                  ->where('is_active', true)
                  ->select('id', 'name', 'country_id', 'is_active')
                  ->orderBy('name')
                  ->get();
    
    return response()->json($cities);
}
```

### 9.2.2 Obtener Subcategorías

Obtiene subcategorías de una categoría padre.

**Endpoint:**
```
GET /api/category/children/{parent_id}
```

**Parámetros:**
| Parámetro | Tipo | Ubicación | Descripción |
|-----------|------|-----------|-------------|
| parent_id | integer | path | ID de categoría padre |

**Respuesta Exitosa (200):**
```json
[
    {
        "id": 10,
        "name": "Apartamentos",
        "parent_id": 1,
        "icon": "fas fa-building",
        "is_active": true
    },
    {
        "id": 11,
        "name": "Casas",
        "parent_id": 1,
        "icon": "fas fa-home",
        "is_active": true
    }
]
```

**Implementación:**
```php
public function get_sub_categories_by_parent_id($id)
{
    $categories = Category::where('parent_id', $id)
                         ->where('is_active', true)
                         ->select('id', 'name', 'parent_id', 'icon', 'is_active')
                         ->orderBy('name')
                         ->get();
    
    return response()->json($categories);
}
```

**Ejemplo de Uso:**
```javascript
// Vue.js Component
async loadSubcategories(parentId) {
    try {
        const response = await axios.get(`/api/category/children/${parentId}`);
        this.subcategories = response.data;
    } catch (error) {
        console.error('Error loading subcategories:', error);
        this.subcategories = [];
    }
}
```

### 9.2.3 Obtener Propiedades

Obtiene listado de propiedades con filtros opcionales.

**Endpoint:**
```
GET /api/properties
```

**Parámetros Query:**
| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| category_id | integer | no | Filtrar por categoría |
| country_id | integer | no | Filtrar por país |
| city_id | integer | no | Filtrar por ciudad |
| property_type | string | no | sale, rent, service |
| price_min | numeric | no | Precio mínimo |
| price_max | numeric | no | Precio máximo |
| page | integer | no | Página (paginación) |

**Respuesta Exitosa (200):**
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "title": "Apartamento en el centro",
            "slug": "apartamento-en-el-centro",
            "description": "Hermoso apartamento totalmente equipado...",
            "price": 150000.00,
            "property_type": "sale",
            "address": "Calle Principal 123",
            "area": 85.5,
            "bedrooms": 2,
            "bathrooms": 1,
            "status": "published",
            "is_public": true,
            "published_at": "2024-01-15T10:00:00.000000Z",
            "expires_at": "2024-02-15T10:00:00.000000Z",
            "created_at": "2024-01-10T15:30:00.000000Z",
            "category": {
                "id": 10,
                "name": "Apartamentos"
            },
            "city": {
                "id": 1,
                "name": "Madrid"
            },
            "country": {
                "id": 1,
                "name": "España",
                "code": "ES"
            },
            "user": {
                "id": 5,
                "name": "Juan Pérez"
            },
            "images": [
                {
                    "id": 1,
                    "url": "https://example.com/storage/properties/1/image1.jpg",
                    "thumb_url": "https://example.com/storage/properties/1/thumbs/image1.jpg"
                }
            ]
        }
    ],
    "first_page_url": "http://localhost/api/properties?page=1",
    "from": 1,
    "last_page": 5,
    "last_page_url": "http://localhost/api/properties?page=5",
    "next_page_url": "http://localhost/api/properties?page=2",
    "path": "http://localhost/api/properties",
    "per_page": 12,
    "prev_page_url": null,
    "to": 12,
    "total": 60
}
```

**Implementación:**
```php
public function get_properies(Request $request)
{
    $query = Property::where('status', 'published')
                    ->where('is_public', true)
                    ->where('expires_at', '>', now());
    
    // Aplicar filtros
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    
    if ($request->filled('country_id')) {
        $query->where('country_id', $request->country_id);
    }
    
    if ($request->filled('city_id')) {
        $query->where('city_id', $request->city_id);
    }
    
    if ($request->filled('property_type')) {
        $query->where('property_type', $request->property_type);
    }
    
    if ($request->filled('price_min')) {
        $query->where('price', '>=', $request->price_min);
    }
    
    if ($request->filled('price_max')) {
        $query->where('price', '<=', $request->price_max);
    }
    
    // Cargar relaciones
    $query->with(['category', 'city', 'country', 'user']);
    
    // Paginar
    $properties = $query->orderBy('created_at', 'desc')
                       ->paginate(12);
    
    // Agregar URLs de imágenes
    $properties->getCollection()->transform(function($property) {
        $property->images = $property->getMedia('images')->map(function($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb_url' => $media->getUrl('thumb')
            ];
        });
        return $property;
    });
    
    return response()->json($properties);
}
```

**Ejemplo de Uso:**
```javascript
// Búsqueda con filtros
async searchProperties(filters) {
    const params = new URLSearchParams();
    
    if (filters.category_id) params.append('category_id', filters.category_id);
    if (filters.city_id) params.append('city_id', filters.city_id);
    if (filters.price_min) params.append('price_min', filters.price_min);
    if (filters.price_max) params.append('price_max', filters.price_max);
    
    try {
        const response = await axios.get(`/api/properties?${params.toString()}`);
        return response.data;
    } catch (error) {
        console.error('Error fetching properties:', error);
        throw error;
    }
}

// Uso
const result = await searchProperties({
    category_id: 10,
    city_id: 1,
    price_min: 100000,
    price_max: 300000
});

console.log('Total properties:', result.total);
console.log('Properties:', result.data);
```

## 9.3 Endpoints Admin (Requieren Autenticación)

### 9.3.1 Listar Países (Admin)

**Endpoint:**
```
GET /api/admin/countries
```

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Respuesta:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "España",
            "code": "ES",
            "is_active": true,
            "cities_count": 52,
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

**Implementación:**
```php
// Api\CountryController
public function index()
{
    $countries = Country::withCount('cities')
                       ->orderBy('name')
                       ->get();
    
    return response()->json([
        'data' => $countries
    ]);
}
```

### 9.3.2 Listar Ciudades (Admin)

**Endpoint:**
```
GET /api/admin/cities
```

**Parámetros Query:**
| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| country_id | integer | Filtrar por país |
| search | string | Buscar por nombre |

**Respuesta:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Madrid",
            "country_id": 1,
            "country": {
                "id": 1,
                "name": "España"
            },
            "is_active": true,
            "properties_count": 125
        }
    ]
}
```

## 9.4 Códigos de Estado HTTP

| Código | Significado | Uso |
|--------|-------------|-----|
| 200 | OK | Petición exitosa |
| 201 | Created | Recurso creado exitosamente |
| 204 | No Content | Operación exitosa sin contenido |
| 400 | Bad Request | Datos inválidos |
| 401 | Unauthorized | No autenticado |
| 403 | Forbidden | Sin permisos |
| 404 | Not Found | Recurso no encontrado |
| 422 | Unprocessable Entity | Validación fallida |
| 429 | Too Many Requests | Rate limit excedido |
| 500 | Internal Server Error | Error del servidor |

## 9.5 Manejo de Errores

### 9.5.1 Formato de Errores

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "El campo email es obligatorio."
        ],
        "password": [
            "El campo password debe tener al menos 8 caracteres."
        ]
    }
}
```

### 9.5.2 Error 404

```json
{
    "message": "No query results for model [App\\Property] 999"
}
```

### 9.5.3 Error de Rate Limit

```json
{
    "message": "Too Many Attempts."
}
```

**Headers de Rate Limit:**
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
Retry-After: 60
```

## 9.6 Paginación

### 9.6.1 Estructura de Respuesta Paginada

```json
{
    "current_page": 1,
    "data": [...],
    "first_page_url": "http://localhost/api/properties?page=1",
    "from": 1,
    "last_page": 10,
    "last_page_url": "http://localhost/api/properties?page=10",
    "next_page_url": "http://localhost/api/properties?page=2",
    "path": "http://localhost/api/properties",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 150
}
```

### 9.6.2 Parámetros de Paginación

| Parámetro | Tipo | Default | Descripción |
|-----------|------|---------|-------------|
| page | integer | 1 | Número de página |
| per_page | integer | 12 | Items por página |

**Ejemplo:**
```
GET /api/properties?page=2&per_page=20
```

## 9.7 Rate Limiting

### 9.7.1 Configuración

```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // 60 requests por minuto
    Route::get('properties', 'ApiController@get_properies');
});

// Diferentes límites por endpoint
Route::get('cities/{id}', 'ApiController@get_city_by_country_id')
     ->middleware('throttle:120,1'); // 120 requests/minuto
```

### 9.7.2 Headers de Respuesta

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
```

Cuando se excede:
```
HTTP/1.1 429 Too Many Requests
Retry-After: 60
```

## 9.8 CORS

### 9.8.1 Configuración

```php
// config/cors.php
return [
    'paths' => ['api/*'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
    ],
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => false,
];
```

## 9.9 Autenticación API (Futuro)

### 9.9.1 Laravel Sanctum (Recomendado)

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

**Configuración:**
```php
// app/Http/Kernel.php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

**Generar Token:**
```php
// User Model
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}

// Controlador
$token = $user->createToken('api-token')->plainTextToken;

return response()->json([
    'token' => $token
]);
```

**Uso:**
```http
GET /api/user/properties
Authorization: Bearer {token}
```

## 9.10 Ejemplos de Integración

### 9.10.1 Cliente JavaScript

```javascript
class ABCmioAPI {
    constructor(baseURL = '/api') {
        this.baseURL = baseURL;
    }
    
    async getCities(countryId) {
        const response = await fetch(`${this.baseURL}/cities/${countryId}`);
        if (!response.ok) throw new Error('Error fetching cities');
        return response.json();
    }
    
    async getSubcategories(parentId) {
        const response = await fetch(`${this.baseURL}/category/children/${parentId}`);
        if (!response.ok) throw new Error('Error fetching subcategories');
        return response.json();
    }
    
    async searchProperties(filters = {}) {
        const params = new URLSearchParams(filters);
        const response = await fetch(`${this.baseURL}/properties?${params}`);
        if (!response.ok) throw new Error('Error searching properties');
        return response.json();
    }
}

// Uso
const api = new ABCmioAPI();

// Obtener ciudades de España
const cities = await api.getCities(1);

// Buscar propiedades
const properties = await api.searchProperties({
    category_id: 10,
    city_id: 1,
    price_min: 100000,
    price_max: 300000
});
```

### 9.10.2 Cliente PHP

```php
<?php

class ABCmioAPIClient
{
    private $baseURL;
    private $client;
    
    public function __construct($baseURL = 'https://abcmio.com/api')
    {
        $this->baseURL = $baseURL;
        $this->client = new \GuzzleHttp\Client();
    }
    
    public function getCities($countryId)
    {
        $response = $this->client->get("{$this->baseURL}/cities/{$countryId}");
        return json_decode($response->getBody(), true);
    }
    
    public function getSubcategories($parentId)
    {
        $response = $this->client->get("{$this->baseURL}/category/children/{$parentId}");
        return json_decode($response->getBody(), true);
    }
    
    public function searchProperties(array $filters = [])
    {
        $queryString = http_build_query($filters);
        $response = $this->client->get("{$this->baseURL}/properties?{$queryString}");
        return json_decode($response->getBody(), true);
    }
}

// Uso
$api = new ABCmioAPIClient();

$cities = $api->getCities(1);
$properties = $api->searchProperties([
    'category_id' => 10,
    'city_id' => 1,
    'price_min' => 100000,
    'price_max' => 300000
]);
```

### 9.10.3 Cliente Python

```python
import requests

class ABCmioAPI:
    def __init__(self, base_url='https://abcmio.com/api'):
        self.base_url = base_url
        
    def get_cities(self, country_id):
        response = requests.get(f'{self.base_url}/cities/{country_id}')
        response.raise_for_status()
        return response.json()
    
    def get_subcategories(self, parent_id):
        response = requests.get(f'{self.base_url}/category/children/{parent_id}')
        response.raise_for_status()
        return response.json()
    
    def search_properties(self, **filters):
        response = requests.get(f'{self.base_url}/properties', params=filters)
        response.raise_for_status()
        return response.json()

# Uso
api = ABCmioAPI()

cities = api.get_cities(1)
properties = api.search_properties(
    category_id=10,
    city_id=1,
    price_min=100000,
    price_max=300000
)
```

## 9.11 Testing de la API

### 9.11.1 Usando cURL

```bash
# Obtener ciudades
curl -X GET "http://localhost:8000/api/cities/1"

# Obtener subcategorías
curl -X GET "http://localhost:8000/api/category/children/1"

# Buscar propiedades
curl -X GET "http://localhost:8000/api/properties?category_id=10&city_id=1&price_min=100000"
```

### 9.11.2 Usando Postman

**Collection Structure:**
```
ABCmio API
├── Public
│   ├── Get Cities by Country
│   ├── Get Subcategories
│   └── Search Properties
└── Admin
    ├── List Countries
    └── List Cities
```

**Environment Variables:**
```json
{
    "base_url": "http://localhost:8000",
    "api_url": "{{base_url}}/api"
}
```

## Documentos Relacionados

- **Anterior**: [Integraciones](08-INTEGRACIONES.md)
- **Siguiente**: [Seguridad](10-SEGURIDAD.md)
- **Ver también**: [Controladores](05-CONTROLADORES.md) - Implementación de controladores API
- **Ver también**: [Frontend](07-FRONTEND.md) - Consumo de API desde frontend

---

[← Volver al Índice](README.md) | [Siguiente: Seguridad →](10-SEGURIDAD.md)
