<?php
session_start();
require_once '../../db/connection.php'; 
require_once '../shared/send_email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND status = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("UPDATE users SET token = ?, updated_at = NOW() WHERE email = ?");
        $stmt->execute([$token, $email]);
        $subject = "Recupera tu contraseña";
        $content = "
            <p>Hola,</p>
            <p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>
            <p><a href='https://cas.edu.gt/reset-password.php?token=$token'>Restablecer Contraseña</a></p>
            <p>Si no solicitaste este cambio, por favor ignora este correo.</p>
        ";

        if (sendDynamicEmail($email, $subject, $content)) {
            echo "Un enlace para restablecer tu contraseña ha sido enviado a tu correo.";
        } else {
            echo "Error al enviar el correo. Intenta nuevamente.";
        }
    } else {
        echo "El correo no está registrado o el usuario no está activo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">¿Olvidaste tu contraseña?</h1>
        <p class="mb-6 text-gray-600">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded p-2" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">Enviar Enlace</button>
        </form>
    </div>
</body>
</html>
