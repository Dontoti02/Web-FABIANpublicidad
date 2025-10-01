<?php
/**
 * FABIAN - Configuración del Sistema
 * Productos Promocionales y Publicitarios Personalizados
 * 
 * Este archivo contiene la información esencial del proyecto
 */

// ==========================================
// INFORMACIÓN DEL PROYECTO
// ==========================================
define('PROJECT_NAME', 'FABIAN');
define('PROJECT_DESCRIPTION', 'Tu empresa especializada en productos promocionales y publicitarios personalizados');
define('PROJECT_VERSION', '1.0.0');

// ==========================================
// CATEGORÍAS DE PRODUCTOS PROMOCIONALES
// ==========================================
$categorias_fabian = [
    1 => 'Lapiceros de plástico',
    2 => 'Tarjeteros metálicos',
    3 => 'Resaltadores', 
    4 => 'Antiestrés',
    5 => 'Llaveros',
    6 => 'Escritorio',
    7 => 'Ecológico',
    8 => 'Memorias USB',
    9 => 'Tomatodos',
    10 => 'Tazas',
    11 => 'Mugs',
    12 => 'Personalizado',
    13 => 'Canguros',
    14 => 'Bolsos',
    15 => 'Mochila',
    16 => 'Textil',
    17 => 'Loncheras'
];

// ==========================================
// CONFIGURACIÓN DE IMÁGENES
// ==========================================
define('MAIN_LOGO', 'assets/logo.png');
define('DEFAULT_CATEGORY_IMAGE', 'assets/lapiceros.png');

// ==========================================
// INSTRUCCIONES DE USO
// ==========================================
/*
PARA CONFIGURAR EL SISTEMA:
1. Ejecuta: setup_complete.php
2. Visita: index.php

ARCHIVOS PRINCIPALES:
- index.php: Página principal
- login.php, register.php: Sistema de usuarios
- store.php: Catálogo de productos
- admin/: Panel de administración

CONFIGURACIÓN DE BASE DE DATOS:
- Archivo SQL: bd_proyectog5_updated.sql
- Categorías: update_categorias_fabian.sql
*/

?>
