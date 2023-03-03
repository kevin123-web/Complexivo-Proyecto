<?php

require 'config/config.php';
require 'config/db.php';
require 'class/clientFunctions.php';


$db = new Database();
$con = $db->conectar();

//print_r($_SESSION);

$token = generarToken();
$_SESSION['token'] = $token;

$id_client = $_SESSION['user_client'];

$sql = $con->prepare("SELECT id_transaction, date, status, total FROM buys 
        WHERE id_client = ? ORDER BY DATE(date) DESC");

$sql->execute([$id_client]);

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
    <main>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h4>Mis Compras</h4>
                </div>
                <hr>
                <?php while ($row = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="card mb-3 border-primary">
                    <div class="card-header">
                        <?php echo $row['date']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Folio: <?php echo $row['id_transaction']; ?></h5>
                        <p class="card-text">Total: <?php echo $row['total']; ?></p>
                        <a href="detailc.php?orden=<?php echo $row['id_transaction']; ?>&token=<?php echo $token; ?>" 
                        class="btn btn-primary">Ver detalle</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>


</body>

</html>