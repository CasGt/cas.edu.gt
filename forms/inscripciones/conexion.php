<?php
init_set('display_errors', 1);
error_reporting(E_ALL);
// Conexión a la base de datos
$host = '127.0.0.1:3306';
$dbname = 'u185752343_informacion';
$username = 'u185752343_admin_cas_info';
$password = 'Admin_cas_inscr2025*';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexi��n fallida: " . $conn->connect_error);
}

if (!$conn->set_charset("utf8mb4")) {
    die("Error al configurar la codificaci��n UTF-8: " . $conn->error);
}
?>
