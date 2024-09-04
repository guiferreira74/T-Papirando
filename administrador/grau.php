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
    $tipo_grau = $_POST['tipo_grau'];
    $cod_grau = $_POST['cod_grau'] ?? null;

    // Verificar se o tipo de grau já está registrado
    $check_sql = "SELECT * FROM grau WHERE tipo_grau='$tipo_grau'";
    if ($cod_grau) {
        $check_sql .= " AND cod_grau != $cod_grau";
    }
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $error_message = "Erro: grau já registrado";
    } else {
        if ($cod_grau) {
            // Atualizar registro
            $sql = "UPDATE grau SET tipo_grau='$tipo_grau' WHERE cod_grau=$cod_grau";
        } else {
            // Inserir novo registro
            $sql = "INSERT INTO grau (tipo_grau) VALUES ('$tipo_grau')";
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
    $cod_grau = $_GET['delete'];
    $sql = "DELETE FROM grau WHERE cod_grau=$cod_grau";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Registro excluído com sucesso!";
    } else {
        $error_message = "Erro: " . $conn->error;
    }
}

// Formulário para criar/atualizar registros
$cod_grau = $_GET['edit'] ?? null;
$tipo_grau = '';

if ($cod_grau) {
    $result = $conn->query("SELECT * FROM grau WHERE cod_grau=$cod_grau");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tipo_grau = $row['tipo_grau'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Graus</title>
    <link rel="stylesheet" href="grau.css">
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
    <div class="menu">
        <a href="#">Inicio</a>
        <a href="#">Simulados</a>
        <a href="grau.php">Graus</a>
        <a href="#">Desempenho</a>
    </div>

    <div id="main-container">
        <div id="corpo">
            <h1>Gerenciar Graus</h1>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="grau.php" method="POST">
                <input type="hidden" name="cod_grau" value="<?php echo htmlspecialchars($cod_grau); ?>">
                <div id="input">
                    <label for="tipo_grau">Tipo de Grau:</label>
                    <input type="text" id="tipo_grau" name="tipo_grau" value="<?php echo htmlspecialchars($tipo_grau); ?>" placeholder="Preencha o tipo de grau" title="Preencha o tipo de grau" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>

            <div class="table-container">
                <?php
                $result = $conn->query("SELECT * FROM grau");

                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Tipo de Grau</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['tipo_grau']) . "</td>";
                        echo "<td class='actions'>";
                        echo "<a class='edit-button' href='grau.php?edit=" . $row['cod_grau'] . "' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"grau.php?delete=" . $row['cod_grau'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
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
