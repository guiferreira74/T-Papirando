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
        $nome_banca = $_POST['nome_banca'];
        $link_banca = $_POST['link_banca'];
        
        // Validação básica
        if (!empty($nome_banca) && !empty($link_banca)) {
            // Insere a nova banca na tabela banca
            $sql = "INSERT INTO banca (nome, link) VALUES ('$nome_banca', '$link_banca')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Banca inserida com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, preencha todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'alterar') {
        $cod_banca = $_POST['cod_banca'];
        $nome_banca = $_POST['nome_banca'];
        $link_banca = $_POST['link_banca'];
        
        // Validação básica
        if (!empty($nome_banca) && !empty($link_banca) && !empty($cod_banca)) {
            // Atualiza a banca na tabela banca
            $sql = "UPDATE banca SET nome='$nome_banca', link='$link_banca' WHERE cod_banca='$cod_banca'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Banca alterada com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, insira todos os campos.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
        $cod_banca = $_POST['cod_banca'];
        
        // Validação básica
        if (!empty($cod_banca)) {
            // Exclui a banca da tabela banca
            $sql = "DELETE FROM banca WHERE cod_banca='$cod_banca'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Banca excluída com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Por favor, selecione uma banca para excluir.";
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'consultar') {
        $showTable = true;
    }
}

// Consulta todas as bancas
$sql = "SELECT cod_banca, nome, link FROM banca";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro da Banca</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h2>Cadastro da Banca</h2>
    
    <!-- Formulário para inserir novas bancas -->
    <form action="banca.php" method="POST">
        <input type="hidden" name="acao" value="inserir">
        <label for="nome_banca">Nome da Banca:</label>
        <input type="text" name="nome_banca" id="nome_banca" required>
        <label for="link_banca">Link da Banca:</label>
        <input type="text" name="link_banca" id="link_banca" required>
        
        <button type="submit">Salvar Banca</button>
    </form>

    <hr>
    
    <!-- Formulário para consultar bancas -->
    <form action="banca.php" method="POST">
        <input type="hidden" name="acao" value="consultar">
        <button type="submit">Consultar Bancas</button>
    </form>
    
    <hr>
    
    <!-- Formulário para consultar e alterar bancas -->
    <form action="banca.php" method="POST">
        <label for="cod_banca">Escolha uma Banca para Alterar:</label>
        <select name="cod_banca" id="cod_banca" required>
            <option value="">Selecione uma banca</option>
            <?php
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_banca"] . "'>" . $row["nome"] . "</option>";
                }
            }
            ?>
        </select>

        <label for="nome_banca_alterar">Novo Nome da Banca:</label>
        <input type="text" name="nome_banca" id="nome_banca_alterar" required>
        <label for="link_banca_alterar">Novo Link da Banca:</label>
        <input type="text" name="link_banca" id="link_banca_alterar" required>

        <input type="hidden" name="acao" value="alterar">
        
        <button type="submit">Alterar Banca</button>
    </form>
    
    <hr>
    
    <!-- Formulário para excluir bancas -->
    <form action="banca.php" method="POST">
        <label for="cod_banca_excluir">Escolha uma Banca para Excluir:</label>
        <select name="cod_banca" id="cod_banca_excluir" required>
            <option value="">Selecione uma banca</option>
            <?php
            // Reabre a conexão para consultar novamente as bancas
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT cod_banca, nome FROM banca";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                // Reposiciona o ponteiro do resultado para o início
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["cod_banca"] . "'>" . $row["nome"] . "</option>";
                }
            }
            $conn->close();
            ?>
        </select>

        <input type="hidden" name="acao" value="excluir">
        
        <button type="submit">Excluir Banca</button>
    </form>
    
    <hr>
    
    <!-- Tabela para exibir as bancas existentes -->
    <?php if ($showTable): ?>
        <h3>Bancas Cadastradas</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome da Banca</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reabre a conexão para consultar as bancas
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT cod_banca, nome, link FROM banca";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["cod_banca"] . "</td>";
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "<td>" . $row["link"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhuma banca encontrada.</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
