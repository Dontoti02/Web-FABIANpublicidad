# 🏪 FABIAN Publicidad

**Sistema de Gestión de Productos Promocionales y Publicitarios Personalizados**

[![PHP Version](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![MySQL Version](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Bootstrap Version](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Descripción del Proyecto

**FABIAN Publicidad** es una plataforma web completa diseñada para la gestión integral de productos promocionales y publicitarios personalizados. El sistema permite administrar categorías, productos, usuarios, pedidos y configuraciones desde un panel administrativo intuitivo y moderno.

### ✨ Características Principales

#### 🎨 **Panel Administrativo Completo**
- Dashboard con estadísticas en tiempo real
- Gestión completa de productos (CRUD)
- Administración de categorías con imágenes
- Control de usuarios y permisos
- Seguimiento de pedidos y ventas
- Configuración de perfil personal
- Cambio seguro de contraseñas

#### 🛒 **Funcionalidades del Sistema**
- **Gestión de Productos**: Crear, editar, eliminar y listar productos con imágenes
- **Categorización**: Organización de productos por categorías personalizables
- **Control de Inventario**: Seguimiento de stock y productos con bajo inventario
- **Sistema de Usuarios**: Múltiples niveles de acceso (admin, subadmin)
- **Gestión de Pedidos**: Seguimiento completo del proceso de ventas
- **Reportes Visuales**: Estadísticas de ventas, productos más vendidos, ingresos totales

#### 🔒 **Características de Seguridad**
- Autenticación segura con hash de contraseñas
- Sesiones protegidas con timeout
- Validación de datos en servidor
- Protección contra inyecciones SQL
- Manejo seguro de archivos de imagen

---

## 🛠️ Tecnologías Utilizadas

### **Backend**
- **PHP 8.0+** - Lenguaje de programación principal
- **MySQL 8.0+** - Sistema de gestión de base de datos
- **PDO** - Conexión segura a base de datos
- **MVC Architecture** - Patrón Modelo-Vista-Controlador

### **Frontend**
- **Bootstrap 5.3** - Framework CSS para diseño responsivo
- **jQuery 3.6+** - Librería JavaScript para interactividad
- **Font Awesome 6.0** - Iconografía moderna
- **HTML5 & CSS3** - Tecnologías web estándar

### **Servidor**
- **Apache** - Servidor web
- **phpMyAdmin** - Administración de base de datos
- **Laragon/XAMPP** - Entorno de desarrollo local

---

## 🚀 Instalación y Configuración

### **Requisitos del Sistema**
- PHP 8.0 o superior
- MySQL 8.0 o superior
- Servidor web Apache
- phpMyAdmin (opcional pero recomendado)

### **Pasos de Instalación**

#### 1. **Clonar o Descargar el Proyecto**
```bash
# Si tienes git instalado
git clone [URL_DEL_REPOSITORIO]
cd FABIAN

# O descarga el ZIP y extrae en tu servidor web
```

#### 2. **Configuración de Base de Datos**
1. Abre phpMyAdmin
2. Crea una nueva base de datos llamada `fabian_db`
3. Importa el archivo `fabian_db.sql` ubicado en la raíz del proyecto

#### 3. **Configuración del Servidor**
1. Asegúrate de que el directorio raíz apunte a la carpeta `FABIAN`
2. Configura un host virtual si es necesario:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/laragon/www/FABIAN"
       ServerName fabian.local
   </VirtualHost>
   ```

#### 4. **Configuración de PHP**
Asegúrate de que las siguientes extensiones estén habilitadas en `php.ini`:
```ini
extension=pdo_mysql
extension=gd
extension=fileinfo
```

### **Estructura de Archivos**
```
FABIAN/
├── admin/                 # Panel administrativo
│   ├── controllers/       # Controladores del admin
│   ├── models/           # Modelos de datos
│   ├── views/            # Vistas del admin
│   └── config/           # Configuraciones
├── assets/               # Recursos estáticos
├── core/                 # Archivos centrales del MVC
├── css/                  # Hojas de estilo
├── js/                   # Archivos JavaScript
├── uploads/              # Archivos subidos
├── conexionSQL/          # Configuración de BD
├── config/               # Configuraciones del sistema
└── [archivos principales]
```

---

## 👤 Credenciales de Acceso

### **Administrador Principal**
- **Correo**: `alvaradogomez23@gmail.com`
- **Contraseña**: `12345678`
- **Rol**: Administrador completo

### **Cliente de Prueba**
- **Correo**: `juanjoel23@gmail.com`
- **Contraseña**: `12345678`
- **Tipo**: Cliente registrado

---

## 📖 Guía de Uso

### **Para Administradores**

#### **1. Inicio de Sesión**
1. Accede a `http://localhost/FABIAN/admin/login`
2. Ingresa tus credenciales de administrador
3. Serás redirigido al panel de control

#### **2. Navegación Principal**
- **🏠 Dashboard**: Vista general con estadísticas
- **📦 Productos**: Gestión completa del catálogo
- **🏷️ Categorías**: Organización de productos
- **👥 Usuarios**: Administración de cuentas admin
- **🛒 Pedidos**: Seguimiento de ventas
- **⚙️ Configuración**: Datos personales y seguridad

#### **3. Gestión de Productos**
- Crear nuevos productos con imágenes
- Editar información existente
- Gestionar stock e inventario
- Organizar por categorías

### **Para Clientes**

#### **1. Navegación Pública**
- Explorar productos por categorías
- Ver detalles completos de productos
- Agregar productos al carrito
- Realizar pedidos seguros

---

## 📊 Funcionalidades Destacadas

### **Dashboard Ejecutivo**
- **Estadísticas en Tiempo Real**: Ventas, ingresos, productos más vendidos
- **Alertas Inteligentes**: Productos con stock bajo
- **Gráficos Visuales**: Tendencias de ventas
- **Acciones Rápidas**: Enlaces directos a funciones importantes

### **Sistema de Productos**
- **CRUD Completo**: Crear, leer, actualizar, eliminar productos
- **Galería de Imágenes**: Múltiples fotos por producto
- **Control de Inventario**: Alertas automáticas de stock bajo
- **Categorización Flexible**: Sistema de categorías ilimitado

### **Seguridad Avanzada**
- **Autenticación Robusta**: Hash seguro de contraseñas
- **Sesiones Protegidas**: Timeout automático y regeneración de ID
- **Validación Completa**: Sanitización de datos en servidor
- **Protección CSRF**: Tokens anti-ataques de falsificación

---

## 🔧 Desarrollo y Mantenimiento

### **Estructura MVC**
- **Modelo**: Manejo de datos y lógica de negocio
- **Vista**: Presentación y interfaz de usuario
- **Controlador**: Manejo de peticiones y respuestas

### **Base de Datos**
- **Diseño Normalizado**: Relaciones optimizadas
- **Índices Estratégicos**: Consultas rápidas y eficientes
- **Restricciones**: Integridad referencial garantizada

### **Código Fuente**
- **Comentarios Completos**: Documentación integrada
- **Estándares PSR**: Código limpio y mantenible
- **Separación de Responsabilidades**: Arquitectura modular

---

