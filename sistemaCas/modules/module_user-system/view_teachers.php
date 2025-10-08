<?php
session_start();
require '../../db/connection.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('administracion');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de maestros</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
     <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/2.0.1/i18n/es-ES.json"></script>
</head>

<body class="bg-gray-100">
       <?php
 include '../shared/navbar.php';
    ?>

   <div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Datos de Maestros</h2>
        <div>
            <a href="./create_teachers.php" class="bg-red-900 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">
                Agregar
            </a>
        </div>
    </div>
    <div class="tabla-container bg-white p-6 rounded-md shadow-md overflow-x-auto">
        <?php
        $sql = "SELECT id, idCategoria, codigoEmpleado, usuarioMaestro, nombresMaestro, apellidosMaestro, nivelPertence, puesto, extencionTel, emailMaestro, comentarios, estado FROM maestros WHERE estado = 1";
        $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<div class='overflow-x-auto'><table id='tabla' class='display w-full whitespace-nowrap min-w-full divide-y divide-gray-200'><thead><tr><th>ID</th><th>ID Categoría</th><th>Código Empleado</th><th>Usuario Maestro</th><th>Nombres Maestro</th><th>Apellidos Maestro</th><th>Nivel Pertenece</th><th>Puesto</th><th>Extensión Tel</th><th>Email Maestro</th><th>Comentarios</th><th>Estado</th><th>Editar</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id"] . "</td><td>" . $row["idCategoria"] . "</td><td>" . $row["codigoEmpleado"] . "</td><td>" . $row["usuarioMaestro"] . "</td><td>" . $row["nombresMaestro"] . "</td><td>" . $row["apellidosMaestro"] . "</td><td>" . $row["nivelPertence"] . "</td><td>" . $row["puesto"] . "</td><td>" . $row["extencionTel"] . "</td><td>" . $row["emailMaestro"] . "</td><td>" . $row["comentarios"] . "</td><td>" . $row["estado"] . "</td><td><form action='./edit_teachers.php' method='GET'><input type='hidden' name='id' value='" . $row["id"] . "'><button type='submit' class='editar-btn bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'>Editar</button></form></td></tr>";
                }
                echo "</tbody></table></div>";
            } else {
                echo "No se encontraron resultados";
            }
            $conn->close();
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
                "pageLength": 13
            });
        });
    </script>
</body>
</html>