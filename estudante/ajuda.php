<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda</title>
    <link rel="stylesheet" href="ajuda.css">
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div id="conteudo-header">
    <header>
        <div class="interface">
            <div class="logo">
                <a href="../index.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
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
                <a href=""><i class="fa-solid fa-gear" id="gear" title="Acesso restrito"></i></a>

                </div>
        </div> <!--interface-->
    </header>

    <main>
        <div class="topo">
            <div class="topo-content">
                <h1 class="topo-title">FAQ</h1>
                <p class="topo-desc">Dúvidas frequentes</p>
            </div>
            <div class="img">
                <img src="../administrador/assets/faq2.svg" alt="">
            </div>
        </div>
        
        <div class="faq">
            <div class="faq-name"> <!-- Corrigido para "faq-name" -->
                <h1 class="faq-top"> Principais <br> Dúvidas</h1>
                <img class="faq-img" src="../administrador/assets/faq.svg" alt="">
            </div>
            <div class="faq-box">
                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-1">
                    <label class="faq-title" for="faq-trigger-1">O que é o TÔPAPIRANDO?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>O TÔPAPIRANDO é uma plataforma para concurseiros que oferece simulados, acesso direto aos sites das bancas organizadoras e ferramentas para acompanhar seu desempenho após a realização dos simulados.</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-2">
                    <label class="faq-title" for="faq-trigger-2">Como faço para me cadastrar no site?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Basta clicar no botão "Cadastrar", preencher suas informações pessoais e criar uma senha. Após o cadastro, você terá acesso a todas as funcionalidades do site.</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-3">
                    <label class="faq-title" for="faq-trigger-3">Como funcionam os simulados?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Os simulados são compostos por questões de provas anteriores e atualizadas. Você pode escolher a área de interesse, o nível de dificuldade e a quantidade de questões. Após a conclusão, você receberá um relatório de desempenho.</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-4">
                    <label class="faq-title" for="faq-trigger-4">Por que não consigo acessar "Simulados" e "Desempenho" sem estar logado?
                    </label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>As áreas de Simulados e Desempenho são personalizadas e requerem que você esteja logado para acessar seus dados e progresso individual.
                        </p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-5">
                    <label class="faq-title" for="faq-trigger-5">Esqueci minha senha. Como posso recuperá-la?
                    </label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Clique em "Esqueci minha senha" na página de login. Você precisará responder à pergunta-chave e a resposta exatamente como a cadastrou ao criar sua conta para redefinir sua senha.
                        </p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-6">
                    <label class="faq-title" for="faq-trigger-6">O que são as "Bancas Organizadoras"?
                    </label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Bancas Organizadoras são as instituições responsáveis pela elaboração de exames e concursos. Nesta seção, você pode obter informações sobre elas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Simulados -->
    <div id="modal-simulados" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o simulado.</p>
            <button id="ok-btn-simulados" class="btn-custom">OK</button>
        </div>
    </div>

    <!-- Modal Desempenho -->
    <div id="modal-desempenho" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o seu desempenho.</p>
            <button id="ok-btn-desempenho" class="btn-custom">OK</button>
        </div>
    </div>

    <script>
    // Obter os elementos dos links que disparam os modais
    var simuladosLink = document.querySelector(".simulados");
    var desempenhoLink = document.querySelector(".desempenho");

    // Obter os modais
    var modalSimulados = document.getElementById("modal-simulados");
    var modalDesempenho = document.getElementById("modal-desempenho");

    // Obter os botões de fechar dos modais
    var closeBtns = document.querySelectorAll(".close-btn");

    // Botões OK dos modais
    var okBtnSimulados = document.getElementById("ok-btn-simulados");
    var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

    // Função para abrir um modal específico
    function showModal(modal) {
        modal.style.display = "block";
    }

    // Função para fechar todos os modais
    function closeModal() {
        modalSimulados.style.display = "none";
        modalDesempenho.style.display = "none";
    }

    // Adicionar eventos para abrir os modais
    simuladosLink.addEventListener("click", function(event) {
        event.preventDefault(); // Evitar navegação imediata
        showModal(modalSimulados);
    });

    desempenhoLink.addEventListener("click", function(event) {
        event.preventDefault(); // Evitar navegação imediata
        showModal(modalDesempenho);
    });

    // Adicionar evento para fechar os modais ao clicar no botão de fechar
    closeBtns.forEach(function(btn) {
        btn.addEventListener("click", closeModal);
    });

    // Adicionar evento para fechar os modais ao clicar nos botões OK
    okBtnSimulados.addEventListener("click", closeModal);
    okBtnDesempenho.addEventListener("click", closeModal);

    // Fechar o modal se o usuário clicar fora do conteúdo do modal
    window.addEventListener("click", function(event) {
        if (event.target === modalSimulados || event.target === modalDesempenho) {
            closeModal();
        }
    });
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
        window.location.href = "/login_adm.php"; // Redirecionar para a página de login
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
<!-- novo -->
<script>
// Mostrar e esconder o dropdown quando o usuário clica em "Simulados"
const simuladosToggle = document.getElementById('simulados-toggle');
const simuladosDropdown = document.getElementById('simulados-dropdown');

simuladosToggle.addEventListener('click', function (e) {
    e.preventDefault();
    simuladosDropdown.classList.toggle('show');
});

// Fechar o dropdown de "Simulados" ao clicar fora
window.addEventListener('click', function (e) {
    if (!simuladosToggle.contains(e.target) && !simuladosDropdown.contains(e.target)) {
        simuladosDropdown.classList.remove('show');
    }
});
</script>

<style>

    /* Estilo para o dropdown *//* Estilo para o dropdown de Simulados */
.menu-desktop ul .dropdown {
    position: relative;
}

.menu-desktop ul .dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1;
    list-style: none;
    margin: 0;
    padding: 0;
    border-radius: 8px;
    min-width: 200px;
}

.menu-desktop ul .dropdown-menu.show {
    display: block;
}

.menu-desktop ul .dropdown-menu li {
    border-bottom: 1px solid #ddd;
}

.menu-desktop ul .dropdown-menu li a {
    display: block;
    padding: 10px;
    color: #333;
    text-decoration: none;
    white-space: nowrap;
}

.menu-desktop ul .dropdown-menu li a:hover {
    background-color: #f4f4f4;
}
</style>

</body>
</html>
