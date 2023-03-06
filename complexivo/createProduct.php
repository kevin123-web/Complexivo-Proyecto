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
   } else {
    $errors[] = "Error al crear el producto perro :v ";
}
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
<div class="navbar navbar-expand-lg navbar-dark bg-dark ">
        <div class="container">
            <a href="#" class="navbar-brand ">
                <strong>Tienda Online Sublimados Quito</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">Catalogo</a>
                    </li>

                    <li class="nav-item">
                        <a href="contacto.php" class="nav-link ">Contacto</a>
                    </li>
                </ul>
                <a href="list_checkout.php" class="btn btn-primary me-2 btn-sm ">
                    Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                </a>

                <?php if (isset($_SESSION['user_id'])) { ?>
                    <div class="dropdown">
                        <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-regular fa-user"></i><?php echo $_SESSION['user_name']; ?></a>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn_session">
                        <li><a class="dropdown-item" href="createProduct.php">Crear Articulo</a></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-success btn-sm "><i class="fa-regular fa-user"></i></i>Ingresar</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

    <!--contenido-->
    <main>
        <div class="container">
            <h2>Crear Articulo</h2>

            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="createProduct.php" method="post" autocomplete="off">
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
                <div class="col-md-6">
                    <label for="activo"><span class="text-danger">*</span>activo</label>
                    <input type="int" name="activo" id="activo" class="form-control" requireda>
                </div>

                <i><b>Nota:</b> Los campos con (*) son obligatorios </i>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
                

            </form>
            

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>