<?php
session_start();
include '../../db/connection.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('usuarios');

$query = "SELECT id, name, last_name, email, role, status FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar información de usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../src/css/styles.css">
</head>
<body class="bg-gray-100">
<?php include '../shared/navbar.php'; ?>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Información Usuarios</h1>
        <div class="bg-white rounded-lg shadow-md p-6">
            <table class="min-w-full border-collapse border border-gray-200">
                <thead class="bg-red-900 text-white">
                    <tr>
                        <th class="border border-gray-200 px-4 py-2">ID</th>
                        <th class="border border-gray-200 px-4 py-2">Nombre</th>
                        <th class="border border-gray-200 px-4 py-2">Apellidos</th>
                        <th class="border border-gray-200 px-4 py-2">E-mail</th>
                        <th class="border border-gray-200 px-4 py-2">Rol</th>
                        <th class="border border-gray-200 px-4 py-2">Estado</th>
                        <th class="border border-gray-200 px-4 py-2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-t border-gray-200">
                                <td class="border border-gray-200 px-4 py-2"><?php echo $row['id']; ?></td>
                                <td class="border border-gray-200 px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="border border-gray-200 px-4 py-2"><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td class="border border-gray-200 px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="border border-gray-200 px-4 py-2"><?php echo htmlspecialchars($row['role']); ?></td>
                                <td class="border border-gray-200 px-4 py-2"><?php echo $row['status'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                                <td class="border border-gray-200 px-4 py-2 text-center">
                                    <button onclick="openEditModal(<?php echo $row['id']; ?>)" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Editar</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">No hay usuarios disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="shared-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <div class="bg-red-900 text-white px-4 py-2 flex justify-between items-center">
                <h2 class="text-xl font-bold" id="modal-title">Editar Usuario</h2>
                <button class="text-white hover:text-gray-300" onclick="closeModal()">×</button>
            </div>
            <div class="p-4" id="modal-content"></div>
            <div class="bg-gray-100 px-4 py-2 flex justify-end">
                <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(userId) {
            fetch(`../api/get_user.php?id=${userId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modal-content').innerHTML = data;
                    document.getElementById('shared-modal').classList.remove('hidden');
                })
                .catch(error => console.error('Error al cargar los datos:', error));
        }

        function closeModal() {
            document.getElementById('shared-modal').classList.add('hidden');
        }

        function saveUserEdits() {
            const id = document.querySelector('#user-id').value.trim();
            const name = document.querySelector('#name').value.trim();
            const lastName = document.querySelector('#last_name').value.trim();
            const email = document.querySelector('#email').value.trim();
            const role = document.querySelector('#role').value.trim();
            const status = document.querySelector('#status').value;

            if (!id || !name || !lastName || !email || !role || !status) {
                alert('Por favor, completa todos los campos.');
                return;
            }

            fetch('../api/put_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: id,
                    name: name,
                    last_name: lastName,
                    email: email,
                    role: role,
                    status: parseInt(status),
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        closeModal();
                        location.reload();
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error al guardar los cambios:', error));
        }
    </script>
</body>
</html>
