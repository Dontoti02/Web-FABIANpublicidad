<?php
class UsuarioModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getUsuarios() {
        $sql = "SELECT idusuario, nombre, email, rol FROM usuarios WHERE estado = 1";
        return $this->selectAll($sql);
    }

    public function registrarUsuario(string $nombre, string $email, string $password, string $rol) {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
        $datos = array($nombre, $email, $password, $rol);
        return $this->insert($sql, $datos);
    }

    public function eliminarUsuario(int $id) {
        $sql = "UPDATE usuarios SET estado = 0 WHERE idusuario = ?";
        $datos = array($id);
        return $this->save($sql, $datos);
    }

    public function login(string $email, string $password) {
        // Primero obtener el usuario por email
        $sql = "SELECT idusuario, nombre, email, rol, password FROM usuarios WHERE email = ? AND rol IN ('admin', 'subadmin') AND estado = 1";
        $datos = array($email);
        $user = $this->select($sql, $datos);
        
        if ($user && password_verify($password, $user['password'])) {
            // Remover password del resultado
            unset($user['password']);
            return $user;
        }
        
        return false;
    }

    public function getUsuario(int $id) {
        $sql = "SELECT * FROM usuarios WHERE idusuario = ? AND estado = 1";
        $datos = array($id);
        return $this->select($sql, $datos);
    }

    public function actualizarUsuario(int $id, string $nombre, string $email, string $telefono = '', string $direccion = '') {
        $sql = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE idusuario = ?";
        $datos = array($nombre, $email, $telefono, $direccion, $id);
        return $this->save($sql, $datos);
    }
}
?>