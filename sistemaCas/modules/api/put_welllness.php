<?php
include '../../db/connection_2.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = $_POST['id'];

        $nombre_wellness = $_POST['name'];
        $lugar = $_POST['lugar'];
        $limite = $_POST['limite'];
        $p1 = isset($_POST['p1']) ? 1 : 0;
        $p2 = isset($_POST['p2']) ? 1 : 0;
        $p3 = isset($_POST['p3']) ? 1 : 0;
        $p4 = isset($_POST['p4']) ? 1 : 0;
        $g1 = isset($_POST['g1']) ? 1 : 0;
        $g2 = isset($_POST['g2']) ? 1 : 0;
        $g3 = isset($_POST['g3']) ? 1 : 0;
        $g4 = isset($_POST['g4']) ? 1 : 0;
        $g5 = isset($_POST['g5']) ? 1 : 0;
        $g6 = isset($_POST['g6']) ? 1 : 0;
        $g7 = isset($_POST['g7']) ? 1 : 0;
        $g8 = isset($_POST['g8']) ? 1 : 0;
        $g9 = isset($_POST['g9']) ? 1 : 0;
        $g10 = isset($_POST['g10']) ? 1 : 0;
        $g11 = isset($_POST['g11']) ? 1 : 0;
        $g12 = isset($_POST['g12']) ? 1 : 0;

        $estado = isset($_POST['estado']) ? 1 : 0;

        $sql = "UPDATE informacion_wellness SET nombre_wellness='$nombre_wellness', lugar='$lugar', limite=$limite, p1=$p1, p2=$p2, p3=$p3, p4=$p4, g1=$g1, g2=$g2, g3=$g3, g4=$g4, g5=$g5, g6=$g6, g7=$g7, g8=$g8, g9=$g9, g10=$g10, g11=$g11, g12=$g12, estado=$estado WHERE id_wellness=$id";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registro actualizado correctamente.');window.location.href = '../module_wellness/view_wellness.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el registro: " . $conn->error . "');window.location.href = '../module_wellness/view_wellness.php';</script>";
        }
    } else {
        echo "ID inválido";
    }

    $conn->close();
} else {
    echo "Método no permitido";
}
?>
