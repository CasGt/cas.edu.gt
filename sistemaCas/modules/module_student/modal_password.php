<?php
session_start();
if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primera Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center">Crear tu Contraseña</h1>
        <form action="verificar_usuario.php" method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-6">
            <div class="mb-4">
                <label for="pass" class="block text-sm font-medium text-gray-600">Nueva Contraseña:</label>
                <input type="password" id="pass" name="pass" class="form-input w-full border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 w-full">
                Crear Contraseña
            </button>
        </form>
    </div>
</body>
</html>
