<?php
require_once '../../db/connection.php';

if (isset($_GET['codigo_alumno'])) {
    $codigoAlumno = $_GET['codigo_alumno'];
        $year = isset($_GET['year']) ? trim($_GET['year']) : 'NO RECIBIDO';

    $query = "SELECT nombres_madre, apellidos_madre, telefonocasa_madre, celular_madre, correo_madre 
              FROM madre 
              WHERE codigo_alumno = ? and cicloActual = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $codigoAlumno,$year);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $madre = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $madre]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró información del madre.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Parámetro código_alumno faltante.']);
}
$conn->close();
?>
