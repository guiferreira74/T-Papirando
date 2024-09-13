<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Simulados</title>
    <link rel="stylesheet" href="simulado.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header-prc">
        <a href="adm.php">
            <img class="logo" src="assets/logo.svg" alt="topapirando">
        </a>
        <div class="links">
                <a id="sobre" href="sobre.html">Sobre</a>
                <a href="#">Ajuda</a>
                <a href="#">Sair</a>
                <img id="user" src="assets/user.svg" alt="">
            </div>
        </header>
    </div>

   <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-light border-right">
            <div class="sidebar-header p-3">
                <h4>Menu</h4>
            </div>
            <ul class="list-unstyled components">
            <li>
                    <a href="adm.php">Início</a>
                </li>
                <li>
                    <a href="#">Ajuda</a>
                </li>
                <li>
                    <a href="#">Parâmetros</a>
                </li>
                <hr>
                <p>Gerenciar Conteudo</p>
                <li>
                    <a href="banca.php">Bancas</a>
                </li>
                <li>
                    <a href="nivel.php">Niveis</a>
                </li>
                <li>
                    <a href="grau.php">Graus</a>
                </li>
                <li>
                    <a href="disciplina.php">Disciplinas</a>
                </li>
                <li>
                    <a href="duracao.php">Durações</a>
                </li>
                <li>
                    <a href="instituicao.php">Instituições</a>
                </li>
                <li>
                    <a href="simulado.php">Simulados</a>
                </li>
                <li>
                    <a href="prova.php">Provas</a>
                </li>
                <li>
                    <a href="concurso.php">Concursos</a>
                </li>
                <li>
                    <a href="questao.php">Questões</a>
                </li>
            </ul>
        </div>

    <main id="main-container">
        <div id="corpo">
            <h1>Gerenciar Simulados</h1>

            <!-- PHP Code for Database Connection and CRUD Operations -->
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
                $nivel_cod_nivel = $_POST['nivel_cod_nivel'];
                $disciplina_cod_disciplina = $_POST['disciplina_cod_disciplina'];
                $prova_cod_prova = $_POST['prova_cod_prova'];
                $concurso_cod_concurso = $_POST['concurso_cod_concurso'];
                $duracao_cod_duracao = $_POST['duracao_cod_duracao'];
                $cod_simulado = $_POST['cod_simulado'] ?? null;

                // Verificar se o nome já está registrado
                $check_sql = "SELECT * FROM simulado WHERE nome='$nome'";
                if ($cod_simulado) {
                    $check_sql .= " AND cod_simulado != $cod_simulado";
                }
                $check_result = $conn->query($check_sql);

                if ($check_result->num_rows > 0) {
                    $error_message = "Erro: simulado já registrado";
                } else {
                    if ($cod_simulado) {
                        // Atualizar registro
                        $sql = "UPDATE simulado SET nome='$nome', nivel_cod_nivel='$nivel_cod_nivel', disciplina_cod_disciplina='$disciplina_cod_disciplina', prova_cod_prova='$prova_cod_prova', concurso_cod_concurso='$concurso_cod_concurso', duracao_cod_duracao='$duracao_cod_duracao' WHERE cod_simulado=$cod_simulado";
                    } else {
                        // Inserir novo registro
                        $sql = "INSERT INTO simulado (nome, nivel_cod_nivel, disciplina_cod_disciplina, prova_cod_prova, concurso_cod_concurso, duracao_cod_duracao) VALUES ('$nome', '$nivel_cod_nivel', '$disciplina_cod_disciplina', '$prova_cod_prova', '$concurso_cod_concurso', '$duracao_cod_duracao')";
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
                $cod_simulado = $_GET['delete'];
                $sql = "DELETE FROM simulado WHERE cod_simulado=$cod_simulado";
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Registro excluído com sucesso!";
                } else {
                    $error_message = "Erro: " . $conn->error;
                }
            }

            // Formulário para criar/atualizar registros
            $cod_simulado = $_GET['edit'] ?? null;
            $nome = '';
            $nivel_cod_nivel = '';
            $disciplina_cod_disciplina = '';
            $prova_cod_prova = '';
            $concurso_cod_concurso = '';
            $duracao_cod_duracao = '';

            if ($cod_simulado) {
                $result = $conn->query("SELECT * FROM simulado WHERE cod_simulado=$cod_simulado");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $nome = $row['nome'];
                    $nivel_cod_nivel = $row['nivel_cod_nivel'];
                    $disciplina_cod_disciplina = $row['disciplina_cod_disciplina'];
                    $prova_cod_prova = $row['prova_cod_prova'];
                    $concurso_cod_concurso = $row['concurso_cod_concurso'];
                    $duracao_cod_duracao = $row['duracao_cod_duracao'];
                }
            }
            ?>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="simulado.php" method="POST">
                <input type="hidden" name="cod_simulado" value="<?php echo htmlspecialchars($cod_simulado); ?>">
                <div id="input">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome do simulado" title="Preencha o nome do simulado" required>
                    
                    <label for="nivel_cod_nivel">Nível:</label>
                    <select id="nivel_cod_nivel" name="nivel_cod_nivel" required>
                        <?php
                        $result = $conn->query("SELECT cod_nivel, tipo_nivel FROM nivel");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['cod_nivel'] == $nivel_cod_nivel ? 'selected' : '';
                            echo "<option value='{$row['cod_nivel']}' $selected>{$row['tipo_nivel']}</option>";
                        }
                        ?>
                    </select>

                    <label for="disciplina_cod_disciplina">Disciplina:</label>
                    <select id="disciplina_cod_disciplina" name="disciplina_cod_disciplina" required>
                        <?php
                        $result = $conn->query("SELECT cod_disciplina, nome FROM disciplina");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['cod_disciplina'] == $disciplina_cod_disciplina ? 'selected' : '';
                            echo "<option value='{$row['cod_disciplina']}' $selected>{$row['nome']}</option>";
                        }
                        ?>
                    </select>

                    <label for="prova_cod_prova">Prova:</label>
                    <select id="prova_cod_prova" name="prova_cod_prova" required>
                        <?php
                        $result = $conn->query("SELECT cod_prova, nome FROM prova");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['cod_prova'] == $prova_cod_prova ? 'selected' : '';
                            echo "<option value='{$row['cod_prova']}' $selected>{$row['nome']}</option>";
                        }
                        ?>
                    </select>

                    <label for="concurso_cod_concurso">Concurso:</label>
                    <select id="concurso_cod_concurso" name="concurso_cod_concurso" required>
                        <?php
                        $result = $conn->query("SELECT cod_concurso, nome FROM concurso");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['cod_concurso'] == $concurso_cod_concurso ? 'selected' : '';
                            echo "<option value='{$row['cod_concurso']}' $selected>{$row['nome']}</option>";
                        }
                        ?>
                    </select>

                    <label for="duracao_cod_duracao">Duração:</label>
                    <select id="duracao_cod_duracao" name="duracao_cod_duracao" required>
                        <?php
                        $result = $conn->query("SELECT cod_duracao, tempo FROM duracao");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['cod_duracao'] == $duracao_cod_duracao ? 'selected' : '';
                            echo "<option value='{$row['cod_duracao']}' $selected>{$row['tempo']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>

            <h2>Simulados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Nível</th>
                        <th>Disciplina</th>
                        <th>Prova</th>
                        <th>Concurso</th>
                        <th>Duração</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT s.cod_simulado, s.nome, n.tipo_nivel, d.nome as disciplina, p.nome as prova, c.nome as concurso, dur.tempo FROM simulado s JOIN nivel n ON s.nivel_cod_nivel = n.cod_nivel JOIN disciplina d ON s.disciplina_cod_disciplina = d.cod_disciplina JOIN prova p ON s.prova_cod_prova = p.cod_prova JOIN concurso c ON s.concurso_cod_concurso = c.cod_concurso JOIN duracao dur ON s.duracao_cod_duracao = dur.cod_duracao");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['nome']}</td>
                            <td>{$row['tipo_nivel']}</td>
                            <td>{$row['disciplina']}</td>
                            <td>{$row['prova']}</td>
                            <td>{$row['concurso']}</td>
                            <td>{$row['tempo']}</td>
                            <td class='actions'>
                                <a href='simulado.php?edit={$row['cod_simulado']}' class='edit-button' title='Editar'><i class='fas fa-pencil-alt'></i></a>
                                <a href='#' onclick='openModal(\"simulado.php?delete={$row['cod_simulado']}\"); return false;' class='delete-button' title='Excluir'><i class='fas fa-trash-alt'></i></a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal de confirmação -->
    <div id="confirm-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <p>Você tem certeza que quer excluir?</p>
            <button id="confirm-delete">Excluir</button>
            <button onclick="closeModal()">Cancelar</button>
        </div>
    </div>

    <script>
        var modal = document.getElementById("confirm-modal");
        var confirmButton = document.getElementById("confirm-delete");

        function openModal(deleteUrl) {
            modal.style.display = "block";
            confirmButton.onclick = function() {
                window.location.href = deleteUrl;
            };
        }

        function closeModal() {
            modal.style.display = "none";
        }

        // Fechar o modal se o usuário clicar fora do conteúdo
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        };
    </script>
</body>
</html>
