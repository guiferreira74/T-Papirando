<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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

<!-- Modal de Sucesso -->
<div id="modal-sucesso" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <img src="assets/ticken.svg" alt="Sucesso" class="tick-image">
        <p>Conta criada com sucesso!</p>
        <button class="ok-button" id="ok-sucesso">OK</button>
    </div>
</div>

<!-- Modal de Erro -->
<div id="modal-erro" class="modal">
    <div class="modal-content">
        <span class="close-erro">&times;</span>
        <img src="assets/erro.svg" alt="Erro" class="error-image">
        <p>Erro: O e-mail já está registrado.</p>
        <button class="ok-button" id="ok-erro">OK</button>
    </div>
</div>


<?php
$success = false;
$error = false;

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
            $error = true;
        } else {
            // Inserir os dados no banco de dados
            $sql = "INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nome, $sobrenome, $email, $senha);

            if ($stmt->execute()) {
                $success = true;
            } else {
                echo "<p>Erro: " . $stmt->error . "</p>";
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var modalSucesso = document.getElementById("modal-sucesso");
    var modalErro = document.getElementById("modal-erro");
    var closeSucesso = document.getElementsByClassName("close")[0];
    var closeErro = document.getElementsByClassName("close-erro")[0];
    var okSucesso = document.getElementById("ok-sucesso");
    var okErro = document.getElementById("ok-erro");

    // Mostrar o modal adequado com base na resposta do PHP
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <?php if ($success): ?>
            modalSucesso.style.display = "block";
        <?php elseif ($error): ?>
            modalErro.style.display = "block";
        <?php endif; ?>
    <?php endif; ?>

    // Fechar o modal de sucesso quando o usuário clicar no botão de fechar
    closeSucesso.onclick = function() {
        modalSucesso.style.display = "none";
    }

    // Fechar o modal de erro quando o usuário clicar no botão de fechar
    closeErro.onclick = function() {
        modalErro.style.display = "none";
    }

    // Fechar o modal de sucesso quando o usuário clicar no botão OK
    okSucesso.onclick = function() {
        modalSucesso.style.display = "none";
    }

    // Fechar o modal de erro quando o usuário clicar no botão OK
    okErro.onclick = function() {
        modalErro.style.display = "none";
    }

    // Fechar o modal quando o usuário clicar fora da área do modal
    window.onclick = function(event) {
        if (event.target == modalSucesso) {
            modalSucesso.style.display = "none";
        }
        if (event.target == modalErro) {
            modalErro.style.display = "none";
        }
    }
});
</script>


</body>
</html>
