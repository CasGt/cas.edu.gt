<?php
$host = '127.0.0.1';
$dbname = 'wellness';
$username = 'cas_wellness';
$password = "c*s2023";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

if (!$conn->set_charset("utf8")) {
    die("Error al establecer la codificacioón UTF-8: " . $conn->error);
}

?>
