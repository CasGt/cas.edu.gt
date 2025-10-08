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
    <?php include '../shared/navbar.php'; ?>
  ?>

    <div class="container mx-auto p-4">
        <div class="tabla-container bg-white p-6 rounded-md shadow-md overflow-x-auto">
            <h2 class="text-2xl font-semibold mb-4">Datos de Wellness</h2>
            <?php
            $sql = "SELECT 
                        c.id_wellness,
                        iw.nombre_wellness AS NOMBRE,
                        c.cupos_disponibles_periodo_1 AS CUPOS_P1,
                        c.cupos_disponibles_periodo_2 AS CUPOS_P2,
                        c.cupos_disponibles_periodo_3 AS CUPOS_P3,
                        c.cupos_disponibles_periodo_4 AS CUPOS_P4
                    FROM informacion_wellness as iw
                    INNER JOIN cupos_disponibles_wellness as c 
                    ON iw.id_wellness = c.id_wellness";

            $result_cupos = $conn->query($sql);

            if ($result_cupos->num_rows > 0) {
                echo "<table id='cupos_table' class='display min-w-full divide-y divide-gray-200'><thead><tr><th>ID Wellness</th><th>Nombre</th><th>Cupos P1</th><th>Cupos P2</th><th>Cupos P3</th><th>Cupos P4</th></tr></thead><tbody>";
                while ($row = $result_cupos->fetch_assoc()) {
                    echo "<tr><td>" . $row["id_wellness"] . "</td><td>" . $row["NOMBRE"] . "</td><td>" . $row["CUPOS_P1"] . "</td><td>" . $row["CUPOS_P2"] . "</td><td>" . $row["CUPOS_P3"] . "</td><td>" . $row["CUPOS_P4"] . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No hay datos disponibles.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cupos_table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
    </script>
</body>

</html>

