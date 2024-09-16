<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title>
    <link rel="stylesheet" href="bancas.css">
   
</head>
<body>
    <header class="header-prc">
        <a href="index.php">
            <img class="logo" src="assets/logo.svg" alt="Logo do Topapirando">
        </a>
        <div class="search-bar">
            <input type="text" placeholder="Digite seu texto aqui">  
        </div>
        <div class="links">
            <a href="sobre.php">Sobre</a>
            <a href="ajuda.php">Ajuda</a>
            <a href="login.php">Entrar</a>
        </div>
    </header>

    <nav class="menu">
        <a href="index.php">Início</a>
        <a href="#" id="link-simulados">Simulados</a>
        <a href="#">Bancas</a> 
        <a href="#" id="link-desempenho">Desempenho</a>
    </nav>

    <main class="content-container">
        <section id="enem">
            <h1>Enem</h1> 
            <p>
                Para mais informações, acesse o 
                <a href="https://www.gov.br/inep/pt-br/areas-de-atuacao/avaliacao-e-exames-educacionais/enem/provas-e-gabaritos" target="_blank" class="external-link">
                    site oficial do Enem
                </a>.
            </p>
        </section>
        <section id="esa">
            <h1>ESA</h1> 
            <p>
                Para mais informações, acesse o 
                <a href="https://esa.eb.mil.br/index.php/pt/concurso/provas-anteriores" target="_blank" class="external-link">
                    site oficial da ESA
                </a>.
            </p>
        </section>
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
        // Seleciona todos os links externos
        document.querySelectorAll('.external-link').forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault(); // Impede o clique padrão
                // Exibe o alerta personalizado
                if (confirm("Você será direcionado para outra página. Clique em 'OK' para continuar ou 'Cancelar' para permanecer na página atual.")) {
                    // Se o usuário clicar em 'OK', abre o link em uma nova guia
                    window.open(link.href, '_blank');
                }
            });
        });

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

        // Função para esconder todos os modais
        function closeModal() {
            modalSimulados.style.display = "none";
            modalDesempenho.style.display = "none";
        }

        // Adicionar eventos de clique para os links que mostram os modais
        document.getElementById('link-simulados').addEventListener('click', function(e) {
            e.preventDefault(); // Previne a navegação padrão
            showModal(modalSimulados);
        });

        document.getElementById('link-desempenho').addEventListener('click', function(e) {
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
