<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Disciplinas</title>
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
                <h1>Gerenciar Disciplinas</h1>

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
                    $cod_disciplina = $_POST['cod_disciplina'] ?? null;

                    // Verificar se a disciplina já está registrada
                    $check_sql = "SELECT * FROM disciplina WHERE nome='$nome'";
                    if ($cod_disciplina) {
                        $check_sql .= " AND cod_disciplina != $cod_disciplina";
                    }
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Erro: disciplina já registrada";
                    } else {
                        if ($cod_disciplina) {
                            // Atualizar registro
                            $sql = "UPDATE disciplina SET nome='$nome' WHERE cod_disciplina=$cod_disciplina";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO disciplina (nome) VALUES ('$nome')";
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
                    $cod_disciplina = $_GET['delete'];
                    $sql = "DELETE FROM disciplina WHERE cod_disciplina=$cod_disciplina";
                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Registro excluído com sucesso!";
                    } else {
                        $error_message = "Erro: " . $conn->error;
                    }
                }

                // Preencher os campos do modal para edição
                $cod_disciplina = $_GET['edit'] ?? null;
                $nome = '';

                if ($cod_disciplina) {
                    $result = $conn->query("SELECT * FROM disciplina WHERE cod_disciplina=$cod_disciplina");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $nome = $row['nome'];
                    }
                }
                ?>

                <div class="text-center mb-3">
                    <button class="btn btn-primary" onclick="openAddModal()">Adicionar Nova Disciplina</button>
                </div>

                <div class="table-container">
                    <?php
                    $result = $conn->query("SELECT * FROM disciplina");

                    if ($result->num_rows > 0) {
                        echo "<table class='table'>";
                        echo "<tr><th>Nome da Disciplina</th><th>Ações</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                            echo "<td class='actions'>";
                            echo "<a class='edit-button' href='#' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . "); return false;' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                            echo "<a class='delete-button' href='#' onclick='openModal(\"disciplina.php?delete=" . $row['cod_disciplina'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
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

        <!-- Modal de Adicionar/Editar Disciplina -->
        <div id="add-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeAddModal()">&times;</span>
                <form action="disciplina.php" method="POST">
                    <input type="hidden" id="cod_disciplina" name="cod_disciplina" value="<?php echo htmlspecialchars($cod_disciplina); ?>">
                    <div id="input">
                        <label for="nome_modal">Nome da Disciplina:</label>
                        <input type="text" id="nome_modal" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da disciplina" required>
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
            // Função para criar um cookie
            function setCookie(name, value, days) {
                const d = new Date();
                d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
                const expires = "expires=" + d.toUTCString();
                document.cookie = name + "=" + value + ";" + expires + ";path=/";
            }

            // Função para obter um cookie
            function getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            // Referência aos modais
            var confirmModal = document.getElementById("confirm-modal");
            var addModal = document.getElementById("add-modal");
            var modalErro = document.getElementById("modal-erro");
            var modalSucesso = document.getElementById("modal-sucesso");
            var confirmButton = document.getElementById("confirm-delete");

            // Função para abrir o modal de adicionar
            function openAddModal() {
                document.getElementById('nome_modal').value = getCookie('disciplina_nome') || '';
                addModal.style.display = "block";
            }

            // Função para fechar o modal de adicionar
            function closeAddModal() {
                setCookie('disciplina_nome', document.getElementById('nome_modal').value, 1);
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
                document.getElementById("nome_modal").value = '';
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
                document.getElementById('cod_disciplina').value = data.cod_disciplina;
                document.getElementById('nome_modal').value = data.nome;
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
    </body>
</html>
