<?php

require 'config/config.php';
require 'config/db.php';
$db = new Database();
$con = $db->conectar();

$products =  isset($_SESSION['carrito']['products']) ? $_SESSION['carrito']['products'] : null;

//print_r($_SESSION);

$list_carrito = array();

if ($products != null) {
    foreach ($products as $clave => $quantity) {

        $sql =  $con->prepare("SELECT id, name ,price ,discount ,$quantity AS quantity FROM products 
        WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $list_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}

//session_destroy(); 

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
                                    <td><?php echo MONEDA . number_format($price_desc, 2, '.', ','); ?></td>
                                    <td>
                                        <input type="number" min="1" max="12" step="1" value="<?php echo $quantity ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value , <?php echo $_id; ?>)">
                                    </td>
                                    <td>
                                        <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                    </td>
                                    <td>
                                        <a href="#" id="eliminar" class="btb btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">
                                    <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                </td>
                            </tr>
                    </tbody>
                <?php } ?>
                </table>
            </div>
            <?php if ($list_carrito != null) { ?>
                <div class="row">
                    <div class="col-md-5 offset-md-7 d-grid gap-2">
                        <?php if (isset($_SESSION['user_client'])) { ?>
                            <a href="payment.php" class="btn btn-primary btn-lg">Realizar Pago</a>
                        <?php } else { ?>
                            <a href="login.php?pago" class="btn btn-primary btn-lg">Realizar Pago</a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminaModalLabel">Alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ??Deseas eliminar el producto de tu lista?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!--boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script>
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })


        function actualizaCantidad(quantity, id) {
            let url = 'class/update_carrito.php';
            let formData = new FormData();
            formData.append('action', 'agregar');
            formData.append('quantity', quantity);
            formData.append('id', id);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let divsubtotal = document.getElementById("subtotal_" + id)
                        divsubtotal.innerHTML = data.sub

                        let total = 0.0
                        let list = document.getElementsByName('subtotal[]')

                        for (let i = 0; i < list.length; i++) {
                            total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                        }

                        total = new Intl.NumberFormat('en-US', {
                            minimumFractionDigits: 2
                        }).format(total)
                        document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                    }
                })
        }

        function eliminar() {
            let botonElimina = document.getElementById('btn-elimina')
            let id = botonElimina.value
            let url = 'class/update_carrito.php';
            let formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('id', id);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload()
                    }
                })
        }
    </script>

</body>

</html>