<?php
function sendEmail($to, $subject, $body, $headers = []) {
    // Plantilla estándar para los correos
    $template = "<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                background: #fff;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            .header {
                font-size: 24px;
                color: #333;
                margin-bottom: 20px;
            }
            .content {
                font-size: 16px;
                color: #555;
                line-height: 1.5;
            }
            .footer {
                margin-top: 20px;
                font-size: 12px;
                color: #999;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>" . htmlspecialchars($subject) . "</div>
            <div class='content'>" . $body . "</div>
            <div class='footer'>
                <p>Este es un mensaje automático. Por favor, no responda a este correo.</p>
            </div>
        </div>
    </body>
    </html>";

    // Encabezados predeterminados
    $defaultHeaders = [
        'From' => 'its@cas.edu.gt',
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/html; charset=UTF-8'
    ];

    // Combina encabezados predeterminados con los personalizados
    $finalHeaders = array_merge($defaultHeaders, $headers);

    // Convierte los encabezados en un string
    $headersString = "";
    foreach ($finalHeaders as $key => $value) {
        $headersString .= "$key: $value\r\n";
    }

    // Envía el correo
    return mail($to, $subject, $template, $headersString);
}

// Ejemplo generalizado para cualquier motivo
function sendDynamicEmail($to, $subject, $content) {
    // Llama a la función de correo con contenido dinámico
    return sendEmail($to, $subject, $content);
}

// Ejemplo de uso dinámico desde otro archivo
if (isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['content'])) {
    $to = $_POST['email'];
    $subject = $_POST['subject'];
    $content = $_POST['content'];

    if (sendDynamicEmail($to, $subject, $content)) {
        echo "Correo enviado correctamente.";
    } else {
        echo "Error al enviar el correo.";
    }
}
?>
