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
    <title>Gerenciar Concursos</title>
    <link rel="stylesheet" href="concurso.css">
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
	        <li class="divider" data-text="Gerenciamento">Gerenciamento</li>
	        <li class="dropdown">
	            <a href="#"><i class='bx bxs-folder-open icon'></i> Simulado <i class='bx bx-chevron-right icon-right'></i></a>
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
            <li><a href="duracao.php"><i class='bx bx-book-open icon'></i> Disciplina</a></li>
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
            $nome = mysqli_real_escape_string($conn, $_POST['nome']);
            $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
            $qtd_questoes = mysqli_real_escape_string($conn, $_POST['qtd_questoes']);
            $data = mysqli_real_escape_string($conn, $_POST['data']);
            $vagas = mysqli_real_escape_string($conn, $_POST['vagas']);
            $escolaridade_cod_escolaridade = (int)$_POST['escolaridade_cod_escolaridade'];
            $instituicao_cod_instituicao = (int)$_POST['instituicao_cod_instituicao'];

            $cod_concurso = $_POST['cod_concurso'] ?? null;

            // Verificar se o concurso já está registrado
            $check_sql = "SELECT * FROM concurso WHERE nome='$nome'";
            if ($cod_concurso) {
                $check_sql .= " AND cod_concurso != $cod_concurso";
            }
            $check_result = $conn->query($check_sql);

            if ($check_result && $check_result->num_rows > 0) {
                $error_message = "Erro: concurso já registrado";
            } else {
                if ($cod_concurso) {
                    // Atualizar registro
                    $sql = "UPDATE concurso SET nome='$nome', descricao='$descricao', qtd_questoes='$qtd_questoes', data='$data', vagas='$vagas', escolaridade_cod_escolaridade='$escolaridade_cod_escolaridade', instituicao_cod_instituicao='$instituicao_cod_instituicao' WHERE cod_concurso=$cod_concurso";
                } else {
                    // Inserir novo registro
                    $sql = "INSERT INTO concurso (nome, descricao, qtd_questoes, data, vagas, escolaridade_cod_escolaridade, instituicao_cod_instituicao) VALUES ('$nome', '$descricao', '$qtd_questoes', '$data', '$vagas', '$escolaridade_cod_escolaridade', '$instituicao_cod_instituicao')";
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
            $cod_concurso = (int)$_GET['delete'];
            $sql = "DELETE FROM concurso WHERE cod_concurso=$cod_concurso";
            if ($conn->query($sql) === TRUE) {
                $success_message = "Registro excluído com sucesso!";
            } else {
                $error_message = "Erro: " . $conn->error;
            }
        }

        // Preencher os campos do modal para edição
        $cod_concurso = $_GET['edit'] ?? null;
        $nome = '';
        $descricao = '';
        $qtd_questoes = '';
        $data = '';
        $vagas = '';
        $escolaridade_cod_escolaridade = '';
        $instituicao_cod_instituicao = '';

        if ($cod_concurso) {
            $result = $conn->query("SELECT * FROM concurso WHERE cod_concurso=$cod_concurso");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nome = $row['nome'];
                $descricao = $row['descricao'];
                $qtd_questoes = $row['qtd_questoes'];
                $data = $row['data'];
                $vagas = $row['vagas'];
                $escolaridade_cod_escolaridade = $row['escolaridade_cod_escolaridade'];
                $instituicao_cod_instituicao = $row['instituicao_cod_instituicao'];
            }
        }

        ?>

<div class="table-container container-principal">
    <h2 style="margin-left:100px;">Gerenciar Concursos</h2>

    <button class="btn-adicionar" onclick="openAddModal()">Adicionar Novo Concurso</button>

    <?php
    // Consultar todos os concursos com informações das chaves estrangeiras
    $sql = "
        SELECT c.*, 
               e.tipo_escolaridade, 
               i.nome AS nome_instituicao
        FROM concurso c
        JOIN escolaridade e ON c.escolaridade_cod_escolaridade = e.cod_escolaridade
        JOIN instituicao i ON c.instituicao_cod_instituicao = i.cod_instituicao
    ";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<table id='concursoTable' class='tabela-registros'>";
        echo "<thead><tr>
                <th>Nome do Concurso</th>
                <th>Descrição</th>
                <th>Quantidade de Questões</th>
                <th>Realizado em:</th>
                <th>Vagas</th>
                <th>Escolaridade</th>
                <th>Instituição</th>
                <th>Ações</th>
            </tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            // Formatar a data no formato brasileiro
            $data_brasileira = date("d/m/Y", strtotime($row['data']));
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
            echo "<td>" . htmlspecialchars($row['qtd_questoes']) . "</td>";
            echo "<td>" . htmlspecialchars($data_brasileira) . "</td>"; // Data formatada
            echo "<td>" . htmlspecialchars($row['vagas']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipo_escolaridade']) . "</td>"; // Mostra o nome da escolaridade
            echo "<td>" . htmlspecialchars($row['nome_instituicao']) . "</td>";  // Mostra o nome da instituição
            echo "<td class='actions'>";
            echo "<button class='btn-editar' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . ")'><i class='fas fa-edit'></i></button>";
            echo "<button class='btn-excluir' onclick='openModal(\"concurso.php?delete=" . $row['cod_concurso'] . "\")'><i class='fas fa-trash-alt'></i></button>";
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

    </div>
</main>


        <!-- Modal de Adicionar/Editar Concurso -->
        <div id="add-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
        <form action="concurso.php" method="POST">
            <input type="hidden" id="cod_concurso" name="cod_concurso" value="<?php echo htmlspecialchars($cod_concurso); ?>">

            <div class="grid-container">
                <!-- Primeira linha com 3 inputs -->
                <div class="grid-item">
                    <label for="nome_modal">Nome do Concurso:</label>
                    <input type="text" id="nome_modal" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome do concurso" required>
                </div>
                <div class="grid-item">
                    <label for="descricao_modal">Descrição:</label>
                    <input type="text" id="descricao_modal" name="descricao" value="<?php echo htmlspecialchars($descricao); ?>" placeholder="Preencha a descrição do concurso" required>
                </div>
                <div class="grid-item">
                    <label for="qtd_questoes_modal">Quantidade de Questões:</label>
                    <input type="text" id="qtd_questoes_modal" name="qtd_questoes" value="<?php echo htmlspecialchars($qtd_questoes); ?>" placeholder="Preencha a quantidade de questões" required>
                </div>

                <!-- Segunda linha com 3 inputs -->
                <div class="grid-item">
                    <label for="data_modal">Realizado em:</label>
                    <input type="date" id="data_modal" name="data" value="<?php echo htmlspecialchars($data); ?>" required>
                </div>
                <div class="grid-item">
                    <label for="vagas_modal">Vagas:</label>
                    <input type="text" id="vagas_modal" name="vagas" value="<?php echo htmlspecialchars($vagas); ?>" placeholder="Preencha a quantidade de vagas" required>
                </div>
                <div class="grid-item">
                    <label for="escolaridade_cod_escolaridade">Escolaridade:</label>
                    <select id="escolaridade_cod_escolaridade" name="escolaridade_cod_escolaridade" required>
                        <option value="">Selecione uma Escolaridade</option>
                        <?php
                        $escolaridades = $conn->query("SELECT * FROM escolaridade");
                        while ($escolaridade = $escolaridades->fetch_assoc()) {
                            $selected = (isset($escolaridade_cod_escolaridade) && $escolaridade['cod_escolaridade'] == $escolaridade_cod_escolaridade) ? ' selected' : '';
                            echo "<option value='" . $escolaridade['cod_escolaridade'] . "'" . $selected . ">" . htmlspecialchars($escolaridade['tipo_escolaridade']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Terceira linha com 2 inputs -->
                <div class="grid-item">
                    <label for="instituicao_cod_instituicao">Instituição:</label>
                    <select id="instituicao_cod_instituicao" name="instituicao_cod_instituicao" required>
                        <option value="">Selecione uma instituição</option>
                        <?php
                        $instituicoes = $conn->query("SELECT * FROM instituicao");
                        while ($instituicao = $instituicoes->fetch_assoc()) {
                            $selected = (isset($instituicao_cod_instituicao) && $instituicao['cod_instituicao'] == $instituicao_cod_instituicao) ? ' selected' : '';
                            echo "<option value='" . $instituicao['cod_instituicao'] . "'" . $selected . ">" . htmlspecialchars($instituicao['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="save-button">Salvar</button>
                <button type="button" class="clear-button" onclick="clearForm()">Limpar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-content {
        width: 800px; /* Aumentando a largura do modal */
        max-width: 90%; /* Responsividade para telas menores */
        padding: 20px;
        background-color: white;
        border-radius: 10px;
    }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 10px;
    }

    .grid-item {
        display: flex;
        flex-direction: column;
    }

    input[type="text"], input[type="date"], select {
        border-radius: 8px; /* Adicionando borda arredondada aos inputs */
        padding: 10px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
        font-size: 14px;
    }

    .button-container {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
    }

    button {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
    }

   
</style>



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
    // Função para definir um cookie
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
        console.log(`Cookie definido: ${name}=${value}`); // Log para verificar cookies
    }

    // Função para obter o valor de um cookie
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length));
        }
        return null;
    }

    // Função para salvar cookies
    function saveCookies() {
        setCookie("cod_concurso", document.getElementById("cod_concurso").value, 7);
        setCookie("nome", document.getElementById("nome_modal").value, 7);
        setCookie("descricao", document.getElementById("descricao_modal").value, 7);
        setCookie("qtd_questoes", document.getElementById("qtd_questoes_modal").value, 7);
        setCookie("data", document.getElementById("data_modal").value, 7);
        setCookie("vagas", document.getElementById("vagas_modal").value, 7);
        setCookie("escolaridade_cod_escolaridade", document.getElementById("escolaridade_cod_escolaridade").value, 7);
        setCookie("instituicao_cod_instituicao", document.getElementById("instituicao_cod_instituicao").value, 7);
    }

    // Função para carregar cookies nos campos do formulário
    function loadCookies() {
        document.getElementById("cod_concurso").value = getCookie("cod_concurso") || '';
        document.getElementById("nome_modal").value = getCookie("nome") || '';
        document.getElementById("descricao_modal").value = getCookie("descricao") || '';
        document.getElementById("qtd_questoes_modal").value = getCookie("qtd_questoes") || '';
        document.getElementById("data_modal").value = getCookie("data") || '';
        document.getElementById("vagas_modal").value = getCookie("vagas") || '';
        document.getElementById("escolaridade_cod_escolaridade").value = getCookie("escolaridade_cod_escolaridade") || '';
        document.getElementById("instituicao_cod_instituicao").value = getCookie("instituicao_cod_instituicao") || '';
        console.log('Cookies carregados:', document.cookie); // Log para verificar cookies
    }

    // Função para abrir o modal de sucesso
    function showSuccess(message) {
        document.getElementById('sucesso-mensagem').textContent = message;
        document.getElementById('modal-sucesso').style.display = 'block';
    }

    // Função para fechar modais
    function closeModal(modalType) {
        if (modalType === 'sucesso') {
            document.getElementById('modal-sucesso').style.display = 'none';
        } else if (modalType === 'erro') {
            document.getElementById('modal-erro').style.display = 'none';
        } else {
            document.getElementById('confirm-modal').style.display = 'none';
        }
    }

    // Adicionando funcionalidade aos botões OK dos modais
    document.getElementById("ok-btn-erro").onclick = function() {
        closeModal('erro');
    };
    document.getElementById("ok-btn-sucesso").onclick = function() {
        closeModal('sucesso');
    };

    // Mostrar mensagens de erro ou sucesso baseadas nas variáveis PHP
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($error_message): ?>
            document.getElementById('erro-mensagem').textContent = '<?php echo htmlspecialchars($error_message); ?>';
            document.getElementById('modal-erro').style.display = "block";
        <?php elseif ($success_message): ?>
            showSuccess('<?php echo htmlspecialchars($success_message); ?>');
        <?php endif; ?>
        
        loadCookies(); // Carregar cookies ao carregar a página
    });

    // Função para limpar o formulário
    function clearForm() {
        document.getElementById('cod_concurso').value = '';
        document.getElementById('nome_modal').value = '';
        document.getElementById('descricao_modal').value = '';
        document.getElementById('qtd_questoes_modal').value = '';
        document.getElementById('data_modal').value = '';
        document.getElementById('vagas_modal').value = '';
        document.getElementById('escolaridade_cod_escolaridade').selectedIndex = 0;
        document.getElementById('instituicao_cod_instituicao').selectedIndex = 0;
    }

    // Função para abrir o modal de edição
    function openEditModal(data) {
        document.getElementById('cod_concurso').value = data.cod_concurso;
        document.getElementById('nome_modal').value = data.nome;
        document.getElementById('descricao_modal').value = data.descricao;
        document.getElementById('qtd_questoes_modal').value = data.qtd_questoes;
        document.getElementById('data_modal').value = data.data;
        document.getElementById('vagas_modal').value = data.vagas;
        document.getElementById('escolaridade_cod_escolaridade').value = data.escolaridade_cod_escolaridade;
        document.getElementById('instituicao_cod_instituicao').value = data.instituicao_cod_instituicao;
        openAddModal();
    }

    // Função para abrir o modal de confirmação
    function openModal(url) {
        document.getElementById('confirm-modal').style.display = 'block';
        document.getElementById('confirm-delete').onclick = function () {
            window.location.href = url;
        };
    }

    // Função para abrir o modal de adição
    function openAddModal() {
        document.getElementById('add-modal').style.display = 'block';
    }

    // Função para fechar o modal de adição
    function closeAddModal() {
        document.getElementById('add-modal').style.display = 'none';
    }

    // Salvar automaticamente os dados quando a página for recarregada ou fechada
    window.addEventListener('beforeunload', function (event) {
        saveCookies();
    });

    // Salvar os dados sempre que houver mudança nos campos
    document.querySelectorAll('#add-modal input, #add-modal select').forEach(function (element) {
        element.addEventListener('input', function () {
            saveCookies();
        });
    });
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
