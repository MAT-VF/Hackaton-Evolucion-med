<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "evolucionmedic_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>