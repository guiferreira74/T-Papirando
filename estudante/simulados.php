<?php
session_start();

// Verifica se o usuário está logado e se tem o tipo de acesso correto
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: ../administrador/login.php");
    exit();
}

// Capturando o nome e sobrenome do usuário da sessão
$usuario_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário'; // Nome padrão
$sobrenome_usuario = isset($_SESSION['sobrenome']) ? $_SESSION['sobrenome'] : ''; // Sobrenome padrão
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulado</title>
    <link rel="stylesheet" href="simulados.css">
</head>
<body>

<div id="conteudo-header">
<header>
        <div class="interface">
            <div class="logo">
                <a href="user.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
            </div>

            <nav class="menu-desktop">
                <ul>
                    <li><a href="user.php">Início</a></li>
                    <li><a class="simulados" href="simulados.php">Simulados</a></li>
                    <li><a href="bancas_user.php">Bancas</a></li>
                    <li><a class="desempenho" href="desempenhos.php">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info">
                <a href="sobre_user.php">Sobre</a>
                <a href="ajuda_user.php">Ajuda</a>
                <span class="saudacao">Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>!</span>
                <a href="../administrador/sair.php">Sair</a>
            </div>
        </div> <!--interface-->
    </header>
    
 </div>
<center><h1>Simulados</h1></center>
</body>
</html>