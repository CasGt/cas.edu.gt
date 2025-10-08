<?php
require './conexion.php';
$year = date("Y");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = trim($_POST['codigo']);
    $nombres = trim($_POST['new_nombres']);
    $apellidos = trim($_POST['new_apellidos']);
    $email = trim($_POST['new_email']);
    $password = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT); 

    // Verificar si el código existe y está activo (estado = 1)
    $sql = "SELECT codigo, estado FROM token_usuarios_nuevos WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->bind_result($codigo_bd, $estado_bd);
    $stmt->fetch();

    if ($codigo_bd && $estado_bd == 1) {
        // El código es válido y no ha sido utilizado
        $stmt->free_result();  // Liberar el resultado actual
        $stmt->close();

        // Generar código único para el nuevo usuario
        $primer_nombre = explode(' ', $nombres)[0];
        $primer_apellido = explode(' ', $apellidos)[0];
        $codigo_numerico = rand(10000000, 99999999);
        $codigo_alumno = $primer_nombre . $primer_apellido . $codigo_numerico;
        $estado_nuevo = "1";

        // Insertar datos del nuevo alumno
        $sql_insert = "INSERT INTO alumno_nuevo_ingreso (nombres_alumno, apellidos_alumno, correo_alumno, pass, codigo_alumno, estado, cicloActual) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssssss", $nombres, $apellidos, $email, $password, $codigo_alumno, $estado_nuevo, $year);

        if ($stmt_insert->execute()) {
            // Actualizar el estado del código a 3 (desactivado)
            $sql_update = "UPDATE token_usuarios_nuevos SET estado = 3 WHERE codigo = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("s", $codigo);
            $stmt_update->execute();
            $stmt_update->close();

            // Responder con éxito
            echo "success";
        } else {
            echo "error_crear_usuario";
        }

        $stmt_insert->close();
    } elseif ($codigo_bd && $estado_bd != 1) {
        // Código ya ha sido utilizado (estado no es 1)
        echo "codigo_usado";
    } else {
        // El código no existe
        echo "codigo_invalido";
    }

    $conn->close();
}
?>
