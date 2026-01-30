# 7. Frontend

## 7.1 Introducción

El frontend de ABCmio combina múltiples tecnologías para ofrecer una experiencia de usuario moderna y responsive. Utiliza Blade como motor de plantillas principal, complementado con componentes React y Vue.js para interactividad, y Bootstrap para el diseño responsive.

### 7.1.1 Stack Frontend

| Tecnología | Versión | Uso |
|------------|---------|-----|
| **Blade** | Laravel 5.8 | Motor de plantillas principal |
| **React** | 16.2.0 | Componentes interactivos |
| **Vue.js** | 2.x | Componentes reactivos |
| **Bootstrap** | 4.6.1 | Framework CSS |
| **jQuery** | 3.3.1 | Manipulación DOM y AJAX |
| **Sass** | 1.15.2 | Preprocesador CSS |
| **Laravel Mix** | 4.0.7 | Compilación de assets |

## 7.2 Blade Templates

### 7.2.1 Estructura de Vistas

```
resources/views/
├── layouts/
│   ├── app.blade.php           # Layout principal
│   ├── admin.blade.php         # Layout admin
│   └── guest.blade.php         # Layout sin autenticación
├── frontend/
│   ├── directories/            # Directorio de propiedades
│   │   ├── index.blade.php
│   │   └── partials/
│   ├── properties/             # Gestión de propiedades
│   │   ├── index.blade.php     # Mis anuncios
│   │   ├── create.blade.php    # Crear
│   │   ├── edit.blade.php      # Editar
│   │   ├── show.blade.php      # Detalle
│   │   └── publish.blade.php   # Publicar
│   ├── profile/                # Perfil usuario
│   ├── credits/                # Créditos
│   └── pages/                  # Páginas estáticas
├── admin/                      # Panel administración
│   ├── countries/
│   ├── cities/
│   ├── categories/
│   ├── users/
│   └── reports/
├── auth/                       # Autenticación
│   ├── login.blade.php
│   ├── register.blade.php
│   └── passwords/
├── emails/                     # Templates de email
└── errors/                     # Páginas de error
    ├── 404.blade.php
    └── 500.blade.php
```

### 7.2.2 Layout Principal

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'ABCmio') }} - @yield('title')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div id="app">
        {{-- Barra de navegación --}}
        @include('layouts.partials.navbar')
        
        {{-- Contenido principal --}}
        <main class="py-4">
            {{-- Mensajes flash --}}
            @include('layouts.partials.flash-messages')
            
            {{-- Contenido de la página --}}
            @yield('content')
        </main>
        
        {{-- Footer --}}
        @include('layouts.partials.footer')
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
```

### 7.2.3 Directivas Blade Personalizadas

```blade
{{-- Formato de precio --}}
@php
    function formatPrice($price) {
        return '$' . number_format($price, 2);
    }
@endphp

{{-- Uso --}}
{{ formatPrice($property->price) }}

{{-- Verificar autenticación --}}
@auth
    <a href="{{ route('properties.create') }}">Publicar</a>
@else
    <a href="{{ route('login') }}">Iniciar Sesión</a>
@endauth

{{-- Verificar rol admin --}}
@if(auth()->check() && auth()->user()->isAdmin())
    <a href="{{ route('admin.dashboard') }}">Admin</a>
@endif

{{-- Bucle con propiedades --}}
@forelse($properties as $property)
    <div class="property-card">
        <h3>{{ $property->title }}</h3>
        <p>{{ Str::limit($property->description, 100) }}</p>
    </div>
@empty
    <p>No hay propiedades disponibles.</p>
@endforelse
```

### 7.2.4 Componentes Blade

```blade
{{-- resources/views/components/property-card.blade.php --}}
<div class="card property-card">
    @if($property->getFirstMediaUrl('images'))
        <img src="{{ $property->getFirstMediaUrl('images') }}" 
             class="card-img-top" 
             alt="{{ $property->title }}">
    @else
        <img src="{{ asset('images/no-image.png') }}" 
             class="card-img-top" 
             alt="Sin imagen">
    @endif
    
    <div class="card-body">
        <h5 class="card-title">{{ $property->title }}</h5>
        <p class="card-text">{{ Str::limit($property->description, 100) }}</p>
        
        <div class="property-meta">
            <span class="price">{{ formatPrice($property->price) }}</span>
            <span class="location">
                <i class="fas fa-map-marker-alt"></i>
                {{ $property->city->name }}, {{ $property->country->name }}
            </span>
        </div>
        
        <a href="{{ route('get.property.by.slug', [app()->getLocale(), $property->slug]) }}" 
           class="btn btn-primary">
            Ver Detalle
        </a>
    </div>
</div>

{{-- Uso del componente --}}
@include('components.property-card', ['property' => $property])
```

### 7.2.5 Partials Comunes

**Navbar:**
```blade
{{-- resources/views/layouts/partials/navbar.blade.php --}}
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home', app()->getLocale()) }}">
            {{ config('app.name', 'ABCmio') }}
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- Left Side --}}
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home', app()->getLocale()) }}">
                        {{ __('nav.directory') }}
                    </a>
                </li>
            </ul>
            
            {{-- Right Side --}}
            <ul class="navbar-nav ml-auto">
                {{-- Selector de idioma --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="langDropdown" 
                       data-toggle="dropdown">
                        {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('home', 'es') }}">Español</a>
                        <a class="dropdown-item" href="{{ route('home', 'en') }}">English</a>
                        <a class="dropdown-item" href="{{ route('home', 'fr') }}">Français</a>
                    </div>
                </li>
                
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login', app()->getLocale()) }}">
                            {{ __('auth.login') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register', app()->getLocale()) }}">
                            {{ __('auth.register') }}
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('properties.create', app()->getLocale()) }}">
                            {{ __('nav.publish') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('properties.index', app()->getLocale()) }}">
                                {{ __('nav.my_ads') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('profile', app()->getLocale()) }}">
                                {{ __('nav.profile') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('credits.index', app()->getLocale()) }}">
                                {{ __('nav.credits') }} ({{ Auth::user()->credits }})
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout', app()->getLocale()) }}"
                               onclick="event.preventDefault(); 
                                        document.getElementById('logout-form').submit();">
                                {{ __('auth.logout') }}
                            </a>
                            <form id="logout-form" 
                                  action="{{ route('logout', app()->getLocale()) }}" 
                                  method="POST" 
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
```

**Flash Messages:**
```blade
{{-- resources/views/layouts/partials/flash-messages.blade.php --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
```

## 7.3 Componentes React

### 7.3.1 Configuración

```javascript
// resources/js/app.js
require('./bootstrap');
require('./ReactJs/ChatApp');
```

```javascript
// resources/js/bootstrap.js
window._ = require('lodash');

/**
 * Cargamos jQuery y Bootstrap
 */
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    
    require('bootstrap');
} catch (e) {}

/**
 * Configuramos Axios con CSRF token
 */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}
```

### 7.3.2 Componente ChatApp

```javascript
// resources/js/ReactJs/ChatApp.js
import React from 'react';
import ReactDOM from 'react-dom';

class ChatApp extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            messages: [],
            newMessage: ''
        };
    }
    
    handleSendMessage = () => {
        const { newMessage, messages } = this.state;
        
        if (newMessage.trim()) {
            this.setState({
                messages: [...messages, {
                    id: Date.now(),
                    text: newMessage,
                    timestamp: new Date()
                }],
                newMessage: ''
            });
        }
    }
    
    render() {
        const { messages, newMessage } = this.state;
        
        return (
            <div className="chat-app">
                <div className="messages">
                    {messages.map(msg => (
                        <div key={msg.id} className="message">
                            <span className="text">{msg.text}</span>
                            <span className="time">
                                {msg.timestamp.toLocaleTimeString()}
                            </span>
                        </div>
                    ))}
                </div>
                
                <div className="input-group">
                    <input
                        type="text"
                        className="form-control"
                        value={newMessage}
                        onChange={(e) => this.setState({newMessage: e.target.value})}
                        onKeyPress={(e) => e.key === 'Enter' && this.handleSendMessage()}
                        placeholder="Escribe un mensaje..."
                    />
                    <button 
                        className="btn btn-primary"
                        onClick={this.handleSendMessage}
                    >
                        Enviar
                    </button>
                </div>
            </div>
        );
    }
}

// Montar componente si existe el elemento
if (document.getElementById('chat-app')) {
    ReactDOM.render(<ChatApp />, document.getElementById('chat-app'));
}
```

## 7.4 Componentes Vue.js

### 7.4.1 Configuración Vue

```javascript
// resources/js/app.js (extensión)
import Vue from 'vue';

// Registrar componentes globalmente
Vue.component('locations-component', require('./components/Locations.vue').default);
Vue.component('categories-component', require('./components/Categories.vue').default);
Vue.component('advanced-search', require('./components/AdvancedSearch.vue').default);
Vue.component('directory-front', require('./components/DirectoryFront.vue').default);

// Crear instancia Vue si existe el elemento
const app = new Vue({
    el: '#vue-app'
});
```

### 7.4.2 Componente Locations

```vue
<!-- resources/js/components/Locations.vue -->
<template>
    <div class="locations-component">
        <div class="form-group">
            <label>{{ labels.country }}</label>
            <select v-model="selectedCountry" 
                    @change="loadCities" 
                    class="form-control">
                <option value="">{{ labels.selectCountry }}</option>
                <option v-for="country in countries" 
                        :key="country.id" 
                        :value="country.id">
                    {{ country.name }}
                </option>
            </select>
        </div>
        
        <div class="form-group" v-if="selectedCountry">
            <label>{{ labels.city }}</label>
            <select v-model="selectedCity" class="form-control">
                <option value="">{{ labels.selectCity }}</option>
                <option v-for="city in cities" 
                        :key="city.id" 
                        :value="city.id">
                    {{ city.name }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        initialCountries: {
            type: Array,
            default: () => []
        },
        labels: {
            type: Object,
            default: () => ({
                country: 'País',
                city: 'Ciudad',
                selectCountry: 'Seleccione un país',
                selectCity: 'Seleccione una ciudad'
            })
        }
    },
    
    data() {
        return {
            countries: this.initialCountries,
            cities: [],
            selectedCountry: '',
            selectedCity: ''
        };
    },
    
    methods: {
        async loadCities() {
            if (!this.selectedCountry) {
                this.cities = [];
                this.selectedCity = '';
                return;
            }
            
            try {
                const response = await axios.get(`/api/cities/${this.selectedCountry}`);
                this.cities = response.data;
                this.selectedCity = '';
            } catch (error) {
                console.error('Error loading cities:', error);
                this.cities = [];
            }
        }
    },
    
    watch: {
        selectedCountry(val) {
            this.$emit('country-changed', val);
        },
        selectedCity(val) {
            this.$emit('city-changed', val);
        }
    }
}
</script>
```

### 7.4.3 Componente Categories

```vue
<!-- resources/js/components/Categories.vue -->
<template>
    <div class="categories-component">
        <div class="form-group">
            <label>{{ labels.category }}</label>
            <select v-model="selectedParent" 
                    @change="loadSubcategories" 
                    class="form-control">
                <option value="">{{ labels.selectCategory }}</option>
                <option v-for="category in parentCategories" 
                        :key="category.id" 
                        :value="category.id">
                    <i :class="category.icon"></i> {{ category.name }}
                </option>
            </select>
        </div>
        
        <div class="form-group" v-if="subcategories.length > 0">
            <label>{{ labels.subcategory }}</label>
            <select v-model="selectedSubcategory" class="form-control">
                <option value="">{{ labels.selectSubcategory }}</option>
                <option v-for="subcategory in subcategories" 
                        :key="subcategory.id" 
                        :value="subcategory.id">
                    {{ subcategory.name }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        initialCategories: Array,
        labels: Object
    },
    
    data() {
        return {
            parentCategories: this.initialCategories || [],
            subcategories: [],
            selectedParent: '',
            selectedSubcategory: ''
        };
    },
    
    methods: {
        async loadSubcategories() {
            if (!this.selectedParent) {
                this.subcategories = [];
                this.selectedSubcategory = '';
                return;
            }
            
            try {
                const response = await axios.get(
                    `/api/category/children/${this.selectedParent}`
                );
                this.subcategories = response.data;
                this.selectedSubcategory = '';
            } catch (error) {
                console.error('Error loading subcategories:', error);
            }
        }
    }
}
</script>
```

### 7.4.4 Componente AdvancedSearch

```vue
<!-- resources/js/components/AdvancedSearch.vue -->
<template>
    <div class="advanced-search">
        <form @submit.prevent="search">
            <div class="row">
                <div class="col-md-4">
                    <input v-model="filters.query" 
                           type="text" 
                           class="form-control" 
                           placeholder="Buscar...">
                </div>
                
                <div class="col-md-3">
                    <select v-model="filters.propertyType" class="form-control">
                        <option value="">Tipo de propiedad</option>
                        <option value="sale">Venta</option>
                        <option value="rent">Alquiler</option>
                        <option value="service">Servicio</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <input v-model="filters.priceMin" 
                           type="number" 
                           class="form-control" 
                           placeholder="Precio mín">
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        Buscar
                    </button>
                </div>
            </div>
            
            <!-- Filtros avanzados colapsables -->
            <div v-if="showAdvanced" class="advanced-filters mt-3">
                <div class="row">
                    <div class="col-md-3">
                        <input v-model="filters.priceMax" 
                               type="number" 
                               class="form-control" 
                               placeholder="Precio máx">
                    </div>
                    <div class="col-md-3">
                        <input v-model="filters.areaMin" 
                               type="number" 
                               class="form-control" 
                               placeholder="Área mín (m²)">
                    </div>
                    <div class="col-md-3">
                        <input v-model="filters.bedrooms" 
                               type="number" 
                               class="form-control" 
                               placeholder="Habitaciones">
                    </div>
                    <div class="col-md-3">
                        <input v-model="filters.bathrooms" 
                               type="number" 
                               class="form-control" 
                               placeholder="Baños">
                    </div>
                </div>
            </div>
            
            <button type="button" 
                    @click="showAdvanced = !showAdvanced" 
                    class="btn btn-link btn-sm mt-2">
                {{ showAdvanced ? 'Menos filtros' : 'Más filtros' }}
            </button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            filters: {
                query: '',
                propertyType: '',
                priceMin: '',
                priceMax: '',
                areaMin: '',
                bedrooms: '',
                bathrooms: ''
            },
            showAdvanced: false
        };
    },
    
    methods: {
        search() {
            // Construir query string
            const params = new URLSearchParams();
            
            Object.keys(this.filters).forEach(key => {
                if (this.filters[key]) {
                    params.append(key, this.filters[key]);
                }
            });
            
            // Redirigir a resultados
            window.location.href = `/results?${params.toString()}`;
        }
    }
}
</script>
```

## 7.5 Assets y Compilación

### 7.5.1 Laravel Mix Configuration

```javascript
// webpack.mix.js
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .react()
   .sass('resources/sass/app.scss', 'public/css')
   .version();

// Opciones adicionales
mix.options({
    processCssUrls: false
});

// Copiar assets
mix.copy('resources/images', 'public/images');
mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts');

// Source maps en desarrollo
if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map'
    }).sourceMaps();
}
```

### 7.5.2 Sass Structure

```scss
// resources/sass/app.scss

// Fonts
@import url('https://fonts.googleapis.com/css?family=Nunito');

// Variables
@import 'variables';

// Bootstrap
@import '~bootstrap/scss/bootstrap';

// FontAwesome
@import '~@fortawesome/fontawesome-free/scss/fontawesome';
@import '~@fortawesome/fontawesome-free/scss/solid';
@import '~@fortawesome/fontawesome-free/scss/regular';

// Librerías
@import '~summernote/dist/summernote-bs4';
@import '~dropzone/dist/dropzone';
@import '~lightbox2/dist/css/lightbox';
@import '~bootstrap-select/dist/css/bootstrap-select';

// Components
@import 'components/navbar';
@import 'components/footer';
@import 'components/property-card';
@import 'components/search';

// Overrides
@import 'overrides';
```

```scss
// resources/sass/_variables.scss
// Colors
$primary: #3490dc;
$secondary: #6c757d;
$success: #38c172;
$danger: #e3342f;
$warning: #ffed4e;
$info: #3490dc;

// Typography
$font-family-sans-serif: 'Nunito', sans-serif;
$font-size-base: 0.9rem;

// Spacing
$spacer: 1rem;
```

## 7.6 Librerías JavaScript

### 7.6.1 Dropzone (Subida de Imágenes)

```javascript
// Configuración Dropzone
Dropzone.autoDiscover = false;

$(document).ready(function() {
    if ($('#property-dropzone').length > 0) {
        const propertyId = $('#property-dropzone').data('property-id');
        
        const myDropzone = new Dropzone('#property-dropzone', {
            url: `/property/gallery/${propertyId}/upload`,
            paramName: 'file',
            maxFilesize: 5, // MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictDefaultMessage: 'Arrastra imágenes aquí o haz clic para seleccionar',
            dictRemoveFile: 'Eliminar',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                console.log('Imagen subida:', response);
                file.serverId = response.media_id;
            },
            removedfile: function(file) {
                if (file.serverId) {
                    $.ajax({
                        url: `/property/gallery/${file.serverId}`,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            console.log('Imagen eliminada');
                        }
                    });
                }
                file.previewElement.remove();
            }
        });
    }
});
```

### 7.6.2 Summernote (Editor WYSIWYG)

```javascript
// Inicializar Summernote
$('.summernote').summernote({
    height: 300,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link']],
        ['view', ['fullscreen', 'codeview', 'help']]
    ],
    placeholder: 'Escribe la descripción de tu propiedad...',
    callbacks: {
        onPaste: function(e) {
            // Limpiar formato al pegar
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            document.execCommand('insertText', false, bufferText);
        }
    }
});
```

### 7.6.3 Lightbox2 (Galería de Imágenes)

```html
<!-- Implementación en Blade -->
<div class="property-gallery">
    @foreach($property->getMedia('images') as $media)
        <a href="{{ $media->getUrl() }}" 
           data-lightbox="property-gallery" 
           data-title="{{ $property->title }}">
            <img src="{{ $media->getUrl('thumb') }}" 
                 alt="{{ $property->title }}"
                 class="gallery-thumb">
        </a>
    @endforeach
</div>
```

### 7.6.4 DataTables

```javascript
// Inicializar DataTable
$('#properties-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/admin/properties/data',
    columns: [
        {data: 'id', name: 'id'},
        {data: 'title', name: 'title'},
        {data: 'user.name', name: 'user.name'},
        {data: 'price', name: 'price'},
        {data: 'status', name: 'status'},
        {data: 'created_at', name: 'created_at'},
        {data: 'action', name: 'action', orderable: false}
    ],
    language: {
        url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    }
});
```

### 7.6.5 Bootstrap Select

```javascript
// Mejorar selects
$('.selectpicker').selectpicker({
    style: 'btn-outline-secondary',
    size: 5,
    liveSearch: true,
    liveSearchPlaceholder: 'Buscar...',
    noneResultsText: 'No se encontraron resultados'
});
```

## 7.7 AJAX y Peticiones API

```javascript
// Cargar ciudades por país
function loadCities(countryId) {
    if (!countryId) {
        $('#city_id').empty().append('<option value="">Seleccione ciudad</option>');
        return;
    }
    
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
            console.error('Error al cargar ciudades:', error);
            alert('Error al cargar ciudades');
        }
    });
}

// Evento change en país
$('#country_id').on('change', function() {
    loadCities($(this).val());
});
```

## Documentos Relacionados

- **Anterior**: [Servicios](06-SERVICIOS.md)
- **Siguiente**: [Integraciones](08-INTEGRACIONES.md)
- **Ver también**: [Arquitectura](02-ARQUITECTURA.md) - Estructura del sistema
- **Ver también**: [Dependencias](17-DEPENDENCIAS.md) - Librerías frontend

---

[← Volver al Índice](README.md) | [Siguiente: Integraciones →](08-INTEGRACIONES.md)
