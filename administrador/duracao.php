<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Durações</title>
    <link rel="stylesheet" href="duracao.css">
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
                <h1>Gerenciar Durações</h1>

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

                <div class="text-center mb-3">
                    <button class="btn btn-primary" onclick="openAddModal()">Adicionar Nova Duração</button>
                </div>

                <div class="table-container">
                    <?php
                    $result = $conn->query("SELECT * FROM duracao");

                    if ($result->num_rows > 0) {
                        echo "<table class='table'>";
                        echo "<tr><th>Tempo</th><th>Descrição</th><th>Ações</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['tempo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                            echo "<td class='actions'>";
                            echo "<a class='edit-button' href='#' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . "); return false;' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                            echo "<a class='delete-button' href='#' onclick='openModal(\"duracao.php?delete=" . $row['cod_duracao'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
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
    </body>
</html>
