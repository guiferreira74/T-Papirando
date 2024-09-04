<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Concurso</title>
    <link rel="stylesheet" href="concurso.css"> <!-- Usando o mesmo CSS para manter o layout -->
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
        <a href="#">Início</a>
        <a href="simulado.php">Simulados</a>
        <a href="banca.html">Bancas</a>
        <a href="#">Desempenho</a>
    </nav>

    <main id="main-container">
        <div id="corpo">
            <h1>Cadastro de Concurso</h1>

            <?php
            // Conexão ao banco de dados
            $servername = "localhost";
            $username = "root";
            $password = "admin";
            $dbname = "topapirando";

            // Criar a conexão
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar a conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            $error_message = '';
            $success_message = '';

            // Verifica se o formulário foi enviado via POST
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Coleta os dados do formulário
                $nome = $conn->real_escape_string($_POST['nome']);
                $descricao = $conn->real_escape_string($_POST['descricao']);
                $qtd_questoes = $conn->real_escape_string($_POST['qtd_questoes']);
                $data = $conn->real_escape_string($_POST['data']);
                $vagas = $conn->real_escape_string($_POST['vagas']);
                $nivel_cod_nivel = $conn->real_escape_string($_POST['nivel_cod_nivel']);
                $Banca_cod_banca = $conn->real_escape_string($_POST['Banca_cod_banca']);
                $instituicao_cod_instituicao = $conn->real_escape_string($_POST['instituicao_cod_instituicao']);

                // Query SQL para inserir os dados
                $sql = "INSERT INTO concurso (nome, descricao, `qtd questoes`, data, vagas, nivel_cod_nivel, Banca_cod_banca, instituicao_cod_instituicao)
                        VALUES ('$nome', '$descricao', '$qtd_questoes', '$data', '$vagas', '$nivel_cod_nivel', '$Banca_cod_banca', '$instituicao_cod_instituicao')";

                // Executa a query e verifica se foi bem-sucedida
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Novo concurso inserido com sucesso!";
                } else {
                    $error_message = "Erro: " . $sql . "<br>" . $conn->error;
                }

                // Fecha a conexão
                $conn->close();
            }
            ?>

            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif ($success_message): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>

            <form action="concurso.php" method="post">
                <div id="input">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" placeholder="Preencha o nome do concurso" title="Preencha o nome do concurso" required>
                    
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Preencha a descrição" title="Preencha a descrição" required>

                    <label for="qtd_questoes">Quantidade de Questões:</label>
                    <input type="number" id="qtd_questoes" name="qtd_questoes" placeholder="Preencha a quantidade de questões" title="Preencha a quantidade de questões" required>

                    <label for="data">Data:</label>
                    <input type="date" id="data" name="data" required>

                    <label for="vagas">Vagas:</label>
                    <input type="number" id="vagas" name="vagas" placeholder="Preencha o número de vagas" title="Preencha o número de vagas" required>

                    <label for="nivel_cod_nivel">Nível:</label>
                    <select id="nivel_cod_nivel" name="nivel_cod_nivel" required>
                        <option value="">Selecione um Nível</option>
                        <?php
                        // Preenchendo o dropdown com opções da tabela nivel
                        $result = $conn->query("SELECT cod_nivel, tipo_nivel FROM nivel");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cod_nivel'] . "'>" . $row['tipo_nivel'] . "</option>";
                        }
                        ?>
                    </select>

                    <label for="Banca_cod_banca">Banca:</label>
                    <select id="Banca_cod_banca" name="Banca_cod_banca" required>
                        <option value="">Selecione uma Banca</option>
                        <?php
                        // Preenchendo o dropdown com opções da tabela Banca
                        $result = $conn->query("SELECT cod_banca, nome FROM Banca");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cod_banca'] . "'>" . $row['nome'] . "</option>";
                        }
                        ?>
                    </select>

                    <label for="instituicao_cod_instituicao">Instituição:</label>
                    <select id="instituicao_cod_instituicao" name="instituicao_cod_instituicao" required>
                        <option value="">Selecione uma Instituição</option>
                        <?php
                        // Preenchendo o dropdown com opções da tabela instituicao
                        $result = $conn->query("SELECT cod_instituicao, nome FROM instituicao");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cod_instituicao'] . "'>" . $row['nome'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="button-container">
                    <button type="submit" class="save-button">Salvar</button>
                    <button type="reset" class="clear-button">Limpar</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
