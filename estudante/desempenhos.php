<?php
session_start();

// Verifica se o usuário está logado e tem o tipo de acesso correto
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: ../administrador/login.php");
    exit();
}

// Capturando o nome e sobrenome do usuário da sessão
$usuario_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário'; // Nome padrão
$sobrenome_usuario = isset($_SESSION['sobrenome']) ? $_SESSION['sobrenome'] : ''; // Sobrenome padrão
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desempenho</title>
    <link rel="stylesheet" href="desempenhos.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {packages:['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Erros', 160],
          ['Acertos', 160]
        ]);

        var options = {
          title: 'Gráfico de Desempenho',
          titleTextStyle: {
            color: '#4CAF50',
            fontSize: 18,
            bold: true,
            italic: true
          },
          pieHole: 0.5,
          colors: ['#e0440e', '#2118CD'],
          pieSliceText: 'percentage',
          legend: {
            position: 'bottom'
          },
          fontName: 'Arial',
          fontSize: 16,
          backgroundColor: {
            fill: 'transparent'
          },
          animation: {
            startup: true,
            duration: 1000,
            easing: 'out'
          },
          slices: {
            1: { offset: 0.2 }
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
    
    <style>
       

        .info {
            display: flex;
            gap: 20px;
        }

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

        .profile-link {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            right: 0;
            z-index: 1;
            border-radius: 8px;
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
                <li><a href="simulados.php" class="simulados-link">Simulados</a></li>
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

<div class="container">
    <div class="info-section">
        <div class="info-item">
            <h3>12</h3>
            Simulados Feitos
        </div>
        <div class="info-item">
            <h3>320</h3>
            Questões Realizadas
        </div>
    </div>

    <div class="chart-container">
        <button class="more-details">Mais detalhes</button>
        <div id="donutchart"></div>
    </div>
</div>

<script>
// Mostrar e esconder o dropdown quando o usuário clica
const profileToggle = document.getElementById('profile-toggle');
const profileDropdown = document.getElementById('profile-dropdown');

profileToggle.addEventListener('click', function (e) {
    e.preventDefault();
    profileDropdown.classList.toggle('show');
});

window.addEventListener('click', function (e) {
    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
});
</script>
</body>
</html>