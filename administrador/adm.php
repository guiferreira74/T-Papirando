<?php
session_start();

// Verificar se o administrador está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_adm.php");
    exit();
}

// Recuperar o nome do administrador da sessão
$admin_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Administrador';

// Configuração da conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "topapirando";  // Coloque o nome correto do seu banco de dados

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultas SQL para os dados
$sql_bancas = "SELECT COUNT(*) as total FROM banca";
$result_bancas = $conn->query($sql_bancas);
$row_bancas = $result_bancas->fetch_assoc();
$total_bancas = $row_bancas['total'];

$sql_disciplinas = "SELECT COUNT(*) as total FROM disciplina";
$result_disciplinas = $conn->query($sql_disciplinas);
$row_disciplinas = $result_disciplinas->fetch_assoc();
$total_disciplinas = $row_disciplinas['total'];

$sql_concursos = "SELECT COUNT(*) as total FROM concurso";
$result_concursos = $conn->query($sql_concursos);
$row_concursos = $result_concursos->fetch_assoc();
$total_concursos = $row_concursos['total'];

$sql_questoes = "SELECT COUNT(*) as total FROM questao";
$result_questoes = $conn->query($sql_questoes);
$row_questoes = $result_questoes->fetch_assoc();
$total_questoes = $row_questoes['total'];

$sql_dificuldades = "SELECT COUNT(*) as total FROM dificuldade";
$result_dificuldades = $conn->query($sql_dificuldades);
$row_dificuldades = $result_dificuldades->fetch_assoc();
$total_dificuldades = $row_dificuldades['total'];

$sql_instituicoes = "SELECT COUNT(*) as total FROM instituicao";
$result_instituicoes = $conn->query($sql_instituicoes);
$row_instituicoes = $result_instituicoes->fetch_assoc();
$total_instituicoes = $row_instituicoes['total'];

$sql_duracao = "SELECT COUNT(*) as total FROM duracao";
$result_duracao = $conn->query($sql_duracao);
$row_duracao = $result_duracao->fetch_assoc();
$total_duracao = $row_duracao['total'];

$sql_provas = "SELECT COUNT(*) as total FROM prova";
$result_provas = $conn->query($sql_provas);
$row_provas = $result_provas->fetch_assoc();
$total_provas = $row_provas['total'];

// Fechar a conexão com o banco de dados
$conn->close();

// Função para definir a classe de seta com base nos valores anteriores e atuais
function getTrendingIconClass($current) {
    if ($current == 0) {
        return 'bx-minus trending-neutral'; // Mostra um traço para valores iguais a zero
    } else {
        return 'bx-trending-up trending-up'; // Mostra a seta para cima para valores maiores que zero
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
	<link rel="stylesheet" href="adm.css">
	<title>Administrador</title>
    <style>
        .trending-up {
            color: green;
        }
        .trending-neutral {
            color: gray;
        }

        /* Estilo para o dropdown do perfil */
        .profile-link {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            list-style-type: none;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-link.show {
            display: block;
        }
        .profile-link li {
            padding: 5px 0;
        }
        .profile-link li a {
            text-decoration: none;
            color: black;
            white-space: nowrap; /* Evita que o texto quebre a linha */
            display: flex;
            align-items: center;
        }
        .profile-link li a i {
            margin-right: 8px; /* Espaço entre o ícone e o texto */
        }
        .profile {
            position: relative;
            display: inline-block;
        }
        .adm-link {
            cursor: pointer;
        }
    </style>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">
	    <a href="adm.php" class="brand"><i class='bx bxs-smile icon'></i> TÔPAPIRANDO</a>
	    <ul class="side-menu">
	        <li><a href="adm.php" class="active"><i class='bx bxs-dashboard icon'></i> Início</a></li>
	        <li><a href="ajuda_adm.php"><i class='bx bx-help-circle icon'></i> Ajuda</a></li>
	        <li class="divider" data-text="Gerenciamento">Gerenciamento</li>
	        <li class="dropdown">
	            <a href="#"><i class='bx bxs-folder-open icon'></i> Simulado  <i class='bx bx-chevron-right icon-right'></i></a>
	            <ul class="side-dropdown">
                <li><a href="concurso.php">Concurso</a></li>
	                <li><a href="prova.php">Prova</a></li>
	                <li><a href="questao.php">Questão</a></li>
	            </ul>

                <hr>
	        </li>
	        <li><a href="banca.php"><i class='bx bx-building icon'></i> Bancas</a></li>
	        <li><a href="dificuldade.php"><i class='bx bx-layer icon'></i> Dificuldade</a></li>
	        <li><a href="instituicao.php"><i class='bx bxs-graduation icon'></i> Instituições</a></li>
            <li><a href="disciplina.php"><i class='bx bx-book-open icon'></i> Disciplina</a></li>
	    </ul>
	</section>

	<!-- NAVBAR -->
	<section id="content">
	<nav>
    <i class='bx bx-menu toggle-sidebar' id="hg"></i> <!-- Ícone do menu primeiro -->
    <a href="adm.php" class="brand">
        <img src="assets/logo_papirando_final.svg" alt="Logo" class="logo"> <!-- Logo após o menu -->
    </a>
    <form action="#"></form>
    <a href="sobre_adm.php" class="sobre">Sobre</a>
   
    <span class="divider"></span>
    <div class="profile">
        <a href="#" class="adm-link" id="profile-toggle">Olá, <?php echo htmlspecialchars($admin_nome); ?> <i class='bx bx-chevron-down'></i></a>
        <ul class="profile-link" id="profile-dropdown">
            <li><a href="editar_dados.php"><i class='bx bxs-user-circle icon'></i> Editar dados</a></li>
            <li><a href="adicionar_adm.php"><i class='bx bxs-cog'></i> Adicionar Adm</a></li>
            <li><a href="#" id="openLogoutModal"><i class='bx bxs-log-out-circle'></i> Sair</a></li>
        </ul>
    </div>
</nav>


		<!-- MAIN -->
		<main>
			<h1 class="title">Início</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Início</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Administrador</a></li>
			</ul>
			
            <div class="info-data">
    <!-- Primeira linha de cards -->
    
    <!-- Card 1: Bancas -->
    <a href="banca.php" class="card-link">
        <div class="card">
            <div class="head">
                <div>
                    <h2><?php echo $total_bancas; ?></h2>
                    <p>Bancas cadastradas</p>
                </div>
                <i class='bx <?php echo getTrendingIconClass($total_bancas); ?> icon'></i>
            </div>
            <span class="progress" data-value="100%"></span>
        </div>
    </a>

    <!-- Card 2: Dificuldades -->
    <a href="dificuldade.php" class="card-link">
        <div class="card">
            <div class="head">
                <div>
                    <h2><?php echo $total_dificuldades; ?></h2>
                    <p>Dificuldades cadastradas</p>
                </div>
                <i class='bx <?php echo getTrendingIconClass($total_dificuldades); ?> icon'></i>
            </div>
            <span class="progress" data-value="100%"></span>
        </div>
    </a>

    <!-- Card 3: Instituições -->
    <a href="instituicao.php" class="card-link">
        <div class="card">
            <div class="head">
                <div>
                    <h2><?php echo $total_instituicoes; ?></h2>
                    <p>Instituições cadastradas</p>
                </div>
                <i class='bx <?php echo getTrendingIconClass($total_instituicoes); ?> icon'></i>
            </div>
            <span class="progress" data-value="100%"></span>
        </div>
    </a>


    <!-- Segunda linha de cards -->

    <!-- Card 5: Concursos -->
    <a href="concurso.php" class="card-link">
        <div class="card">
            <div class="head">
                <div>
                    <h2><?php echo $total_concursos; ?></h2>
                    <p>Concursos cadastrados</p>
                </div>
                <i class='bx <?php echo getTrendingIconClass($total_concursos); ?> icon'></i>
            </div>
            <span class="progress" data-value="100%"></span>
        </div>
    </a>

    <!-- Card 6: Provas -->
    <a href="prova.php" class="card-link">
        <div class="card">
            <div class="head">
                <div>
                    <h2><?php echo $total_provas; ?></h2>
                    <p>Provas cadastradas</p>
                </div>
                <i class='bx <?php echo getTrendingIconClass($total_provas); ?> icon'></i>
            </div>
            <span class="progress" data-value="100%"></span>
        </div>
    </a>

    <!-- Card 7: Disciplinas -->
    <a href="disciplina.php" class="card-link">
        <div class="card">
            <div class="head">
                <div>
                    <h2><?php echo $total_disciplinas; ?></h2>
                    <p>Disciplinas cadastradas</p>
                </div>
                <i class='bx <?php echo getTrendingIconClass($total_disciplinas); ?> icon'></i>
            </div>
            <span class="progress" data-value="100%"></span>
        </div>
    </a>

    <!-- Card 8: Questões -->
    <a href="questao.php" class="card-link">
        <div class="card">
            <div class="head">
                <div>
                    <h2><?php echo $total_questoes; ?></h2>
                    <p>Questões cadastradas</p>
                </div>
                <i class='bx <?php echo getTrendingIconClass($total_questoes); ?> icon'></i>
            </div>
            <span class="progress" data-value="100%"></span>
        </div>
    </a>
</div>


			<!-- Gráfico -->
			<div id="chart"></div>
		</main>
	</section>

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

	<script>
	// Gráfico ApexCharts com dados dinâmicos
	var options = {
	  series: [{
	    name: 'Cadastrados',
	    data: [
	      <?php echo $total_bancas; ?>, 
	      <?php echo $total_disciplinas; ?>, 
	      <?php echo $total_provas; ?>,
	      <?php echo $total_concursos; ?>, 
	      <?php echo $total_questoes; ?>, 
	      <?php echo $total_dificuldades; ?>, 
	      <?php echo $total_instituicoes; ?>, 
	    ]
	  }],
	  chart: {
	    height: 350,
	    type: 'bar'
	  },
	  plotOptions: {
	    bar: {
	      horizontal: false,
	    },
	  },
	  dataLabels: {
	    enabled: false
	  },
	  xaxis: {
	    categories: ['Bancas', 'Disciplinas', 'Provas', 'Concursos', 'Questões', 'Dificuldades', 'Instituições'],
	  }
	};

	var chart = new ApexCharts(document.querySelector("#chart"), options);
	chart.render();

	// Script para o dropdown do Concurso
	document.querySelectorAll('.dropdown > a').forEach(dropdownLink => {
	    dropdownLink.addEventListener('click', function(event) {
	        event.preventDefault();
	        this.parentElement.classList.toggle('active');
	        this.nextElementSibling.classList.toggle('show');
	    });
	});

	const toggleSidebar = document.querySelector('nav .toggle-sidebar');
const sidebar = document.getElementById('sidebar');

toggleSidebar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
});


	// PROFILE DROPDOWN
	const profileToggle = document.getElementById('profile-toggle');
	const profileDropdown = document.getElementById('profile-dropdown');
	profileToggle.addEventListener('click', function(e) {
	    e.preventDefault();
	    profileDropdown.classList.toggle('show');
	});

	// Fechar o dropdown se clicar fora
	window.addEventListener('click', function(event) {
	    if (!profileToggle.contains(event.target) && !profileDropdown.contains(event.target)) {
	        profileDropdown.classList.remove('show');
	    }
	});
	</script>
<!-- Modal de Agradecimento -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Muito obrigado Por acessar nosso site Tôpapirando!!!</p>
        <a href="sair.php" class="btn-site">Ok</a>
    </div>
</div>

<script>
    // Selecionar elementos
    const logoutModal = document.getElementById('logoutModal');
    const openLogoutModalBtn = document.getElementById('openLogoutModal');
    const closeLogoutModalBtn = document.querySelector('.close-btn');

    // Abrir o modal quando o botão "Sair" é clicado
    openLogoutModalBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Previne o redirecionamento imediato
        logoutModal.style.display = 'flex';
    });

    // Fechar o modal quando o botão de fechar é clicado
    closeLogoutModalBtn.addEventListener('click', function() {
        logoutModal.style.display = 'none';
    });

    // Fechar o modal ao clicar fora da área do modal
    window.addEventListener('click', function(e) {
        if (e.target === logoutModal) {
            logoutModal.style.display = 'none';
        }
    });
</script>


</body>
</html>
