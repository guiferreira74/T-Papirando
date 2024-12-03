<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="cadastro.css">
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
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
                <li><a href="bancas.php" class="bancas">Bancas</a></li>
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

        <form id="form-cadastro" action="cadastro.php" method="post">
    <div id="input">
        <div class="grid-duplo">
            <input class="esquerda" id="nome" name="nome" type="text" placeholder="Nome" required title="Preencha o seu Nome">
            <input class="direita" id="sobrenome" name="sobrenome" type="text" placeholder="Sobrenome" required title="Preencha seu Sobrenome">
        </div>    

        <input id="e-mail" name="email" type="email" placeholder="E-mail" required title="Preencha seu email">
        <input id="pergunta" name="pergunta" type="text" placeholder="Pergunta de segurança" required title="Preencha a pergunta de segurança">
        <input id="resposta" name="resposta" type="text" placeholder="Resposta de segurança" required title="Preencha a resposta de segurança">
        <input id="telefone" name="telefone" type="text" placeholder="Telefone" required title="Preencha o seu Telefone">
        <input id="senha" name="senha" type="password" placeholder="Senha" required title="Preencha a sua Senha">
        <input id="confirmar-senha" name="confirmar-senha" type="password" placeholder="Confirmar Senha" required title="Confirme a sua Senha">
      
    </div>

    <button type="submit" id="button">Criar Conta</button>
</form>


        <a href="login.php" class="login">Entrar com sua conta</a>
    </div>
</main>


<!-- Modal de erro para senhas -->
<div id="modal-senha-erro" class="modal senha-erro" style="display:none;">
    <div class="modal-content">
        <span class="close-senha-erro">&times;</span>
        <img src="./administrador/assets/erro.svg" alt="Erro" class="error-image">
        <p>Erro: As senhas devem ser iguais</p>
        <button class="ok-button" id="ok-senha-erro">OK</button>
    </div>
</div>

<!-- Modal de erro para email já registrado -->
<div id="modal-email-erro" class="modal modal-erro" style="display:none;">
    <div class="modal-content">
        <span class="close-erro">&times;</span>
        <img src="./administrador/assets/erro.svg" alt="Erro" class="error-image">
        <p>Erro: O email já está registrado.</p>
        <button class="ok-button" id="ok-email-erro">OK</button>
    </div>
</div>

<!-- Modal de erro para domínio @admin -->
<div id="modal-admin-erro" class="modal modal-admin-erro" style="display:none;">
    <div class="modal-content">
        <span class="close-admin-erro">&times;</span> <!-- O X aqui -->
        <img src="./administrador/assets/erro.svg" alt="Erro" class="error-image">
        <p>Erro: O domínio @admin não é permitido.</p>
        <button class="ok-button" id="ok-admin-erro">OK</button>
    </div>
</div>


<!-- Modal de Sucesso -->
<div id="modal-sucesso" class="modal modal-sucesso" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <img src="./administrador/assets/ticken.svg" alt="Sucesso" class="tick-image">
        <p>Conta criada com sucesso!</p>
        <button class="ok-button" id="ok-sucesso">OK</button>
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
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $dbname = "topapirando";

    // Conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Sanitizar e capturar os dados do formulário
    $nome = $conn->real_escape_string($_POST['nome']);
    $sobrenome = $conn->real_escape_string($_POST['sobrenome']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $senha = $_POST['senha']; // Senha em texto claro para validação
    $confirmar_senha = $_POST['confirmar-senha']; // Senha de confirmação
    $pergunta = isset($_POST['pergunta']) ? $conn->real_escape_string($_POST['pergunta']) : '';
    $resposta = isset($_POST['resposta']) ? $conn->real_escape_string($_POST['resposta']) : '';
    

    // Verificar se o e-mail é válido
    if ($email === false) {
        echo "<script>document.getElementById('modal-email-erro').style.display = 'block';</script>";
    } else {
        // Verificar se o e-mail já está cadastrado
        $checkEmailSql = "SELECT email FROM estudante WHERE email = ?";
        $stmt = $conn->prepare($checkEmailSql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Se o e-mail já estiver registrado, exibe a mensagem de erro
            echo "<script>document.getElementById('modal-email-erro').style.display = 'block';</script>";
        } elseif ($senha !== $confirmar_senha) {
            // Se as senhas não coincidirem, exibe a mensagem de erro
            echo "<script>document.getElementById('modal-senha-erro').style.display = 'block';</script>";
        } else {
            // Hash da senha para segurança
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

            // Inserir o novo estudante na tabela estudante
            $sql = "INSERT INTO estudante (nome, sobrenome, email, telefone, senha, pergunta, resposta) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $nome, $sobrenome, $email, $telefone, $senha_hash, $pergunta, $resposta);

            if ($stmt->execute()) {
                // Se a inserção for bem-sucedida, exibe o modal de sucesso
                echo "<script>document.getElementById('modal-sucesso').style.display = 'block';</script>";
                $success = true;
            } else {
                // Caso ocorra algum erro ao inserir no banco
                echo "<script>alert('Erro: " . $stmt->error . "');</script>";
            }
        }

        $stmt->close();
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
}
?>


<script>
    document.getElementById('form-cadastro').onsubmit = function(event) {
    const telefone = document.getElementById('telefone').value;
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmar-senha').value;

    // Validação do telefone (exemplo: verificar se não está vazio)
    if (telefone.trim() === "") {
        event.preventDefault();
        alert("Preencha o campo telefone.");
    }

    if (senha !== confirmarSenha) {
        event.preventDefault(); // Impede o envio do formulário
        document.getElementById('modal-senha-erro').style.display = 'block'; // Mostra o modal de erro
    }
};

</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Função para validar senhas
    document.getElementById('form-cadastro').onsubmit = function(event) {
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmar-senha').value;

        if (senha !== confirmarSenha) {
            event.preventDefault(); // Impede o envio do formulário
            document.getElementById('modal-senha-erro').style.display = 'block'; // Mostra o modal de erro
        }
    };

    // Fechar os modais de erro
    document.getElementById('ok-senha-erro').onclick = function() {
        document.getElementById('modal-senha-erro').style.display = 'none';
    };
    document.querySelector('.close-senha-erro').onclick = function() {
        document.getElementById('modal-senha-erro').style.display = 'none';
    };

    document.getElementById('ok-email-erro').onclick = function() {
        document.getElementById('modal-email-erro').style.display = 'none';
    };
    document.querySelector('.close-erro').onclick = function() {
        document.getElementById('modal-email-erro').style.display = 'none';
    };

    // Mostrar o modal de sucesso ou erro com base na resposta do PHP
    var modalSucesso = document.getElementById("modal-sucesso");
    var modalEmailErro = document.getElementById("modal-email-erro");
    var closeSucesso = document.getElementsByClassName("close")[0];

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <?php if ($success): ?>
            modalSucesso.style.display = "block";
        <?php elseif ($error): ?>
            modalEmailErro.style.display = "block";
        <?php endif; ?>
    <?php endif; ?>

    // Fechar os modais
    closeSucesso.onclick = function() {
        modalSucesso.style.display = "none";
    };

    // Fechar o modal de sucesso ao clicar no botão "OK"
    document.getElementById('ok-sucesso').onclick = function() {
        modalSucesso.style.display = "none";
    };

    // Fechar os modais quando o usuário clicar fora da área do modal
    window.onclick = function(event) {
        if (event.target == modalSucesso) {
            modalSucesso.style.display = "none";
        }
        if (event.target == modalEmailErro) {
            modalEmailErro.style.display = "none";
        }
        if (event.target == document.getElementById('modal-senha-erro')) {
            document.getElementById('modal-senha-erro').style.display = 'none';
        }
    };

    // Funções para os modais de Simulados e Desempenho
    var modalSimulados = document.getElementById("modal-simulados");
    var modalDesempenho = document.getElementById("modal-desempenho");

    document.getElementById("link-simulados").onclick = function(event) {
        event.preventDefault();
        modalSimulados.style.display = "block";
    };

    document.getElementById("link-desempenho").onclick = function(event) {
        event.preventDefault();
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