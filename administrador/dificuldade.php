<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Dificuldades</title>
    <link rel="stylesheet" href="disciplina.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header-prc">
        <a href="adm.php">
            <img class="logo" src="assets/logo_papirando_final.svg" alt="topapirando">
        </a>
        <div class="links">
            <a id="sobre" href="sobre_adm.php">Sobre</a>
            <a href="ajuda_adm.php">Ajuda</a>
            <a href="sair.php">Sair</a>
            <img id="user" src="assets/user.svg" alt="">
        </div>
    </header>

    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-light border-right">
            <div class="sidebar-header p-3">
                <h4>Menu</h4>
            </div>
            <ul class="list-unstyled components">
                <li><a href="adm.php">Início</a></li>
                <li><a href="ajuda_adm.php">Ajuda</a></li>
                <li><a href="parametros.php">Parâmetros</a></li>
                <hr>
                <p>Gerenciar Conteúdo</p>
                <li><a href="banca.php">Bancas</a></li>
                <li><a href="dificuldade.php">Dificuldade</a></li>
                <li><a href="disciplina.php">Disciplinas</a></li>
                <li><a href="duracao.php">Durações</a></li>
                <li><a href="instituicao.php">Instituições</a></li>
                <li><a href="prova.php">Provas</a></li>
                <li><a href="concurso.php">Concursos</a></li>
                <li><a href="questao.php">Questões</a></li>
            </ul>
        </div>

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
                    $tipo_dificuldade = $_POST['tipo_dificuldade'];
                    $cod_dificuldade = $_POST['cod_dificuldade'] ?? null;

                    // Verificar se a dificuldade já está registrada
                    $check_sql = "SELECT * FROM dificuldade WHERE tipo_dificuldade='$tipo_dificuldade'";
                    if ($cod_dificuldade) {
                        $check_sql .= " AND cod_dificuldade != $cod_dificuldade";
                    }
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Erro: dificuldade já registrada";
                    } else {
                        if ($cod_dificuldade) {
                            // Atualizar registro
                            $sql = "UPDATE dificuldade SET tipo_dificuldade='$tipo_dificuldade' WHERE cod_dificuldade=$cod_dificuldade";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO dificuldade (tipo_dificuldade) VALUES ('$tipo_dificuldade')";
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
                    $cod_dificuldade = $_GET['delete'];
                    $sql = "DELETE FROM dificuldade WHERE cod_dificuldade=$cod_dificuldade";
                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Registro excluído com sucesso!";
                    } else {
                        $error_message = "Erro: " . $conn->error;
                    }
                }

                // Preencher os campos do modal para edição
                $cod_dificuldade = $_GET['edit'] ?? null;
                $tipo_dificuldade = '';

                if ($cod_dificuldade) {
                    $result = $conn->query("SELECT * FROM dificuldade WHERE cod_dificuldade=$cod_dificuldade");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $tipo_dificuldade = $row['tipo_dificuldade'];
                    }
                }
                ?>

<div class="table-container container-principal">
    <h2>Gerenciar Dificuldade</h2>
    <button class="btn-adicionar" onclick="openAddModal()">Adicionar Dificuldade</button>

    <?php
    // Query para obter os registros de dificuldade
    $result = $conn->query("SELECT * FROM dificuldade");

    if ($result->num_rows > 0) {
        echo "<table id='dificuldadeTable' class='tabela-registros'>";
        echo "<thead><tr><th>Tipo de Dificuldade</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['tipo_dificuldade']) . "</td>";
            echo "<td class='actions'>";
            // Botões de editar e excluir com o novo layout
            echo "<button class='btn-editar' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . ")'><i class='fas fa-edit'></i></button>";
            echo "<button class='btn-excluir' onclick='openModal(\"dificuldade.php?delete=" . $row['cod_dificuldade'] . "\")'><i class='fas fa-trash-alt'></i></button>";
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

<!-- Modal de Adicionar/Editar Dificuldade -->
<div id="add-modal" class="modal" style="overflow: hidden;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
        <form action="dificuldade.php" method="POST">
            <input type="hidden" id="cod_dificuldade" name="cod_dificuldade" value="<?php echo htmlspecialchars($cod_dificuldade); ?>">
            <div id="input">
                <label for="tipo_dificuldade_modal">Tipo de Dificuldade:</label>
                <input type="text" id="tipo_dificuldade_modal" name="tipo_dificuldade" value="<?php echo htmlspecialchars($tipo_dificuldade); ?>" placeholder="Preencha o tipo de dificuldade" required>
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
    // Referência aos modais
    var confirmModal = document.getElementById("confirm-modal");
    var addModal = document.getElementById("add-modal");
    var modalErro = document.getElementById("modal-erro");
    var modalSucesso = document.getElementById("modal-sucesso");
    var confirmButton = document.getElementById("confirm-delete");

    // Função para abrir o modal de adicionar
    function openAddModal() {
        document.getElementById('tipo_dificuldade_modal').value = '';
        addModal.style.display = "block";
    }

    // Função para fechar o modal de adicionar
    function closeAddModal() {
        addModal.style.display = "none";
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

    // Função para abrir o modal de confirmação
    function openModal(deleteUrl) {
        confirmModal.style.display = "block";
        confirmButton.onclick = function() {
            window.location.href = deleteUrl;
        };
    }

    // Limpar formulário
    function clearForm() {
        document.getElementById("tipo_dificuldade_modal").value = '';
    }

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

    // Função para abrir o modal de edição
    function openEditModal(data) {
        document.getElementById('cod_dificuldade').value = data.cod_dificuldade;
        document.getElementById('tipo_dificuldade_modal').value = data.tipo_dificuldade;
        addModal.style.display = "block";
    }

    // Função para fechar modais
    function closeModal(modalType) {
        if (modalType === 'erro') {
            modalErro.style.display = "none";
        } else if (modalType === 'sucesso') {
            modalSucesso.style.display = "none";
        } else {
            confirmModal.style.display = "none";
        }
    }
</script>

<!-- Modal de Confirmação de Logout -->
<div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmLogoutModalLabel">Sair</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja sair?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="confirmLogout" href="sair.php" class="btn btn-primary">Sair</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
