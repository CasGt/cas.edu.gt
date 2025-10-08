<?php 
session_start();
include '../shared/role_validation.php';
include '../shared/alerts.php';

validateAccess('usuarios');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../src/css/styles.css">
</head>
<body class="bg-gray-100">
    <?php include '../shared/navbar.php'; ?>

    <div class="flex flex-col justify-center items-center min-h-screen px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-8">
            <h1 class="text-3xl font-bold text-center mb-4">Crear nuevo usuario</h1>
            <p class="text-gray-600 text-center mb-6">
                Ingresa los datos del usuario para registrarlo en el sistema.
            </p>
            <form id="create-user-form">
                <label for="name" class="block font-bold mb-2">Nombres</label>
                <input type="text" id="name" name="name" placeholder="Nombre 1 Nombre 2 Nombre 3" 
                       class="w-full px-4 py-2 border rounded-lg mb-4" required>
                
                <label for="last_name" class="block font-bold mb-2">Apellidos</label>
                <input type="text" id="last_name" name="last_name" placeholder="Apellido 1 Apellido 2 Apellido 3" 
                       class="w-full px-4 py-2 border rounded-lg mb-4" required>
                
                <label for="email" class="block font-bold mb-2">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="ejemplo: usuario@gmail.com" 
                       class="w-full px-4 py-2 border rounded-lg mb-4" required>
                
                <label for="password" class="block font-bold mb-2">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="********"
                       class="w-full px-4 py-2 border rounded-lg mb-4" required>
                
                <label for="role" class="block font-bold mb-2">Rol</label>
                <select id="role" name="role" class="w-full px-4 py-2 border rounded-lg mb-4" required>
                    <option value="admin">Admin</option>
                    <option value="medical">Medical</option>
                    <option value="assistant">Assistant</option>
                </select>
                
                <button type="button" onclick="saveUser()" 
                        class="bg-red-900 text-white px-4 py-2 w-full rounded hover:bg-red-700">
                    Guardar Usuario
                </button>
            </form>
        </div>
    </div>

    <script>
        function saveUser() {
            const name = document.querySelector('#name').value.trim();
            const lastName = document.querySelector('#last_name').value.trim();
            const email = document.querySelector('#email').value.trim();
            const password = document.querySelector('#password').value.trim();
            const role = document.querySelector('#role').value;

            if (!name || !lastName || !email || !password || !role) {
                alert('Por favor, completa todos los campos.');
                return;
            }

            fetch('../api/post_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    name: name,
                    last_name: lastName,
                    email: email,
                    password: password,
                    role: role,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        document.getElementById('create-user-form').reset();
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error al guardar el usuario:', error));
        }
    </script>
</body>
</html>
