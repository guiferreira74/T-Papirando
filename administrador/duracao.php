<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Durações</title>
    <link rel="stylesheet" href="duracao.css">
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
            <li><a href="simulado.php">Simulados</a></li>
            <li><a href="prova.php">Provas</a></li>
            <li><a href="concurso.php">Concursos</a></li>
            <li><a href="questao.php">Questões</a></li>
        </ul>
    </div>
    
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
                $descricao = $_POST['descricao'];
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
                        $sql = "UPDATE duracao SET tempo='$tempo', descricao='$descricao' WHERE cod_duracao=$cod_duracao";
                    } else {
                        // Inserir novo registro
                        $sql = "INSERT INTO duracao (tempo, descricao) VALUES ('$tempo', '$descricao')";
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
            $descricao = '';

            if ($cod_duracao) {
                $result = $conn->query("SELECT * FROM duracao WHERE cod_duracao=$cod_duracao");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tempo = $row['tempo'];
                    $descricao = $row['descricao'];
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
                    <input type="text" id="tempo" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" placeholder="Preencha o tempo (HH:MM:SS)" title="Preencha o tempo" required>
                </div>
                <div id="input">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" value="<?php echo htmlspecialchars($descricao); ?>" placeholder="Preencha a descrição" title="Preencha a descrição" required>
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
                    echo "<tr><th>Tempo</th><th>Descrição</th><th>Ações</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['tempo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
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
        var deleteUrl = '';

        function openModal(url) {
            deleteUrl = url;
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        confirmButton.onclick = function() {
            if (deleteUrl) {
                window.location.href = deleteUrl;
            }
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        };
    </script>
</body>
</html>
