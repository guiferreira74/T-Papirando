<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Instituições</title>
    <link rel="stylesheet" href="instituicao.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header-prc">
        <a href="adm.php">
            <img class="logo" src="assets/logo_papirando_final.svg" alt="topapirando">
        </a>
        <div class="links">
            <a id="sobre" href="sobre.html">Sobre</a>
            <a href="#">Ajuda</a>
            <a href="#">Sair</a>
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
                <li><a href="#">Ajuda</a></li>
                <li><a href="#">Parâmetros</a></li>
                <hr>
                <p>Gerenciar Conteúdo</p>
                <li><a href="banca.php">Bancas</a></li>
                <li><a href="nivel.php">Níveis</a></li>
                <li><a href="grau.php">Graus</a></li>
                <li><a href="instituicao.php">Instituições</a></li>
                <li><a href="duracao.php">Durações</a></li>
                <li><a href="simulado.php">Simulados</a></li>
                <li><a href="prova.php">Provas</a></li>
                <li><a href="concurso.php">Concursos</a></li>
                <li><a href="questao.php">Questões</a></li>
            </ul>
        </div>

        <main id="main-container">
            <div id="corpo">
                <h1>Gerenciar Instituições</h1>

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
                    $cod_instituicao = $_POST['cod_instituicao'] ?? null;

                    // Verificar se o nome da instituição já está registrado
                    $check_sql = "SELECT * FROM instituicao WHERE nome='$nome'";
                    if ($cod_instituicao) {
                        $check_sql .= " AND cod_instituicao != $cod_instituicao";
                    }
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Erro: instituição já registrada";
                    } else {
                        if ($cod_instituicao) {
                            // Atualizar registro
                            $sql = "UPDATE instituicao SET nome='$nome' WHERE cod_instituicao=$cod_instituicao";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO instituicao (nome) VALUES ('$nome')";
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
                    $cod_instituicao = $_GET['delete'];
                    $sql = "DELETE FROM instituicao WHERE cod_instituicao=$cod_instituicao";
                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Registro excluído com sucesso!";
                    } else {
                        $error_message = "Erro: " . $conn->error;
                    }
                }

                // Formulário para criar/atualizar registros
                $cod_instituicao = $_GET['edit'] ?? null;
                $nome = '';

                if ($cod_instituicao) {
                    $result = $conn->query("SELECT * FROM instituicao WHERE cod_instituicao=$cod_instituicao");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $nome = $row['nome'];
                    }
                }
                ?>

                <form action="instituicao.php" method="POST">
                    <input type="hidden" name="cod_instituicao" value="<?php echo htmlspecialchars($cod_instituicao); ?>">
                    <div id="input">
                        <label for="nome">Nome da Instituição:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da instituição" title="Preencha o nome da instituição" required>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="save-button">Salvar</button>
                        <button type="reset" class="clear-button">Limpar</button>
                    </div>
                </form>

                <div class="table-container">
                    <button id="toggle-table" style="background-color: blue; color: white; border: none; padding: 10px 15px; cursor: pointer;">Mostrar Instituições Cadastradas</button>
                    <div id="table-content" style="display:none;">
                        <?php
                        $result = $conn->query("SELECT * FROM instituicao");

                        if ($result->num_rows > 0) {
                            echo "<table class='table'>";
                            echo "<tr><th>Nome da Instituição</th><th>Ações</th></tr>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                                echo "<td class='actions'>";
                                echo "<a class='edit-button' href='instituicao.php?edit=" . $row['cod_instituicao'] . "' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                                echo "<a class='delete-button' href='#' onclick='openModal(\"instituicao.php?delete=" . $row['cod_instituicao'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
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

        <script>
            // Referência ao modal e aos botões
            var confirmModal = document.getElementById("confirm-modal");
            var confirmButton = document.getElementById("confirm-delete");

            // Função para abrir o modal
            function openModal(deleteUrl) {
                confirmModal.style.display = "block";
                confirmButton.onclick = function() {
                    window.location.href = deleteUrl;
                };
            }

            // Função para fechar o modal
            function closeModal() {
                confirmModal.style.display = "none";
            }

            // Fechar o modal se o usuário clicar fora dele
            window.onclick = function(event) {
                if (event.target === confirmModal) {
                    closeModal();
                }
            };

            // Tabela toggle
            document.getElementById('toggle-table').addEventListener('click', function() {
                var tableContent = document.getElementById('table-content');
                if (tableContent.style.display === 'none') {
                    tableContent.style.display = 'block';
                    this.textContent = 'Ocultar Instituições Cadastradas'; // Atualiza o texto do botão
                } else {
                    tableContent.style.display = 'none';
                    this.textContent = 'Mostrar Instituições Cadastradas'; // Atualiza o texto do botão
                }
            });
        </script>

        <!-- Modais de Sucesso e Erro -->
        <div id="modal-erro" class="modal modal-erro">
            <div class="modal-content modal-content-erro">
                <span class="close-btn close-btn-erro" onclick="closeModal('erro')">&times;</span>
                <p id="erro-mensagem">Erro!</p>
                <button id="ok-btn-erro" class="ok-btn ok-btn-erro">OK</button>
            </div>
        </div>

        <div id="modal-sucesso" class="modal modal-sucesso">
            <div class="modal-content modal-content-sucesso">
                <span class="close-btn close-btn-sucesso" onclick="closeModal('sucesso')">&times;</span>
                <p id="sucesso-mensagem">Sucesso!</p>
                <button id="ok-btn-sucesso" class="ok-btn ok-btn-sucesso">OK</button>
            </div>
        </div>

        <script>
            // Obter elementos dos modais e botões
            var modalErro = document.getElementById("modal-erro");
            var modalSucesso = document.getElementById("modal-sucesso");

            var okBtnErro = document.getElementById("ok-btn-erro");
            var okBtnSucesso = document.getElementById("ok-btn-sucesso");

            // Função para mostrar um modal específico
            function showModal(type, message) {
                var modal = type === 'erro' ? modalErro : modalSucesso;
                var messageElem = modal.querySelector('p');
                messageElem.textContent = message;
                modal.style.display = "block";
            }

            // Função para esconder o modal
            function closeModal(type) {
                if (type) {
                    var modal = type === 'erro' ? modalErro : modalSucesso;
                    modal.style.display = "none";
                } else {
                    confirmModal.style.display = "none"; // Fecha o modal de confirmação
                }
            }

            // Adicionar eventos de clique para os botões OK
            okBtnErro.onclick = function() {
                closeModal('erro');
            };
            okBtnSucesso.onclick = function() {
                closeModal('sucesso');
            };

            // Fechar o modal se o usuário clicar fora dele
            window.onclick = function(event) {
                if (event.target == modalErro || event.target == modalSucesso) {
                    closeModal(event.target === modalErro ? 'erro' : 'sucesso');
                }
            };

            // Mostrar mensagens de erro ou sucesso baseadas nas variáveis PHP
            <?php if ($error_message): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    showModal('erro', '<?php echo htmlspecialchars($error_message); ?>');
                });
            <?php elseif ($success_message): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    showModal('sucesso', '<?php echo htmlspecialchars($success_message); ?>');
                });
            <?php endif; ?>
        </script>
    </body>
</html>
