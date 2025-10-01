<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../../core/Controller.php';

class Pedidos extends Controller {
    public function __construct() {
        parent::__construct();
        // Session is already started in admin/index.php, just check authentication
        if (empty($_SESSION['tipo']) || !in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
        
        // Load the correct model manually
        require_once __DIR__ . '/../models/PedidoModel.php';
        $this->model = new PedidoModel();
    }

    public function index() {
        $data['title'] = 'Gestión de Pedidos';
        $data['pedidos'] = $this->model->getPedidos();
        $data['estadisticas'] = $this->model->getEstadisticasPedidos();
        $this->views->getView('admin/pedidos', 'index', $data);
    }

    public function detalle($id) {
        if (empty($id)) {
            echo json_encode(['error' => 'ID de pedido requerido']);
            return;
        }

        $pedido = $this->model->getPedido($id);
        $detalle = $this->model->getDetallePedido($id);

        if (!$pedido) {
            echo json_encode(['error' => 'Pedido no encontrado']);
            return;
        }

        $response = [
            'pedido' => $pedido,
            'detalle' => $detalle
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function actualizarEstado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $estado = $_POST['estado'] ?? '';

            if (empty($id) || empty($estado)) {
                $res = ['msg' => 'ID y estado son requeridos', 'type' => 'warning'];
            } else {
                $resultado = $this->model->actualizarEstado($id, $estado);
                if ($resultado) {
                    $res = ['msg' => 'Estado actualizado correctamente', 'type' => 'success'];
                } else {
                    $res = ['msg' => 'Error al actualizar el estado', 'type' => 'error'];
                }
            }

            header('Content-Type: application/json');
            echo json_encode($res);
        }
    }

    public function listar() {
        $pedidos = $this->model->getPedidos();
        header('Content-Type: application/json');
        echo json_encode($pedidos);
    }

    public function eliminar($id) {
        $data = $this->model->eliminarPedido($id);
        if ($data == 1) {
            $res = array('msg' => 'Pedido eliminado correctamente', 'type' => 'success');
        } else {
            $res = array('msg' => 'Error al eliminar el pedido', 'type' => 'error');
        }
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    public function eliminarMultiple() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ids = $_POST['ids'] ?? '';
            
            if (empty($ids)) {
                $res = array('msg' => 'No se seleccionaron pedidos', 'type' => 'warning');
            } else {
                $idsArray = explode(',', $ids);
                $eliminados = 0;
                $errores = 0;
                
                foreach ($idsArray as $id) {
                    $id = trim($id);
                    if (!empty($id)) {
                        $resultado = $this->model->eliminarPedido($id);
                        if ($resultado == 1) {
                            $eliminados++;
                        } else {
                            $errores++;
                        }
                    }
                }
                
                if ($eliminados > 0 && $errores == 0) {
                    $res = array('msg' => "Se eliminaron $eliminados pedidos correctamente", 'type' => 'success');
                } else if ($eliminados > 0 && $errores > 0) {
                    $res = array('msg' => "Se eliminaron $eliminados pedidos, $errores fallaron", 'type' => 'warning');
                } else {
                    $res = array('msg' => 'Error al eliminar los pedidos', 'type' => 'error');
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode($res);
        }
    }
}
?>