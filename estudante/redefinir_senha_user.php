<?php
session_start();

// Verificar se o email está na sessão
if (!isset($_SESSION['reset_email'])) {
    header("Location: esqueci_senha_user.php");
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', 'admin', 'Topapirando');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($nova_senha !== $confirmar_senha) {
        $error_message = "As senhas não correspondem. Tente novamente.";
    } else {
        // Hash da nova senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);

        // Atualizar a senha no banco de dados
        $email = $_SESSION['reset_email'];
        $sql = "UPDATE estudante SET senha = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $nova_senha_hash, $email);

        if ($stmt->execute()) {
            $success_message = "Senha redefinida com sucesso!";
            unset($_SESSION['reset_email']); // Remove a sessão após redefinir a senha
        } else {
            $error_message = "Erro ao redefinir a senha. Tente novamente.";
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="redefinir_senha_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="../index.php"><img src="../administrador/assets/logo_papirando_final.svg" alt="Logo"></a>
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
        <h2>Redefinir Senha</h2>
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
            <div id="successModal" class="modal" style="display: block;">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <p class="modal-text" style="color: #2118CD;"><?php echo htmlspecialchars($success_message); ?></p>
                    <button id="okBtnSuccess" class="ok-btn">OK</button>
                </div>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="input-box">
                <input type="password" name="nova_senha" placeholder="Digite a nova senha" required>
                <label>Nova senha</label>
            </div>
            <div class="input-box">
                <input type="password" name="confirmar_senha" placeholder="Confirme a nova senha" required>
                <label>Confirmar nova senha</label>
            </div>
            <button type="submit" class="login-btn">Redefinir</button>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const errorModal = document.getElementById('errorModal');
        const successModal = document.getElementById('successModal');
        const closeBtns = document.querySelectorAll('.close-btn');
        const okBtnSuccess = document.getElementById('okBtnSuccess');

        // Fechar modal de erro
        if (errorModal) {
            errorModal.querySelector('.ok-btn').addEventListener('click', function () {
                errorModal.style.display = 'none';
            });
        }

        // Redirecionar ao clicar no botão "OK" do modal de sucesso
        if (successModal) {
            okBtnSuccess.addEventListener('click', function () {
                window.location.href = '../index.php'; // Redireciona para a página inicial
            });
        }

        // Fechar modal ao clicar no botão "X"
        closeBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (successModal) successModal.style.display = 'none';
                if (errorModal) errorModal.style.display = 'none';
            });
        });
    });
</script>

<style>
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
</body>
</html>