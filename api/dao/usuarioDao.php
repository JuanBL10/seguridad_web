<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/usuario.php";

class UsuarioDAO{
    private $conexion;
    public function __construct(){
        $conexionBaseDatos = new Conexion();
        $this->conexion = $conexionBaseDatos->Conectar();
    }
    //Para ingresar un nuevo usuario
    public function validarSiCorreoExiste($correo){
        $sql = "Select id from usuarios 
                where correo = :correo";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([":correo" => $correo]);
        return $consulta->fetch() !== false;
    }
    public function registrar(Usuario $usuario)
    {
        $sql = "INSERT INTO usuarios (nombre, correo, password)
                VALUES (:nombre, :correo, :password)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ":nombre" => $usuario->getNombre(),
            ":correo" => $usuario->getCorreo(),
            ":password" => $usuario->getPassword()
        ]);
    }

    //Para validar si un usuario existe y que inicie sesion
    public function obtenerDatosUsuario($correo){
        $sql = "Select nombre, correo, password
                from usuarios where correo = :correo
                and estado = 'Activo'";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            ":correo" => $correo
        ]);
        return $consulta->fetch();
    }

    //Solicitar recuperacion
    public function guardarTokenRecuperacion($correo, $token)
    {
        $sql = "UPDATE usuarios
                SET token_recuperacion = :token
                WHERE correo = :correo";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ":token" => $token,
            ":correo" => $correo
        ]);
    }

    //Restablecer contra
    public function buscarPorTokenRecuperacion($token)
    {
        $sql = "SELECT id, correo
                FROM usuarios
                WHERE token_recuperacion = :token";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ":token" => $token
        ]);

        return $consulta->fetch();
    }

    public function restablecerPassword($token, $passwordEncriptada)
    {
        $sql = "UPDATE usuarios
                SET password = :password,
                    token_recuperacion = NULL
                WHERE token_recuperacion = :token";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ":password" => $passwordEncriptada,
            ":token" => $token
        ]);
    }
}
?>