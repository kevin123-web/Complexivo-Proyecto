<?php
if (isset($_POST['submit'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];

  // Valida y sanitiza los datos de entrada aquí

  // Actualiza el registro en la base de datos
  $query = "UPDATE users SET name = '$name', email = '$email' WHERE id = $id";
  if (mysqli_query($connection, $query)) {
    echo 'El registro se ha actualizado correctamente';
  } else {
    echo 'Hubo un error al actualizar el registro';
  }
}
?>
