<?php
// Recupera los datos existentes del usuario desde la base de datos
$datos = obtener_datos($products);

// Si se ha enviado el formulario de edición, actualiza los datos en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $discount = $_POST['discount'];
  $activo = $_POST['activo'];

  
  // Realiza la actualización en la base de datos
  actualizar_datos($products, $name, $description, $price, $discount, $activo );
  
  // Redirige al usuario de vuelta a la página de detalles del usuario
  header("Location: detalles.php?id=$products");
  exit;
}
?>
