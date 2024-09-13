<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: login.php");
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
    <link rel="stylesheet" href="user-logado.css">
    <script src="scripst.js"></script>
    <link rel="stylesheet" href="scripst.js">
    <link   rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
           <a href="inicio.html"><img class="logo" src="assets/Logo.svg"/> </a>
            <div class="search-bar">
                <input type="text" placeholder="Digite seu texto aqui">  
            </div>
            <div class="links">
                <a id="sobre" href="sobre.html">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="sair.php">Sair</a>
            </div>
        </header>
        <div class="menu">
            <a href="user.php">Inicio</a>
            <a href="simulados.php">Simulados</a>
            <a href="bancas.php">Bancas</a>
            <a href="desempenhos.php">Desempenho</a>
        </div>
     </div>

     <main>

        <div class="container">

            <div class="encima" >
                <h1> Oque você está procurando?</h1>
                <input id="pesquisa" type="text" placeholder="Digite seu texto aqui">
            </div>

            <div class="enbaixo">
                <img src="assets/imagem celular.svg" alt="">
                <img src="assets/imagem noteboooke.svg" alt="">
            </div>

        </div>

     </main>

</body>
</html>