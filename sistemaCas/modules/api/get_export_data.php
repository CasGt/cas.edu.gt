<?php 
include '../../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['seleccion']) && !empty($_POST['seleccion'])) { 
        $seleccion = $_POST['seleccion'];

        $selectWithAlias = array_map(function ($campo) {
            return "$campo AS " . str_replace('.', '_', $campo);
        }, $seleccion);
        $select = implode(', ', $selectWithAlias);
        mysqli_set_charset($conn, "utf8");
        $anioSeleccionado = $_POST['anio'];
        $anioSeleccionado == date('Y');
               $sql = "SELECT 
                            $select
                        FROM 
                            alumno AS a
                        LEFT JOIN 
                            madre AS m ON m.codigo_alumno = a.codigo_alumno 
                        LEFT JOIN 
                            padre AS p ON p.codigo_alumno = a.codigo_alumno 
                        LEFT JOIN 
                            terceros AS t ON t.codigo_alumno = a.codigo_alumno 
                        WHERE 
                            a.cicloActual = $anioSeleccionado AND a.estado = 1
                        GROUP BY 
                            a.codigo_alumno
                        HAVING 
                            MAX(a.updated_at)";
                     file_put_contents('./debug.log', "Consulta ejecutada: $sql\n", FILE_APPEND);
        $consultaGuardada = $sql;
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $fecha_actual = date('Y-m-d_H-i-s'); 
            $nombre_archivo = "archivo_$fecha_actual.csv";
            header("Content-Type: text/csv; charset=UTF-8");
            header("Content-Disposition: attachment; filename=$nombre_archivo"); 
            $output = fopen('php://output', 'w');
            $headerPrinted = false;
            while ($row = mysqli_fetch_assoc($result)) {
                if (!$headerPrinted) {
                    fputcsv($output, array_map('utf8_decode', array_keys($row)));
                    $headerPrinted = true;
                }
                fputcsv($output, array_map('utf8_decode', $row)); 
            }
            mysqli_free_result($result);
            mysqli_close($conn);

        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($conn);
        }
    } else {
        echo "No se ha seleccionado ningún campo.";
    }
} else {
    echo "Acceso no permitido.";
}
