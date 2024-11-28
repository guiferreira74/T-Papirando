<?php
session_start();

$error_message = '';
$success_message = '';

// Verificar se o formulário foi enviado
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

    // Obter os dados do formulário
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $lembrar_dados = isset($_POST['lembrar_dados']);

    // Tipo de acesso: Estudante
    $tipo_acesso = 2;

   // Verificar se o e-mail existe na tabela de estudantes
$sql_estudante = "SELECT cod_estudante, nome, sobrenome, senha FROM estudante WHERE email = ?";
$stmt_estudante = $conn->prepare($sql_estudante);
$stmt_estudante->bind_param("s", $email);
$stmt_estudante->execute();
$stmt_estudante->store_result();

if ($stmt_estudante->num_rows > 0) {
    $stmt_estudante->bind_result($cod_estudante, $nome_estudante, $sobrenome_estudante, $hashed_senha_estudante);
    $stmt_estudante->fetch();

    // Verificar a senha
    if (password_verify($senha, $hashed_senha_estudante)) {
        // Definir sessão e redirecionar
        $_SESSION['email'] = $email;
        $_SESSION['nome'] = $nome_estudante;
        $_SESSION['sobrenome'] = $sobrenome_estudante;
        $_SESSION['cod_estudante'] = $cod_estudante;  // Adiciona o cod_estudante à sessão
        $_SESSION['tipo_acesso'] = $tipo_acesso; // Estudante

        // Lembrar dados
        if ($lembrar_dados) {
            setcookie('email', $email, time() + (86400 * 30), "/"); // 30 dias
            setcookie('senha', $senha, time() + (86400 * 30), "/"); // 30 dias
        } else {
            // Se não lembrar, eliminar cookies
            setcookie('email', '', time() - 3600, "/");
            setcookie('senha', '', time() - 3600, "/");
        }

        // Redirecionar para a página do estudante
        header("Location: ./estudante/user.php");
        exit();
    } else {
        $error_message = 'Senha incorreta.';
    }
} else {
    $error_message = 'E-mail não cadastrado, crie sua conta ao lado por favor.';
}

    $stmt_estudante->close();

    // Fecha a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Estudante</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="interface">
            <div class="logo">
                <a href="index.php"><img class="logo" src="./administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
            </div>

            <nav class="menu-desktop">
                <ul>
                    <li><a href="index.php" class="inicio">Início</a></li>
                    <li><a href="simulados.php" class="simulados">Simulados</a></li>
                    <li><a href="bancas.php" class="bancas">Bancas</a></li>
                    <li><a href="desempenhos.php" class="desempenho">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info"> 
                <a href="./estudante/sobre.php">Sobre</a>
                <a href="./estudante/ajuda.php">Ajuda</a>
                <a href="#"><i class="fa-solid fa-gear" id="gear"></i></a>
                </div>
            </div>
        </div>
    </header>

    <main>
    <div class="grid-duplo">
    <!-- Parte Esquerda -->
    <div class="esquerda">
        <img id="login" src="./administrador/assets/login azul.svg" alt="Login">
        <h1>Já tem conta?</h1>
        <p>Informe seus dados para acessá-la</p>

        <form action="login.php" method="post">
    <input 
        id="input-email" 
        name="email" 
        type="text" 
        placeholder="E-mail" 
        value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>" 
        required
    >
    <input 
        id="input-senha" 
        name="senha" 
        type="password" 
        placeholder="Senha" 
        value="<?php echo isset($_COOKIE['senha']) ? htmlspecialchars($_COOKIE['senha']) : ''; ?>" 
        required>
    <div class="options-row">
        <div class="lembrar-dados">
            <input type="checkbox" id="checar" name="lembrar_dados" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
            <label for="checar">Lembrar meus dados</label>
        </div>
        <a href="./estudante/esqueci_senha_user.php?email=<?php echo urlencode(isset($_POST['email']) ? $_POST['email'] : ''); ?>" class="esqueci-senha">Esqueci minha senha</a>

    </div>

    <!-- Botão de Login -->
    <div class="button-container">
        <button id="button-esquerda" type="submit">Acessar Conta</button>
    </div>
</form>

    </div>

    <!-- Parte Direita -->
    <div class="direita">
        <img id="Log" src="./administrador/assets/login verde.svg" alt="Novo Usuário">
        <h1>Novo usuário</h1>
        <p>Criar uma conta é fácil! Informe seus dados e uma senha para <br> aproveitar todos os benefícios de ter uma conta.</p>
        <a href="cadastro.php"><button id="button-direita">Crie sua Conta</button></a>
    </div>
</div>

     <!-- Modal Simulados -->
     <div id="modal-simulados" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o simulado.</p>
            <button id="ok-btn-simulados" class="btn-custom">OK</button>
        </div>
    </div>

    <!-- Modal Desempenho -->
    <div id="modal-desempenho" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o seu desempenho.</p>
            <button id="ok-btn-desempenho" class="btn-custom">OK</button>
        </div>
    </div>

    <script>
        // Obter elementos dos modais e botões
        var modalSimulados = document.getElementById("modal-simulados");
        var modalDesempenho = document.getElementById("modal-desempenho");

        var closeBtns = document.getElementsByClassName("close-btn");
        var okBtnSimulados = document.getElementById("ok-btn-simulados");
        var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

        // Função para mostrar um modal específico
        function showModal(modal) {
            modal.style.display = "block";
        }

        // Função para esconder o modal
        function closeModal() {
            modalSimulados.style.display = "none";
            modalDesempenho.style.display = "none";
        }

        // Adicionar eventos de clique para os links Simulados e Desempenho
        document.querySelectorAll('.menu-desktop a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (this.classList.contains("simulados")) {
                    e.preventDefault(); 
                    showModal(modalSimulados);
                } else if (this.classList.contains("desempenho")) {
                    e.preventDefault(); 
                    showModal(modalDesempenho);
                }
            });
        });

        // Adicionar eventos de clique para os botões de fechar e os botões OK
        Array.from(closeBtns).forEach(function(btn) {
            btn.onclick = closeModal;
        });
        okBtnSimulados.onclick = closeModal;
        okBtnDesempenho.onclick = closeModal;

        // Fechar o modal se o usuário clicar fora dele
        window.onclick = function(event) {
            if (event.target == modalSimulados || event.target == modalDesempenho) {
                closeModal();
            }
        }
    </script>


    <!-- Modal Erro -->
    <div id="errorModal" class="modal modal-custom" style="<?php echo !empty($error_message) ? 'display:block;' : 'display:none;'; ?>">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <button onclick="closeModal()" class="btn-custom">OK</button>
        </div>
    </div>

    <script>
        // Função para esconder todos os modais
        function closeModal() {
            document.getElementById("errorModal").style.display = "none";
        }

        // Fechar o modal se o usuário clicar fora dele
        window.onclick = function(event) {
            if (event.target == document.getElementById("errorModal")) {
                closeModal();
            }
        }

        // Mostrar modal de erro com mensagens se houver
        <?php if (!empty($error_message)): ?>
            document.getElementById('errorModal').style.display = 'block';
        <?php endif; ?>
    </script>

<!-- Modal Acesso Restrito -->
<div id="modal-acesso" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span> <!-- Certifique-se que a classe é "close-btn" -->
        <p class="modal-text">Acesso restrito, deseja continuar?</p>
        <div class="modal-buttons">
            <button id="ok-btn-acesso" class="btn-ok">OK</button>
            <button id="cancel-btn-acesso" class="btn-cancel">Cancelar</button>
        </div>
    </div>
</div>


<script>
    // Obter o modal e os botões
    var modalAcesso = document.getElementById("modal-acesso");
    var btnGear = document.getElementById("gear");
    var okBtnAcesso = document.getElementById("ok-btn-acesso");
    var cancelBtnAcesso = document.getElementById("cancel-btn-acesso");
    var closeBtn = document.querySelector("#modal-acesso .close-btn"); // Certifique-se de selecionar o botão X corretamente

    // Quando o ícone da engrenagem for clicado, exibir o modal
    btnGear.addEventListener("click", function(event) {
        event.preventDefault(); // Prevenir a navegação imediata
        modalAcesso.style.display = "block";
    });

    // Se o usuário clicar em "OK", fechar o modal e continuar com o redirecionamento
    okBtnAcesso.addEventListener("click", function() {
        modalAcesso.style.display = "none";
        window.location.href = "/login_adm.php"; // Redirecionar para a página de login
    });

    // Se o usuário clicar em "Cancelar", apenas fechar o modal
    cancelBtnAcesso.addEventListener("click", function() {
        modalAcesso.style.display = "none";
    });

    // Fechar o modal se o usuário clicar no X
    closeBtn.addEventListener("click", function() {
        modalAcesso.style.display = "none";
    });

    // Fechar o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        if (event.target == modalAcesso) {
            modalAcesso.style.display = "none";
        }
    };
</script>

</body>
</html>