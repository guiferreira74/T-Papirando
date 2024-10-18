<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', 'admin', 'Topapirando');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Variáveis para armazenar mensagens de erro ou sucesso
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $current_password = $_POST['current_password'];  // senha atual
    $new_password = $_POST['new_password'];          // nova senha
    $confirm_password = $_POST['confirm_password'];  // confirmação da nova senha
    $email = $_POST['email'];                        // novo email (caso mude)
    $nome = $_POST['nome'];                          // novo nome (caso mude)
    $sobrenome = $_POST['sobrenome'];                // novo sobrenome (caso mude)
    
    // Verifica o administrador logado pela sessão (supondo que o ID seja armazenado na sessão)
    $cod_administrador = $_SESSION['cod_administrador'];

    // Busca a senha atual do administrador no banco de dados
    $sql = "SELECT senha FROM administrador WHERE cod_administrador = $cod_administrador";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $senha_atual_hash = $row['senha']; // Hash da senha atual armazenada

        // Verificar se a senha atual está correta
        if (password_verify($current_password, $senha_atual_hash)) {
            // Verificar se as novas senhas coincidem
            if ($new_password === $confirm_password) {
                // Atualizar o nome, sobrenome e email, e hash da nova senha
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                $update_sql = "
                    UPDATE administrador 
                    SET nome = ?, sobrenome = ?, email = ?, senha = ?
                    WHERE cod_administrador = ?
                ";

                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param('ssssi', $nome, $sobrenome, $email, $new_password_hash, $cod_administrador);

                if ($stmt->execute()) {
                    // Mensagem de sucesso
                    $success_message = "Dados atualizados com sucesso!";
                } else {
                    $error_message = "Erro ao atualizar os dados. Por favor, tente novamente.";
                }
            } else {
                $error_message = "As novas senhas não coincidem.";
            }
        } else {
            $error_message = "A senha atual está incorreta.";
        }
    } else {
        $error_message = "Administrador não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados de Login</title>
    <link rel="stylesheet" href="editar_dados.css">
</head>
<body>

<header>
    <div class="interface">
        <div class="logo">
            <a href="adm.php">
                <img src="assets/logo_papirando_final.svg" alt="Logo">
            </a>    
        </div>

        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="info">
            <a href="sobre_adm.php">Sobre</a>
            <a href="ajuda_adm.php">Ajuda</a>
            <a href="sair.php">Sair</a>
        </div>
    </div> <!--interface-->
</header>

<div class="container-geral">
    <div class="formulario-container">
        <h1>Editar Dados de Login</h1>

        <!-- Exibe mensagem de erro -->
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="editar_dados.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
            </div>

            <div class="form-group">
                <label for="sobrenome">Sobrenome</label>
                <input type="text" id="sobrenome" name="sobrenome" placeholder="Digite seu sobrenome" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email" required>
            </div>

            <div class="form-group">
                <label for="current_password">Senha atual</label>
                <input type="password" id="current_password" name="current_password" placeholder="Digite sua senha atual" required>
            </div>

            <div class="form-group">
                <label for="new_password">Nova senha</label>
                <input type="password" id="new_password" name="new_password" placeholder="Digite a nova senha" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirme a nova senha</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme a nova senha" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-save">Salvar Alterações</button>
            </div>
        </form>
    </div>

    <div class="imagem-container">
        <img src="assets/editar.svg" alt="Editar Dados">
    </div>
</div> <!--container-geral-->

<!-- Modal para mensagem de sucesso -->
<?php if (!empty($success_message)): ?>
<div id="successModal" class="modal">
    <div class="modal-content">
        <p><?php echo $success_message; ?></p>
        <button id="modalOkBtn">OK</button>
    </div>
</div>
<?php endif; ?>

<script>
// Script para o menu hamburguer funcionar
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('header nav ul');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('show');
});

// Script para mostrar o modal de sucesso e redirecionar após clicar no botão "OK"
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('successModal');
    const okBtn = document.getElementById('modalOkBtn');

    if (modal) {
        // Exibe o modal
        modal.style.display = 'block';

        // Ao clicar no botão OK, redireciona para index.php
        okBtn.addEventListener('click', function () {
            window.location.href = '../index.php';
        });
    }
});
</script>

</body>
</html>
