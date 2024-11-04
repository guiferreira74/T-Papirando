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
    <title>Gerenciar Questões</title>
    <link rel="stylesheet" href="questao.css">
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
            <li><a href="disciplina.php"><i class='bx bx-time-five icon'></i>Disciplina</a></li>
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


        <style>
            .modal-content {
                width: 800px;
                max-width: 90%;
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
                border-radius: 8px;
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

        // Inserir ou atualizar questão
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pergunta = mysqli_real_escape_string($conn, $_POST['pergunta']);
            $resposta1 = mysqli_real_escape_string($conn, $_POST['resposta1']);
            $resposta2 = mysqli_real_escape_string($conn, $_POST['resposta2']);
            $resposta3 = mysqli_real_escape_string($conn, $_POST['resposta3']);
            $resposta4 = mysqli_real_escape_string($conn, $_POST['resposta4']);
            $respostacorreta = mysqli_real_escape_string($conn, $_POST['respostacorreta']);
            $dificuldade_cod_dificuldade = (int)$_POST['dificuldade_cod_dificuldade'];
            $disciplina_cod_disciplina = (int)$_POST['disciplina_cod_disciplina'];
            $prova_cod_prova = (int)$_POST['prova_cod_prova'];
            $concurso_cod_concurso = (int)$_POST['concurso_cod_concurso'];
            $cod_questao = $_POST['cod_questao'] ?? null;

            // Verificar se a questão já está registrada
            $check_sql = "SELECT * FROM questao WHERE pergunta='$pergunta'";
            if ($cod_questao) {
                $check_sql .= " AND cod_questao != $cod_questao";
            }
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                $error_message = "Erro: questão já registrada";
            } else {
                if ($cod_questao) {
                    // Atualizar questão
                    $sql = "UPDATE questao SET pergunta='$pergunta', resposta1='$resposta1', resposta2='$resposta2', resposta3='$resposta3', resposta4='$resposta4', respostacorreta='$respostacorreta', dificuldade_cod_dificuldade='$dificuldade_cod_dificuldade', disciplina_cod_disciplina='$disciplina_cod_disciplina', prova_cod_prova='$prova_cod_prova', concurso_cod_concurso='$concurso_cod_concurso' WHERE cod_questao=$cod_questao";
                } else {
                    // Inserir nova questão
                    $sql = "INSERT INTO questao (pergunta, resposta1, resposta2, resposta3, resposta4, respostacorreta, dificuldade_cod_dificuldade, disciplina_cod_disciplina, prova_cod_prova, concurso_cod_concurso) VALUES ('$pergunta', '$resposta1', '$resposta2', '$resposta3', '$resposta4', '$respostacorreta', '$dificuldade_cod_dificuldade', '$disciplina_cod_disciplina', '$prova_cod_prova', '$concurso_cod_concurso')";
                }

                if ($conn->query($sql) === TRUE) {
                    echo "<script>var saveSuccessful = true;</script>";
                    $success_message = "Registro salvo com sucesso!";
                } else {
                    $error_message = "Erro: " . $conn->error;
                }                
            }
        }

        // Excluir questão
        if (isset($_GET['delete'])) {
            $cod_questao = (int)$_GET['delete'];
            $sql = "DELETE FROM questao WHERE cod_questao=$cod_questao";
            if ($conn->query($sql) === TRUE) {
                $success_message = "Registro excluído com sucesso!";
            } else {
                $error_message = "Erro: " . $conn->error;
            }
        }

        // Preencher os campos do modal para edição
        $cod_questao = $_GET['edit'] ?? null;
        $pergunta = '';
        $resposta1 = '';
        $resposta2 = '';
        $resposta3 = '';
        $resposta4 = '';
        $respostacorreta = '';
        $dificuldade_cod_dificuldade = '';
        $disciplina_cod_disciplina = '';
        $prova_cod_prova = '';
        $concurso_cod_concurso = '';

        if ($cod_questao) {
            $result = $conn->query("SELECT * FROM questao WHERE cod_questao=$cod_questao");
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $pergunta = $row['pergunta'];
                $resposta1 = $row['resposta1'];
                $resposta2 = $row['resposta2'];
                $resposta3 = $row['resposta3'];
                $resposta4 = $row['resposta4'];
                $respostacorreta = $row['respostacorreta'];
                $dificuldade_cod_dificuldade = $row['dificuldade_cod_dificuldade'];
                $disciplina_cod_disciplina = $row['disciplina_cod_disciplina'];
                $prova_cod_prova = $row['prova_cod_prova'];
                $concurso_cod_concurso = $row['concurso_cod_concurso'];
            }
        }
        ?>

        <div class="table-container container-principal">
            <h2 style="margin-left:200px;">Gerenciar Questões</h2>
            <button class="btn-adicionar" onclick="openAddModal()">Adicionar Nova Questão</button>

            <?php
            // Ajustar a consulta SQL para buscar os dados das tabelas estrangeiras
            $result = $conn->query("
                SELECT q.*, d.tipo_dificuldade, disc.nome as nome_disciplina, p.nome as nome_prova, c.nome as nome_concurso
                FROM questao q
                JOIN dificuldade d ON q.dificuldade_cod_dificuldade = d.cod_dificuldade
                JOIN disciplina disc ON q.disciplina_cod_disciplina = disc.cod_disciplina
                JOIN prova p ON q.prova_cod_prova = p.cod_prova
                JOIN concurso c ON q.concurso_cod_concurso = c.cod_concurso
            ");

            if ($result->num_rows > 0) {
                echo "<table id='questaoTable' class='tabela-registros'>";
                echo "<thead><tr><th>Pergunta</th><th>Resposta 1</th><th>Resposta 2</th><th>Resposta 3</th><th>Resposta 4</th><th>Resposta Correta</th><th>Dificuldade</th><th>Disciplina</th><th>Prova</th><th>Concurso</th><th>Ações</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['pergunta']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['resposta1']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['resposta2']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['resposta3']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['resposta4']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['respostacorreta']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tipo_dificuldade']) . "</td>";  // Mostra o nome da dificuldade
                    echo "<td>" . htmlspecialchars($row['nome_disciplina']) . "</td>";  // Mostra o nome da disciplina
                    echo "<td>" . htmlspecialchars($row['nome_prova']) . "</td>";  // Mostra o nome da prova
                    echo "<td>" . htmlspecialchars($row['nome_concurso']) . "</td>";  // Mostra o nome do concurso
                    echo "<td class='actions'>";
                    echo "<button class='btn-editar' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . ")'><i class='fas fa-edit'></i></button>";
                    echo "<button class='btn-excluir' onclick='openModal(\"questao.php?delete=" . $row['cod_questao'] . "\")'><i class='fas fa-trash-alt'></i></button>";
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

<!-- Modal de Adicionar/Editar Questão -->
<div id="add-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
        <form action="questao.php" method="POST">
            <input type="hidden" id="cod_questao" name="cod_questao" value="<?php echo htmlspecialchars($cod_questao); ?>">

            <div class="grid-container">
                <div class="grid-item">
                    <label for="pergunta_modal">Pergunta:</label>
                    <input type="text" id="pergunta_modal" name="pergunta" value="<?php echo htmlspecialchars($pergunta); ?>" placeholder="Preencha a pergunta" required>
                </div>
                <div class="grid-item">
                    <label for="resposta1_modal">Resposta 1:</label>
                    <input type="text" id="resposta1_modal" name="resposta1" value="<?php echo htmlspecialchars($resposta1); ?>" placeholder="Preencha a resposta 1" required>
                </div>
                <div class="grid-item">
                    <label for="resposta2_modal">Resposta 2:</label>
                    <input type="text" id="resposta2_modal" name="resposta2" value="<?php echo htmlspecialchars($resposta2); ?>" placeholder="Preencha a resposta 2" required>
                </div>
                <div class="grid-item">
                    <label for="resposta3_modal">Resposta 3:</label>
                    <input type="text" id="resposta3_modal" name="resposta3" value="<?php echo htmlspecialchars($resposta3); ?>" placeholder="Preencha a resposta 3" required>
                </div>
                <div class="grid-item">
                    <label for="resposta4_modal">Resposta 4:</label>
                    <input type="text" id="resposta4_modal" name="resposta4" value="<?php echo htmlspecialchars($resposta4); ?>" placeholder="Preencha a resposta 4" required>
                </div>
                <div class="grid-item">
                <label for="respostacorreta_modal" style="color: #4CAF50; font-weight: bold;">Resposta Correta:</label>
                    <input type="text" id="respostacorreta_modal" name="respostacorreta" value="<?php echo htmlspecialchars($respostacorreta); ?>" placeholder="Preencha a resposta correta" required>
                </div>
                <div class="grid-item">
                    <label for="dificuldade_cod_dificuldade">Dificuldade:</label>
                    <select id="dificuldade_cod_dificuldade" name="dificuldade_cod_dificuldade" required>
                        <option value="">Selecione uma Dificuldade</option>
                        <?php
                        $dificuldades = $conn->query("SELECT * FROM dificuldade");
                        while ($dificuldade = $dificuldades->fetch_assoc()) {
                            $selected = (isset($dificuldade_cod_dificuldade) && $dificuldade['cod_dificuldade'] == $dificuldade_cod_dificuldade) ? ' selected' : '';
                            echo "<option value='" . $dificuldade['cod_dificuldade'] . "'" . $selected . ">" . htmlspecialchars($dificuldade['tipo_dificuldade']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="grid-item">
                    <label for="disciplina_cod_disciplina">Disciplina:</label>
                    <select id="disciplina_cod_disciplina" name="disciplina_cod_disciplina" required>
                        <option value="">Selecione uma Disciplina</option>
                        <?php
                        $disciplinas = $conn->query("SELECT * FROM disciplina");
                        while ($disciplina = $disciplinas->fetch_assoc()) {
                            $selected = (isset($disciplina_cod_disciplina) && $disciplina['cod_disciplina'] == $disciplina_cod_disciplina) ? ' selected' : '';
                            echo "<option value='" . $disciplina['cod_disciplina'] . "'" . $selected . ">" . htmlspecialchars($disciplina['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="grid-item">
                    <label for="prova_cod_prova">Prova:</label>
                    <select id="prova_cod_prova" name="prova_cod_prova" required>
                        <option value="">Selecione uma Prova</option>
                        <?php
                        $provas = $conn->query("SELECT * FROM prova");
                        while ($prova = $provas->fetch_assoc()) {
                            $selected = (isset($prova_cod_prova) && $prova['cod_prova'] == $prova_cod_prova) ? ' selected' : '';
                            echo "<option value='" . $prova['cod_prova'] . "'" . $selected . ">" . htmlspecialchars($prova['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="grid-item">
                    <label for="concurso_cod_concurso">Concurso:</label>
                    <select id="concurso_cod_concurso" name="concurso_cod_concurso" required>
                        <option value="">Selecione um Concurso</option>
                        <?php
                        $concursos = $conn->query("SELECT * FROM concurso");
                        while ($concurso = $concursos->fetch_assoc()) {
                            $selected = (isset($concurso_cod_concurso) && $concurso['cod_concurso'] == $concurso_cod_concurso) ? ' selected' : '';
                            echo "<option value='" . $concurso['cod_concurso'] . "'" . $selected . ">" . htmlspecialchars($concurso['nome']) . "</option>";
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
    setCookie("cod_questao", document.getElementById("cod_questao").value, 7);
    setCookie("pergunta", document.getElementById("pergunta_modal").value, 7);
    setCookie("resposta1", document.getElementById("resposta1_modal").value, 7);
    setCookie("resposta2", document.getElementById("resposta2_modal").value, 7);
    setCookie("resposta3", document.getElementById("resposta3_modal").value, 7);
    setCookie("resposta4", document.getElementById("resposta4_modal").value, 7);
    setCookie("respostacorreta", document.getElementById("respostacorreta_modal").value, 7);
    setCookie("dificuldade_cod_dificuldade", document.getElementById("dificuldade_cod_dificuldade").value, 7);
    setCookie("disciplina_cod_disciplina", document.getElementById("disciplina_cod_disciplina").value, 7);
    setCookie("prova_cod_prova", document.getElementById("prova_cod_prova").value, 7);
    setCookie("concurso_cod_concurso", document.getElementById("concurso_cod_concurso").value, 7);
}

// Função para carregar cookies nos campos do formulário
function loadCookies() {
    document.getElementById("cod_questao").value = getCookie("cod_questao") || '';
    document.getElementById("pergunta_modal").value = getCookie("pergunta") || '';
    document.getElementById("resposta1_modal").value = getCookie("resposta1") || '';
    document.getElementById("resposta2_modal").value = getCookie("resposta2") || '';
    document.getElementById("resposta3_modal").value = getCookie("resposta3") || '';
    document.getElementById("resposta4_modal").value = getCookie("resposta4") || '';
    document.getElementById("respostacorreta_modal").value = getCookie("respostacorreta") || '';
    document.getElementById("dificuldade_cod_dificuldade").value = getCookie("dificuldade_cod_dificuldade") || '';
    document.getElementById("disciplina_cod_disciplina").value = getCookie("disciplina_cod_disciplina") || '';
    document.getElementById("prova_cod_prova").value = getCookie("prova_cod_prova") || '';
    document.getElementById("concurso_cod_concurso").value = getCookie("concurso_cod_concurso") || '';
}

// Função para mostrar o modal de sucesso e fechar o modal de adição/edição
function showSuccess(message) {
    document.getElementById('sucesso-mensagem').textContent = message;
    document.getElementById('modal-sucesso').style.display = 'block';

    // Fechar o modal de adição/edição e limpar o formulário
    closeAddModal();
    clearForm();
}

// Função para fechar os modais
function closeModal(modalType) {
    if (modalType === 'sucesso') {
        document.getElementById('modal-sucesso').style.display = 'none';
    } else if (modalType === 'erro') {
        document.getElementById('modal-erro').style.display = 'none';
    } else {
        document.getElementById('confirm-modal').style.display = 'none';
    }
}

// Função para abrir o modal de confirmação
function openModal(url) {
    document.getElementById('confirm-modal').style.display = 'block';
    document.getElementById('confirm-delete').onclick = function () {
        window.location.href = url;
    };
}

// Função para abrir o modal de adição/edição de questão
function openAddModal() {
    document.getElementById('add-modal').style.display = 'block';
}

// Função para fechar o modal de adição/edição de questão
function closeAddModal() {
    document.getElementById('add-modal').style.display = 'none';
}

// Função para abrir o modal de edição de questão com os dados carregados
function openEditModal(data) {
    document.getElementById('cod_questao').value = data.cod_questao;
    document.getElementById('pergunta_modal').value = data.pergunta;
    document.getElementById('resposta1_modal').value = data.resposta1;
    document.getElementById('resposta2_modal').value = data.resposta2;
    document.getElementById('resposta3_modal').value = data.resposta3;
    document.getElementById('resposta4_modal').value = data.resposta4;
    document.getElementById('respostacorreta_modal').value = data.respostacorreta;
    document.getElementById('dificuldade_cod_dificuldade').value = data.dificuldade_cod_dificuldade;
    document.getElementById('disciplina_cod_disciplina').value = data.disciplina_cod_disciplina;
    document.getElementById('prova_cod_prova').value = data.prova_cod_prova;
    document.getElementById('concurso_cod_concurso').value = data.concurso_cod_concurso;
    openAddModal();
}

// Função para limpar o formulário
function clearForm() {
    document.getElementById('cod_questao').value = '';
    document.getElementById('pergunta_modal').value = '';
    document.getElementById('resposta1_modal').value = '';
    document.getElementById('resposta2_modal').value = '';
    document.getElementById('resposta3_modal').value = '';
    document.getElementById('resposta4_modal').value = '';
    document.getElementById('respostacorreta_modal').value = '';
    document.getElementById('dificuldade_cod_dificuldade').selectedIndex = 0;
    document.getElementById('disciplina_cod_disciplina').selectedIndex = 0;
    document.getElementById('prova_cod_prova').selectedIndex = 0;
    document.getElementById('concurso_cod_concurso').selectedIndex = 0;
}

// Adicionando funcionalidade aos botões OK dos modais de erro e sucesso
document.getElementById("ok-btn-erro").onclick = function() {
    closeModal('erro');
};
document.getElementById("ok-btn-sucesso").onclick = function() {
    closeModal('sucesso');
};

document.addEventListener('DOMContentLoaded', function() {
    <?php if ($error_message): ?>
        document.getElementById('erro-mensagem').textContent = '<?php echo htmlspecialchars($error_message); ?>';
        document.getElementById('modal-erro').style.display = "block";
    <?php elseif ($success_message): ?>
        showSuccess('<?php echo htmlspecialchars($success_message); ?>');
        if (typeof saveSuccessful !== 'undefined' && saveSuccessful) {
            closeAddModal();
            clearForm();
        }
    <?php endif; ?>
});


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