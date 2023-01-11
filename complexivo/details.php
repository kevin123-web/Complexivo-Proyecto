<?php

require 'config/config.php';
require 'config/db.php';
$db = new Database();
$con = $db ->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == ''){
  echo 'ERROR AL REALIZAR LA SIGUIENTE PETICIÓN';
  exit;

}else{
    $token_tmp = hash_hmac('sha1', $id , KEY_TOKEN );
    
    if($token == $token_tmp){
        $sql =  $con->prepare("SELECT count(id) FROM products WHERE id=? AND activo=1");
        $sql ->execute([$id]);
        
        if($sql->fetchColumn() > 0 ){
            $sql =  $con->prepare("SELECT name, description, price FROM products WHERE id=? AND activo=1 LIMIT 1");
            $sql ->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
        }

    }else{
        echo 'ERROR AL REALIZAR LA SIGUIENTE PETICIÓN';
        exit;
    }
}


$sql =  $con ->prepare("SELECT id, name , price FROM products WHERE activo=1");
$sql ->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark ">
            <div class="container">
                <a href="#" class="navbar-brand ">
                    <strong>Tienda Online</strong>
                </a>
                    <button class="navbar-toggler" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarHeader" 
                    aria-controls="navbarHeader" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
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
                        <a href="carrito.php" class="btn btn-primary">Carrito</a>
                </div>
            </div>
        </div>
    </header>

<main>
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-1">
                <img src="images/productos/1/laptop.jpg">
            </div>
            <div clas="col-md6 order-md-1">

            </div>
        </div>
    </div>
</main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
    crossorigin="anonymous">
    </script>
    
</body>
</html>