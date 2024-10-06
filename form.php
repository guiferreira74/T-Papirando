<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
    <h2>Cadastro de usuário</h2>
    <form action="form.php" method="post">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="sobrenome">Sobrenome:</label><br>
        <input type="text" id="sobrenome" name="sobrenome" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>

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
        $sobrenome = $conn->real_escape_string($_POST['sobrenome']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

        if ($email === false) {
            echo "<p>Por favor, insira um e-mail válido.</p>";
        } else {
            // Verificar se o e-mail já está cadastrado
            $checkEmailSql = "SELECT email FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($checkEmailSql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "<p>Erro: O e-mail já está registrado.</p>";
            } else {
                // Inserir os dados no banco de dados
                $sql = "INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $nome, $sobrenome, $email, $senha);

                if ($stmt->execute()) {
                    echo "<p>Usuário cadastrado com sucesso!</p>";
                } else {
                    echo "<p>Erro: " . $stmt->error . "</p>";
                }
            }

            $stmt->close();
        }

        $conn->close();
    }
    ?>
</body>
</html>

