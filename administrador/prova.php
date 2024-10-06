<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Provas</title>
    <link rel="stylesheet" href="prova.css">
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
                <li><a href="nivel.php">Níveis</a></li>
                <li><a href="grau.php">Graus</a></li>
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
                <h1>Gerenciar Provas</h1>

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
                    $qtd_provas = $_POST['qtd_provas'];
                    $banca_cod_banca = $_POST['banca_cod_banca'];
                    $cod_prova = $_POST['cod_prova'] ?? null;

                    // Verificar se a prova já está registrada
                    $check_sql = "SELECT * FROM prova WHERE nome='$nome'";
                    if ($cod_prova) {
                        $check_sql .= " AND cod_prova != $cod_prova";
                    }
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Erro: prova já registrada";
                    } else {
                        if ($cod_prova) {
                            // Atualizar registro
                            $sql = "UPDATE prova SET nome='$nome', tempo='$tempo', qtd_provas='$qtd_provas', banca_cod_banca=$banca_cod_banca WHERE cod_prova=$cod_prova";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO prova (nome, tempo, qtd_provas, banca_cod_banca) VALUES ('$nome', '$tempo', '$qtd_provas', $banca_cod_banca)";
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
                $qtd_provas = '';
                $banca_cod_banca = '';

                if ($cod_prova) {
                    $result = $conn->query("SELECT * FROM prova WHERE cod_prova=$cod_prova");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $nome = $row['nome'];
                        $tempo = $row['tempo'];
                        $qtd_provas = $row['qtd_provas'];
                        $banca_cod_banca = $row['banca_cod_banca'];
                    }
                }

                // Obter as bancas
                $bancas_result = $conn->query("SELECT * FROM banca");
                ?>

                <div class="text-center mb-3">
                    <button class="btn btn-primary" onclick="openAddModal()">Adicionar Nova Prova</button>
                </div>

                <div class="table-container">
                    <?php
                    $result = $conn->query("SELECT * FROM prova");

                    if ($result->num_rows > 0) {
                        echo "<table class='table'>";
                        echo "<tr><th>Nome da Prova</th><th>Tempo</th><th>Qtd Provas</th><th>Banca</th><th>Ações</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            // Obter o nome da banca
                            $banca = $conn->query("SELECT nome FROM banca WHERE cod_banca=" . $row['banca_cod_banca'])->fetch_assoc();
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tempo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['qtd_provas']) . "</td>";
                            echo "<td>" . htmlspecialchars($banca['nome']) . "</td>";
                            echo "<td class='actions'>";
                            echo "<a class='edit-button' href='#' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . "); return false;' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                            echo "<a class='delete-button' href='#' onclick='openModal(\"prova.php?delete=" . $row['cod_prova'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Nenhum registro encontrado.</p>";
                    }
                    ?>
                </div>
            </div>
        </main>

        <!-- Modal de Adicionar/Editar Prova -->
        <div id="add-modal" class="custom-modal">
            <div class="custom-modal-content">
                <span class="close-btn" onclick="closeAddModal()">&times;</span>
                <form action="prova.php" method="POST">
                    <input type="hidden" id="cod_prova" name="cod_prova" value="<?php echo htmlspecialchars($cod_prova); ?>">
                    <div id="input">
                        <label for="nome_modal">Nome da Prova:</label>
                        <input type="text" id="nome_modal" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da prova" required>
                    </div>
                    <div id="input">
                        <label for="tempo_modal">Tempo (HH:MM:SS):</label>
                        <input type="text" id="tempo_modal" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]" placeholder="HH:MM:SS" required>
                    </div>
                    <div id="input">
                        <label for="qtd_provas_modal">Quantidade de Provas:</label>
                        <input type="text" id="qtd_provas_modal" name="qtd_provas" value="<?php echo htmlspecialchars($qtd_provas); ?>" required>
                    </div>
                    <div id="input">
                        <label for="banca_cod_banca_modal">Selecione a Banca:</label>
                        <select id="banca_cod_banca_modal" name="banca_cod_banca" required>
                            <option value="" disabled selected>Selecione uma Banca</option>
                            <?php while ($banca = $bancas_result->fetch_assoc()): ?>
                                <option value="<?php echo $banca['cod_banca']; ?>" <?php echo ($banca['cod_banca'] == $banca_cod_banca) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($banca['nome']); ?>
                                </option>
                            <?php endwhile; ?>
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

    // Função para abrir o modal de edição
    function openEditModal(data) {
        document.getElementById("cod_prova").value = data.cod_prova;
        document.getElementById("nome_modal").value = data.nome;
        document.getElementById("tempo_modal").value = data.tempo;
        document.getElementById("qtd_provas_modal").value = data.qtd_provas;
        document.getElementById("banca_cod_banca_modal").value = data.banca_cod_banca;
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
    document.getElementById("cod_prova").value = '';
    document.getElementById("nome_modal").value = '';
    document.getElementById("tempo_modal").value = '';
    document.getElementById("qtd_provas_modal").value = '';
    document.getElementById("banca_cod_banca_modal").value = '';
}

// Função para salvar os valores dos inputs em cookies
function saveCookies() {
    var cod_prova = document.getElementById("cod_prova").value;
    var nome = document.getElementById("nome_modal").value;
    var tempo = document.getElementById("tempo_modal").value;
    var qtd_provas = document.getElementById("qtd_provas_modal").value;
    var banca_cod_banca = document.getElementById("banca_cod_banca_modal").value;

    document.cookie = "cod_prova=" + encodeURIComponent(cod_prova) + "; path=/";
    document.cookie = "nome=" + encodeURIComponent(nome) + "; path=/";
    document.cookie = "tempo=" + encodeURIComponent(tempo) + "; path=/";
    document.cookie = "qtd_provas=" + encodeURIComponent(qtd_provas) + "; path=/";
    document.cookie = "banca_cod_banca=" + encodeURIComponent(banca_cod_banca) + "; path=/";
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
        } else if (name === 'qtd_provas') {
            document.getElementById("qtd_provas_modal").value = value;
        } else if (name === 'banca_cod_banca') {
            document.getElementById("banca_cod_banca_modal").value = value;
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

    </body>
</html>