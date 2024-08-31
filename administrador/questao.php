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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Questão</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="main-container">
        <div id="corpo">
            <h2>Cadastro de Questão</h2>
            <form action="questao.php" method="post">
                <div class="form-group">
                    <label for="pergunta">Pergunta:</label>
                    <input type="text" name="pergunta" id="pergunta" required>
                </div>

                <div class="form-group">
                    <label for="resposta1">Resposta 1:</label>
                    <input type="text" name="resposta1" id="resposta1" required>
                </div>

                <div class="form-group">
                    <label for="resposta2">Resposta 2:</label>
                    <input type="text" name="resposta2" id="resposta2" required>
                </div>

                <div class="form-group">
                    <label for="resposta3">Resposta 3:</label>
                    <input type="text" name="resposta3" id="resposta3" required>
                </div>

                <div class="form-group">
                    <label for="resposta4">Resposta 4:</label>
                    <input type="text" name="resposta4" id="resposta4" required>
                </div>

                <div class="form-group">
                    <label for="resposta_correta">Resposta Correta:</label>
                    <input type="text" name="resposta_correta" id="resposta_correta" required>
                </div>

                <div class="form-group">
                    <label for="disciplina_cod_disciplina">Disciplina:</label>
                    <select name="disciplina_cod_disciplina" id="disciplina_cod_disciplina" required>
                        <option value="">Selecione uma Disciplina</option>
                        <?php
                        // Preenchendo o dropdown com opções da tabela disciplina
                        $result = $conn->query("SELECT cod_disciplina, nome FROM disciplina");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cod_disciplina'] . "'>" . $row['nome'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="concurso_cod_concurso">Concurso:</label>
                    <select name="concurso_cod_concurso" id="concurso_cod_concurso" required>
                        <option value="">Selecione um Concurso</option>
                        <?php
                        // Preenchendo o dropdown com opções da tabela concurso
                        $result = $conn->query("SELECT cod_concurso, nome FROM concurso");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cod_concurso'] . "'>" . $row['nome'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
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
                    </select>
                </div>

                <div class="form-group">
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
                    </select>
                </div>

                <div class="form-group">
                    <label for="prova_cod_prova">Prova:</label>
                    <select name="prova_cod_prova" id="prova_cod_prova" required>
                        <option value="">Selecione uma Prova</option>
                        <?php
                        // Preenchendo o dropdown com opções da tabela prova
                        $result = $conn->query("SELECT cod_prova, nome FROM prova");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cod_prova'] . "'>" . $row['nome'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="grau_cod_grau">Grau:</label>
                    <select name="grau_cod_grau" id="grau_cod_grau" required>
                        <option value="">Selecione um Grau</option>
                        <?php
                        // Preenchendo o dropdown com opções da tabela grau
                        $result = $conn->query("SELECT cod_grau, tipo_grau FROM grau");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cod_grau'] . "'>" . $row['tipo_grau'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-buttons">
                    <button type="submit" id="save">Salvar</button>
                    <button type="reset" id="clear">Limpar</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    // Verifica se o formulário foi enviado via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Coleta os dados do formulário
        $pergunta = $_POST['pergunta'];
        $resposta1 = $_POST['resposta1'];
        $resposta2 = $_POST['resposta2'];
        $resposta3 = $_POST['resposta3'];
        $resposta4 = $_POST['resposta4'];
        $resposta_correta = $_POST['resposta_correta'];
        $disciplina_cod_disciplina = $_POST['disciplina_cod_disciplina'];
        $concurso_cod_concurso = $_POST['concurso_cod_concurso'];
        $nivel_cod_nivel = $_POST['nivel_cod_nivel'];
        $Banca_cod_banca = $_POST['Banca_cod_banca'];
        $prova_cod_prova = $_POST['prova_cod_prova'];
        $grau_cod_grau = $_POST['grau_cod_grau'];

        // Query SQL para inserir os dados
        $sql = "INSERT INTO questao (pergunta, `resposta 1`, `resposta 2`, `resposta 3`, `resposta 4`, `resposta correta`, disciplina_cod_disciplina, concurso_cod_concurso, nivel_cod_nivel, Banca_cod_banca, prova_cod_prova, grau_cod_grau)
                VALUES ('$pergunta', '$resposta1', '$resposta2', '$resposta3', '$resposta4', '$resposta_correta', '$disciplina_cod_disciplina', '$concurso_cod_concurso', '$nivel_cod_nivel', '$Banca_cod_banca', '$prova_cod_prova', '$grau_cod_grau')";

        // Executa a query e verifica se foi bem-sucedida
        if ($conn->query($sql) === TRUE) {
            echo "<p>Nova questão inserida com sucesso!</p>";
        } else {
            echo "<p>Erro: " . $sql . "<br>" . $conn->error . "</p>";
        }

        // Fecha a conexão
        $conn->close();
    }
    ?>
</body>
</html>
