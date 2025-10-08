<?php
session_start();
if (!isset($_SESSION['id_alumno'])) {
    header('Location: index.php');
    exit();
}
$year = date("Y") ;

require './conexion.php';

$sql = "SELECT 
    alumno.nombres_alumno,
    alumno.apellidos_alumno,
    alumno.correo_alumno,
    alumno.familiar_vive,
    alumno.nacimiento_alumno,
    alumno.grado_alumno,
FROM alumno
WHERE alumno.codigo_alumno = ?";


$stmt = $conn->prepare($sql);


if ($stmt === false) {
    die('Error al preparar la consulta: ' . $conn->error);
}

// Obtener el código del alumno desde la sesión (o de otra fuente)
$codigo_alumno = $_SESSION['id_alumno'];  // Asumiendo que el código del alumno está en la sesión

// Ligar el parámetro con bind_param: "s" para string
$stmt->bind_param("s", $codigo_alumno);

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    $alumno = $result->fetch_assoc();
} else {
    echo "No se encontraron datos para el alumno.";
    exit();
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario <?php echo $year; ?> Admisiones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-lg">
        <h1 class="text-3xl font-bold mb-4 text-center"><?php echo $year; ?> Admisiones</h1>
        <p class="text-center text-gray-600 mb-8">Esta información nos permitirá saber más sobre usted.</p>

        <form id="admissionForm" action="guardar_datos.php" method="POST" class="space-y-6">


            <div id="section1" class="section">
                <h2 class="text-xl font-bold mb-4">Información básica</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombres_alumno" class="block font-medium">Nombre(s) (*)</label>
                        <input type="text" id="nombres_alumno" name="nombres_alumno" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="apellidos_alumno" class="block font-medium">Apellido(s) (*)</label>
                        <input type="text" id="apellidos_alumno" name="apellidos_alumno" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="correo_alumno" class="block font-medium">Correo (*)</label>
                        <input type="email" id="correo_alumno" name="correo_alumno" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="familiar_vive" class="block font-medium">Familiares con los que vive (*)</label>
                        <input type="text" id="familiar_vive" name="familiar_vive" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="nacimiento_alumno" class="block font-medium">Fecha de Nacimiento (*)</label>
                        <input type="date" id="nacimiento_alumno" name="nacimiento_alumno" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label for="grado_alumno" class="block font-medium">Grado en <?php echo $year; ?> (*)</label>
                        <select id="grado_alumno" name="grado_alumno" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                            <option value="Pre-kinder 1">Pre-kinder 1</option>
                            <option value="Pre-kinder 2">Pre-kinder 2</option>
                            <option value="Kindergarten">Kindergarten</option>
                            <option value="1">Grado 1</option>
                            <option value="2">Grado 2</option>
                            <option value="3">Grado 3</option>
                            <option value="4">Grado 4</option>
                            <option value="5">Grado 5</option>
                            <option value="6">Grado 6</option>
                            <option value="7">Grado 7</option>
                            <option value="8">Grado 8</option>
                            <option value="9">Grado 9</option>
                            <option value="10">Grado 10</option>
                            <option value="11">Grado 11</option>
                            <option value="12">Grado 12</option>
                        </select>
                    </div>
                </div>
            </div>

<!-- Sección 2: Información del Padre -->
<div id="section2" class="section hidden">
    <h2 class="text-xl font-bold mb-4">Información del Padre</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Información básica -->
        <div>
            <label for="nombres_padre" class="block font-medium">Nombre(s)</label>
            <input type="text" id="nombres_padre" name="nombres_padre" placeholder="Juan, Carlos..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="apellidos_padre" class="block font-medium">Apellidos</label>
            <input type="text" id="apellidos_padre" name="apellidos_padre" placeholder="Pérez, García..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="dpi_padre" class="block font-medium">D.P.I.</label>
            <input type="text" id="dpi_padre" name="dpi_padre" placeholder="1234567890101" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="nit_padre" class="block font-medium">NIT</label>
            <input type="text" id="nit_padre" name="nit_padre" placeholder="1234567-8" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="estado_civil_padre" class="block font-medium">Estado Civil</label>
            <select id="estado_civil_padre" name="estado_civil_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                <option value="">Seleccione...</option>
                <option value="Casado">Casado</option>
                <option value="Soltero">Soltero</option>
                <option value="Unido">Unido</option>
                <option value="Divorciado">Divorciado</option>
            </select>
        </div>
        <div>
            <label for="nacionalidad_padre" class="block font-medium">Nacionalidad</label>
            <input type="text" id="nacionalidad_padre" name="nacionalidad_padre" placeholder="Guatemalteca, Mexicana..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>

        <!-- Vacuna Covid-19 -->
        <div class="col-span-2">
            <h3 class="text-xl font-bold text-center">Vacuna contra Covid-19</h3>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="flex flex-col items-center">
                    <label class="block font-medium">1ra. Dosis</label>
                    <input type="checkbox" id="vacuna1_SI_padre" name="vacuna1_SI_padre" class="mr-2">
                    <select id="nombre_vacuna1_padre" name="nombre_vacuna1_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        <option value="Moderna">Moderna</option>
                        <option value="Johnson & Johnson">Johnson & Johnson</option>
                        <option value="Pfizer">Pfizer</option>
                        <option value="Sputnik V">Sputnik V</option>
                        <option value="Astrazeneca">Astrazeneca</option>
                    </select>
                </div>
                <div class="flex flex-col items-center">
                    <label class="block font-medium">2da. Dosis</label>
                    <input type="checkbox" id="vacuna2_SI_padre" name="vacuna2_SI_padre" class="mr-2">
                    <select id="nombre_vacuna2_padre" name="nombre_vacuna2_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        <option value="Moderna">Moderna</option>
                        <option value="Johnson & Johnson">Johnson & Johnson</option>
                        <option value="Pfizer">Pfizer</option>
                        <option value="Sputnik V">Sputnik V</option>
                        <option value="Astrazeneca">Astrazeneca</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Dirección del hogar -->
        <div class="col-span-2">
            <h3 class="text-xl font-bold mb-4">Dirección del hogar</h3>
        </div>
        <div>
            <label for="departamento_padre" class="block font-medium">Departamento</label>
            <input type="text" id="departamento_padre" name="departamento_padre" placeholder="Escuintla, Guatemala..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="municipio_padre" class="block font-medium">Municipio</label>
            <input type="text" id="municipio_padre" name="municipio_padre" placeholder="Siquinalá, Antigua..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="direccion_padre" class="block font-medium">Dirección</label>
            <input type="text" id="direccion_padre" name="direccion_padre" placeholder="445 Mount Eden Road, Mazatenango..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="telefono_padre" class="block font-medium">Teléfono</label>
            <input type="number" id="telefono_padre" name="telefono_padre" placeholder="78820000" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="celular_padre" class="block font-medium">Celular</label>
            <input type="number" id="celular_padre" name="celular_padre" placeholder="51510000" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="correo_personal_padre" class="block font-medium">Correo Personal</label>
            <input type="email" id="correo_personal_padre" name="correo_personal_padre" placeholder="myname@servidor.com" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>

        <!-- Dirección de la empresa -->
        <div class="col-span-2">
            <h3 class="text-xl font-bold mb-4">Dirección de la Empresa</h3>
        </div>
        <div>
            <label for="profesion_padre" class="block font-medium">Profesión</label>
            <input type="text" id="profesion_padre" name="profesion_padre" placeholder="Ingeniero, Contador..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="puesto_trabajo_padre" class="block font-medium">Puesto que labora</label>
            <input type="text" id="puesto_trabajo_padre" name="puesto_trabajo_padre" placeholder="Gerente, Supervisor..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="nombre_empresa_padre" class="block font-medium">Nombre de la empresa</label>
            <input type="text" id="nombre_empresa_padre" name="nombre_empresa_padre" placeholder="Industrias XYZ..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="telefono_empresa_padre" class="block font-medium">Teléfono de la empresa</label>
            <input type="number" id="telefono_empresa_padre" name="telefono_empresa_padre" placeholder="23240000" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="departamento_empresa_padre" class="block font-medium">Departamento</label>
            <input type="text" id="departamento_empresa_padre" name="departamento_empresa_padre" placeholder="Tecnología, Ventas..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="municipio_empresa_padre" class="block font-medium">Municipalidad</label>
            <input type="text" id="municipio_empresa_padre" name="municipio_empresa_padre" placeholder="Guatemala, Antigua..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="direccion_exacta_empresa_padre" class="block font-medium">Dirección exacta de la empresa</label>
            <input type="text" id="direccion_exacta_empresa_padre" name="direccion_exacta_empresa_padre" placeholder="5ta avenida zona 10, Guatemala..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="correo_corporativo_padre" class="block font-medium">Correo Corporativo</label>
            <input type="email" id="correo_corporativo_padre" name="correo_corporativo_padre" placeholder="corporativo@empresa.com" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
    </div>
</div>

            <!-- Sección 3: Información de la Madre -->
           <!-- Sección 3: Información de la Madre -->
<div id="section3" class="section hidden">
    <h2 class="text-xl font-bold mb-4">Información de la Madre</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Información básica -->
        <div>
            <label for="nombres_madre" class="block font-medium">Nombre(s)</label>
            <input type="text" id="nombres_madre" name="nombres_madre" placeholder="Juanita, Sofía..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="apellidos_madre" class="block font-medium">Apellidos</label>
            <input type="text" id="apellidos_madre" name="apellidos_madre" placeholder="Pérez, García..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="dpi_madre" class="block font-medium">D.P.I.</label>
            <input type="text" id="dpi_madre" name="dpi_madre" placeholder="1234567890101" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="nit_madre" class="block font-medium">NIT</label>
            <input type="text" id="nit_madre" name="nit_madre" placeholder="1234567-8" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="estado_civil_madre" class="block font-medium">Estado Civil</label>
            <select id="estado_civil_madre" name="estado_civil_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                <option value="">Seleccione...</option>
                <option value="Casada">Casada</option>
                <option value="Soltera">Soltera</option>
                <option value="Unida">Unida</option>
                <option value="Divorciada">Divorciada</option>
            </select>
        </div>
        <div>
            <label for="nacionalidad_madre" class="block font-medium">Nacionalidad</label>
            <input type="text" id="nacionalidad_madre" name="nacionalidad_madre" placeholder="Guatemalteca, Mexicana..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>

        <!-- Vacuna Covid-19 -->
        <div class="col-span-2">
            <h3 class="text-xl font-bold text-center">Vacuna contra Covid-19</h3>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="flex flex-col items-center">
                    <label class="block font-medium">1ra. Dosis</label>
                    <input type="checkbox" id="vacuna1_SI_madre" name="vacuna1_SI_madre" class="mr-2">
                    <select id="nombre_vacuna1_madre" name="nombre_vacuna1_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        <option value="Moderna">Moderna</option>
                        <option value="Johnson & Johnson">Johnson & Johnson</option>
                        <option value="Pfizer">Pfizer</option>
                        <option value="Sputnik V">Sputnik V</option>
                        <option value="Astrazeneca">Astrazeneca</option>
                    </select>
                </div>
                <div class="flex flex-col items-center">
                    <label class="block font-medium">2da. Dosis</label>
                    <input type="checkbox" id="vacuna2_SI_madre" name="vacuna2_SI_madre" class="mr-2">
                    <select id="nombre_vacuna2_madre" name="nombre_vacuna2_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                        <option value="">Seleccione...</option>
                        <option value="Moderna">Moderna</option>
                        <option value="Johnson & Johnson">Johnson & Johnson</option>
                        <option value="Pfizer">Pfizer</option>
                        <option value="Sputnik V">Sputnik V</option>
                        <option value="Astrazeneca">Astrazeneca</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Dirección del hogar -->
        <div class="col-span-2">
            <h3 class="text-xl font-bold mb-4">Dirección del hogar</h3>
        </div>
        <div>
            <label for="departamento_madre" class="block font-medium">Departamento</label>
            <input type="text" id="departamento_madre" name="departamento_madre" placeholder="Escuintla, Guatemala..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="municipio_madre" class="block font-medium">Municipio</label>
            <input type="text" id="municipio_madre" name="municipio_madre" placeholder="Siquinalá, Antigua..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="direccion_madre" class="block font-medium">Dirección</label>
            <input type="text" id="direccion_madre" name="direccion_madre" placeholder="445 Mount Eden Road, Mazatenango..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="telefonocasa_madre" class="block font-medium">Teléfono</label>
            <input type="number" id="telefonocasa_madre" name="telefonocasa_madre" placeholder="78820000" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="celular_madre" class="block font-medium">Celular</label>
            <input type="number" id="celular_madre" name="celular_madre" placeholder="51510000" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="correo_madre" class="block font-medium">Correo Personal</label>
            <input type="email" id="correo_madre" name="correo_madre" placeholder="myname@servidor.com" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>

        <!-- Dirección de la empresa -->
        <div class="col-span-2">
            <h3 class="text-xl font-bold mb-4">Dirección de la Empresa</h3>
        </div>
        <div>
            <label for="profesion_madre" class="block font-medium">Profesión</label>
            <input type="text" id="profesion_madre" name="profesion_madre" placeholder="Ingeniera, Contadora..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="cargoenepresa_madre" class="block font-medium">Puesto que labora</label>
            <input type="text" id="cargoenepresa_madre" name="cargoenepresa_madre" placeholder="Gerente, Supervisora..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="empresalabora_madre" class="block font-medium">Nombre de la empresa</label>
            <input type="text" id="empresalabora_madre" name="empresalabora_madre" placeholder="Industrias XYZ..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="telefono_empresa_madre" class="block font-medium">Teléfono de la empresa</label>
            <input type="number" id="telefono_empresa_madre" name="telefono_empresa_madre" placeholder="23240000" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="departamentoempresa_madre" class="block font-medium">Departamento</label>
            <input type="text" id="departamentoempresa_madre" name="departamentoempresa_madre" placeholder="Tecnología, Ventas..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="municipio_empresa_madre" class="block font-medium">Municipalidad</label>
            <input type="text" id="municipio_empresa_madre" name="municipio_empresa_madre" placeholder="Guatemala, Antigua..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="direccion_empresa_madre" class="block font-medium">Dirección exacta de la empresa</label>
            <input type="text" id="direccion_empresa_madre" name="direccion_empresa_madre" placeholder="5ta avenida zona 10, Guatemala..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div class="col-span-2">
            <label for="correo_empresa_madre" class="block font-medium">Correo Corporativo</label>
            <input type="email" id="correo_empresa_madre" name="correo_empresa_madre" placeholder="corporativo@empresa.com" class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
    </div>
</div>


            <!-- Sección 4: Información Importante -->
 <!-- Sección 4: Información Importante -->
<div id="section4" class="section hidden">
    <h2 class="text-xl font-bold mb-4">Información Importante</h2>

    <!-- Información general -->
    <div class="grid grid-cols-1 gap-4 mb-8">
        <div>
            <label for="actividades_estudiante" class="block font-medium">Actividades especiales que realiza el alumno</label>
            <input type="text" id="actividades_estudiante" name="actividades_estudiante" placeholder="Ej. Fútbol, Música..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="enfermedades_estudiante" class="block font-medium">Condiciones o enfermedades que tiene mi hijo (*)</label>
            <input type="text" id="enfermedades_estudiante" name="enfermedades_estudiante" placeholder="Ej. Asma, Alergias..." class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div>
            <label for="medicamentos_diario" class="block font-medium">Medicamentos que usa diariamente (*)</label>
            <input type="text" id="medicamentos_diario" name="medicamentos_diario" placeholder="Ej. Ibuprofeno, Albuterol..." class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div>
            <label for="alergias_estudiante" class="block font-medium">Alergias que sufre mi hijo (*)</label>
            <input type="text" id="alergias_estudiante" name="alergias_estudiante" placeholder="Ej. Polvo, Gluten..." class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
        </div>
        <div>
            <label for="evaluacion_estudiante" class="block font-medium">Tiene alguna evaluación psicológica, física y / o conductual (en caso positivo describir)</label>
            <input type="text" id="evaluacion_estudiante" name="evaluacion_estudiante" placeholder="Describa si aplica..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        <div>
            <label for="intervencion_estudiante" class="block font-medium">Ha sido intervenido quirúrgicamente (en caso positivo describir)</label>
            <input type="text" id="intervencion_estudiante" name="intervencion_estudiante" placeholder="Describa si aplica..." class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
    </div>

    <!-- Doy autorización para proporcionar -->
    <h3 class="text-xl font-bold mb-4">Doy autorización para proporcionar</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div>
            <input type="checkbox" id="acetaminofen" name="acetaminofen">
            <label for="acetaminofen" class="ml-2">Acetaminofén</label><br>

            <input type="checkbox" id="diclofenaco" name="diclofenaco">
            <label for="diclofenaco" class="ml-2">Diclofenaco</label><br>

            <input type="checkbox" id="aspirina" name="aspirina">
            <label for="aspirina" class="ml-2">Aspirina</label><br>

            <input type="checkbox" id="peptobismol" name="peptobismol">
            <label for="peptobismol" class="ml-2">Peptobismol</label><br>

            <input type="checkbox" id="fastum" name="fastum">
            <label for="fastum" class="ml-2">Fastum</label><br>

            <input type="checkbox" id="ibuprofeno" name="ibuprofeno">
            <label for="ibuprofeno" class="ml-2">Ibuprofeno</label><br>

            <input type="checkbox" id="bromexina" name="bromexina">
            <label for="bromexina" class="ml-2">Bromexina</label><br>

            <input type="checkbox" id="loperamida" name="loperamida">
            <label for="loperamida" class="ml-2">Loperamida</label><br>

            <input type="checkbox" id="ranitidina" name="ranitidina">
            <label for="ranitidina" class="ml-2">Ranitidina</label><br>

            <input type="checkbox" id="tabcin" name="tabcin">
            <label for="tabcin" class="ml-2">Tabcin</label><br>
        </div>
        <div>
            <input type="checkbox" id="cataflan" name="cataflan">
            <label for="cataflan" class="ml-2">Cataflan</label><br>

            <input type="checkbox" id="cloprin" name="cloprin">
            <label for="cloprin" class="ml-2">Cloprin</label><br>

            <input type="checkbox" id="certal" name="certal">
            <label for="certal" class="ml-2">Certal</label><br>

            <input type="checkbox" id="otomodil" name="otomodil">
            <label for="otomodil" class="ml-2">Otomidil</label><br>

            <input type="checkbox" id="histaprin" name="histaprin">
            <label for="histaprin" class="ml-2">Histaprin</label><br>

            <input type="checkbox" id="sal_andrews" name="sal_andrews">
            <label for="sal_andrews" class="ml-2">Sal Andrews</label><br>

            <input type="checkbox" id="cofal_fuerte" name="cofal_fuerte">
            <label for="cofal_fuerte" class="ml-2">Cofal Fuerte</label><br>

            <input type="checkbox" id="nauseol" name="nauseol">
            <label for="nauseol" class="ml-2">Nauseol</label><br>

            <input type="checkbox" id="suero_oral" name="suero_oral">
            <label for="suero_oral" class="ml-2">Suero Oral</label><br>

            <input type="checkbox" id="baselina" name="baselina">
            <label for="baselina" class="ml-2">Baselina / Pasta lasar</label><br>
        </div>
        <div>
            <input type="checkbox" id="irs" name="irs">
            <label for="irs" class="ml-2">IRS</label><br>

            <input type="checkbox" id="acetaminofen2" name="acetaminofen2">
            <label for="acetaminofen2" class="ml-2">Acetaminofén</label><br>

            <input type="checkbox" id="piralvex" name="piralvex">
            <label for="piralvex" class="ml-2">Piralvex</label><br>

            <input type="checkbox" id="alfersurf" name="alfersurf">
            <label for="alfersurf" class="ml-2">Alfersurf</label><br>

            <input type="checkbox" id="loratadina" name="loratadina">
            <label for="loratadina" class="ml-2">Loratadina</label><br>

            <input type="checkbox" id="alka_seltzer" name="alka_seltzer">
            <label for="alka_seltzer" class="ml-2">Alka Seltzer</label><br>

            <input type="checkbox" id="gencloben" name="gencloben">
            <label for="gencloben" class="ml-2">Gencloben</label><br>

            <input type="checkbox" id="nistatina" name="nistatina">
            <label for="nistatina" class="ml-2">Nistatina</label><br>

            <input type="checkbox" id="trilox" name="trilox">
            <label for="trilox" class="ml-2">Trilox Antiasido</label><br>

            <input type="checkbox" id="hidrocortisona" name="hidrocortisona">
            <label for="hidrocortisona" class="ml-2">A. Hidrocortisona</label><br>
        </div>
    </div>

    <!-- Vacunas que tiene -->
    <h3 class="text-xl font-bold mb-4">Vacunas que tiene</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div>
            <input type="checkbox" id="bcg" name="bcg">
            <label for="bcg" class="ml-2">B.C.G. (Tuberculosis)</label><br>

            <input type="checkbox" id="hepatitis_b" name="hepatitis_b">
            <label for="hepatitis_b" class="ml-2">Hepatitis B</label><br>

            <input type="checkbox" id="antigripal" name="antigripal">
            <label for="antigripal" class="ml-2">Antigripal</label><br>

            <input type="checkbox" id="vph" name="vph">
            <label for="vph" class="ml-2">V.P.H (Virus papiloma)</label><br>

            <input type="checkbox" id="rubeola" name="rubeola">
            <label for="rubeola" class="ml-2">Rubeola</label><br>
        </div>
        <div>
            <input type="checkbox" id="dpt" name="dpt">
            <label for="dpt" class="ml-2">D.P.T (Polivalente, triple viral)</label><br>

            <input type="checkbox" id="hepatitis_b2" name="hepatitis_b2">
            <label for="hepatitis_b2" class="ml-2">Hepatitis B</label><br>

            <input type="checkbox" id="varicela" name="varicela">
            <label for="varicela" class="ml-2">Varicela</label><br>

            <input type="checkbox" id="paperas" name="paperas">
            <label for="paperas" class="ml-2">Paperas</label><br>

            <input type="checkbox" id="sarampion" name="sarampion">
            <label for="sarampion" class="ml-2">Sarampión</label><br>
        </div>
        <div>
            <input type="checkbox" id="polio" name="polio">
            <label for="polio" class="ml-2">Polio</label><br>

            <input type="checkbox" id="neumococo" name="neumococo">
            <label for="neumococo" class="ml-2">Neumococo</label><br>

            <input type="checkbox" id="gripe_viral" name="gripe_viral">
            <label for="gripe_viral" class="ml-2">Gripe Viral</label><br>

            <input type="checkbox" id="fiebre_tifoidea" name="fiebre_tifoidea">
            <label for="fiebre_tifoidea" class="ml-2">Fiebre Tifoidea</label><br>

            <input type="checkbox" id="rotavirus" name="rotavirus">
            <label for="rotavirus" class="ml-2">Rotavirus</label><br>
        </div>
    </div>

    <!-- Sección Vacunas Contra Covid-19 (Similar a las anteriores) -->
    <h3 class="text-xl font-bold mb-4">Contra Covid-19</h3>
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="flex flex-col items-center">
            <label class="block font-medium">1ra. Dosis</label>
            <input type="checkbox" id="vacuna1_SI_estudiante" name="vacuna1_SI_estudiante">
        </div>
        <div class="flex flex-col items-center">
            <label class="block font-medium">2da. Dosis</label>
            <input type="checkbox" id="vacuna2_SI_estudiante" name="vacuna2_SI_estudiante">
        </div>
    </div>

    <!-- Sección de Permisos Adicionales -->
<h3 class="text-xl font-bold mb-4">Permisos Adicionales</h3>

<!-- Primera persona autorizada a retirar -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div>
        <label for="autorizo_retirar1_nombre" class="block font-medium">Nombres(*)</label>
        <input type="text" id="autorizo_retirar1_nombre" name="autorizo_retirar1_nombre" placeholder="Byron..." class="mt-1 block w-full p-2 border  rounded" required>
    </div>
    <div>
        <label for="autorizo_retirar1_parentesco" class="block font-medium">Parentesco(*)</label>
        <input type="text" id="autorizo_retirar1_parentesco" name="autorizo_retirar1_parentesco" placeholder="Uncle..." class="mt-1 block w-full p-2 border  rounded" required>
    </div>
    <div>
        <label for="autorizo_retirar1_telefono" class="block font-medium">Teléfono(*)</label>
        <input type="number" id="autorizo_retirar1_telefono" name="autorizo_retirar1_telefono" placeholder="12345678" class="mt-1 block w-full p-2 border rounded" required>
    </div>
</div>

<!-- Segunda persona autorizada a retirar -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div>
        <label for="autorizo_retirar2_nombre" class="block font-medium">Nombres(*)</label>
        <input type="text" id="autorizo_retirar2_nombre" name="autorizo_retirar2_nombre" placeholder="Byron..." class="mt-1 block w-full p-2 border  rounded" required>
    </div>
    <div>
        <label for="autorizo_retirar2_parentesco" class="block font-medium">Parentesco(*)</label>
        <input type="text" id="autorizo_retirar2_parentesco" name="autorizo_retirar2_parentesco" placeholder="Uncle..." class="mt-1 block w-full p-2 border  rounded" required>
    </div>
    <div>
        <label for="autorizo_retirar2_telefono" class="block font-medium">Teléfono(*)</label>
        <input type="number" id="autorizo_retirar2_telefono" name="autorizo_retirar2_telefono" placeholder="12345678" class="mt-1 block w-full p-2 border  rounded" required>
    </div>
</div>

<!-- Tipo de transporte -->
<div class="mb-4">
    <label for="tipo_transporte" class="block font-medium">Tipo de transporte que utiliza para retirarse:(*)</label>
    <select id="tipo_transporte" name="tipo_transporte" class="mt-1 block w-full p-2 border  rounded" required>
        <option value="">Escoger...</option>
        <option value="Bus">Bus</option>
        <option value="Microbús">Microbús</option>
        <option value="Vehículo Familiar">Vehículo Familiar</option>
    </select>
</div>



</div>
<!-- Sección 4: Términos y Condiciones -->
<div id="section5" class="section hidden">
<div class="text-center">
    <h2 class="text-xl font-bold mb-4">Términos y Condiciones</h2>
    <p>
        <a href="#" id="openTermsModal" class="text-blue-600 hover:underline">Leer Términos y Condiciones</a>
    </p>
    <div class="inline-flex items-center justify-center mt-4">
        <input type="checkbox" id="acceptTerms" class="mr-2" disabled>
        <label for="acceptTerms">Acepto los términos y condiciones</label>
    </div>
</div>

                <!-- Campos adicionales -->
                <div id="extraFields" class="hidden mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nombre_encargado" class="block font-medium" >Nombre encargado llenó formulario (*)</label>
                            <input type="text" id="nombre_encargado_lleno_f" name="nombre_encargado" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label for="correo_encargado" class="block font-medium">Correo del encargado (*)</label>
                            <input type="email" id="correo_encargado_lleno_f" name="correo_encargado" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                        </div>
                    </div>
                </div>
            </div>
      

            <!-- Campos adicionales que se habilitan solo después de aceptar los términos -->
         
            <div class="mt-6 flex justify-between">
                <button type="button" id="prevBtn" class="px-4 py-2 bg-gray-500 text-white font-bold rounded hover:bg-gray-700 hidden">Anterior</button>
                <button type="button" id="nextBtn" class="px-4 py-2 bg-orange-600 text-white font-bold rounded hover:bg-orange-700">Siguiente</button>
            </div>

       
        </form>
    </div>

     <!-- Modal de Términos y Condiciones -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <h2 class="text-xl font-bold mb-4">Términos y Condiciones</h2>
            <div class="mb-4">
                <p>Este reglamento contiene información general...</p>
            </div>
            <div>
                <input type="checkbox" id="acceptModalTerms" class="mr-2">
                <label for="acceptModalTerms">Acepto los términos y condiciones</label>
            </div>
            <div class="mt-4 text-right">
                <button id="closeTermsModal" class="px-4 py-2 bg-gray-600 text-white font-bold rounded hover:bg-gray-700" disabled>Cerrar</button>
            </div>
        </div>
    </div>
    <script>
        // Modal de términos y condiciones
        const termsModal = document.getElementById('termsModal');
        const openTermsModalBtn = document.getElementById('openTermsModal');
        const closeTermsModalBtn = document.getElementById('closeTermsModal');
        const acceptModalTermsCheckbox = document.getElementById('acceptModalTerms');
        const acceptTermsCheckbox = document.getElementById('acceptTerms');
        const extraFields = document.getElementById('extraFields');
        const submitBtn = document.getElementById('submitBtn');

        openTermsModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            termsModal.style.display = 'block';
        });

        closeTermsModalBtn.addEventListener('click', () => {
            termsModal.style.display = 'none';
        });

        acceptModalTermsCheckbox.addEventListener('change', () => {
            closeTermsModalBtn.disabled = !acceptModalTermsCheckbox.checked;
        });

        closeTermsModalBtn.addEventListener('click', () => {
            acceptTermsCheckbox.disabled = false;
        });

        acceptTermsCheckbox.addEventListener('change', () => {
            if (acceptTermsCheckbox.checked) {
                extraFields.classList.remove('hidden');
                submitBtn.disabled = false;
            } else {
                extraFields.classList.add('hidden');
                submitBtn.disabled = true;
            }
        });
    </script>

    <script>
        // Controlador de navegación entre secciones
        let currentSection = 1;
        const totalSections = 5;

        document.getElementById('nextBtn').addEventListener('click', () => {
    const currentSectionFields = document.querySelectorAll(`#section${currentSection} [required]`);
    let isValid = true;

    currentSectionFields.forEach(field => {
        if (!field.value) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });

    if (isValid) {
        if (currentSection < totalSections) {
            document.getElementById(`section${currentSection}`).classList.add('hidden');
            currentSection++;
            document.getElementById(`section${currentSection}`).classList.remove('hidden');
            updateButtons();
        } else {
            // Habilita el botón de enviar y somete el formulario
            document.getElementById('admissionForm').submit();
        }
    } else {
        alert('Por favor, complete todos los campos obligatorios antes de continuar.');
    }
});

function updateButtons() {
    document.getElementById('prevBtn').classList.toggle('hidden', currentSection === 1);
    document.getElementById('nextBtn').textContent = currentSection === totalSections ? 'Enviar' : 'Siguiente';
    document.getElementById('submitBtn').classList.toggle('hidden', currentSection !== totalSections);
}


        window.onload = function() {
            // Cargar datos del alumno si están disponibles (simulación)
            const alumnoData = <?php echo json_encode($alumno); ?>;
            if (alumnoData) {
                document.getElementById('nombres_alumno').value = alumnoData.nombres_alumno || '';
                document.getElementById('apellidos_alumno').value = alumnoData.apellidos_alumno || '';
                document.getElementById('correo_alumno').value = alumnoData.correo_alumno || '';
                document.getElementById('familiar_vive').value = alumnoData.familiar_vive || '';
                document.getElementById('nacimiento_alumno').value = alumnoData.nacimiento_alumno || '';
                document.getElementById('grado_alumno').value = alumnoData.grado_alumno || '';
            }
        };
    </script>

    <script>
    // Datos del alumno desde PHP en formato JSON
    const alumnoData = <?php echo json_encode($alumno); ?>;

    // Función para cargar datos en el formulario
    function cargarDatosAlumno() {
        if (alumnoData) {
            // Rellenar los campos del alumno
            document.getElementById('nombres_alumno').value = alumnoData.nombres_alumno || '';
            document.getElementById('apellidos_alumno').value = alumnoData.apellidos_alumno || '';
            document.getElementById('correo_alumno').value = alumnoData.correo_alumno || '';
            document.getElementById('familiar_vive').value = alumnoData.familiar_vive || '';
            document.getElementById('nacimiento_alumno').value = alumnoData.nacimiento_alumno || '';
            document.getElementById('grado_alumno').value = alumnoData.grado_alumno || '';
        }
    }

   
    window.onload = cargarDatosAlumno;
</script>

    <script>
        // Controlador de navegación entre secciones
        let currentSection = 1;
        const totalSections = 4;

        document.getElementById('nextBtn').addEventListener('click', () => {
    // Obtener los campos obligatorios de la sección actual
    const currentSectionFields = document.querySelectorAll(`#section${currentSection} [required]`);
    let isValid = true;

    // Validar si todos los campos obligatorios están completos
    currentSectionFields.forEach(field => {
        if (!field.value) {
            isValid = false;
            field.classList.add('border-red-500');  // Añadir un borde rojo para indicar error
        } else {
            field.classList.remove('border-red-500');  // Remover el borde rojo si está completo
        }
    });

    if (isValid) {
        if (currentSection < totalSections) {
            document.getElementById(`section${currentSection}`).classList.add('hidden');
            currentSection++;
            document.getElementById(`section${currentSection}`).classList.remove('hidden');
            updateButtons();
        } else {
            // Si está en la última sección, enviar el formulario
            document.getElementById('admissionForm').submit();
        }
    } else {
        alert('Por favor, complete todos los campos obligatorios antes de continuar.');
    }
});
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentSection > 1) {
                document.getElementById(`section${currentSection}`).classList.add('hidden');
                currentSection--;
                document.getElementById(`section${currentSection}`).classList.remove('hidden');
                updateButtons();
            }
        });

        function updateButtons() {
            document.getElementById('prevBtn').classList.toggle('hidden', currentSection === 1);
            document.getElementById('nextBtn').textContent = currentSection === totalSections ? 'Enviar' : 'Siguiente';
        }
    </script>
</body>

</html>
