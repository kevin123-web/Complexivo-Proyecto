<?php

#Salir si alguno de los datos no está presente
if (
	!isset($_POST["name"]) ||
	!isset($_POST["description"]) ||
	!isset($_POST["price"]) ||
	!isset($_POST["discount"]) ||
	!isset($_POST["activo"]) ||
	!isset($_POST["id"])
) exit();

#Si todo va bien, se ejecuta esta parte del código...

include_once "../config/db.php";
$db = new Database();
$con = $db->conectar();

$id = $_POST["id"];
$name  = $_POST["name"];
$description  = $_POST["description"];
$price  = $_POST["price"];
$discount  = $_POST["discount"];
$activo  = $_POST["activo"];


$sentencia = $con->prepare("UPDATE products SET name = ?, description = ?, price = ?, discount = ?, activo = ? WHERE id = ?");
$resultado = $sentencia->execute([$name, $description, $price, $discount, $activo, $id]);

if ($resultado === true) {
	echo "cambios guardados  ";
	echo '<div class="btn-group"><a href="http://localhost/complexivo/admin.php" class="btn btn-primary">regresar</a></div>';
} else {
	echo "Algo salió mal. Por favor verifica que la tabla exista, así como el ID del usuario";
}
