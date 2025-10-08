<?php
include '../../db/connection.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo '
            <form id="edit-user-form">
                <input type="hidden" id="user-id" name="id" value="' . htmlspecialchars($user['id']) . '">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="' . htmlspecialchars($user['name']) . '" class="w-full px-4 py-2 border rounded-lg mb-4">
                <label for="last_name">Apellidos:</label>
                <input type="text" id="last_name" name="last_name" value="' . htmlspecialchars($user['last_name']) . '" class="w-full px-4 py-2 border rounded-lg mb-4">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="' . htmlspecialchars($user['email']) . '" class="w-full px-4 py-2 border rounded-lg mb-4">
                <label for="role">Rol:</label>
                <input type="text" id="role" name="role" value="' . htmlspecialchars($user['role']) . '" class="w-full px-4 py-2 border rounded-lg mb-4">
                <label for="status">Estado:</label>
                <select id="status" name="status" class="w-full px-4 py-2 border rounded-lg mb-4">
                    <option value="1" ' . ($user['status'] == 1 ? 'selected' : '') . '>Activo</option>
                    <option value="0" ' . ($user['status'] == 0 ? 'selected' : '') . '>Inactivo</option>
                </select>
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="saveUserEdits()">Guardar Cambios</button>
            </form>
        ';
    } else {
        echo '<p>Usuario no encontrado.</p>';
    }
} else {
    echo '<p>ID no proporcionado.</p>';
}
