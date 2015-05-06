
<?php
$tempFile = $_FILES['Filedata']['tmp_name'];
$fileName = $_FILES['Filedata']['name'];
$fileSize = $_FILES['Filedata']['size'];
$proyectoid = $_POST['proyectoid'];
$codigo = $_POST['codigo'];
$fileName=$proyectoid."-HH";
move_uploaded_file($tempFile, "./upload/proyecto/$fileName".".xls");
//echo "El logo del cliente de ruc nro: $proyectoid fue cargado al servidor correctamente";
?>
 <script> 
 var proyectoid='<?php echo $_POST['proyectoid']; ?>';
 var codigo='<?php echo $_POST['codigo']; ?>';

                    alert("Propuesta Cargada de Horas en el Servidor");
                    document.location.href="/proyecto/index/subirpropuesta/proyectoid/"+proyectoid+"/codigo_prop_proy/"+codigo+"/bandera/S";                                                 
                </script>


