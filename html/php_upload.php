<html>
<?php
$tempFile = $_FILES['Filedata']['tmp_name'];
$fileName = $_FILES['Filedata']['name'];
$fileSize = $_FILES['Filedata']['size'];
$ruc = $_POST['cliente'];
$fileName=$ruc;
move_uploaded_file($tempFile, "./img/cliente/$fileName".".jpg");

echo "El logo del cliente de ruc nro: $ruc fue cargado al servidor correctamente";
?>
</html>