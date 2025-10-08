<?php
session_start();
include '../../db/connection.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('administracion');

if (isset($_GET['id'])) {

    $id_maestro = $_GET['id'];
    $sql = "SELECT * FROM maestros WHERE id = $id_maestro";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $codigoEmpleado = $row["codigoEmpleado"];
        $usuarioMaestro = $row["usuarioMaestro"];
        $nombresMaestro = $row["nombresMaestro"];
        $apellidosMaestro = $row["apellidosMaestro"];
        $nivelPertenece = $row["nivelPertence"];
        $puesto = $row["puesto"];
        $extensionTel = $row["extencionTel"];
        $emailMaestro = $row["emailMaestro"];
        $comentarios = $row["comentarios"];
        $estado = $row["estado"];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar datos maestro</title>
     <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php
 include '../shared/navbar.php';
    ?>
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Actualización de datos docentes</h2>
            <form action="../api/put_maestro.php?id=<?php echo $id_maestro; ?>" method="POST">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700">Código Empleado:</label>
                        <input type="int" name="codigoEmpleado" class="form-input w-full mt-1 border" value="<?php echo $codigoEmpleado; ?>">
                    </div>
                    <div>
                        <label class="block text-gray-700">Usuario Maestro:</label>
                        <input type="text" name="usuarioMaestro" class="form-input w-full mt-1 border" value="<?php echo $usuarioMaestro; ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Nombres Maestro:</label>
                        <input type="text" name="nombresMaestro" class="form-input w-full mt-1 border" value="<?php echo $nombresMaestro; ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Apellidos Maestro:</label>
                        <input type="text" name="apellidosMaestro" class="form-input w-full mt-1 border" value="<?php echo $apellidosMaestro; ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Nivel Pertenece:</label>
                        <select name="nivelPertenece" class="form-select w-full mt-1 border" required>
                            <option value="" disabled selected>Selecciona el nivel</option>
                            <option value="administracion" <?php if ($nivelPertenece == 'administracion') echo 'selected'; ?>>ADMINISTRATION</option>
                            <option value="ecp" <?php if ($nivelPertenece == 'ecp') echo 'selected'; ?>>ECP</option>
                            <option value="elementary" <?php if ($nivelPertenece == 'elementary') echo 'selected'; ?>>ELEMENTARY</option>
                            <option value="middle school" <?php if ($nivelPertenece == 'middle school') echo 'selected'; ?>>MIDDLE SCHOOL</option>
                            <option value="high school" <?php if ($nivelPertenece == 'high school') echo 'selected'; ?>>HIGHSCHOOL</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Puesto:</label>
                        <input type="text" name="puesto" class="form-input w-full mt-1 border" value="<?php echo $puesto; ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Extensión Tel:</label>
                        <input type="number" name="extensionTel" class="form-input w-full mt-1 border" value="<?php echo $extensionTel; ?>">
                    </div>
                    <div>
                        <label class="block text-gray-700">Email Maestro:</label>
                        <input type="email" name="emailMaestro" class="form-input w-full mt-1 border" value="<?php echo $emailMaestro; ?>" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Comentarios:</label>
                        <textarea name="comentarios" class="form-textarea w-full mt-1 border" rows="3"><?php echo $comentarios; ?></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700">Estado:</label>
                        <select name="estado" class="form-select w-full mt-1" required>
                            <option value="1" <?php if ($estado == '1') echo 'selected'; ?>>Activo</option>
                            <option value="0" <?php if ($estado == '0') echo 'selected'; ?>>Inactivo</option>
                        </select>

                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>