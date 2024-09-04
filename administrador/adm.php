<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 1) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="adm.css">
</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
           <a href="inicio.html"><img class="logo" src="assets/Logo.svg" alt="topapirando"></a>
            <div class="search-bar">
                <input type="text" placeholder="Digite seu texto aqui">  
            </div>
            <div class="links">
                <a href="#">Sobre</a>
                <a href="login.html">Sair</a>
            </div>
        </header>

        <label>
            <input type="checkbox">
            <div class="toggle">
                <span class="top_line common"></span>
                <span class="middle_line common"></span>
                <span class="bottom_line common"></span>
            </div>
            <div class="slide">
                <h1>Menu</h1>
                <ul>
                    <li><a href=""><i class="fas fa-home"></i> Inicio</a></li>
                    <li><a href=""><i class="fas fa-circle-exclamation"></i> Ajuda</a></li>
                    <hr>
                    <p> gerenciamento de conteudo</p>
                    <li><a href="banca.php"><i ></i> bancas</a></li>
                    <li><a href=""><i ></i> nivel </a></li>
                    <li><a href=""><i ></i> Grau</a></li>
                    <li><a href=""><i ></i> Disciplina</a></li>
                    <li><a href=""><i ></i> Instituição</a></li>
                    <li><a href=""><i ></i> Duração</a></li>
                    <li><a href=""><i ></i> Prova</a></li>
                    <li><a href=""><i ></i> Concurso</a></li>
                    <li><a href=""><i ></i> questões</a></li>
                    <li><a href=""><i ></i> Simulados</a></li>
                </ul>
            </div>
        </label>
    </div>
</body>
</html>
