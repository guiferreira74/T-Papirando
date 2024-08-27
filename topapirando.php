<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>topapirando</title>
    <link rel="stylesheet" href="topapi.css">
</head>
<body>
    <header class="header-prc">
        <a href="topapirando.php">
            <img class="logo" src="logo.svg" alt="topapirando">
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
        <a href="banca.html">Bancas</a> 
        <a href="">Desempenho</a>
    </div>

    <main id="main-conteiner">
        <div id="corpo">
            <img id="img main" src="perfil.svg" alt="">
            <h1>Criar minha conta!</h1>
            <h2>Informe seus dados abaixo para criar sua conta</h2>

            <form action="" method="POST">
                <div id="input">
                    <div id="nome-sobrenome">
                        <input id="nome" name="nome" type="text" placeholder="Nome" required>
                        <input id="sobrenome" name="sobrenome" type="text" placeholder="Sobrenome" required>
                    </div>

                    <div id="email-senha">
                        <input id="email" name="email" type="email" placeholder="E-mail" required>
                        <input id="senha" name="senha" type="password" placeholder="Senha" required>
                    </div>
                </div>

                <button id="button" type="submit">Criar Conta</button>
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
