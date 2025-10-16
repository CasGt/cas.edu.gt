<?php
session_start();

if (!isset($_SESSION['id_alumno'])) {
    header('Location: index.php');
    exit();
}

// CSRF
if (empty($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}


$year = date("Y") + 1;

require './conexion.php';

$sql = "SELECT 
    -- Información del alumno
    alumno.nombres_alumno,
    alumno.apellidos_alumno,
    alumno.correo_alumno,
    alumno.carnet,
    alumno.familiar_vive,
    alumno.nacimiento_alumno,
    alumno.grado_alumno,

    -- Historial médico
    historial_medico.actividades_estudiante,
    historial_medico.enfermedades_estudiante,
    historial_medico.alergias_estudiante,
    historial_medico.medicamento_diario,
    historial_medico.vacuna1_SI_alumno,
    historial_medico.nombre_vacuna1_alumno,
    historial_medico.vacuna2_SI_alumno,
    historial_medico.nombre_vacuna2_alumno,
    historial_medico.tuberculosis,
    historial_medico.polivalente,
    historial_medico.polio,
    historial_medico.hepatitis_b,
    historial_medico.hepatitis_a,
    historial_medico.neumococo,
    historial_medico.antigripal,
    historial_medico.varicela,
    historial_medico.gripe_viral,
    historial_medico.papiloma,
    historial_medico.paperas,
    historial_medico.fiebre_tifoidea,
    historial_medico.rubeola,
    historial_medico.sarampion,
    historial_medico.rotavirus,
    historial_medico.acetaminofen,
    historial_medico.cataflan,
    historial_medico.irs,
    historial_medico.diclofenaco,
    historial_medico.aspirina,
    historial_medico.cloprin,
    historial_medico.peptobismol,
    historial_medico.certal,
    historial_medico.piralvex,
    historial_medico.otomidil,
    historial_medico.alfersurf,
    historial_medico.fastum,
    historial_medico.histaprin,
    historial_medico.loratadina,
    historial_medico.ibuprofeno,
    historial_medico.hidrocortisona,
    historial_medico.sal_andrews,
    historial_medico.alka_seltzer,
    historial_medico.bromexina,
    historial_medico.cofal_fuerte,
    historial_medico.gencloben,
    historial_medico.loperamida,
    historial_medico.nauseol,
    historial_medico.nistatina,
    historial_medico.alka_d,
    historial_medico.otik,
    historial_medico.sulfacetamida,
    historial_medico.ranitidina,
    historial_medico.suero_oral,
    historial_medico.trilox_antiasido,
    historial_medico.tabcin,
    historial_medico.pasta_lasar,

    -- Información de la madre
    madre.nombres_madre,
    madre.apellidos_madre,
    madre.dpi_madre,
    madre.nit_madre,
    madre.estado_civil_madre,
    madre.nacionalidad_madre,
    madre.vacuna1_SI_madre,
    madre.nombre_vacuna1_madre,
    madre.vacuna2_SI_madre,
    madre.nombre_vacuna2_madre,
    madre.profesion_madre,
    madre.departamento_madre,
    madre.municipio_madre,
    madre.direccion_madre,
    madre.telefonocasa_madre,
    madre.celular_madre,
    madre.correo_madre,
    madre.empresalabora_madre,
    madre.cargoenepresa_madre,
    madre.departamentoempresa_madre,
    madre.municipio_empresa_madre,
    madre.direccion_empresa_madre,
    madre.telefono_empresa_madre,
    madre.correo_empresa_madre,

    -- Información del padre
    padre.nombres_padre,
    padre.apellidos_padre,
    padre.dpi_padre,
    padre.nit_padre,
    padre.estado_civil_padre,
    padre.nacionalidad_padre,
    padre.vacuna1_SI_padre,
    padre.nombre_vacuna1_padre,
    padre.vacuna2_SI_padre,
    padre.nombre_vacuna2_padre,
    padre.profesion_padre,
    padre.departamento_padre,
    padre.municipio_padre,
    padre.direccion_padre,
    padre.telefonocasa_padre,
    padre.celular_padre,
    padre.correo_padre,
    padre.empresalabora_padre,
    padre.cargoenepresa_padre,
    padre.departamentoempresa_padre,
    padre.municipio_empresa_padre,
    padre.direccion_empresa_padre,
    padre.telefono_empresa_padre,
    padre.correo_empresa_padre,

    -- Terceros
    terceros.terceros1_retiran_alumno,
    terceros.terceros1_retiran_alumno_parentesco,
    terceros.terceros1_retiran_alumno_telefono,
    terceros.terceros2_retiran_alumno,
    terceros.terceros2_retiran_alumno_parentesco,
    terceros.terceros2_retiran_alumno_telefono

FROM alumno
LEFT JOIN historial_medico ON alumno.codigo_alumno = historial_medico.codigo_alumno
LEFT JOIN madre ON alumno.codigo_alumno = madre.codigo_alumno
LEFT JOIN padre ON alumno.codigo_alumno = padre.codigo_alumno
LEFT JOIN terceros ON alumno.codigo_alumno = terceros.codigo_alumno
WHERE alumno.codigo_alumno = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Error al preparar la consulta: ' . $conn->error);
}
$codigo_alumno = $_SESSION['id_alumno'];
$stmt->bind_param("s", $codigo_alumno);
$stmt->execute();

$stmt->bind_result(
    $nombres_alumno,$apellidos_alumno,$correo_alumno,$carnet,$familiar_vive,$nacimiento_alumno,$grado_alumno,
    $actividades_estudiante,$enfermedades_estudiante,$alergias_estudiante,$medicamento_diario,
    $vacuna1_SI_alumno,$nombre_vacuna1_alumno,$vacuna2_SI_alumno,$nombre_vacuna2_alumno,
    $tuberculosis,$polivalente,$polio,$hepatitis_b,$hepatitis_a,$neumococo,$antigripal,$varicela,$gripe_viral,$papiloma,$paperas,$fiebre_tifoidea,$rubeola,$sarampion,$rotavirus,
    $acetaminofen,$cataflan,$irs,$diclofenaco,$aspirina,$cloprin,$peptobismol,$certal,$piralvex,$otomidil,$alfersurf,$fastum,$histaprin,$loratadina,$ibuprofeno,$hidrocortisona,$sal_andrews,$alka_seltzer,$bromexina,$cofal_fuerte,$gencloben,$loperamida,$nauseol,$nistatina,$alka_d,$otik,$sulfacetamida,$ranitidina,$suero_oral,$trilox_antiasido,$tabcin,$pasta_lasar,
    $nombres_madre,$apellidos_madre,$dpi_madre,$nit_madre,$estado_civil_madre,$nacionalidad_madre,$vacuna1_SI_madre,$nombre_vacuna1_madre,$vacuna2_SI_madre,$nombre_vacuna2_madre,$profesion_madre,$departamento_madre,$municipio_madre,$direccion_madre,$telefonocasa_madre,$celular_madre,$correo_madre,$empresalabora_madre,$cargoenepresa_madre,$departamentoempresa_madre,$municipio_empresa_madre,$direccion_empresa_madre,$telefono_empresa_madre,$correo_empresa_madre,
    $nombres_padre,$apellidos_padre,$dpi_padre,$nit_padre,$estado_civil_padre,$nacionalidad_padre,$vacuna1_SI_padre,$nombre_vacuna1_padre,$vacuna2_SI_padre,$nombre_vacuna2_padre,$profesion_padre,$departamento_padre,$municipio_padre,$direccion_padre,$telefonocasa_padre,$celular_padre,$correo_padre,$empresalabora_padre,$cargoenepresa_padre,$departamentoempresa_padre,$municipio_empresa_padre,$direccion_empresa_padre,$telefono_empresa_padre,$correo_empresa_padre,
    $terceros1_retiran_alumno,$terceros1_retiran_alumno_parentesco,$terceros1_retiran_alumno_telefono,$terceros2_retiran_alumno,$terceros2_retiran_alumno_parentesco,$terceros2_retiran_alumno_telefono
);

if ($stmt->fetch()) {
    $alumno = [
   
        'nombres_alumno'=>$nombres_alumno,'apellidos_alumno'=>$apellidos_alumno,'correo_alumno'=>$correo_alumno,
        'carnet'=>$carnet,'familiar_vive'=>$familiar_vive,'nacimiento_alumno'=>$nacimiento_alumno,'grado_alumno'=>$grado_alumno,

        'nombres_padre'=>$nombres_padre,'apellidos_padre'=>$apellidos_padre,'dpi_padre'=>$dpi_padre,'nit_padre'=>$nit_padre,
        'estado_civil_padre'=>$estado_civil_padre,'nacionalidad_padre'=>$nacionalidad_padre,'departamento_padre'=>$departamento_padre,
        'municipio_padre'=>$municipio_padre,'direccion_padre'=>$direccion_padre,

        'telefono_padre'=>$telefonocasa_padre,
        'celular_padre'=>$celular_padre,'correo_personal_padre'=>$correo_padre,'profesion_padre'=>$profesion_padre,
        'puesto_trabajo_padre'=>$cargoenepresa_padre,'nombre_empresa_padre'=>$empresalabora_padre,
        'telefono_empresa_padre'=>$telefono_empresa_padre,'departamento_empresa_padre'=>$departamentoempresa_padre,
        'municipio_empresa_padre'=>$municipio_empresa_padre,'direccion_exacta_empresa_padre'=>$direccion_empresa_padre,
        'correo_corporativo_padre'=>$correo_empresa_padre,

        'nombres_madre'=>$nombres_madre,'apellidos_madre'=>$apellidos_madre,'dpi_madre'=>$dpi_madre,'nit_madre'=>$nit_madre,
        'estado_civil_madre'=>$estado_civil_madre,'nacionalidad_madre'=>$nacionalidad_madre,'departamento_madre'=>$departamento_madre,
        'municipio_madre'=>$municipio_madre,'direccion_madre'=>$direccion_madre,'telefonocasa_madre'=>$telefonocasa_madre,
        'celular_madre'=>$celular_madre,'correo_madre'=>$correo_madre,'profesion_madre'=>$profesion_madre,
        'cargoenepresa_madre'=>$cargoenepresa_madre,'empresalabora_madre'=>$empresalabora_madre,
        'telefono_empresa_madre'=>$telefono_empresa_madre,'departamentoempresa_madre'=>$departamentoempresa_madre,
        'municipio_empresa_madre'=>$municipio_empresa_madre,'direccion_empresa_madre'=>$direccion_empresa_madre,
        'correo_empresa_madre'=>$correo_empresa_madre,

        'actividades_estudiante'=>$actividades_estudiante,'enfermedades_estudiante'=>$enfermedades_estudiante,
        'alergias_estudiante'=>$alergias_estudiante,'medicamentos_diario'=>$medicamento_diario,

        'acetaminofen'=>$acetaminofen,'cataflan'=>$cataflan,'irs'=>$irs,'diclofenaco'=>$diclofenaco,'aspirina'=>$aspirina,
        'cloprin'=>$cloprin,'peptobismol'=>$peptobismol,'certal'=>$certal,'piralvex'=>$piralvex,'otomidil'=>$otomidil,'alfersurf'=>$alfersurf,
        'fastum'=>$fastum,'histaprin'=>$histaprin,'loratadina'=>$loratadina,'ibuprofeno'=>$ibuprofeno,'hidrocortisona'=>$hidrocortisona,
        'sal_andrews'=>$sal_andrews,'alka_seltzer'=>$alka_seltzer,'bromexina'=>$bromexina,'cofal_fuerte'=>$cofal_fuerte,'gencloben'=>$gencloben,
        'loperamida'=>$loperamida,'nauseol'=>$nauseol,'nistatina'=>$nistatina,'alka_d'=>$alka_d,'otik'=>$otik,'sulfacetamida'=>$sulfacetamida,
        'ranitidina'=>$ranitidina,'suero_oral'=>$suero_oral,'trilox_antiasido'=>$trilox_antiasido,'tabcin'=>$tabcin,'pasta_lasar'=>$pasta_lasar,

        'hepatitis_b'=>$hepatitis_b,'antigripal'=>$antigripal,'varicela'=>$varicela,'paperas'=>$paperas,'sarampion'=>$sarampion,
        'polio'=>$polio,'neumococo'=>$neumococo,'gripe_viral'=>$gripe_viral,'fiebre_tifoidea'=>$fiebre_tifoidea,'rotavirus'=>$rotavirus,

        'autorizo_retirar1_nombre'=>$terceros1_retiran_alumno,
        'autorizo_retirar1_parentesco'=>$terceros1_retiran_alumno_parentesco,
        'autorizo_retirar1_telefono'=>$terceros1_retiran_alumno_telefono,
        'autorizo_retirar2_nombre'=>$terceros2_retiran_alumno,
        'autorizo_retirar2_parentesco'=>$terceros2_retiran_alumno_parentesco,
        'autorizo_retirar2_telefono'=>$terceros2_retiran_alumno_telefono,

        'tipo_transporte'=>''
    ];
} else {
    echo "No se encontraron datos para el alumno.";
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario <?php echo htmlspecialchars($year); ?> Admisiones</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .modal{display:none;position:fixed;z-index:50;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5)}
    .modal-content{background:#fff;margin:10% auto;padding:20px;border:1px solid #888;width:80%;max-width:600px;border-radius:10px}
    .hidden{display:none}
  </style>
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-4xl mx-auto bg-white p-8 shadow-lg">
    <div class="flex justify-center mb-6">
      <img src="./assets/img/logo_cas_red.webp" alt="Logo CAS" class="w-28 h-auto">
    </div>

    <h1 class="text-3xl font-bold mb-4 text-center"><?php echo htmlspecialchars($year); ?> Admisiones</h1>
    <p class="text-center text-gray-600 mb-8">Esta información nos permitirá saber más sobre usted como estudiante.</p>

    <form id="admissionForm" action="guardar_datos.php" method="POST" class="space-y-6" novalidate>
      <input type="hidden" name="form_token" value="<?php echo htmlspecialchars($_SESSION['form_token']); ?>">

      <div id="section1" class="section">
        <h2 class="text-xl font-bold mb-4">Información básica del estudiante</h2>
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
            <label for="carnet" class="block font-medium">Carnet</label>
            <input type="number" id="carnet" name="carnet" class="mt-1 block w-full p-2 border border-gray-300 rounded">
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
            <label for="anioIngresoCas" class="block font-medium">Año en que ingresó o ingresará por primera vez a CAS: (*)</label>
            <input type="number" id="anioIngresoCas" name="anioIngresoCas" placeholder="Si es primer ingreso colocar el año en el que va a ingresar" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="grado_alumno" class="block font-medium">Grado en <?php echo htmlspecialchars($year); ?> (*)</label>
            <select id="grado_alumno" name="grado_alumno" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
              <option value="">Seleccione...</option>
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

      <div id="section2" class="section hidden">
        <h2 class="text-xl font-bold mb-4">Información del Padre</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="nombres_padre" class="block font-medium">Nombre(s) (*)</label>
            <input type="text" id="nombres_padre" name="nombres_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="apellidos_padre" class="block font-medium">Apellidos (*)</label>
            <input type="text" id="apellidos_padre" name="apellidos_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="dpi_padre" class="block font-medium">D.P.I. (*)</label>
            <input type="number" id="dpi_padre" name="dpi_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="nit_padre" class="block font-medium">NIT</label>
            <input type="text" id="nit_padre" name="nit_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
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
            <label for="nacionalidad_padre" class="block font-medium">Nacionalidad (*)</label>
            <input type="text" id="nacionalidad_padre" name="nacionalidad_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>

          <div class="col-span-2"><h3 class="text-xl font-bold mb-4">Dirección del hogar</h3></div>
          <div>
            <label for="departamento_padre" class="block font-medium">Departamento (*)</label>
            <input type="text" id="departamento_padre" name="departamento_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="municipio_padre" class="block font-medium">Municipio (*)</label>
            <input type="text" id="municipio_padre" name="municipio_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div class="col-span-2">
            <label for="direccion_padre" class="block font-medium">Dirección (*)</label>
            <input type="text" id="direccion_padre" name="direccion_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="telefono_padre" class="block font-medium">Teléfono</label>
            <input type="number" id="telefono_padre" name="telefono_padre" placeholder="Ej. 78820000" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div>
            <label for="celular_padre" class="block font-medium">Celular (*)</label>
            <input type="number" id="celular_padre" name="celular_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" max="99999999" required>
          </div>
          <div class="col-span-2">
            <label for="correo_personal_padre" class="block font-medium">Correo Personal (*)</label>
            <input type="email" id="correo_personal_padre" name="correo_personal_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>

          <div class="col-span-2"><h3 class="text-xl font-bold mb-4">Dirección de la Empresa</h3></div>
          <div>
            <label for="profesion_padre" class="block font-medium">Profesión (*)</label>
            <input type="text" id="profesion_padre" name="profesion_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="puesto_trabajo_padre" class="block font-medium">Puesto que labora</label>
            <input type="text" id="puesto_trabajo_padre" name="puesto_trabajo_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="col-span-2">
            <label for="nombre_empresa_padre" class="block font-medium">Nombre de la empresa</label>
            <input type="text" id="nombre_empresa_padre" name="nombre_empresa_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div>
            <label for="telefono_empresa_padre" class="block font-medium">Teléfono de la empresa</label>
            <input type="number" id="telefono_empresa_padre" name="telefono_empresa_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded" max="99999999">
          </div>
          <div>
            <label for="departamento_empresa_padre" class="block font-medium">Departamento</label>
            <input type="text" id="departamento_empresa_padre" name="departamento_empresa_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div>
            <label for="municipio_empresa_padre" class="block font-medium">Municipio</label>
            <input type="text" id="municipio_empresa_padre" name="municipio_empresa_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="col-span-2">
            <label for="direccion_exacta_empresa_padre" class="block font-medium">Dirección exacta de la empresa</label>
            <input type="text" id="direccion_exacta_empresa_padre" name="direccion_exacta_empresa_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="col-span-2">
            <label for="correo_corporativo_padre" class="block font-medium">Correo Corporativo</label>
            <input type="email" id="correo_corporativo_padre" name="correo_corporativo_padre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
        </div>
      </div>

      <div id="section3" class="section hidden">
        <h2 class="text-xl font-bold mb-4">Información de la Madre</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="nombres_madre" class="block font-medium">Nombre(s) (*)</label>
            <input type="text" id="nombres_madre" name="nombres_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="apellidos_madre" class="block font-medium">Apellidos (*)</label>
            <input type="text" id="apellidos_madre" name="apellidos_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="dpi_madre" class="block font-medium">D.P.I. (*)</label>
            <input type="number" id="dpi_madre" name="dpi_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="nit_madre" class="block font-medium">NIT</label>
            <input type="text" id="nit_madre" name="nit_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
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
            <label for="nacionalidad_madre" class="block font-medium">Nacionalidad (*)</label>
            <input type="text" id="nacionalidad_madre" name="nacionalidad_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>

          <div class="col-span-2"><h3 class="text-xl font-bold mb-4">Dirección del hogar</h3></div>
          <div>
            <label for="departamento_madre" class="block font-medium">Departamento (*)</label>
            <input type="text" id="departamento_madre" name="departamento_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="municipio_madre" class="block font-medium">Municipio (*)</label>
            <input type="text" id="municipio_madre" name="municipio_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div class="col-span-2">
            <label for="direccion_madre" class="block font-medium">Dirección (*)</label>
            <input type="text" id="direccion_madre" name="direccion_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="telefonocasa_madre" class="block font-medium">Teléfono</label>
            <input type="number" id="telefonocasa_madre" name="telefonocasa_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" max="99999999">
          </div>
          <div>
            <label for="celular_madre" class="block font-medium">Celular (*)</label>
            <input type="number" id="celular_madre" name="celular_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" max="99999999" required>
          </div>
          <div class="col-span-2">
            <label for="correo_madre" class="block font-medium">Correo Personal (*)</label>
            <input type="email" id="correo_madre" name="correo_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>

          <div class="col-span-2"><h3 class="text-xl font-bold mb-4">Dirección de la Empresa</h3></div>
          <div>
            <label for="profesion_madre" class="block font-medium">Profesión (*)</label>
            <input type="text" id="profesion_madre" name="profesion_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="cargoenepresa_madre" class="block font-medium">Puesto que labora</label>
            <input type="text" id="cargoenepresa_madre" name="cargoenepresa_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="col-span-2">
            <label for="empresalabora_madre" class="block font-medium">Nombre de la empresa</label>
            <input type="text" id="empresalabora_madre" name="empresalabora_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div>
            <label for="telefono_empresa_madre" class="block font-medium">Teléfono de la empresa</label>
            <input type="number" id="telefono_empresa_madre" name="telefono_empresa_madre" placeholder="Ej. 23240000" class="mt-1 block w-full p-2 border border-gray-300 rounded" max="99999999">
          </div>
          <div>
            <label for="departamentoempresa_madre" class="block font-medium">Departamento</label>
            <input type="text" id="departamentoempresa_madre" name="departamentoempresa_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div>
            <label for="municipio_empresa_madre" class="block font-medium">Municipio</label>
            <input type="text" id="municipio_empresa_madre" name="municipio_empresa_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="col-span-2">
            <label for="direccion_empresa_madre" class="block font-medium">Dirección exacta de la empresa</label>
            <input type="text" id="direccion_empresa_madre" name="direccion_empresa_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="col-span-2">
            <label for="correo_empresa_madre" class="block font-medium">Correo Corporativo</label>
            <input type="email" id="correo_empresa_madre" name="correo_empresa_madre" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
        </div>
      </div>

      <div id="section4" class="section hidden">
        <h2 class="text-xl font-bold mb-4">Información importante</h2>
        <div class="grid grid-cols-1 gap-4 mb-8">
          <div>
            <label for="actividades_estudiante" class="block font-medium">Actividades especiales que realiza el alumno</label>
            <input type="text" id="actividades_estudiante" name="actividades_estudiante" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div>
            <label for="enfermedades_estudiante" class="block font-medium">Condiciones o enfermedades que tiene mi hijo (*)</label>
            <input type="text" id="enfermedades_estudiante" name="enfermedades_estudiante" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="medicamentos_diario" class="block font-medium">Medicamentos que usa diariamente (*)</label>
            <input type="text" id="medicamentos_diario" name="medicamentos_diario" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="alergias_estudiante" class="block font-medium">Alergias que sufre mi hijo (*)</label>
            <input type="text" id="alergias_estudiante" name="alergias_estudiante" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
          </div>
          <div>
            <label for="evaluacion_estudiante" class="block font-medium">Tiene alguna evaluación psicológica, física y / o conductual (en caso positivo describir)</label>
            <input type="text" id="evaluacion_estudiante" name="evaluacion_estudiante" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
          <div>
            <label for="intervencion_estudiante" class="block font-medium">Ha sido intervenido quirúrgicamente (en caso positivo describir)</label>
            <input type="text" id="intervencion_estudiante" name="intervencion_estudiante" class="mt-1 block w-full p-2 border border-gray-300 rounded">
          </div>
        </div>

        <h3 class="text-xl font-bold mb-4">Doy autorización para proporcionar</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
          <div>
            <input type="checkbox" id="acetaminofen" name="acetaminofen"><label for="acetaminofen" class="ml-2">Acetaminofén</label><br>
            <input type="checkbox" id="diclofenaco" name="diclofenaco"><label for="diclofenaco" class="ml-2">Diclofenaco</label><br>
            <input type="checkbox" id="aspirina" name="aspirina"><label for="aspirina" class="ml-2">Aspirina</label><br>
            <input type="checkbox" id="peptobismol" name="peptobismol"><label for="peptobismol" class="ml-2">Peptobismol</label><br>
            <input type="checkbox" id="fastum" name="fastum"><label for="fastum" class="ml-2">Fastum</label><br>
            <input type="checkbox" id="ibuprofeno" name="ibuprofeno"><label for="ibuprofeno" class="ml-2">Ibuprofeno</label><br>
            <input type="checkbox" id="bromexina" name="bromexina"><label for="bromexina" class="ml-2">Bromexina</label><br>
            <input type="checkbox" id="loperamida" name="loperamida"><label for="loperamida" class="ml-2">Loperamida</label><br>
            <input type="checkbox" id="ranitidina" name="ranitidina"><label for="ranitidina" class="ml-2">Ranitidina</label><br>
            <input type="checkbox" id="tabcin" name="tabcin"><label for="tabcin" class="ml-2">Tabcin</label><br>
          </div>
          <div>
            <input type="checkbox" id="cataflan" name="cataflan"><label for="cataflan" class="ml-2">Cataflan</label><br>
            <input type="checkbox" id="cloprin" name="cloprin"><label for="cloprin" class="ml-2">Cloprin</label><br>
            <input type="checkbox" id="certal" name="certal"><label for="certal" class="ml-2">Certal</label><br>
            <input type="checkbox" id="otomodil" name="otomodil"><label for="otomodil" class="ml-2">Otomidil</label><br>
            <input type="checkbox" id="histaprin" name="histaprin"><label for="histaprin" class="ml-2">Histaprin</label><br>
            <input type="checkbox" id="sal_andrews" name="sal_andrews"><label for="sal_andrews" class="ml-2">Sal Andrews</label><br>
            <input type="checkbox" id="cofal_fuerte" name="cofal_fuerte"><label for="cofal_fuerte" class="ml-2">Cofal Fuerte</label><br>
            <input type="checkbox" id="nauseol" name="nauseol"><label for="nauseol" class="ml-2">Nauseol</label><br>
            <input type="checkbox" id="suero_oral" name="suero_oral"><label for="suero_oral" class="ml-2">Suero Oral</label><br>
            <input type="checkbox" id="pasta_lasar" name="pasta_lasar"><label for="pasta_lasar" class="ml-2">Baselina / Pasta lasar</label><br>
          </div>
          <div>
            <input type="checkbox" id="irs" name="irs"><label for="irs" class="ml-2">IRS</label><br>
            <input type="checkbox" id="piralvex" name="piralvex"><label for="piralvex" class="ml-2">Piralvex</label><br>
            <input type="checkbox" id="alfersurf" name="alfersurf"><label for="alfersurf" class="ml-2">Alfersurf</label><br>
            <input type="checkbox" id="loratadina" name="loratadina"><label for="loratadina" class="ml-2">Loratadina</label><br>
            <input type="checkbox" id="alka_seltzer" name="alka_seltzer"><label for="alka_seltzer" class="ml-2">Alka Seltzer</label><br>
            <input type="checkbox" id="gencloben" name="gencloben"><label for="gencloben" class="ml-2">Gencloben</label><br>
            <input type="checkbox" id="nistatina" name="nistatina"><label for="nistatina" class="ml-2">Nistatina</label><br>
            <input type="checkbox" id="trilox" name="trilox"><label for="trilox" class="ml-2">Trilox Antiasido</label><br>
            <input type="checkbox" id="hidrocortisona" name="hidrocortisona"><label for="hidrocortisona" class="ml-2">A. Hidrocortisona</label><br>
          </div>
        </div>

        <h3 class="text-xl font-bold mb-4">Vacunas que tiene</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
          <div>
            <input type="checkbox" id="hepatitis_b" name="hepatitis_b"><label for="hepatitis_b" class="ml-2">Hepatitis B</label><br>
            <input type="checkbox" id="antigripal" name="antigripal"><label for="antigripal" class="ml-2">Antigripal</label><br>
            <input type="checkbox" id="rubeola" name="rubeola"><label for="rubeola" class="ml-2">Rubeola</label><br>
          </div>
          <div>
            <input type="checkbox" id="varicela" name="varicela"><label for="varicela" class="ml-2">Varicela</label><br>
            <input type="checkbox" id="paperas" name="paperas"><label for="paperas" class="ml-2">Paperas</label><br>
            <input type="checkbox" id="sarampion" name="sarampion"><label for="sarampion" class="ml-2">Sarampión</label><br>
          </div>
          <div>
            <input type="checkbox" id="polio" name="polio"><label for="polio" class="ml-2">Polio</label><br>
            <input type="checkbox" id="neumococo" name="neumococo"><label for="neumococo" class="ml-2">Neumococo</label><br>
            <input type="checkbox" id="gripe_viral" name="gripe_viral"><label for="gripe_viral" class="ml-2">Gripe Viral</label><br>
            <input type="checkbox" id="fiebre_tifoidea" name="fiebre_tifoidea"><label for="fiebre_tifoidea" class="ml-2">Fiebre Tifoidea</label><br>
            <input type="checkbox" id="rotavirus" name="rotavirus"><label for="rotavirus" class="ml-2">Rotavirus</label><br>
          </div>
        </div>

        <h3 class="text-xl font-bold mb-4">Personas autorizadas para retirar</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label for="autorizo_retirar1_nombre" class="block font-medium">Nombres(*)</label>
            <input type="text" id="autorizo_retirar1_nombre" name="autorizo_retirar1_nombre" class="mt-1 block w-full p-2 border rounded" required>
          </div>
          <div>
            <label for="autorizo_retirar1_parentesco" class="block font-medium">Parentesco(*)</label>
            <input type="text" id="autorizo_retirar1_parentesco" name="autorizo_retirar1_parentesco" class="mt-1 block w-full p-2 border rounded" required>
          </div>
          <div>
            <label for="autorizo_retirar1_telefono" class="block font-medium">Teléfono(*)</label>
            <input type="number" id="autorizo_retirar1_telefono" name="autorizo_retirar1_telefono" class="mt-1 block w-full p-2 border rounded" max="99999999" required>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label for="autorizo_retirar2_nombre" class="block font-medium">Nombres(*)</label>
            <input type="text" id="autorizo_retirar2_nombre" name="autorizo_retirar2_nombre" class="mt-1 block w-full p-2 border rounded" required>
          </div>
          <div>
            <label for="autorizo_retirar2_parentesco" class="block font-medium">Parentesco(*)</label>
            <input type="text" id="autorizo_retirar2_parentesco" name="autorizo_retirar2_parentesco" class="mt-1 block w-full p-2 border rounded" required>
          </div>
          <div>
            <label for="autorizo_retirar2_telefono" class="block font-medium">Teléfono(*)</label>
            <input type="number" id="autorizo_retirar2_telefono" name="autorizo_retirar2_telefono" class="mt-1 block w-full p-2 border  rounded" max="99999999" required>
          </div>
        </div>

        <div class="mb-4">
          <label for="tipo_transporte" class="block font-medium">Tipo de transporte que utiliza para retirarse:(*)</label>
          <select id="tipo_transporte" name="tipo_transporte" class="mt-1 block w-full p-2 border rounded" required>
            <option value="">Escoger...</option>
            <option value="Bus">Bus</option>
            <option value="Microbús">Microbús</option>
            <option value="Vehículo Familiar">Vehículo Familiar</option>
          </select>
        </div>
      </div>

      <div id="section5" class="section hidden">
        <div class="text-center">
          <h2 class="text-xl font-bold mb-4">Términos y Condiciones</h2>
          <p><a href="#" id="openTermsModal" class="text-blue-600 hover:underline" target="_blank">Leer Términos y Condiciones</a></p>
          <div class="inline-flex items-center justify-center mt-4">
            <input type="checkbox" id="acceptTerms" class="mr-2" disabled>
            <label for="acceptTerms">Acepto los términos y condiciones</label>
          </div>
        </div>

        <div id="extraFields" class="hidden mt-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="nombre_encargado_lleno_f" class="block font-medium">Nombre encargado llenó formulario (*)</label>
              <input type="text" id="nombre_encargado_lleno_f" name="nombre_encargado_lleno_f" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div>
              <label for="correo_encargado_lleno_f" class="block font-medium">Correo del encargado (*)</label>
              <input type="email" id="correo_encargado_lleno_f" name="correo_encargado_lleno_f" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
            </div>
          </div>
        </div>
      </div>

    <div class="mt-6 flex items-center justify-between gap-2">
  <a href="./cerrar_sesion.php"
     class="px-4 py-2 bg-gray-300 text-gray-900 font-bold rounded hover:bg-gray-400"
     onclick="return confirm('¿Seguro que deseas cancelar y salir?');">
    Cancelar
  </a>

  <div class="flex items-center gap-2">
    <button type="button" id="prevBtn"
            class="px-4 py-2 bg-gray-500 text-white font-bold rounded hover:bg-gray-700 hidden">
      Anterior
    </button>
    <button type="button" id="nextBtn"
            class="px-4 py-2 bg-orange-600 text-white font-bold rounded hover:bg-orange-700">
      Siguiente
    </button>
  </div>
</div>
    </form>
  </div>

  <div id="termsModal" class="modal">
    <div class="modal-content">
      <h2 class="text-xl font-bold mb-4">Términos y Condiciones</h2>
      <div class="mb-4">
        <p>Este reglamento contiene información general:
          <a href="https://docs.google.com/document/d/e/2PACX-1vSXddC7NqToGP2r31shELljsflBY-SGThpaAr7D1IGnR0qvMHiGy5HpBzBBtyGuf9j0OvkrAOuDPG6k/pub?urp=gmail_link" class="text-blue-500 hover:underline" target="_blank">Ir a reglamento</a>
        </p>
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
    const termsModal = document.getElementById('termsModal');
    const openTermsModalBtn = document.getElementById('openTermsModal');
    const closeTermsModalBtn = document.getElementById('closeTermsModal');
    const acceptModalTermsCheckbox = document.getElementById('acceptModalTerms');
    const acceptTermsCheckbox = document.getElementById('acceptTerms');
    const extraFields = document.getElementById('extraFields');

    openTermsModalBtn.addEventListener('click', (e) => {
      e.preventDefault();
      termsModal.style.display = 'block';
    });
    closeTermsModalBtn.addEventListener('click', () => {
      termsModal.style.display = 'none';
      acceptTermsCheckbox.disabled = false;
    });
    acceptModalTermsCheckbox.addEventListener('change', () => {
      closeTermsModalBtn.disabled = !acceptModalTermsCheckbox.checked;
    });
    acceptTermsCheckbox.addEventListener('change', () => {
      extraFields.classList.toggle('hidden', !acceptTermsCheckbox.checked);
    });

    let currentSection = 1;
    const totalSections = 5;
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    function updateButtons() {
      prevBtn.classList.toggle('hidden', currentSection === 1);
      nextBtn.textContent = (currentSection === totalSections) ? 'Enviar' : 'Siguiente';
    }
    updateButtons();

    function validateCurrentSection() {
      const req = document.querySelectorAll(`#section${currentSection} [required]`);
      let ok = true;
      req.forEach(f => {
        const empty = (f.type === 'checkbox') ? !f.checked : !String(f.value || '').trim();
        f.classList.toggle('border-red-500', empty);
        if (empty) ok = false;
      });
      return ok;
    }

    nextBtn.addEventListener('click', () => {
      if (!validateCurrentSection()) {
        alert('Por favor, complete todos los campos obligatorios antes de continuar.');
        return;
      }
      if (currentSection < totalSections) {
        document.getElementById(`section${currentSection}`).classList.add('hidden');
        currentSection++;
        document.getElementById(`section${currentSection}`).classList.remove('hidden');
        updateButtons();
      } else {
        document.getElementById('admissionForm').submit();
      }
    });

    prevBtn.addEventListener('click', () => {
      if (currentSection === 1) return;
      document.getElementById(`section${currentSection}`).classList.add('hidden');
      currentSection--;
      document.getElementById(`section${currentSection}`).classList.remove('hidden');
      updateButtons();
    });

    window.addEventListener('load', () => {
      const alumno = <?php echo json_encode($alumno, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?> || {};
      const map = {

        nombres_alumno:'nombres_alumno', apellidos_alumno:'apellidos_alumno',
        correo_alumno:'correo_alumno', carnet:'carnet', familiar_vive:'familiar_vive',
        nacimiento_alumno:'nacimiento_alumno', grado_alumno:'grado_alumno',

        nombres_padre:'nombres_padre', apellidos_padre:'apellidos_padre',
        dpi_padre:'dpi_padre', nit_padre:'nit_padre', estado_civil_padre:'estado_civil_padre',
        nacionalidad_padre:'nacionalidad_padre', departamento_padre:'departamento_padre',
        municipio_padre:'municipio_padre', direccion_padre:'direccion_padre',
        telefono_padre:'telefono_padre', celular_padre:'celular_padre',
        correo_personal_padre:'correo_personal_padre',
        profesion_padre:'profesion_padre', puesto_trabajo_padre:'puesto_trabajo_padre',
        nombre_empresa_padre:'nombre_empresa_padre', telefono_empresa_padre:'telefono_empresa_padre',
        departamento_empresa_padre:'departamento_empresa_padre', municipio_empresa_padre:'municipio_empresa_padre',
        direccion_exacta_empresa_padre:'direccion_exacta_empresa_padre', correo_corporativo_padre:'correo_corporativo_padre',

        nombres_madre:'nombres_madre', apellidos_madre:'apellidos_madre',
        dpi_madre:'dpi_madre', nit_madre:'nit_madre', estado_civil_madre:'estado_civil_madre',
        nacionalidad_madre:'nacionalidad_madre', departamento_madre:'departamento_madre',
        municipio_madre:'municipio_madre', direccion_madre:'direccion_madre',
        telefonocasa_madre:'telefonocasa_madre', celular_madre:'celular_madre',
        correo_madre:'correo_madre', profesion_madre:'profesion_madre',
        cargoenepresa_madre:'cargoenepresa_madre', empresalabora_madre:'empresalabora_madre',
        telefono_empresa_madre:'telefono_empresa_madre', departamentoempresa_madre:'departamentoempresa_madre',
        municipio_empresa_madre:'municipio_empresa_madre', direccion_empresa_madre:'direccion_empresa_madre',
        correo_empresa_madre:'correo_empresa_madre',

        actividades_estudiante:'actividades_estudiante',
        enfermedades_estudiante:'enfermedades_estudiante',
        medicamentos_diario:'medicamentos_diario',
        alergias_estudiante:'alergias_estudiante',
        evaluacion_estudiante:'evaluacion_estudiante',
        intervencion_estudiante:'intervencion_estudiante',

        autorizo_retirar1_nombre:'autorizo_retirar1_nombre',
        autorizo_retirar1_parentesco:'autorizo_retirar1_parentesco',
        autorizo_retirar1_telefono:'autorizo_retirar1_telefono',
        autorizo_retirar2_nombre:'autorizo_retirar2_nombre',
        autorizo_retirar2_parentesco:'autorizo_retirar2_parentesco',
        autorizo_retirar2_telefono:'autorizo_retirar2_telefono',
        tipo_transporte:'tipo_transporte'
      };
      Object.entries(map).forEach(([k, id]) => {
        const el = document.getElementById(id);
        if (el && alumno[k] != null) el.value = alumno[k];
      });

      const checks = [
        'acetaminofen','diclofenaco','aspirina','peptobismol','fastum','ibuprofeno','bromexina',
        'loperamida','ranitidina','tabcin','cataflan','irs','cloprin','certal','piralvex',
        'otomidil','alfersurf','histaprin','loratadina','hidrocortisona','sal_andrews',
        'alka_seltzer','cofal_fuerte','gencloben','nauseol','nistatina','alka_d','otik',
        'sulfacetamida','suero_oral','trilox_antiasido','pasta_lasar',
        'hepatitis_b','antigripal','varicela','paperas','sarampion','polio','neumococo','gripe_viral','fiebre_tifoidea','rotavirus'
      ];
      checks.forEach(id => {
        const el = document.getElementById(id);
        if (el && alumno[id] != null) el.checked = (String(alumno[id]) === '1');
      });
    });
  </script>
</body>
</html>