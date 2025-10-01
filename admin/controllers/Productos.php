<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../../core/Controller.php';

class Productos extends Controller {
    public function __construct() {
        parent::__construct();
        // Session is already started in admin/index.php, just check authentication
        if (empty($_SESSION['tipo']) || !in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
        
        // Load the correct model manually
        require_once __DIR__ . '/../models/ProductoModel.php';
        $this->model = new ProductoModel();
    }

    public function index() {
        $data['title'] = 'Gestión de Productos';
        // Load categories for the dropdown
        require_once __DIR__ . '/../models/CategoriaModel.php';
        $categoriaModel = new CategoriaModel();
        $data['categorias'] = $categoriaModel->getCategorias();
        $this->views->getView('admin/productos', 'index', $data);
    }

    public function listar() {
        $data = $this->model->getProductos();
        echo json_encode($data);
        die();
    }

    public function registrar() {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $id_categoria = $_POST['id_categoria'];
        $imagen = $_FILES['imagen'];
        $tmp_name = $imagen['tmp_name'];
        $ruta = 'uploads/productos/';
        $nombre_imagen = date('YmdHis');
        
        if (empty($nombre) || empty($descripcion) || empty($precio) || empty($stock) || empty($id_categoria)) {
            $res = array('msg' => 'Todos los campos son requeridos', 'type' => 'warning');
        } else {
            if (!empty($imagen['name'])) {
                $destino = $ruta . $nombre_imagen . '.jpg';
            } else {
                $destino = $ruta . 'default.jpg';
            }
            $data = $this->model->registrarProducto($nombre, $descripcion, $precio, $stock, $id_categoria, $destino);
            if ($data > 0) {
                if (!empty($imagen['name'])) {
                    move_uploaded_file($tmp_name, $destino);
                }
                $res = array('msg' => 'Producto registrado', 'type' => 'success');
            } else {
                $res = array('msg' => 'Error al registrar', 'type' => 'error');
            }
        }
        echo json_encode($res);
        die();
    }

    public function eliminar($id) {
        $data = $this->model->eliminarProducto($id);
        if ($data == 1) {
            $res = array('msg' => 'Producto eliminado', 'type' => 'success');
        } else {
            $res = array('msg' => 'Error al eliminar', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function eliminarMultiple() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ids = $_POST['ids'] ?? '';
            
            if (empty($ids)) {
                $res = array('msg' => 'No se seleccionaron productos', 'type' => 'warning');
            } else {
                $idsArray = explode(',', $ids);
                $eliminados = 0;
                $errores = 0;
                
                foreach ($idsArray as $id) {
                    $id = trim($id);
                    if (!empty($id)) {
                        $resultado = $this->model->eliminarProducto($id);
                        if ($resultado == 1) {
                            $eliminados++;
                        } else {
                            $errores++;
                        }
                    }
                }
                
                if ($eliminados > 0 && $errores == 0) {
                    $res = array('msg' => "Se eliminaron $eliminados productos correctamente", 'type' => 'success');
                } else if ($eliminados > 0 && $errores > 0) {
                    $res = array('msg' => "Se eliminaron $eliminados productos, $errores fallaron", 'type' => 'warning');
                } else {
                    $res = array('msg' => 'Error al eliminar los productos', 'type' => 'error');
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode($res);
        }
    }

    public function editar($id) {
        $data = $this->model->getProducto($id);
        echo json_encode($data);
        die();
    }

    public function actualizar() {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $id_categoria = $_POST['id_categoria'];
        $imagen = $_FILES['imagen'] ?? null;
        
        if (empty($id) || empty($nombre) || empty($descripcion) || empty($precio) || empty($stock) || empty($id_categoria)) {
            $res = array('msg' => 'Todos los campos son requeridos', 'type' => 'warning');
        } else {
            $ruta = 'uploads/productos/';
            $destino = null;
            
            // Si se subió una nueva imagen
            if (!empty($imagen['name'])) {
                $nombre_imagen = date('YmdHis');
                $destino = $ruta . $nombre_imagen . '.jpg';
                move_uploaded_file($imagen['tmp_name'], $destino);
            }
            
            $data = $this->model->actualizarProducto($id, $nombre, $descripcion, $precio, $stock, $id_categoria, $destino);
            if ($data == 1) {
                $res = array('msg' => 'Producto actualizado correctamente', 'type' => 'success');
            } else {
                $res = array('msg' => 'Error al actualizar el producto', 'type' => 'error');
            }
        }
        echo json_encode($res);
        die();
    }
}
?>