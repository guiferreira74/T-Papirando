<?php
session_start();
$conn = new mysqli('localhost', 'root', 'admin', 'Topapirando');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_SESSION['reset_email'])) {
        $email = $_SESSION['reset_email'];
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        // Verificar se as senhas coincidem
        if ($nova_senha !== $confirmar_senha) {
            $error_message = "As senhas não coincidem. Tente novamente.";
        } else {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            $sql = "UPDATE administrador SET senha = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $nova_senha_hash, $email);
            if ($stmt->execute()) {
                $success_message = "Senha redefinida com sucesso!";
                unset($_SESSION['reset_email']);
            } else {
                $error_message = "Erro ao redefinir a senha. Tente novamente.";
            }
            $stmt->close();
        }
    } else {
        $error_message = "Sessão expirada. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Administrador</title>
    <link rel="stylesheet" href="redefinir_senha.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: "Poppins", sans-serif;
            background: #E6E6E6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }

        .login-box h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }

        .input-box {
            position: relative;
            margin-bottom: 30px;
        }

        .input-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #333;
            border: none;
            border-bottom: 1px solid #aaa;
            outline: none;
            background: transparent;
        }

        .input-box label {
            position: absolute;
            top: 0;
            left: 0;
            padding: 10px 0;
            font-size: 16px;
            color: #aaa;
            pointer-events: none;
            transition: 0.5s;
        }

        .input-box input:focus ~ label,
        .input-box input:valid ~ label {
            top: -20px;
            left: 0;
            color: #2118CD;
            font-size: 12px;
        }

        .login-btn {
            width: 100%;
            background-color: #2118CD;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #000A7F;
        }

        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            width: 80%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-text {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .ok-btn {
            background-color: #2118CD;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            outline: none;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            color: #000;
        }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Redefinir Senha</h2>

    <?php if (empty($success_message)): ?>
        <form action="" method="POST">
            <div class="input-box">
                <input type="password" name="nova_senha" placeholder="Digite a nova senha" required>
                <label>Nova Senha</label>
            </div>
            <div class="input-box">
                <input type="password" name="confirmar_senha" placeholder="Confirme a nova senha" required>
                <label>Confirmar Senha</label>
            </div>
            <button type="submit" class="login-btn">Redefinir</button>
        </form>
    <?php endif; ?>
</div>

<!-- Modal de Sucesso -->
<?php if (!empty($success_message)): ?>
<div id="successModal" class="modal" style="display: block;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p class="modal-text" style="color: #2118CD;"><?php echo htmlspecialchars($success_message); ?></p>
        <button id="okBtnSuccess" class="ok-btn">OK</button>
    </div>
</div>
<?php endif; ?>

<!-- Modal de Erro -->
<?php if (!empty($error_message)): ?>
<div id="errorModal" class="modal" style="display: block;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p class="modal-text" style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <button id="okBtnError" class="ok-btn">OK</button>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Modal de sucesso
    const successModal = document.getElementById('successModal');
    const okBtnSuccess = document.getElementById('okBtnSuccess');
    const closeBtnSuccess = successModal ? successModal.querySelector('.close-btn') : null;

    if (successModal) {
        successModal.style.display = 'block';
        okBtnSuccess.addEventListener('click', function () {
            successModal.style.display = 'none';
            window.location.href = '../index.php'; // Redirecionar após sucesso
        });
        closeBtnSuccess.addEventListener('click', function () {
            successModal.style.display = 'none';
            window.location.href = '../index.php'; // Redirecionar após sucesso
        });
    }

    // Modal de erro
    const errorModal = document.getElementById('errorModal');
    const okBtnError = document.getElementById('okBtnError');
    const closeBtnError = errorModal ? errorModal.querySelector('.close-btn') : null;

    if (errorModal) {
        errorModal.style.display = 'block';
        okBtnError.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });
        closeBtnError.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });
    }
});
</script>


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
