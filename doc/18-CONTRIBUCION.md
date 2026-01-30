# 18. Guía de Contribución

## 18.1 Introducción

Gracias por tu interés en contribuir a ABCmio. Este documento establece las pautas y mejores prácticas para contribuir al proyecto de manera efectiva.

## 18.2 Código de Conducta

### 18.2.1 Nuestro Compromiso

Nos comprometemos a hacer de la participación en este proyecto una experiencia libre de acoso para todos, independientemente de edad, tamaño corporal, discapacidad, etnia, identidad y expresión de género, nivel de experiencia, nacionalidad, apariencia personal, raza, religión o identidad y orientación sexual.

### 18.2.2 Nuestros Estándares

**Comportamientos que contribuyen a crear un ambiente positivo:**
- Uso de lenguaje acogedor e inclusivo
- Respeto a diferentes puntos de vista y experiencias
- Aceptación de críticas constructivas
- Enfoque en lo que es mejor para la comunidad
- Empatía hacia otros miembros de la comunidad

**Comportamientos inaceptables:**
- Uso de lenguaje o imágenes sexualizadas
- Trolling, comentarios insultantes/despectivos
- Acoso público o privado
- Publicar información privada de otros sin permiso
- Conducta no profesional

## 18.3 Cómo Contribuir

### 18.3.1 Reportar Bugs

**Antes de reportar un bug:**
1. Verifica que no sea un duplicado buscando en [Issues existentes](https://github.com/JosvierR/abcmio-study/issues)
2. Determina en qué parte del código ocurre el bug
3. Recopila información sobre tu entorno

**Crear un reporte de bug efectivo:**

```markdown
**Descripción del Bug**
Una descripción clara y concisa del problema.

**Pasos para Reproducir**
1. Ve a '...'
2. Click en '....'
3. Scroll hasta '....'
4. Ver error

**Comportamiento Esperado**
Descripción clara de lo que esperabas que sucediera.

**Comportamiento Actual**
Descripción de lo que realmente sucedió.

**Screenshots**
Si aplica, agregar screenshots que ayuden a explicar el problema.

**Entorno:**
- OS: [e.g. Ubuntu 20.04]
- PHP: [e.g. 7.4]
- Laravel: [e.g. 5.8.38]
- Navegador: [e.g. Chrome 96]

**Logs/Error Messages**
```
Pegar logs relevantes aquí
```

**Contexto Adicional**
Cualquier otra información relevante sobre el problema.
```

### 18.3.2 Sugerir Mejoras

**Template para Feature Request:**

```markdown
**¿Tu solicitud de feature está relacionada con un problema?**
Una descripción clara del problema. Ej: "Siempre es frustrante cuando [...]"

**Describe la solución que te gustaría**
Descripción clara y concisa de lo que quieres que suceda.

**Describe alternativas consideradas**
Descripción de soluciones o features alternativos que hayas considerado.

**Contexto Adicional**
Cualquier otro contexto o screenshots sobre la solicitud.
```

### 18.3.3 Contribuir con Código

**Proceso:**

1. **Fork del Repositorio**
```bash
# Fork en GitHub
# Luego clonar tu fork
git clone https://github.com/TU_USUARIO/abcmio-study.git
cd abcmio-study

# Agregar upstream
git remote add upstream https://github.com/JosvierR/abcmio-study.git
```

2. **Crear Rama de Feature**
```bash
# Actualizar tu fork
git checkout main
git pull upstream main

# Crear nueva rama
git checkout -b feature/nombre-descriptivo

# O para bugfix
git checkout -b fix/descripcion-del-bug
```

3. **Hacer Cambios**
```bash
# Hacer tus cambios
# Seguir estándares de código

# Agregar tests
# Ejecutar tests
vendor/bin/phpunit

# Verificar código
composer cs-check  # Si existe
```

4. **Commit de Cambios**
```bash
# Hacer commits descriptivos
git add .
git commit -m "Add: Nueva funcionalidad X

- Implementa feature X
- Agrega tests para feature X
- Actualiza documentación"
```

5. **Push y Pull Request**
```bash
# Push a tu fork
git push origin feature/nombre-descriptivo

# Crear Pull Request en GitHub
# Incluir descripción detallada
```

## 18.4 Estándares de Código

### 18.4.1 PHP

**PSR-12 Extended Coding Style:**

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $properties = Property::where('status', 'published')
                              ->paginate(12);
        
        return view('properties.index', compact('properties'));
    }
}
```

**Reglas Importantes:**
- Indentación de 4 espacios (no tabs)
- Llaves en nueva línea para clases y métodos
- Nombres de clases en PascalCase
- Nombres de métodos en camelCase
- Nombres de variables en snake_case para base de datos
- Siempre usar strict types cuando sea posible
- Documentar métodos públicos con DocBlocks

### 18.4.2 JavaScript

**ES6+ y Airbnb Style Guide:**

```javascript
// ✅ Correcto
class PropertyManager {
    constructor(properties) {
        this.properties = properties;
    }
    
    async loadProperties(filters = {}) {
        try {
            const response = await axios.get('/api/properties', { params: filters });
            return response.data;
        } catch (error) {
            console.error('Error loading properties:', error);
            throw error;
        }
    }
}

// Uso de const/let, no var
const manager = new PropertyManager([]);

// Arrow functions
const filteredProperties = properties.filter(prop => prop.price > 100000);

// Template literals
const message = `Found ${filteredProperties.length} properties`;
```

**Reglas:**
- Usar const por defecto, let cuando sea necesario, nunca var
- Arrow functions para funciones anónimas
- Template literals para strings con variables
- Async/await sobre callbacks cuando sea posible
- Destructuring cuando mejore la legibilidad
- Punto y coma obligatorios

### 18.4.3 Blade Templates

```blade
{{-- Comentarios con guiones dobles --}}

{{-- Escape automático --}}
{{ $property->title }}

{{-- Sin escape (usar con precaución) --}}
{!! clean($property->description) !!}

{{-- Directivas en minúsculas --}}
@if($property->is_public)
    <p>Propiedad pública</p>
@endif

@foreach($properties as $property)
    @include('components.property-card', ['property' => $property])
@endforeach

{{-- Componentes --}}
<x-alert type="success" :message="$message" />
```

### 18.4.4 CSS/Sass

```scss
// Variables descriptivas
$primary-color: #3490dc;
$font-family-base: 'Nunito', sans-serif;

// Anidación razonable (máximo 3 niveles)
.property-card {
    padding: 1rem;
    border-radius: 0.5rem;
    
    .property-title {
        font-size: 1.25rem;
        font-weight: bold;
    }
    
    &:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
}

// Mobile-first
.container {
    padding: 1rem;
    
    @media (min-width: 768px) {
        padding: 2rem;
    }
}
```

## 18.5 Git Workflow

### 18.5.1 Branching Strategy

**Ramas Principales:**
- `main`: Código de producción
- `develop`: Desarrollo activo (si existe)

**Ramas de Feature:**
- `feature/nombre-feature`: Nuevas funcionalidades
- `fix/descripcion-bug`: Corrección de bugs
- `refactor/descripcion`: Refactorización
- `docs/descripcion`: Cambios en documentación
- `test/descripcion`: Agregar o mejorar tests

### 18.5.2 Commits

**Formato de Mensaje de Commit:**

```
Tipo: Descripción corta (máximo 50 caracteres)

Descripción detallada del cambio si es necesario.
Explicar el QUÉ y el POR QUÉ, no el CÓMO.

- Cambio específico 1
- Cambio específico 2

Closes #123
```

**Tipos de Commit:**
- `Add`: Nueva funcionalidad
- `Fix`: Corrección de bug
- `Update`: Actualización de funcionalidad existente
- `Refactor`: Refactorización sin cambio de funcionalidad
- `Docs`: Cambios en documentación
- `Test`: Agregar o modificar tests
- `Style`: Cambios de formato (no afectan funcionalidad)
- `Perf`: Mejora de rendimiento
- `Chore`: Tareas de mantenimiento

**Ejemplos:**

```bash
# Feature
Add: User profile editing functionality

Implements user profile page where users can:
- Update their name and email
- Upload profile picture
- Change password

Closes #45

# Bugfix
Fix: Property images not loading on Safari

The issue was caused by incorrect MIME type handling.
Changed to use browser-agnostic detection.

Fixes #89

# Refactor
Refactor: Extract payment logic to PaymentService

Moves PayPal payment processing from controller to
dedicated service class for better testability and reuse.
```

### 18.5.3 Pull Requests

**Checklist antes de crear PR:**

- [ ] Código sigue estándares del proyecto
- [ ] Tests agregados/actualizados y passing
- [ ] Documentación actualizada si es necesario
- [ ] Cambios probados localmente
- [ ] Branch actualizado con main/develop
- [ ] No hay conflictos de merge
- [ ] Commits son claros y descriptivos

**Template de Pull Request:**

```markdown
## Descripción
Descripción clara de los cambios realizados.

## Tipo de Cambio
- [ ] Bug fix (cambio que corrige un issue)
- [ ] Nueva feature (cambio que agrega funcionalidad)
- [ ] Breaking change (fix o feature que causa que funcionalidad existente no funcione como esperado)
- [ ] Documentación

## Cómo se ha probado
Descripción de tests realizados.

## Checklist
- [ ] Mi código sigue el estilo de este proyecto
- [ ] He realizado auto-review de mi código
- [ ] He comentado mi código, particularmente en áreas difíciles
- [ ] He hecho cambios correspondientes en documentación
- [ ] Mis cambios no generan nuevos warnings
- [ ] He agregado tests que prueban que mi fix es efectivo o que mi feature funciona
- [ ] Tests unitarios nuevos y existentes pasan localmente
- [ ] Cambios dependientes han sido merged y publicados

## Screenshots (si aplica)
Agregar screenshots aquí.

## Issues Relacionados
Closes #123
Related to #456
```

## 18.6 Testing

### 18.6.1 Requisitos de Testing

**Para nuevas features:**
- Tests unitarios para lógica de negocio
- Tests de feature para flujos completos
- Coverage mínimo de 70%

**Para bug fixes:**
- Test que reproduce el bug
- Test que verifica que el fix funciona

**Ejemplo:**

```php
<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyCreationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function authenticated_user_can_create_property()
    {
        $user = factory(User::class)->create();
        
        $response = $this->actingAs($user)->post(route('properties.store', 'es'), [
            'title' => 'Test Property',
            'description' => 'A long description with more than 50 characters',
            'price' => 150000,
            'country_id' => 1,
            'city_id' => 1,
            'category_id' => 1,
            'property_type' => 'sale'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'title' => 'Test Property',
            'user_id' => $user->id
        ]);
    }
}
```

## 18.7 Documentación

### 18.7.1 Código Autodocumentado

**Priorizar código claro sobre comentarios:**

```php
// ❌ No
// Obtener propiedades activas
$p = Property::where('s', 'published')->where('e', '>', now())->get();

// ✅ Sí
$activeProperties = Property::where('status', 'published')
                            ->where('expires_at', '>', now())
                            ->get();
```

### 18.7.2 Comentarios de Código

**Cuándo comentar:**
- Lógica compleja que no es obvia
- Decisiones de diseño no intuitivas
- Workarounds temporales (con TODO)
- Casos extremos manejados

```php
// Explicar el POR QUÉ, no el QUÉ
// Usamos transacción aquí porque necesitamos garantizar que
// tanto el decremento de créditos como la publicación de
// propiedad ocurran atómicamente o ninguna ocurra.
DB::transaction(function () use ($user, $property) {
    $user->decrement('credits', 1);
    $property->publish();
});
```

### 18.7.3 Actualizar Documentación

Si tu cambio afecta:
- **API**: Actualizar `doc/09-API.md`
- **Configuración**: Actualizar `doc/11-CONFIGURACION.md`
- **Base de datos**: Actualizar `doc/03-BASE-DE-DATOS.md`
- **Nuevas features**: Actualizar `doc/04-FUNCIONALIDADES.md`
- **Cambios en arquitectura**: Actualizar `doc/02-ARQUITECTURA.md`

## 18.8 Proceso de Revisión

### 18.8.1 Qué Esperar

1. **Revisión Automática:**
   - Tests de CI/CD
   - Análisis de código estático
   - Verificación de estándares

2. **Revisión Manual:**
   - Revisión de código por mantenedores
   - Feedback constructivo
   - Solicitud de cambios si es necesario

3. **Iteración:**
   - Hacer cambios solicitados
   - Actualizar PR
   - Re-revisión

4. **Merge:**
   - Aprobación de mantenedores
   - Merge a rama principal
   - Cierre de issues relacionados

### 18.8.2 Cómo Dar Feedback

**Como revisor:**
- Ser respetuoso y constructivo
- Explicar el "por qué" de los cambios sugeridos
- Distinguir entre cambios requeridos y sugerencias
- Aprobar cuando esté listo

**Ejemplos:**

```markdown
# ✅ Constructivo
Este código funciona, pero podría ser más eficiente usando 
`whereIn()` en lugar de múltiples `where()`. Ejemplo:

```php
// En lugar de:
$query->where('id', 1)->orWhere('id', 2);

// Usar:
$query->whereIn('id', [1, 2]);
```

# ❌ No constructivo
Este código es malo. Cámbialo.
```

## 18.9 Recursos Adicionales

### 18.9.1 Documentación

- [Laravel Documentation](https://laravel.com/docs/5.8)
- [PHP The Right Way](https://phptherightway.com/)
- [PSR Standards](https://www.php-fig.org/psr/)

### 18.9.2 Herramientas Útiles

- **PHP CS Fixer**: Formateo automático de código PHP
- **ESLint**: Linting de JavaScript
- **PHPStan**: Análisis estático de PHP
- **Prettier**: Formateo de código JavaScript/CSS

### 18.9.3 Comunidad

- GitHub Issues para discusiones técnicas
- Pull Requests para contribuciones de código
- Documentación para guías y tutoriales

## 18.10 Agradecimientos

Gracias a todos los que contribuyen a hacer de ABCmio un mejor proyecto. Cada contribución, grande o pequeña, es valiosa y apreciada.

### 18.10.1 Tipos de Contribución

No solo código es bienvenido:
- 📝 Mejorar documentación
- 🐛 Reportar bugs
- 💡 Sugerir features
- ✅ Escribir tests
- 🎨 Mejorar diseño UI/UX
- 🌍 Traducciones
- 📖 Tutoriales y ejemplos

**¡Toda contribución cuenta!**

---

## Documentos Relacionados

- **Anterior**: [Dependencias](17-DEPENDENCIAS.md)
- **Ver también**: [Testing](13-TESTING.md) - Guía de testing
- **Ver también**: [Arquitectura](02-ARQUITECTURA.md) - Estructura del proyecto

---

[← Volver al Índice](README.md)

**¡Gracias por contribuir a ABCmio!** 🎉
