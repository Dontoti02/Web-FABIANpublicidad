<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../../core/Controller.php';

class Configuracion extends Controller {
    public function __construct() {
        parent::__construct();
        // Session is already started in admin/index.php, just check authentication
        if (empty($_SESSION['tipo']) || !in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }

        // Load the correct model manually
        require_once __DIR__ . '/../models/ConfiguracionModel.php';
        $this->model = new ConfiguracionModel();
    }

    public function index() {
        $data['title'] = 'Configuración';
        $this->views->getView('admin/configuracion', 'index', $data);
    }

    public function actualizarDatosPersonales() {
        $id = $_SESSION['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];

        if (empty($nombre) || empty($email)) {
            $res = array('msg' => 'Todos los campos son requeridos', 'type' => 'warning');
        } else {
            $data = $this->model->actualizarDatosPersonales($id, $nombre, $email);
            if ($data == 1) {
                // Actualizar la sesión con los nuevos datos
                $_SESSION['nombre'] = $nombre;
                $res = array('msg' => 'Datos personales actualizados correctamente', 'type' => 'success');
            } else {
                $res = array('msg' => 'Error al actualizar los datos personales', 'type' => 'error');
            }
        }
        echo json_encode($res);
        die();
    }

    public function cambiarContrasena() {
        $id = $_SESSION['id'];
        $contrasena_actual = $_POST['contrasena_actual'];
        $contrasena_nueva = $_POST['contrasena_nueva'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];

        if (empty($contrasena_actual) || empty($contrasena_nueva) || empty($confirmar_contrasena)) {
            $res = array('msg' => 'Todos los campos son requeridos', 'type' => 'warning');
        } else if ($contrasena_nueva !== $confirmar_contrasena) {
            $res = array('msg' => 'Las contraseñas nuevas no coinciden', 'type' => 'warning');
        } else if (strlen($contrasena_nueva) < 6) {
            $res = array('msg' => 'La contraseña debe tener al menos 6 caracteres', 'type' => 'warning');
        } else {
            // Verificar contraseña actual
            $usuario = $this->model->verificarContrasenaActual($id, $contrasena_actual);
            if ($usuario) {
                // Cambiar contraseña
                $data = $this->model->cambiarContrasena($id, $contrasena_nueva);
                if ($data == 1) {
                    $res = array('msg' => 'Contraseña cambiada correctamente', 'type' => 'success');
                } else {
                    $res = array('msg' => 'Error al cambiar la contraseña', 'type' => 'error');
                }
            } else {
                $res = array('msg' => 'La contraseña actual es incorrecta', 'type' => 'error');
            }
        }
        echo json_encode($res);
        die();
    }

    public function obtenerDatosUsuario() {
        $id = $_SESSION['id'];

        // Debug: Log session data
        error_log("ConfiguracionController::obtenerDatosUsuario - Session ID: $id, Session data: " . print_r($_SESSION, true));

        try {
            // Usar consulta directa para evitar problemas con el método select()
            require_once __DIR__ . '/../core/Conexion.php';
            $conexion = new Conexion();
            $pdo = $conexion->conect();

            $sql = "SELECT nombre, email, estado FROM usuarios WHERE idusuario = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("ConfiguracionController::obtenerDatosUsuario - Direct query result: " . print_r($result, true));

            if ($result === false) {
                error_log("ConfiguracionController::obtenerDatosUsuario - Usuario no encontrado con ID: $id");
                $response = array('error' => 'Usuario no encontrado');
            } else if ($result['estado'] != 1) {
                error_log("ConfiguracionController::obtenerDatosUsuario - Usuario inactivo con ID: $id");
                $response = array('error' => 'Usuario inactivo');
            } else {
                $response = array(
                    'nombre' => $result['nombre'],
                    'email' => $result['email']
                );
            }

        } catch (Exception $e) {
            error_log("ConfiguracionController::obtenerDatosUsuario - Exception: " . $e->getMessage());
            $response = array('error' => 'Error de conexión: ' . $e->getMessage());
        }

        echo json_encode($response);
        die();
    }
}
?>
