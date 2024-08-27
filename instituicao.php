<?php
// Conexão com o banco de dados
$servername = "localhost";  // Servidor
$username = "root";         // Usuário do banco de dados
$password = "admin";        // Senha do banco de dados
$dbname = "topapirando";    // Nome do banco de dados

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
        $nome_instituicao = $_POST['nome_instituicao'];
        
        // Validação básica
        if (!empty($nome_instituicao)) {
            // Insere a nova instituição na tabela instituicao
            $sql = "INSERT INTO instituicao (nome) VALUES ('$nome_instituicao')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Instituição inserida com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira o nome da instituição.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
        $cod_instituicao = $_POST['cod_instituicao'];
        $nome_instituicao = $_POST['nome_instituicao'];
        
        // Validação básica
        if (!empty($nome_instituicao) && !empty($cod_instituicao)) {
            // Atualiza a instituição na tabela instituicao
            $sql = "UPDATE instituicao SET nome='$nome_instituicao' WHERE cod_instituicao='$cod_instituicao'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Instituição alterada com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $cod_instituicao = $_POST['cod_instituicao'];
        
        // Validação básica
        if (!empty($cod_instituicao)) {
            // Exclui a instituição da tabela instituicao
            $sql = "DELETE FROM instituicao WHERE cod_instituicao='$cod_instituicao'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Instituição excluída com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, selecione uma instituição para excluir.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'consultar') {
        $showTable = true;
    }
}

// Consulta todas as instituições
$sql = "SELECT cod_instituicao, nome FROM instituicao";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Instituição</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h2>Cadastro de Instituição</h2>
    
    <!-- Formulário para inserir novas instituições -->
    <form action="instituicao.php" method="POST">
        <input type="hidden" name="acao" value="inserir">
        <label for="nome_instituicao">Nome da Instituição:</label>
        <input type="text" name="nome_instituicao" id="nome_instituicao" required>
        
        <button type="submit">Salvar Instituição</button>
    </form>

    <hr>
    
    <!-- Formulário para consultar instituições -->
    <form action="instituicao.php" method="POST">
        <input type="hidden" name="acao" value="consultar">
        <button type="submit">Consultar Instituições</button>
    </form>
    
    <hr>
    
    <!-- Formulário para consultar e alterar instituições -->
    <form action="instituicao.php" method="POST">
        <label for="cod_instituicao">Escolha uma Instituição para Alterar:</label>
        <select name="cod_instituicao" id="cod_instituicao" required>
            <option value="">Selecione uma instituição</option>
            <?php
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_instituicao"] . "'>" . $row["nome"] . "</option>";
                }
            }
            ?>
        </select>

        <label for="nome_instituicao_alterar">Novo Nome da Instituição:</label>
        <input type="text" name="nome_instituicao" id="nome_instituicao_alterar" required>

        <input type="hidden" name="acao" value="alterar">
        
        <button type="submit">Alterar Instituição</button>
    </form>
    
    <hr>
    
    <!-- Formulário para excluir instituições -->
    <form action="instituicao.php" method="POST">
        <label for="cod_instituicao_excluir">Escolha uma Instituição para Excluir:</label>
        <select name="cod_instituicao" id="cod_instituicao_excluir" required>
            <option value="">Selecione uma instituição</option>
            <?php
            // Reabre a conexão para consultar novamente as instituições
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT cod_instituicao, nome FROM instituicao";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_instituicao"] . "'>" . $row["nome"] . "</option>";
                }
            }
            $conn->close();
            ?>
        </select>

        <input type="hidden" name="acao" value="excluir">
        
        <button type="submit">Excluir Instituição</button>
    </form>
    
    <hr>
    
    <!-- Tabela para exibir as instituições existentes -->
    <?php if ($showTable): ?>
        <h3>Instituições Cadastradas</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome da Instituição</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reabre a conexão para consultar as instituições
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT cod_instituicao, nome FROM instituicao";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["cod_instituicao"] . "</td>";
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhuma instituição encontrada.</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
