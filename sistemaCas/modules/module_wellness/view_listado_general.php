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
    <title>Lista de Inscritos</title>
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
            <h2 class="text-2xl font-semibold mb-4">Datos de inscripciones</h2>
            <?php
            $sql = "SELECT di.correo_alumno, 
                            CONCAT(UPPER(SUBSTRING(ie.nombres_alumno, 1, 1)), LOWER(SUBSTRING(ie.nombres_alumno, 2)), ' ', UPPER(SUBSTRING(ie.apellidos_alumno, 1, 1)), LOWER(SUBSTRING(ie.apellidos_alumno, 2))) AS nombre_alumno,
                            CASE 
                                WHEN ie.grado_alumno BETWEEN 0 AND 9 THEN CONCAT('0', ie.grado_alumno)
                                ELSE ie.grado_alumno
                            END AS grado_alumno,
                            dw_p1.nombre_wellness AS wellness_p1,
                            dw_p2.nombre_wellness AS wellness_p2,
                            dw_p3.nombre_wellness AS wellness_p3,
                            dw_p4.nombre_wellness AS wellness_p4
                    FROM datos_inscritos di
                    LEFT JOIN informacion_wellness dw_p1 ON di.id_inscripcion_p1 = dw_p1.id_wellness
                    LEFT JOIN informacion_wellness dw_p2 ON di.id_inscripcion_p2 = dw_p2.id_wellness
                    LEFT JOIN informacion_wellness dw_p3 ON di.id_inscripcion_p3 = dw_p3.id_wellness
                    LEFT JOIN informacion_wellness dw_p4 ON di.id_inscripcion_p4 = dw_p4.id_wellness
                    INNER JOIN informacion_estudiantes.alumno ie ON di.correo_alumno = ie.correo_alumno
                    WHERE ie.estado = 1 and di.estado = 1";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table id='tablaInscritos' class='display min-w-full divide-y divide-gray-200'><thead><tr><th>Correo del Alumno</th><th>Nombre del Alumno</th><th>Grado</th><th>Wellness P1</th><th>Wellness P2</th><th>Wellness P3</th><th>Wellness P4</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["correo_alumno"] . "</td><td>" . $row["nombre_alumno"] . "</td><td>" . $row["grado_alumno"] . "</td><td>" . $row["wellness_p1"] . "</td><td>" . $row["wellness_p2"] . "</td><td>" . $row["wellness_p3"] . "</td><td>" . $row["wellness_p4"] . "</td></tr>";
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
            $('#tablaInscritos').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
    </script>
</body>

</html>
