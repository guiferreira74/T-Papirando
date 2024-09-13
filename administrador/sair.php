<?php
session_start();

// Remove todos os dados da sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona para a página inicial ou de login
header("Location: index.php"); // Ou "login.php", conforme sua necessidade
exit();
?>
