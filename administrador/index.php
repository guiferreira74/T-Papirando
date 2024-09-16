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
            <a href="simulados.php" class="#">Simulados</a>
            <a href="bancas.php">Bancas</a>
            <a href="desempenhos.php" class="#">Desempenho</a>
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
      <!-- Modal Simulados -->
<div id="modal-simulados" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Por favor, crie sua conta para ver o simulado.</p>
        <button id="ok-btn-simulados" class="ok-btn">OK</button>
    </div>
</div>

<!-- Modal Desempenho -->
<div id="modal-desempenho" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Por favor, crie sua conta para ver o desempenho.</p>
        <button id="ok-btn-desempenho" class="ok-btn">OK</button>
    </div>
</div>
<script>
// Obter elementos dos modais e botões
var modalSimulados = document.getElementById("modal-simulados");
var modalDesempenho = document.getElementById("modal-desempenho");

var closeBtns = document.getElementsByClassName("close-btn");
var okBtnSimulados = document.getElementById("ok-btn-simulados");
var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

// Função para mostrar um modal específico
function showModal(modal) {
    modal.style.display = "block";
}

// Função para esconder o modal
function closeModal() {
    modalSimulados.style.display = "none";
    modalDesempenho.style.display = "none";
}

// Adicionar eventos de clique para os links Simulados e Desempenho
document.querySelectorAll('.menu a').forEach(function(link) {
    link.addEventListener('click', function(e) {
        if (this.textContent === "Simulados") {
            e.preventDefault(); // Previne a navegação padrão
            showModal(modalSimulados);
        } else if (this.textContent === "Desempenho") {
            e.preventDefault(); // Previne a navegação padrão
            showModal(modalDesempenho);
        }
    });
});

// Adicionar eventos de clique para os botões de fechar e os botões OK
Array.from(closeBtns).forEach(function(btn) {
    btn.onclick = closeModal;
});
okBtnSimulados.onclick = closeModal;
okBtnDesempenho.onclick = closeModal;

// Fechar o modal se o usuário clicar fora dele
window.onclick = function(event) {
    if (event.target == modalSimulados || event.target == modalDesempenho) {
        closeModal();
    }
}
</script>
    <!-- Footer -->
    <footer style="background-color: #000; padding: 20px; text-align: center;">
        <p style="margin: 0; color: #fff;">© 2024, Topapirando</p>
    </footer>
</body>
</html>
