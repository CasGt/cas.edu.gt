
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hidden { display: none; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
               <div class="flex justify-center mb-6">
            <img src="./assets/img/logo_cas_red.webp" alt="Logo CAS" class="w-16 h-auto">
        </div>
            <h2 class="text-2xl font-bold text-center mb-6">Bienvenido</h2>
            
            <!-- Pestañas para alternar entre "Iniciar Sesión" y "Crear Usuario" -->
            <div class="flex justify-center mb-6">
                <button id="loginTab" class="px-4 py-2 bg-red-900 text-white rounded-l hover:bg-red-600">Iniciar Sesión</button>
                <button id="registerTab" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-r">Crear Usuario</button>
            </div>

            <!-- Formulario de Inicio de Sesión -->
            <form id="loginForm" action="validar.php" method="POST" class="space-y-6">
                <div>
                    <label for="carnet_or_email" class="block font-semibold">Carnet o Correo del Alumno:</label>
                    <input type="text" id="carnet_or_email" name="carnet_or_email" class="w-full p-2 border border-gray-300 rounded" placeholder="Ingrese su carnet o correo" required>
                </div>
                <div>
                    <label for="password" class="block font-semibold">Contraseña:</label>
                    <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded" placeholder="Ingrese su contraseña" required>
                </div>
                
                <div class="text-right">
                <a href="./cambiar_pss/cambiarpsw.php" class="text-blue-500 hover:underline">Olvidé mi contraseña</a>
                </div>
                
                <button type="submit" class="w-full bg-red-900 text-white p-2 rounded hover:bg-red-600">Iniciar Sesión</button>
            </form>

            <!-- Formulario de Crear Usuario -->
            <form id="registerForm" method="POST" class="space-y-6 hidden">
    <div>
        <label for="new_nombres" class="block font-semibold">Nombres:</label>
        <input type="text" id="new_nombres" name="new_nombres" class="w-full p-2 border border-gray-300 rounded" placeholder="Ingrese sus nombres" required>
    </div>
    <div>
        <label for="new_apellidos" class="block font-semibold">Apellidos:</label>
        <input type="text" id="new_apellidos" name="new_apellidos" class="w-full p-2 border border-gray-300 rounded" placeholder="Ingrese sus apellidos" required>
    </div>
    <div>
        <label for="new_email" class="block font-semibold">Correo Electrónico:</label>
        <input type="email" id="new_email" name="new_email" class="w-full p-2 border border-gray-300 rounded" placeholder="Ingrese su correo" required>
    </div>
    <div>
        <label for="new_password" class="block font-semibold">Contraseña:</label>
        <input type="password" id="new_password" name="new_password" class="w-full p-2 border border-gray-300 rounded" placeholder="Ingrese su contraseña" required>
    </div>
    <button type="button" id="openModal" class="w-full bg-red-900 text-white p-2 rounded hover:bg-red-600">Crear Usuario</button>
</form>


            <!-- Modal para ingresar el código -->
            <div id="codeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
                <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
                    <h2 class="text-xl font-semibold mb-4">Ingrese el Código</h2>
                    <input type="text" id="codigo" class="w-full p-2 border border-gray-300 rounded mb-4" placeholder="Código de verificación">
                    <button id="verifyCode" class="w-full bg-red-900 text-white p-2 rounded hover:bg-red-600">Verificar Código</button>
                </div>
            </div>

            <!-- Mensajes de error -->
           <?php
if (isset($_GET['error'])) {
    echo '<p id="errorMessage" class="text-red-500 mt-4 text-center">' . htmlspecialchars($_GET['error']) . '</p>';
}
?>

        </div>
    </div>

    <!-- Script para alternar entre formularios y controlar el modal -->
    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const openModalButton = document.getElementById('openModal');
        const modal = document.getElementById('codeModal');
        const verifyButton = document.getElementById('verifyCode');
        const errorMessage = document.getElementById('errorMessage');

        // Mostrar formulario de inicio de sesión y ocultar el de registro
        loginTab.addEventListener('click', function() {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            loginTab.classList.add('bg-red-900', 'text-white');
            registerTab.classList.remove('bg-red-900', 'text-white');
            registerTab.classList.add('bg-gray-300', 'hover:bg-gray-400');
             if (errorMessage) {
        errorMessage.style.display = 'none';
    }
        });

        // Mostrar formulario de registro y ocultar el de inicio de sesión
        registerTab.addEventListener('click', function() {
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
            registerTab.classList.add('bg-red-900', 'text-white');
            loginTab.classList.remove('bg-red-900', 'text-white');
            loginTab.classList.add('bg-gray-300', 'hover:bg-gray-400');
             if (errorMessage) {
        errorMessage.style.display = 'none';
    }
        });

        // Mostrar el modal para ingresar el código
        openModalButton.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });

        // Verificar el código y realizar la validación
        verifyButton.addEventListener('click', function() {
    const codigo = document.getElementById('codigo').value;
    const nombres = document.getElementById('new_nombres').value;
    const apellidos = document.getElementById('new_apellidos').value;
    const email = document.getElementById('new_email').value;
    const password = document.getElementById('new_password').value;

    // Realizar una solicitud AJAX para verificar el código y crear el usuario
 const xhr = new XMLHttpRequest();
            xhr.open("POST", "verificar_codigo.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = xhr.responseText.trim(); // Asegurarse de eliminar espacios en blanco
                    if (response === "success") {
                        alert("¡Usuario creado exitosamente! ¡Bienvenid@!");
                        setTimeout(function() {
                            window.location.href = "formulario.php";  // Redirigir después de mostrar el alert
                        }, 500);  // Añade un retraso de 500ms antes de redirigir
                    } else if (response === "codigo_usado") {
                        alert("El código ya fue utilizado. Por favor, solicita uno nuevo a administración.");
                    } else if (response === "codigo_invalido") {
                        alert("Código incorrecto. Por favor, intente de nuevo.");
                    } else if (response === "error_crear_usuario") {
                        alert("Hubo un error al crear el usuario. Por favor, intente de nuevo más tarde.");
                    }
                }
            };
    xhr.send(`codigo=${codigo}&new_nombres=${nombres}&new_apellidos=${apellidos}&new_email=${email}&new_password=${password}`);
});

    </script>
</body>
</html>
