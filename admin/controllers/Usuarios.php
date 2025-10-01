<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../../core/Controller.php';

class Usuarios extends Controller {
    public function __construct() {
        parent::__construct();
        // Session is already started in admin/index.php, just check authentication
        if (empty($_SESSION['tipo']) || !in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
        
        // Load the correct model manually
        require_once __DIR__ . '/../models/UsuarioModel.php';
        $this->model = new UsuarioModel();
    }

    public function index() {
        $data['title'] = 'Gestión de Usuarios';
        $this->views->getView('admin/usuarios', 'index', $data);
    }

    public function listar() {
        $data = $this->model->getUsuarios();
        echo json_encode($data);
        die();
    }

    public function registrar() {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $clave = $_POST['clave'];
        $confirmar_clave = $_POST['confirmar_clave'];
        $tipo = 'admin'; // Por defecto, se registra como admin

        if (empty($nombre) || empty($correo) || empty($clave) || empty($confirmar_clave)) {
            $res = array('msg' => 'Todos los campos son requeridos', 'type' => 'warning');
        } else if ($clave != $confirmar_clave) {
            $res = array('msg' => 'Las contraseñas no coinciden', 'type' => 'warning');
        } else {
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $data = $this->model->registrarUsuario($nombre, $correo, $hash, $tipo);
            if ($data > 0) {
                $res = array('msg' => 'Usuario registrado', 'type' => 'success');
            } else {
                $res = array('msg' => 'Error al registrar', 'type' => 'error');
            }
        }
        echo json_encode($res);
        die();
    }

    public function eliminar($id) {
        $data = $this->model->eliminarUsuario($id);
        if ($data == 1) {
            $res = array('msg' => 'Usuario eliminado', 'type' => 'success');
        } else {
            $res = array('msg' => 'Error al eliminar', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }
}
?>