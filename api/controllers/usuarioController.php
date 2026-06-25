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
}
?>