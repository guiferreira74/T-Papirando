<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "topapirando";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
echo "Conexão bem-sucedida!";
$conn->close();
?>
