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

    // Verificar se o e-mail existe na tabela de administradores
    $sql_administrador = "SELECT cod_administrador, senha FROM administrador WHERE email = ?";
    $stmt_administrador = $conn->prepare($sql_administrador);
    $stmt_administrador->bind_param("s", $email);
    $stmt_administrador->execute();
    $stmt_administrador->store_result();

    if ($stmt_administrador->num_rows > 0) {
        $stmt_administrador->bind_result($cod_administrador, $hashed_senha_administrador);
        $stmt_administrador->fetch();

        // Verificar se a senha está criptografada
        if (password_needs_rehash($hashed_senha_administrador, PASSWORD_DEFAULT)) {
            // Se a senha não está criptografada, compará-la diretamente
            if ($senha == $hashed_senha_administrador) {
                // Se a senha for correta, criptografar e atualizar o banco de dados
                $new_hashed_senha = password_hash($senha, PASSWORD_DEFAULT);
                $update_sql = "UPDATE administrador SET senha = ? WHERE cod_administrador = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $new_hashed_senha, $cod_administrador);
                $update_stmt->execute();
                $update_stmt->close();

                // Definir sessão e redirecionar
                $_SESSION['loggedin'] = true;
                $_SESSION['cod_administrador'] = $cod_administrador;
                $_SESSION['email'] = $email;
                $_SESSION['tipo_acesso'] = 3;

                header("Location: ./administrador/adm.php");
                exit();
            } else {
                $error_message = 'Senha incorreta.';
            }
        } else {
            // Verificar a senha com hash
            if (password_verify($senha, $hashed_senha_administrador)) {
                // Definir sessão e redirecionar
                $_SESSION['loggedin'] = true;
                $_SESSION['cod_administrador'] = $cod_administrador;
                $_SESSION['email'] = $email;
                $_SESSION['tipo_acesso'] = 3;

                header("Location: ./administrador/adm.php");
                exit();
            } else {
                $error_message = 'Senha incorreta.';
            }
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
                <img src="administrador/assets/logo_papirando_final.svg" alt="Logo">     
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
                <a href="login_adm.php"><i class="fa-solid fa-gear" id="gear"></i></a>
            </div>
        </div> <!--interface-->
    </header>

    <main class="login-container">
        <div class="login-box">
            <h2>Login administrador</h2>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form action="" method="POST">
            <div class="input-box">
                <input type="email" name="email" required title="Preencha o seu email">
                <label>Email</label>
            </div>
            <div class="input-box">
                <input type="password" name="senha" required title="Preencha a sua senha">
                <label>Senha</label>
            </div>
            </form>

                <button type="submit" class="login-btn">Entrar</button>
                <div class="forgot-password">
                    <a href="#">Esqueceu sua senha?</a>
                </div>
            </form>
        </div>

        <div class="img_content">
            <img src="/administrador/assets/Login_Adm.svg" alt="">
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
});

    </script>
</body>
</html>