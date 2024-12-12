<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "edugloss";

// Crear conexión
$link = mysqli_connect($host, $user, $password) or die("Error al conectarse al servidor");

// Seleccionar la base de datos
mysqli_select_db($link, $dbname) or die("Error al conectarse a la base de datos");

// Retornar la conexión
return $link;
?>
