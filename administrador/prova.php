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

// Variável para controle da exibição da tabela
$showTable = false;

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] == 'inserir') {
        $nome = $_POST['nome'];
        $horas = $_POST['horas'];
        $minutos = $_POST['minutos'];
        $segundos = $_POST['segundos'];
        $tempo = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        $Banca_cod_banca = $_POST['Banca_cod_banca'];

        // Validação básica
        if (!empty($nome) && !empty($Banca_cod_banca)) {
            // Insere a nova prova na tabela prova
            $sql = "INSERT INTO prova (nome, tempo, Banca_cod_banca) VALUES ('$nome', '$tempo', '$Banca_cod_banca')";

            if ($conn->query($sql) === TRUE) {
                echo "Prova inserida com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
        $cod_prova = $_POST['cod_prova'];
        $nome = $_POST['nome'];
        $horas = $_POST['horas'];
        $minutos = $_POST['minutos'];
        $segundos = $_POST['segundos'];
        $tempo = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        $Banca_cod_banca = $_POST['Banca_cod_banca'];

        // Validação básica
        if (!empty($cod_prova) && !empty($nome) && !empty($Banca_cod_banca)) {
            // Atualiza a prova na tabela prova
            $sql = "UPDATE prova SET nome='$nome', tempo='$tempo', Banca_cod_banca='$Banca_cod_banca' WHERE cod_prova='$cod_prova'";

            if ($conn->query($sql) === TRUE) {
                echo "Prova alterada com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $cod_prova = $_POST['cod_prova'];

        // Validação básica
        if (!empty($cod_prova)) {
            // Exclui a prova da tabela prova
            $sql = "DELETE FROM prova WHERE cod_prova='$cod_prova'";

            if ($conn->query($sql) === TRUE) {
                echo "Prova excluída com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, selecione uma prova para excluir.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'consultar') {
        $showTable = true;
    }
}

// Consulta todas as provas
$sql = "SELECT cod_prova, nome, tempo, Banca_cod_banca FROM prova";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Prova</title>
    <link rel="stylesheet" href="prova.css"> 
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
            <a href="">Sobre</a>
            <a href="">Ajuda</a>
            <a href="">Entrar</a>
        </div>
    </header>
    <div class="menu">
        <a href="">Inicio</a>
        <a href="">Simulados</a>
        <a href="prova.php">Provas</a>
        <a href="">Desempenho</a>
    </div>

    <div id="main-container">
        <div id="corpo">
            <h1>Cadastro de Prova</h1>

            <!-- Formulário para inserir novas provas -->
            <form action="prova.php" method="POST">
                <input type="hidden" name="acao" value="inserir">
                <div id="input">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" id="nome" required><br>

                    <label for="tempo">Tempo (HH:MM:SS):</label>
                    <input type="number" name="horas" id="horas" min="0" max="23" placeholder="HH" required>
                    <input type="number" name="minutos" id="minutos" min="0" max="59" placeholder="MM" required>
                    <input type="number" name="segundos" id="segundos" min="0" max="59" placeholder="SS" required><br>

                    <label for="Banca_cod_banca">Banca:</label>
                    <select name="Banca_cod_banca" id="Banca_cod_banca" required>
                        <option value="">Selecione uma Banca</option>
                        <?php
                        // Reabre a conexão para consultar as bancas
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        $sql = "SELECT cod_banca, nome FROM Banca";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["cod_banca"] . "'>" . $row["nome"] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select><br>

                    <div class="button-container">
                        <button type="submit" class="save-button">Salvar</button>
                        <button type="reset" class="clear-button">Limpar</button>
                    </div>
                </div>
            </form>

            <hr>
            
            <!-- Formulário para consultar provas -->
            <form action="prova.php" method="POST">
                <input type="hidden" name="acao" value="consultar">
                <button type="submit" class="consultar-button">Consultar</button>
            </form>
            
            <hr>
            
            <!-- Formulário para consultar e alterar provas -->
            <form action="prova.php" method="POST">
                <label for="cod_prova">Escolha uma Prova para Alterar:</label>
                <select name="cod_prova" id="cod_prova" required>
                    <option value="">Selecione uma prova</option>
                    <?php
                    // Reabre a conexão para consultar as provas
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $sql = "SELECT cod_prova, nome FROM prova";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["cod_prova"] . "'>" . $row["nome"] . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select><br>

                <label for="nome_alterar">Novo Nome da Prova:</label>
                <input type="text" name="nome" id="nome_alterar" required><br>

                <label for="tempo_alterar">Novo Tempo (HH:MM:SS):</label>
                <input type="number" name="horas" id="horas_alterar" min="0" max="23" placeholder="HH" required>
                <input type="number" name="minutos" id="minutos_alterar" min="0" max="59" placeholder="MM" required>
                <input type="number" name="segundos" id="segundos_alterar" min="0" max="59" placeholder="SS" required><br>

                <label for="Banca_cod_banca_alterar">Nova Banca:</label>
                <select name="Banca_cod_banca" id="Banca_cod_banca_alterar" required>
                    <option value="">Selecione uma Banca</option>
                    <?php
                    // Reabre a conexão para consultar as bancas
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    $sql = "SELECT cod_banca, nome FROM Banca";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["cod_banca"] . "'>" . $row["nome"] . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select><br>

                <input type="hidden" name="acao" value="alterar">
                <div class="button-container">
                    <button type="submit" class="update-button">Alterar Prova</button>
                    <button type="submit" name="acao" value="excluir" class="delete-button">Excluir Prova</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
