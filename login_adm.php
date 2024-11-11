<?php
session_start();

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $dbname = "Topapirando";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Obter os dados do formulário
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $lembrar_dados = isset($_POST['lembrar_dados']); // Verificar se o checkbox foi marcado

    // Verificar se o e-mail existe na tabela de administradores
    $sql_administrador = "SELECT cod_administrador, nome, sobrenome, senha FROM administrador WHERE email = ?";
    $stmt_administrador = $conn->prepare($sql_administrador);
    $stmt_administrador->bind_param("s", $email);
    $stmt_administrador->execute();
    $stmt_administrador->store_result();

    if ($stmt_administrador->num_rows > 0) {
        $stmt_administrador->bind_result($cod_administrador, $nome, $sobrenome, $hashed_senha_administrador);
        $stmt_administrador->fetch();

        // Verificar a senha com hash
        if (password_verify($senha, $hashed_senha_administrador)) {
            // Definir sessão e redirecionar
            $_SESSION['loggedin'] = true;
            $_SESSION['cod_administrador'] = $cod_administrador;
            $_SESSION['nome'] = $nome . ' ' . $sobrenome; // Armazenar o nome completo
            $_SESSION['tipo_acesso'] = 3;

            // Lembrar dados
            if ($lembrar_dados) {
                setcookie('email_adm', $email, time() + (86400 * 30), "/"); // 30 dias
                setcookie('senha_adm', $senha, time() + (86400 * 30), "/"); // 30 dias
            } else {
                // Remover cookies se a opção não for marcada
                setcookie('email_adm', '', time() - 3600, "/");
                setcookie('senha_adm', '', time() - 3600, "/");
            }

            // Redirecionar para a página do administrador
            header("Location: ./administrador/adm.php");
            exit();
        } else {
            $error_message = 'Senha incorreta.';
        }
    } else {
        $error_message = 'E-mail não encontrado.';
    }

    // Fecha a declaração
    $stmt_administrador->close();
    // Fecha a conexão
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="login_adm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <header>
        <div class="interface">
        <div class="logo">
        <a href="index.php"><img class="logo" src="administrador/assets/logo_papirando_final.svg" alt="Logo"/></a>    
            </div><!--logo-->

            <nav class="menu-desktop">
                <ul>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="#" class="simulados">Simulados</a></li>
                    <li><a href="bancas.php">Bancas</a></li>
                    <li><a href="#" class="desempenho">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info">
                <a href="./estudante/sobre.php">Sobre</a>
                <a href="./estudante/ajuda.php">Ajuda</a>
                <a href=""><i class="fa-solid fa-gear" id="gear" title="Acesso restrito"></i></a>
            </div>
        </div> <!--interface-->
    </header>

    <main class="login-container">
    <div class="login-box">
        <h2>Login administrador</h2>
        
        <!-- Exibição de mensagem de erro no modal, se houver -->
        <?php if (!empty($error_message)): ?>
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <p class="error-text"><?php echo htmlspecialchars($error_message); ?></p>
                <button id="okBtnErro" class="ok-btn">OK</button>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Formulário de login -->
<form action="" method="POST">
    <div class="input-box">
        <input type="email" name="email" value="<?php echo isset($_COOKIE['email_adm']) ? $_COOKIE['email_adm'] : ''; ?>" required>
        <label>Email</label>
    </div>
    <div class="input-box">
        <input type="password" name="senha" value="<?php echo isset($_COOKIE['senha_adm']) ? $_COOKIE['senha_adm'] : ''; ?>" required>
        <label>Senha</label>
    </div>

    <!-- Botão de login -->
    <button type="submit" class="login-btn">Entrar</button>

    <!-- Lembrar meus dados -->
    <div class="lembrar-container">
        <input type="checkbox" id="checar" name="lembrar_dados" <?php echo isset($_COOKIE['email_adm']) ? 'checked' : ''; ?>>
        <label for="checar">Lembrar meus dados</label>
    </div>
</form>

</div>

    <div class="img_content">
        <img src="/administrador/assets/Login_Adm.svg" alt="Login Admin Image">
    </div>
    </main>

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

    <script>
   document.addEventListener('DOMContentLoaded', function() {
    var modalSimulados = document.getElementById("modal-simulados");
    var modalDesempenho = document.getElementById("modal-desempenho");

    var closeBtns = document.getElementsByClassName("close-btn");
    var okBtnSimulados = document.getElementById("ok-btn-simulados");
    var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

    // Show modal function
    function showModal(modal) {
        modal.style.display = "block";
    }

    // Hide modal function
    function closeModal() {
        modalSimulados.style.display = "none";
        modalDesempenho.style.display = "none";
    }

    // Add event listeners for menu links
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

    // Add click events for close buttons and OK buttons
    Array.from(closeBtns).forEach(function(btn) {
        btn.onclick = closeModal;
    });
    okBtnSimulados.onclick = closeModal;
    okBtnDesempenho.onclick = closeModal;

    // Close modal if clicking outside of it
    window.onclick = function(event) {
        if (event.target == modalSimulados || event.target == modalDesempenho) {
            closeModal();
        }
    };

    // Modal de erro
    const errorModal = document.getElementById('errorModal');
    const okBtnErro = document.getElementById('okBtnErro');
    const closeBtnErro = document.querySelector('.close-btn');

    if (errorModal) {
        errorModal.style.display = 'block';
        okBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });
        closeBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target == errorModal) {
                errorModal.style.display = 'none';
            }
        });
    }
});

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
