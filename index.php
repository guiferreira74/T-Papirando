<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TôPapirando</title>
    <link rel="icon" href="./administrador/assets/favicon t.png" type="image/x-icon">
    <link rel="stylesheet" href="inicio.css">
    <!-- awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="index.php"><img class="logo" src="administrador/assets/logo_papirando_final.svg" alt="Logo"/></a>   
        </div>

        <nav class="menu-desktop">
            <ul>
                <li><a href="index.php" class="inicio">Início</a></li>
                <li><a href="estudante/simulados.php" class="simulados">Simulados</a></li>
                <li><a href="bancas.php" class="bancas">Bancas</a></li> 
                <li><a href="desempenhos.php" class="desempenho">Desempenho</a></li>
            </ul>
        </nav>

            <div class="info"> 
            <a href="estudante/sobre.php">Sobre</a>
            <a href="estudante/ajuda.php">Ajuda</a>
            <a href=""><i class="fa-solid fa-gear" id="gear" title="Acesso restrito"></i></a>
            <button class="menu-toggle" aria-label="Abrir menu lateral">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

    </div> 
</header>

<nav class="menu-lateral">
    <ul>
        <li><a href="index.php">Início</a></li>
        <li><a href="estudante/simulados.php" class="disabled-link">Simulados</a></li>
        <li><a href="bancas.php">Bancas</a></li>
        <li><a href="desempenhos.php" class="disabled-link">Desempenho</a></li>
        <li><a href="estudante/sobre.php">Sobre</a></li>
        <li><a href="estudante/ajuda.php">Ajuda</a></li>
    </ul>
</nav>

    <main class="hidden" id="content">
    <section class="topo-do-site">
        <div class="interface"> 
            <div class="flex">
                <div class="txt-topo-site">
                    <h1>Teste seu conhecimento e prepare-se melhor para o seu concurso</h1>
                    <p>Crie sua conta para aproveitar todos os benefícios de ter uma conta</p>

                    <div class="btn-contato">
                        <a href="cadastro.php">
                            <button>Crie sua conta</button> 
                        </a>
                        <p class="ou">Ou</p>
                        <div class="entrar">
                            <a href="login.php">
                                <button>Entrar</button> 
                            </a>
                        </div>
                    </div>
                </div>

                <div class="img-topo-site">
                    <img id="img" src="administrador/assets/imagem mulher sentada.svg" alt="">
                </div>
            </div>
        </div> 
    </section> 
</main>
<script>
    // Verifica se o site está sendo acessado em localhost
if (window.location.hostname === "localhost") {
    const content = document.getElementById("content");

    // Remove a classe "hidden" e adiciona a classe de animação
    content.classList.remove("hidden");
    content.classList.add("animate-slide-up");
} else {
    // Remove a classe "hidden" diretamente se não for localhost
    document.getElementById("content").classList.remove("hidden");
}
</script>

    <style>
    /* Estilos básicos para o layout */
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Conteúdo principal da página para ocupar o espaço necessário */
    .content {
        flex: 1;
    }

    footer {
    background: linear-gradient(to bottom, #2118cd 65%, #4682b4 100%); /* Azul escuro até o meio, transição para azul intermediário */
    color: white;
    padding: 20px 10px;
    width: 100%;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.9); /* Sombra para efeito de profundidade */
}

    /* Layout flexível e responsivo */
    .footer-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: auto;
        flex-wrap: wrap;
        text-align: center;
    }

    .footer-column {
        flex: 1;
        min-width: 200px;
        padding: 5px;
    }

    /* Ajuste de texto no rodapé */
    .footer-column h3, .footer-column h4 {
        margin-bottom: 5px;
        font-size: 16px;
    }

    .footer-column p {
        font-size: 12px;
        line-height: 1.4;
        margin: 0;
    }

    .social-icons a {
        color: #bbb;
        margin-right: 10px;
        font-size: 18px;
    }

    /* Estilo para a linha de direitos reservados */
    .footer-bottom {
        text-align: center;
        font-size: 10px;
        margin-top: 10px;
        color: white;
    }

    /* Responsividade para telas menores */
    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div class="content">
    <!-- Conteúdo principal da página -->
</div>

<footer>
    <div class="footer-container">
        <!-- Informações da empresa -->
        <div class="footer-column">
            <h3>TôPapirando</h3>
            <p>Plataforma para você testar seu conhecimento e se preparar para o seu concurso.<br>Acesse simulados, desempenho e muito mais!</p>
        </div>

        <!-- Links sociais -->
        <div class="footer-column">
            <h4>Siga-nos</h4>
            <a href="https://www.instagram.com/topapirando/" target="_blank">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width: 24px; height: 24px;">
            <path fill="#E4405F" d="M224.1 141c-63.6 0-114.9 51.4-114.9 114.9S160.5 370.9 224.1 370.9 339 319.5 339 255.9 287.7 141 224.1 141zm0 186.6c-39.6 0-71.7-32.1-71.7-71.7s32.1-71.7 71.7-71.7 71.7 32.1 71.7 71.7-32.1 71.7-71.7 71.7zm146.4-194.3c0 14.9-12 27-27 27-14.9 0-27-12-27-27s12-27 27-27c15 0 27 12 27 27zm76.1 27.2c-1.7-35.7-9.9-67.5-36.2-93.8s-58-34.5-93.8-36.2c-37-2.1-148.5-2.1-185.5 0-35.7 1.7-67.5 9.9-93.8 36.2S4.6 125.2 2.9 160.9c-2.1 37-2.1 148.5 0 185.5 1.7 35.7 9.9 67.5 36.2 93.8s58 34.5 93.8 36.2c37 2.1 148.5 2.1 185.5 0 35.7-1.7 67.5-9.9 93.8-36.2s34.5-58 36.2-93.8c2.1-37 2.1-148.5 0-185.5zm-48.3 232c-7.8 19.6-22.9 34.7-42.5 42.5-29.4 11.7-99.2 9-132.7 9s-103.4 2.6-132.7-9c-19.6-7.8-34.7-22.9-42.5-42.5-11.7-29.4-9-99.2-9-132.7s-2.6-103.4 9-132.7c7.8-19.6 22.9-34.7 42.5-42.5 29.4-11.7 99.2-9 132.7-9s103.4-2.6 132.7 9c19.6 7.8 34.7 22.9 42.5 42.5 11.7 29.4 9 99.2 9 132.7s2.6 103.4-9 132.7z"/>
        </svg>
    </a>
</div>

        </div>
    </div>
    <div class="footer-bottom">
        © 2024 TôPapirando. Todos os direitos reservados.
    </div>
</footer>


    <div id="modal-simulados" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o simulado.</p>
            <button id="ok-btn-simulados" class="ok-btn">OK</button>
        </div>
    </div>

    <!-- Modal Desempenho -->
    <div id="modal-desempenho" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o seu desempenho.</p>
            <button id="ok-btn-desempenho" class="ok-btn">OK</button>
        </div>
    </div>

  <!-- abrir menu -->
  <script>
// Seleciona o botão de alternância e o menu lateral
const menuToggle = document.querySelector('.menu-toggle');
const menuLateral = document.querySelector('.menu-lateral');

// Adiciona evento de clique para abrir e fechar o menu lateral
menuToggle.addEventListener('click', (event) => {
    event.stopPropagation(); // Evita propagação do clique
    menuLateral.classList.toggle('show');
});

// Fecha o menu lateral se o usuário clicar fora dele
document.addEventListener('click', (event) => {
    if (!menuLateral.contains(event.target) && !menuToggle.contains(event.target)) {
        menuLateral.classList.remove('show');
    }
});

// Fecha o menu lateral se a tela for redimensionada para maior que 768px
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        menuLateral.classList.remove('show');
    }
});

// Seleciona todos os links com a classe 'disabled-link'
const disabledLinks = document.querySelectorAll('.menu-lateral a.disabled-link');

// Adiciona um evento de clique que impede o comportamento padrão
disabledLinks.forEach((link) => {
    link.addEventListener('click', (event) => {
        event.preventDefault(); // Impede a navegação
        alert("Este link está desativado no momento."); // Mensagem opcional
    });
});


</script>
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
            if (event.target == modalSimulados || event.target == modalDesempenho) {
                closeModal();
            }
        }
    </script>

<!-- Modal Acesso Restrito -->
<div id="modal-acesso" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span> <!-- Certifique-se que a classe é "close-btn" -->
        <p class="modal-text">Acesso restrito, deseja continuar?</p>
        <div class="modal-buttons">
            <button id="ok-btn-acesso" class="btn-ok">OK</button>
            <button id="cancel-btn-acesso" class="btn-cancel">Cancelar</button>
        </div>
    </div>
</div>


<script>
    // Obter o modal e os botões
    var modalAcesso = document.getElementById("modal-acesso");
    var btnGear = document.getElementById("gear");
    var okBtnAcesso = document.getElementById("ok-btn-acesso");
    var cancelBtnAcesso = document.getElementById("cancel-btn-acesso");
    var closeBtn = document.querySelector("#modal-acesso .close-btn"); // Certifique-se de selecionar o botão X corretamente

    // Quando o ícone da engrenagem for clicado, exibir o modal
    btnGear.addEventListener("click", function(event) {
        event.preventDefault(); // Prevenir a navegação imediata
        modalAcesso.style.display = "block";
    });

    // Se o usuário clicar em "OK", fechar o modal e continuar com o redirecionamento
    okBtnAcesso.addEventListener("click", function() {
        modalAcesso.style.display = "none";
        window.location.href = "login_adm.php"; // Redirecionar para a página de login
    });

    // Se o usuário clicar em "Cancelar", apenas fechar o modal
    cancelBtnAcesso.addEventListener("click", function() {
        modalAcesso.style.display = "none";
    });

    // Fechar o modal se o usuário clicar no X
    closeBtn.addEventListener("click", function() {
        modalAcesso.style.display = "none";
    });

    // Fechar o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        if (event.target == modalAcesso) {
            modalAcesso.style.display = "none";
        }
    };
</script>


</body>
</html>
