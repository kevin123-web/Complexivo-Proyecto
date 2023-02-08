<?php


//configuracion del sistema
define("SITE_URL" , "http://localhost/complexivo");
define("KEY_TOKEN" , "APR.wqc-354*");
define("MONEDA" ,"$");  

//configuracion paypal
define("CLIENT_ID" , "AbIQMS6FeSOmuskYuf9eUUhBtZtc2ZH3nZhP8JfxzgGrQpP15KHJaEwpnuBN8rXPW2ZR7cgI3_SjKUhq");
define("CURRENCY" , "USD");


//configuracion correo electronico
define("MAILS_HOST" , "smtp.gmail.com");
define("MAILS_USER" , "kevinmotoche123@gmail.com");
define("MAILS_PASS" , "");
define("MAILS_PORT" , "587");


session_start();


$num_cart = 0;

if(isset($_SESSION['carrito']['products'])){
    $num_cart = count($_SESSION['carrito']['products']);
}

?>