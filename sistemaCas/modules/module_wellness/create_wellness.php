<?php 
session_start();
include '../../db/connection.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';

validateAccess('usuarios');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include '../shared/navbar.php';
  ?>

    <!-- Contenido de la página -->
    <div class="container mx-auto p-4">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Nuevo wellness</h2>
            <form action="../controller/procesar_formulario.php" method="post">
                <div class="mb-4">
                    <label for="carnet_docente" class="block text-gray-700 font-semibold">Docente</label>
                    <?php

                    // Consultar la base de datos para obtener los nombres de los maestros
                    $sql2 = "SELECT m.* 
                    FROM informacion_estudiantes.maestros m 
                    WHERE m.estado = 1
                    ORDER BY m.nombresMaestro, m.apellidosMaestro";
                    $result2 = $conn->query($sql2);

                    // Verificar si hay resultados
                    if ($result2->num_rows > 0) {
                        // Imprimir los options del select con los nombres de los maestros
                        echo "<select name='carnet_docente' id='carnet_docente' class='mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200'>";
                        while ($row2 = $result2->fetch_assoc()) {
                            echo "<option value='" . $row2["id"] . "'>" . $row2["nombresMaestro"] . " " . $row2["apellidosMaestro"] . "</option>";
                        }
                        echo "</select>";
                    } else {
                        echo "No se encontraron maestros";
                    }

                    // Cerrar la conexión
                    $conn->close();
                    ?>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold">Nombre wellness</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label for="lugar" class="block text-gray-700 font-semibold">Ubicación wellness</label>
                    <input type="text" name="lugar" id="lugar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label for="limite" class="block text-gray-700 font-semibold">Límite</label>
                    <input type="number" name="limite" id="limite" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div class="mb-4">
                    <p class="text-gray-700 font-semibold mb-2">Periodos</p>
                    <!-- Campos de P -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="p<?php echo $i ?>" value="1" class="form-checkbox text-blue-500 focus:ring-blue-400 h-5 w-5">
                                <span class="ml-2"><?php echo $i ?></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 font-semibold mb-2">Grados</p>
                    <!-- Campos de G -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="g<?php echo $i ?>" value="1" class="form-checkbox text-blue-500 focus:ring-blue-400 h-5 w-5">
                                <span class="ml-2"><?php echo $i ?></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>