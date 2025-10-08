<?php
session_start();
include '../../db/connection.php';
include '../shared/alerts.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $stmt = $conn->prepare("SELECT id, password, role, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['status'] !== 1) {
            showAlert("401", "El usuario no está activo.", "../../index.php", "index");
        }

        if (!password_verify($password, $user['password'])) {
            showAlert("403", "La contraseña es incorrecta.", "../../index.php", "index");
        }

        if (!in_array($user['role'], ['admin', 'medical', 'assistant'])) {
            showAlert("403", "No tienes permiso para acceder al sistema.", "../../index.php", "index");
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: ./dashboard.php');
        exit();
    } else {
        showAlert("404", "El usuario no existe o no está activo.", "../../index.php", "index");
    }
} else {
    header('Location: ../../index.php');
    exit();
}
