<?php
require './conexion.php';
session_start();

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict'); 
ini_set('session.cookie_secure', 1); // habilitar si usas HTTPS

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header('Location: index.php'); exit();
}

$input = trim($_POST['carnet_or_email'] ?? '');
$password = $_POST['password'] ?? '';

if ($input === '' || $password === '') {
  header("Location: index.php?error=" . urlencode("Usuario o contraseña incorrectos"));
  exit();
}

$cicloActual = date('Y'); 

// Detectar email vs carnet
$isEmail = filter_var($input, FILTER_VALIDATE_EMAIL);
$sql = "SELECT codigo_alumno, correo_alumno, pass, carnet
        FROM alumno
        WHERE ".($isEmail ? "LOWER(correo_alumno)=LOWER(?)" : "carnet=?")."
          AND estado=1
          AND cicloActual=?";

$stmt = $conn->prepare($sql);
if (!$stmt) { error_log($conn->error); fail(); }

if ($isEmail) {
  $stmt->bind_param("si", $input, $cicloActual);
} else {
  $carnet = preg_replace('/\D/', '', $input);
  $stmt->bind_param("si", $carnet, $cicloActual);
}

$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  if (password_verify($password, $row['pass'])) {

    if (password_needs_rehash($row['pass'], PASSWORD_DEFAULT)) {
      $newHash = password_hash($password, PASSWORD_DEFAULT);
      $upd = $conn->prepare("UPDATE alumno SET pass=? WHERE codigo_alumno=?");
      if ($upd) { $upd->bind_param("ss", $newHash, $row['codigo_alumno']); $upd->execute(); }
    }

    session_regenerate_id(true);
    $_SESSION['id_alumno'] = $row['codigo_alumno'];
    $_SESSION['carnet']    = $row['carnet'];

    header('Location: formulario.php'); exit();
  }
}

fail();

function fail() {
  $msg = urlencode("Usuario o contraseña incorrectos");
  header("Location: index.php?error=$msg"); exit();
}
