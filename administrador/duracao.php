<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Durações</title>
    <link rel="stylesheet" href="duracao.css">
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
        <a href="nivel.php">Níveis</a>
        <a href="#">Desempenho</a>
    </nav>

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
                        $sql = "UPDATE duracao SET tempo='$tempo' WHERE cod_duracao=$cod_duracao";
                    } else {
                        // Inserir novo registro
                        $sql = "INSERT INTO duracao (tempo) VALUES ('$tempo')";
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

            // Formulário para criar/atualizar registros
            $cod_duracao = $_GET['edit'] ?? null;
            $tempo = '';

            if ($cod_duracao) {
                $result = $conn->query("SELECT * FROM duracao WHERE cod_duracao=$cod_duracao");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tempo = $row['tempo'];
                }
            }
            ?>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="duracao.php" method="POST">
                <input type="hidden" name="cod_duracao" value="<?php echo htmlspecialchars($cod_duracao); ?>">
                <div id="input">
                    <label for="tempo">Tempo:</label>
                    <input type="text" id="tempo" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" placeholder="Preencha o tempo   (HH:MM:SS)" title="Preencha o tempo " required>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>

            <div class="table-container">
                <?php
                $result = $conn->query("SELECT * FROM duracao");

                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Tempo</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['tempo']) . "</td>";
                        echo "<td class='actions'>";
                        echo "<a class='edit-button' href='duracao.php?edit=" . $row['cod_duracao'] . "' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"duracao.php?delete=" . $row['cod_duracao'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
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
