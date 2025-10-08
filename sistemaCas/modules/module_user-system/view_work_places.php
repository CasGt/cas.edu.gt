<?php
session_start();
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('administracion');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de plazas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include('../shared/navbar.php'); ?>
    <div class="mt-4"></div>
    <div class="container mx-auto p-6">
        <header class="mb-6">
            <div class="flex items-center justify-between">
                <a href="#" class="bg-red-900 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">Regresar</a>
            </div>
        </header>
        <div class="bg-white p-6 rounded-md shadow-md overflow-x-auto">
            <h2 class="text-2xl font-semibold mb-4">Gestión de ofertas laborales</h2>
            <button class="bg-red-900 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="openModal()">
                Cargar nuevo
            </button>
            <table id="tabla" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Imagen</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $directory = '../../src/images/works_places/';
                    $images = glob($directory . '*.{jpg,png,gif}', GLOB_BRACE);
                    if (!empty($images)) {
                        foreach ($images as $image) {
                            $imageName = basename($image);
                            echo "<tr>
                                    <td class='border px-4 py-2'><img src='$directory$imageName' alt='$imageName' class='w-16 h-16 object-cover'></td>
                                    <td class='border px-4 py-2'>$imageName</td>
                                    <td class='border px-4 py-2'>
                                        <form action='edit_work_places.php' method='POST'>
                                            <input type='hidden' name='filename' value='$imageName'>
                                            <button type='submit' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none'>Eliminar</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center py-4'>No hay imágenes disponibles.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-md w-1/3">
            <h2 class="text-xl font-semibold mb-4">Subir nueva imagen</h2>
            <form id="uploadForm" action="create_work_places.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" class="mb-4">
                <button type="submit" class="bg-red-900 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Subir</button>
            </form>
            <button class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="closeModal()">Cerrar</button>
            <div id="uploadSuccess" class="text-green-500 mt-4 hidden">¡Imagen subida con éxito!</div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tabla').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
            $('#uploadForm').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'create_work_places.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function () {
                        $('#uploadSuccess').removeClass('hidden');
                        setTimeout(function () {
                            closeModal();
                            location.reload();
                        }, 1500);
                    },
                    error: function () {
                        alert('Error al subir la imagen.');
                    }
                });
            });
        });
        function openModal() {
            $('#uploadModal').removeClass('hidden');
        }

        function closeModal() {
            $('#uploadModal').addClass('hidden');
        }
    </script>
</body>
</html>
