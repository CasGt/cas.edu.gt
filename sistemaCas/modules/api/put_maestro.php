<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../../db/connection.php';

    $id_maestro = $_GET['id'];
    $codigoEmpleado = $_POST['codigoEmpleado'];
    $usuarioMaestro = $_POST['usuarioMaestro'];
    $nombresMaestro = $_POST['nombresMaestro'];
    $apellidosMaestro = $_POST['apellidosMaestro'];
    $nivelPertenece = $_POST['nivelPertenece'];
    $puesto = $_POST['puesto'];
    $extensionTel = $_POST['extensionTel'];
    $emailMaestro = $_POST['emailMaestro'];
    $comentarios = $_POST['comentarios'];
    $estado = $_POST['estado'];

    $sql = "UPDATE maestros SET codigoEmpleado = ?, usuarioMaestro = ?, nombresMaestro = ?, apellidosMaestro = ?, nivelPertence = ?, puesto = ?, extencionTel = ?, emailMaestro = ?, comentarios = ?, estado = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("isssssisssi", $codigoEmpleado, $usuarioMaestro, $nombresMaestro, $apellidosMaestro, $nivelPertenece, $puesto, $extensionTel, $emailMaestro, $comentarios, $estado, $id_maestro);

    if ($stmt->execute()) {
        echo "<script>alert('Información de maestro/a actualizada correctamente.');window.location.href = '../module_user-system/view_students.php';</script>";
    } else {
        echo "Error al actualizar los datos del maestro: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {

    echo "No se recibieron datos del formulario.";
}