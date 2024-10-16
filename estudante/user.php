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
    <title>Estudante</title>
    <link rel="stylesheet" href="user.css">
    <link   rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
</head>
<body>
<header>
        <div class="interface">
            <div class="logo">
            <a href="user.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
            </div>
            <nav class="menu-desktop">
                <ul>
                    <li><a href="user.php">Início</a></li>
                    <li><a href="simulados.php" class="simulados-link">Simulados</a></li>
                    <li><a href="bancas_user.php">Bancas</a></li>
                    <li><a href="desempenhos.php" class="desempenho-link">Desempenho</a></li>
                  
                </ul>
            </nav>
            <div class="info">
            <a href="sobre_user.php">Sobre</a>
            <a href="ajuda_user.php">Ajuda</a>
            <span class="saudacao">Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>!</span>
            <a href="../administrador/sair.php">Sair</a>
        </div>
    </header>
     <main>

            <div class="encima" >
                <h1> O que você está procurando?</h1>
                <input id="pesquisa" type="text" placeholder="Digite seu texto aqui">
            </div>

            <div class="enbaixo">
                <div class="esquerda">
                    
                    <a href="simulados.php"><img src="../administrador/assets/Simualdos.svg" alt=""></a>
                </div>
             
              <div class="direita">
                
                <a href="desempenhos.php"><img src="../administrador/assets/Desempenho.svg" alt=""></a>
              </div>
               
            </div>

        </div>

     </main>
</body>
</html>