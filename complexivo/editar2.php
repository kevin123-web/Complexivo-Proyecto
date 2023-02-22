<?php
require 'config/config.php';
require 'config/db.php';


if(!isset($_GET["id"])) exit();
$id = $_GET["id"];
$db = new Database();
$con = $db->conectar();
$sentencia = $con->prepare("SELECT * FROM products WHERE id = ?;");
$sentencia->execute([$id]);
$persona = $sentencia->fetch(PDO::FETCH_OBJ);
if($persona === FALSE){
	#No existe
	echo "¡No existe ningun producto  con ese ID!";
	exit();
}

#Si la persona existe, se ejecuta esta parte del código
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>editar producto</title>
</head>
<body>
	<form method="post" action="class/editar_producto.php">
		<!-- Ocultamos el ID para que el usuario no pueda cambiarlo (en teoría) -->
		<input type="hidden" name="id" value="<?php echo $persona->id; ?>">

		<label for="name">Nombre:</label>
		<br>
		<input value="<?php echo $persona->name?>" name="name" required type="text" id="nombre" placeholder="Escribe tu nombre...">
		<br>
		<br><br><input type="submit" value="Guardar cambios">
	</form>
</body>
