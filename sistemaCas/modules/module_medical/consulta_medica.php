<?php
// Inicia la sesión
session_start();
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('medicina');


// Obtén los datos de la URL y valida
$codigo_alumno = filter_input(INPUT_GET, 'codigo_alumno', FILTER_SANITIZE_STRING);
$carnet = filter_input(INPUT_GET, 'carnet', FILTER_SANITIZE_STRING);
$nombre = filter_input(INPUT_GET, 'nombre', FILTER_SANITIZE_STRING);
$grado = filter_input(INPUT_GET, 'grado', FILTER_SANITIZE_STRING);

$userName = $_SESSION['usuario']['user'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Consulta Médica</title>
       <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <?php 
     include '../shared/navbar.php';
    ?>

    <!-- Formulario -->
    <div class="container mx-auto p-8 bg-white rounded-md shadow-md mt-8 max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Formulario de Consulta Médica</h1>
        <form action="../api/put_consulta_medica.php" method="post" class="space-y-4" onsubmit="return validarFormulario()">
            <!-- Código Alumno -->
            <input type="hidden" name="codigo_alumno" value="<?= htmlspecialchars($codigo_alumno) ?>">

            <!-- Carnet -->
            <div class="mb-4">
                <label for="carnet" class="block text-sm font-medium text-gray-600">Carnet:</label>
                <input type="text" id="carnet" name="carnet" value="<?= htmlspecialchars($carnet) ?>" class="form-input w-full border border-gray" readonly>
            </div>

            <!-- Nombre Completo -->
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-600">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" class="form-input w-full border border-gray" readonly>
            </div>

            <!-- Grado -->
            <div class="mb-4">
                <label for="grado" class="block text-sm font-medium text-gray-600">Grado:</label>
                <input type="text" id="grado" name="grado" value="<?= htmlspecialchars($grado) ?>" class="form-input w-full border border-gray" readonly>
            </div>

            <!-- Síntomas -->
            <div class="mb-4">
                <label for="sintomas" class="block text-sm font-medium text-gray-600">Síntomas:</label>
                <textarea id="sintomas" name="sintomas" rows="3" class="form-input w-full border border-gray"></textarea>
            </div>

            <!-- Tratamiento -->
            <div class="mb-4">
                <label for="tratamiento" class="block text-sm font-medium text-gray-600">Tratamiento:</label>
                <textarea id="tratamiento" name="tratamiento" rows="3" class="form-input w-full border border-gray"></textarea>
            </div>

            <!-- Fecha de Consulta -->
            <div class="mb-4">
                <label for="fecha_consulta" class="block text-sm font-medium text-gray-600">Fecha de Consulta:</label>
                <input type="datetime-local" id="fecha_consulta" name="fecha_consulta" class="form-input w-full border border-gray">
            </div>

            <!-- Relevancia -->
            <div class="mb-4">
                <label for="relevancia" class="block text-sm font-medium text-gray-600">Relevancia:</label>
                <select id="relevancia" name="relevancia" class="form-select w-full border border-gray">
                    <option value="atencion_primaria">Atención Primaria</option>
                    <option value="importante">Importante</option>
                    <option value="seguimiento">Seguimiento de Tratamiento</option>
                </select>
            </div>

            <!-- Observaciones -->
            <div class="mb-4">
                <label for="observacion" class="block text-sm font-medium text-gray-600">Observaciones:</label>
                <textarea id="observacion" name="observacion" rows="3" class="form-input w-full border border-gray"></textarea>
            </div>

            <!-- Botón Guardar Cambios -->
            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <script>
        function validarFormulario() {
            const requiredFields = ['sintomas', 'tratamiento', 'fecha_consulta', 'relevancia', 'observacion'];
            for (let field of requiredFields) {
                if (document.getElementById(field).value === '') {
                    alert('Error: Todos los campos son obligatorios. Por favor, completa todos los datos.');
                    return false;
                }
            }
            return true;
        }
    </script>
</body>
</html>
