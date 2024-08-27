<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Simulado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div>
        <h2>Cadastro de Simulado</h2>
        <form action="simulado.php" method="post">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="nivel">Nível:</label>
            <select id="nivel" name="nivel_cod_nivel" required>
                <!-- Opções serão carregadas via PHP -->
                <?php
                // Conectar ao banco de dados
                $conn = new mysqli("localhost", "root", "admin", "topapirando");
                if ($conn->connect_error) {
                    die("Falha na conexão: " . $conn->connect_error);
                }

                // Obter dados da tabela nivel
                $result = $conn->query("SELECT cod_nivel, tipo_nivel FROM nivel");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['cod_nivel']}'>{$row['tipo_nivel']}</option>";
                }
                $conn->close();
                ?>
            </select>

            <label for="disciplina">Disciplina:</label>
            <select id="disciplina" name="disciplina_cod_disciplina" required>
                <!-- Opções serão carregadas via PHP -->
                <?php
                // Conectar ao banco de dados
                $conn = new mysqli("localhost", "root", "admin", "topapirando");
                if ($conn->connect_error) {
                    die("Falha na conexão: " . $conn->connect_error);
                }

                // Obter dados da tabela disciplina
                $result = $conn->query("SELECT cod_disciplina, nome FROM disciplina");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['cod_disciplina']}'>{$row['nome']}</option>";
                }
                $conn->close();
                ?>
            </select>

            <label for="prova">Prova:</label>
            <select id="prova" name="prova_cod_prova" required>
                <!-- Opções serão carregadas via PHP -->
                <?php
                // Conectar ao banco de dados
                $conn = new mysqli("localhost", "root", "admin", "topapirando");
                if ($conn->connect_error) {
                    die("Falha na conexão: " . $conn->connect_error);
                }

                // Obter dados da tabela prova
                $result = $conn->query("SELECT cod_prova, nome FROM prova");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['cod_prova']}'>{$row['nome']}</option>";
                }
                $conn->close();
                ?>
            </select>

            <label for="concurso">Concurso:</label>
            <select id="concurso" name="concurso_cod_concurso" required>
                <!-- Opções serão carregadas via PHP -->
                <?php
                // Conectar ao banco de dados
                $conn = new mysqli("localhost", "root", "admin", "topapirando");
                if ($conn->connect_error) {
                    die("Falha na conexão: " . $conn->connect_error);
                }

                // Obter dados da tabela concurso
                $result = $conn->query("SELECT cod_concurso, nome FROM concurso");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['cod_concurso']}'>{$row['nome']}</option>";
                }
                $conn->close();
                ?>
            </select>

            <label for="duracao">Duração:</label>
            <select id="duracao" name="duracao_cod_duracao" required>
                <!-- Opções serão carregadas via PHP -->
                <?php
                // Conectar ao banco de dados
                $conn = new mysqli("localhost", "root", "admin", "topapirando");
                if ($conn->connect_error) {
                    die("Falha na conexão: " . $conn->connect_error);
                }

                // Obter dados da tabela duracao
                $result = $conn->query("SELECT cod_duracao, tempo FROM duracao");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['cod_duracao']}'>{$row['tempo']}</option>";
                }
                $conn->close();
                ?>
            </select>

            <input id="save" type="submit" value="Salvar">
            <input id="limpar" type="reset" value="Limpar">
        </form>
    </div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $dbname = "topapirando";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Obter os dados do formulário e validar
    $nome = $conn->real_escape_string($_POST['nome']);
    $nivel_cod_nivel = intval($_POST['nivel_cod_nivel']);
    $disciplina_cod_disciplina = intval($_POST['disciplina_cod_disciplina']);
    $prova_cod_prova = intval($_POST['prova_cod_prova']);
    $concurso_cod_concurso = intval($_POST['concurso_cod_concurso']);
    $duracao_cod_duracao = intval($_POST['duracao_cod_duracao']);

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO simulado (nome, nivel_cod_nivel, disciplina_cod_disciplina, prova_cod_prova, concurso_cod_concurso, duracao_cod_duracao)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiii", $nome, $nivel_cod_nivel, $disciplina_cod_disciplina, $prova_cod_prova, $concurso_cod_concurso, $duracao_cod_duracao);

    if ($stmt->execute()) {
        echo "<p>Simulado cadastrado com sucesso!</p>";
    } else {
        echo "<p>Erro: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
