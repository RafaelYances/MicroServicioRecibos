<?php
$conn = mysqli_connect("localhost", "root", "", "recibos_db");

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>