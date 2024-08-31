<?php
//testeahsdad

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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Concurso</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <div>
        <!-- Formulário HTML para inserir dados -->
        <form action="concurso.php" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required><br>
            
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" id="descricao" required><br>
            
            <label for="qtd_questoes">Quantidade de Questões:</label>
            <input type="text" name="qtd_questoes" id="qtd_questoes" required><br>
            
            <label for="data">Data:</label>
            <input type="date" name="data" id="data" required><br>
            
            <label for="vagas">Vagas:</label>
            <input type="text" name="vagas" id="vagas" required><br>

            <label for="nivel_cod_nivel">Nível:</label>
            <select name="nivel_cod_nivel" id="nivel_cod_nivel" required>
                <option value="">Selecione um Nível</option>
                <?php
                // Preenchendo o dropdown com opções da tabela nivel
                $result = $conn->query("SELECT cod_nivel, tipo_nivel FROM nivel");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['cod_nivel'] . "'>" . $row['tipo_nivel'] . "</option>";
                }
                ?>
            </select><br>

            <label for="Banca_cod_banca">Banca:</label>
            <select name="Banca_cod_banca" id="Banca_cod_banca" required>
                <option value="">Selecione uma Banca</option>
                <?php
                // Preenchendo o dropdown com opções da tabela Banca
                $result = $conn->query("SELECT cod_banca, nome FROM Banca");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['cod_banca'] . "'>" . $row['nome'] . "</option>";
                }
                ?>
            </select><br>

            <label for="instituicao_cod_instituicao">Instituição:</label>
            <select name="instituicao_cod_instituicao" id="instituicao_cod_instituicao" required>
                <option value="">Selecione uma Instituição</option>
                <?php
                // Preenchendo o dropdown com opções da tabela instituicao
                $result = $conn->query("SELECT cod_instituicao, nome FROM instituicao");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['cod_instituicao'] . "'>" . $row['nome'] . "</option>";
                }
                ?>
            </select><br>

            <div class="form-buttons">
                <button type="submit" id="save">Salvar</button>
                <button type="reset" id="clear">Limpar</button>
            </div>
        </form>
    </div>

    <?php
    // Verifica se o formulário foi enviado via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Coleta os dados do formulário
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $qtd_questoes = $_POST['qtd_questoes'];
        $data = $_POST['data'];
        $vagas = $_POST['vagas'];
        $nivel_cod_nivel = $_POST['nivel_cod_nivel'];
        $Banca_cod_banca = $_POST['Banca_cod_banca'];
        $instituicao_cod_instituicao = $_POST['instituicao_cod_instituicao'];

        // Query SQL para inserir os dados
        $sql = "INSERT INTO concurso (nome, descricao, `qtd questoes`, data, vagas, nivel_cod_nivel, Banca_cod_banca, instituicao_cod_instituicao)
                VALUES ('$nome', '$descricao', '$qtd_questoes', '$data', '$vagas', '$nivel_cod_nivel', '$Banca_cod_banca', '$instituicao_cod_instituicao')";

        // Executa a query e verifica se foi bem-sucedida
        if ($conn->query($sql) === TRUE) {
            echo "<p>Novo concurso inserido com sucesso!</p>";
        } else {
            echo "<p>Erro: " . $sql . "<br>" . $conn->error . "</p>";
        }

        // Fecha a conexão
        $conn->close();
    }
    ?>
</body>
</html>
