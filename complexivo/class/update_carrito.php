<?php

require '../config/config.php';
require '../config/db.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action == 'agregar') {
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
        $respuesta = agregar($id, $quantity) ;

        if($respuesta > 0){
            $datos['ok'] = true ;
        }else{
            $datos['ok'] = false;   
        }
        $datos['sub'] = MONEDA . number_format($respuesta,2 ,'.', ',');
    }else if ($action == 'eliminar'){
        $datos['ok'] = eliminar($id);
    } else{
        $datos['ok'] = false;
    }
}else{
    $datos['ok'] = false;
}

echo json_encode($datos);

function agregar($id, $quantity)
{
    $res = 0;
    if ($id > 0 && $quantity > 0 && is_numeric(($quantity))) {
        if (isset($_SESSION['carrito']['products'][$id])) {
            $_SESSION['carrito']['products'][$id] = $quantity;

            $db = new Database();
            $con = $db->conectar();
            $sql =  $con->prepare("SELECT  price, discount FROM products WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $price = $row['price'];
            $discount = $row['discount'];
            $price_desc = $price - (($price * $discount) / 100);
            $res = $quantity * $price_desc;

            return $res;
        }
    }else{
        return $res;    
    }
}

function eliminar($id)
{
    if($id > 0){
        if(isset($_SESSION['carrito']['products'][$id])){
            unset($_SESSION['carrito']['products'][$id]);
            return true;
        }
    }else{
        return false;
    }
}