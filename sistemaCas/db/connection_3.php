<?php
$host = '127.0.0.1';
$dbname = 'casedu_mac';
$username = 'inscripciones';
$password = "Admin_cas_inscr2025";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

if (!$conn->set_charset("utf8")) {
    die("Error al establecer la codificaci��n UTF-8: " . $conn->error);
}

?>