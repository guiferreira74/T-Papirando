<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Provas</title>
    <link rel="stylesheet" href="simulado.css">
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
        <a href="#">Provas</a>
        <a href="banca.html">Bancas</a>
        <a href="#">Desempenho</a>
    </nav>

    <main id="main-container">
        <div id="corpo">
            <h1>Gerenciar Provas</h1>

            <?php
            // Conexão com o banco de dados
            $servername = "localhost";
            $username = "root";
            $password = "admin";
            $dbname = "topapirando";

            // Cria a conexão
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica se há erros na conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            $error_message = '';
            $success_message = '';
            
            // Inserir ou atualizar registro
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $acao = $_POST['acao'] ?? '';
                $cod_prova = $_POST['cod_prova'] ?? null;
                $nome = $_POST['nome'];
                $tempo = $_POST['tempo'];
                $Banca_cod_banca = $_POST['Banca_cod_banca'];
            
                // Verificar se o registro já existe
                $sql_check = "SELECT * FROM prova WHERE nome='$nome' AND Banca_cod_banca='$Banca_cod_banca' AND cod_prova != '$cod_prova'";
                $result_check = $conn->query($sql_check);
            
                if ($result_check->num_rows > 0) {
                    $error_message = "Já existe uma prova com este nome para a banca selecionada.";
                } else {
                    // Validar formato de tempo
                    if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $tempo)) {
                        $error_message = "Formato de tempo inválido. Use HH:MM:SS.";
                    } else {
                        if ($acao == 'inserir') {
                            // Validação básica
                            if (!empty($nome) && !empty($Banca_cod_banca)) {
                                $sql = "INSERT INTO prova (nome, tempo, Banca_cod_banca) VALUES ('$nome', '$tempo', '$Banca_cod_banca')";
                                if ($conn->query($sql) === TRUE) {
                                    $success_message = "Prova inserida com sucesso!";
                                } else {
                                    $error_message = "Erro: " . $conn->error;
                                }
                            } else {
                                $error_message = "Por favor, insira todos os campos.";
                            }
                        } elseif ($acao == 'alterar') {
                            // Atualizar registro
                            if (!empty($cod_prova) && !empty($nome) && !empty($Banca_cod_banca)) {
                                $sql = "UPDATE prova SET nome='$nome', tempo='$tempo', Banca_cod_banca='$Banca_cod_banca' WHERE cod_prova='$cod_prova'";
                                if ($conn->query($sql) === TRUE) {
                                    $success_message = "Prova alterada com sucesso!";
                                } else {
                                    $error_message = "Erro: " . $conn->error;
                                }
                            } else {
                                $error_message = "Por favor, insira todos os campos.";
                            }
                        }
                    }
                }
            }
            // Excluir registro
            if (isset($_GET['delete'])) {
                $cod_prova = $_GET['delete'];
                $sql = "DELETE FROM prova WHERE cod_prova=$cod_prova";
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Prova excluída com sucesso!";
                } else {
                    $error_message = "Erro: " . $conn->error;
                }
            }

            // Formulário para criar/atualizar registros
            $cod_prova = $_GET['edit'] ?? null;
            $nome = '';
            $tempo = '';
            $Banca_cod_banca = '';

            if ($cod_prova) {
                $result = $conn->query("SELECT * FROM prova WHERE cod_prova=$cod_prova");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $nome = $row['nome'];
                    $tempo = $row['tempo'];
                    $Banca_cod_banca = $row['Banca_cod_banca'];
                }
            }
            ?>
<?php if ($error_message): ?>
    <p class="error-message" style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
<?php elseif ($success_message): ?>
    <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
<?php endif; ?>


            <form action="prova.php" method="POST">
                <input type="hidden" name="cod_prova" value="<?php echo htmlspecialchars($cod_prova); ?>">
                <input type="hidden" name="acao" value="<?php echo $cod_prova ? 'alterar' : 'inserir'; ?>">
                <div id="input">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da prova" title="Preencha o nome da prova" required>

                    <label for="tempo">Tempo (HH:MM:SS):</label>
                    <input type="text" id="tempo" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" placeholder="Preencha o tempo (HH:MM:SS)" title="Preencha o tempo" required>

                    <label for="Banca_cod_banca">Banca:</label>
                    <select id="Banca_cod_banca" name="Banca_cod_banca" required>
                        <?php
                        $result = $conn->query("SELECT cod_banca, nome FROM Banca");
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row['cod_banca'] == $Banca_cod_banca ? 'selected' : '';
                            echo "<option value='{$row['cod_banca']}' $selected>{$row['nome']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>

            <h2>Provas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tempo</th>
                        <th>Banca</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT p.cod_prova, p.nome, p.tempo, b.nome as banca FROM prova p JOIN Banca b ON p.Banca_cod_banca = b.cod_banca");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['nome']}</td>
                            <td>{$row['tempo']}</td>
                            <td>{$row['banca']}</td>
                            <td class='actions'>
                                <a href='prova.php?edit={$row['cod_prova']}' class='edit-button' title='Editar'><i class='fas fa-pencil-alt'></i></a>
                                <a href='#' onclick='openModal(\"prova.php?delete={$row['cod_prova']}\"); return false;' class='delete-button' title='Excluir'><i class='fas fa-trash-alt'></i></a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal de confirmação -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Você tem certeza de que deseja excluir este registro?</p>
            <a id="confirmButton" href="#" class="confirm-button">Confirmar</a>
            <button class="cancel-button" onclick="closeModal()">Cancelar</button>
        </div>
    </div>

    <script>
        function openModal(url) {
            document.getElementById('confirmButton').href = url;
            document.getElementById('confirmModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('confirmModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>
