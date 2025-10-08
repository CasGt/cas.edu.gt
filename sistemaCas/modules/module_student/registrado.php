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
    <title>MAC Registrada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center">Registro Completado</h1>
        <p class="text-center mt-4">
            Ya tienes una dirección MAC registrada. Si necesitas cambiarla, por favor contacta a Soporte de Tecnología o Dirección.
        </p>
    </div>
</body>
</html>
