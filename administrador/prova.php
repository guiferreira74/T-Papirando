
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
    <title>Gerenciar Provas</title>
    <link rel="stylesheet" href="prova.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    $nome = $_POST['nome'];
    $tempo = $_POST['tempo'];
    $qtd_questoes = $_POST['qtd_questoes'];
    $banca_cod_banca = $_POST['banca_cod_banca'];
    $disciplina_cod_disciplina = $_POST['disciplina_cod_disciplina'];
    $concurso_cod_concurso = $_POST['concurso_cod_concurso'];
    $cod_prova = $_POST['cod_prova'] ?? null;

    // Verificar se a prova já está registrada
    $check_sql = "SELECT * FROM prova WHERE nome='$nome' AND banca_cod_banca=$banca_cod_banca AND disciplina_cod_disciplina=$disciplina_cod_disciplina AND concurso_cod_concurso=$concurso_cod_concurso";
    if ($cod_prova) {
        $check_sql .= " AND cod_prova != $cod_prova";
    }
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $error_message = "Erro: prova já registrada";
    } else {
        if ($cod_prova) {
            // Atualizar registro
            $sql = "UPDATE prova SET nome='$nome', tempo='$tempo', qtd_questoes='$qtd_questoes', banca_cod_banca=$banca_cod_banca, disciplina_cod_disciplina=$disciplina_cod_disciplina, concurso_cod_concurso=$concurso_cod_concurso WHERE cod_prova=$cod_prova";
        } else {
            // Inserir novo registro
            $sql = "INSERT INTO prova (nome, tempo, qtd_questoes, banca_cod_banca, disciplina_cod_disciplina, concurso_cod_concurso) VALUES ('$nome', '$tempo', '$qtd_questoes', $banca_cod_banca, $disciplina_cod_disciplina, $concurso_cod_concurso)";
        }

        if ($conn->query($sql) === TRUE) {
            $success_message = "Registro salvo com sucesso!";
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('sucesso-mensagem').textContent = '$success_message';
                        modalSucesso.style.display = 'block';
                        clearForm();
                        closeAddModal();
                    });
                  </script>";
        } else {
            $error_message = "Erro: " . $conn->error;
        }
    }
}

// Excluir registro
if (isset($_GET['delete'])) {
    $cod_prova = $_GET['delete'];
    $sql = "DELETE FROM prova WHERE cod_prova=$cod_prova";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Registro excluído com sucesso!";
    } else {
        $error_message = "Erro: " . $conn->error;
    }
}

// Preencher os campos do modal para edição
$cod_prova = $_GET['edit'] ?? null;
$nome = '';
$tempo = '';
$qtd_questoes = '';
$banca_cod_banca = '';
$disciplina_cod_disciplina = '';
$concurso_cod_concurso = '';

if ($cod_prova) {
    $result = $conn->query("SELECT * FROM prova WHERE cod_prova=$cod_prova");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome = $row['nome'];
        $tempo = $row['tempo'];
        $qtd_questoes = $row['qtd_questoes'];
        $banca_cod_banca = $row['banca_cod_banca'];
        $disciplina_cod_disciplina = $row['disciplina_cod_disciplina'];
        $concurso_cod_concurso = $row['concurso_cod_concurso'];
    }
}

// Obter as bancas
$bancas_result = $conn->query("SELECT * FROM banca");

// Obter as disciplinas
$disciplinas_result = $conn->query("SELECT * FROM disciplina");

// Obter os concursos
$concursos_result = $conn->query("SELECT * FROM concurso");
?>


<div class="table-container container-principal">
    <h2 style="margin-left:200px;">Gerenciar Provas</h2>

    <form method="GET" action="" class="form-filtro" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="filtro" placeholder="Pesquisar qualquer informação da tabela" 
               value="<?php echo isset($_GET['filtro']) ? htmlspecialchars($_GET['filtro']) : ''; ?>" 
               style="margin-left:200px;width: 300px; padding: 10px; font-size: 16px; border-radius: 5px; border: 1px solid #ccc;">
        <button type="submit" class="btn-filtrar" 
                style="background-color: #2118CD; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Filtrar</button>
    </form>

    <button class="btn-adicionar" onclick="openAddModal()" 
            style="background-color:#2118CD; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Adicionar Nova Prova</button>

    <?php
    // Configuração de paginação
    $records_per_page = 6;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $start_from = ($page - 1) * $records_per_page;

    // Obter filtro
    $filtro = isset($_GET['filtro']) ? $conn->real_escape_string($_GET['filtro']) : '';

    // Consulta SQL com filtro e paginação
    $sql = "
        SELECT 
            prova.cod_prova, 
            prova.nome AS prova_nome, 
            prova.tempo, 
            prova.qtd_questoes, 
            banca.cod_banca, 
            banca.nome AS banca_nome, 
            disciplina.cod_disciplina,
            disciplina.nome AS disciplina_nome,
            concurso.cod_concurso,
            concurso.nome AS concurso_nome
        FROM prova
        INNER JOIN banca ON prova.banca_cod_banca = banca.cod_banca
        INNER JOIN disciplina ON prova.disciplina_cod_disciplina = disciplina.cod_disciplina
        INNER JOIN concurso ON prova.concurso_cod_concurso = concurso.cod_concurso
    ";

    // Adicionar filtro
    if (!empty($filtro)) {
        $sql .= " WHERE 
            prova.nome LIKE '%$filtro%' OR
            banca.nome LIKE '%$filtro%' OR
            disciplina.nome LIKE '%$filtro%' OR
            concurso.nome LIKE '%$filtro%'
        ";
    }

    // Adicionar limite e offset para paginação
    $sql .= " LIMIT $start_from, $records_per_page";
    $result = $conn->query($sql);

    // Consulta para contar o total de registros (para paginação)
    $count_sql = "
        SELECT COUNT(*) as total
        FROM prova
        INNER JOIN banca ON prova.banca_cod_banca = banca.cod_banca
        INNER JOIN disciplina ON prova.disciplina_cod_disciplina = disciplina.cod_disciplina
        INNER JOIN concurso ON prova.concurso_cod_concurso = concurso.cod_concurso
    ";
    if (!empty($filtro)) {
        $count_sql .= " WHERE 
            prova.nome LIKE '%$filtro%' OR
            banca.nome LIKE '%$filtro%' OR
            disciplina.nome LIKE '%$filtro%' OR
            concurso.nome LIKE '%$filtro%'
        ";
    }

    $count_result = $conn->query($count_sql);
    $total_records = $count_result->fetch_assoc()['total'];
    $total_pages = ceil($total_records / $records_per_page);

    // Exibir tabela
    if ($result->num_rows > 0) {
        echo "<table id='provaTable' class='tabela-registros'>";
        echo "<thead><tr><th>Nome da Prova</th><th>Tempo</th><th>Qtd. de Questões</th><th>Banca</th><th>Disciplina</th><th>Concurso</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['prova_nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tempo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['qtd_questoes']) . "</td>";
            echo "<td>" . htmlspecialchars($row['banca_nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['disciplina_nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['concurso_nome']) . "</td>";
            echo "<td class='actions'>";
            echo "<button class='btn-editar' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . ")'><i class='fas fa-edit'></i></button>";
            echo "<button class='btn-excluir' onclick='openModal(\"prova.php?delete=" . $row['cod_prova'] . "\")'><i class='fas fa-trash-alt'></i></button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        // Exibir paginação
        echo "<div class='pagination-container'>";
        echo "<ul class='pagination'>";
        if ($page > 1) {
            echo "<li><a href='?page=" . ($page - 1) . "&filtro=$filtro'>Anterior</a></li>";
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<li class='" . ($i == $page ? 'active' : '') . "'><a href='?page=$i&filtro=$filtro'>$i</a></li>";
        }
        if ($page < $total_pages) {
            echo "<li><a href='?page=" . ($page + 1) . "&filtro=$filtro'>Próximo</a></li>";
        }
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<p class='text-muted text-center'>Nenhum registro encontrado.</p>";
    }
    ?>
</div>


<!-- Modal de Adicionar/Editar Prova -->
<div id="add-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
        <form action="prova.php" method="POST">
            <input type="hidden" id="cod_prova" name="cod_prova" value="<?php echo htmlspecialchars($cod_prova); ?>">
            <div id="input">
                <label for="nome_modal">Nome da Prova:</label>
                <input type="text" id="nome_modal" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            <div id="input">
                <label for="tempo_modal">Tempo (HH:MM:SS):</label>
                <input type="text" id="tempo_modal" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]" placeholder="HH:MM:SS" required>
            </div>
            <div id="input">
                <label for="qtd_questoes_modal">Quantidade de Questões:</label>
                <input type="number" id="qtd_questoes_modal" name="qtd_questoes" value="<?php echo htmlspecialchars($qtd_questoes); ?>" required>
            </div>
            <div id="input">
                <label for="banca_cod_banca">Banca:</label>
                <select name="banca_cod_banca" id="banca_cod_banca" required>
                    <option value="">Selecione uma banca</option>
                    <?php
                    while ($banca = $bancas_result->fetch_assoc()) {
                        $selected = ($banca['cod_banca'] == $banca_cod_banca) ? 'selected' : '';
                        echo "<option value='" . $banca['cod_banca'] . "' $selected>" . htmlspecialchars($banca['nome']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div id="input">
                <label for="disciplina_cod_disciplina">Disciplina:</label>
                <select name="disciplina_cod_disciplina" id="disciplina_cod_disciplina" required>
                    <option value="">Selecione uma disciplina</option>
                    <?php
                    while ($disciplina = $disciplinas_result->fetch_assoc()) {
                        $selected = ($disciplina['cod_disciplina'] == $disciplina_cod_disciplina) ? 'selected' : '';
                        echo "<option value='" . $disciplina['cod_disciplina'] . "' $selected>" . htmlspecialchars($disciplina['nome']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div id="input">
                <label for="concurso_cod_concurso">Concurso:</label>
                <select name="concurso_cod_concurso" id="concurso_cod_concurso" required>
                    <option value="">Selecione um concurso</option>
                    <?php
                    while ($concurso = $concursos_result->fetch_assoc()) {
                        $selected = ($concurso['cod_concurso'] == $concurso_cod_concurso) ? 'selected' : '';
                        echo "<option value='" . $concurso['cod_concurso'] . "' $selected>" . htmlspecialchars($concurso['nome']) . "</option>";
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

    // Função para fechar o modal de adicionar
    function closeAddModal() {
        saveCookies();
        addModal.style.display = "none";
    }

    function openEditModal(data) {
        // Preencher os campos do modal com os dados recebidos
        document.getElementById('cod_prova').value = data.cod_prova || '';
        document.getElementById('nome_modal').value = data.prova_nome || '';  // Preenche o campo de nome
        document.getElementById('tempo_modal').value = data.tempo || '';
        document.getElementById('qtd_questoes_modal').value = data.qtd_questoes || '';

        // Selecionar a opção correta no select de Banca
        const bancaSelect = document.getElementById('banca_cod_banca');
        bancaSelect.value = data.cod_banca || '';  // Define o valor correto da banca
    
        // Selecionar a opção correta no select de Disciplina
        const disciplinaSelect = document.getElementById('disciplina_cod_disciplina');
        disciplinaSelect.value = data.cod_disciplina || '';  // Define o valor correto da disciplina

        // Selecionar a opção correta no select de Concurso
        const concursoSelect = document.getElementById('concurso_cod_concurso');
        concursoSelect.value = data.cod_concurso || '';  // Define o valor correto do concurso

        addModal.style.display = 'block';  // Exibe o modal de edição
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
        document.getElementById("cod_prova").value = '';
        document.getElementById("nome_modal").value = '';
        document.getElementById("tempo_modal").value = '';
        document.getElementById('qtd_questoes_modal').value = '';
        document.getElementById('disciplina_cod_disciplina').value = '';
        document.getElementById("banca_cod_banca").value = '';
        document.getElementById("concurso_cod_concurso").value = '';
    }

    // Função para salvar os valores dos inputs em cookies
    function saveCookies() {
        var cod_prova = document.getElementById("cod_prova").value;
        var nome = document.getElementById("nome_modal").value;
        var tempo = document.getElementById("tempo_modal").value;
        var qtd_questoes = document.getElementById('qtd_questoes_modal').value; 
        var disciplina_cod_disciplina = document.getElementById('disciplina_cod_disciplina').value;
        var banca_cod_banca = document.getElementById("banca_cod_banca").value;
        var concurso_cod_concurso = document.getElementById("concurso_cod_concurso").value;

        document.cookie = "cod_prova=" + encodeURIComponent(cod_prova) + "; path=/";
        document.cookie = "nome=" + encodeURIComponent(nome) + "; path=/";
        document.cookie = "tempo=" + encodeURIComponent(tempo) + "; path=/";
        document.cookie = "qtd_questoes=" + encodeURIComponent(qtd_questoes) + "; path=/";
        document.cookie = "disciplina_cod_disciplina=" + encodeURIComponent(disciplina_cod_disciplina) + "; path=/";
        document.cookie = "banca_cod_banca=" + encodeURIComponent(banca_cod_banca) + "; path=/";
        document.cookie = "concurso_cod_concurso=" + encodeURIComponent(concurso_cod_concurso) + "; path=/";
    }

    // Função para carregar os valores dos cookies nos inputs
    function loadCookies() {
        var cookies = document.cookie.split(';');
        cookies.forEach(function(cookie) {
            var parts = cookie.split('=');
            var name = parts[0].trim();
            var value = parts[1] ? decodeURIComponent(parts[1].trim()) : '';

            if (name === 'cod_prova') {
                document.getElementById("cod_prova").value = value;
            } else if (name === 'nome') {
                document.getElementById("nome_modal").value = value;
            } else if (name === 'tempo') {
                document.getElementById("tempo_modal").value = value;
            } else if (name === 'qtd_questoes') {
                document.getElementById("qtd_questoes_modal").value = value;
            } else if (name === 'banca_cod_banca') {
                document.getElementById("banca_cod_banca").value = value;
            } else if (name === 'disciplina_cod_disciplina') {
                document.getElementById("disciplina_cod_disciplina").value = value;
            } else if (name === 'concurso_cod_concurso') {
                document.getElementById("concurso_cod_concurso").value = value;
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
