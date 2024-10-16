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
    <link rel="stylesheet" href="simulados.css">
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

      .info .saudacao {
          color: white;
      }

      .container {
          width: 90%;
          max-width: 1200px;
          margin: 20px auto;
          padding: 20px;
          background-color: white;
          border-radius: 15px;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
          position: relative;
      }

      .chart-container {
          position: relative;
          width: 100%;
          height: 400px;
          margin-top: 20px;
      }

      #donutchart {
          width: 100%;
          height: 100%;
      }

      .more-details {
          display: block;
          width: 100%;
          padding: 10px;
          background-color: #2118CD;
          color: white;
          border: none;
          border-radius: 5px;
          text-align: center;
          cursor: pointer;
          margin-top: 10px;
          transition: background-color 0.3s ease;
      }

      .more-details:hover {
          background-color: #1a1489;
      }

      .info-section {
          display: flex;
          justify-content: space-around;
          padding: 20px;
          background-color: #f9f9f9;
          border-radius: 10px;
          margin-top: 20px;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      }

      .info-item {
          text-align: center;
      }

      .info-item h3 {
          font-size: 24px;
          color: #4CAF50;
      }
    </style>
</head>

<body>
    <header>
        <div class="interface">
            <div class="logo">
                <a href="user.php"><img src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a>
            </div>

            <nav>
                <ul>
                    <li><a href="user.php">Início</a></li>
                    <li><a href="simulados.php">Simulados</a></li>
                    <li><a href="bancas_user.php">Bancas</a></li>
                    <li><a href="desempenhos.php">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info">
                <a href="sobre_user.php">Sobre</a>
                <a href="ajuda_user.php">Ajuda</a>
                <span class="saudacao">Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>!</span>
                <a href="../administrador/sair.php">Sair</a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Seção de informações -->
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

        <!-- Gráfico e botão "Mais Detalhes" -->
        <div class="chart-container">
            <div id="donutchart"></div>
           
        </div>
    </div>
</body>
</html>