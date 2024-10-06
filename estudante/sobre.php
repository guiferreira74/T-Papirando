<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="sobre.css">
    <script src="scripts.js"></script>
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <div class="interface">
            <div class="logo">
                <img src="../administrador/assets/logo_papirando_final.svg" alt="Logo">     
            </div>
            <nav class="menu-desktop">
                <ul>
                    <li><a href="../index.php">Início</a></li>
                    <li><a href="">Simulados</a></li>
                    <li><a href="../bancas.php">Bancas</a></li>
                    <li><a href="#">Desempenho</a></li>
                </ul>
            </nav>
            <div class="info">
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="../login.php">Entrar</a>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main>
    <div class="sobre-container">
        <div class="desenvolvedores">
            <h2>Desenvolvedores</h2>
            <ul>
                <li>Breno Soares Frasncisco</li>
                <li>Luca Kalyl da Cunha Beckman</li>
                <li>Guilherme Ferreira Alves Biserra</li>
                <li>Marcos Antonio Pinheiro de Queiroz</li>
                <!-- Adicione mais desenvolvedores conforme necessário -->
            </ul>
        </div>
        <div class="foto">
            <img src="../administrador/assets/grupo.svg" alt="Foto dos Desenvolvedores">
        </div>
    </div>

    <div class="creditos-container">
        <div class="foto">
            <img src="../administrador/assets/Faetec.svg" alt="Foto de Créditos">
        </div>
        <div class="creditos">
            <h2>Créditos e Agradecimentos</h2>
           
            <p>  Gostaríamos de agradecer primeiramente a Deus, por nos dar força e disposição para nos levantar todos os dias, Agradecemos também nosso professor orientador Gustavo Mendonça por toda ajuda e esforço dedicado a nós por todo o nosso percurso de aprendizado.
 
                Agradecemos a Professora, Lidiana Silva por toda disponibilidade e atenção para tirar dúvidas e reforço dado a nós durante a realização do nosso trabalho de conclusão de curso.
              Agradecemos também à diretora da FAETEC CVT Nilópolis, Patrícia Monteiro, pelo grande apoio não só em nosso projeto, mas pela dedicação em estar todos os dias procurando o melhor para seus alunos.
              
                Agradecemos também a todos os profissionais da instituição que trabalham todos os dias para que nosso ambiente escolar fique o melhor possível.</p>
        
        </div>
    </div>
</main>
</body>
</html>

    <!-- Modal Simulados -->
    <div id="modal-simulados" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o simulado.</p>
            <button id="ok-btn-simulados" class="ok-btn">OK</button>
        </div>
    </div>

    <!-- Modal Desempenho -->
    <div id="modal-desempenho" class="modal">
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
        document.querySelector('.simulados-link').addEventListener('click', function(e) {
            e.preventDefault(); // Previne a navegação padrão
            showModal(modalSimulados);
        });

        document.querySelector('.desempenho-link').addEventListener('click', function(e) {
            e.preventDefault(); // Previne a navegação padrão
            showModal(modalDesempenho);
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
