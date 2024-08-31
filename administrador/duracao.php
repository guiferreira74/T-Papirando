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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Durações</title>
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
        <a href="disciplina.php">Disciplinas</a>
        <a href="">Desempenho</a>
    </div>

    <div id="main-container">
        <div id="corpo">
            <h1>Gerenciar Durações</h1>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="duracao.php" method="POST">
                <input type="hidden" name="cod_duracao" value="<?php echo htmlspecialchars($cod_duracao); ?>">
                <div id="input">
                    <label for="tempo">Tempo:</label>
                    <input type="text" id="tempo" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" placeholder="Preencha o tempo de duração para realização do simulado (HH:MM:SS)" title="Preencha o tempo de duração para realização do simulado" required>
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
                    echo "<tr><th>Código da Duração</th><th>Tempo</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['cod_duracao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tempo']) . "</td>";
                        echo "<td>";
                        echo "<a class='edit-button' href='duracao.php?edit=" . $row['cod_duracao'] . "'>Editar</a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"duracao.php?delete=" . $row['cod_duracao'] . "\"); return false;'>Excluir</a>";
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
