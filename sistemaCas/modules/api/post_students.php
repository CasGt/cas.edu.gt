<?php
// crear_usuario.php
require '../../db/connection.php'; // Debe exponer $conn = new mysqli(...);

// Opcional pero recomendado: forzar charset
$conn->set_charset("utf8mb4");

$year = date("Y");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Metodo_no_permitido");
}

// 1) Sanitización y validación básica
$nombres   = isset($_POST['new_nombres'])   ? trim($_POST['new_nombres'])   : '';
$apellidos = isset($_POST['new_apellidos']) ? trim($_POST['new_apellidos']) : '';
$email     = isset($_POST['new_email'])     ? trim($_POST['new_email'])     : '';
$pwd_raw   = isset($_POST['new_password'])  ? trim($_POST['new_password'])  : '';

if ($nombres === '' || $apellidos === '' || $email === '' || $pwd_raw === '') {
    exit("campos_incompletos");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("email_invalido");
}

// 2) Hash de contraseña
$password = password_hash($pwd_raw, PASSWORD_DEFAULT);

// 3) Verificar si el correo ya existe (en ambas tablas)
$correoExiste = false;
$sqlCheck1 = "SELECT 1 FROM alumno WHERE correo_alumno = ? LIMIT 1";
$sqlCheck2 = "SELECT 1 FROM alumno_nuevo_ingreso WHERE correo_alumno = ? LIMIT 1";

if ($stmt = $conn->prepare($sqlCheck1)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $correoExiste = $stmt->num_rows > 0;
    $stmt->close();
}
if (!$correoExiste && ($stmt = $conn->prepare($sqlCheck2))) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $correoExiste = $stmt->num_rows > 0;
    $stmt->close();
}

if ($correoExiste) {
    exit("usuario_existente");
}

// 4) Generar codigo_alumno único
function limpiarTexto($s) {
    // Quitar espacios, acentos y dejar solo letras
    $s = trim($s);
    $s = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
    $s = preg_replace('/[^A-Za-z]/', '', $s);
    return $s;
}

$primer_nombre   = explode(' ', $nombres)[0] ?? '';
$primer_apellido = explode(' ', $apellidos)[0] ?? '';
$base = limpiarTexto($primer_nombre) . limpiarTexto($primer_apellido);
if ($base === '') $base = 'ALUMNO';

function codigoDisponible($conn, $codigo) {
    $sql = "SELECT 1 FROM alumno WHERE codigo_alumno = ? LIMIT 1";
    if ($st = $conn->prepare($sql)) {
        $st->bind_param("s", $codigo);
        $st->execute();
        $st->store_result();
        if ($st->num_rows > 0) { $st->close(); return false; }
        $st->close();
    }
    $sql = "SELECT 1 FROM alumno_nuevo_ingreso WHERE codigo_alumno = ? LIMIT 1";
    if ($st = $conn->prepare($sql)) {
        $st->bind_param("s", $codigo);
        $st->execute();
        $st->store_result();
        $ok = $st->num_rows === 0;
        $st->close();
        return $ok;
    }
    return false;
}

$codigo_alumno = '';
$maxIntentos = 10;
for ($i=0; $i<$maxIntentos; $i++) {
    $codigo_numerico = random_int(10000000, 99999999);
    $codigo_temp = $base . $codigo_numerico;
    if (codigoDisponible($conn, $codigo_temp)) {
        $codigo_alumno = $codigo_temp;
        break;
    }
}
if ($codigo_alumno === '') {
    http_response_code(500);
    exit("no_se_pudo_generar_codigo");
}

// 5) Insertar alumno_nuevo_ingreso
$estado_nuevo = "1"; // ajusta según tu semántica (1=activo/pending)
$sql_insert = "INSERT INTO alumno 
    (nombres_alumno, apellidos_alumno, correo_alumno, pass, codigo_alumno, estado, cicloActual)
    VALUES (?, ?, ?, ?, ?, ?, ?)";

if (!($stmt_insert = $conn->prepare($sql_insert))) {
    http_response_code(500);
    exit("error_preparar_insert");
}

$stmt_insert->bind_param(
    "sssssss",
    $nombres,
    $apellidos,
    $email,
    $password,
    $codigo_alumno,
    $estado_nuevo,
    $year
);

if ($stmt_insert->execute()) {
    echo "success";
} else {
    // Si tienes índices únicos en correo/codigo, aquí puede caer por duplicados.
    echo "error_crear_usuario";
}

$stmt_insert->close();
$conn->close();
