<?php

require 'config/config.php';
require 'config/db.php';
require 'class/clientFunctions.php';



$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if($orden == null || $token == null || $token != $token_session){
    header("Location: buys.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, id_transaction, date, total FROM buys  WHERE id_transaction = ? LIMIT 1");
$sql->execute([$orden]);
$row = $sql->fetch(PDO::FETCH_ASSOC);
$idCompra = $row['id'];

$sqlDetail = $con->prepare("SELECT id, name , price, quantity FROM buys_details  WHERE id_buy = ? ");
$sqlDetail->execute([$idCompra]);






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


    <!--contenido-->
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Detalles de la compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha: </strong><?php echo $row ['date']; ?></p>
                            <p><strong>Orden: </strong><?php echo $row ['id_transaction']; ?></p>
                            <p><strong>Total: </strong><?php echo MONEDA. '' . number_format($row ['total'],2, '.', ',');?></p>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row = $sqlDetail->fetch(PDO::FETCH_ASSOC)){
                                $price = $row['price'];
                                $quantity = $row['quantity'];
                                $subtotal = $price * $quantity;
                                ?>
                                
                                <tr>
                                    <td><?php echo $row ['name']; ?></td>
                                    <td><?php echo MONEDA. '' . number_format($price,2, '.', ',');?></p></td>
                                    <td><?php echo $quantity; ?></td>
                                    <td><?php echo MONEDA. '' . number_format($subtotal,2, '.', ',');?></p></td>

                                </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>


</body>

</html>