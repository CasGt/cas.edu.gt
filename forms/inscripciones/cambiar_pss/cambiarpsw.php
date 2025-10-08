<?php 
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $usuario = $_POST["usuario"]; // Puede ser carnet o correo
    $email_encargado = $_POST["email-encargado"];
    $nuevaContrasena = $_POST["contrasena"];
    $repetirContrasena = $_POST["repetir-contrasena"];

    // Validar que las contraseñas coincidan
    if ($nuevaContrasena !== $repetirContrasena) {
        echo "Las contraseñas no coinciden. Por favor, inténtelo de nuevo.";
    } else {
        
        $servername = "127.0.0.1:3306"; 
        $username = "u185752343_admin_cas_inscr";
        $password = "Admin_cas_inscr2025*";
        $dbname = "u185752343_inscripciones";

        
        
        
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Año actual y estado requeridos
        $anio = date("Y");
        $anio_actual = $anio - 1;
        $estado_requerido = 1;

        // Determinar si el usuario ingresado es un carnet o un correo
        $isEmail = filter_var($usuario, FILTER_VALIDATE_EMAIL);

        // Inicializar variable para la consulta
        $sql = "";

        // Condicional para verificar si es correo o carnet
        if ($isEmail) {
            // Si es un correo, buscamos tanto en alumno como en alumno_nuevo_ingreso
            $sql = "
                SELECT 'alumno' AS tabla, carnet, correo_alumno, pass 
                FROM alumno 
                WHERE correo_alumno = ? AND cicloActual = ? AND estado = ?
                UNION
                SELECT 'alumno_nuevo_ingreso' AS tabla, NULL AS carnet, correo_alumno, pass 
                FROM alumno_nuevo_ingreso 
                WHERE correo_alumno = ? AND cicloActual = ? AND estado = ?
            ";
        } else {
            // Si es un carnet, solo buscamos en la tabla alumno
            $sql = "
                SELECT 'alumno' AS tabla, carnet, correo_alumno, pass 
                FROM alumno 
                WHERE carnet = ? AND cicloActual = ? AND estado = ?
            ";
        }

        $stmt = $conn->prepare($sql);

        // Enlazar parámetros según si es correo o carnet
        if ($isEmail) {
            $stmt->bind_param("sisisi", $usuario, $anio_actual, $estado_requerido, $usuario, $anio_actual, $estado_requerido);
        } else {
            $stmt->bind_param("sis", $usuario, $anio_actual, $estado_requerido);
        }

        $stmt->execute();
        
        // Almacenar los resultados
        $stmt->store_result();

        // Vincular resultados
        $stmt->bind_result($tabla, $carnet, $correo_alumno, $pass);

        $usuarioEncontrado = false;  // Variable para rastrear si encontramos al usuario
        while ($stmt->fetch()) {
            $usuarioEncontrado = true;

            // Actualizar la contraseña según la tabla (alumno o alumno_nuevo_ingreso)
            if ($tabla == 'alumno') {
                // Actualizar en la tabla alumno, ya sea por correo o carnet
                $updateSql = $isEmail ? "UPDATE alumno SET pass = ? WHERE correo_alumno = ?" : "UPDATE alumno SET pass = ? WHERE carnet = ?";
            } else {
                // Actualizar en la tabla alumno_nuevo_ingreso, ya sea por correo
                $updateSql = "UPDATE alumno_nuevo_ingreso SET pass = ? WHERE correo_alumno = ?";
            }

            // Preparar la actualización de contraseña
            $updateStmt = $conn->prepare($updateSql);
            $hash_password = password_hash($nuevaContrasena, PASSWORD_DEFAULT); // Encriptar la nueva contraseña

            if ($isEmail) {
                $updateStmt->bind_param("ss", $hash_password, $correo_alumno);
            } else {
                $updateStmt->bind_param("ss", $hash_password, $carnet);
            }

            // Ejecutar la actualización de la contraseña
            if ($updateStmt->execute()) {
                // Enviar correo al encargado
                $to = $email_encargado;
                $subject = "Reinicio de Contraseña CAS";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "From: its@cas.edu.gt" . "\r\n";
                $message = "
                Has reiniciado tu contraseña, guárdala para futuros ingresos.
                Nueva contraseña: $nuevaContrasena. 
                ";
                mail($to, $subject, $message, $headers);

                // Enviar correo a Lili (informando sobre la actualización)
                $toLili = "hlopez@cas.edu.gt";
                $subjectLili = "Actualización de Contraseña";
                $messageLili = "
                El alumno con " . ($carnet ? "carnet {$carnet}" : "correo {$correo_alumno}") . " ha reiniciado su contraseña:
                Nueva contraseña: $nuevaContrasena
                ";
                mail($toLili, $subjectLili, $messageLili, $headers);

                // Redirigir a la página de éxito
                header('Location: actualizacion_exitosa.php');
                exit;
            } else {
                // Redirigir a la página de error
                header('Location: actualizacion_error.php');
                exit;
            }
        }

        // Si no se encontró ningún usuario
        if (!$usuarioEncontrado) {
            echo "No se encontró al usuario con los criterios proporcionados.";
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conn->close();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-md rounded px-8 py-6">
                <h2 class="text-2xl font-bold mb-6 text-center">Cambio de Contraseña</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="mb-4">
                        <label for="usuario" class="block text-gray-700 text-sm font-bold mb-2">Usuario/Carnét:</label>
                        <input type="text" id="usuario" name="usuario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingrese número de carnet del estudiante" required>
                    </div>
                    <div class="mb-4">
                        <label for="email-encargado" class="block text-gray-700 text-sm font-bold mb-2">Email encargado llenar formulario:</label>
                        <input type="email" id="email-encargado" name="email-encargado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingresar correo válido del encargado/a" required>
                    </div>
                    <div class="mb-4">
                        <label for="contrasena" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="contrasena" name="contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('contrasena')">Ver</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="repetir-contrasena" class="block text-gray-700 text-sm font-bold mb-2">Repetir Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="repetir-contrasena" name="repetir-contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('repetir-contrasena')">Ver</button>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-red-900 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarContrasena(idInput) {
            var input = document.getElementById(idInput);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-md rounded px-8 py-6">
                <h2 class="text-2xl font-bold mb-6 text-center">Cambio de Contraseña</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="mb-4">
                        <label for="usuario" class="block text-gray-700 text-sm font-bold mb-2">Usuario/Carnét:</label>
                        <input type="text" id="usuario" name="usuario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingrese número de carnet del estudiante" required>
                    </div>
                    <div class="mb-4">
                        <label for="email-encargado" class="block text-gray-700 text-sm font-bold mb-2">Email encargado llenar formulario:</label>
                        <input type="email" id="email-encargado" name="email-encargado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingresar correo válido del encargado/a" required>
                    </div>
                    <div class="mb-4">
                        <label for="contrasena" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="contrasena" name="contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('contrasena')">Ver</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="repetir-contrasena" class="block text-gray-700 text-sm font-bold mb-2">Repetir Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="repetir-contrasena" name="repetir-contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('repetir-contrasena')">Ver</button>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarContrasena(idInput) {
            var input = document.getElementById(idInput);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-md rounded px-8 py-6">
                <h2 class="text-2xl font-bold mb-6 text-center">Cambio de Contraseña</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="mb-4">
                        <label for="usuario" class="block text-gray-700 text-sm font-bold mb-2">Usuario/Carnét:</label>
                        <input type="text" id="usuario" name="usuario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingrese número de carnet del estudiante" required>
                    </div>
                    <div class="mb-4">
                        <label for="email-encargado" class="block text-gray-700 text-sm font-bold mb-2">Email encargado llenar formulario:</label>
                        <input type="email" id="email-encargado" name="email-encargado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingresar correo válido del encargado/a" required>
                    </div>
                    <div class="mb-4">
                        <label for="contrasena" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="contrasena" name="contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('contrasena')">Ver</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="repetir-contrasena" class="block text-gray-700 text-sm font-bold mb-2">Repetir Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="repetir-contrasena" name="repetir-contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('repetir-contrasena')">Ver</button>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-red-900 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarContrasena(idInput) {
            var input = document.getElementById(idInput);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-md rounded px-8 py-6">
                <h2 class="text-2xl font-bold mb-6 text-center">Cambio de Contraseña</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="mb-4">
                        <label for="usuario" class="block text-gray-700 text-sm font-bold mb-2">Usuario/Carnét:</label>
                        <input type="text" id="usuario" name="usuario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingrese número de carnet del estudiante" required>
                    </div>
                    <div class="mb-4">
                        <label for="email-encargado" class="block text-gray-700 text-sm font-bold mb-2">Email encargado llenar formulario:</label>
                        <input type="email" id="email-encargado" name="email-encargado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Por favor ingresar correo válido del encargado/a" required>
                    </div>
                    <div class="mb-4">
                        <label for="contrasena" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="contrasena" name="contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('contrasena')">Ver</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="repetir-contrasena" class="block text-gray-700 text-sm font-bold mb-2">Repetir Contraseña:</label>
                        <div class="relative">
                            <input type="password" id="repetir-contrasena" name="repetir-contrasena" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="mostrarContrasena('repetir-contrasena')">Ver</button>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarContrasena(idInput) {
            var input = document.getElementById(idInput);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>
