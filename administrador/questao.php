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
            $desc1 = mysqli_real_escape_string($conn, $_POST['desc1']);
            $desc2 = mysqli_real_escape_string($conn, $_POST['desc2']);
            $desc3 = mysqli_real_escape_string($conn, $_POST['desc3']);
            $desc4 = mysqli_real_escape_string($conn, $_POST['desc4']);
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
                    $sql = "UPDATE questao SET pergunta='$pergunta', desc1='$desc1', desc2='$desc2', desc3='$desc3',
                            desc4='$desc4', desc_correta='$desc_correta', dificuldade_cod_dificuldade='$dificuldade_cod_dificuldade',
                            disciplina_cod_disciplina='$disciplina_cod_disciplina', prova_cod_prova='$prova_cod_prova',
                            concurso_cod_concurso='$concurso_cod_concurso' WHERE cod_questao=$cod_questao";
                } else {
                    // Inserir nova questão
                    $sql = "INSERT INTO questao (pergunta, desc1, desc2, desc3, desc4, desc_correta,
                            dificuldade_cod_dificuldade, disciplina_cod_disciplina, prova_cod_prova, concurso_cod_concurso)
                            VALUES ('$pergunta', '$desc1', '$desc2', '$desc3', '$desc4', '$desc_correta',
                            '$dificuldade_cod_dificuldade', '$disciplina_cod_disciplina', '$prova_cod_prova', '$concurso_cod_concurso')";
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

      $records_per_page = 1; // Um concurso por página
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Obter o concurso atual
$sql_concursos_pag = "
    SELECT DISTINCT c.cod_concurso, c.nome AS nome_concurso, c.qtd_questoes
    FROM concurso c
    LIMIT $start_from, $records_per_page";
$result_concursos_pag = $conn->query($sql_concursos_pag);
$concurso_atual = $result_concursos_pag->fetch_assoc();

$cod_concurso_atual = $concurso_atual['cod_concurso'];
$nome_concurso_atual = $concurso_atual['nome_concurso'];
$total_questoes_concurso = $concurso_atual['qtd_questoes'];

// Contar o total de registros para calcular o total de páginas
$total_concursos_sql = "SELECT COUNT(*) AS total FROM concurso";
$total_concursos_result = $conn->query($total_concursos_sql);
$total_concursos = $total_concursos_result->fetch_assoc()['total'];
$total_pages = ceil($total_concursos / $records_per_page); // Calcular o número total de páginas

// Determinar o status do concurso
$count_questoes_sql = "SELECT COUNT(*) AS total_questoes FROM questao WHERE concurso_cod_concurso = $cod_concurso_atual";
$count_result = $conn->query($count_questoes_sql);
$total_questoes_cadastradas = $count_result->fetch_assoc()['total_questoes'];
$status = $total_questoes_cadastradas > $total_questoes_concurso ? "Excedido" : ($total_questoes_cadastradas === $total_questoes_concurso ? "Cadastrado" : "Pendente");
$faltantes = max(0, $total_questoes_concurso - $total_questoes_cadastradas);

        ?>
        

<style>
    .status {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
        text-align: center;
    }
    .status.cadastrado {
        color: #fff;
        background-color: #28a745; /* Verde */
    }
    .status.pendente {
        color: #000;
        background-color: #ffc107; /* Amarelo */
    }
    .pagination {
        list-style: none;
        display: flex;
        justify-content: center;
        padding: 0;
    }
    .pagination li {
        margin: 0 5px;
    }
    .pagination li a {
        text-decoration: none;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .pagination li.active a {
        background-color: #2118CD;
        color: #fff;
    }
</style>

<div class="table-container container-principal">
    <h2>Gerenciar Questões - <?php echo htmlspecialchars($nome_concurso_atual); ?></h2>
    <p>Status do Concurso: 
        <span class="status <?php echo strtolower($status); ?>" 
              style="<?php echo $status === 'Excedido' ? 'color: #fff; background-color: #dc3545;' : ''; ?>">
            <?php echo $status; ?>
        </span>
    </p>
    <?php if ($status === 'Pendente'): ?>
        <p>Faltam <?php echo $faltantes; ?> questões para completar o concurso.</p>
    <?php elseif ($status === 'Excedido'): ?>
        <p style="color: #dc3545;">O concurso excedeu a quantidade de questões permitidas.</p>
    <?php endif; ?>

    <!-- Formulário de Filtro -->
    <form method="GET" action="" class="form-filtro" style="margin-bottom: 20px;">
        <input type="text" name="filtro" placeholder="Pesquisar questões" 
               value="<?php echo htmlspecialchars($filtro ?? ''); ?>" 
               style="width: 300px; padding: 10px; font-size: 16px; border-radius: 5px; border: 1px solid #ccc;">
        <button type="submit" class="btn-filtrar" 
                style="background-color: #2118CD; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            Filtrar
        </button>
    </form>

    <!-- Botão Adicionar Nova Questão -->
    <button onclick="document.getElementById('add-modal').style.display='block'" 
            style="background-color:#2118CD; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 20px;">
        Adicionar Nova Questão
    </button>

    <!-- Tabela -->
    <?php
    // Adicionar filtro na query de busca
    $filtro = isset($_GET['filtro']) ? $conn->real_escape_string($_GET['filtro']) : '';
    $sql = "
        SELECT q.*, d.tipo_dificuldade, disc.nome AS nome_disciplina, 
               p.nome AS nome_prova
        FROM questao q
        JOIN dificuldade d ON q.dificuldade_cod_dificuldade = d.cod_dificuldade
        JOIN disciplina disc ON q.disciplina_cod_disciplina = disc.cod_disciplina
        JOIN prova p ON q.prova_cod_prova = p.cod_prova
        WHERE q.concurso_cod_concurso = $cod_concurso_atual
    ";
    if (!empty($filtro)) {
        $sql .= " AND (
            q.pergunta LIKE '%$filtro%' OR
            disc.nome LIKE '%$filtro%' OR
            p.nome LIKE '%$filtro%' OR
            d.tipo_dificuldade LIKE '%$filtro%'
        )";
    }
    $result = $conn->query($sql);
    ?>

    <?php if ($result->num_rows > 0): ?>
        <table id="questaoTable" class="tabela-registros" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Prova</th>
                    <th>Disciplina</th>
                    <th>Dificuldade</th>
                    <th>Pergunta</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nome_prova']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome_disciplina']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo_dificuldade']); ?></td>
                        <td><?php echo htmlspecialchars($row['pergunta']); ?></td>
                        <td class="actions">
                            <button class="btn-editar" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-excluir" onclick="openModal('questao.php?delete=<?php echo $row['cod_questao']; ?>')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted text-center">Nenhuma questão encontrada para este concurso.</p>
    <?php endif; ?>

  <!-- Paginação -->
  <?php if ($total_pages > 1): ?>
        <div class="pagination-container">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li><a href="?page=<?php echo $page - 1; ?>">Anterior</a></li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <li><a href="?page=<?php echo $page + 1; ?>">Próximo</a></li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>


    <?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', 'admin', 'topapirando');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$records_per_page = 1; // Um concurso por página
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Contar o total de concursos
$total_concursos_sql = "SELECT COUNT(*) AS total FROM concurso";
$total_concursos_result = $conn->query($total_concursos_sql);
$total_concursos = $total_concursos_result->fetch_assoc()['total'];
$total_pages = ceil($total_concursos / $records_per_page); // Calcular o total de páginas

// Obter o concurso atual
$sql_concursos_pag = "
    SELECT DISTINCT c.cod_concurso, c.nome AS nome_concurso, c.qtd_questoes
    FROM concurso c
    LIMIT $start_from, $records_per_page";
$result_concursos_pag = $conn->query($sql_concursos_pag);
$concurso_atual = $result_concursos_pag->fetch_assoc();

$cod_concurso_atual = $concurso_atual['cod_concurso'];
$nome_concurso_atual = $concurso_atual['nome_concurso'];
$total_questoes_concurso = $concurso_atual['qtd_questoes'];

// Continue com o restante do código...
?>

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
<!-- Modal para adicionar/editar questão -->
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
                        <label for="desc1_modal">Descrição 1:</label>
                        <input type="text" id="desc1_modal" name="desc1" placeholder="Descrição Resposta 1" required>
                    </div>
                    <div>
                        <label for="desc2_modal">Descrição 2:</label>
                        <input type="text" id="desc2_modal" name="desc2" placeholder="Descrição Resposta 2" required>
                    </div>
                </div>
                <div class="horizontal-answers">
                    <div>
                        <label for="desc3_modal">Descrição 3:</label>
                        <input type="text" id="desc3_modal" name="desc3" placeholder="Descrição Resposta 3" required>
                    </div>
                    <div>
                        <label for="desc4_modal">Descrição 4:</label>
                        <input type="text" id="desc4_modal" name="desc4" placeholder="Descrição Resposta 4" required>
                    </div>
                </div>
                <div class="vertical-answer">
                    <label for="desc_correta_modal" style="color: #4CAF50; font-weight: bold;">Descrição Correta:</label>
                    <input type="text" id="desc_correta_modal" name="desc_correta" placeholder="Descrição da Resposta Correta" required>
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

function openEditModal(data) {
    if (!data) {
        alert("Dados inválidos para edição.");
        return;
    }

    console.log("Dados recebidos para edição:", data); // Debugging: Log the data

    // Map fields in the modal to the data received
    const fieldMapping = {
        "cod_questao": "cod_questao",
        "pergunta": "pergunta_modal",
        "desc1": "desc1_modal",
        "desc2": "desc2_modal",
        "desc3": "desc3_modal",
        "desc4": "desc4_modal",
        "desc_correta": "desc_correta_modal",
        "dificuldade_cod_dificuldade": "dificuldade_cod_dificuldade",
        "disciplina_cod_disciplina": "disciplina_cod_disciplina",
        "prova_cod_prova": "prova_cod_prova",
        "concurso_cod_concurso": "concurso_cod_concurso"
    };

    // Iterate through the mapping and assign values
    for (const key in fieldMapping) {
        const fieldId = fieldMapping[key];
        const fieldElement = document.getElementById(fieldId);

        if (fieldElement) {
            fieldElement.value = data[key] !== undefined ? data[key] : '';
        }
    }

    // Open the modal
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
        "desc1_modal",
        "desc2_modal",
        "desc3_modal",
        "desc4_modal",
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
        "desc1_modal",
        "desc2_modal",
        "desc3_modal",
        "desc4_modal",
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