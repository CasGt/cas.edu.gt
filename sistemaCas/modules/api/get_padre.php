<?php
require_once '../../db/connection.php';

if (isset($_GET['codigo_alumno'])) {
    $codigoAlumno = $_GET['codigo_alumno'];
    $year = isset($_GET['year']) ? trim($_GET['year']) : 'NO RECIBIDO';
    
    $query = "SELECT nombres_padre, apellidos_padre, telefonocasa_padre, celular_padre, correo_padre 
              FROM padre 
              WHERE codigo_alumno = ? and cicloActual = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $codigoAlumno,$year);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $padre = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $padre]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró información del padre.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Parámetro código_alumno faltante.']);
}
$conn->close();
?>
