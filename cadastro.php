<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>

<header>
    <div class="interface">
        <div class="logo">
            <a href="index.php"><img class="logo" src="./administrador/assets/logo_papirando_final.svg" alt="Logo"/></a>   
        </div><!--logo-->

        <nav class="menu-desktop">
            <ul>
                <li><a href="index.php" class="simulados">Início</a></li>
                <li><a href="simulados.php" class="simulados" id="link-simulados">Simulados</a></li>
                <li><a href="bancas.php" class="bancas">Bancas</a></li> <!-- Link de Bancas sem modal -->
                <li><a href="desempenhos.php" class="desempenho" id="link-desempenho">Desempenho</a></li>
            </ul>
        </nav>

        <div class="info"> 
            <a href="./estudante/sobre.php">Sobre</a>
            <a href="./estudante/ajuda.php">Ajuda</a>
            <a href="login.php">Entrar</a>
        </div>
    </div> <!--interface-->
</header>

<main id="main-conteiner">
    <div id="corpo">
        <img id="img-main" src="./administrador/assets/login verde.svg" alt="">

        <h1>Criar minha conta!</h1>
        <h2>Informe seus dados abaixo para criar sua conta</h2>

      <!-- Formulário adicionado -->
<form action="cadastro.php" method="post" onsubmit="return validarSenhas()">
    <div id="input">
        <div class="grid-duplo">
            <input class="esquerda" id="nome" name="nome" type="text" placeholder="Nome" required title="Preencha o seu Nome">
            <input class="direita" id="sobrenome" name="sobrenome" type="text" placeholder="Sobrenome" required title="Preencha seu Sobrenome">
        </div>    

        <input id="e-mail" name="email" type="email" placeholder="E-mail" required title="Preencha seu email">
        <input id="senha" name="senha" type="password" placeholder="Senha" required title="Preencha a sua Senha">
        <input id="confirmar-senha" name="confirmar-senha" type="password" placeholder="Confirmar Senha" required title="Confirme a sua Senha">
    </div>

    <button type="submit" id="button">Criar Conta</button>
</form>
</div>
</main>

<!-- Modal de erro -->
<div id="modal-erro" class="modal modal-erro" style="display:none;">
    <div class="modal-content">
        <span class="close-erro">&times;</span>
        <img src="./administrador/assets/erro.svg" alt="Erro" class="error-image">
        <p>Erro: As senhas não coincidem.</p>
        <button class="ok-button" id="ok-erro">OK</button>
    </div>
</div>

<script>
    function validarSenhas() {
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmar-senha').value;

        if (senha !== confirmarSenha) {
            document.getElementById('modal-erro').style.display = 'block'; // Mostra o modal de erro
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio do formulário
    }

    // Fechar o modal ao clicar no botão "OK" ou no "X"
    document.getElementById('ok-erro').onclick = function() {
        document.getElementById('modal-erro').style.display = 'none';
    };
    document.querySelector('.close-erro').onclick = function() {
        document.getElementById('modal-erro').style.display = 'none';
    };
</script>

<!-- Modal de Sucesso -->
<div id="modal-sucesso" class="modal modal-sucesso" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <img src="./administrador/assets/ticken.svg" alt="Sucesso" class="tick-image">
        <p>Conta criada com sucesso!</p>
        <button class="ok-button" id="ok-sucesso">OK</button>
    </div>
</div>

<!-- Modal de Erro -->
<div id="modal-erro" class="modal modal-erro" style="display:none;">
    <div class="modal-content">
        <span class="close-erro">&times;</span>
        <img src="./administrador/assets/erro.svg" alt="Erro" class="error-image">
        <p>Erro: O email já está registrado.</p>
        <button class="ok-button" id="ok-erro">OK</button>
    </div>
</div>

<!-- Modal Simulados -->
<div id="modal-simulados" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Por favor, faça o login para ver o simulado.</p>
        <button id="ok-btn-simulados" class="ok-btn">OK</button>
    </div>
</div>

<!-- Modal Desempenho -->
<div id="modal-desempenho" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Por favor, faça o login para ver o seu desempenho.</p>
        <button id="ok-btn-desempenho" class="ok-btn">OK</button>
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
        echo "<script>document.getElementById('modal-erro').style.display = 'block';</script>";
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
                echo "<script>alert('Erro: " . $stmt->error . "');</script>";
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

    // Fechar os modais
    closeSucesso.onclick = function() {
        modalSucesso.style.display = "none";
    }

    closeErro.onclick = function() {
        modalErro.style.display = "none";
    }

    okSucesso.onclick = function() {
        modalSucesso.style.display = "none";
    }

    okErro.onclick = function() {
        modalErro.style.display = "none";
    }

    // Fechar os modais quando o usuário clicar fora da área do modal
    window.onclick = function(event) {
        if (event.target == modalSucesso) {
            modalSucesso.style.display = "none";
        }
        if (event.target == modalErro) {
            modalErro.style.display = "none";
        }
    }

    // Funções para os modais de Simulados e Desempenho
    var modalSimulados = document.getElementById("modal-simulados");
    var modalDesempenho = document.getElementById("modal-desempenho");

    // Mostrar modais ao clicar nos links
    document.getElementById("link-simulados").onclick = function(event) {
        event.preventDefault(); // Evitar o comportamento padrão do link
        modalSimulados.style.display = "block";
    };

    document.getElementById("link-desempenho").onclick = function(event) {
        event.preventDefault(); // Evitar o comportamento padrão do link
        modalDesempenho.style.display = "block";
    };
    
    // Fechar os modais de Simulados e Desempenho
    var closeBtns = document.getElementsByClassName("close-btn");
    var okBtnSimulados = document.getElementById("ok-btn-simulados");
    var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

    Array.from(closeBtns).forEach(function(btn) {
        btn.onclick = function() {
            modalSimulados.style.display = "none";
            modalDesempenho.style.display = "none";
        };
    });

    okBtnSimulados.onclick = function() {
        modalSimulados.style.display = "none";
    };

    okBtnDesempenho.onclick = function() {
        modalDesempenho.style.display = "none";
    };
});
</script>

</body>
</html>
