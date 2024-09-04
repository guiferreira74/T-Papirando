<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Disciplinas</title>
    <link rel="stylesheet" href="disciplina.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="header-prc">
        <a href="topapirando.php">
            <img class="logo" src="assets/logo.svg" alt="topapirando">
        </a>
        <div class="search-bar">
            <input type="text" placeholder="Digite seu texto aqui">
        </div>
        <div class="links">
            <a href="#">Sobre</a>
            <a href="#">Ajuda</a>
            <a href="#">Entrar</a>
        </div>
    </header>
    <nav class="menu">
        <a href="#">Inicio</a>
        <a href="#">Simulados</a>
        <a href="disciplina.php">Disciplinas</a>
        <a href="#">Desempenho</a>
    </nav>

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

                // Verificar se o nome da disciplina já está registrado
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

            // Formulário para criar/atualizar registros
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

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="disciplina.php" method="POST">
                <input type="hidden" name="cod_disciplina" value="<?php echo htmlspecialchars($cod_disciplina); ?>">
                <div id="input">
                    <label for="nome">Nome da Disciplina:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da disciplina" title="Preencha o nome da disciplina" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>

            <div class="table-container">
                <?php
                $result = $conn->query("SELECT * FROM disciplina");

                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Nome da Disciplina</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td class='actions'>";
                        echo "<a class='edit-button' href='disciplina.php?edit=" . $row['cod_disciplina'] . "' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"disciplina.php?delete=" . $row['cod_disciplina'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Nenhum registro encontrado.</p>";
                }

                $conn->close();
                ?>
            </div>
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
