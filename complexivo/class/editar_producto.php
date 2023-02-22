<?php

#Salir si alguno de los datos no está presente
if(
	!isset($_POST["name"]) || 
	!isset($_POST["id"])
) exit();

#Si todo va bien, se ejecuta esta parte del código...

include_once "../config/db.php";
$id = $_POST["id"];
$name  = $_POST["name"];
$db = new Database();
$con = $db->conectar();

$sentencia = $con->prepare("UPDATE products SET name = ? WHERE id = ?;");
$resultado = $sentencia->execute([$name, $id]); # Pasar en el mismo orden de los ?

if($resultado === true ) {
echo "cambios guardados perro :v ";
echo '<div class="btn-group"><a href="http://localhost/Complexivo-Proyecto/complexivo/" class="btn btn-primary">regresar</a></div>';

}else echo "Algo salió mal. Por favor verifica que la tabla exista, así como el ID del usuario";
?>

