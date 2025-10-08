<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de maestros</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/2.0.1/i18n/es-ES.json"></script>
    <script>
        function toggleCheckboxes() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="seleccion[]"]');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
        }
    </script>
</head>

<body class="bg-gray-100">
    <?php include '../shared/navbar.php'; ?>

    <br>
    <form action="../api/get_export_data.php" method="post" class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <div class="mb-4">
            <h2 class="text-2xl font-bold mb-4">Exportar informacion</h2>

            <div class="mb-4">
                <label for="anio" class="block text-lg font-semibold mb-2">Seleccione el Año</label>
                <select name="anio" id="anio" class="block w-full px-3 py-2 border border-gray-300 rounded">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            
            <h2 class="text-2xl font-bold mb-4">Seleccione los campos</h2>
            
            <button type="button" class="bg-red-500 text-white py-2 px-4 rounded mb-4" onclick="toggleCheckboxes()">
                Seleccionar/Deseleccionar todos
            </button>
            
            <!-- Grupo de Alumnos -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Alumnos</h3>
                <?php
                $alumnos = ['a.carnet', 'a.codigo_alumno', 'a.nombres_alumno', 'a.apellidos_alumno', 'a.grado_alumno', 'a.nacimiento_alumno'];
                foreach ($alumnos as $campo) {
                    $label = ucwords(str_replace('_', ' ', substr($campo, strpos($campo, '.') + 1)));
                    echo "<label class='block'><input type='checkbox' name='seleccion[]' value='$campo' class='mr-2'>$label</label>";
                }
                ?>
            </div>

            <!-- Grupo de Padres -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Padres</h3>
                <?php
                $padres = ['p.nombres_padre', 'p.apellidos_padre', 'p.dpi_padre', 'p.correo_padre', 'p.celular_padre', 'p.telefonocasa_padre', 'p.direccion_padre', 'p.empresalabora_padre', 'p.departamentoempresa_padre', 'p.correo_empresa_padre', 'p.telefono_empresa_padre', 'p.nacionalidad_padre', 'p.nit_padre', 'p.profesion_padre', 'p.departamento_padre', 'p.estado_civil_padre', 'p.municipio_padre', 'p.cargoenepresa_padre', 'p.municipio_empresa_padre'];
                foreach ($padres as $campo) {
                    $label = ucwords(str_replace('_', ' ', substr($campo, strpos($campo, '.') + 1)));
                    echo "<label class='block'><input type='checkbox' name='seleccion[]' value='$campo' class='mr-2'>$label</label>";
                }
                ?>
            </div>

            <!-- Grupo de Madres -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Madres</h3>
                <?php
                $madres = ['m.nombres_madre', 'm.apellidos_madre', 'm.dpi_madre', 'm.correo_madre', 'm.celular_madre', 'm.telefonocasa_madre', 'm.direccion_madre', 'm.empresalabora_madre', 'm.departamentoempresa_madre', 'm.correo_empresa_madre', 'm.telefono_empresa_madre', 'm.nacionalidad_madre', 'm.nit_madre', 'm.profesion_madre', 'm.departamento_madre', 'm.estado_civil_madre', 'm.municipio_madre', 'm.cargoenepresa_madre', 'm.municipio_empresa_madre'];
                foreach ($madres as $campo) {
                    $label = ucwords(str_replace('_', ' ', substr($campo, strpos($campo, '.') + 1)));
                    echo "<label class='block'><input type='checkbox' name='seleccion[]' value='$campo' class='mr-2'>$label</label>";
                }
                ?>
            </div>

            <!-- Grupo de Terceros -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Terceros</h3>
                <?php
                $terceros = ['t.terceros1_retiran_alumno', 't.terceros1_retiran_alumno_parentesco', 't.terceros1_retiran_alumno_telefono', 't.terceros1_parentezco', 't.terceros2_retiran_alumno', 't.terceros2_retiran_alumno_parentesco', 't.terceros2_retiran_alumno_telefono', 't.terceros2_parentezco'];
                foreach ($terceros as $campo) {
                    $label = ucwords(str_replace('_', ' ', substr($campo, strpos($campo, '.') + 1)));
                    echo "<label class='block'><input type='checkbox' name='seleccion[]' value='$campo' class='mr-2'>$label</label>";
                }
                ?>
            </div>

        </div>
        <button type="submit" class="bg-red-900 text-white py-2 px-4 rounded">Enviar</button>
    </form>
</body>
</html>
