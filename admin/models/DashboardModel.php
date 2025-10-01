<?php
class DashboardModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getTotalProductos() {
        try {
            $sql = "SELECT COUNT(*) as total FROM productos WHERE estado = 1";
            $result = $this->select($sql);
            return $result ? intval($result['total']) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getTotalCategorias() {
        try {
            $sql = "SELECT COUNT(*) as total FROM categorias WHERE estado = 1";
            $result = $this->select($sql);
            return $result ? intval($result['total']) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getTotalUsuarios() {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 1";
            $result = $this->select($sql);
            return $result ? intval($result['total']) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getTotalVentas() {
        try {
            $sql = "SELECT COUNT(*) as total FROM venta";
            $result = $this->select($sql);
            return $result ? intval($result['total']) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getTotalIngresos() {
        try {
            $sql = "SELECT COALESCE(SUM(total), 0) as ingresos FROM venta WHERE estado = 'completada'";
            $result = $this->select($sql);
            return $result ? floatval($result['ingresos']) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getProductosBajoStock($limite = 10) {
        try {
            $limite = intval($limite);
            $sql = "SELECT nombre, stock FROM productos WHERE stock <= $limite AND estado = 1 ORDER BY stock ASC LIMIT 5";
            $result = $this->selectAll($sql);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getVentasRecientes($limite = 5) {
        try {
            $limite = intval($limite);
            $sql = "SELECT v.id_venta, v.total, v.fecha, v.estado, c.nombre as cliente 
                    FROM venta v 
                    LEFT JOIN clientes c ON v.id_cliente = c.id_cliente 
                    ORDER BY v.fecha DESC 
                    LIMIT $limite";
            $result = $this->selectAll($sql);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getProductosMasVendidos($limite = 5) {
        try {
            $limite = intval($limite);
            $sql = "SELECT p.nombre, COALESCE(SUM(dv.cantidad), 0) as total_vendido 
                    FROM productos p
                    LEFT JOIN detalle_venta dv ON p.id_producto = dv.id_producto 
                    WHERE p.estado = 1
                    GROUP BY p.id_producto, p.nombre 
                    HAVING total_vendido > 0
                    ORDER BY total_vendido DESC 
                    LIMIT $limite";
            $result = $this->selectAll($sql);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getVentasPorMes() {
        try {
            $sql = "SELECT 
                        MONTH(fecha) as mes, 
                        YEAR(fecha) as año, 
                        COUNT(*) as total_ventas,
                        COALESCE(SUM(total), 0) as ingresos
                    FROM venta 
                    WHERE fecha >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                    GROUP BY YEAR(fecha), MONTH(fecha)
                    ORDER BY año DESC, mes DESC
                    LIMIT 12";
            $result = $this->selectAll($sql);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }
}
?>