<?php
session_start();
include './modules/shared/alerts.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/css/styles.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <?php displayAlert("index"); ?>
    <div class="container mx-auto px-4 flex-grow">
        <br><br>
        <h1 class="text-2xl font-bold text-center mb-6">Iniciar sesión</h1>
        <div class="bg-white shadow-lg rounded-lg flex flex-col lg:flex-row lg:max-w-4xl mx-auto">
            <!-- Formulario -->
            <div class="p-8 lg:w-1/2 flex flex-col justify-center">
                <div class="text-center mb-4">
                    <img src="src/images/logo_cas_rojo.png" alt="Logo" class="mx-auto w-20">
                </div>
                <form action="modules/module_user-system/login.php" method="POST" class="space-y-4">
                    <div>
                        <label for="usuario" class="block text-sm font-medium text-gray-600">Correo electrónico</label>
                        <input type="text" id="email" name="email" class="w-full border border-gray-300 rounded-md p-2 mt-1" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                        <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md p-2 mt-1" required>
                    </div>
                    <div class="text-right">
                        <a href="./modules/module_user-system/forgot_password.php" class="text-sm text-red-500">Olvidé mi contraseña</a>
                    </div>
                    <button type="submit" class="w-full bg-red-900 text-white py-2 px-4 rounded-md hover:bg-red-600">Iniciar sesión</button>
                </form>
            </div>
            <!-- Imagen -->
            <div class="lg:w-1/2">
                <img src="src/images/login_img.jpeg" alt="Imagen" class="h-full w-full object-cover rounded-b-lg lg:rounded-r-lg lg:rounded-bl-none">
            </div>
        </div>
    </div>
    <footer class="bg-gray-800 text-white text-center py-2 mt-4">
        <p class="text-sm">CAS - TODOS LOS DERECHOS RESERVADOS 2024</p>
    </footer>
</body>
</html>
