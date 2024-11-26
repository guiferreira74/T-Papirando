<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: ../administrador/login.php");
    exit();
}

// Capturando o nome e sobrenome do usuário da sessão
$usuario_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';
$sobrenome_usuario = isset($_SESSION['sobrenome']) ? $_SESSION['sobrenome'] : '';

// Conexão com o banco de dados
$host = "localhost";
$user = "root";
$password = "admin";
$database = "Topapirando";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Obtendo o ID do estudante e suas informações
$email_usuario = $_SESSION['email'];
$sql_id = "SELECT cod_estudante, nome, sobrenome, qtd_questoes, acertos, erros, qtd_concursos FROM estudante WHERE email = ?";
$stmt = $conn->prepare($sql_id);
$stmt->bind_param("s", $email_usuario);
$stmt->execute();
$stmt->bind_result($cod_estudante, $nome, $sobrenome, $qtd_questoes, $acertos, $erros, $qtd_concursos);
$stmt->fetch();
$stmt->close();

// Verifica se o botão de "Zerar Desempenho" foi pressionado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $sql_reset = "UPDATE estudante SET qtd_questoes = 0, acertos = 0, erros = 0, qtd_concursos = 0 WHERE cod_estudante = ?";
    $stmt_reset = $conn->prepare($sql_reset);
    $stmt_reset->bind_param("i", $cod_estudante);

    if ($stmt_reset->execute()) {
        // Atualiza as variáveis para refletir os valores resetados
        $qtd_questoes = 0;
        $acertos = 0;
        $erros = 0;
        $qtd_concursos = 0;
        $reset_success = true; // Mensagem de sucesso
    } else {
        $reset_error = "Erro ao zerar o desempenho. Por favor, tente novamente.";
    }
    $stmt_reset->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Desempenho</title>
    <link href='desempenhos.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <div class="container">
        <h1>Meu Desempenho</h1>
        <div class="info-section">
            <p><strong>Nome:</strong> <span class="highlight"><?php echo htmlspecialchars($nome . ' ' . $sobrenome); ?></span></p>
            <p><strong>Simulados Feitos:</strong> <span class="highlight"><?php echo $qtd_concursos; ?></span></p>
            <p><strong>Questões Respondidas:</strong> <span class="highlight"><?php echo $qtd_questoes; ?></span></p>
        </div>

        <?php if ($acertos == 0 && $erros == 0): ?>
            <div class="no-data-message">
            <p>Para ver seu desempenho, você precisa realizar o simulado.</p>
            <a href="simulados.php" class="btn-simulado">Ir para Simulados</a>
        </div>
        <?php else: ?>
            <div id="donutchart"></div>
        <?php endif; ?>

        <!-- Botão Zerar Desempenho -->
        <div style="text-align: center; margin-top: 20px;">
            <form method="POST" action="">
                <button type="submit" name="reset" class="btn-reset">Zerar Estatísticas</button>
            </form>
            <?php if (isset($reset_success) && $reset_success): ?>
                <p style="text-align: center; color: green; margin-top: 10px;">Estatísticas zeradas com sucesso!</p>
            <?php elseif (isset($reset_error)): ?>
                <p style="text-align: center; color: red; margin-top: 10px;"><?php echo $reset_error; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Script para carregar e desenhar o gráfico
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var acertos = <?php echo intval($acertos); ?>;
            var erros = <?php echo intval($erros); ?>;

            var data = google.visualization.arrayToDataTable([
                ['Status', 'Quantidade'],
                ['Erros', erros],
                ['Acertos', acertos]
            ]);

            var options = {
                title: 'Gráfico de Desempenho',
                pieHole: 0.4,
                colors: ['#e53935', '#2118CD'],
                legend: { position: 'top', alignment: 'center' },
                pieStartAngle: 180,
                chartArea: { width: '90%', height: '70%' }
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>

<style>
    .no-data-message {
        text-align: center;
        margin-top: 30px;
        font-size: 18px;
        color: #333;
    }

    .no-data-message p {
        margin-bottom: 20px;
        font-weight: bold;
    }

    .btn-simulado {
        display: inline-block;
        background-color: #2118CD;
        color: #fff;
        padding: 10px 20px;
        font-size: 16px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-simulado:hover {
        background-color: #1a14b2;
    }
</style>

    <style>
        .container {
            text-align: center;
            margin: 150px auto;
            max-width: 900px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            color: #2118CD;
            margin-bottom: 20px;
        }

        .info-section {
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .info-section p {
            margin: 10px 0;
            font-weight: bold;
        }

        .highlight {
            color: green;
            font-weight: bold;
        }

        #donutchart {
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            height: 400px;
        }

        .btn-reset {
            display: inline-block;
            background-color: #Ff0000;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-reset:hover {
            background-color: #Ff0000;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            color: #555;
        }
    </style>


    <style>


        .container {
            text-align: center;
            margin: 150px auto;
            max-width: 900px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            color: #2118CD;
            margin-bottom: 20px;
        }

        .info-section {
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .info-section p {
            margin: 10px 0;
            font-weight: bold;
        }

        #donutchart {
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            height: 400px;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            color: #555;
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
                <li><a href="simulados.php">Simulados</a></li>
                <li><a href="bancas_user.php">Bancas</a></li>
                <li><a href="desempenhos.php" class="desempenho-link">Desempenho</a></li>
            </ul>
        </nav>
        <div class="info">
            <a href="sobre_user.php">Sobre</a>
            <a href="ajuda_user.php">Ajuda</a>
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

<style>
    .highlight {
        color: green;
        font-weight: bold;
    }
</style>




<style>
/* Estilo para o dropdown */
.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-toggle {
    color: white; /* Cor do texto de saudação */
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.profile-toggle i {
    margin-left: 5px;
}

.profile-link {
    display: none; /* O dropdown estará oculto inicialmente */
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
    white-space: nowrap; /* Evita que o texto quebre a linha */
    display: flex;
    align-items: center;
    padding: 10px;
    color: #000; /* Cor do texto */
    text-decoration: none;
}

.profile-link li a i {
    margin-right: 8px; /* Espaço entre o ícone e o texto */
    font-size: 18px; /* Ajuste do tamanho dos ícones */
    color: #000; /* Cor dos ícones */
}

.profile-link li a:hover {
    background-color: #f1f1f1; /* Muda a cor ao passar o mouse */
}

/* Estilo adicional para o visual arredondado do dropdown */
.profile-link {
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 10px;
}

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

</style>
<script>
  // Mostrar e esconder o dropdown quando o usuário clica
const profileToggle = document.getElementById('profile-toggle');
const profileDropdown = document.getElementById('profile-dropdown');

profileToggle.addEventListener('click', function (e) {
    e.preventDefault(); // Evita o comportamento padrão do link
    profileDropdown.classList.toggle('show'); // Alterna a classe "show"
});

// Fechar o dropdown quando o usuário clica fora dele
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

</body>
</html>