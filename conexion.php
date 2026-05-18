<?php


$host     = "localhost";
$usuario  = "root";        
$password = "";          
$base     = "urbanstyle";

$conn = new mysqli($host, $usuario, $password, $base);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");