<?php
session_start();
require '../../db/connection.php';
require '../shared/role_validation.php';
require '../shared/alerts.php';
header('Content-Type: text/html; charset=utf-8');

validateAccess('estudiantes');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Estudiantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../src/css/styles.css">
</head>
<body class="bg-gray-100">
    <?php include '../shared/navbar.php'; ?>
      <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
      <div class="flex justify-center mb-6">
        <img src="../../src/images/logo_cas_rojo.png" alt="Logo CAS" class="w-16 h-auto">
      </div>
      <h2 class="text-2xl font-bold text-center mb-6">Crear usuario para estudiante</h2>

      <form id="registerForm" method="POST" class="space-y-6">
        <div>
          <label for="new_nombres" class="block font-semibold">Nombres:</label>
          <input type="text" id="new_nombres" name="new_nombres"
                 class="w-full p-2 border border-gray-300 rounded"
                 placeholder="Ingrese sus nombres" required>
        </div>
        <div>
          <label for="new_apellidos" class="block font-semibold">Apellidos:</label>
          <input type="text" id="new_apellidos" name="new_apellidos"
                 class="w-full p-2 border border-gray-300 rounded"
                 placeholder="Ingrese sus apellidos" required>
        </div>
        <div>
          <label for="new_email" class="block font-semibold">Correo Electrónico:</label>
          <input type="email" id="new_email" name="new_email"
                 class="w-full p-2 border border-gray-300 rounded"
                 placeholder="Ingrese su correo" required>
        </div>
        <div>
          <label for="new_password" class="block font-semibold">Contraseña:</label>
          <input type="password" id="new_password" name="new_password"
                 class="w-full p-2 border border-gray-300 rounded"
                 placeholder="Ingrese su contraseña" required>
        </div>
        <button type="button" id="createUserBtn" class="w-full bg-red-900 text-white p-2 rounded hover:bg-red-600">
          Crear Usuario
        </button>
      </form>

    </div>
  </div>
    <?php include './modals/modal_edit_students.php'; ?>
  <script type="module" src="../../src/js/app.js"></script>
</body>
</html>
