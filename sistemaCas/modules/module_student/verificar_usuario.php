<?php
session_start();
require '../../src/phpMailer/Exception.php';
require '../../src/phpMailer/PHPMailer.php';
require '../../src/phpMailer/SMTP.php';
include '../../db/connection_3.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// Verifica si el correo está en sesión
if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit();
}

// Obtén la contraseña del formulario
$correo = $_SESSION['correo'];
$pass = password_hash(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);
$token = bin2hex(random_bytes(16)); // Genera un token único



// Inserta o actualiza el usuario en la tabla datos_mac
$query = "
    INSERT INTO datos_mac (correo_alumno, pass, token) 
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE pass = VALUES(pass), token = VALUES(token)
";
$stmt = $conn->prepare($query);
$stmt->bind_param('sss', $correo, $pass, $token);

if ($stmt->execute()) {
    // Envía el correo de verificación
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'cas.edu.gt';
        $mail->SMTPAuth = true;
        $mail->Username = 'its@cas.edu.gt';
        $mail->Password = 'Cas2024*';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('its@cas.edu.gt', 'Verificación CAS');
        $mail->addAddress($correo, 'Estudiante CAS');

        $mail->isHTML(true);
        $mail->Subject = 'Verificación de cuenta';
        $mail->Body = "
            <p>Hola,</p>
            <p>Por favor verifica tu cuenta usando el siguiente enlace:</p>
            <p><a href='http://tu-dominio.com/actualizar_mac.php?token=$token'>Verificar cuenta</a></p>
            <p>Gracias,</p>
            <p>CAS Tecnología</p>
        ";

        $mail->send();
        echo "<script>alert('Contraseña creada y correo de verificación enviado.'); window.location.href = 'index.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el correo: {$mail->ErrorInfo}'); window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('Error al registrar la contraseña.'); window.location.href = 'modal_password.php';</script>";
}

$stmt->close();
$conn->close();
