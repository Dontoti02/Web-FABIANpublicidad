<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../../core/Controller.php';

class Categorias extends Controller {
    public function __construct() {
        parent::__construct();
        // Session is already started in admin/index.php, just check authentication
        if (empty($_SESSION['tipo']) || !in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
        
        // Load the correct model manually
        require_once __DIR__ . '/../models/CategoriaModel.php';
        $this->model = new CategoriaModel();
    }

    public function index() {
        $data['title'] = 'Gestión de Categorías';
        $data['categorias'] = $this->model->getCategorias();
        $this->views->getView('admin/categorias', 'index', $data);
    }

    public function listar() {
        $data = $this->model->getCategorias();
        echo json_encode($data);
        die();
    }

    public function registrar() {
        $nombre = $_POST['nombre'];
        if (empty($nombre)) {
            $res = array('msg' => 'El nombre es requerido', 'type' => 'warning');
        } else {
            $data = $this->model->registrarCategoria($nombre);
            if ($data > 0) {
                $res = array('msg' => 'Categoría registrada', 'type' => 'success');
            } else {
                $res = array('msg' => 'Error al registrar', 'type' => 'error');
            }
        }
        echo json_encode($res);
        die();
    }

    public function eliminar($id) {
        $data = $this->model->eliminarCategoria($id);
        if ($data == 1) {
            $res = array('msg' => 'Categoría eliminada', 'type' => 'success');
        } else {
            $res = array('msg' => 'Error al eliminar', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function editar($id) {
        $data = $this->model->getCategoria($id);
        echo json_encode($data);
        die();
    }

    public function actualizar() {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        
        if (empty($id) || empty($nombre)) {
            $res = array('msg' => 'Todos los campos son requeridos', 'type' => 'warning');
        } else {
            $data = $this->model->actualizarCategoria($id, $nombre);
            if ($data == 1) {
                $res = array('msg' => 'Categoría actualizada correctamente', 'type' => 'success');
            } else {
                $res = array('msg' => 'Error al actualizar la categoría', 'type' => 'error');
            }
        }
        echo json_encode($res);
        die();
    }
}
?>