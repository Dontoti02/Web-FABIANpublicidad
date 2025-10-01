<?php
class ConfiguracionModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function actualizarDatosPersonales(int $id, string $nombre, string $email) {
        $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE idusuario = ?";
        $datos = array($nombre, $email, $id);
        return $this->save($sql, $datos);
    }

    public function cambiarContrasena(int $id, string $contrasena_nueva) {
        $contrasena_hash = password_hash($contrasena_nueva, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET password = ? WHERE idusuario = ?";
        $datos = array($contrasena_hash, $id);
        return $this->save($sql, $datos);
    }

    public function verificarContrasenaActual(int $id, string $contrasena_actual) {
        $sql = "SELECT * FROM usuarios WHERE idusuario = ?";
        $datos = array($id);
        $usuario = $this->select($sql, $datos);

        if ($usuario && password_verify($contrasena_actual, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public function obtenerDatosUsuario(int $id) {
        try {
            $sql = "SELECT nombre, email, estado FROM usuarios WHERE idusuario = ?";
            $datos = array($id);

            // Usar consulta directa para debug
            $conexion = new Conexion();
            $pdo = $conexion->conect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($datos);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("ConfiguracionModel::obtenerDatosUsuario - ID: $id, SQL: $sql, Result: " . print_r($result, true));

            if ($result === false) {
                error_log("ConfiguracionModel::obtenerDatosUsuario - Usuario no encontrado con ID: $id");
                return null;
            }

            // Verificar si el usuario está activo
            if ($result['estado'] != 1) {
                error_log("ConfiguracionModel::obtenerDatosUsuario - Usuario inactivo con ID: $id");
                return null;
            }

            // Devolver solo los campos necesarios
            return array(
                'nombre' => $result['nombre'],
                'email' => $result['email']
            );

        } catch (Exception $e) {
            error_log("ConfiguracionModel::obtenerDatosUsuario - Exception: " . $e->getMessage());
            return null;
        }
    }
}
?>
