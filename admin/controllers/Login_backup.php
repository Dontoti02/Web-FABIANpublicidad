<?php
// Backup of original Login controller
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../../core/Controller.php';

class Login extends Controller {
    public function __construct() {
        // Start session first, before parent constructor
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
    }

    public function index() {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['tipo']) && in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        $data['title'] = 'Login Admin - ' . APP_NAME;
        $this->views->getView('admin/login', 'index', $data);
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'] ?? '';
            $clave = $_POST['clave'] ?? '';

            if (empty($correo) || empty($clave)) {
                $res = array('msg' => 'Todos los campos son requeridos', 'type' => 'warning');
                echo json_encode($res);
                return;
            }

            // Buscar usuario en la base de datos usando el modelo de Usuario
            require_once __DIR__ . '/../models/UsuarioModel.php';
            $usuarioModel = new UsuarioModel();
            
            // Verificar credenciales
            $user = $usuarioModel->login($correo, $clave);

            if ($user) {
                // Iniciar sesión
                $_SESSION['id'] = $user['idusuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['correo'] = $user['email'];
                $_SESSION['tipo'] = $user['rol'];

                $res = array('msg' => 'Login exitoso', 'type' => 'success', 'url' => BASE_URL . 'admin');
            } else {
                $res = array('msg' => 'Credenciales incorrectas', 'type' => 'error');
            }

            echo json_encode($res);
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ' . BASE_URL . 'admin/login');
        exit;
    }
}
?>