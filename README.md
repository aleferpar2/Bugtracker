# 🐞 BGT - Bug Tracker

<div align="center">
  <img src="imagen/ChatGPT Image 5 jun 2025, 13_31_05.png" alt="BGT Logo" width="300"/>
</div>

## 📝 Descripción

BGT (Bug Tracker) es una aplicación web moderna diseñada para el seguimiento y gestión eficiente de errores en proyectos de desarrollo. Integra búsqueda local en base de datos y búsqueda avanzada con la API de StackOverflow, permitiendo a los desarrolladores encontrar y solucionar problemas de manera más efectiva. La aplicación está construida con Laravel y Vue.js, ofreciendo una experiencia de usuario fluida y responsive.

## 🔗 Enlaces

- [GitHub](TU_LINK_AQUI)
- [Presentación del Proyecto](TU_LINK_DE_CANVA_AQUI)

## 🛠️ Tecnologías Utilizadas

- **Frontend**: Vue.js 3, Tailwind CSS, Inertia.js
- **Backend**: Laravel 10, PHP 8.2
- **Base de Datos**: MySQL
- **Servidor**: Apache
- **APIs**: StackOverflow API

## 🚀 Instalación Local

1. Clona el repositorio
```bash
git clone [tu-repositorio]
```

2. Instala las dependencias de PHP
```bash
composer install
```

3. Instala las dependencias de Node.js
```bash
npm install
```

4. Configura el archivo .env
```bash
cp .env.example .env
php artisan key:generate
```

5. Configura la base de datos en MySQL
```bash
# Crea una base de datos en MySQL
# Actualiza las credenciales en el archivo .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bugtracker
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

6. Ejecuta las migraciones
```bash
php artisan migrate
```

7. Compila los assets
```bash
npm run dev
```

8. Configura Apache
- Asegúrate de que Apache está instalado y funcionando
- Configura un virtual host apuntando al directorio `/public` del proyecto
- Habilita el módulo rewrite de Apache:
```bash
sudo a2enmod rewrite
```

## 🌟 Características Principales

- Sistema de autenticación y roles
- Gestión de bugs y seguimiento
- Búsqueda local en base de datos
- Búsqueda avanzada integrada con StackOverflow
- Sistema de comentarios y soluciones
- Notificaciones en tiempo real
- Interfaz responsive y moderna

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles. 