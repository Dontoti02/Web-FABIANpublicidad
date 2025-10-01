<?php
// Configuración global de constantes
require_once __DIR__ . '/../../config/Config.php';

// Definir constantes globales desde la clase Config
if (!defined('BASE_URL')) {
    define('BASE_URL', Config::BASE_URL);
}
if (!defined('APP_NAME')) {
    define('APP_NAME', Config::APP_NAME);
}
?>
