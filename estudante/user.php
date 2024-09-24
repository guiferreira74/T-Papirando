<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: ../administrador/login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="user.css">
    <link   rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
</head>
<body>
<header>
        <div class="interface">
            <div class="logo">
                <a href="user.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a>   
            </div><!--logo-->

            <nav class="menu-desktop">
                <ul>
                    <li><a href="user.php" class="inicio">Início</a></li>
                    <li><a href="simulados.php" class="simulados">Simulados</a></li>
                    <li><a href="bancas.php" class="bancas">Bancas</a></li> <!-- Link de Bancas sem modal -->
                    <li><a href="desempenhos.php" class="desempenho">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info"> 
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="../index.php">Sair</a>
            </div>
        </div> <!--interface-->
    </header>

     <main>

        <div class="container">

            <div class="encima" >
                <h1> Oque você está procurando?</h1>
                <input id="pesquisa" type="text" placeholder="Digite seu texto aqui">
            </div>

            <div class="enbaixo">
                <img src="../administrador/assets/imagem celular.svg " alt="">
                <img src="../administrador/assets/imagem noteboooke.svg" alt="">
            </div>

        </div>

     </main>

</body>
</html>