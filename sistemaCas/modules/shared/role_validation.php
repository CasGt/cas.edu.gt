<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function validateAccess($module) {
    $permissions = include __DIR__ . '/permissions.php';

    if (!isset($_SESSION['user_role'])) {
        showAlert("401", "Acceso no autorizado.", "../../index.php", "index");
    }

    if (!isset($permissions[$module])) {
        showAlert("404", "Módulo no encontrado.", "../../index.php", "index");
    }

    $allowed_roles = $permissions[$module]['roles'];
    if (!in_array($_SESSION['user_role'], $allowed_roles)) {
        showAlert("403", "No tienes permiso para acceder al sistema.", "../../index.php", "index");
    }

}
?>
