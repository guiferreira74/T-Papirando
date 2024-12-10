<?php
session_start();

// Verifica se o usuário está logado e se tem o tipo de acesso correto
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: ../administrador/login.php");
    exit();
}

// Capturando o nome e sobrenome do usuário da sessão
$usuario_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário'; // Nome padrão
$sobrenome_usuario = isset($_SESSION['sobrenome']) ? $_SESSION['sobrenome'] : ''; // Sobrenome padrão

// Conexão com o banco de dados
$host = "localhost";
$user = "root";
$password = "admin";
$database = "Topapirando";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Recupera o ID do concurso
if (!isset($_GET['cod_concurso']) || empty($_GET['cod_concurso'])) {
    die("Concurso inválido!.");
}

$cod_concurso = intval($_GET['cod_concurso']);

// Busca o nome do concurso
$concurso_nome = '';
$query_concurso = "SELECT nome FROM concurso WHERE cod_concurso = $cod_concurso";
$result_concurso = $conn->query($query_concurso);

if ($result_concurso && $result_concurso->num_rows > 0) {
    $row_concurso = $result_concurso->fetch_assoc();
    $concurso_nome = $row_concurso['nome'];
} else {
    die("Concurso não encontrado.");
}

// Busca questões do concurso
$query = "SELECT * FROM questao WHERE concurso_cod_concurso = $cod_concurso";
$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    die("Nenhuma questão encontrada para este concurso.");
}

// Armazena as questões e ordem randomizada
$questoes = [];
$_SESSION['ordem_alternativas'] = []; // Salvar a ordem para cada questão

while ($row = $result->fetch_assoc()) {
    // Cria as alternativas com descrições
    $alternativas = [
        $row['desc1'],
        $row['desc2'],
        $row['desc3'],
        $row['desc4'],
        $row['desc_correta']
    ];

    // Embaralha as alternativas
    $ordem = range(0, 4);
    shuffle($ordem);

    // Salva a ordem randomizada na sessão
    $_SESSION['ordem_alternativas'][$row['cod_questao']] = $ordem;

    // Reorganiza as alternativas
    $alternativas_randomizadas = [];
    foreach ($ordem as $indice) {
        $alternativas_randomizadas[] = $alternativas[$indice];
    }

    $row['alternativas'] = $alternativas_randomizadas;
    $questoes[] = $row;
}

if (empty($questoes)) {
    die("Nenhuma questão encontrada após o processamento.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulado</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href='simulado_user.css' rel='stylesheet'>
</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="#"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
        </div>
        <nav class="menu-desktop">
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="#">Simulados</a></li>
                <li><a href="#">Bancas</a></li>
                <li><a href="#" class="desempenho-link">Desempenho</a></li>
            </ul>
        </nav>

        <div class="info">
            <a href="#">Sobre</a>
            <a href="#">Ajuda</a>
            <div class="profile-dropdown">
                <a href="#" class="profile-toggle" id="profile-toggle">
                    Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>
                    <i class='bx bx-chevron-down'></i>
                </a>
                <ul class="profile-link" id="profile-dropdown">
                    <li><a href="#"><i class='bx bxs-user-circle icon'></i> Editar dados</a></li>
                    <li><a href="#"><i class='bx bxs-log-out-circle'></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="page-container">
    <div class="simulado-container">
        <h1 class="titulo-simulado">Simulado - <?php echo htmlspecialchars($concurso_nome); ?></h1>
        <form id="simulado-form" method="POST" action="resultado.php" onsubmit="return verificarQuestoesEmBranco()">
            <?php foreach ($questoes as $index => $questao): ?>
                <div class="questao" id="questao-<?php echo $index + 1; ?>">
                <p><?php echo $index + 1; ?>. <?php echo htmlspecialchars($questao['pergunta']); ?></p>
                <ul>
                    <?php
                    $letras = ['A', 'B', 'C', 'D', 'E']; // Letras das alternativas
                    foreach ($questao['alternativas'] as $key => $descricao): ?>
                        <li>
                            <input 
                                type="radio" 
                                name="resposta[<?php echo $questao['cod_questao']; ?>]" 
                                value="<?php echo $key + 1; ?>" 
                                id="questao-<?php echo $questao['cod_questao'] . '-' . $key; ?>"
                            >
                            <label for="questao-<?php echo $questao['cod_questao'] . '-' . $key; ?>">
                                <?php echo $letras[$key]; ?>
                            </label>
                            <span><?php echo htmlspecialchars($descricao); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <hr>
            <?php endforeach; ?>
        </form>
    </div>
</div>

    <div class="info-container">
        <div class="timer">
            <p><strong>Tempo Decorrido:</strong> <span id="cronometro">00:00:00</span></p>
        </div>
        <div class="status">
        <p><strong>Questões Respondidas:</strong> <span id="questoes-respondidas">0 de <?php echo count($questoes); ?></span></p>
        </div>
        <div class="button-container">
            <button id="parar-tempo" class="btn btn-warning">Parar Tempo</button>
            <button type="submit" form="simulado-form" class="btn btn-primary">Finalizar Simulado</button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancelar Simulado</button>
        </div>
        <div class="question-navigator">
    <p><strong>Ir para questão:</strong></p>
    <div class="question-links">
        <?php foreach ($questoes as $index => $questao): ?>
            <a href="#questao-<?php echo $index + 1; ?>" 
               class="question-link" 
               id="link-questao-<?php echo $index + 1; ?>">
               <?php echo $index + 1; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

</div>

<style>
    .info-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .timer, .status, .button-container {
        margin-bottom: 15px;
    }

    .question-navigator {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        width: 100%;
        text-align: center;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    }

    .question-navigator p {
        margin-bottom: 10px;
        font-weight: bold;
    }

    .question-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }

    .question-link {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        text-decoration: none;
        border: 1px solid #007bff;
        border-radius: 50%;
        color: #007bff;
        font-weight: bold;
        background-color: white;
        transition: all 0.3s ease;
    }

    .question-link:hover {
        background-color: #007bff;
        color: white;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
    }

    .question-link:active {
        background-color: #0056b3;
    }

    .question-link.respondida {
    background-color: #2118CD; /* Azul para questões respondidas */
    color: white;
    border-color: #2118CD;
    transition: all 0.3s ease; /* Suaviza a transição de cores */
}


    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const radios = document.querySelectorAll('input[type="radio"]');

    // Evento para cada botão de rádio
    radios.forEach(radio => {
        radio.addEventListener("change", function () {
            const numeroQuestao = this.closest('.questao').id.match(/\d+/)[0]; // Obtém o número da questão pelo ID do contêiner
            atualizarStatusQuestao(numeroQuestao);
            atualizarContadorQuestoes();
        });
    });

    // Permitir "desmarcar" um botão de rádio
    radios.forEach(radio => {
        radio.addEventListener('click', function () {
            if (this.dataset.checked === 'true') {
                this.checked = false;
                this.dataset.checked = 'false';
                const numeroQuestao = this.closest('.questao').id.match(/\d+/)[0];
                atualizarStatusQuestao(numeroQuestao);
                atualizarContadorQuestoes(); // Atualiza contador ao desmarcar
            } else {
                this.dataset.checked = 'true';
                const grupo = document.getElementsByName(this.name);
                grupo.forEach(input => {
                    if (input !== this) {
                        input.dataset.checked = 'false';
                    }
                });
            }
        });
    });
});

// Atualizar o status do link da questão
function atualizarStatusQuestao(numeroQuestao) {
    const link = document.getElementById(`link-questao-${numeroQuestao}`);
    const inputs = document.querySelectorAll(`#questao-${numeroQuestao} input[type="radio"]`);
    const isChecked = Array.from(inputs).some(input => input.checked);

    if (link) {
        if (isChecked) {
            link.classList.add('respondida');
        } else {
            link.classList.remove('respondida');
        }
    }
}

function atualizarContadorQuestoes() {
    const totalQuestoes = document.querySelectorAll('.questao').length; // Total de questões
    const respondidas = document.querySelectorAll('input[type="radio"]:checked').length; // Total de respostas marcadas
    const contador = document.getElementById("questoes-respondidas"); // Elemento de contagem

    if (contador) {
        contador.textContent = `${respondidas} de ${totalQuestoes}`;
    }
}


</script>


<script>
    let tempoDecorrido = 0; // Tempo inicial em segundos
    let cronometroAtivo = true;

    // Atualiza o cronômetro
    function atualizarCronometro() {
        if (!cronometroAtivo) return;

        tempoDecorrido++;

        const horas = String(Math.floor(tempoDecorrido / 3600)).padStart(2, '0');
        const minutos = String(Math.floor((tempoDecorrido % 3600) / 60)).padStart(2, '0');
        const segundos = String(tempoDecorrido % 60).padStart(2, '0');

        document.getElementById('cronometro').textContent = `${horas}:${minutos}:${segundos}`;
    }

    const cronometroInterval = setInterval(atualizarCronometro, 1000);

    document.getElementById("parar-tempo").addEventListener("click", function () {
        cronometroAtivo = !cronometroAtivo;
        this.textContent = cronometroAtivo ? "Parar Tempo" : "Retomar Tempo";
    });

    // Atualiza a quantidade de questões respondidas
    function atualizarQuestoesRespondidas() {
        const respondidas = document.querySelectorAll('input[type="radio"]:checked').length; // Contar inputs selecionados
        const totalQuestoes = document.getElementById("total-questoes").textContent; // Total de questões

        // Atualiza o contador na página
        document.getElementById("questoes-respondidas").textContent = respondidas;
    }

    // Adiciona evento de mudança nos inputs
    document.addEventListener("DOMContentLoaded", () => {
        const radios = document.querySelectorAll('input[type="radio"]');
        radios.forEach((radio) => {
            radio.addEventListener("change", atualizarQuestoesRespondidas);
        });

        // Atualiza inicialmente para mostrar o estado atual
        atualizarQuestoesRespondidas();
    });

    // Salvar o tempo ao enviar o formulário
    document.getElementById("simulado-form").addEventListener("submit", function () {
        const tempoFinal = document.getElementById('cronometro').textContent;
        localStorage.setItem('tempoDecorrido', tempoFinal); // Salva o tempo no LocalStorage
    });
</script>




<!-- Modal Cancelar Simulado -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancelar Simulado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Se você cancelar, este simulado não será contabilizado no seu desempenho. Você tem certeza que deseja cancelar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmarCancelarSimulado">Sim</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Bootstrap: Questões em Branco -->
<div class="modal fade" id="blankModal" tabindex="-1" aria-labelledby="blankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blankModalLabel">Questões em Branco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Você deixou <span id="qtdEmBranco"></span> questão(ões) em branco. Tem certeza que deseja finalizar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="confirmarFinalizacao()">Sim</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap: Informar desempenho -->
<div class="modal fade" id="informModal" tabindex="-1" aria-labelledby="informModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="informModalLabel">Informação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                As questões deixadas em branco não serão contabilizadas no seu desempenho.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="redirecionarResultado()">Ok</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Certifique-se de que o evento está configurado após o carregamento do DOM
    document.addEventListener("DOMContentLoaded", function () {
        // Evento de clique no botão "Sim"
        document.getElementById("confirmarCancelarSimulado").addEventListener("click", function () {
            // Redirecionar para a página desejada
            window.location.href = "user.php";
        });
    });
</script>
<script>
    function verificarQuestoesEmBranco() {
        const form = document.getElementById('simulado-form');
        const inputs = form.querySelectorAll('input[type="radio"]');
        const questoesRespondidas = new Set();
        const totalQuestoes = <?php echo count($questoes); ?>;

        inputs.forEach(input => {
            if (input.checked) {
                questoesRespondidas.add(input.name);
            }
        });

        const emBranco = totalQuestoes - questoesRespondidas.size;
        if (emBranco > 0) {
            document.getElementById('qtdEmBranco').innerText = emBranco;
            const blankModal = new bootstrap.Modal(document.getElementById('blankModal'));
            blankModal.show();
            return false;
        }

        return true;
    }

    function confirmarFinalizacao() {
        const informModal = new bootstrap.Modal(document.getElementById('informModal'));
        informModal.show();
        const blankModal = bootstrap.Modal.getInstance(document.getElementById('blankModal'));
        blankModal.hide();
    }

    function redirecionarResultado() {
        document.getElementById('simulado-form').submit();
    }
</script>



<!-- Adicione o Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<style>
    .titulo-simulado {
        text-align: center;
        color: #2118CD;
        font-weight: bold;
        font-size: 36px;
        margin-bottom: 20px;
    }

    .simulado-container {
        max-width: 800px;
        margin: auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        max-height: calc(100vh - 120px);
    }

    .button-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn {
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        font-weight: bold;
    }

    .btn-primary {
        background-color: #2118CD;
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background-color: #1a14b2;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }
</style>


    <script>
// Mostrar e esconder o dropdown quando o usuário clica no perfil
const profileToggle = document.getElementById('profile-toggle');
const profileDropdown = document.getElementById('profile-dropdown');

profileToggle.addEventListener('click', function (e) {
    e.preventDefault(); // Evita o comportamento padrão do link
    profileDropdown.classList.toggle('show'); // Alterna a classe "show"
});

// Fechar o dropdown quando o usuário clica fora do perfil
window.addEventListener('click', function (e) {
    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
});

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
/* Estilo para o dropdown de Simulados */
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

/* Dropdown de Perfil */
.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-toggle {
    color: white;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.profile-toggle i {
    margin-left: 5px;
}

.profile-link {
    display: none;
    position: absolute;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    right: 0;
    z-index: 1;
    border-radius: 8px;
    min-width: 150px;
    padding: 10px 0;
    text-align: left;
}

.profile-link.show {
    display: block;
}

.profile-link li {
    list-style-type: none;
}

.profile-link li a {
    white-space: nowrap;
    display: flex;
    align-items: center;
    padding: 10px;
    color: #000;
    text-decoration: none;
}

.profile-link li a i {
    margin-right: 8px;
    font-size: 18px;
    color: #000;
}

.profile-link li a:hover {
    background-color: #f1f1f1;
}

.profile-link {
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 10px;
}

</style>
</body>
</html>