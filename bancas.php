<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title> 
    <link rel="stylesheet" href="bancas.css">
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="index.php"><img class="logo" src="./administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
        </div>

        <nav class="menu-desktop">
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a class="simulados" href="#">Simulados</a></li>
                <li><a href="bancas.php">Bancas</a></li>
                <li><a class="desempenho" href="#">Desempenho</a></li>
            </ul>
        </nav>

        <div class="info">
            <a href="./estudante/sobre.php">Sobre</a>
            <a href="./estudante/ajuda.php">Ajuda</a>
            <a href="./login.php">Entrar</a>
        </div>
    </div> <!--interface-->
</header>

<!-- Modal Simulados -->
<div id="modal-simulados" class="modal modal-custom" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p style="color: red;">Por favor, faça o login para ver o simulado.</p>
        <button id="ok-btn-simulados" class="ok-btn">OK</button>
    </div>
</div>

<!-- Modal Desempenho -->
<div id="modal-desempenho" class="modal modal-custom" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p style="color: red;">Por favor, faça o login para ver o seu desempenho.</p>
        <button id="ok-btn-desempenho" class="ok-btn">OK</button>
    </div>
</div>


<main class="content-container">
    <section id="enem">
        <h1>Enem</h1>
        <img src="./administrador/uploads/enemlogo.jpg" alt="Imagem do Enem" class="imagem-enem" />
        <p>
            Para mais informações, acesse o 
            <a href="https://www.gov.br/inep/pt-br/areas-de-atuacao/avaliacao-e-exames-educacionais/enem/provas-e-gabaritos" target="_blank" class="external-link">
                site oficial do Enem
            </a>.
        </p>
    </section>
    <section id="correios"> 
        <h1>Correios</h1>
        <img src="./administrador/uploads/correios.svg" alt="Imagem dos correios" class="imagem-correios" />
        <p>
            Para mais informações, acesse o 
            <a href="https://prosel.correios.com.br/concursos/detalharconcurso/1211#:~:text=Leia%20o%20edital%20para%20saber%20mais%20sobre%20os%20munic%C3%ADpios%20integrantes" target="_blank" class="external-link">
                site oficial dos correios
            </a>.
        </p>
    </section>
</main>

<!-- Modal de confirmação -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p style="color: black;">Tôpapirando informa: Você está sendo direcionado para outra página.</p>
        <div class="button-container">
            <button id="confirmRedirect" class="button ok">OK</button>
            <button class="button cancel" id="cancelRedirect">Cancelar</button>
        </div>
    </div>
</div>

<script>
    // Obter elementos dos modais
    var modalSimulados = document.getElementById("modal-simulados");
    var modalDesempenho = document.getElementById("modal-desempenho");
    var modalConfirmacao = document.getElementById("myModal");

    var closeBtns = document.getElementsByClassName("close-btn");
    var okBtnSimulados = document.getElementById("ok-btn-simulados");
    var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

    // Função para mostrar um modal específico
    function showModal(modal) {
        modal.style.display = "block";
    }

    // Função para esconder todos os modais
    function closeModal() {
        modalSimulados.style.display = "none";
        modalDesempenho.style.display = "none";
        modalConfirmacao.style.display = "none";
    }

    // Adicionar eventos de clique para os links Simulados e Desempenho
    document.querySelectorAll('.menu-desktop a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (this.classList.contains("simulados")) {
                e.preventDefault(); 
                showModal(modalSimulados);
            } else if (this.classList.contains("desempenho")) {
                e.preventDefault(); 
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
        if (event.target === modalSimulados || event.target === modalDesempenho) {
            closeModal();
        } else if (event.target === modalConfirmacao) {
            modalConfirmacao.style.display = "none";
        }
    }

    // Seleciona todos os links externos
    document.querySelectorAll('.external-link').forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Impede o clique padrão

            // Exibe o modal de confirmação
            modalConfirmacao.style.display = 'block';

            // Confirm button event
            document.getElementById('confirmRedirect').onclick = function() {
                window.open(link.href, '_blank');
                modalConfirmacao.style.display = 'none'; // Fecha o modal
            }

            // Cancel button event
            document.getElementById('cancelRedirect').onclick = function() {
                modalConfirmacao.style.display = 'none'; // Fecha o modal
            }
        });
    });

    // Fechar modal ao clicar no "x"
    document.querySelector('.close').onclick = function() {
        modalConfirmacao.style.display = 'none';
    }
</script>
</body>
</html>
