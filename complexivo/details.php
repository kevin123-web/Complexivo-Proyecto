<?php

require 'config/config.php';
require 'config/db.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'ERROR AL REALIZAR LA SIGUIENTE PETICIÓN';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {
        $sql =  $con->prepare("SELECT count(id) FROM products WHERE id=? AND activo=1");
        $sql->execute([$id]);

        if ($sql->fetchColumn() > 0) {
            $sql =  $con->prepare("SELECT name, description, price, discount FROM products WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $discount = $row['discount'];
            $price_desc = $price - (($price * $discount) / 100);
            $dir_images = 'images/productos/' . $id . '/';

            $rutaImg = $dir_images . 'descarga.jpg';

            if (!file_exists($rutaImg)) {
                $rutaImg = 'images/no-photo.jpg';
            }

            $image = array();
            if (file_exists($dir_images)) {
                $dir = dir($dir_images);

                while (($archivo = $dir->read()) != false) {
                    if ($archivo != 'descarga.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                        $image[] = $dir_images . $archivo;
                    }
                }
                $dir->close();
            }
        }
    } else {
        echo 'ERROR AL REALIZAR LA SIGUIENTE PETICIÓN';
        exit;
    }
}


$sql =  $con->prepare("SELECT id, name , price FROM products WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="css/fontawesome-free-6.3.0-web/css/all.min.css">

</head>

<body>
    <?php include 'menu.php'; ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-5 order-md-1">
                    <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?php echo $rutaImg; ?>">
                            </div>

                            <?php foreach ($image as $img) { ?>
                                <div class="carousel-item">
                                    <img src="<?php echo $img; ?>">
                                </div>
                            <?php } ?>

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-7 order-md-2">
                    <h2><?php echo $name; ?></h2>

                    <?php if ($discount > 0) { ?>
                        <p><del><?php echo MONEDA . number_format($price, 2, '.', ','); ?></del></p>
                        <h2>
                            <?php echo MONEDA . number_format($price_desc, 2, '.', ','); ?>
                            <small class="text-success"><?php echo $discount ?>% descuento</small>
                        </h2>
                    <?php } else { ?>
                        <h2><?php echo MONEDA . number_format($price, 2, '.', ','); ?></h2>
                    <?php } ?>

                    <p class="lead">
                        <?php echo $description; ?>
                    </p>

                    <div class="d-grid gap-3 col-9 mx-30">
                        <a href="list_checkout.php" class="btn btn-primary ">Comprar Ahora</a>
                        <button class="btn btn-outline-primary" type="button" onclick="addProduct(<?php echo $id; ?>,
                        '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                    </div>
                </div>
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