<?php

$host = "localhost";
$usuario = "root";
$password = "";
$nombre_bd = "tic_tac_toe_db";

// Crear una nueva conexión a la base de datos usando MySQLi
$conexion = new mysqli($host, $usuario, $password, $nombre_bd);

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    // Si hay un error, terminar el script y mostrar el mensaje de error
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8
$conexion->set_charset("utf8mb4");


?>