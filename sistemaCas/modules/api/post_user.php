<?php
include '../../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$required_fields = ['name', 'last_name', 'email', 'password', 'role'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos necesarios', 'missing_fields' => $missing_fields]);
    exit();
}

$name = htmlspecialchars(trim($input['name']));
$last_name = htmlspecialchars(trim($input['last_name']));
$email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
$password = password_hash(trim($input['password']), PASSWORD_BCRYPT);
$role = htmlspecialchars(trim($input['role']));


if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Correo electrónico inválido']);
    exit();
}

$allowed_roles = ['admin', 'medical', 'assistant'];
if (!in_array($role, $allowed_roles)) {
    http_response_code(400);
    echo json_encode(['error' => 'Rol inválido']);
    exit();
}

$query = "INSERT INTO users (name, last_name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("sssss", $name, $last_name, $email, $password, $role);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Usuario creado correctamente']);
    } else {
        file_put_contents('debug.log', 'Error en la ejecución: ' . $stmt->error . PHP_EOL, FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'Error al crear el usuario']);
    }
    $stmt->close();
} else {
    file_put_contents('debug.log', 'Error al preparar la consulta: ' . $conn->error . PHP_EOL, FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'Error al preparar la consulta']);
}

$conn->close();
