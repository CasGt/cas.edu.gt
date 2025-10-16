<?php
include 'log.php';
session_start();
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('medicina');

require '../../src/phpMailer/Exception.php';
require '../../src/phpMailer/PHPMailer.php';
require '../../src/phpMailer/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$codigo_alumno = filter_input(INPUT_POST, 'codigo_alumno', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);
$grado = filter_input(INPUT_POST, 'grado', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);
$fecha_consulta_raw = filter_input(INPUT_POST, 'fecha_consulta', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);
$observacion = filter_input(INPUT_POST, 'observacion', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);
$sintomas = filter_input(INPUT_POST, 'sintomas', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);
$tratamiento = filter_input(INPUT_POST, 'tratamiento', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);
$relevancia = filter_input(INPUT_POST, 'relevancia', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_CALLBACK, ['options' => 'htmlspecialchars']);

$fecha_consulta = date('Y-m-d H:i:s', strtotime($fecha_consulta_raw));

include '../../db/connection.php';



try {

    $consulta = $conn->prepare("
        INSERT INTO historial_asistencia_medica 
        (codigo_alumno, fecha_asistencia, observacion, sintomas, tratamiento, relevancia) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$consulta) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $consulta->bind_param("ssssss", $codigo_alumno, $fecha_consulta, $observacion, $sintomas, $tratamiento, $relevancia);

    if (!$consulta->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $consulta->error);
    }

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'casnursing@cas.edu.gt';
    $mail->Password = 'yumkftpqwwncphkq';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('casnursing@cas.edu.gt', 'Enfermeria CAS');

    if (in_array($grado, ['Kindergarten', 'Pre-kinder1', 'Pre-kinder2', 'G01', 'G02', 'G03', 'G04', 'G05'])) {
        $mail->addAddress('salvarez@cas.edu.gt', 'Asistencia Primaria');
        
    } else {
        $mail->addAddress('iortiz@cas.edu.gt', 'Asistencia MS-HS');
      
    }

    $mail->isHTML(true);
    $mail->Subject = 'Reporte de enfermeria: ' . $nombre;
    $mail->Body = "
        <p>Se ha registrado una nueva consulta médica.</p>
        <p><strong>Detalles:</strong></p>
        <ul>
            <li><strong>Nombre:</strong> $nombre</li>
            <li><strong>Fecha de Consulta:</strong> $fecha_consulta</li>
            <li><strong>Observación:</strong> $observacion</li>
            <li><strong>Síntomas:</strong> $sintomas</li>
            <li><strong>Tratamiento:</strong> $tratamiento</li>
        </ul>
    ";

    $mail->send();

    echo "<script>
        alert('Registro y envío de información exitoso.');
        window.location.href = '../module_medical/nursing.php';
    </script>";
} catch (Exception $e) {
    echo "<script>
        alert('Ocurrió un error: " . addslashes($e->getMessage()) . "');
        window.location.href = '../module_medical/nursing.php';
    </script>";
} finally {
    if (isset($consulta)) $consulta->close();
    $conn->close();
}
?>
