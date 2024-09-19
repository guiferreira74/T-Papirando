<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="sobre.css">
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <div class="interface">
            <div class="logo">
                <img src="assets/logo_papirando_final.svg" alt="Logo">     
            </div>
            <nav class="menu-desktop">
                <ul>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="simulados.php">Simulados</a></li>
                    <li><a href="bancas.php">Bancas</a></li>
                    <li><a href="desempenhos.php">Desempenho</a></li>
                </ul>
            </nav>
            <div class="info">
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="login.php">Entrar</a>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main>
        <div class="container">
            <div class="encima">
                <div class="dev">
                    <h1>Desenvolvedores</h1>
                    <ul>
                        <li>Luca Kaly da Cunha</li>
                        <li>Breno Soares Francisco</li>
                        <li>Marcos Antonio</li>
                        <li>Guilherme Ferreira</li>
                    </ul>
                </div>
                <img class="grupo" src="assets/grupo.svg" alt="Grupo"> 
            </div>

            <div class="embaixo">
                <img id="faetec" src="assets/Faetec.svg" alt="FAETEC"> 
                <div class="text">
                    <h3>Créditos e Agradecimentos</h3>
                    <p>Gostaríamos de agradecer primeiramente a Deus, por nos dar força e disposição para nos levantar todos os dias. Agradecemos também nosso professor orientador Gustavo Mendonça por toda ajuda e esforço dedicado a nós durante nosso percurso de aprendizado.
                    Agradecemos à Professora Lidiana Silva por toda disponibilidade e atenção para tirar dúvidas e reforço dado a nós durante a realização do nosso trabalho de conclusão de curso.
                    Agradecemos também à diretora da FAETEC CVT Nilópolis, Patrícia Monteiro, pelo grande apoio não só em nosso projeto, mas pela dedicação em estar todos os dias procurando o melhor para seus alunos.
                    Agradecemos também a todos os profissionais da instituição que trabalham todos os dias para que nosso ambiente escolar fique o melhor possível.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Simulados -->
    <div id="modal-simulados" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor,faça o login para ver o simulado.</p>
            <button id="ok-btn-simulados" class="ok-btn">OK</button>
        </div>
    </div>

    <!-- Modal Desempenho -->
    <div id="modal-desempenho" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor,faça o login para ver o seu desempenho.</p>
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
</body>
</html>
