
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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Durações</title>
    <link rel="stylesheet" href="duracao.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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
	        <li><a href="parametros.php"><i class='bx bx-cog icon'></i> Parâmetros</a></li>
	        <li class="divider" data-text="Gerenciamento">Gerenciamento</li>
	        <li class="dropdown">
	            <a href="#"><i class='bx bxs-folder-open icon'></i> Simulado  <i class='bx bx-chevron-right icon-right'></i></a>
	            <ul class="side-dropdown">
                <li><a href="concurso.php">Concurso</a></li>
	                <li><a href="prova.php">Prova</a></li>
	                <li><a href="questao.php">Questão</a></li>
	            </ul>

                <hr class="custom-hr">
                
	        </li>
	        <li><a href="banca.php"><i class='bx bx-building icon'></i> Bancas</a></li>
	        <li><a href="dificuldade.php"><i class='bx bx-layer icon'></i> Dificuldade</a></li>
	        <li><a href="instituicao.php"><i class='bx bxs-graduation icon'></i> Instituições</a></li>
	        <li><a href="duracao.php"><i class='bx bx-time-five icon'></i> Duração</a></li>
            <li><a href="disciplina.php"><i class='bx bx-book-open icon'></i> Disciplina</a></li>
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
    <a href="#" class="nav-link">
        <i class='bx bxs-bell icon'></i>
    </a>

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


        <main id="main-container">
            <div id="corpo">
                <h1></h1>

                <?php
                // Conexão com o banco de dados
                $conn = new mysqli('localhost', 'root', 'admin', 'topapirando');

                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }

                $error_message = '';
                $success_message = '';

                // Inserir ou atualizar registro
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $tempo = $_POST['tempo'];
                    $descricao = $_POST['descricao'];
                    $cod_duracao = $_POST['cod_duracao'] ?? null;

                    // Verificar se o tempo já está registrado
                    $check_sql = "SELECT * FROM duracao WHERE tempo='$tempo'";
                    if ($cod_duracao) {
                        $check_sql .= " AND cod_duracao != $cod_duracao";
                    }
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Erro: duração já registrada";
                    } else {
                        if ($cod_duracao) {
                            // Atualizar registro
                            $sql = "UPDATE duracao SET tempo='$tempo', descricao='$descricao' WHERE cod_duracao=$cod_duracao";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO duracao (tempo, descricao) VALUES ('$tempo', '$descricao')";
                        }

                        if ($conn->query($sql) === TRUE) {
                            $success_message = "Registro salvo com sucesso!";
                        } else {
                            $error_message = "Erro: " . $conn->error;
                        }
                    }
                }

                // Excluir registro
                if (isset($_GET['delete'])) {
                    $cod_duracao = $_GET['delete'];
                    $sql = "DELETE FROM duracao WHERE cod_duracao=$cod_duracao";
                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Registro excluído com sucesso!";
                    } else {
                        $error_message = "Erro: " . $conn->error;
                    }
                }

                // Preencher os campos do modal para edição
                $cod_duracao = $_GET['edit'] ?? null;
                $tempo = '';
                $descricao = '';

                if ($cod_duracao) {
                    $result = $conn->query("SELECT * FROM duracao WHERE cod_duracao=$cod_duracao");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $tempo = $row['tempo'];
                        $descricao = $row['descricao'];
                    }
                }
                ?>

<div class="table-container container-principal">
    <h2>Gerenciar Durações</h2>
    <button class="btn-adicionar" onclick="openAddModal()">Adicionar Nova Duração</button>

    <?php
    // Consultar todas as durações
    $result = $conn->query("SELECT * FROM duracao");

    if ($result->num_rows > 0) {
        echo "<table id='duracaoTable' class='tabela-registros'>";
        echo "<thead><tr><th>Tempo</th><th>Descrição</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            // Exibir o campo 'tempo' e 'descricao'
            echo "<td>" . htmlspecialchars($row['tempo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
            echo "<td class='actions'>";
            // Botões de editar e excluir com o novo layout
            echo "<button class='btn-editar' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . ")'><i class='fas fa-edit'></i></button>";
            echo "<button class='btn-excluir' onclick='openModal(\"duracao.php?delete=" . $row['cod_duracao'] . "\")'><i class='fas fa-trash-alt'></i></button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p class='text-muted text-center'>Nenhum registro encontrado.</p>";
    }
    ?>
</div>


        <!-- Modal de Adicionar/Editar Duração -->
        <div id="add-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeAddModal()">&times;</span>
                <form action="duracao.php" method="POST">
                    <input type="hidden" id="cod_duracao" name="cod_duracao" value="<?php echo htmlspecialchars($cod_duracao); ?>">
                    <div id="input">
                        <label for="tempo_modal">Tempo:</label>
                        <input type="text" id="tempo_modal" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" placeholder="Preencha o tempo (HH:MM:SS)" required>
                    </div>
                    <div id="input">
                        <label for="descricao_modal">Descrição:</label>
                        <input type="text" id="descricao_modal" name="descricao" value="<?php echo htmlspecialchars($descricao); ?>" placeholder="Preencha a descrição" required>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="save-button">Salvar</button>
                        <button type="button" class="clear-button" onclick="clearForm()">Limpar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de confirmação -->
        <div id="confirm-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <div class="modal-body">
                    <p>Você tem certeza que quer excluir?</p>
                    <div class="button-container">
                        <button id="confirm-delete" class="btn-delete">Excluir</button>
                        <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modais de Sucesso e Erro -->
        <div id="modal-erro" class="modal modal-erro">
            <div class="modal-content modal-content-erro">
                <span class="close-btn" onclick="closeModal('erro')">&times;</span>
                <p id="erro-mensagem">Erro!</p>
                <button id="ok-btn-erro" class="ok-btn ok-btn-erro">OK</button>
            </div>
        </div>

        <div id="modal-sucesso" class="modal modal-sucesso">
            <div class="modal-content modal-content-sucesso">
                <span class="close-btn" onclick="closeModal('sucesso')">&times;</span>
                <p id="sucesso-mensagem">Sucesso!</p>
                <button id="ok-btn-sucesso" class="ok-btn ok-btn-sucesso">OK</button>
            </div>
        </div>

        <script>
            // Referência aos modais e botões
            var confirmModal = document.getElementById("confirm-modal");
            var addModal = document.getElementById("add-modal");
            var modalErro = document.getElementById("modal-erro");
            var modalSucesso = document.getElementById("modal-sucesso");
            var confirmButton = document.getElementById("confirm-delete");

            // Função para abrir o modal de adicionar
            function openAddModal() {
                clearForm();
                loadCookies();
                addModal.style.display = "block";
            }

            // Função para abrir o modal de edição
            function openEditModal(data) {
                document.getElementById("tempo_modal").value = data.tempo;
                document.getElementById("descricao_modal").value = data.descricao;
                document.getElementById("cod_duracao").value = data.cod_duracao;
                addModal.style.display = "block";
            }

            // Função para abrir o modal de confirmação
            function openModal(deleteUrl) {
                confirmModal.style.display = "block";
                confirmButton.onclick = function() {
                    window.location.href = deleteUrl;
                };
            }

            // Função para fechar os modais
            function closeModal(type) {
                if (type === 'erro') {
                    modalErro.style.display = "none";
                } else if (type === 'sucesso') {
                    modalSucesso.style.display = "none";
                } else {
                    confirmModal.style.display = "none"; // Fecha o modal de confirmação
                }
            }

            function closeAddModal() {
                saveCookies();
                addModal.style.display = "none";
            }

            // Limpar formulário
            function clearForm() {
                document.getElementById("tempo_modal").value = '';
                document.getElementById("descricao_modal").value = '';
                document.getElementById("cod_duracao").value = '';
            }

            // Função para salvar os valores dos inputs em cookies
            function saveCookies() {
                var tempo = document.getElementById("tempo_modal").value;
                var descricao = document.getElementById("descricao_modal").value;

                document.cookie = "tempo=" + tempo + "; path=/";
                document.cookie = "descricao=" + descricao + "; path=/";
            }

            // Função para carregar os valores dos cookies nos inputs
            function loadCookies() {
                var cookies = document.cookie.split(';');
                cookies.forEach(function(cookie) {
                    var parts = cookie.split('=');
                    var name = parts[0].trim();
                    var value = parts[1] ? decodeURIComponent(parts[1].trim()) : '';

                    if (name === 'tempo') {
                        document.getElementById("tempo_modal").value = value;
                    }
                    if (name === 'descricao') {
                        document.getElementById("descricao_modal").value = value;
                    }
                });
            }

            // Fechar o modal se o usuário clicar fora dele
            window.onclick = function(event) {
                if (event.target === confirmModal) {
                    closeModal();
                }
                if (event.target === addModal) {
                    closeAddModal();
                }
                if (event.target === modalErro) {
                    closeModal('erro');
                }
                if (event.target === modalSucesso) {
                    closeModal('sucesso');
                }
            };

            // Mostrar mensagens de erro ou sucesso baseadas nas variáveis PHP
            <?php if ($error_message): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('erro-mensagem').textContent = '<?php echo htmlspecialchars($error_message); ?>';
                    modalErro.style.display = "block";
                });
            <?php elseif ($success_message): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('sucesso-mensagem').textContent = '<?php echo htmlspecialchars($success_message); ?>';
                    modalSucesso.style.display = "block";
                });
            <?php endif; ?>

            // Adicionando funcionalidade aos botões OK dos modais
            document.getElementById("ok-btn-erro").onclick = function() {
                closeModal('erro');
            };
            document.getElementById("ok-btn-sucesso").onclick = function() {
                closeModal('sucesso');
            };
        </script>

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
