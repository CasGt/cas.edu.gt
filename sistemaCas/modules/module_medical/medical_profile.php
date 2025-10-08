<?php
session_start();
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('medicina');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Médico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-center">


<?php

 include '../shared/navbar.php';
   
$codigo_alumno = filter_input(INPUT_GET, 'codigo_alumno', FILTER_SANITIZE_STRING);
$carnet = filter_input(INPUT_GET, 'carnet', FILTER_SANITIZE_STRING);
$nombres = filter_input(INPUT_GET, 'nombres', FILTER_SANITIZE_STRING);
$apellidos = filter_input(INPUT_GET, 'apellidos', FILTER_SANITIZE_STRING);
$grado = filter_input(INPUT_GET, 'grado', FILTER_SANITIZE_STRING);
$fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING);
$cicloActual =  filter_input(INPUT_GET, 'ciclo_actual', FILTER_SANITIZE_STRING);


?>

<div class="max-w-5xl mx-auto bg-white border-2 border-gray-300 rounded-lg p-8 shadow-lg mt-8">
    <div class="mb-4 flex justify-end">
        <a href="./nursing.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Regresar
        </a>
        
       <a href="./consulta_medica.php?codigo_alumno=<?= urlencode($codigo_alumno) ?>&carnet=<?= urlencode($carnet) ?>&nombre=<?= urlencode($nombres . ' ' . $apellidos) ?>&grado=<?= urlencode($grado) ?>" 
   class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
    Registrar consulta
</a>

    </div>
    <h1 class="text-3xl font-bold mb-6 text-red-800">Datos del Estudiante</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div><h2 class="font-bold">Carnet:</h2><p><?= htmlspecialchars($carnet) ?></p></div>
        <div><h2 class="font-bold">Nombres:</h2><p><?= htmlspecialchars($nombres) ?></p></div>
        <div><h2 class="font-bold">Apellidos:</h2><p><?= htmlspecialchars($apellidos) ?></p></div>
        <div><h2 class="font-bold">Grado:</h2><p><?= htmlspecialchars($grado) ?></p></div>
        <div><h2 class="font-bold">Ciclo Actual:</h2><p><?= htmlspecialchars($cicloActual) ?></p></div>
        <div><h2 class="font-bold">Fecha de Actualización:</h2><p><?= htmlspecialchars($fecha) ?></p></div>
    </div>

    <div id="medical-data" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tablas dinámicas -->
        <div id="alergias-container"></div>
        <div id="enfermedades-container"></div>
        <div id="medicamento-diario-container"></div>
        <div id="otras-vacunas-container"></div>
        <div id="medicamentos-container"></div>
        <div id="vacunas-container"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const codigoAlumno = "<?= $codigo_alumno ?>";
    const cicloActual = "<?= $_GET['ciclo_actual'] ?>";

    const apiUrl = `../api/get_medical_history.php?codigo_alumno=${codigoAlumno}&ciclo_actual=${cicloActual}`;
    
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTable(data.data.alergias, "Alergias", "alergias-container");
                renderTable(data.data.enfermedades, "Enfermedades", "enfermedades-container");
                renderTable(data.data.medicamento_diario, "Medicamento Diario", "medicamento-diario-container");
                renderTable(data.data.otras_vacunas, "Otras Vacunas", "otras-vacunas-container");
                renderTable(data.data.medicamentos, "Medicamentos", "medicamentos-container");
                renderTable(data.data.vacunas, "Vacunas", "vacunas-container");
            } else {
                alert(data.message || "Error al cargar los datos.");
            }
        })
        .catch(error => console.error("Error:", error));

        const renderTable = (items, title, containerId) => {
    const container = document.getElementById(containerId);
    const table = `
        <div>
            <table class="table-auto w-full border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="text-sm py-1">${title}</th>
                    </tr>
                </thead>
                <tbody>
                    ${items.length > 0
                        ? items
                              .map(item => `<tr><td class="border px-2 py-1">${item}</td></tr>`)
                              .join("")
                        : `<tr><td class="border px-2 py-1">Ninguno</td></tr>`}
                </tbody>
            </table>
        </div>`;
    container.innerHTML = table;
};

});

</script>

</body>
</html>
