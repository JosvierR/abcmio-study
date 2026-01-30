# 13. Testing

## 13.1 Introducción

ABCmio utiliza PHPUnit para testing unitario y de integración. El sistema de testing verifica la funcionalidad correcta del código y previene regresiones.

### 13.1.1 Tipos de Tests

| Tipo | Ubicación | Propósito |
|------|-----------|-----------|
| **Unit Tests** | `tests/Unit/` | Probar clases individuales aisladas |
| **Feature Tests** | `tests/Feature/` | Probar flujos completos HTTP |
| **Browser Tests** | `tests/Browser/` | Pruebas end-to-end (Laravel Dusk) |

### 13.1.2 Configuración de Testing

```xml
<!-- phpunit.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">./app</directory>
        </whitelist>
    </filter>
    <php>
        <server name="APP_ENV" value="testing"/>
        <server name="BCRYPT_ROUNDS" value="4"/>
        <server name="CACHE_DRIVER" value="array"/>
        <server name="DB_CONNECTION" value="sqlite"/>
        <server name="DB_DATABASE" value=":memory:"/>
        <server name="MAIL_MAILER" value="array"/>
        <server name="QUEUE_CONNECTION" value="sync"/>
        <server name="SESSION_DRIVER" value="array"/>
    </php>
</phpunit>
```

## 13.2 Configuración del Entorno de Testing

### 13.2.1 Base de Datos de Testing

```env
# .env.testing
APP_ENV=testing
APP_DEBUG=true
APP_KEY=base64:testing_key_here

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
MAIL_MAILER=array
```

### 13.2.2 TestCase Base

```php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Configuración adicional para todos los tests
        $this->withoutExceptionHandling();
    }
    
    /**
     * Helper para crear usuario autenticado
     */
    protected function signIn($user = null)
    {
        $user = $user ?? factory(\App\User::class)->create();
        $this->actingAs($user);
        
        return $user;
    }
}
```

## 13.3 Unit Tests

### 13.3.1 Testing de Modelos

```php
<?php

namespace Tests\Unit;

use App\Property;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_property_belongs_to_a_user()
    {
        $property = factory(Property::class)->create();
        
        $this->assertInstanceOf(User::class, $property->user);
    }
    
    /** @test */
    public function a_property_has_a_category()
    {
        $property = factory(Property::class)->create();
        
        $this->assertNotNull($property->category);
        $this->assertEquals('Apartamentos', $property->category->name);
    }
    
    /** @test */
    public function a_property_can_be_published()
    {
        $property = factory(Property::class)->create([
            'status' => 'draft'
        ]);
        
        $property->publish();
        
        $this->assertEquals('published', $property->fresh()->status);
        $this->assertTrue($property->fresh()->is_public);
    }
    
    /** @test */
    public function property_generates_unique_slug()
    {
        $property1 = factory(Property::class)->create([
            'title' => 'Casa en Madrid'
        ]);
        
        $property2 = factory(Property::class)->create([
            'title' => 'Casa en Madrid'
        ]);
        
        $this->assertEquals('casa-en-madrid', $property1->slug);
        $this->assertEquals('casa-en-madrid-1', $property2->slug);
    }
    
    /** @test */
    public function property_can_check_if_expired()
    {
        $property = factory(Property::class)->create([
            'expires_at' => now()->subDay()
        ]);
        
        $this->assertTrue($property->isExpired());
    }
}
```

### 13.3.2 Testing de Servicios

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
        $this->assertEquals(1, $service->getPropertyVisitors($property));
    }
    
    /** @test */
    public function it_can_get_report_options()
    {
        $service = new PropertyService();
        $options = $service->getReportOptions();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $options);
        $this->assertGreaterThan(0, $options->count());
    }
}
```

### 13.3.3 Testing de Helpers

```php
<?php

namespace Tests\Unit;

use App\Helpers\Util;
use Tests\TestCase;

class UtilTest extends TestCase
{
    /** @test */
    public function it_formats_price_correctly()
    {
        $formatted = Util::formatPrice(1500.50);
        
        $this->assertEquals('$1,500.50', $formatted);
    }
    
    /** @test */
    public function it_truncates_text()
    {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        $truncated = Util::truncate($text, 20);
        
        $this->assertEquals('Lorem ipsum dolor...', $truncated);
    }
    
    /** @test */
    public function it_sanitizes_html()
    {
        $html = '<p>Hello</p><script>alert("xss")</script>';
        $sanitized = Util::sanitizeHtml($html);
        
        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringContainsString('<p>Hello</p>', $sanitized);
    }
}
```

## 13.4 Feature Tests

### 13.4.1 Testing de Autenticación

```php
<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_user_can_register()
    {
        $response = $this->post(route('register', 'es'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        
        $response->assertRedirect(route('home', 'es'));
        $this->assertCount(1, User::all());
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }
    
    /** @test */
    public function a_user_can_login()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'password123')
        ]);
        
        $response = $this->post(route('login', 'es'), [
            'email' => $user->email,
            'password' => $password
        ]);
        
        $response->assertRedirect(route('home', 'es'));
        $this->assertAuthenticatedAs($user);
    }
    
    /** @test */
    public function invalid_credentials_are_rejected()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('password123')
        ]);
        
        $response = $this->post(route('login', 'es'), [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);
        
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
    
    /** @test */
    public function a_user_can_logout()
    {
        $this->signIn();
        
        $response = $this->post(route('logout', 'es'));
        
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
```

### 13.4.2 Testing de Propiedades

```php
<?php

namespace Tests\Feature;

use App\Property;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function authenticated_user_can_create_property()
    {
        $user = $this->signIn();
        
        $response = $this->post(route('properties.store', 'es'), [
            'title' => 'Test Property',
            'description' => 'A very long description with more than 50 characters',
            'price' => 150000,
            'country_id' => 1,
            'city_id' => 1,
            'category_id' => 1,
            'property_type' => 'sale'
        ]);
        
        $response->assertRedirect();
        $this->assertCount(1, Property::all());
        $this->assertDatabaseHas('properties', [
            'title' => 'Test Property',
            'user_id' => $user->id
        ]);
    }
    
    /** @test */
    public function guest_cannot_create_property()
    {
        $response = $this->post(route('properties.store', 'es'), [
            'title' => 'Test Property'
        ]);
        
        $response->assertRedirect(route('login', 'es'));
        $this->assertCount(0, Property::all());
    }
    
    /** @test */
    public function user_can_update_own_property()
    {
        $user = $this->signIn();
        $property = factory(Property::class)->create(['user_id' => $user->id]);
        
        $response = $this->patch(route('properties.update', ['es', $property]), [
            'title' => 'Updated Title',
            'description' => 'Updated description with more than 50 characters here',
            'price' => 200000,
            'country_id' => 1,
            'city_id' => 1,
            'category_id' => 1,
            'property_type' => 'sale'
        ]);
        
        $response->assertRedirect();
        $this->assertEquals('Updated Title', $property->fresh()->title);
    }
    
    /** @test */
    public function user_cannot_update_others_property()
    {
        $this->signIn();
        $property = factory(Property::class)->create();
        
        $response = $this->patch(route('properties.update', ['es', $property]), [
            'title' => 'Hacked Title'
        ]);
        
        $response->assertStatus(403);
        $this->assertNotEquals('Hacked Title', $property->fresh()->title);
    }
    
    /** @test */
    public function user_can_delete_own_property()
    {
        $user = $this->signIn();
        $property = factory(Property::class)->create(['user_id' => $user->id]);
        
        $response = $this->delete(route('properties.destroy', ['es', $property]));
        
        $response->assertRedirect();
        $this->assertCount(0, Property::all());
    }
}
```

### 13.4.3 Testing de API

```php
<?php

namespace Tests\Feature\Api;

use App\City;
use App\Category;
use App\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_returns_cities_by_country()
    {
        $cities = factory(City::class, 3)->create(['country_id' => 1]);
        
        $response = $this->getJson('/api/cities/1');
        
        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'country_id']
                 ]);
    }
    
    /** @test */
    public function it_returns_subcategories()
    {
        $parent = factory(Category::class)->create();
        $children = factory(Category::class, 2)->create(['parent_id' => $parent->id]);
        
        $response = $this->getJson("/api/category/children/{$parent->id}");
        
        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }
    
    /** @test */
    public function it_returns_paginated_properties()
    {
        factory(Property::class, 15)->create([
            'status' => 'published',
            'is_public' => true,
            'expires_at' => now()->addDays(30)
        ]);
        
        $response = $this->getJson('/api/properties');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'current_page',
                     'total',
                     'per_page'
                 ]);
    }
    
    /** @test */
    public function it_filters_properties_by_category()
    {
        factory(Property::class, 3)->create([
            'category_id' => 1,
            'status' => 'published',
            'is_public' => true
        ]);
        
        factory(Property::class, 2)->create([
            'category_id' => 2,
            'status' => 'published',
            'is_public' => true
        ]);
        
        $response = $this->getJson('/api/properties?category_id=1');
        
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }
}
```

### 13.4.4 Testing de Créditos

```php
<?php

namespace Tests\Feature;

use App\User;
use App\Credit;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreditTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function user_can_view_credit_packages()
    {
        $user = $this->signIn();
        factory(Credit::class, 3)->create(['is_active' => true]);
        
        $response = $this->get(route('credits.index', 'es'));
        
        $response->assertStatus(200)
                 ->assertViewHas('credits');
    }
    
    /** @test */
    public function user_can_transfer_credits_to_another_user()
    {
        $sender = $this->signIn(factory(User::class)->create(['credits' => 10]));
        $receiver = factory(User::class)->create(['credits' => 0]);
        
        $response = $this->post(route('sent.credits', 'es'), [
            'email' => $receiver->email,
            'credits' => 5
        ]);
        
        $response->assertRedirect();
        $this->assertEquals(5, $sender->fresh()->credits);
        $this->assertEquals(5, $receiver->fresh()->credits);
    }
    
    /** @test */
    public function user_cannot_transfer_more_credits_than_they_have()
    {
        $sender = $this->signIn(factory(User::class)->create(['credits' => 3]));
        $receiver = factory(User::class)->create();
        
        $response = $this->post(route('sent.credits', 'es'), [
            'email' => $receiver->email,
            'credits' => 10
        ]);
        
        $response->assertSessionHasErrors();
        $this->assertEquals(3, $sender->fresh()->credits);
    }
}
```

## 13.5 Factories

### 13.5.1 User Factory

```php
<?php

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'credits' => 0,
        'type' => 'user',
    ];
});

$factory->state(User::class, 'admin', [
    'type' => 'admin',
]);
```

### 13.5.2 Property Factory

```php
<?php

use App\Property;
use App\User;
use App\Category;
use App\Country;
use App\City;
use Faker\Generator as Faker;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'category_id' => factory(Category::class),
        'country_id' => factory(Country::class),
        'city_id' => factory(City::class),
        'title' => $faker->sentence,
        'description' => $faker->paragraph(5),
        'slug' => $faker->unique()->slug,
        'price' => $faker->randomFloat(2, 50000, 500000),
        'property_type' => $faker->randomElement(['sale', 'rent', 'service']),
        'address' => $faker->address,
        'area' => $faker->randomFloat(2, 50, 500),
        'bedrooms' => $faker->numberBetween(1, 5),
        'bathrooms' => $faker->numberBetween(1, 3),
        'status' => 'draft',
        'is_public' => false,
    ];
});

$factory->state(Property::class, 'published', [
    'status' => 'published',
    'is_public' => true,
    'published_at' => now(),
    'expires_at' => now()->addDays(30),
]);
```

## 13.6 Ejecución de Tests

### 13.6.1 Comandos Básicos

```bash
# Ejecutar todos los tests
vendor/bin/phpunit

# Ejecutar tests específicos
vendor/bin/phpunit tests/Feature/PropertyTest.php

# Ejecutar un método específico
vendor/bin/phpunit --filter testUserCanCreateProperty

# Ejecutar con coverage
vendor/bin/phpunit --coverage-html coverage

# Ejecutar solo unit tests
vendor/bin/phpunit tests/Unit

# Ejecutar solo feature tests
vendor/bin/phpunit tests/Feature
```

### 13.6.2 Alias en Composer

```json
{
    "scripts": {
        "test": "vendor/bin/phpunit",
        "test-coverage": "vendor/bin/phpunit --coverage-html coverage",
        "test-filter": "vendor/bin/phpunit --filter"
    }
}
```

```bash
# Uso
composer test
composer test-coverage
composer test-filter testUserCanLogin
```

## 13.7 Code Coverage

### 13.7.1 Generar Reporte

```bash
# HTML Report
vendor/bin/phpunit --coverage-html coverage

# Abrir en navegador
open coverage/index.html

# Text Report
vendor/bin/phpunit --coverage-text

# Clover XML (para CI)
vendor/bin/phpunit --coverage-clover coverage.xml
```

### 13.7.2 Configuración en phpunit.xml

```xml
<logging>
    <log type="coverage-html" target="coverage"/>
    <log type="coverage-text" target="php://stdout"/>
</logging>
```

## 13.8 Continuous Integration

### 13.8.1 GitHub Actions

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: 7.4
        extensions: mbstring, xml, zip, bcmath, gd
        coverage: xdebug
    
    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"
    
    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
    
    - name: Generate key
      run: php artisan key:generate
    
    - name: Directory Permissions
      run: chmod -R 777 storage bootstrap/cache
    
    - name: Create Database
      run: |
        mkdir -p database
        touch database/database.sqlite
    
    - name: Execute tests
      env:
        DB_CONNECTION: sqlite
        DB_DATABASE: database/database.sqlite
      run: vendor/bin/phpunit --coverage-text
```

## 13.9 Testing Best Practices

### 13.9.1 Buenas Prácticas

**1. Nombres Descriptivos:**
```php
// ✅ Bueno
public function user_can_update_own_property()

// ❌ Malo
public function test1()
```

**2. Arrange-Act-Assert:**
```php
public function test_example()
{
    // Arrange - Preparar datos
    $user = factory(User::class)->create();
    
    // Act - Ejecutar acción
    $response = $this->actingAs($user)->get('/profile');
    
    // Assert - Verificar resultado
    $response->assertStatus(200);
}
```

**3. Un Assert por Test:**
```php
// ✅ Preferido
public function user_can_login()
{
    $user = factory(User::class)->create();
    $response = $this->post('/login', [/* ... */]);
    
    $response->assertRedirect('/home');
}

public function user_is_authenticated_after_login()
{
    $user = factory(User::class)->create();
    $this->post('/login', [/* ... */]);
    
    $this->assertAuthenticatedAs($user);
}
```

**4. Tests Independientes:**
```php
// Cada test debe poder ejecutarse solo
use RefreshDatabase; // Reset DB entre tests
```

**5. No Testear Framework:**
```php
// ❌ No testear funcionalidad de Laravel
public function eloquent_can_save_model()
{
    $user = new User(['name' => 'John']);
    $user->save();
    
    $this->assertDatabaseHas('users', ['name' => 'John']);
}

// ✅ Testear lógica de negocio
public function user_receives_welcome_email_on_registration()
{
    Mail::fake();
    
    $this->post('/register', $userData);
    
    Mail::assertSent(WelcomeMail::class);
}
```

## Documentos Relacionados

- **Anterior**: [Despliegue](12-DESPLIEGUE.md)
- **Siguiente**: [Flujos de Trabajo](14-FLUJOS-DE-TRABAJO.md)
- **Ver también**: [Funcionalidades](04-FUNCIONALIDADES.md) - Features a testear
- **Ver también**: [API](09-API.md) - Testing de endpoints

---

[← Volver al Índice](README.md) | [Siguiente: Flujos de Trabajo →](14-FLUJOS-DE-TRABAJO.md)
