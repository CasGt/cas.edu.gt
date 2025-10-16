<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reingreso | CAS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
      <div class="flex justify-center mb-6">
        <img src="./assets/img/logo_cas_red.webp" alt="Logo CAS" class="w-16 h-auto">
      </div>
      <h2 class="text-2xl font-bold text-center mb-6">Proceso de inscripción</h2>

      <form id="loginForm" action="validar.php" method="POST" class="space-y-6">
        <div>
          <label for="carnet_or_email" class="block font-semibold">Carnet o Correo del Alumno:</label>
          <input type="text" id="carnet_or_email" name="carnet_or_email"
                 class="w-full p-2 border border-gray-300 rounded"
                 placeholder="Ingrese su carnet o correo" required>
        </div>
        <div>
          <label for="password" class="block font-semibold">Contraseña:</label>
          <input type="password" id="password" name="password"
                 class="w-full p-2 border border-gray-300 rounded"
                 placeholder="Ingrese su contraseña" required>
        </div>
        <button type="submit" class="w-full bg-red-900 text-white p-2 rounded hover:bg-red-600">Iniciar Sesión</button>
      </form>

      <?php
      if (isset($_GET['error'])) {
        echo '<p id="errorMessage" class="text-red-500 mt-4 text-center">'
           . htmlspecialchars($_GET['error'])
           . '</p>';
      }
      ?>
    </div>
  </div>

  <script type="module" src="./assets/js/reingreso.js"></script>
</body>
</html>
