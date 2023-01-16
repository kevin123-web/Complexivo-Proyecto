<?php

require 'config/config.php';
require 'config/db.php';
$db = new Database();
$con = $db->conectar();

$id_trasaction = isset($_GET['key']) ? $_GET['key'] : '0';

$error = '';

if ($id_trasaction == '') {
    $error = 'Error al procesar la petición';
} else {

    $sql =  $con->prepare("SELECT count(id) FROM buys WHERE id_transaction=? AND status=?");
    $sql->execute([$id_trasaction, 'COMPLETED']);

    if ($sql->fetchColumn() > 0) {
        $sql =  $con->prepare("SELECT id, date, email, total FROM buys WHERE id_transaction=? AND status=? LIMIT 1");
        $sql->execute([$id_trasaction, 'COMPLETED']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $idBuy = $row['id'];
        $total = $row['total'];
        $date = $row['date'];

        $sqlDet = $con->prepare("SELECT name, price, quantity FROM buys_details WHERE id_buy=?");
        $sqlDet->execute([$idBuy]);
    } else {
        $error = 'Error al comprobar la compra';
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
                    <a href="list_checkout.php" class="btn btn-primary">
                        Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if (strlen($error) > 0) { ?>
                <div class="row">
                    <div class="col">
                        <h3><?php echo $error; ?></h3>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col">
                        <b>Folio de compra: </b><?php echo $id_trasaction; ?><br>
                        <b>Fecha de compra: </b><?php echo $date; ?><br>
                        <b>Total: </b><?php echo  MONEDA . number_format($total, 2, '.', ','); ?><br>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                    $importe = $row_det['price'] * $row_det['quantity'] ?>
                                    <tr>
                                        <td><?php echo $row_det['quantity'] ?></td>
                                        <td><?php echo $row_det['name'] ?></td>
                                        <td><?php echo MONEDA . number_format($importe, 2, '.', ',') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

</html>