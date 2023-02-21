<?php

require_once '../config/db.php';
require_once 'clientFunctions.php';

$datos = [];

if (isset($_POST['actions'])) {
    $action = $_POST['actions'];
    $db = new Database();
    $con = $db->conectar();

    if ($action == "existeUser") {
        $datos['ok'] = userExiste($_POST['user'], $con);

    } elseif ($action == 'existeEmail') {
        $datos['ok'] = emailExiste($_POST['email'], $con);

    } elseif ($action == 'existeCedula') {
        $datos['ok'] = cedulaExiste($_POST['cedula'], $con);
    }
}

echo json_encode($datos);
