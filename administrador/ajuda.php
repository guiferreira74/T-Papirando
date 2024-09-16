<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda</title>
    <link rel="stylesheet" href="ajuda.css">
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
 
</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
            <a href="index.php"><img class="logo" src="assets/Logo.svg" alt="Logo"/></a>
            <div class="search-bar">
                <input type="text" placeholder="Digite seu texto aqui">  
            </div>
            <div class="links">
                <a id="sobre" href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="login.php">Entrar</a>
            </div>
        </header>
        <div class="menu">
            <a href="index.php">Inicio</a>
            <a href="#" class="">Simulados</a>
            <a href="bancas.php">Bancas</a>
            <a href="#" class="">Desempenho</a>
        </div>
    </div>

    <main>
        <div class="titulos">
            <h1 id="faq">FAQ</h1>
            <p id="pf"> (Perguntas Frequentes) </p>
            <h2>Tire suas dúvidas aqui!</h2>
        </div>

        <div class="container">
            <div class="esquerda">
                <div class="esquerda1"> 
                    <h1>Título</h1>
                    <p>Texto</p>
                </div>
            </div>

            <div class="direita1">
                <h1>Como me cadastro no site?</h1>
                <p>Para se cadastrar, clique no link "Entrar" no topo da página e selecione a opção "Criar conta". Preencha os dados solicitados, como nome, e-mail e senha. Após a confirmação, você terá acesso completo às funcionalidades, como geração de simulados e monitoramento de desempenho.</p>
            </div>

            <div class="esquerda2">
                <h1>Título</h1>
                <p>Texto</p>
            </div>

            <div class="direita2"> 
                <h1>Título</h1>
                <p>Texto</p>
            </div>

            <div class="esquerda3">
                <h1>Título</h1>
                <p>Texto</p>
            </div>

            <div class="direita3">
                <h1>Título</h1>
                <p>Texto</p>
            </div>
        </div>
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
document.querySelectorAll('.menu a').forEach(function(link) {
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
