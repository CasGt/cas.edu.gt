<?php
session_start();
include '../../db/connection_3.php';

$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
if (!$token) {
    echo "<script>alert('Token inválido.'); window.location.href = 'index.php';</script>";
    exit();
}


// Verifica si el token es válido
$query = "SELECT correo_alumno FROM datos_mac WHERE token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    $_SESSION['correo'] = $usuario['correo_alumno'];
} else {
    echo "<script>alert('Token inválido o expirado.'); window.location.href = 'index.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mac_address = filter_input(INPUT_POST, 'mac_address', FILTER_SANITIZE_STRING);
    $tipo_os = filter_input(INPUT_POST, 'tipo_os', FILTER_SANITIZE_STRING);

    // Actualiza los datos de MAC en la tabla
    $query_update = "
        UPDATE datos_mac 
        SET mac_address = ?, tipo_os = ?, token = NULL 
        WHERE correo_alumno = ?
    ";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param('sss', $mac_address, $tipo_os, $_SESSION['correo']);

    if ($stmt_update->execute()) {
        echo "<script>alert('Dirección MAC registrada exitosamente.'); window.location.href = 'registrado.php';</script>";
    } else {
        echo "<script>alert('Error al registrar la dirección MAC.'); window.location.href = 'actualizar_mac.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dirección MAC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center">Registrar Dirección MAC</h1>
        <form action="" method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-6">
            <div class="mb-4">
                <label for="mac_address" class="block text-sm font-medium text-gray-600">Dirección MAC:</label>
                <input type="text" id="mac_address" name="mac_address" class="form-input w-full border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="tipo_os" class="block text-sm font-medium text-gray-600">Tipo de Sistema Operativo:</label>
                <select id="tipo_os" name="tipo_os" class="form-select w-full border border-gray-300 rounded" required>
                    <option value="Windows">Windows</option>
                    <option value="MacOS">MacOS</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 w-full">
                Registrar Dirección MAC
            </button>
        </form>
    </div>
</body>
</html>
