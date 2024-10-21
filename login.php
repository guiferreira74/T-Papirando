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
                    <li><a href="index.php" class="simulados">Início</a></li>
                    <li><a href="simulados.php" class="simulados">Simulados</a></li>
                    <li><a href="bancas.php" class="bancas">Bancas</a></li>
                    <li><a href="desempenhos.php" class="desempenho">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info"> 
                <a href="./estudante/sobre.php">Sobre</a>
                <a href="./estudante/ajuda.php">Ajuda</a>
                <a href="login_adm.php"><i class="fa-solid fa-gear" id="gear"></i></a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="grid-duplo">
            <div class="esquerda">
                <img id="login" src="./administrador/assets/login azul.svg" alt="">
                <h1>Já tem conta?</h1>
                <p>Informe seus dados para acessá-la</p>
                
                <form action="login.php" method="post">
                    <input id="input-email" name="email" type="text" placeholder="E-mail" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" required>
                    <input id="input-senha" name="senha" type="password" placeholder="Senha" value="<?php echo isset($_COOKIE['senha']) ? $_COOKIE['senha'] : ''; ?>" required>

                    <div id="checa">  
                        <input type="checkbox" id="checar" name="lembrar_dados" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                        <label for="checar">Lembrar meus dados</label>
                    </div>

                    <div class="button-container">
                        <button id="button-esquerda" type="submit">Acessar Conta</button>
                    </div>
                </form>
            </div>
                
            <div class="direita">
                <img id="Log" src="./administrador/assets/login verde.svg" alt="">
                <h1>Novo usuário</h1>
                <p>Criar uma conta é fácil! Informe seus dados e uma senha para <br> aproveitar todos os benefícios de ter uma conta.</p>
                <a href="cadastro.php"><button id="button-direita">Crie sua Conta</button></a>
            </div>
        </div>
    </main>

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
</body>
</html>
