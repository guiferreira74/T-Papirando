<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Níveis</title>
    <link rel="stylesheet" href="banca.css">
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
            <a href="">Sobre</a>
            <a href="">Ajuda</a>
            <a href="">Entrar</a>
        </div>
    </header>
    <div class="menu">
        <a href="">Inicio</a>
        <a href="">Simulados</a>
        <a href="nivel.php">Níveis</a>
        <a href="">Desempenho</a>
    </div>

    <div id="main-container">
        <div id="corpo">
            <h1>Gerenciar Níveis</h1>

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
                $tipo_nivel = $_POST['tipo_nivel'];
                $cod_nivel = $_POST['cod_nivel'] ?? null;

                // Verificar se o tipo de nível já está registrado
                $check_sql = "SELECT * FROM nivel WHERE tipo_nivel='$tipo_nivel'";
                if ($cod_nivel) {
                    $check_sql .= " AND cod_nivel != $cod_nivel";
                }
                $check_result = $conn->query($check_sql);

                if ($check_result->num_rows > 0) {
                    $error_message = "Erro: nível já registrado";
                } else {
                    if ($cod_nivel) {
                        // Atualizar registro
                        $sql = "UPDATE nivel SET tipo_nivel='$tipo_nivel' WHERE cod_nivel=$cod_nivel";
                    } else {
                        // Inserir novo registro
                        $sql = "INSERT INTO nivel (tipo_nivel) VALUES ('$tipo_nivel')";
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
                $cod_nivel = $_GET['delete'];
                $sql = "DELETE FROM nivel WHERE cod_nivel=$cod_nivel";
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Registro excluído com sucesso!";
                } else {
                    $error_message = "Erro: " . $conn->error;
                }
            }

            // Formulário para criar/atualizar registros
            $cod_nivel = $_GET['edit'] ?? null;
            $tipo_nivel = '';

            if ($cod_nivel) {
                $result = $conn->query("SELECT * FROM nivel WHERE cod_nivel=$cod_nivel");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tipo_nivel = $row['tipo_nivel'];
                }
            }
            ?>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="nivel.php" method="POST">
                <input type="hidden" name="cod_nivel" value="<?php echo htmlspecialchars($cod_nivel); ?>">
                <div id="input">
                    <label for="tipo_nivel">Tipo de Nível:</label>
                    <input type="text" id="tipo_nivel" name="tipo_nivel" value="<?php echo htmlspecialchars($tipo_nivel); ?>" placeholder="Preencha o tipo de nível" title="Preencha o tipo de nível" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>

            <div class="table-container">
                <?php
                $result = $conn->query("SELECT * FROM nivel");

                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Tipo de Nível</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['tipo_nivel']) . "</td>";
                        echo "<td>";
                        echo "<a class='edit-button' href='nivel.php?edit=" . $row['cod_nivel'] . "'>Editar</a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"nivel.php?delete=" . $row['cod_nivel'] . "\"); return false;'>Excluir</a>";
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
    </div>

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
