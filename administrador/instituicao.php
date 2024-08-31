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

    // Verificar se a instituição já está registrada
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Instituições</title>
    <link rel="stylesheet" href="banca.css"> 
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
            <a href="">Entrarr</a>
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
            <h1>Gerenciar Instituições</h1>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

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
                <?php
                $result = $conn->query("SELECT * FROM instituicao");

                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Código da Instituição</th><th>Nome da Instituição</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['cod_instituicao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>";
                        echo "<a class='edit-button' href='instituicao.php?edit=" . $row['cod_instituicao'] . "'>Editar</a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"instituicao.php?delete=" . $row['cod_instituicao'] . "\"); return false;'>Excluir</a>";
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
