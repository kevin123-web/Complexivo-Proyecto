<?php

require 'config/config.php';
require 'config/db.php';
require 'class/clientFunctions.php';


$db = new Database();
$con = $db->conectar();

$errors = [];

if(isset($_POST['enviar'])){

} else{
    $id=$_GET['id'];
    $sql="select * from products where id='".$id."'";
    $resultado= pdo($con,$sql);
    $row= pdo($resultado);
    $name=$row['name'];
    $description=$row['description'];
    $price=$row['price'];
    $discount=$row['discount'];
    $activo=$row['activo'];

    pdo_close($con);
}

$sql = $con->prepare("UPDATE  products SET name=?, description=?, price=?, discount=?, activo=? WHERE id=?");

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
            <h2>Editar Articulo</h2>

            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="editar_producto.php" method="POST" autocomplete="off">

                
                <div class="col-md-6">
                    <label for="name"><span class="text-danger">*</span>Nombres</label>
                    <input type="text" name="name" id="name" value="<?php echo $name; ?>>

                </div>
                
                <div class="col-md-6">
                    <label for="description"><span class="text-danger">*</span>Descripcion</label>
                    <input type="text" name="description" id="description" value="<?php echo $description; ?>>
                </div>

                <div class="col-md-6">
                    <label for="price"><span class="text-danger">*</span>Precio</label>
                    <input type="number" name="price" step="any" id="price" value="<?php echo $price; ?>>
                </div>
                
                <div class="col-md-6">
                    <label for="discount"><span class="text-danger">*</span>Descuento</label>
                    <input type="number" name="discount" id="discount" value="<?php echo $discount; ?>>
                </div>
                
                <div action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image">
                </div>

                <div class="col-md-6">
                    <label for="activo"><span class="text-danger">*</span>activo</label>
                    <input type="int" name="activo" id="activo" value="<?php echo $activo; ?>>
                </div>

                <i><b>Nota:</b> Los campos con (*) son obligatorios </i>

                <div class="col-12">
                <input type="submit" value="Guardar cambios">
                </div>
            </form>
            

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>