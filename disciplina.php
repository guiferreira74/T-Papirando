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

// Verifica se o formulário de inserção foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] == 'inserir') {
        $nome_disciplina = $_POST['nome_disciplina'];
        
        // Validação básica
        if (!empty($nome_disciplina)) {
            // Insere a nova disciplina na tabela disciplina
            $sql = "INSERT INTO disciplina (nome) VALUES ('$nome_disciplina')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Disciplina inserida com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira o nome da disciplina.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
        $cod_disciplina = $_POST['cod_disciplina'];
        $nome_disciplina = $_POST['nome_disciplina'];
        
        // Validação básica
        if (!empty($nome_disciplina) && !empty($cod_disciplina)) {
            // Atualiza a disciplina na tabela disciplina
            $sql = "UPDATE disciplina SET nome='$nome_disciplina' WHERE cod_disciplina='$cod_disciplina'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Disciplina alterada com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $cod_disciplina = $_POST['cod_disciplina'];
        
        // Validação básica
        if (!empty($cod_disciplina)) {
            // Exclui a disciplina da tabela disciplina
            $sql = "DELETE FROM disciplina WHERE cod_disciplina='$cod_disciplina'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Disciplina excluída com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, selecione uma disciplina para excluir.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'consultar') {
        $showTable = true;
    }
}

// Consulta todas as disciplinas
$sql = "SELECT cod_disciplina, nome FROM disciplina";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Disciplina</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h2>Cadastro de Disciplina</h2>
    
    <!-- Formulário para inserir novas disciplinas -->
    <form action="disciplina.php" method="POST">
        <input type="hidden" name="acao" value="inserir">
        <label for="nome_disciplina">Nome da Disciplina:</label>
        <input type="text" name="nome_disciplina" id="nome_disciplina" required>
        
        <button type="submit">Salvar Disciplina</button>
    </form>

    <hr>
    
    <!-- Formulário para consultar disciplinas -->
    <form action="disciplina.php" method="POST">
        <input type="hidden" name="acao" value="consultar">
        <button type="submit">Consultar Disciplinas</button>
    </form>
    
    <hr>
    
    <!-- Formulário para consultar e alterar disciplinas -->
    <form action="disciplina.php" method="POST">
        <label for="cod_disciplina">Escolha uma Disciplina para Alterar:</label>
        <select name="cod_disciplina" id="cod_disciplina" required>
            <option value="">Selecione uma disciplina</option>
            <?php
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_disciplina"] . "'>" . $row["nome"] . "</option>";
                }
            }
            ?>
        </select>

        <label for="nome_disciplina_alterar">Novo Nome da Disciplina:</label>
        <input type="text" name="nome_disciplina" id="nome_disciplina_alterar" required>

        <input type="hidden" name="acao" value="alterar">
        
        <button type="submit">Alterar Disciplina</button>
    </form>
    
    <hr>
    
    <!-- Formulário para excluir disciplinas -->
    <form action="disciplina.php" method="POST">
        <label for="cod_disciplina_excluir">Escolha uma Disciplina para Excluir:</label>
        <select name="cod_disciplina" id="cod_disciplina_excluir" required>
            <option value="">Selecione uma disciplina</option>
            <?php
            // Reabre a conexão para consultar novamente as disciplinas
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT cod_disciplina, nome FROM disciplina";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_disciplina"] . "'>" . $row["nome"] . "</option>";
                }
            }
            $conn->close();
            ?>
        </select>

        <input type="hidden" name="acao" value="excluir">
        
        <button type="submit">Excluir Disciplina</button>
    </form>
    
    <hr>
    
    <!-- Tabela para exibir as disciplinas existentes -->
    <?php if ($showTable): ?>
        <h3>Disciplinas Cadastradas</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome da Disciplina</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reabre a conexão para consultar as disciplinas
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT cod_disciplina, nome FROM disciplina";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["cod_disciplina"] . "</td>";
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhuma disciplina encontrada.</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
