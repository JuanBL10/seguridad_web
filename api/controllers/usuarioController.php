<?php

require_once __DIR__ . "/../dao/usuarioDao.php";
require_once __DIR__ . "/../models/usuario.php";

class UsuarioController
{
    private $usuarioDAO;

    public function __construct()
    {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function registrar($datos)
    {
        $nombre = trim($datos["nombre"] ?? "");
        $correo = trim($datos["correo"] ?? "");
        $password = $datos["password"] ?? "";

        if ($nombre === "" || $correo === "" || $password === "") {
            return [
                "codigo" => 400,
                "respuesta" => [
                    "mensaje" => "Todos los campos son obligatorios."
                ]
            ];
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return [
                "codigo" => 400,
                "respuesta" => [
                    "mensaje" => "El correo no tiene un formato válido."
                ]
            ];
        }

        if ($this->usuarioDAO->validarSiCorreoExiste($correo)) {
            return [
                "codigo" => 409,
                "respuesta" => [
                    "mensaje" => "El correo ya está registrado."
                ]
            ];
        }

        $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);

        $usuario = new Usuario(
            $nombre,
            $correo,
            $passwordEncriptada
        );

        $registroExitoso = $this->usuarioDAO->registrar($usuario);

        if ($registroExitoso) {
            return [
                "codigo" => 201,
                "respuesta" => [
                    "mensaje" => "Usuario registrado exitosamente."
                ]
            ];
        }

        return [
            "codigo" => 500,
            "respuesta" => [
                "mensaje" => "No fue posible registrar el usuario."
            ]
        ];
    }

    public function iniciarSesion($datos)
    {
        $correo = trim($datos["correo"] ?? "");
        $password = $datos["password"] ?? "";

        if($correo === "" || $password === ""){
            return [
                "codigo" => 400,
                "respuesta" => [
                    "mensaje" => "Correo y contraseña son campos obligatorios."
                ]
            ];
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return [
                "codigo" => 400,
                "respuesta" => [
                    "mensaje" => "El correo no tiene un formato válido."
                ]
            ];
        }
        
        $usuario = $this->usuarioDAO->obtenerDatosUsuario($correo);

        if ($usuario === false) {
            return [
                "codigo" => 401,
                "respuesta" => [
                    "mensaje" => "Usuario no encontrado o inactivo."
                ]
            ];
        }

        if (!password_verify($password, $usuario["password"])) {
            return [
                "codigo" => 401,
                "respuesta" => [
                    "mensaje" => "Contraseña incorrecta."
                ]
            ];
        }

        return [
            "codigo" => 200,
            "respuesta" => [
                "mensaje" => "Inicio de sesión exitoso.",
                "usuario" => [
                    "nombre" => $usuario["nombre"],
                    "correo" => $usuario["correo"]
                ]
            ]
        ];
    }

    public function solicitarRecuperacion($datos)
    {
        $correo = trim($datos["correo"] ?? "");

        if ($correo === "") {
            return [
                "codigo" => 400,
                "respuesta" => [
                    "mensaje" => "El correo es obligatorio."
                ]
            ];
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return [
                "codigo" => 400,
                "respuesta" => [
                    "mensaje" => "El correo no tiene un formato válido."
                ]
            ];
        }

        if (!$this->usuarioDAO->validarSiCorreoExiste($correo)) {
            return [
                "codigo" => 404,
                "respuesta" => [
                    "mensaje" => "No existe un usuario registrado con ese correo."
                ]
            ];
        }

        $token = bin2hex(random_bytes(32));

        $tokenGuardado = $this->usuarioDAO->guardarTokenRecuperacion(
            $correo,
            $token
        );

        if (!$tokenGuardado) {
            return [
                "codigo" => 500,
                "respuesta" => [
                    "mensaje" => "No fue posible generar el token de recuperación."
                ]
            ];
        }

        return [
            "codigo" => 200,
            "respuesta" => [
                "mensaje" => "Solicitud de recuperación generada exitosamente.",
                "token" => $token
            ]
        ];
    }
}
?>