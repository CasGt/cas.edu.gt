<?php
require_once '../../db/connection.php';
header('Content-Type: application/json; charset=utf-8');
$conn->set_charset('utf8mb4');
if (isset($_GET['codigo_alumno']) && !empty(trim($_GET['codigo_alumno']))) {
    $codigoAlumno = trim($_GET['codigo_alumno']);
     $year = isset($_GET['year']) ? trim($_GET['year']) : 'NO RECIBIDO';

    try {
        $query = "SELECT 
            id_alumno, 
            carnet, 
            codigo_alumno, 
            nombres_alumno AS nombres, 
            apellidos_alumno AS apellidos, 
            correo_alumno AS correo, 
            grado_alumno AS grado, 
            nacimiento_alumno AS fecha_nacimiento, 
            correo_encargado_llenar_form AS correo_encargado,
            cicloActual
        FROM alumno
        WHERE codigo_alumno = ? AND cicloActual = ? AND estado = 1";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $codigoAlumno,$year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            if (is_numeric($student['grado']) && intval($student['grado']) >= 1 && intval($student['grado']) <= 12) {
                $student['grado'] = 'G0' . $student['grado'];
            }
       
            echo json_encode(['success' => true, 'data' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el estudiante con el código proporcionado.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} elseif (isset($_GET['action']) && $_GET['action'] === 'pending') {
    $anio_actual = date("Y")+1;
    
    $grade = isset($_GET['grade']) ? trim($_GET['grade']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    try {
        $query = "SELECT 
            alumno.id_alumno AS id, 
            alumno.carnet, 
            alumno.codigo_alumno, 
            alumno.nombres_alumno AS nombres, 
            alumno.apellidos_alumno AS apellidos, 
            alumno.correo_alumno AS correo, 
            alumno.grado_alumno AS grado, 
            alumno.nacimiento_alumno AS fecha_nacimiento, 
            alumno.correo_encargado_llenar_form AS correo_encargado, 
            alumno.updated_at,
            cicloActual
        FROM alumno
        WHERE alumno.cicloActual = ? AND alumno.estado = 2";

        if (!empty($grade)) {
            $query .= " AND alumno.grado_alumno = ?";
        }

        if (!empty($search)) {
            $query .= " AND (alumno.nombres_alumno LIKE ? OR alumno.apellidos_alumno LIKE ? OR alumno.carnet LIKE ?)";
        }

        $stmt = $conn->prepare($query);

        if (!empty($grade) && !empty($search)) {
            $search = '%' . $search . '%';
            $stmt->bind_param("isssss", $anio_actual, $grade, $search, $search, $search);
        } elseif (!empty($grade)) {
            $stmt->bind_param("is", $anio_actual, $grade);
        } elseif (!empty($search)) {
            $search = '%' . $search . '%';
            $stmt->bind_param("isss", $anio_actual, $search, $search, $search);
        } else {
            $stmt->bind_param("i", $anio_actual);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                if (is_numeric($row['grado']) && intval($row['grado']) >= 1 && intval($row['grado']) <= 12) {
                    $row['grado'] = 'G0' . $row['grado'];
                }
                $data[] = $row;
            }
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => true, 'data' => []]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

elseif (isset($_GET['action']) && $_GET['action'] === 'recent_or_previous') {
    $anio_actual = date("Y");
    $anio_anterior = $anio_actual - 1;

    $ciclo_actual = isset($_GET['ciclo_actual']) && !empty($_GET['ciclo_actual']) ? intval($_GET['ciclo_actual']) : null;
    $search = isset($_GET['search']) && !empty(trim($_GET['search'])) ? '%' . trim($_GET['search']) . '%' : null;
    $grado = isset($_GET['grado']) && !empty($_GET['grado']) ? trim($_GET['grado']) : null;
    $get_years = isset($_GET['get_years']) && $_GET['get_years'] === 'true';

    try {
        $response = [];

        if ($get_years) {
            $query_years = "SELECT DISTINCT cicloActual FROM alumno WHERE estado = 1 ORDER BY cicloActual DESC";
            $result_years = $conn->query($query_years);

            $years = [];
            while ($row = $result_years->fetch_assoc()) {
                $years[] = $row['cicloActual'];
            }
            $response['years'] = $years;
        }

        $query_grados = "SELECT DISTINCT grado_alumno AS grado FROM alumno WHERE estado = 1 ORDER BY grado";
        $result_grados = $conn->query($query_grados);

        $grados = [];
        while ($row = $result_grados->fetch_assoc()) {
            $grados[] = $row['grado'];
        }
        $response['grados'] = $grados;

        $query = "SELECT 
                    alumno.id_alumno AS id,
                    alumno.carnet,
                    alumno.codigo_alumno,
                    alumno.nombres_alumno AS nombres,
                    alumno.apellidos_alumno AS apellidos,
                    alumno.correo_alumno AS correo,
                    alumno.grado_alumno AS grado,
                    alumno.nacimiento_alumno AS fecha_nacimiento,
                    alumno.correo_encargado_llenar_form AS correo_encargado,
                    alumno.cicloActual,
                    alumno.updated_at
                FROM alumno
                WHERE alumno.estado = 1";

        $params = [];
        $types = "";

        if ($ciclo_actual) {
            $query .= " AND alumno.cicloActual = ?";
            $params[] = $ciclo_actual;
            $types .= "i";
        } else {
            $query .= " AND alumno.cicloActual IN (?, ?)";
            $params[] = $anio_anterior;
            $params[] = $anio_actual;
            $types .= "ii";
        }

        if ($grado) {
            $query .= " AND alumno.grado_alumno = ?";
            $params[] = $grado;
            $types .= "s";
        }

        if ($search) {
            $query .= " AND (alumno.nombres_alumno LIKE ? 
                            OR alumno.apellidos_alumno LIKE ? 
                            OR alumno.carnet LIKE ?)";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $types .= "sss";
        }

        $query .= " ORDER BY alumno.nombres_alumno, alumno.apellidos_alumno";

        $stmt = $conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                if (is_numeric($row['grado']) && intval($row['grado']) >= 1 && intval($row['grado']) <= 12) {
                    $row['grado'] = 'G0' . $row['grado'];
                }
                $data[] = $row;
            }
            $response['success'] = true;
            $response['data'] = $data;
        } else {
            $response['success'] = true;
            $response['data'] = [];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}



else {
    $year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
    $status = isset($_GET['status']) ? intval($_GET['status']) : 1;
    $grade = isset($_GET['grade']) ? trim($_GET['grade']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    try {
        $query = "SELECT 
            id_alumno AS id, 
            carnet, 
            codigo_alumno, 
            nombres_alumno AS nombres, 
            apellidos_alumno AS apellidos, 
            correo_alumno AS correo, 
            grado_alumno AS grado, 
            nacimiento_alumno AS fecha_nacimiento, 
            correo_encargado_llenar_form AS correo_encargado ,
            cicloActual
        FROM alumno
        WHERE estado = ? AND cicloActual = ?";

        if (!empty($grade)) {
            $query .= " AND grado_alumno = ?";
        }

        if (!empty($search)) {
            $query .= " AND (nombres_alumno LIKE ? OR apellidos_alumno LIKE ? OR carnet LIKE ?)";
        }

        $stmt = $conn->prepare($query);

        if (!empty($grade) && !empty($search)) {
            $search = '%' . $search . '%';
            $stmt->bind_param("iisssss", $status, $year, $grade, $search, $search, $search);
        } elseif (!empty($grade)) {
            $stmt->bind_param("iis", $status, $year, $grade);
        } elseif (!empty($search)) {
            $search = '%' . $search . '%';
            $stmt->bind_param("iisss", $status, $year, $search, $search, $search);
        } else {
            $stmt->bind_param("ii", $status, $year);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                if (is_numeric($row['grado']) && intval($row['grado']) >= 1 && intval($row['grado']) <= 12) {
                    $row['grado'] = 'G0' . $row['grado'];
                }
                $data[] = $row;
            }
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => true, 'data' => []]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

$conn->close();
