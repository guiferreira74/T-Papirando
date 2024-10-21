<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="sobre.css">
    <script src="scripts.js"></script>
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  
    <header>
        <div class="interface">
            <div class="logo">
                <img src="../administrador/assets/logo_papirando_final.svg" alt="Logo">     
            </div>
            <nav class="menu-desktop">
                <ul>
                    <li><a href="../index.php">Início</a></li>
                    <li><a href="#" class="simulados">Simulados</a></li>
                    <li><a href="../bancas.php">Bancas</a></li>
                    <li><a href="#" class="desempenho">Desempenho</a></li>
                </ul>
            </nav>
            <div class="info">
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="#"><i class="fa-solid fa-gear" id="gear" title="Acesso restrito"></i></a>
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
                <p>
                    Gostaríamos de agradecer primeiramente a Deus, por nos dar força e disposição para nos levantar todos os dias.
                    Agradecemos também nosso professor orientador Gustavo Mendonça por toda ajuda e esforço dedicado a nós por todo o nosso percurso de aprendizado.
                    Agradecemos à Professora, Lidiana Silva, pela disponibilidade e atenção.
                    Também agradecemos à diretora da FAETEC CVT Nilópolis, Patrícia Monteiro, pelo grande apoio não só em nosso projeto, mas pela dedicação diária aos alunos.
                </p>
            </div>
        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            var modalSimulados = document.getElementById("modal-simulados");
            var modalDesempenho = document.getElementById("modal-desempenho");
            var modalAcesso = document.getElementById("modal-acesso");

            var closeBtns = document.getElementsByClassName("close-btn");
            var okBtnSimulados = document.getElementById("ok-btn-simulados");
            var okBtnDesempenho = document.getElementById("ok-btn-desempenho");
            var okBtnAcesso = document.getElementById("ok-btn-acesso");
            var cancelBtnAcesso = document.getElementById("cancel-btn-acesso");

            var btnGear = document.getElementById("gear");

            // Função para mostrar o modal
            function showModal(modal) {
                modal.style.display = "block";
            }

            // Função para fechar o modal
            function closeModal() {
                modalSimulados.style.display = "none";
                modalDesempenho.style.display = "none";
                modalAcesso.style.display = "none";
            }

            // Ações ao clicar nos links do menu
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

            // Quando o ícone da engrenagem for clicado, exibir o modal de acesso restrito
            btnGear.addEventListener("click", function(event) {
                event.preventDefault();
                showModal(modalAcesso);
            });

            // Fechar os modais ao clicar no botão OK ou Cancelar
            okBtnSimulados.addEventListener("click", closeModal);
            okBtnDesempenho.addEventListener("click", closeModal);
            okBtnAcesso.addEventListener("click", function() {
                closeModal();
                window.location.href = "/login_adm.php"; // Redirecionar para a página de login
            });
            cancelBtnAcesso.addEventListener("click", closeModal);

            // Fechar o modal ao clicar no X
            Array.from(closeBtns).forEach(function(btn) {
                btn.onclick = closeModal;
            });

            // Fechar o modal ao clicar fora dele
            window.onclick = function(event) {
                if (event.target == modalSimulados || event.target == modalDesempenho || event.target == modalAcesso) {
                    closeModal();
                }
            };
        });
    </script>

</body>
</html>
