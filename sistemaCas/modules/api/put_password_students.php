<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido.";
    exit;
}

require_once __DIR__ . '/../../db/connection.php';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

$codigo_alumno = $_POST['codigo_alumno'] ?? null;
$cicloActual   = $_POST['cicloActual']   ?? null;
$grado_alumno  = $_POST['grado_alumno']  ?? null;
$seccion       = $_POST['seccion']       ?? null;
$carnet        = $_POST['carnet']        ?? null;
$correo_alumno = $_POST['correo_alumno'] ?? null;
$id_alumno     = $_POST['id_alumno']     ?? null;
$codigos_bulk  = $_POST['codigos_bulk']  ?? null;

$dryRun = isset($_POST['dryRun']) && ($_POST['dryRun'] === '1' || $_POST['dryRun'] === 'true');

$codigos = [];
if (!empty($codigos_bulk)) {
    foreach (preg_split('/[\r\n,;]+/', $codigos_bulk) as $p) {
        $p = trim($p);
        if ($p !== '') $codigos[] = $p;
    }
}

$where  = ["estado = 1"];
$params = [];
$types  = "";

$add = function(string $cond, $val, string $type) use (&$where,&$params,&$types){
    $where[]  = $cond;
    $params[] = $val;
    $types   .= $type;
};

if (!empty($codigo_alumno)) $add("codigo_alumno = ?", $codigo_alumno, "s");
if (!empty($cicloActual))   $add("cicloActual = ?",   $cicloActual,   "s");
if (!empty($grado_alumno))  $add("grado_alumno = ?",  $grado_alumno,  "s");
if (!empty($seccion))       $add("seccion = ?",       $seccion,       "s");
if (!empty($carnet))        $add("carnet = ?",        $carnet,        "s");
if (!empty($correo_alumno)) $add("correo_alumno = ?", $correo_alumno, "s");
if (!empty($id_alumno))     $add("id_alumno = ?",     $id_alumno,     "i");

if (!empty($codigos)) {
    $place = implode(",", array_fill(0, count($codigos), "?"));
    $where[] = "codigo_alumno IN ($place)";
    foreach ($codigos as $c) { $params[] = $c; $types .= "s"; }
}

$whereSql = "WHERE ".implode(" AND ", $where);

$sqlCount = "SELECT COUNT(*) AS n FROM alumno {$whereSql}";
$stmt = $conn->prepare($sqlCount);
if (!$stmt) {
    $msg = "Error COUNT: ".$conn->error;
    if ($isAjax) { http_response_code(500); echo json_encode(['ok'=>false,'message'=>$msg]); }
    else { echo "<script>alert('{$msg}');history.back();</script>"; }
    exit;
}
if ($types !== "") $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$toAffect = (int)($res?->fetch_assoc()['n'] ?? 0);
$stmt->close();

if ($dryRun || $toAffect === 0) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok'=>true,'message'=>'Vista previa','result'=>['affected'=>$toAffect,'updated'=>0]]);
    } else {
        $msg = $dryRun ? "Vista previa: {$toAffect} posibles afectados." : "No hay registros que cumplan los filtros.";
        echo "<script>alert('{$msg}');history.back();</script>";
    }
    exit;
}

$sqlSelect = "SELECT id_alumno, codigo_alumno FROM alumno {$whereSql}";
$stmtSel = $conn->prepare($sqlSelect);
if ($types !== "") $stmtSel->bind_param($types, ...$params);
$stmtSel->execute();
$res = $stmtSel->get_result();

$updated = 0;
$affected = 0;
$errors = [];

if ($res) {
    $conn->begin_transaction();
    try {
        while ($row = $res->fetch_assoc()) {
            $hash = password_hash($row['codigo_alumno'], PASSWORD_DEFAULT);
            $sqlUpd = "UPDATE alumno SET pass = ? WHERE id_alumno = ?";
            $stmtU = $conn->prepare($sqlUpd);
            if ($stmtU) {
                $stmtU->bind_param("si", $hash, $row['id_alumno']);
                if ($stmtU->execute()) {
                    $updated += $stmtU->affected_rows;
                } else {
                    $errors[] = $stmtU->error;
                }
                $stmtU->close();
            } else {
                $errors[] = $conn->error;
            }
            $affected++;
        }

        if (empty($errors)) {
            $conn->commit();
        } else {
            $conn->rollback();
        }
    } catch (Throwable $e) {
        $conn->rollback();
        $errors[] = $e->getMessage();
    }
}
$stmtSel->close();

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    if (empty($errors)) {
        echo json_encode([
            'ok' => true,
            'message' => 'Actualización completa',
            'result' => [
                'affected' => $affected,
                'updated'  => $updated,
                'note'     => 'La contraseña fue regenerada y encriptada con password_hash.'
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['ok'=>false,'message'=>'Errores al actualizar','errors'=>$errors]);
    }
} else {
    if (empty($errors)) {
        echo "<script>alert('Afectados: {$affected}. Actualizados: {$updated}. Las contraseñas fueron encriptadas correctamente.');window.location.href='../module_user-system/view_students.php';</script>";
    } else {
        $errMsg = implode('; ', $errors);
        echo "<script>alert('Error al actualizar: {$errMsg}');history.back();</script>";
    }
}

$conn->close();