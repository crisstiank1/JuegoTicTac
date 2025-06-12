<?php
session_start(); // Siempre inicia la sesión antes de manipularla
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión en el servidor

// Redirige al usuario a la página principal o de inicio de sesión
header("Location: index.php");
exit();
?>