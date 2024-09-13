<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Bancas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="banca.css">
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    <div id="main-container">
                        <div id="corpo">
                            <h1>Gerenciar Bancas</h1>

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
                $link = $_POST['link'];
                $cod_banca = $_POST['cod_banca'] ?? null;

                // Verificar se o nome e o link já estão registrados
                $check_sql = "SELECT * FROM banca WHERE nome='$nome' AND link='$link'";
                if ($cod_banca) {
                    $check_sql .= " AND cod_banca != $cod_banca";
                }
                $check_result = $conn->query($check_sql);

                if ($check_result->num_rows > 0) {
                    $error_message = "Erro: banca e link já registrados";
                } else {
                    if ($cod_banca) {
                        // Atualizar registro
                        $sql = "UPDATE banca SET nome='$nome', link='$link' WHERE cod_banca=$cod_banca";
                    } else {
                        // Inserir novo registro
                        $sql = "INSERT INTO banca (nome, link) VALUES ('$nome', '$link')";
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
                $cod_banca = $_GET['delete'];
                $sql = "DELETE FROM banca WHERE cod_banca=$cod_banca";
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Registro excluído com sucesso!";
                } else {
                    $error_message = "Erro: " . $conn->error;
                }
            }

            // Formulário para criar/atualizar registros
            $cod_banca = $_GET['edit'] ?? null;
            $nome = '';
            $link = '';

            if ($cod_banca) {
                $result = $conn->query("SELECT * FROM banca WHERE cod_banca=$cod_banca");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $nome = $row['nome'];
                    $link = $row['link'];
                }
            }
            ?>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="banca.php" method="POST">
                <input type="hidden" name="cod_banca" value="<?php echo htmlspecialchars($cod_banca); ?>">
                <div id="input">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da banca" title="Preencha o nome da banca" required>
                    <label for="link">Link:</label>
                    <input type="text" id="link" name="link" value="<?php echo htmlspecialchars($link); ?>" placeholder="Preencha o link da banca" title="Preencha o link da banca" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>

            <div class="table-container">
                <?php
                $result = $conn->query("SELECT * FROM banca");

                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Nome</th><th>Link</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['link']) . "</td>";
                        echo "<td>";
                        echo "<a class='edit-button' href='banca.php?edit=" . $row['cod_banca'] . "' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                        echo "<a class='delete-button' href='#' onclick='openModal(\"banca.php?delete=" . $row['cod_banca'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
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
