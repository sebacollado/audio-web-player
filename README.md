# Reproductor Audio Web

## Proyecto
Proyecto de prácticas para la asignatura **Sistemas Multimedia** en el curso 2015/16, desarrollado por Sebastián Collado Montañez y Jaime Collado Montañez.

Consiste en el diseño, implementación y despliegue de una aplicación multimedia para la **reproducción de audio** mediante el uso de **tecnologías web** para su uso multiplataforma gracias a su diseño adaptativo.

## Tecnologías utilizadas
- **HTML5** (estructura y uso de la etiqueta *audio* implementado en ésta versión de HTML).
- **PHP** con framework Codeigniter (patrón Modelo Vista Controlador).
- **jQuery** (reproductor implementado como SPA o aplicación de página única).
- **SQlite** (base de datos para el control de las canciones en el servidor).
- **Bootstrap** (diseño y adaptabilidad del frontend).
- **AJAX** (gestión de canciones asíncrona).

## Despliegue
1. **Instalar Apache** como servidor web (recomendable instalar XAMPP).
2. **Descomprimir** el proyecto dentro de la carpeta *htdocs*.
3. **Añadir música** a la carpeta *music* dentro del proyecto descomprimido.
4. **Acceder** al proyecto vía navegador (*localhost/mwp*) por primera vez para actualizar la base de datos de canciones disponibles.

Llegados a éste punto, el proyecto debe estar funcionando sobre Apache y con la base de datos actualizada. Si se añadiese música posteriormente al directorio, se actualizará automáticamente al refrescar la página.

**IMPORTANTE:** Para conocer el funcionamiento del proyecto, leer la Guía de Usuario incluida en  la raíz de éste repositorio.
