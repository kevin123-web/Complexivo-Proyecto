<?php
require 'config/config.php';
require 'config/db.php';




if (!isset($_GET["id"])) exit();
$id = $_GET["id"];
$db = new Database();
$con = $db->conectar();

$sentencia = $con->prepare("SELECT * FROM products WHERE id = ?;");
$sentencia->execute([$id]);
$persona = $sentencia->fetch(PDO::FETCH_OBJ);
if ($persona === FALSE) {

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

<header>
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
                        <a href="admin.php" class="nav-link active">Catalogo</a>
                    </li>

                    <li class="nav-item">
                        <a href="contactoAdmin.php" class="nav-link ">Contacto</a>
                    </li>
                </ul>

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
</body>


<style>
    .custom-input {
        width: flex;
    };

</style>
<div style="background-color: white; display: flex; justify-content: center; ">
    <main>
        <form class="form-detalles m-auto pt-4" method="post" action="class/function_updateP.php">
            <!-- Ocultamos el ID para que el usuario no pueda cambiarlo (en teoría) -->
            <input type="hidden" name="id" value="<?php echo $persona->id; ?>">

            <h1>Editar Productos</h1>

            <div class="form-group">
                <label for="name" class="form-label">Nuevo nombre del Producto </label>
                <input type="text" value="<?php echo $persona->name ?>" name="name" class="form-control custom-input" placeholder="escribe el nombre">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Nueva Descripcion del Producto:</label>
                <input type="text" value="<?php echo $persona->description ?>" name="description" class="form-control custom-input" placeholder="escribe la descripcion">
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Nuevo Precio del Producto </label>
                <input type="number" value="<?php echo $persona->price ?>" name="price" class="form-control custom-input" placeholder="escribe el precio" step="any">
            </div>

            <div class="form-group">
                <label for="discount" class="form-label">Nuevo Descuento del Producto </label>
                <input type="number" value="<?php echo $persona->discount ?>" name="discount" class="form-control custom-input" placeholder="escribe el descuento">
            </div>

            <div class="form-group">
                <label for="activo" class="form-label">Activo del Producto </label>
                <input type="number" value="<?php echo $persona->activo ?>" name="activo" class="form-control custom-input" placeholder="escribe el activo">
            </div>



            <br> <input class="btn btn-primary" type="submit" value="Guardar cambios">
        </form>
    </main>

</div>