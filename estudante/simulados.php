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
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulados</title>
    <link rel="stylesheet" href="simulados.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
</head>
<!-- LTELA DE CARREGAMENTO -->
<div id="loading-screen">
    <div class="spinner"></div>
    <p class="loading-text">Preparando seu Simulado, aguarde...</p>
</div>

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

<main>
<?php
session_start();

// Conexão com o banco de dados
$host = "localhost";
$user = "root";
$password = "admin";
$database = "Topapirando";

$conn = new mysqli($host, $user, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Query para buscar os concursos (incluindo cod_concurso)
$sql = "SELECT c.cod_concurso, c.nome, c.descricao, c.qtd_questoes, c.data, c.vagas, i.nome AS instituicao, e.tipo_escolaridade AS escolaridade
        FROM concurso c
        JOIN instituicao i ON c.instituicao_cod_instituicao = i.cod_instituicao
        JOIN escolaridade e ON c.escolaridade_cod_escolaridade = e.cod_escolaridade";

$result = $conn->query($sql);

if (!$result) {
    die("Erro na query: " . $conn->error);
}

$concursos = [];
while ($row = $result->fetch_assoc()) {
    $concursos[] = $row;
}
?>

<div class="page-header">
    <h1 class="page-title">Simulados por Concurso</h1>
</div>


<main class="container">
    <div class="fixed-image">
        <img src="/administrador/assets/estudo.svg" alt="Cérebro">
        <p class="quote">O sucesso é a soma de pequenos esforços dia após dia</p>
    </div>

    <div class="scrollable-cards">
    <?php if (!empty($concursos)): ?>
        <?php foreach ($concursos as $index => $concurso): ?>
            <?php 
            // Converte a data para o formato brasileiro
            $data_brasileira = date("d/m/Y", strtotime($concurso['data'])); 

            // Verifica a quantidade de questões cadastradas e compara com o esperado
            $cod_concurso = intval($concurso['cod_concurso']);
            $query_questoes = "SELECT COUNT(*) AS total_questoes FROM questao WHERE concurso_cod_concurso = $cod_concurso";
            $result_questoes = $conn->query($query_questoes);

            $simulado_disponivel = false;
            if ($result_questoes) {
                $row_questoes = $result_questoes->fetch_assoc();
                $questoes_cadastradas = $row_questoes['total_questoes'];
                $simulado_disponivel = $questoes_cadastradas == intval($concurso['qtd_questoes']); // Verifica se há a quantidade correta de questões
            }
            ?>
            <div class="card">
                <h2 class="title"><?php echo htmlspecialchars($concurso['nome']); ?></h2>
                <div class="details-row">
                    <span class="badge">Escolaridade: <?php echo htmlspecialchars($concurso['escolaridade']); ?></span>
                    <span class="badge">Realizado em: <?php echo htmlspecialchars($data_brasileira); ?></span>
                </div>
                <hr>
                <div class="details-row">
                    <p>Instituição: <?php echo htmlspecialchars($concurso['instituicao']); ?></p>
                    <p>Vagas: <?php echo htmlspecialchars($concurso['vagas']); ?></p>
                </div>
                <div class="button-row">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalConcurso<?php echo $index; ?>">
                        Mais Informações
                    </button>
                    <?php if ($simulado_disponivel): ?>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalDicas<?php echo $index; ?>">
                            Iniciar Simulado
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>Simulado não disponível</button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal do Concurso -->
            <div class="modal fade" id="modalConcurso<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $index; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel<?php echo $index; ?>"><?php echo htmlspecialchars($concurso['nome']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="display: flex; align-items: flex-start; justify-content: space-between; gap: 15px;">
                <!-- Conteúdo do modal -->
                <div>
                    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($concurso['descricao']); ?></p>
                    <p><strong>Quantidade de Questões:</strong> <?php echo $questoes_cadastradas; ?></p>
                    <p><strong>Realizado em:</strong> <?php echo htmlspecialchars($data_brasileira); ?></p>
                    <p><strong>Vagas:</strong> <?php echo htmlspecialchars($concurso['vagas']); ?></p>
                    <p><strong>Instituição:</strong> <?php echo htmlspecialchars($concurso['instituicao']); ?></p>
                </div>

                <!-- Imagem estática -->
                <img src="../administrador/assets/informações.svg" alt="Imagem ilustrativa" style="width: 150px; height: auto; border-radius: 8px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

            <!-- Modal de Dicas -->
            <div class="modal fade" id="modalDicas<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalDicasLabel<?php echo $index; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDicasLabel<?php echo $index; ?>">Dicas para o Simulado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul>
                                <li>Leia todas as questões atentamente antes de responder.</li>
                                <li>Gerencie bem o seu tempo. Não passe muito tempo em uma única questão.</li>
                                <li>O cronômetro é opcional e serve para acompanhar o tempo que você leva para concluir o simulado. Caso prefira, você pode pausá-lo a qualquer momento.</li>
                                <li>Se não souber a resposta, elimine as opções que parecem menos prováveis.</li>
                                <li>Caso deixe questões em branco,não seram contabilizadas no seu desempenho.</li>
                                <li>Certifique-se de estar em um local tranquilo para se concentrar melhor.</li>
                                <li>Boa sorte!</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                            <a href="simulado_user.php?cod_concurso=<?php echo $cod_concurso; ?>" class="btn btn-primary">
                                Iniciar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-data">Nenhum concurso encontrado.</p>
    <?php endif; ?>
    </div>
</main>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<style>.modal-title {
    color: #2118CD; 
    font-weight: bold;
}
</style>

!-- scripit para acionar a tela de carregamento -->
<script>
// Mostra a tela de carregamento e redireciona após 3 segundos
function showLoadingScreenAndRedirect(url) {
    const loadingScreen = document.getElementById('loading-screen');
    loadingScreen.style.display = 'flex'; // Exibe a tela de carregamento
    setTimeout(() => {
        window.location.href = url; // Redireciona após 3 segundos
    }, 2000);
}

// Adiciona evento ao botão "Iniciar"
document.querySelectorAll('.btn-primary').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Evita o redirecionamento imediato
        const url = this.closest('a').href; // Obtém o URL do botão
        showLoadingScreenAndRedirect(url); // Mostra a tela de carregamento e redireciona
    });
});
</script>

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