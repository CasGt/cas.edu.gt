<?php
require_once '../../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No se especificó ninguna acción']);
    exit();
}

$action = $input['action'];

switch ($action) {
    case 'pending':
        handlePending($input, $conn);
        break;

    case 'update_student':
        updateStudent($input, $conn);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => "La acción '$action' no está soportada"]);
        break;
}

$conn->close();

function handlePending($data, $conn) {
    $required_fields = ['id', 'codigo_alumno', 'updated_at', 'estado'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan campos necesarios', 'missing_fields' => $missing_fields]);
        return;
    }

    $id = intval($data['id']);
    $codigo_alumno = htmlspecialchars(trim($data['codigo_alumno']));
    $updated_at = htmlspecialchars(trim($data['updated_at']));
    $estado = intval($data['estado']); // 1 = Activar, 3 = Eliminar

    if (!in_array($estado, [1, 3])) {
        http_response_code(400);
        echo json_encode(['error' => 'Estado no válido. Debe ser 1 (Activar) o 3 (Eliminar)']);
        return;
    }

    $query = "UPDATE alumno SET estado = ?, updated_at = ? WHERE id_alumno = ? AND codigo_alumno = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("isis", $estado, $updated_at, $id, $codigo_alumno);
        if ($stmt->execute()) {
            echo json_encode([
                'message' => $estado === 1 
                    ? 'Estudiante activado correctamente' 
                    : 'Estudiante eliminado correctamente'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el estado del estudiante', 'sql_error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al preparar la consulta', 'sql_error' => $conn->error]);
    }
}

function updateStudent($data, $conn) {
    $required_fields = ['codigo_alumno', 'nombres', 'apellidos', 'correo', 'grado', 'nacimiento', 'correo_encargado', 'year'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan campos necesarios', 'missing_fields' => $missing_fields]);
        return;
    }
  
    $id = intval($data['id']);
    $cod_alumno = htmlspecialchars(trim($data['codigo_alumno']));
    $carnet = htmlspecialchars(trim($data['carnet']));
    $nombres = htmlspecialchars(trim($data['nombres']));
    $apellidos = htmlspecialchars(trim($data['apellidos']));
    $correo = htmlspecialchars(trim($data['correo']));
    $grado = htmlspecialchars(trim($data['grado']));
    $nacimiento = htmlspecialchars(trim($data['nacimiento']));
    $correo_encargado = htmlspecialchars(trim($data['correo_encargado']));
    $cicloActual = htmlspecialchars(trim($data['year']));

    $query = "UPDATE alumno 
              SET carnet = ?, nombres_alumno = ?, apellidos_alumno = ?, correo_alumno = ?, grado_alumno = ?, nacimiento_alumno = ?, correo_encargado_llenar_form = ?
              WHERE codigo_alumno = ? AND cicloActual = ?";
              
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("issssssss", $carnet, $nombres, $apellidos, $correo, $grado, $nacimiento, $correo_encargado, $cod_alumno, $cicloActual);
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Estudiante actualizado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al ejecutar la consulta', 'sql_error' => $stmt->error]);
        }
        
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al preparar la consulta', 'sql_error' => $conn->error]);
    }
}
?>