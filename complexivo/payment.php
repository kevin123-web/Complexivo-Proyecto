<?php

require 'config/config.php';
require 'config/db.php';
$db = new Database();
$con = $db->conectar();

$products =  isset($_SESSION['carrito']['products']) ? $_SESSION['carrito']['products'] : null;


$list_carrito = array();

if ($products != null) {
    foreach ($products as $clave => $quantity) {

        $sql =  $con->prepare("SELECT id, name ,price ,discount ,$quantity AS quantity FROM products 
        WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $list_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location:index.php");
    exit;
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
                    <a href="carrito.php" class="btn btn-primary">
                        Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!--contenido-->
    <main>
        <div class="container">

            <div class="row">
                <div class="col-6">
                    <h4>Detalles de Pago</h4>
                    <div id="paypal-button-container"></div>
                </div>

                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($list_carrito == null) {
                                    echo '<tr><td colspan="5" class="text-center"><br>Lista vacia </br></td></tr>';
                                } else {
                                    $total = 0;

                                    foreach ($list_carrito as $product) {
                                        $_id = $product['id'];
                                        $name = $product['name'];
                                        $price = $product['price'];
                                        $discount = $product['discount'];
                                        $quantity = $product['quantity'];
                                        $price_desc = $price - (($price * $discount) / 100);
                                        $subtotal = $quantity * $price_desc;
                                        $total += $subtotal;
                                ?>
                                        <tr>
                                            <td><?php echo $name; ?></td>
                                            <td >
                                                <div  id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr>
                                        <td colspan="2">
                                            <p class="h3 text-end"id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                        </td>
                                    </tr>
                            </tbody>
                        <?php } ?>
                        </table>
                    </div>
                </div>
            </div>      
        </div>
    </main>

    <!--boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!--PayPal-->
    <script src="https://www.paypal.com/sdk/js?client-id=AbIQMS6FeSOmuskYuf9eUUhBtZtc2ZH3nZhP8JfxzgGrQpP15KHJaEwpnuBN8rXPW2ZR7cgI3_SjKUhq&currency=USD"></script>
    
    <!--action PayPal-->
    <script>
    paypal.Buttons({
      style: {
        color: 'blue',
        shape: 'pill',
        label: 'pay'
      },
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: <?php echo $total; ?>
            }
          }]
        });
      },
      onApprove: function(data, actions) {
        let url='class/capture.php' 
        actions.order.capture().then(function(detalles) {

          console.log(detalles)

          return fetch(url,{
            method: 'post',
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify({
                detalles: detalles
            })
          })
        });
      },
      onCancel: function(data) {
        alert("Pago Cancelado")
        console.log(data)
      }
    }).render('#paypal-button-container');
  </script>

</body>

</html>