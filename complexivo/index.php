<?php

require 'config/config.php';
require 'config/db.php';
$db = new Database();
$con = $db->conectar();


if (isset($_POST['buscar'])) {
    // Obtenemos el valor del campo de búsqueda y de la categoría seleccionada
    $busqueda = $_POST['busqueda'];
    $categoria = $_POST['categoria'];

    // Preparamos la consulta SQL para buscar los productos que coincidan con el término de búsqueda y la categoría seleccionada
    $sql = $con->prepare("SELECT id, name, price, discount FROM products WHERE activo=1 AND name LIKE '%$busqueda%'");
    if (!empty($categoria)) {
        $sql = $con->prepare("SELECT id, name, price, discount FROM products WHERE activo=1 AND id_categoria = '$categoria' AND name LIKE '%$busqueda%'");
    }

    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si no se ha enviado el formulario de búsqueda, mostramos todos los productos de la categoría seleccionada
    $sql = $con->prepare("SELECT id, name, price, discount FROM products WHERE activo=1");
    if (!empty($categoria)) {
        $sql = $con->prepare("SELECT id, name, price, discount FROM products WHERE activo=1 AND id_categoria = '$categoria'");
    }

    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}







//session_destroy();

//print_r($_SESSION);

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
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/category.css">
    <link rel="stylesheet" href="css/fontawesome-free-6.3.0-web/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <?php include 'menu.php'; ?>



    <!--contenido-->
    <main>
        <form method="POST" action="index.php">
            <select name="categoria">
                <option value="">Todas las categorías</option>
                <?php
                $sql = $con->prepare("SELECT id, name FROM categories");
                $sql->execute();
                $categorias = $sql->fetchAll(PDO::FETCH_ASSOC);
                foreach ($categorias as $categoria) {
                    echo '<option value="' . $categoria['id'] . '">' . $categoria['name'] . '</option>';
                }
                ?>
            </select>
            <input type="text" name="busqueda" placeholder="Buscar productos">
            <button type="submit" name="buscar" class="btn btn-primary">Buscar</button>
        </form>


        <div class="container">

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($resultado as $row) { ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <?php
                            $id = $row['id'];
                            $images = "images/productos/" . $id . "/laptop.jpg"; //agregar a la base de datos

                            if (!file_exists($images)) {
                                $images =  "images/no-photo.jpg"; //nase de datos no mas
                            }
                            ?>
                            <img src="<?php echo $images; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <?php $price_desc = $row['price'] - (($row['price']  * $row['discount']) / 100); ?>
                                <p class="card-text"><?php if ($row['discount'] > 0) { ?>
                                <p><del><?php echo MONEDA . number_format($row['price'], 2, '.', ','); ?></del></p>
                                <h5>
                                    <?php echo MONEDA . number_format($price_desc, 2, '.', ','); ?>
                                    <small class="text-success"><?php echo $row['discount'] ?>% descuento</small>
                                </h5>
                            <?php } else { ?>
                                <h5>
                                    <p><?php echo MONEDA . number_format($row['price'], 2, '.', ','); ?></p>

                                    <small class="text-success"><?php echo $row['discount'] ?>% descuento</small>
                                </h5>
                            <?php } ?></p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                                </div>
                                <button class="btn btn-outline-success" type="button" onclick="addProduct(<?php echo  $row['id']; ?>,
                                    '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar al carrito</button>
                            </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script>
        function addProduct(id, token) {
            let url = 'class/carrito.php';
            let formData = new FormData();
            formData.append('id', id);
            formData.append('token', token);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let element = document.getElementById("num_cart")
                        element.innerHTML = data.number
                    }
                })
        }
    </script>
</body>

</html>