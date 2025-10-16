
<?php
session_start();
include '../../db/connection.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';

validateAccess('administracion');

if (isset($_FILES['image'])) {
    $target_dir = "../../src/images/works_places/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_extensions = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $valid_extensions)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<script>
                    alert('La imagen se ha subido correctamente.');
                    window.location.href = './view_work_places.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al subir la imagen.');
                    window.location.href = './view_work_places.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Formato de archivo no permitido.');
                window.location.href = './view_work_places.php';
              </script>";
    }
}
?>
