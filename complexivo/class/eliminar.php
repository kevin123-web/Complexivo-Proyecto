<?php
if(!isset($_GET["id"])) exit();
$id = $_GET["id"];
include_once "../config/db.php";

$db = new Database();
$con = $db->conectar();

$sentencia = $con->prepare("DELETE FROM products WHERE id = ?;");
$resultado = $sentencia->execute([$id]);
if($resultado === TRUE){
     echo "Eliminado correctamente";
     echo '<div class="btn-group"><a href="http://localhost/Complexivo-Proyecto/complexivo/" class="btn btn-primary">regresar</a></div>';
}else echo "Algo salió mal";
?>