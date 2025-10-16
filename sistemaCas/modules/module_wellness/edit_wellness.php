<?php 
session_start();
include '../../db/connection_2.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';

validateAccess('usuarios');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Wellness</title>
     <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/2.0.1/i18n/es-ES.json"></script>
</head>

<body class="bg-gray-100">

      <?php include '../shared/navbar.php';

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {

        $id = $_GET['id'];

        $sql = "SELECT id_wellness, id_docente, nombre_wellness, estado, lugar, limite, p1, p2, p3, p4, g1, g2, g3, g4, g5, g6, g7, g8, g9, g10, g11, g12 FROM informacion_wellness WHERE id_wellness = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nombre_wellness = $row['nombre_wellness'];
            $lugar = $row['lugar'];
            $limite = $row['limite'];
            $p1 = $row['p1'];
            $p2 = $row['p2'];
            $p3 = $row['p3'];
            $p4 = $row['p4'];
            $g1 = $row['g1'];
            $g2 = $row['g2'];
            $g3 = $row['g3'];
            $g4 = $row['g4'];
            $g5 = $row['g5'];
            $g6 = $row['g6'];
            $g7 = $row['g7'];
            $g8 = $row['g8'];
            $g9 = $row['g9'];
            $g10 = $row['g10'];
            $g11 = $row['g11'];
            $g12 = $row['g12'];
            $id_docente = $row['id_docente'];
        } else {
            echo "No se encontraron resultados";
        }
    } else {
        echo "ID inválido";
    }

    $conn->close();
    ?>
    <div class="container mx-auto p-4">

        <div class="max-w-lg mx-auto bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Editar Wellness</h2>
            <div class="mb-4">
                <label for="carnet_docente" class="block text-gray-700 font-semibold">Docente</label>
                <?php
                include '../../db/connection.php';
                $sql2 = "SELECT id, nombresMaestro, apellidosMaestro FROM maestros ORDER BY nombresMaestro, apellidosMaestro";
                $result2 = $conn->query($sql2);

                if ($result2->num_rows > 0) {
                    echo "<select name='carnet_docente' id='carnet_docente' class='mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200'>";
                    while ($row2 = $result2->fetch_assoc()) {
                        $selected = ($row2["id"] == $id_docente) ? "selected" : "";
                        echo "<option value='" . $row2["id"] . "' $selected>" . $row2["nombresMaestro"] . " " . $row2["apellidosMaestro"] . "</option>";
                    }
                    echo "</select>";
                } else {
                    echo "No se encontraron maestros";
                }

                $conn->close();
                ?>
            </div>
            <h2 class="text-2xl font-semibold mb-4">Editar wellness</h2>
            <form action="../api/put_welllness.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold">Nombre wellness</label>
                    <input type="text" name="name" id="name" value="<?php echo $nombre_wellness; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                </div>
                <div class="mb-4">
                    <label for="lugar" class="block text-gray-700 font-semibold">Ubicación wellness</label>
                    <input type="text" name="lugar" id="lugar" value="<?php echo $lugar; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                </div>
                <div class="mb-4">
                    <label for="limite" class="block text-gray-700 font-semibold">Límite</label>
                    <input type="number" name="limite" id="limite" value="<?php echo $limite; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                </div>

                <div class="mb-4">
                    <p class="text-gray-700 font-semibold mb-2">Periodos</p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="p<?php echo $i ?>" value="1" <?php if ($row['p' . $i] == 1) echo "checked"; ?> class="form-checkbox text-red-500 focus:ring-red-400 h-5 w-5">
                                <span class="ml-2"><?php echo $i ?></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 font-semibold mb-2">Grados</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="g<?php echo $i ?>" value="1" <?php if ($row['g' . $i] == 1) echo "checked"; ?> class="form-checkbox text-red-500 focus:ring-red-400 h-5 w-5">
                                <span class="ml-2"><?php echo $i ?></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
                
<div class="mb-4">
    <p class="text-gray-700 font-semibold mb-2">Estado</p>
    <label class="inline-flex items-center">
        <input type="checkbox" name="estado" value="1" <?php if ($row['estado'] == 1) echo "checked"; ?> class="form-checkbox text-red-500 focus:ring-red-400 h-5 w-5">
        <span class="ml-2">Activo</span>
    </label>
</div>
                <div class="mt-6">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:bg-red-600">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    $('#tabla').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });
</script>


</html>