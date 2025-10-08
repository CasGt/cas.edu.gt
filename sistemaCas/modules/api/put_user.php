<?php
include '../../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$required_fields = ['id', 'name', 'last_name', 'email', 'role', 'status'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (!isset($input[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos necesarios', 'missing_fields' => $missing_fields]);
    exit();
}

$id = intval($input['id']);
$name = htmlspecialchars(trim($input['name']));
$last_name = htmlspecialchars(trim($input['last_name']));
$email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
$role = htmlspecialchars(trim($input['role']));
$status = intval($input['status']);

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Correo electrónico inválido']);
    exit();
}

$query = "UPDATE users SET name = ?, last_name = ?, email = ?, role = ?, status = ?, updated_at = NOW() WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("ssssii", $name, $last_name, $email, $role, $status, $id);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Usuario actualizado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al actualizar el usuario']);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al preparar la consulta']);
}

$conn->close();
