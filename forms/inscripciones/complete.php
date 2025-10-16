<?php

session_start();
error_log(print_r($_SESSION, true));
$year = date("Y");
$nombres_alumno = $_SESSION['nombres_alumno'];
$correo_encargado_lleno_f = $_SESSION['correo_encargado_lleno_f'];
$nombre_encargado_lleno_f = $_SESSION['nombre_encargado_lleno_f'];
$grado_alumno =  $_SESSION['grado_alumno'];

$to = filter_var($correo_encargado_lleno_f, FILTER_SANITIZE_EMAIL); 
$subject = 'Confirmación de envío de datos de ' . $nombres_alumno;  
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: its@cas.edu.gt" . "\r\n"; 

$message = "<p>Estimado/a <b>$nombre_encargado_lleno_f</b>,</p>
            <p>Se ha recibido correctamente la información del estudiante <b>$nombres_alumno</b>.</p>";

if (mail($to, $subject, $message, $headers)) {
    
    $to = filter_var("iortiz@cas.edu.gt", FILTER_SANITIZE_EMAIL); 
    $subject = 'Nuevo formulario '. $year; '/ Estudiante: ' . $nombres_alumno ;  
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: its@cas.edu.gt" . "\r\n"; 
$header .= "Cc: hlopez@cas.edu.gt, csuyan@cas.edu.gt\r\n";

$message = "<p> <b>$nombre_encargado_lleno_f</b>, con correo: <b>$correo_encargado_lleno_f</b></p>
            <p>ha actualizado el formulario completamente para el alumno <b>$nombres_alumno</b>, quien ingresa a grado: <b>$grado_alumno</b>.</p>";

    if (mail($to, $subject, $message, $headers)) {
         
    }
 
} else {
    echo "Error al enviar el correo.";
    error_log("Error al enviar el correo a $to. Revisar la configuración SMTP.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información guardada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Información guardada con éxito</h1>
            <p class="text-gray-600">Gracias por completar el formulario. Su información ha sido guardada exitosamente.</p>
            <p class="mt-4 text-gray-600">Cualquier proceso o duda, comuníquese al correo <a href="mailto:iortiz@cas.edu.gt" class="text-blue-500 underline">iortiz@cas.edu.gt</a>. Nos pondremos en contacto con usted si hay novedades.</p>

            <button onclick="window.location.href='./cerrar_sesion.php';" class="mt-6 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none">
                Finalizar
            </button>
        </div>
    </div>
</body>
</html>
