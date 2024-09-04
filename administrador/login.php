<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
           <a href="index.php"><img class="logo" src="assets/logo.svg" alt="topapirando"></a>
            <div class="search-bar">
                <input type="text" placeholder="Digite seu texto aqui">  
            </div>
            <div class="links">
                <a href="#">Sobre</a>
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

    <main>
        <div class="grid-duplo">
            <div class="esquerda">
                <img id="login" src="assets/login azul.svg" alt="">
                <h1>Já tem conta?</h1>
                <p>Informe seus dados para acessá-la</p>
                
                <form action="login.php" method="post">
                    <input id="input-email" name="email" type="text" placeholder="E-mail" required>
                    <input id="input-senha" name="senha" type="password" placeholder="Senha" required>

                    <div id="checa">  
                        <input type="checkbox" id="checar" name="lembrar_dados">
                        <label for="checar">Lembrar meus dados</label>
                    </div>
                 
                    <div class="button-container">
                    <button id="button-esquerda" type="submit">Acessar Conta</button>
                    </div>

                </form>
            </div>

            <div class="direita">
                <img id="Log" src="assets/login verde.svg" alt="">
                <h1>Novo usuário</h1>
                <p>Criar uma conta é fácil! Informe seus dados e uma senha para <br> aproveitar todos os benefícios de ter uma conta.</p>
                <a href="cadastro.php"><button id="button-direita">Crie sua Conta</button></a>
            </div>
        </div>
    </main>
</body>
</html>

<?php
session_start();

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
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    // Verificar se o e-mail existe
    $sql = "SELECT senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_senha);
        $stmt->fetch();

        // Verificar a senha
        if (password_verify($senha, $hashed_senha)) {
            // Definir sessão e redirecionar com base no domínio do e-mail
            $_SESSION['email'] = $email;

            if (strpos($email, '@admin') !== false) {
                // E-mail contém o domínio @admin, tratar como administrador
                $_SESSION['tipo_acesso'] = 1;
                header("Location: adm.php");
                exit();
            } else {
                // E-mail não contém o domínio @admin, tratar como usuário normal
                $_SESSION['tipo_acesso'] = 2;
                header("Location: user.php");
                exit();
            }
        } else {
            echo "<p>Erro: Senha incorreta.</p>";
        }
    } else {
        echo "<p>Erro: E-mail não cadastrado,crie sua conta ao lado por favor.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>