<?php 
session_start();
include '../../db/connection_2.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';

validateAccess('usuarios');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness existentes</title>
      <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/2.0.1/i18n/es-ES.json"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include '../shared/navbar.php';
  ?>

    <div class="container mx-auto p-4">
        <div class="tabla-container bg-white p-6 rounded-md shadow-md overflow-x-auto">
            <h2 class="text-2xl font-semibold mb-4">Datos de Wellness</h2>
            <?php
            $sql = "SELECT CONCAT(m.nombresMaestro, ' ', m.apellidosMaestro) AS docente,
                    iw.id_wellness AS id_wellness,
                    iw.id_docente AS id_docente,
                    iw.nombre_wellness AS nombre_wellness,
                    iw.lugar AS lugar,
                    iw.limite AS limite,
                    iw.estado AS estado,
                    CONCAT_WS(',', IF(iw.p1 = 1, '1', NULL), IF(iw.p2 = 1, '2', NULL), IF(iw.p3 = 1, '3', NULL), IF(iw.p4 = 1, '4', NULL)) AS periodos,
                    CONCAT_WS(',', IF(iw.g1 = 1, '1', NULL), IF(iw.g2 = 1, '2', NULL), IF(iw.g3 = 1, '3', NULL), IF(iw.g4 = 1, '4', NULL), IF(iw.g5 = 1, '5', NULL), IF(iw.g6 = 1, '6', NULL), IF(iw.g7 = 1, '7', NULL), IF(iw.g8 = 1, '8', NULL), IF(iw.g9 = 1, '9', NULL), IF(iw.g10 = 1, '10', NULL), IF(iw.g11 = 1, '11', NULL), IF(iw.g12 = 1, '12', NULL)) AS grados 
                    FROM wellness.informacion_wellness AS iw 
                    INNER JOIN informacion_estudiantes.maestros AS m ON iw.id_docente = m.id 
                    WHERE iw.estado = 1;";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table id='tabla' class='display min-w-full divide-y divide-gray-200'><thead><tr><th>ID Wellness</th><th>ID Docente</th><th>Nombre Wellness</th><th>Lugar</th><th>Límite</th><th>Periodo</th><th>Grado</th><th>Editar</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id_wellness"] . "</td><td>" . $row["docente"] . "</td><td>" . $row["nombre_wellness"] . "</td><td>" . $row["lugar"] . 
                    "</td><td>" . $row["limite"] . "</td><td>" . $row["periodos"] . "</td><td>" . $row["grados"] . "</td><td><button class='editar-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline' data-id='" . $row["id_wellness"] . "'>Editar</button></td></tr>";
                }
                echo "</tbody></table>";
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
            $('#tabla').DataTable();
            $('.editar-btn').click(function() {
                var id = $(this).data('id');
                window.location.href = 'edit_wellness.php?id=' + id;
            });
        });
    </script>
</body>
<script>
    $('#tabla').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });
</script>

</html>
