<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require './conexion.php';
$nuevo_anio = date("Y")+1;

 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $codigo_alumno = $_SESSION['id_alumno']; 
    $carnet = $_POST['carnet'];
    $nombres_alumno = $_POST['nombres_alumno'];
    $apellidos_alumno = $_POST['apellidos_alumno'];
    $correo_alumno = $_POST['correo_alumno'];
    $familiar_vive = $_POST['familiar_vive'];
    $nacimiento_alumno = $_POST['nacimiento_alumno'];
    $grado_alumno = $_POST['grado_alumno'];
    $correo_encargado_lleno_f = $_POST['correo_encargado_lleno_f'];
    $nombre_encargado_lleno_f = $_POST['nombre_encargado_lleno_f'];
    $anioIngresoCas = $_POST['anioIngresoCas'];
    $estadoUsuario = '2';

$sql_alumno = "UPDATE alumno 
               SET
                   carnet = ?, 
                   anioIngresoCas = ?, 
                   nombres_alumno = ?, 
                   apellidos_alumno = ?, 
                   correo_alumno = ?, 
                   familiar_vive = ?, 
                   nacimiento_alumno = ?, 
                   grado_alumno = ?, 
                   cicloActual = ?, 
                   correo_encargado_llenar_form = ?, 
                   encargado_llenar_form = ?, 
                   estado = ?
               WHERE codigo_alumno = ? and cicloActual = '2025'";

$stmt_alumno = $conn->prepare($sql_alumno);
$stmt_alumno->bind_param(
    "iissssssissss", 
    $carnet, 
    $anioIngresoCas, 
    $nombres_alumno, 
    $apellidos_alumno, 
    $correo_alumno, 
    $familiar_vive, 
    $nacimiento_alumno, 
    $grado_alumno, 
    $nuevo_anio, 
    $correo_encargado_lleno_f, 
    $nombre_encargado_lleno_f, 
    $estadoUsuario,
    $codigo_alumno 
);

    if (!$stmt_alumno->execute()) {
        die("Error al insertar en la tabla alumno: " . $stmt_alumno->error);
    }


    if ($stmt_alumno) {

        $actividades_estudiante = $_POST['actividades_estudiante'];
        $enfermedades_estudiante = $_POST['enfermedades_estudiante'];
        $alergias_estudiante = $_POST['alergias_estudiante'];
        $medicamento_diario = $_POST['medicamentos_diario'];
        $tuberculosis = isset($_POST['tuberculosis']) ? 1 : 0;
        $polivalente = isset($_POST['polivalente']) ? 1 : 0;
        $polio = isset($_POST['polio']) ? 1 : 0;
        $hepatitis_b = isset($_POST['hepatitis_b']) ? 1 : 0;
        $hepatitis_a = isset($_POST['hepatitis_a']) ? 1 : 0;
        $neumococo = isset($_POST['neumococo']) ? 1 : 0;
        $antigripal = isset($_POST['antigripal']) ? 1 : 0;
        $varicela = isset($_POST['varicela']) ? 1 : 0;
        $gripe_viral = isset($_POST['gripe_viral']) ? 1 : 0;
        $papiloma = isset($_POST['papiloma']) ? 1 : 0;
        $paperas = isset($_POST['paperas']) ? 1 : 0;
        $fiebre_tifoidea = isset($_POST['fiebre_tifoidea']) ? 1 : 0;
        $rubeola = isset($_POST['rubeola']) ? 1 : 0;
        $sarampion = isset($_POST['sarampion']) ? 1 : 0;
        $rotavirus = isset($_POST['rotavirus']) ? 1 : 0;
        $acetaminofen = isset($_POST['acetaminofen']) ? 1 : 0;
        $cataflan = isset($_POST['cataflan']) ? 1 : 0;
        $irs = isset($_POST['irs']) ? 1 : 0;
        $diclofenaco = isset($_POST['diclofenaco']) ? 1 : 0;
        $aspirina = isset($_POST['aspirina']) ? 1 : 0;
        $cloprin = isset($_POST['cloprin']) ? 1 : 0;
        $peptobismol = isset($_POST['peptobismol']) ? 1 : 0;
        $certal = isset($_POST['certal']) ? 1 : 0;
        $piralvex = isset($_POST['piralvex']) ? 1 : 0;
        $otomidil = isset($_POST['otomidil']) ? 1 : 0;
        $alfersurf = isset($_POST['alfersurf']) ? 1 : 0;
        $fastum = isset($_POST['fastum']) ? 1 : 0;
        $histaprin = isset($_POST['histaprin']) ? 1 : 0;
        $loratadina = isset($_POST['loratadina']) ? 1 : 0;
        $ibuprofeno = isset($_POST['ibuprofeno']) ? 1 : 0;
        $hidrocortisona = isset($_POST['hidrocortisona']) ? 1 : 0;
        $sal_andrews = isset($_POST['sal_andrews']) ? 1 : 0;
        $alka_seltzer = isset($_POST['alka_seltzer']) ? 1 : 0;
        $bromexina = isset($_POST['bromexina']) ? 1 : 0;
        $cofal_fuerte = isset($_POST['cofal_fuerte']) ? 1 : 0;
        $gencloben = isset($_POST['gencloben']) ? 1 : 0;
        $loperamida = isset($_POST['loperamida']) ? 1 : 0;
        $nauseol = isset($_POST['nauseol']) ? 1 : 0;
        $nistatina = isset($_POST['nistatina']) ? 1 : 0;
        $alka_d = isset($_POST['alka_d']) ? 1 : 0;
        $otik = isset($_POST['otik']) ? 1 : 0;
        $sulfacetamida = isset($_POST['sulfacetamida']) ? 1 : 0;
        $ranitidina = isset($_POST['ranitidina']) ? 1 : 0;
        $suero_oral = isset($_POST['suero_oral']) ? 1 : 0;
        $trilox_antiasido = isset($_POST['trilox_antiasido']) ? 1 : 0;
        $tabcin = isset($_POST['tabcin']) ? 1 : 0;
        $pasta_lasar = isset($_POST['pasta_lasar']) ? 1 : 0;

        $sql_historial_medico = "INSERT INTO historial_medico (
            carnet,
            codigo_alumno, 
            actividades_estudiante, 
            enfermedades_estudiante, 
            alergias_estudiante, 
            medicamento_diario, 
            tuberculosis, 
            polivalente, 
            polio, 
            hepatitis_b, 
            hepatitis_a, 
            neumococo, 
            antigripal, 
            varicela, 
            gripe_viral, 
            papiloma, 
            paperas, 
            fiebre_tifoidea, 
            rubeola, 
            sarampion, 
            rotavirus, 
            acetaminofen, 
            cataflan, 
            irs, 
            diclofenaco, 
            aspirina, 
            cloprin, 
            peptobismol, 
            certal, 
            piralvex, 
            otomidil, 
            alfersurf, 
            fastum, 
            histaprin, 
            loratadina, 
            ibuprofeno, 
            hidrocortisona, 
            sal_andrews, 
            alka_seltzer, 
            bromexina, 
            cofal_fuerte, 
            gencloben, 
            loperamida, 
            nauseol, 
            nistatina, 
            alka_d, 
            otik, 
            sulfacetamida, 
            ranitidina, 
            suero_oral, 
            trilox_antiasido, 
            tabcin, 
            pasta_lasar,
            cicloActual
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt_historial_medico = $conn->prepare($sql_historial_medico);

        $stmt_historial_medico->bind_param(
            "isssssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii",
            $carnet,
            $codigo_alumno,
            $actividades_estudiante,
            $enfermedades_estudiante,
            $alergias_estudiante,
            $medicamento_diario,
            $tuberculosis,
            $polivalente,
            $polio,
            $hepatitis_b,
            $hepatitis_a,
            $neumococo,
            $antigripal,
            $varicela,
            $gripe_viral,
            $papiloma,
            $paperas,
            $fiebre_tifoidea,
            $rubeola,
            $sarampion,
            $rotavirus,
            $acetaminofen,
            $cataflan,
            $irs,
            $diclofenaco,
            $aspirina,
            $cloprin,
            $peptobismol,
            $certal,
            $piralvex,
            $otomidil,
            $alfersurf,
            $fastum,
            $histaprin,
            $loratadina,
            $ibuprofeno,
            $hidrocortisona,
            $sal_andrews,
            $alka_seltzer,
            $bromexina,
            $cofal_fuerte,
            $gencloben,
            $loperamida,
            $nauseol,
            $nistatina,
            $alka_d,
            $otik,
            $sulfacetamida,
            $ranitidina,
            $suero_oral,
            $trilox_antiasido,
            $tabcin,
            $pasta_lasar,
            $nuevo_anio
        );;
        echo $sql_historial_medico;

        if (!$stmt_historial_medico->execute()) {
            die("Error al insertar en la tabla alumno: " . $stmt_historial_medico->error);
            echo $sql_historial_medico;
        }
   
        $nombres_madre = $_POST['nombres_madre'];
        $apellidos_madre = $_POST['apellidos_madre'];
        $dpi_madre = $_POST['dpi_madre'];
        $nit_madre = $_POST['nit_madre'];
        $estado_civil_madre = $_POST['estado_civil_madre'];
        $nacionalidad_madre = $_POST['nacionalidad_madre'];
        $profesion_madre = $_POST['profesion_madre'];
        $departamento_madre = $_POST['departamento_madre'];
        $municipio_madre = $_POST['municipio_madre'];
        $direccion_madre = $_POST['direccion_madre'];
        $telefonocasa_madre = $_POST['telefonocasa_madre'];
        $celular_madre = $_POST['celular_madre'];
        $correo_madre = $_POST['correo_madre'];
        $empresalabora_madre = $_POST['empresalabora_madre'];
        $cargoenepresa_madre = $_POST['cargoenepresa_madre'];
        $departamentoempresa_madre = $_POST['departamentoempresa_madre'];
        $municipio_empresa_madre = $_POST['municipio_empresa_madre'];
        $direccion_empresa_madre = $_POST['direccion_empresa_madre'];
        $telefono_empresa_madre = $_POST['telefono_empresa_madre'];
        $correo_empresa_madre = $_POST['correo_empresa_madre'];

        $sql_madre = "INSERT INTO madre (carnet,codigo_alumno, nombres_madre, apellidos_madre, dpi_madre, nit_madre, estado_civil_madre, nacionalidad_madre, profesion_madre, departamento_madre, municipio_madre, direccion_madre, telefonocasa_madre, celular_madre, correo_madre, empresalabora_madre, cargoenepresa_madre, departamentoempresa_madre, municipio_empresa_madre, direccion_empresa_madre, telefono_empresa_madre, correo_empresa_madre,cicloActual) 
                      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt_madre = $conn->prepare($sql_madre);
        $stmt_madre->bind_param(
            "isssssssssssiissssssisi",
            $carnet,
            $codigo_alumno,
            $nombres_madre,
            $apellidos_madre,
            $dpi_madre,
            $nit_madre,
            $estado_civil_madre,
            $nacionalidad_madre,
            $profesion_madre,
            $departamento_madre,
            $municipio_madre,
            $direccion_madre,
            $telefonocasa_madre,
            $celular_madre,
            $correo_madre,
            $empresalabora_madre,
            $cargoenepresa_madre,
            $departamentoempresa_madre,
            $municipio_empresa_madre,
            $direccion_empresa_madre,
            $telefono_empresa_madre,
            $correo_empresa_madre,
            $nuevo_anio
        );
        if (!$stmt_madre->execute()) {
            die("Error al insertar en la tabla alumno: " . $stmt_madre->error);
        }

        $nombres_padre = $_POST['nombres_padre'];
        $apellidos_padre = $_POST['apellidos_padre'];
        $dpi_padre = $_POST['dpi_padre'];
        $nit_padre = $_POST['nit_padre'];
        $estado_civil_padre = $_POST['estado_civil_padre'];
        $nacionalidad_padre = $_POST['nacionalidad_padre'];
        $profesion_padre = $_POST['profesion_padre'];
        $departamento_padre = $_POST['departamento_padre'];
        $municipio_padre = $_POST['municipio_padre'];
        $direccion_padre = $_POST['direccion_padre'];
        $telefonocasa_padre = $_POST['telefonocasa_padre'];
        $celular_padre = $_POST['celular_padre'];
        $correo_padre = $_POST['correo_padre'];
        $empresalabora_padre = $_POST['empresalabora_padre'];
        $cargoenepresa_padre = $_POST['cargoenepresa_padre'];
        $departamentoempresa_padre = $_POST['departamentoempresa_padre'];
        $municipio_empresa_padre = $_POST['municipio_empresa_padre'];
        $direccion_empresa_padre = $_POST['direccion_empresa_padre'];
        $telefono_empresa_padre = $_POST['telefono_empresa_padre'];
        $correo_empresa_padre = $_POST['correo_empresa_padre'];

        $sql_padre = "INSERT INTO padre (carnet, codigo_alumno, nombres_padre, apellidos_padre, dpi_padre, nit_padre, estado_civil_padre, nacionalidad_padre, profesion_padre, departamento_padre, municipio_padre, direccion_padre, telefonocasa_padre, celular_padre, correo_padre, empresalabora_padre, cargoenepresa_padre, departamentoempresa_padre, municipio_empresa_padre, direccion_empresa_padre, telefono_empresa_padre, correo_empresa_padre,cicloActual) 
                      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt_padre = $conn->prepare($sql_padre);
        $stmt_padre->bind_param(
            "isssssssssssiissssssisi",
            $carnet,
            $codigo_alumno,
            $nombres_padre,
            $apellidos_padre,
            $dpi_padre,
            $nit_padre,
            $estado_civil_padre,
            $nacionalidad_padre,
            $profesion_padre,
            $departamento_padre,
            $municipio_padre,
            $direccion_padre,
            $telefonocasa_padre,
            $celular_padre,
            $correo_padre,
            $empresalabora_padre,
            $cargoenepresa_padre,
            $departamentoempresa_padre,
            $municipio_empresa_padre,
            $direccion_empresa_padre,
            $telefono_empresa_padre,
            $correo_empresa_padre,
            $nuevo_anio
        );

        if (!$stmt_padre->execute()) {
            die("Error al insertar en la tabla alumno: " . $stmt_padre->error);
        }

        $terceros1_nombre = $_POST['autorizo_retirar1_nombre'];
        $terceros1_parentesco = $_POST['autorizo_retirar1_parentesco'];
        $terceros1_telefono = $_POST['autorizo_retirar1_telefono'];
        $terceros2_nombre = $_POST['autorizo_retirar2_nombre'];
        $terceros2_parentesco = $_POST['autorizo_retirar2_parentesco'];
        $terceros2_telefono = $_POST['autorizo_retirar2_telefono'];

        $sql_terceros = "INSERT INTO terceros (carnet,codigo_alumno, terceros1_retiran_alumno, terceros1_retiran_alumno_parentesco, terceros1_retiran_alumno_telefono, terceros2_retiran_alumno, terceros2_retiran_alumno_parentesco, terceros2_retiran_alumno_telefono,cicloActual) 
                         VALUES (?,?, ?, ?, ?, ?, ?, ?,?)";
        $stmt_terceros = $conn->prepare($sql_terceros);
        $stmt_terceros->bind_param("isssissii", $carnet, $codigo_alumno, $terceros1_nombre, $terceros1_parentesco, $terceros1_telefono, $terceros2_nombre, $terceros2_parentesco, $terceros2_telefono, $nuevo_anio);

        if (!$stmt_terceros->execute()) {
            die("Error al insertar en la terceros: " . $stmt_terceros->error);
        }
        $sql_update_estado = "UPDATE alumno_nuevo_ingreso SET estado = 3 WHERE codigo_alumno = ?";
        $stmt_update_estado = $conn->prepare($sql_update_estado);
        $stmt_update_estado->bind_param("s", $codigo_alumno);

        if ($stmt_update_estado->execute()) {
            
        $_SESSION['grado_alumno'] = $grado_alumno;
        $_SESSION['nombres_alumno'] = $nombres_alumno ;
        $_SESSION['correo_encargado_lleno_f'] = $correo_encargado_lleno_f;
        error_log("Correo encargado: " . $_SESSION['correo_encargado_lleno_f']);
        $_SESSION['nombre_encargado_lleno_f'] = $nombre_encargado_lleno_f;

            echo "Estado actualizado correctamente.";

            header("Location: ./complete.php");
            exit();
        } else {
            echo "Error al actualizar el estado: " . $stmt_update_estado->error;
        }
    } else {
        echo "Error al insertar los datos: " . $conn->error;
    }


    $stmt_historial_medico->close();
    $stmt_madre->close();
    $stmt_padre->close();
    $stmt_terceros->close();
    $conn->close();
}