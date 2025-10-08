<?php
require_once '../../db/connection.php';

$sql = "SELECT id, idCategoria, codigoEmpleado, usuarioMaestro, nombresMaestro, apellidosMaestro, nivelPertenece, puesto, extensionTel, emailMaestro, comentarios, estado FROM maestros";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

echo json_encode($data);