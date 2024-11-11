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
    $desc1 = mysqli_real_escape_string($conn, $_POST['desc1']);
    $resposta2 = mysqli_real_escape_string($conn, $_POST['resposta2']);
    $desc2 = mysqli_real_escape_string($conn, $_POST['desc2']);
    $resposta3 = mysqli_real_escape_string($conn, $_POST['resposta3']);
    $desc3 = mysqli_real_escape_string($conn, $_POST['desc3']);
    $resposta4 = mysqli_real_escape_string($conn, $_POST['resposta4']);
    $desc4 = mysqli_real_escape_string($conn, $_POST['desc4']);
    $respostacorreta = mysqli_real_escape_string($conn, $_POST['respostacorreta']);
    $desc_correta = mysqli_real_escape_string($conn, $_POST['desc_correta']);
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
            $sql = "UPDATE questao SET pergunta='$pergunta', resposta1='$resposta1', desc1='$desc1',
                    resposta2='$resposta2', desc2='$desc2', resposta3='$resposta3', desc3='$desc3',
                    resposta4='$resposta4', desc4='$desc4', respostacorreta='$respostacorreta', desc_correta='$desc_correta',
                    dificuldade_cod_dificuldade='$dificuldade_cod_dificuldade', disciplina_cod_disciplina='$disciplina_cod_disciplina',
                    prova_cod_prova='$prova_cod_prova', concurso_cod_concurso='$concurso_cod_concurso' WHERE cod_questao=$cod_questao";
        } else {
            // Inserir nova questão
            $sql = "INSERT INTO questao (pergunta, resposta1, desc1, resposta2, desc2, resposta3, desc3, resposta4, desc4, 
                    respostacorreta, desc_correta, dificuldade_cod_dificuldade, disciplina_cod_disciplina, prova_cod_prova, concurso_cod_concurso) 
                    VALUES ('$pergunta', '$resposta1', '$desc1', '$resposta2', '$desc2', '$resposta3', '$desc3', '$resposta4', '$desc4', 
                    '$respostacorreta', '$desc_correta', '$dificuldade_cod_dificuldade', '$disciplina_cod_disciplina', '$prova_cod_prova', '$concurso_cod_concurso')";
        }

        if ($conn->query($sql) === TRUE) {
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
?>

<div class="table-container container-principal">
    <h2 style="margin-left:200px;">Gerenciar Questões</h2>
    <button class="btn-adicionar" onclick="openAddModal()">Adicionar Nova Questão</button>

    <?php
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
        echo "<thead><tr><th>Pergunta</th><th>Resp 1</th><th>Desc 1</th><th>Resp 2</th><th>Desc 2</th><th>Resp 3</th><th>Desc 3</th><th>Resp 4</th><th>Desc 4</th><th>Resp Correta</th><th>Desc Correta</th><th>Dificuldade</th><th>Disciplina</th><th>Prova</th><th>Concurso</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['pergunta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta1']) . "</td>";
            echo "<td>" . htmlspecialchars($row['desc1']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta2']) . "</td>";
            echo "<td>" . htmlspecialchars($row['desc2']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta3']) . "</td>";
            echo "<td>" . htmlspecialchars($row['desc3']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta4']) . "</td>";
            echo "<td>" . htmlspecialchars($row['desc4']) . "</td>";
            echo "<td>" . htmlspecialchars($row['respostacorreta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['desc_correta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipo_dificuldade']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome_disciplina']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome_prova']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome_concurso']) . "</td>";
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

<div id="add-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
        <form action="questao.php" method="POST">
            <input type="hidden" id="cod_questao" name="cod_questao">

            <div class="question-container">
                <label for="pergunta_modal" class="highlight-question">Pergunta:</label>
                <input type="text" id="pergunta_modal" name="pergunta" placeholder="Preencha a pergunta" class="question-field" required>
            </div>

            <div class="answers-container">
                <div class="horizontal-answers">
                    <div>
                        <label for="resposta1_modal">Resposta 1:</label>
                        <input type="text" id="resposta1_modal" name="resposta1" placeholder="Resposta 1" required>
                        <label for="desc1_modal">Descrição 1:</label>
                        <input type="text" id="desc1_modal" name="desc1" placeholder="Descrição Resposta 1" required>
                    </div>
                    <div>
                        <label for="resposta2_modal">Resposta 2:</label>
                        <input type="text" id="resposta2_modal" name="resposta2" placeholder="Resposta 2" required>
                        <label for="desc2_modal">Descrição 2:</label>
                        <input type="text" id="desc2_modal" name="desc2" placeholder="Descrição Resposta 2" required>
                    </div>
                </div>
                <div class="horizontal-answers">
                    <div>
                        <label for="resposta3_modal">Resposta 3:</label>
                        <input type="text" id="resposta3_modal" name="resposta3" placeholder="Resposta 3" required>
                        <label for="desc3_modal">Descrição 3:</label>
                        <input type="text" id="desc3_modal" name="desc3" placeholder="Descrição Resposta 3" required>
                    </div>
                    <div>
                        <label for="resposta4_modal">Resposta 4:</label>
                        <input type="text" id="resposta4_modal" name="resposta4" placeholder="Resposta 4" required>
                        <label for="desc4_modal">Descrição 4:</label>
                        <input type="text" id="desc4_modal" name="desc4" placeholder="Descrição Resposta 4" required>
                    </div>
                </div>
                <div class="vertical-answer">
                    <label for="respostacorreta_modal" style="color: #4CAF50; font-weight: bold;">Resposta Correta:</label>
                    <input type="text" id="respostacorreta_modal" name="respostacorreta" placeholder="Resposta Correta" required>
                    <label for="desc_correta_modal">Descrição Correta:</label>
                    <input type="text" id="desc_correta_modal" name="desc_correta" placeholder="Descrição Resposta Correta" required>
                </div>
            </div>

            <div class="details-container">
                <label for="dificuldade_cod_dificuldade">Dificuldade:</label>
                <select id="dificuldade_cod_dificuldade" name="dificuldade_cod_dificuldade" required>
                    <option value="">Selecione uma Dificuldade</option>
                    <?php
                    $dificuldades = $conn->query("SELECT cod_dificuldade, tipo_dificuldade FROM dificuldade");
                    if ($dificuldades->num_rows > 0) {
                        while ($dificuldade = $dificuldades->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($dificuldade['cod_dificuldade']) . "'>" . htmlspecialchars($dificuldade['tipo_dificuldade']) . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="disciplina_cod_disciplina">Disciplina:</label>
                <select id="disciplina_cod_disciplina" name="disciplina_cod_disciplina" required>
                    <option value="">Selecione uma Disciplina</option>
                    <?php
                    $disciplinas = $conn->query("SELECT cod_disciplina, nome FROM disciplina");
                    if ($disciplinas->num_rows > 0) {
                        while ($disciplina = $disciplinas->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($disciplina['cod_disciplina']) . "'>" . htmlspecialchars($disciplina['nome']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="details-container">
                <label for="prova_cod_prova">Prova:</label>
                <select id="prova_cod_prova" name="prova_cod_prova" required>
                    <option value="">Selecione uma Prova</option>
                    <?php
                    $provas = $conn->query("SELECT cod_prova, nome FROM prova");
                    if ($provas->num_rows > 0) {
                        while ($prova = $provas->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($prova['cod_prova']) . "'>" . htmlspecialchars($prova['nome']) . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="concurso_cod_concurso">Concurso:</label>
                <select id="concurso_cod_concurso" name="concurso_cod_concurso" required>
                    <option value="">Selecione um Concurso</option>
                    <?php
                    $concursos = $conn->query("SELECT cod_concurso, nome FROM concurso");
                    if ($concursos->num_rows > 0) {
                        while ($concurso = $concursos->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($concurso['cod_concurso']) . "'>" . htmlspecialchars($concurso['nome']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="button-container">
                <button type="submit" class="save-button">Salvar</button>
                <button type="button" class="clear-button" onclick="clearForm()">Limpar</button>
            </div>
        </form>
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

// Função para fechar o modal de adicionar
function closeAddModal() {
    saveCookies();
    addModal.style.display = "none";
}

// Função para abrir o modal de edição com dados da questão
function openEditModal(data) {
    const fields = [
        "cod_questao",
        "pergunta_modal",
        "resposta1_modal",
        "desc1_modal",
        "resposta2_modal",
        "desc2_modal",
        "resposta3_modal",
        "desc3_modal",
        "resposta4_modal",
        "desc4_modal",
        "respostacorreta_modal",
        "desc_correta_modal",
        "dificuldade_cod_dificuldade",
        "disciplina_cod_disciplina",
        "prova_cod_prova",
        "concurso_cod_concurso"
    ];

    fields.forEach(id => {
        if (data[id]) {
            document.getElementById(id).value = data[id];
        }
    });

    addModal.style.display = 'block';
}

// Função para abrir o modal de confirmação para deletar
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

// Limpar formulário
function clearForm() {
    const fields = [
        "cod_questao",
        "pergunta_modal",
        "resposta1_modal",
        "desc1_modal",
        "resposta2_modal",
        "desc2_modal",
        "resposta3_modal",
        "desc3_modal",
        "resposta4_modal",
        "desc4_modal",
        "respostacorreta_modal",
        "desc_correta_modal",
        "dificuldade_cod_dificuldade",
        "disciplina_cod_disciplina",
        "prova_cod_prova",
        "concurso_cod_concurso"
    ];

    fields.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.value = '';
        }
    });
}

// Função para salvar os valores dos inputs em cookies
function saveCookies() {
    const fields = [
        "cod_questao",
        "pergunta_modal",
        "resposta1_modal",
        "desc1_modal",
        "resposta2_modal",
        "desc2_modal",
        "resposta3_modal",
        "desc3_modal",
        "resposta4_modal",
        "desc4_modal",
        "respostacorreta_modal",
        "desc_correta_modal",
        "dificuldade_cod_dificuldade",
        "disciplina_cod_disciplina",
        "prova_cod_prova",
        "concurso_cod_concurso"
    ];

    fields.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            document.cookie = id + "=" + encodeURIComponent(element.value) + "; path=/";
        }
    });
}

// Função para carregar os valores dos cookies nos inputs
function loadCookies() {
    const cookies = document.cookie.split(';');
    cookies.forEach(function(cookie) {
        const parts = cookie.split('=');
        const name = parts[0].trim();
        const value = parts[1] ? decodeURIComponent(parts[1].trim()) : '';

        const element = document.getElementById(name);
        if (element) {
            element.value = value;
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

// Salvar automaticamente os dados quando a página for recarregada ou fechada
window.addEventListener('beforeunload', function () {
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