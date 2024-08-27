<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "Topapirando";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Adiciona uma nova duração ao banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'inserir') {
    $tempo = $_POST['tempo'];

    $sql = "INSERT INTO duracao (tempo) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tempo);
    
    if ($stmt->execute()) {
        echo "Nova duração adicionada com sucesso.";
    } else {
        echo "Erro: " . $stmt->error;
    }
}

// Atualiza uma duração existente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
    $cod_duracao = $_POST['cod_duracao'];
    $tempo = $_POST['tempo'];

    $sql = "UPDATE duracao SET tempo = ? WHERE cod_duracao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $tempo, $cod_duracao);
    
    if ($stmt->execute()) {
        echo "Duração atualizada com sucesso.";
    } else {
        echo "Erro: " . $stmt->error;
    }
}

// Exclui uma duração existente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
    $cod_duracao = $_POST['cod_duracao'];

    $sql = "DELETE FROM duracao WHERE cod_duracao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cod_duracao);
    
    if ($stmt->execute()) {
        echo "Duração excluída com sucesso.";
    } else {
        echo "Erro: " . $stmt->error;
    }
}

// Consulta todas as durações
$sql = "SELECT cod_duracao, tempo FROM duracao";
$result = $conn->query($sql);

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Duração</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div>
        <h2>Adicionar Duração</h2>
        <form action="" method="POST">
            <input type="hidden" name="acao" value="inserir">
            <label for="tempo">Tempo:</label>
            <input type="time" id="tempo" name="tempo" step="1" required><br><br>
            
            <button id="save" type="submit" name="save">Salvar</button>
            <button id="limpar" type="reset">Limpar</button>
        </form>
    </div>

    <hr>

    <div>
        <h2>Atualizar Duração</h2>
        <form action="" method="POST">
            <input type="hidden" name="acao" value="alterar">
            <label for="cod_duracao_alterar">Escolha uma Duração para Alterar:</label>
            <select name="cod_duracao" id="cod_duracao_alterar" required>
                <option value="">Selecione uma duração</option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["cod_duracao"] . "'>" . $row["tempo"] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="tempo_alterar">Novo Tempo:</label>
            <input type="time" id="tempo_alterar" name="tempo" step="1" required>
            <button id="save" type="submit">Atualizar</button>
        </form>
    </div>

    <hr>

    <div>
        <h2>Excluir Duração</h2>
        <form action="" method="POST">
            <input type="hidden" name="acao" value="excluir">
            <label for="cod_duracao_excluir">Escolha uma Duração para Excluir:</label>
            <select name="cod_duracao" id="cod_duracao_excluir" required>
                <option value="">Selecione uma duração</option>
                <?php
                // Reabre a conexão para consultar novamente as durações
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT cod_duracao, tempo FROM duracao";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["cod_duracao"] . "'>" . $row["tempo"] . "</option>";
                    }
                }
                $conn->close();
                ?>
            </select>
            <button id="save" type="submit">Excluir</button>
        </form>
    </div>

    <hr>

    <div>
        <h2>Durações Cadastradas</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tempo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Exibe todas as durações cadastradas
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT cod_duracao, tempo FROM duracao";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["cod_duracao"] . "</td>";
                        echo "<td>" . $row["tempo"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhuma duração encontrada.</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
