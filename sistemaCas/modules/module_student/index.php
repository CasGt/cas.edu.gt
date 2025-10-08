<?php
session_start();
include '../../db/connection_3.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

    // Verifica si el correo existe en la vista `informacion_estudiantes`
    $query = "SELECT * FROM informacion_estudiantes WHERE correo_alumno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Verifica si ya tiene contraseña y MAC registrada
        $query_mac = "SELECT * FROM datos_mac WHERE correo_alumno = ?";
        $stmt_mac = $conn->prepare($query_mac);
        $stmt_mac->bind_param('s', $correo);
        $stmt_mac->execute();
        $resultado_mac = $stmt_mac->get_result();

        if ($resultado_mac->num_rows > 0) {
            // Si ya tiene dirección MAC registrada
            $_SESSION['correo'] = $correo;
            echo "<script>alert('Ya tienes una dirección MAC registrada.'); window.location.href = 'registrado.php';</script>";
            exit();
        } else {
            // Si es la primera vez
            $_SESSION['correo'] = $correo;
            echo "<script>window.location.href = 'modal_password.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('El correo no existe en el sistema.'); window.location.href = 'index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center">Iniciar Sesión</h1>
        <form action="" method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-6">
            <div class="mb-4">
                <label for="correo" class="block text-sm font-medium text-gray-600">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" class="form-input w-full border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="pass" class="block text-sm font-medium text-gray-600">Contraseña:</label>
                <input type="password" id="pass" name="pass" class="form-input w-full border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 w-full">
                Iniciar Sesión
            </button>
        </form>
    </div>
</body>
</html>
