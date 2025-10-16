<?php
session_start();
include '../../db/connection.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';

validateAccess('dashboard');

$anio_actual = date('Y');

// Total de formularios del año actual con estado 1
$query_actual = "SELECT COUNT(*) as total_actual FROM alumno WHERE estado = 1 AND cicloActual = ?";
$stmt_actual = $conn->prepare($query_actual);
$stmt_actual->bind_param("i", $anio_actual);
$stmt_actual->execute();
$result_actual = $stmt_actual->get_result();
$total_actual = $result_actual->fetch_assoc()['total_actual'];

// Total de formularios del año actual - 1 con estado 1
$anio_anterior = $anio_actual - 1;
$query_anterior = "SELECT COUNT(*) as total_anterior FROM alumno WHERE estado = 1 AND cicloActual = ?";
$stmt_anterior = $conn->prepare($query_anterior);
$stmt_anterior->bind_param("i", $anio_anterior);
$stmt_anterior->execute();
$result_anterior = $stmt_anterior->get_result();
$total_anterior = $result_anterior->fetch_assoc()['total_anterior'];

// Total de formularios del año actual + 1 con estado 2
$anio_proximo = $anio_actual + 1;
$query_proximo = "SELECT COUNT(*) as total_proximo FROM alumno WHERE estado = 2 AND cicloActual = ?";
$stmt_proximo = $conn->prepare($query_proximo);
$stmt_proximo->bind_param("i", $anio_proximo);
$stmt_proximo->execute();
$result_proximo = $stmt_proximo->get_result();
$total_proximo = $result_proximo->fetch_assoc()['total_proximo'];

// Total de formularios del año actual con estado 2
$query_actual_estado2 = "SELECT COUNT(*) as total_actual_estado2 FROM alumno WHERE estado = 2 AND cicloActual = ?";
$stmt_actual_estado2 = $conn->prepare($query_actual_estado2);
$stmt_actual_estado2->bind_param("i", $anio_actual);
$stmt_actual_estado2->execute();
$result_actual_estado2 = $stmt_actual_estado2->get_result();
$total_actual_estado2 = $result_actual_estado2->fetch_assoc()['total_actual_estado2'];

// Cerrar consultas
$stmt_actual->close();
$stmt_anterior->close();
$stmt_proximo->close();
$stmt_actual_estado2->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../src/css/styles.css">
    <style>
        .centered {
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include '../shared/navbar.php'; ?>

    <div class="container mx-auto px-4 py-6 centered">
        
    <?php displayAlert("dashboard"); ?>

        <h1 class="text-4xl font-bold text-gray-800 mb-4">Bienvenido al sistema de información de CAS</h1>
        <p class="text-lg text-gray-600 mb-6">
            Utiliza el menú superior para navegar entre las opciones disponibles del sistema.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center">

            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-700 mb-4">Formularios (<?php echo $anio_anterior; ?>)</h2>
                <p class="text-lg text-gray-500">Cantidad de formularios validados</p>
                <p class="text-5xl font-bold text-gray-800 mt-4"><?php echo $total_anterior; ?></p>
            </div>

              <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-700 mb-4">Formularios (<?php echo $anio_actual; ?>)</h2>
                <p class="text-lg text-gray-500">Cantidad de formularios validados</p>
                <p class="text-5xl font-bold text-gray-800 mt-4"><?php echo $total_actual; ?></p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-700 mb-4">Formularios llenos (<?php echo $anio_proximo; ?>)</h2>
                <p class="text-lg text-gray-500">Cantidad de formularios validados</p>
                <p class="text-5xl font-bold text-gray-800 mt-4"><?php echo $total_proximo; ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-8 justify-center mt-8">
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <h2 class="text-2xl font-bold text-red-600 mb-4">Formularios no validados (<?php echo $anio_actual; ?>)</h2>
                <p class="text-lg text-gray-500">Cantidad de formularios llenos pero no habilitados</p>
                <p class="text-5xl font-bold text-red-800 mt-4"><?php echo $total_actual_estado2; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
