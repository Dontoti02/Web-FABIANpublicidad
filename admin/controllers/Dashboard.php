<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../../core/Controller.php';

class Dashboard extends Controller {
    protected $dashboardModel;
    
    public function __construct() {
        parent::__construct();
        // Session is already started in admin/index.php, just check authentication
        if (empty($_SESSION['tipo']) || !in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
        
        // Load the dashboard model
        require_once __DIR__ . '/../models/DashboardModel.php';
        $this->dashboardModel = new DashboardModel();
    }

    public function index() {
        $data['title'] = 'Panel Administrativo';
        
        // Obtener estadísticas reales de la base de datos
        $data['totalProductos'] = $this->dashboardModel->getTotalProductos();
        $data['totalCategorias'] = $this->dashboardModel->getTotalCategorias();
        $data['totalUsuarios'] = $this->dashboardModel->getTotalUsuarios();
        $data['totalVentas'] = $this->dashboardModel->getTotalVentas();
        $data['totalIngresos'] = $this->dashboardModel->getTotalIngresos();
        
        // Datos adicionales para el dashboard
        $data['productosBajoStock'] = $this->dashboardModel->getProductosBajoStock();
        $data['ventasRecientes'] = $this->dashboardModel->getVentasRecientes();
        $data['productosMasVendidos'] = $this->dashboardModel->getProductosMasVendidos();
        
        $this->views->getView('admin/dashboard', 'index', $data);
    }

    // Método AJAX para obtener estadísticas en tiempo real
    public function getEstadisticas() {
        $estadisticas = [
            'totalProductos' => $this->dashboardModel->getTotalProductos(),
            'totalCategorias' => $this->dashboardModel->getTotalCategorias(),
            'totalUsuarios' => $this->dashboardModel->getTotalUsuarios(),
            'totalVentas' => $this->dashboardModel->getTotalVentas(),
            'totalIngresos' => number_format($this->dashboardModel->getTotalIngresos(), 2)
        ];
        
        header('Content-Type: application/json');
        echo json_encode($estadisticas);
        exit;
    }
}
?>