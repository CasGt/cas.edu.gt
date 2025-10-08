<?php
include '../../db/connection_2.php';

// Recuperar los datos del formulario
$carnet_docente = $_POST['carnet_docente'];
$nombre_wellness = $_POST['name'];
$lugar_wellness = $_POST['lugar'];
$limite = $_POST['limite'];

// Inicializar los valores de los checkboxes
$p_values = array();
$g_values = array();

// Recuperar los valores de los checkboxes de P
for ($i = 1; $i <= 4; $i++) {
    $p_values[$i] = isset($_POST['p' . $i]) ? 1 : 0;
}

// Recuperar los valores de los checkboxes de G
for ($i = 1; $i <= 12; $i++) {
    $g_values[$i] = isset($_POST['g' . $i]) ? 1 : 0;
}

// Insertar los datos en la base de datos
$sql = "INSERT INTO informacion_wellness (id_docente, nombre_wellness, lugar, limite, p1, p2, p3, p4, g1, g2, g3, g4, g5, g6, g7, g8, g9, g10, g11, g12, estado)
VALUES ('$carnet_docente', '$nombre_wellness', '$lugar_wellness', '$limite', {$p_values[1]}, {$p_values[2]}, {$p_values[3]}, {$p_values[4]}, 
{$g_values[1]}, {$g_values[2]}, {$g_values[3]}, {$g_values[4]}, {$g_values[5]}, {$g_values[6]}, {$g_values[7]}, {$g_values[8]}, {$g_values[9]}, 
{$g_values[10]}, {$g_values[11]}, {$g_values[12]}, 1)";

if ($conn->query($sql) === TRUE) {
    // Mensaje de éxito
    echo "<script>alert('Datos guardados correctamente'); window.location.href = '../module_wellness/create_wellness.php';</script>";
} else {
    // Mensaje de error
    echo "<script>alert('Error al guardar los datos: " . $conn->error . "');</script>";
}

// Cerrar la conexión
$conn->close();
