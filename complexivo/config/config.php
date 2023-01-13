<?php

define("KEY_TOKEN" , "APR.wqc-354*");

define("CLIENT_ID" , "AbIQMS6FeSOmuskYuf9eUUhBtZtc2ZH3nZhP8JfxzgGrQpP15KHJaEwpnuBN8rXPW2ZR7cgI3_SjKUhq");
define("CURRENCY" , "USD");


define("MONEDA" ,"$");

session_start();

$num_cart = 0;

if(isset($_SESSION['carrito']['products'])){
    $num_cart = count($_SESSION['carrito']['products']);
}

?>