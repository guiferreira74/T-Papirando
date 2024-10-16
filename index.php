<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topapirando</title>
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
            <a href="login_adm.php"><i class="fa-solid fa-gear" id="gear"></i></a>
            </div>

        </div> 
    </header>

    <main>
        <section class="topo-do-site">
            <div class="interface"> 
                <div class="flex">
                    <div class="txt-topo-site">
                        <h1>Teste seu conhecimento e passe no seu desejado concurso</h1>
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
