<?php

require 'config/config.php';
require 'config/db.php';
require 'class/clientFunctions.php';


$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $discount = trim($_POST['discount']);
    $activo = trim($_POST['activo']);

  
    if (count($errors) == 0) {

       $id = crearProducto([$name, $description, $price, $discount ,$activo], $con);

       if ($id > 0) {
        echo 'se a creado correctamente'; 
       } else {
           $errors[] = "Error al crear su Producto";
       }
   }
}


if (isset($_FILES['image'])) {
    $file = $_FILES['image'];
  
    // Verifica si el archivo subido es una imagen
    $image_info = getimagesize($file['tmp_name']);
    if (!$image_info) {
      echo 'El archivo subido no es una imagen';
      return;
    }
  
    // Verifica si el archivo subido está dentro de los límites permitidos
    if ($file['size'] > 1000000) {
      echo 'El archivo subido es demasiado grande';
      return;
    }
  
    // Verifica si no hubo errores al subir el archivo
    if ($file['error'] !== UPLOAD_ERR_OK) {
      echo 'Hubo un error al subir el archivo';
      return;
    }
  
    // Mueve el archivo subido a su destino final
    $destination = 'images/' . $file['name'];
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
      echo 'Hubo un error al mover el archivo subido';
      return;
    }
  
    echo 'La imagen se ha subido correctamente';
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark ">
            <div class="container">
                <a href="#" class="navbar-brand ">
                    <strong>Tienda Online</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Catalogo</a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link ">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!--contenido-->
    <main>
        <div class="container">
            <h2>Crear Articulo</h2>

            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="form.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="name"><span class="text-danger">*</span>Nombres</label>
                    <input type="text" name="name" id="name" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="description"><span class="text-danger">*</span>Descripcion</label>
                    <input type="text" name="description" id="description" class="form-control" requireda>
                </div>

                <div class="col-md-6">
                    <label for="price"><span class="text-danger">*</span>Precio</label>
                    <input type="number" name="price" step="any" id="price" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="discount"><span class="text-danger">*</span>Descuento</label>
                    <input type="number" name="discount" id="discount" class="form-control" requireda>
                </div>
                <div action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image">
                </div>
                <div class="col-md-6">
                    <label for="activo"><span class="text-danger">*</span>activo</label>
                    <input type="int" name="activo" id="activo" class="form-control" requireda>
                </div>

                <i><b>Nota:</b> Los campos con (*) son obligatorios </i>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
            

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>