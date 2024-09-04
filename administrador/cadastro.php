<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadastro</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>

<div id="conteudo-header">
    <header class="header-prc">
        <a href="index.php"><img class="logo" src="assets/logo.svg" alt="topapirando"></a>
        <div class="search-bar">
            <input type="text" placeholder="Digite seu texto aqui">  
        </div>
        <div class="links">
            <a href="">Sobre</a>
            <a href="ajuda.php">Ajuda</a>
            <a href="login.php">Entrar</a>
        </div>
    </header>
    <div class="menu">
        <a href="index.php">Inicio</a>
        <a href="simulados.php">Simulados</a>
        <a href="bancas.php">Bancas</a>
        <a href="desempenhos.php">Desempenho</a>
    </div>
</div>

<main id="main-conteiner">
    <div id="corpo">
        <img id="img main" src="assets/login verde.svg" alt="">

        <h1>Criar minha conta!</h1>
        <h2>Informe seus dados abaixo para criar sua conta</h2>

        <!-- Formulário adicionado -->
        <form action="cadastro.php" method="post">
            <div id="input">
                <div class="grid-duplo">
                    <input class="esquerda" id="nome" name="nome" type="text" placeholder="Nome" required>
                    <input class="direita" id="sobrenome" name="sobrenome" type="text" placeholder="Sobrenome" required>
                </div>    

                <input id="e-mail" name="email" type="text" placeholder="E-mail" required>
                <input id="senha" name="senha" type="password" placeholder="Senha" required>
            </div>

            <button type="submit" id="button">Criar Conta</button>
        </form>
    </div>
</main>
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
                echo "<p>Conta criada com sucesso!</p>";
            } else {
                echo "<p>Erro: " . $stmt->error . "</p>";
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>
