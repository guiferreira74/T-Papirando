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

// Verifica se as respostas foram enviadas
$respostas_usuario = isset($_POST['resposta']) ? $_POST['resposta'] : [];
$total_questoes = isset($_SESSION['ordem_alternativas']) ? count($_SESSION['ordem_alternativas']) : 0;
$acertos = 0;
$resultados = [];

// Processar todas as questões
foreach ($_SESSION['ordem_alternativas'] as $cod_questao => $ordem) {
    $query = "SELECT pergunta, desc1, desc2, desc3, desc4, desc_correta FROM questao WHERE cod_questao = $cod_questao";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $questao = $result->fetch_assoc();

        // Reorganizar alternativas conforme a ordem randomizada salva na sessão
        $alternativas = [
            $questao['desc1'],
            $questao['desc2'],
            $questao['desc3'],
            $questao['desc4'],
            $questao['desc_correta']
        ];
        $alternativas_randomizadas = [];
        foreach ($ordem as $indice) {
            $alternativas_randomizadas[] = $alternativas[$indice];
        }

        // Identificar a posição correta
        $posicao_correta = array_search($questao['desc_correta'], $alternativas_randomizadas) + 1;

        // Verificar a resposta do usuário
        $resposta_usuario = isset($respostas_usuario[$cod_questao]) ? intval($respostas_usuario[$cod_questao]) : 0;
        $acertou = ($posicao_correta == $resposta_usuario);

        // Incrementar o número de acertos se a resposta estiver correta
        if ($acertou) {
            $acertos++;
        }

        // Armazenar resultados para exibição
        $resultados[] = [
            'pergunta' => $questao['pergunta'],
            'alternativas' => $alternativas_randomizadas,
            'correta' => $posicao_correta,
            'usuario' => $resposta_usuario,
            'acertou' => $acertou
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Simulado</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href='resultado.css' rel='stylesheet'>
    <style>
        
        .correct {
            color: green;
            font-weight: bold;
        }

        .wrong {
            color: red;
            font-weight: bold;
        }
    </style>
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
<div class="resultado-simulado">
    <h1 class="titulo-simulado">Resultado do Simulado</h1>
    <div class="resumo-desempenho">
        <p><strong>Total de questões:</strong> <?php echo $total_questoes; ?></p>
        <p><strong>Acertos:</strong> <?php echo $acertos; ?></p>
    </div>
    <hr>
    <?php foreach ($resultados as $index => $resultado): ?>
        <div class="questao">
            <p class="pergunta"><strong><?php echo $index + 1; ?>. <?php echo htmlspecialchars($resultado['pergunta']); ?></strong></p>
            <ul class="alternativas">
                <?php 
                $letras = ['A', 'B', 'C', 'D', 'E'];
                foreach ($resultado['alternativas'] as $key => $descricao): ?>
                    <li>
                        <?php if ($key + 1 == $resultado['correta']): ?>
                            <span class="alternativa correta">✔️ <?php echo $letras[$key] . ") " . htmlspecialchars($descricao); ?></span>
                        <?php elseif ($key + 1 == $resultado['usuario']): ?>
                            <span class="alternativa errada">❌ <?php echo $letras[$key] . ") " . htmlspecialchars($descricao); ?></span>
                        <?php else: ?>
                            <span class="alternativa"><?php echo $letras[$key] . ") " . htmlspecialchars($descricao); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <hr>
    <?php endforeach; ?>
</div>


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
