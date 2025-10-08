<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir archivo de conexión a la base de datos
    require_once '../../db/connection.php';

    // Recuperar los datos del formulario

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
    $sql = "INSERT INTO maestros (codigoEmpleado, usuarioMaestro, nombresMaestro, apellidosMaestro, nivelPertence, puesto, extencionTel, emailMaestro, comentarios, estado) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("isssssissi", $codigoEmpleado, $usuarioMaestro, $nombresMaestro, $apellidosMaestro, $nivelPertenece, $puesto, $extensionTel, $emailMaestro, $comentarios, $estado);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Maestro registrado correctamente.');window.location.href = '../module_user-system/view_students.php';</script>";
        // Redireccionar a una página de éxito o mostrar un mensaje

    } else {
        echo "<script>alert('Error al registrar el maestro:' . $conn->error);window.location.href = '../module_user-system/view_students.php';</script>";
        // Mostrar un mensaje de error si la consulta falla
    }

    // Cerrar la conexión
    $stmt->close();
    $conn1->close();
} else {
    // Si no se ha enviado el formulario, redireccionar a una página de error o mostrar un mensaje
    echo "<script>alert('No se recibieron datos del formulario');window.location.href = '../module_user-system/create_maestro.php';</script>";
}