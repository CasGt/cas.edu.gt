<?php
require '../../db/connection.php';
header('Content-Type: application/json');

if (!isset($_GET['codigo_alumno']) || empty(trim($_GET['codigo_alumno'])) || 
    !isset($_GET['ciclo_actual']) || empty(trim($_GET['ciclo_actual']))) {
    echo json_encode(['success' => false, 'message' => 'El código del alumno y el ciclo actual son obligatorios.']);
    exit();
}

$codigo_alumno = trim($_GET['codigo_alumno']);
$ciclo_actual = trim($_GET['ciclo_actual']);

try {
    $data = [
        'alergias' => [],
        'enfermedades' => [],
        'medicamento_diario' => [],
        'otras_vacunas' => [],
        'medicamentos' => [],
        'vacunas' => []
    ];

    $stmt = $conn->prepare("SELECT alergias_estudiante FROM historial_medico WHERE codigo_alumno = ?");
    $stmt->bind_param("s", $codigo_alumno);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $alergias = preg_split("/[,\/\-]+/", $row['alergias_estudiante']);
        foreach ($alergias as $alergia) {
            $data['alergias'][] = ucfirst(trim($alergia));
        }
    }

    $stmt = $conn->prepare("SELECT enfermedades_estudiante FROM historial_medico WHERE codigo_alumno = ? AND cicloActual = ?");
    $stmt->bind_param("ss", $codigo_alumno, $ciclo_actual);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $enfermedades = preg_split("/[,\/\-]+/", $row['enfermedades_estudiante']);
        foreach ($enfermedades as $enfermedad) {
            $data['enfermedades'][] = ucfirst(trim($enfermedad));
        }
    }

    $stmt = $conn->prepare("SELECT medicamento_diario FROM historial_medico WHERE codigo_alumno = ? AND cicloActual = ?");
    $stmt->bind_param("ss", $codigo_alumno, $ciclo_actual);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $medicamentos = preg_split("/[,\/\-]+/", $row['medicamento_diario']);
        foreach ($medicamentos as $medicamento) {
            $data['medicamento_diario'][] = ucfirst(trim($medicamento));
        }
    }

    $stmt = $conn->prepare("SELECT otras_vacunas_estudiante FROM historial_medico WHERE codigo_alumno = ? AND cicloActual = ?");
    $stmt->bind_param("ss", $codigo_alumno, $ciclo_actual);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $vacunas = preg_split("/[,\/\-]+/", $row['otras_vacunas_estudiante']);
        foreach ($vacunas as $vacuna) {
            $data['otras_vacunas'][] = ucfirst(trim($vacuna));
        }
    }

    $stmt_columns = $conn->prepare("SELECT * FROM historial_medico WHERE codigo_alumno = ? AND cicloActual = ?");
    $stmt_columns->bind_param("ss", $codigo_alumno, $ciclo_actual);
    $stmt_columns->execute();
    $result_columns = $stmt_columns->get_result();

    if ($result_columns->num_rows > 0) {
        $columnas_medicamentos = ['acetaminofen', 'alfersurf', 'alka_d', 'alka_seltzer', 'aspirina', 'bromexina', 'cataflan', 'certal', 'cloprin',
        'cofal_fuerte', 'diclofenaco', 'fastum', 'gencloben', 'hidrocortisona', 'histaprin', 'ibuprofeno', 'irs', 'loperamida', 'loratadina',
        'nauseol', 'nistatina', 'otik', 'otomidil', 'pasta_lasar', 'peptobismol', 'piralvex', 'ranitidina', 'sal_andrews', 'suero_oral',
        'sulfacetamida', 'tabcin', 'trilox_antiasido'];
    $columnas_vacunas = [
        'antigripal', 'fiebre_tifoidea', 'gripe_viral', 'hepatitis_a', 'hepatitis_b', 'neumococo', 'paperas', 'papiloma', 'polio',
        'polivalente', 'rotavirus', 'rubeola', 'sarampion', 'tuberculosis', 'varicela'];

        while ($row = $result_columns->fetch_assoc()) {
            foreach ($columnas_medicamentos as $med) {
                if (!empty($row[$med]) && ($row[$med] == 1 || strtoupper($row[$med]) === 'ON')) {
                    $data['medicamentos'][] = ucwords(str_replace('_', ' ', $med));
                }
                
            }
            foreach ($columnas_vacunas as $vac) {
                if (!empty($row[$vac]) && ($row[$vac] == 1 || strtoupper($row[$vac]) === 'ON')) {
                    $data['vacunas'][] = ucwords(str_replace('_', ' ', $vac));
                }
                
            }
        }
    }

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    $conn->close();
}
