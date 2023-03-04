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
	
	echo "¡No existe ningun producto con ese ID!";
	exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>editar producto</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
     
</body>

<main >
	<form class="form-detalles m-auto pt-4" method="post" action="class/editar_producto.php">
		<!-- Ocultamos el ID para que el usuario no pueda cambiarlo (en teoría) -->
		<input type="hidden" name="id" value="<?php echo $persona->id; ?>">
	
	<h1>Editar Productos</h1>

	<div class="mb-3">
      <label for="name" class="form-label">Nuevo nombre del Producto </label>
      <input type="text" value="<?php echo $persona->name?>" name="name" class="form-control" placeholder="escribe el nombre">
    </div>
		
	<div class="mb-3">
		<label for="description" class="form-label">Nueva Descripcion del Producto:</label>
		<input type="text" value="<?php echo $persona->description?>" name="description" class="form-control" placeholder="escribe la descripcion">
	</div>

	<div class="mb-3">
      <label for="price" class="form-label">Nuevo Precio del Producto </label>
      <input type="number" value="<?php echo $persona->price?>" name="price" class="form-control" placeholder="escribe el precio" step="any">
    </div>

	<div class="mb-3">
      <label for="discount" class="form-label">Nuevo Descuento del Producto </label>
      <input type="number" value="<?php echo $persona->discount?>" name="discount" class="form-control" placeholder="escribe el descuento">
    </div>

	<div class="mb-3">
      <label for="activo" class="form-label">Activo del Producto </label>
      <input type="number" value="<?php echo $persona->activo?>" name="activo" class="form-control" placeholder="escribe el activo">
    </div>


    
<input class = "btn btn-primary" type="submit" value="Guardar cambios">
</form>
</main>

