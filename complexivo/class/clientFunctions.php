<?php

function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if(strlen(trim($parametro)) < 1 ){
            return true ;
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
    if(strcmp($password , $repassword) === 0){
        return false;
    }
    return true;        
}

function generarToken()
{
    return md5(uniqid(mt_rand(), false));
}

function crearProducto(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO products (name, description, price, discount, id_category, activo) VALUES(
    ?,?,?,?,1,?)");

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
        return true;
    }
    return false;
}

function userExiste($user, $con)
{
    $sql = $con->prepare("SELECT id FROM users WHERE user LIKE ? LIMIT 1");
    $sql->execute([$user]);

    if($sql->fetchColumn() > 0  ){
        return true;
    }
    return false;
}

function cedulaExiste($cedula, $con)
{
    $sql = $con->prepare("SELECT id FROM clients WHERE cedula LIKE ? LIMIT 1");
    $sql->execute([$cedula]);

    if($sql->fetchColumn() > 0  ){
        return true;
    }
    return false;
}

function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clients WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);

    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}

function mostrarMensajes(array $errors)
{
    if(count($errors) > 0){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach($errors as $error){
            echo '<li>'. $error .'</li>';
        }
        echo '<ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>    ';
    }
}
