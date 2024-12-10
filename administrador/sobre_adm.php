<?php
session_start();

// Verificar se o administrador está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_adm.php");
    exit();
}

// Recuperar o nome do administrador da sessão
$admin_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Administrador';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sobre_adm.css">
    <title>Sobre</title>
    <!-- Link para Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
    <link rel="stylesheet" href="adm.css">
</head>
<body>

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
	            <a href="#"><i class='bx bxs-folder-open icon'></i>Simulado  <i class='bx bx-chevron-right icon-right'></i></a>
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
            <li><a href="disciplina.php"><i class='bx bx-book-open icon'></i>Disciplina</a></li>
	    </ul>
	</section>

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
            <!-- Usando o ID profile-toggle -->
            <a href="#" class="adm-link" id="profile-toggle">Olá, <?php echo htmlspecialchars($admin_nome); ?> <i class='bx bx-chevron-down'></i></a>
            <ul class="profile-link" id="profile-dropdown">
                <li><a href="editar_dados.php"><i class='bx bxs-user-circle icon'></i> Editar dados</a></li>
                <li><a href="adicionar_adm.php"><i class='bx bxs-cog'></i> Adicionar Adm</a></li>
                <li><a href="sair.php"><i class='bx bxs-log-out-circle'></i> Sair</a></li>
            </ul>
        </div>
    </nav>
</section>

   <!-- Conteúdo Principal -->
   <main id="content">
        <div class="sobre-container">
            <div class="desenvolvedores">
                <h2>Desenvolvedores</h2>
                <ul>
                    <li>Breno Soares Francisco</li>
                    <li>Luca Kalyl da Cunha Beckman</li>
                    <li>Guilherme Ferreira Alves Biserra</li>
                    <li>Marcos Antonio Pinheiro de Queiroz</li>
                </ul>
            </div>
            <div class="foto">
                <img src="../administrador/assets/grupo-faetec (2).jpeg" alt="Foto dos Desenvolvedores">
            </div>
        </div>

        <div class="creditos-container">
            <div class="foto">
                <img src="../administrador/assets/Faetec.svg" alt="Foto de Créditos">
            </div>
            <div class="creditos">
                <h2>Créditos e Agradecimentos</h2>
                <p>Gostaríamos de agradecer, primeiramente, a Deus, por nos conceder força e disposição para nos levantarmos todos os dias. Agradecemos também ao nosso professor orientador, Gustavo Mendonça, por toda a ajuda e esforço dedicados a nós ao longo de nosso percurso de aprendizado. Estendemos nossos agradecimentos à professora Lidiana Silva pela disponibilidade e atenção concedidas. Por fim, agradecemos à diretora da FAETEC CVT Nilópolis, Patrícia Monteiro, pelo grande apoio, não apenas ao nosso projeto, mas também pela dedicação diária aos alunos.</p>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // SIDEBAR DROPDOWN
        const allDropdown = document.querySelectorAll('#sidebar .side-dropdown');
        const sidebar = document.getElementById('sidebar');

        allDropdown.forEach(item=> {
            const a = item.parentElement.querySelector('a:first-child');
            a.addEventListener('click', function (e) {
                e.preventDefault();

                if(!this.classList.contains('active')) {
                    allDropdown.forEach(i=> {
                        const aLink = i.parentElement.querySelector('a:first-child');
                        aLink.classList.remove('active');
                        i.classList.remove('show');
                    });
                }

                this.classList.toggle('active');
                item.classList.toggle('show');
            });
        });

        // SIDEBAR COLLAPSE
        const toggleSidebar = document.querySelector('nav .toggle-sidebar');
        const allSideDivider = document.querySelectorAll('#sidebar .divider');

        if(sidebar.classList.contains('hide')) {
            allSideDivider.forEach(item=> {
                item.textContent = '-';
            });
            allDropdown.forEach(item=> {
                const a = item.parentElement.querySelector('a:first-child');
                a.classList.remove('active');
                item.classList.remove('show');
            });
        } else {
            allSideDivider.forEach(item=> {
                item.textContent = item.dataset.text;
            });
        }

        toggleSidebar.addEventListener('click', function () {
            sidebar.classList.toggle('hide');

            if(sidebar.classList.contains('hide')) {
                allSideDivider.forEach(item=> {
                    item.textContent = '-';
                });

                allDropdown.forEach(item=> {
                    const a = item.parentElement.querySelector('a:first-child');
                    a.classList.remove('active');
                    item.classList.remove('show');
                });
            } else {
                allSideDivider.forEach(item=> {
                    item.textContent = item.dataset.text;
                });
            }
        });

        sidebar.addEventListener('mouseleave', function () {
            if(this.classList.contains('hide')) {
                allDropdown.forEach(item=> {
                    const a = item.parentElement.querySelector('a:first-child');
                    a.classList.remove('active');
                    item.classList.remove('show');
                });
                allSideDivider.forEach(item=> {
                    item.textContent = '-';
                });
            }
        });

        sidebar.addEventListener('mouseenter', function () {
            if(this.classList.contains('hide')) {
                allDropdown.forEach(item=> {
                    const a = item.parentElement.querySelector('a:first-child');
                    a.classList.remove('active');
                    item.classList.remove('show');
                });
                allSideDivider.forEach(item=> {
                    item.textContent = item.dataset.text;
                });
            }
        });

        // PROFILE DROPDOWN
        const profileToggle = document.getElementById('profile-toggle');
        const profileDropdown = document.getElementById('profile-dropdown');

        profileToggle.addEventListener('click', function (e) {
            e.preventDefault();  // Evita o comportamento padrão do link
            profileDropdown.classList.toggle('show');  // Alterna o dropdown
        });

        // Fechar dropdown ao clicar fora
        window.addEventListener('click', function (e) {
            if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');  // Fecha o dropdown ao clicar fora
            }
        });

        // APEXCHARTS EXEMPLO
        var options = {
            series: [{
                name: 'series1',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'series2',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
   
</body>
</html>