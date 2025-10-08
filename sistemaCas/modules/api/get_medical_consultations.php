<?php
session_start();
include '../../db/connection.php';

header('Content-Type: application/json');


try {
    // Recoger los parámetros opcionales de la solicitud
    $year = isset($_GET['year']) ? intval($_GET['year']) : null;
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;

    // Base de la consulta
    $query = "SELECT 
                id_historial_asistencia_medica, 
                codigo_alumno, 
                fecha_asistencia, 
                observacion, 
                sintomas, 
                tratamiento, 
                relevancia 
              FROM historial_asistencia_medica 
              WHERE 1=1";

    $params = [];
    $types = "";

    // Agregar filtro por año si está definido
    if ($year) {
        $query .= " AND YEAR(fecha_asistencia) = ?";
        $params[] = $year;
        $types .= "i"; // Tipo entero
    }

    // Agregar filtro de búsqueda si está definido
    if ($search) {
        $query .= " AND (codigo_alumno LIKE ? OR fecha_asistencia LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "ss"; // Dos strings
    }

    // Preparar la consulta
    $stmt = $conn->prepare($query);

    // Vincular parámetros dinámicamente
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    // Ejecutar la consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Procesar los resultados
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Enviar respuesta
    echo json_encode(['success' => true, 'data' => $data]);

} catch (Exception $e) {
    // Enviar error si algo falla
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
