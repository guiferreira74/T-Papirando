<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topapirando</title>
    <link rel="stylesheet" href="inicio.css">
    <link rel="icon" href="assets/Pré - Projeto  TôPapirando! 1.svg" type="image/x-icon">
</head>
<body>
    <!-- Navigation-->
    <!-- <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container">
            <img class="logo-img" style="height: 60px; width:auto;" src="logo_papirando_final.svg" alt="">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#home">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tratamentos">Tratamentos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contato</a></li>
                </ul>
            </div>
            <div>
                <a class="botao-consulta-p font-1-m" href="">
                    <i class="fa-solid fa-calendar-days"></i>
                </a>
            </div>
        </div>
    </nav> -->
    
    <!-- Masthead-->
    <!-- <header class="masthead" id="home">
        <div class="intro-content container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-left reveal-txt">
                <div class="align-self-end">
                    <h1 class="font-1-xxl-b text-w">Teste seu conhecimento</h1>
                </div>
                <div class="align-self-baseline">
                    <p class="font-2-m text-w-2" style="margin-top: 20px;">Passe no seu desejado concurso </p>
                </div>
            </div>
        </div>
    </header> -->

    <div id="conteudo-header">
        <header class="header-prc">
           <a href="index.php"><img class="logo" src="assets/logo.svg" alt="topapirando"></a>
            <div class="search-bar">
                <input type="text" placeholder="Digite seu texto aqui">  
            </div>
            <div class="links">
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="login.php">Entrar</a>
            </div>
        </header>
        <div class="menu">
            <a href="index.php">Inicio</a>
            <a href="simulados.php" class="restricted">Simulados</a>
            <a href="bancas.php">Bancas</a>
            <a href="desempenhos.php" class="restricted">Desempenho</a>
        </div>
    </div>

    <main> 
        <div class="conteudo-principal">
             <div class="div1">
                 <h1 id="text1">
                      Teste seu conhecimento  e  <br>passe no seu desejado <br> concurso 
                 </h1>

                 <img id="mulher" src="assets/imagem mulher sentada.svg" alt="">
             </div>

             <div class="div2">
                <h1>Seu guia  para a vitória em concursos </h1>
                <a href="cadastro.php"><button id="button">Crie sua Conta </button></a>
                <h2>Crie sua conta e aproveite todos os beneficios de se ter uma conta  </h2>
             </div>
      </div>
    </main>
    
    <!-- Footer -->
    <footer style="background-color: #000; padding: 20px; text-align: center;">
        <p style="margin: 0; color: #fff;">© 2024, Topapirando</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Example check to determine if user is logged in
            var loggedIn = false; // Replace with actual login status check

            // Get all elements with class 'restricted'
            var restrictedLinks = document.querySelectorAll('.menu a.restricted');
            
            // Update the visibility and access based on login status
            restrictedLinks.forEach(function(link) {
                if (loggedIn) {
                    link.classList.remove('restricted');
                    link.style.color = ''; // Reset color
                    link.style.pointerEvents = ''; // Reset pointer events
                } else {
                    link.classList.add('restricted');
                }
            });
        });
    </script>
</body>
</html>
