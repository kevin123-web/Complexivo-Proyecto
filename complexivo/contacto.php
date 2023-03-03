<?php

require 'config/config.php';
require 'config/db.php';


$db = new Database();
$con = $db->conectar();


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
<?php include 'menu.php'; ?>


    <!--contenido-->
    <div class="container">
        <h2>SOMOS</h2>
        <p>Somos una expendedora de sublimados que nos enfocamos a dar un servicio unico ya que podras 
            personalizar tu vasos, almohdas, colchas, etc una variedad que esta a tu alcance atraves de nuestra tienda 
            online has tu pedido y no te quedes atras.
        </p>
        <h3>Ubicación</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7689598855422!2d-78.57114193540563!3d-0.28500409922432224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d59f530e547163%3A0x6b7a113c08fe30be!2sEl%20Transito%2C%20Quito%20170105!5e0!3m2!1ses!2sec!4v1677877769937!5m2!1ses!2sec" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <h3>Contactos para mas información</h3>
        <p>Numeros: 0988372390</p>
        </div>
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

   
</body>

</html>