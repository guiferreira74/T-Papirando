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
        $tipo_nivel = $_POST['tipo_nivel'];
        
        // Validação básica
        if (!empty($tipo_nivel)) {
            // Insere o novo nível na tabela nivel
            $sql = "INSERT INTO nivel (tipo_nivel) VALUES ('$tipo_nivel')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Nível inserido com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira o nome do nível.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
        $cod_nivel = $_POST['cod_nivel'];
        $tipo_nivel = $_POST['tipo_nivel'];
        
        // Validação básica
        if (!empty($tipo_nivel) && !empty($cod_nivel)) {
            // Atualiza o nível na tabela nivel
            $sql = "UPDATE nivel SET tipo_nivel='$tipo_nivel' WHERE cod_nivel='$cod_nivel'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Nível alterado com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $cod_nivel = $_POST['cod_nivel'];
        
        // Validação básica
        if (!empty($cod_nivel)) {
            // Exclui o nível da tabela nivel
            $sql = "DELETE FROM nivel WHERE cod_nivel='$cod_nivel'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Nível excluído com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, selecione um nível para excluir.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'consultar') {
        $showTable = true;
    }
}

// Consulta todos os níveis
$sql = "SELECT cod_nivel, tipo_nivel FROM nivel";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Nível</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h2>Cadastro de Nível</h2>
    
    <!-- Formulário para inserir novos níveis -->
    <form action="nivel.php" method="POST">
        <input type="hidden" name="acao" value="inserir">
        <label for="tipo_nivel">Nome do Nível:</label>
        <input type="text" name="tipo_nivel" id="tipo_nivel" required>
        
        <button type="submit">Salvar Nível</button>
    </form>

    <hr>
    
    <!-- Formulário para consultar níveis -->
    <form action="nivel.php" method="POST">
        <input type="hidden" name="acao" value="consultar">
        <button type="submit">Consultar Níveis</button>
    </form>
    
    <hr>
    
    <!-- Formulário para consultar e alterar níveis -->
    <form action="nivel.php" method="POST">
        <label for="cod_nivel">Escolha um Nível para Alterar:</label>
        <select name="cod_nivel" id="cod_nivel" required>
            <option value="">Selecione um nível</option>
            <?php
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_nivel"] . "'>" . $row["tipo_nivel"] . "</option>";
                }
            }
            ?>
        </select>

        <label for="tipo_nivel_alterar">Novo Nome do Nível:</label>
        <input type="text" name="tipo_nivel" id="tipo_nivel_alterar" required>

        <input type="hidden" name="acao" value="alterar">
        
        <button type="submit">Alterar Nível</button>
    </form>
    
    <hr>
    
    <!-- Formulário para excluir níveis -->
    <form action="nivel.php" method="POST">
        <label for="cod_nivel_excluir">Escolha um Nível para Excluir:</label>
        <select name="cod_nivel" id="cod_nivel_excluir" required>
            <option value="">Selecione um nível</option>
            <?php
            // Reabre a conexão para consultar novamente os níveis
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT cod_nivel, tipo_nivel FROM nivel";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_nivel"] . "'>" . $row["tipo_nivel"] . "</option>";
                }
            }
            $conn->close();
            ?>
        </select>

        <input type="hidden" name="acao" value="excluir">
        
        <button type="submit">Excluir Nível</button>
    </form>
    
    <hr>
    
    <!-- Tabela para exibir os níveis existentes -->
    <?php if ($showTable): ?>
        <h3>Níveis Cadastrados</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome do Nível</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reabre a conexão para consultar os níveis
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT cod_nivel, tipo_nivel FROM nivel";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["cod_nivel"] . "</td>";
                        echo "<td>" . $row["tipo_nivel"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhum nível encontrado.</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
