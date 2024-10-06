<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topapirando</title>
    <link rel="icon" href="./administrador/assets/Pré - Projeto  TôPapirando! 1.svg" type="image/x-icon">
    <link rel="stylesheet" href="inicio.css">
</head>
<body>
    <header>
        <div class="interface">
            <div class="logo">
                <a href="index.php"><img class="logo" src="administrador/assets/logo_papirando_final.svg" alt="Logo"/></a>   
               
            </div><!--logo-->

            <nav class="menu-desktop">
                <ul>
                    <li><a href="index.php" class="inicio">Início</a></li>
                    <li><a href="estudante/simulados.php" class="simulados">Simulados</a></li>
                    <li><a href="bancas.php" class="bancas">Bancas</a></li> <!-- Link de Bancas sem modal -->
                    <li><a href="desempenhos.php" class="desempenho">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info"> 
            <a href="estudante/sobre.php">Sobre</a>
            <a href="estudante/ajuda.php">Ajuda</a>
            <a href="login.php">Entrar</a>
            </div>

        </div> <!--interface-->
    </header>

    <main>
        <section class="topo-do-site">
            <div class="interface"> 
                <div class="flex">
                    <div class="txt-topo-site">
                        <h1>Teste Seu conhecimento e passe no seu desejado concurso</h1>
                        <p>Crie sua conta para aproveitar todos os benefícios de ter uma conta</p>

                        <div class="btn-contato">
                            <a href="cadastro.php">
                               <button>Crie sua conta</button> 
                            </a>
                        </div>
                    </div> <!--txt topo site-->

                    <div class="img-topo-site">
                        <img id="img" src="administrador/assets/imagem mulher sentada.svg" alt="">
                    </div> <!--img topo site-->
                </div> <!--flex-->
            </div> <!--interface-->
        </section>  <!--topo do site-->
    </main> 

    <!-- Modal Simulados -->
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
</body>
</html>
