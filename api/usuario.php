<?php
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "mensaje" => "Método no permitido. Use POST."
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

require_once __DIR__ . "/controllers/UsuarioController.php";

$datos = json_decode(file_get_contents("php://input"), true);

if (!is_array($datos)) {
    http_response_code(400);

    echo json_encode([
        "mensaje" => "El cuerpo de la solicitud debe ser un JSON válido."
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$usuarioController = new UsuarioController();

$resultado = $usuarioController->registrar($datos);

http_response_code($resultado["codigo"]);

echo json_encode(
    $resultado["respuesta"],
    JSON_UNESCAPED_UNICODE
);
?>