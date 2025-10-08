<?php
session_start();
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir archivo de conexión a la base de datos
    require_once '../../db/connection.php';

    // Recuperar los datos del formulario
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

    // Preparar la consulta SQL
    $sql = "UPDATE maestros SET codigoEmpleado = ?, usuarioMaestro = ?, nombresMaestro = ?, apellidosMaestro = ?, nivelPertence = ?, puesto = ?, extencionTel = ?, emailMaestro = ?, comentarios = ?, estado = ? WHERE id = ?";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    // Vincular los parámetros
    $stmt->bind_param("isssssisssi", $codigoEmpleado, $usuarioMaestro, $nombresMaestro, $apellidosMaestro, $nivelPertenece, $puesto, $extensionTel, $emailMaestro, $comentarios, $estado, $id_maestro);


    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Información de maestro/a actualizada correctamente.');window.location.href = '../module_user-system/view_students.php';</script>";
    } else {
        // Mostrar un mensaje de error si la consulta falla
        echo "Error al actualizar los datos del maestro: " . $conn->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si no se ha enviado el formulario, redireccionar a una página de error o mostrar un mensaje
    echo "No se recibieron datos del formulario.";
}