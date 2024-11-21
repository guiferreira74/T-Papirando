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
            <a href="user.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
        </div>
        <nav class="menu-desktop">
            <ul>
                <li><a href="user.php">Início</a></li>
                <li><a href="simulados.php">Simulados</a></li>
                <li><a href="bancas_user.php">Bancas</a></li>
                <li><a href="desempenhos.php" class="desempenho-link">Desempenho</a></li>
            </ul>
        </nav>

        <!-- Dropdown de Perfil -->
        <div class="info">
            <a href="sobre_user.php">Sobre</a>
            <a href="ajuda_user.php">Ajuda</a>
            
            <!-- Link de saudação com o nome e o dropdown -->
            <div class="profile-dropdown">
                <a href="#" class="profile-toggle" id="profile-toggle">
                    Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>
                    <i class='bx bx-chevron-down'></i>
                </a>
                <ul class="profile-link" id="profile-dropdown">
                    <li><a href="editar_dados_user.php"><i class='bx bxs-user-circle icon'></i> Editar dados</a></li>
                    <li><a href="../administrador/sair.php"><i class='bx bxs-log-out-circle'></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="simulado-container">
    <h1 class="titulo-simulado">Simulado</h1>
    <form id="simulado-form" method="POST" action="resultado.php" onsubmit="return verificarQuestoesEmBranco()">
        <?php foreach ($questoes as $index => $questao): ?>
            <div class="questao">
                <p><?php echo $index + 1; ?>. <?php echo htmlspecialchars($questao['pergunta']); ?></p>
                <ul>
                    <?php
                    // Letras para as opções
                    $letras = ['A)', 'B)', 'C)', 'D)', 'E)'];
                    foreach ($questao['alternativas'] as $key => $descricao): ?>
                        <li>
                            <input 
                                type="radio" 
                                name="resposta[<?php echo $questao['cod_questao']; ?>]" 
                                value="<?php echo $key + 1; ?>" 
                                id="questao-<?php echo $questao['cod_questao'] . '-' . $key; ?>"
                            >
                            <label for="questao-<?php echo $questao['cod_questao'] . '-' . $key; ?>">
                                <?php echo $letras[$key]; ?> <?php echo htmlspecialchars($descricao); ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <hr>
        <?php endforeach; ?>
        <div class="button-container">
            <button type="submit" class="btn btn-primary">Finalizar Simulado</button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancelar Simulado</button>
        </div>
    </form>
</div>

<!-- Modal Bootstrap: Cancelar -->
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
                <a href="simulados.php" class="btn btn-primary">Sim</a>
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
