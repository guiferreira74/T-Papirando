<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Níveis</title>
    <link rel="stylesheet" href="nivel.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
           <a href="adm.php"><img class="logo" src="assets/Logo.svg"/> </a>
           <div class="search-bar">

            <div class="links">
            <a id="sobre" href="sobre.html">Sobre</a>
            <a href="#">Ajuda</a>
            <a href="#">Sair</a>
            <img id="user" src="assets/user.svg" alt="">
    </div>
           
        </header>
    </div>

    <!-- Sidebar -->
    <div class="d-flex">
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
                    <a href="#">Parâmetros </a>
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
                        echo "<td class='actions'>";
                        echo "<a class='edit-button' href='nivel.php?edit=" . $row['cod_nivel'] . "' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"nivel.php?delete=" . $row['cod_nivel'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
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