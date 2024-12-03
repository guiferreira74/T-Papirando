<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', 'admin', 'Topapirando');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$error_message = '';
$success_message = '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; // Obter email da URL
$pergunta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step']) && $_POST['step'] === 'validate') {
        // Primeira etapa: validar pergunta e resposta
        $email = $conn->real_escape_string($_POST['email']);
        $pergunta = $conn->real_escape_string($_POST['pergunta']);
        $resposta = $conn->real_escape_string($_POST['resposta']);

        // Verificar se o email existe
        $sql = "SELECT pergunta, resposta FROM estudante WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_pergunta, $db_resposta);
            $stmt->fetch();

            if (trim(mb_strtolower($pergunta)) === mb_strtolower($db_pergunta)) {
                if (trim(mb_strtolower($resposta)) === mb_strtolower($db_resposta)) {
                    $_SESSION['reset_email'] = $email;
                    $success_message = "Validação concluída com sucesso!";
                    // Remove o setTimeout e só exibe o modal
                    echo '<script>document.addEventListener("DOMContentLoaded", function() { document.getElementById("successModal").style.display = "block"; });</script>';
                } else {
                    $error_message = "Resposta incorreta. Tente novamente.";
                }
            } else {
                $error_message = "Pergunta incorreta. Tente novamente.";
            }
            
            
        }
        $stmt->close();
    }
}

// Obter a pergunta de segurança, se o email estiver preenchido
if (!empty($email)) {
    $sql = "SELECT pergunta FROM estudante WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($pergunta);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha Estudante</title>
    <link rel="stylesheet" href="esqueci_senha_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="../index.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a>
        </div>
        <nav class="menu-desktop">
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="#" class="simulados">Simulados</a></li>
                <li><a href="../bancas.php">Bancas</a></li>
                <li><a href="#" class="desempenho">Desempenho</a></li>
            </ul>
        </nav>
        <div class="info">
            <a href="../estudante/sobre.php">Sobre</a>
            <a href="../estudante/ajuda.php">Ajuda</a>
            <a href=""><i class="fa-solid fa-gear" id="gear" title="Acesso restrito"></i></a>
        </div>
    </div>
</header>
<main class="login-container">
    <div class="login-wrapper">
        <!-- Container da imagem -->
        <div class="image-container">
            <img src="../administrador/assets/autenticar.svg" alt="Imagem de Recuperação de Senha" class="form-image">
        </div>

        <!-- Container do formulário -->
        <div class="login-box">
            <h2>Recuperar Senha Estudante</h2>

            <!-- Modal de erro -->
            <?php if (!empty($error_message)): ?>
                <div id="errorModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <p class="modal-text"><?php echo htmlspecialchars($error_message); ?></p>
                        <button id="okBtnErro" class="ok-btn">OK</button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" style="color: #2118CD;">&times;</span>
            <p class="modal-text" style="color: #2118CD;"><?php echo htmlspecialchars($success_message); ?></p>
            <button id="okBtnSuccess" class="ok-btn" style="background-color: #2118CD; color: white;">OK</button>
        </div>
    </div>
<?php endif; ?>

            <form action="" method="POST">
                <input type="hidden" name="step" value="validate">

                <!-- Campo de email -->
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Digite seu e-mail" required>
                </div>

                <!-- Campo de pergunta -->
                <div class="input-group">
                    <label>Pergunta de Segurança</label>
                    <input type="text" name="pergunta" placeholder="Digite sua pergunta de segurança" required>
                </div>

                <!-- Campo de resposta -->
                <div class="input-group">
                    <label>Resposta</label>
                    <input type="text" name="resposta" placeholder="Digite sua resposta" required>
                </div>

                <button type="submit" class="login-btn">Validar</button>
            </form>
        </div>
    </div>
</main>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const successModal = document.getElementById('successModal');
    const okBtnSuccess = document.getElementById('okBtnSuccess');

    // Redirecionar ao clicar no botão "OK" do modal de sucesso
    if (successModal) {
        okBtnSuccess.addEventListener('click', function () {
            window.location.href = 'redefinir_senha_user.php'; // Redireciona para a redefinição
        });
    }
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const errorModal = document.getElementById('errorModal');
        const successModal = document.getElementById('successModal');
        const closeBtns = document.querySelectorAll('.close-btn');

        closeBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (errorModal) errorModal.style.display = 'none';
                if (successModal) successModal.style.display = 'none';
            });
        });

        window.addEventListener('click', function (event) {
            if (event.target === errorModal) errorModal.style.display = 'none';
            if (event.target === successModal) successModal.style.display = 'none';
        });
    });
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const errorModal = document.getElementById('errorModal');
    const okBtnErro = document.getElementById('okBtnErro');
    const closeBtnErro = document.querySelector('.close-btn');

    if (errorModal) {
        okBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });

        closeBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === errorModal) {
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
            if (event.target === errorModal) {
                errorModal.style.display = 'none';
            }
        });
    }
});

</script>

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
