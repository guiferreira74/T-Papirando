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
        $tipo_grau = $_POST['tipo_grau'];
        
        // Validação básica
        if (!empty($tipo_grau)) {
            // Insere o novo grau na tabela grau
            $sql = "INSERT INTO grau (tipo_grau) VALUES ('$tipo_grau')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Grau inserido com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira o nome do grau.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
        $cod_grau = $_POST['cod_grau'];
        $tipo_grau = $_POST['tipo_grau'];
        
        // Validação básica
        if (!empty($tipo_grau) && !empty($cod_grau)) {
            // Atualiza o grau na tabela grau
            $sql = "UPDATE grau SET tipo_grau='$tipo_grau' WHERE cod_grau='$cod_grau'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Grau alterado com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $cod_grau = $_POST['cod_grau'];
        
        // Validação básica
        if (!empty($cod_grau)) {
            // Exclui o grau da tabela grau
            $sql = "DELETE FROM grau WHERE cod_grau='$cod_grau'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Grau excluído com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, selecione um grau para excluir.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'consultar') {
        $showTable = true;
    }
}

// Consulta todos os graus
$sql = "SELECT cod_grau, tipo_grau FROM grau";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Grau</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h2>Cadastro de Grau</h2>
    
    <!-- Formulário para inserir novos graus -->
    <form action="grau.php" method="POST">
        <input type="hidden" name="acao" value="inserir">
        <label for="tipo_grau">Nome do Grau:</label>
        <input type="text" name="tipo_grau" id="tipo_grau" required>
        
        <button type="submit">Salvar Grau</button>
    </form>

    <hr>
    
    <!-- Formulário para consultar graus -->
    <form action="grau.php" method="POST">
        <input type="hidden" name="acao" value="consultar">
        <button type="submit">Consultar Graus</button>
    </form>
    
    <hr>
    
    <!-- Formulário para consultar e alterar graus -->
    <form action="grau.php" method="POST">
        <label for="cod_grau">Escolha um Grau para Alterar:</label>
        <select name="cod_grau" id="cod_grau" required>
            <option value="">Selecione um grau</option>
            <?php
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_grau"] . "'>" . $row["tipo_grau"] . "</option>";
                }
            }
            ?>
        </select>

        <label for="tipo_grau_alterar">Novo Nome do Grau:</label>
        <input type="text" name="tipo_grau" id="tipo_grau_alterar" required>

        <input type="hidden" name="acao" value="alterar">
        
        <button type="submit">Alterar Grau</button>
    </form>
    
    <hr>
    
    <!-- Formulário para excluir graus -->
    <form action="grau.php" method="POST">
        <label for="cod_grau_excluir">Escolha um Grau para Excluir:</label>
        <select name="cod_grau" id="cod_grau_excluir" required>
            <option value="">Selecione um grau</option>
            <?php
            // Reabre a conexão para consultar novamente os graus
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT cod_grau, tipo_grau FROM grau";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_grau"] . "'>" . $row["tipo_grau"] . "</option>";
                }
            }
            $conn->close();
            ?>
        </select>

        <input type="hidden" name="acao" value="excluir">
        
        <button type="submit">Excluir Grau</button>
    </form>
    
    <hr>
    
    <!-- Tabela para exibir os graus existentes -->
    <?php if ($showTable): ?>
        <h3>Graus Cadastrados</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome do Grau</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reabre a conexão para consultar os graus
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT cod_grau, tipo_grau FROM grau";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["cod_grau"] . "</td>";
                        echo "<td>" . $row["tipo_grau"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhum grau encontrado.</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
