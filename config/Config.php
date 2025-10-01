<?php
class Config {
    // Database configuration
    const DB_HOST = 'localhost';
    const DB_NAME = 'fabian_db';
    const DB_USER = 'root';
    const DB_PASS = '';
    
    // Application configuration
    const APP_NAME = 'FABIAN Publicidad';
    const APP_VERSION = '2.0';
    const BASE_URL = 'http://localhost/FABIAN/';
    
    // Paths
    const VIEWS_PATH = __DIR__ . '/../views/';
    const MODELS_PATH = __DIR__ . '/../models/';
    const CONTROLLERS_PATH = __DIR__ . '/../controllers/';
    const ASSETS_PATH = '/PROYECTO-G5-ING-WEB/';
    
    // Session configuration
    const SESSION_TIMEOUT = 3600; // 1 hour
    
    // Upload paths
    const UPLOAD_PATH = __DIR__ . '/../uploads/';
    const PRODUCT_IMAGES_PATH = 'img/';
    const USER_IMAGES_PATH = 'admin/files/usuarios/';
}
?>
