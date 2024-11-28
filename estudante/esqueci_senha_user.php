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
    <div class="login-box">
        <h2>Recuperar Senha Estudante</h2>

        <!-- Modal de erro -->
        <?php if (!empty($error_message)): ?>
            <div id="errorModal" class="modal" style="display: block;">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <p class="modal-text"><?php echo htmlspecialchars($error_message); ?></p>
                    <button id="okBtnErro" class="ok-btn">OK</button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
    <div id="successModal" class="modal" style="display: flex; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;">
        <div class="modal-content" style="background-color: #fff; border-radius: 8px; padding: 20px; width: 80%; max-width: 500px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-align: center; position: relative;">
            <span class="close-btn" style="position: absolute; top: 10px; right: 10px; font-size: 20px; font-weight: bold; cursor: pointer; color: #000;">&times;</span>
            <p class="modal-text" style="font-size: 20px; color: #2118CD; font-weight: bold; margin-bottom: 20px;"><?php echo htmlspecialchars($success_message); ?></p>
            <button id="okBtnSuccess" class="ok-btn" style="background-color: #2118CD; color: white; border: none; border-radius: 5px; padding: 10px 20px; cursor: pointer; font-size: 16px; outline: none;">OK</button>
        </div>
    </div>
<?php endif; ?>



<style></style>


        <form action="" method="POST">
            <input type="hidden" name="step" value="validate">

            <!-- Campo de email -->
            <div class="input-box">
                <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Digite seu e-mail" required>
                <label>Email</label>
            </div>
            <!-- Campo de pergunta -->
            <div class="input-box">
                <input type="text" name="pergunta" placeholder="Pergunta de segurança" required>
                <label>Pergunta</label>
            </div>
            <!-- Campo de resposta -->
            <div class="input-box">
                <input type="text" name="resposta" placeholder="Digite a resposta" required>
                <label>Resposta</label>
            </div>

            <button type="submit" class="login-btn">Validar</button>
        </form>
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

<style>
    /* Estilo geral */
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f5f5f5;
    }

    .login-box {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    .input-box {
        position: relative;
        margin: 20px 0;
    }

    .input-box input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .input-box input[readonly] {
        background-color: #e9ecef;
        color: #000;
        cursor: not-allowed;
    }

    .input-box label {
        position: absolute;
        top: -18px;
        left: 10px;
        font-size: 14px;
        background: #fff;
        padding: 0 5px;
        color: #666;
    }

    .login-btn {
        background: #2118CD;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }

    .login-btn:hover {
        background: #1b14b0;
    }


</style>

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
