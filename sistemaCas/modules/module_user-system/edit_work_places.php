<?php
session_start();
include '../shared/role_validation.php';
include '../shared/alerts.php';
validateAccess('administracion');

if (isset($_POST['filename'])) {
    $file = '../../src/images/works_places/' . $_POST['filename'];
    if (file_exists($file)) {
        unlink($file);
          echo "<script>
                    alert('Imagen eliminada correctamente.');
                    window.location.href = './view_work_places.php';
                  </script>";
    } else {
           echo "<script>
                    alert('El archivo no existe.');
                    window.location.href = './view_work_places.php';
                  </script>";
    }
}
?>
