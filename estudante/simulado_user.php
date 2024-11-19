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
                <li><a href="simulados.php">Simulado</a></li>
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
    <h1>Simulado</h1>
    <form method="POST" action="resultado.php">
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
        <button type="submit">Finalizar Simulado</button>
    </form>
</div>


    <style>
    body {
        padding-top: 120px;
    }

    form {
        margin-top: 20px;
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
