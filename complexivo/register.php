<?php

require 'config/config.php';
require 'config/db.php';
require 'class/clientFunctions.php';


$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
    $names = trim($_POST['names']);
    $lastnames = trim($_POST['lastnames']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $cedula = trim($_POST['cedula']);
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$names, $lastnames, $email, $phone, $cedula, $user, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "Dirreción de correo no valida";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (userExiste($user, $con)) {
        $errors[] = "El nombre del usuario: $user ya existe";
    }

    if (cedulaExiste($cedula, $con)) {
        $errors[] = "La cedula: $cedula ya esta en uso";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "La dirreción del: $email ya existe";
    }

    if (count($errors) == 0) {

        $id = registrarClient([$names, $lastnames, $email, $phone, $cedula], $con);

        if ($id > 0) {

            require 'class/Mailer.php';
            $mailer = new Mailer();
            $token = generarToken();


            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $idUsuario = registrarUser([$user, $pass_hash, $token, $id], $con);
            if ($idUsuario > 0) {

                $url = SITE_URL . "/activateClient.php?id=" . $idUsuario . '&token=' . $token;
                $asunto = "Activar cuenta - Tienda Sublimados Quito";
                $cuerpo = "Estimado $names:<br> Para continuar con el proceso es importante dar click al siguiente enlace
                <a href='$url'> Activar Cuenta </a> ";

                if ($mailer->sendEmail($email, $asunto, $cuerpo)) {
                    echo "Para continuar el proceso del registro siga las instrucciones que se envio a su correo 
                    electrónico $email";
                    exit;
                }
            } else {
                $errors[] = "Error al registrar el Usuario";
            }
        } else {
            $errors[] = "Error al registrar al Cliente";
        }
    }
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
                    <a href="list_checkout.php" class="btn btn-primary">
                        Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!--contenido-->
    <main>
        <div class="container">
            <h2>Datos del Cliente</h2>

            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="register.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="names"><span class="text-danger">*</span>Nombres</label>
                    <input type="text" name="names" id="names" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="lastnames"><span class="text-danger">*</span>Apellidos</label>
                    <input type="text" name="lastnames" id="lastnames" class="form-control" requireda>
                </div>

                <div class="col-md-6">
                    <label for="email"><span class="text-danger">*</span>Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" requireda>
                    <span id="validateEmail" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="phone"><span class="text-danger">*</span>Celular</label>
                    <input type="tel" name="phone" id="phone" class="form-control" requireda>
                </div>

                <div class="col-md-6">
                    <label for="cedula"><span class="text-danger">*</span>Cedula</label>
                    <input type="text" name="cedula" id="cedula" class="form-control" requireda>
                    <span id="validateCedula" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="user"><span class="text-danger">*</span>Usuario</label>
                    <input type="text" name="user" id="user" class="form-control" requireda>
                    <span id="validateUser" class="text-danger"></span>
                </div>

                <div class="col-md-6">
                    <label for="password"><span class="text-danger">*</span>Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" requireda>
                </div>
                <div class="col-md-6">
                    <label for="repassword"><span class="text-danger">*</span>Repita Contraseña</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" requireda>
                </div>

                <i><b>Nota:</b> Los campos con (*) son obligatorios </i>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script>
        let txtUsuario = document.getElementById('user')
        txtUsuario.addEventListener("blur", function() {
            existeUser(txtUsuario.value)
        }, false)

        let txtEmail = document.getElementById('email')
        txtEmail.addEventListener("blur", function() {
            existeEmail(txtEmail.value)
        }, false)

        let txtCedula = document.getElementById('cedula')
        txtCedula.addEventListener("blur", function() {
            existeCedula(txtCedula.value)
        }, false)

        function existeCedula(cedula) {
            let url = "class/clientAjax.php"
            let formData = new FormData()
            formData.append("actions", "existeCedula")
            formData.append("cedula", cedula)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('cedula').value = ''
                        document.getElementById('validateCedula').innerHTML = 'Cedula no disponible'
                    } else {
                        document.getElementById('validateCedula').innerHTML = ''
                    }
                })
        }

        function existeEmail(email) {
            let url = "class/clientAjax.php"
            let formData = new FormData()
            formData.append("actions", "existeEmail")
            formData.append("email", email)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('email').value = ''
                        document.getElementById('validateEmail').innerHTML = 'Email no disponible'
                    } else {
                        document.getElementById('validateEmail').innerHTML = ''
                    }
                })
        }

        function existeUser(user) {
            let url = "class/clientAjax.php"
            let formData = new FormData()
            formData.append("actions", "existeUser")
            formData.append("user", user)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('user').value = ''
                        document.getElementById('validateUser').innerHTML = 'Usuario no disponible'
                    } else {
                        document.getElementById('validateUser').innerHTML = ''
                    }
                })
        }
    </script>
</body>

</html>