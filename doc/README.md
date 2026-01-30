# Documentación Técnica - ABCmio

Bienvenido a la documentación técnica completa del proyecto ABCmio, una plataforma de listado de propiedades inmobiliarias construida con Laravel 5.8.

## 📚 Índice de Documentación

Esta documentación está organizada en módulos temáticos para facilitar su navegación y comprensión:

### 1. [Introducción](01-INTRODUCCION.md)
Descripción general del proyecto, objetivos, stack tecnológico y requisitos del sistema.

### 2. [Arquitectura](02-ARQUITECTURA.md)
Arquitectura general del sistema, estructura de directorios, componentes principales y flujo de request-response.

### 3. [Base de Datos](03-BASE-DE-DATOS.md)
Esquema de base de datos, modelos principales, relaciones y migraciones.

### 4. [Funcionalidades](04-FUNCIONALIDADES.md)
Descripción detallada de todas las funcionalidades del sistema: gestión de usuarios, propiedades, créditos, reportes, búsqueda, etc.

### 5. [Controladores](05-CONTROLADORES.md)
Documentación de todos los controladores del sistema y sus responsabilidades.

### 6. [Servicios](06-SERVICIOS.md)
Capa de servicios y lógica de negocio separada de los controladores.

### 7. [Frontend](07-FRONTEND.md)
Componentes frontend: Blade templates, React, Vue.js, assets y librerías.

### 8. [Integraciones](08-INTEGRACIONES.md)
Integraciones con servicios externos: PayPal, AWS S3, Spatie Media Library, reCAPTCHA, Laravel Telescope.

### 9. [API](09-API.md)
Documentación completa de endpoints API, autenticación, formatos de respuesta y ejemplos.

### 10. [Seguridad](10-SEGURIDAD.md)
Autenticación, autorización, protección CSRF, validación y prevención de ataques.

### 11. [Configuración](11-CONFIGURACION.md)
Variables de entorno, archivos de configuración y servicios externos.

### 12. [Despliegue](12-DESPLIEGUE.md)
Requisitos del servidor, instalación, configuración DDEV y producción.

### 13. [Testing](13-TESTING.md)
Estrategia de testing, tests unitarios, de integración y ejecución.

### 14. [Flujos de Trabajo](14-FLUJOS-DE-TRABAJO.md)
Diagramas y descripciones de los flujos principales del sistema.

### 15. [Mantenimiento](15-MANTENIMIENTO.md)
Tareas de mantenimiento, backup, monitoreo, logs y troubleshooting.

### 16. [Glosario](16-GLOSARIO.md)
Términos técnicos, acrónimos y conceptos específicos del dominio.

### 17. [Dependencias](17-DEPENDENCIAS.md)
Listado completo de dependencias PHP (Composer) y JavaScript (NPM).

### 18. [Contribución](18-CONTRIBUCION.md)
Guía para contribuidores, estándares de código y workflow Git.

## 🎯 Convenciones de Documentación

### Formato
- **Idioma principal**: Español
- **Términos técnicos**: Inglés cuando es estándar en la industria
- **Formato**: Markdown con syntax highlighting para código
- **Diagramas**: Mermaid o ASCII art cuando sea aplicable

### Estructura de Documentos
Cada documento sigue una estructura consistente:
1. **Título principal** (H1)
2. **Índice local** si el documento es extenso
3. **Secciones principales** (H2)
4. **Subsecciones** (H3, H4)
5. **Ejemplos de código** con comentarios explicativos
6. **Notas importantes** destacadas
7. **Referencias cruzadas** a otros documentos cuando sea relevante

### Bloques de Código
Los ejemplos de código incluyen:
```php
// Comentarios explicativos en español
public function ejemplo() {
    // Código de ejemplo
}
```

### Navegación
- Use los enlaces del índice para navegar entre documentos
- Cada documento contiene enlaces a secciones relacionadas
- Vuelva a este README para acceso rápido a cualquier sección

## 📖 Cómo Usar esta Documentación

### Para Nuevos Desarrolladores
1. Comience con [Introducción](01-INTRODUCCION.md) para entender el proyecto
2. Revise [Arquitectura](02-ARQUITECTURA.md) para comprender la estructura
3. Estudie [Base de Datos](03-BASE-DE-DATOS.md) para conocer los modelos
4. Siga con [Despliegue](12-DESPLIEGUE.md) para configurar su entorno local

### Para Desarrolladores Existentes
- Use el [Glosario](16-GLOSARIO.md) para términos específicos
- Consulte [API](09-API.md) para integración de servicios
- Revise [Flujos de Trabajo](14-FLUJOS-DE-TRABAJO.md) para entender procesos

### Para Arquitectos y Tech Leads
- [Arquitectura](02-ARQUITECTURA.md) para diseño del sistema
- [Seguridad](10-SEGURIDAD.md) para políticas de seguridad
- [Mantenimiento](15-MANTENIMIENTO.md) para operaciones

### Para DevOps
- [Despliegue](12-DESPLIEGUE.md) para instalación y configuración
- [Configuración](11-CONFIGURACION.md) para variables de entorno
- [Mantenimiento](15-MANTENIMIENTO.md) para tareas operativas

## 🚀 Inicio Rápido

Para comenzar rápidamente con el proyecto:

```bash
# Clonar el repositorio
git clone https://github.com/JosvierR/abcmio-study.git

# Navegar al directorio
cd abcmio-study

# Iniciar con DDEV (recomendado)
ddev start
ddev composer install
ddev npm install
ddev exec php artisan migrate --seed

# Acceder a la aplicación
# https://abcmio.ddev.site
```

Consulte [Despliegue](12-DESPLIEGUE.md) para instrucciones detalladas.

## 📝 Actualización de la Documentación

Esta documentación debe actualizarse cuando:
- Se añaden nuevas funcionalidades
- Se modifican componentes arquitectónicos
- Cambian las dependencias o requisitos
- Se implementan nuevas integraciones
- Se actualizan procedimientos de despliegue

Consulte [Contribución](18-CONTRIBUCION.md) para las guías de actualización.

## 📞 Soporte

Para preguntas o aclaraciones sobre esta documentación:
- Revise primero el [Glosario](16-GLOSARIO.md)
- Consulte los [Flujos de Trabajo](14-FLUJOS-DE-TRABAJO.md) para procesos específicos
- Revise la sección de [Mantenimiento](15-MANTENIMIENTO.md) para problemas comunes

## 📄 Licencia

Este proyecto está licenciado bajo MIT License. Ver el archivo LICENSE en la raíz del proyecto.

---

**Última actualización**: Enero 2026  
**Versión**: 1.0  
**Framework**: Laravel 5.8  
**PHP**: 7.1.3+
