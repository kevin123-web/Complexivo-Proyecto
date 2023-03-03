<?php

function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function esEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function validaPassword($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}

function generarToken()
{
    return md5(uniqid(mt_rand(), false));
}

function registrarClient(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO clients (names, lastnames, email, phone, cedula, status, date_up) VALUES(
    ?,?,?,?,?,1,now())");

    if ($sql->execute($datos)) {
        return $con->lastInsertid();
    }
    return 0;
}

function registrarUser(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO users (user, password, token, id_client) VALUES(
    ?,?,?,?)");

    if ($sql->execute($datos)) {
        return $con->lastInsertid();
    }
    return 0;
}

function userExiste($user, $con)
{
    $sql = $con->prepare("SELECT id FROM users WHERE user LIKE ? LIMIT 1");
    $sql->execute([$user]);

    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function cedulaExiste($cedula, $con)
{
    $sql = $con->prepare("SELECT id FROM clients WHERE cedula LIKE ? LIMIT 1");
    $sql->execute([$cedula]);

    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clients WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);

    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function mostrarMensajes(array $errors)
{
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '<ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>    ';
    }
}

function validarToken($id, $token, $con)
{
    $msg  = "";
    $sql = $con->prepare("SELECT id FROM users WHERE id = ? AND token LIKE ? LIMIT 1");
    $sql->execute([$id, $token]);
    if ($sql->fetchColumn() > 0) {
        if (activarUsario($id, $con)) {
            $msg = "Cuenta Activada.";
            $msg .= "<br>Si quieres iniciar sesión de clik en el siguiente link <a href ='login.php'>Iniciar Sesión</a>";
        } else {
            $msg = "Error al activar la cuenta";
        }
    } else {
        $msg  = "No existe el registro del cliente.";
    }
    return $msg;
}

function activarUsario($id, $con)
{
    $sql = $con->prepare("UPDATE users SET activation = 1 , token = '' WHERE id = ?");
    return $sql->execute([$id]);
}

function login($user, $password, $con, $proceso)
{
    $sql = $con->prepare("SELECT id, user, password, id_client FROM users WHERE user LIKE ? LIMIT 1");
    $sql->execute([$user]);

    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (esActivo($user, $con)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['user'];
                $_SESSION['user_client'] = $row['id_client'];
                if($proceso == 'pago'){
                    header("Location: list_checkout.php");
                }else{
                header("Location: index.php");
                }
                exit;
            }
        } else {
            return 'El usuario no ah sido activado.';
        }
    }
    return 'El usuario y/o contraseña son incorrectas.';
}

function esActivo($user, $con)
{
    $sql = $con->prepare("SELECT activation FROM users WHERE user LIKE ? LIMIT 1");
    $sql->execute([$user]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if ($row['activation'] == 1) {
        return true;
    }
    return false;
}

function solicitaPassword($user_id, $con)
{
    $token = generarToken();

    $sql = $con->prepare("UPDATE users SET token_password = ? , password_request = 1 WHERE id = ? ");
    if ($sql->execute([$token, $user_id])) {
        return $token;
    }
    return null;
}

function verificaTokenRequest($user_id, $con, $token)
{
    $sql = $con->prepare("SELECT id FROM users WHERE id = ? AND token_password  LIKE ? 
    AND password_request = 1 LIMIT 1");
    $sql->execute([$user_id, $token]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function actualizaPassword($user_id, $password, $con,)
{
    $sql = $con->prepare("UPDATE users SET password = ? , token_password = '', password_request = 0 WHERE id = ? ");
    if ($sql->execute([ $password, $user_id])) {
        return true;
    }
    return false;
}
