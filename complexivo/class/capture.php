<?php

require '../config/config.php';
require '../config/db.php';
$db = new Database();
$con = $db->conectar();

$json =  file_get_contents('php://input');
$datos = json_decode($json, true);

/*echo '<pre>';
print_r($datos);
echo '</pre>';*/

if (is_array($datos)) {

    $id_client = $_SESSION['user_client'];
    $sql =  $con->prepare("SELECT email  FROM clients WHERE id=? AND status=1");
    $sql->execute([$id_client]);
    $row_client = $sql->fetch(PDO::FETCH_ASSOC);


    $id_transaction = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $date = $datos['detalles']['update_time'];
    $date_new = date('Y-m-d H:i:s', strtotime($date));
    //$email = $datos['detalles']['payer']['email_address'];
    $email = $row_client['email'];
    //$id_client = $datos['detalles']['payer']['payer_id'];


    $sql = $con->prepare("INSERT INTO buys (id_transaction, date, status, email, id_client, total) 
    VALUES(?,?,?,?,?,?)");

    $sql->execute([$id_transaction, $date_new, $status, $email, $id_client, $total]);
    $id = $con->lastInsertId();

    if ($id > 0) {

        $products =  isset($_SESSION['carrito']['products']) ? $_SESSION['carrito']['products'] : null;

        if ($products != null) {
            foreach ($products as $clave => $quantity) {

                $sql =  $con->prepare("SELECT id, name ,price ,discount  FROM products 
                WHERE id=? AND activo=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $price = $row_prod['price'];
                $discount = $row_prod['discount'];
                $price_desc = $price - (($price * $discount) / 100);

                $sql_insert = $con->prepare("INSERT INTO buys_details (id_buy, id_product, name, price, quantity) 
                VALUES(?,?,?,?,?)");

                $sql_insert->execute([$id, $clave, $row_prod['name'], $price_desc, $quantity]);
            }
            require 'Mailer.php';

            $asunto = "Detalles de su compra";
            $cuerpo = '<h4>Gracias por su compra!!</h4>';
            $cuerpo .= '<p>El ID de su compra es <br>'. $id_transaction .'</br> </p>';

            $mailer = new Mailer();
            $mailer->sendEmail($email, $asunto, $cuerpo);
        }
        unset($_SESSION['carrito']);
    }
}
