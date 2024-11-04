<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TôPapirando</title>
    <link rel="icon" href="./administrador/assets/Pré - Projeto  TôPapirando! 1.svg" type="image/x-icon">
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
            </div>
        </div> 
    </header>

    <main>
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

    /* Estilos do rodapé */
    footer {
        background-color: #333;
        color: white;
        padding: 20px 10px;
        width: 100%;
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
        color: #888;
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
            <p>Plataforma para você testar seu conhecimento e se preparar para o seu concurso.<br>Acesse simulados, desempenho e mais recursos exclusivos!</p>
        </div>

        <!-- Links sociais -->
        <div class="footer-column">
            <h4>Siga-nos</h4>
            <div class="social-icons">
                <a href="https://www.instagram.com/topapirando/" target="_blank"><i class="fab fa-instagram"></i></a>
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
