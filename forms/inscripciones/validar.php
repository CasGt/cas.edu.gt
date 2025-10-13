<?php

// Conexión a la base de datos
require './conexion.php';

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carnet_or_email = trim($_POST['carnet_or_email']);
    $password = trim($_POST['password']);

    // Comprobar si el input es un correo electrónico
    if (filter_var($carnet_or_email, FILTER_VALIDATE_EMAIL)) {
        // Si es un correo electrónico, buscar en la tabla alumno
        $sql = "SELECT codigo_alumno, correo_alumno, pass, carnet FROM alumno WHERE correo_alumno = ? AND estado = 1 AND cicloActual = '2025'";
    } else {
        // Si no es un correo, asumimos que es un carnet, buscar en la tabla alumno
        $sql = "SELECT codigo_alumno, correo_alumno, pass, carnet FROM alumno WHERE carnet = ? AND estado = 1 AND cicloActual = '2025'";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $carnet_or_email);
    $stmt->execute();

    // Usar bind_result si get_result no está disponible
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Recuperar el resultado
        $stmt->bind_result($codigo_alumno, $correo_alumno, $hashed_pass, $carnet);
        $stmt->fetch();

        // Verificar la contraseña ingresada con el hash en la base de datos
        if (password_verify($password, $hashed_pass)) {
            // Contraseña correcta, asignar el rol tipo_alumno = reingreso
            session_start();
            $_SESSION['id_alumno'] = $codigo_alumno;
            $_SESSION['tipo_alumno'] = 'reingreso';
            $_SESSION['password'] = $hashed_pass;
            $_SESSION['carnet'] = $carnet;
            
            header('Location: formulario.php');
            exit();
        } else {
            // Contraseña incorrecta
            $error_msg = urlencode("Usuario o contraseña incorrectos");
            header("Location: index.php?error=$error_msg");
            exit();
        }
    } else {
        // Si no se encuentra en la tabla alumno, buscar en la tabla alumno_nuevo_ingreso
        $sql_nuevo = "SELECT codigo_alumno, correo_alumno, pass FROM alumno_nuevo_ingreso WHERE correo_alumno = ? AND estado = 1";
        $stmt_nuevo = $conn->prepare($sql_nuevo);
        $stmt_nuevo->bind_param("s", $carnet_or_email);
        $stmt_nuevo->execute();
        $stmt_nuevo->store_result();

        if ($stmt_nuevo->num_rows > 0) {
            // Recuperar el resultado
            $stmt_nuevo->bind_result($codigo_alumno, $correo_alumno, $hashed_pass);
            $stmt_nuevo->fetch();

            // Verificar la contraseña ingresada con el hash en la base de datos
            if (password_verify($password, $hashed_pass)) {
                // Contraseña correcta, asignar el rol tipo_alumno = nuevo
                session_start();
                $_SESSION['id_alumno'] = $codigo_alumno;
                $_SESSION['tipo_alumno'] = 'nuevo';
                $_SESSION['password'] = $hashed_pass;


                header('Location: formulario.php');
                exit();
            } else {
                // Contraseña incorrecta
                $error_msg = urlencode("Usuario o contraseña incorrectos");
                header("Location: index.php?error=$error_msg");
                exit();
            }
        } else {
            // No se encontró el usuario en ninguna tabla, redirigir con mensaje de error
            $error_msg = urlencode("Usuario o contraseña incorrectos");
            header("Location: index.php?error=$error_msg");
            exit();
        }

        $stmt_nuevo->close();
    }

    $stmt->close();
}

$conn->close();
