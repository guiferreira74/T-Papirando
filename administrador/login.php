<?php
// Inicie a sessão no início do arquivo PHP
session_start();

$error_message = '';

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

    // Obter os dados do formulário e validar
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    // Verificar se o e-mail existe
    $sql = "SELECT senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_senha);
        $stmt->fetch();

        // Verificar a senha
        if (password_verify($senha, $hashed_senha)) {
            // Definir sessão e redirecionar com base no domínio do e-mail
            $_SESSION['email'] = $email;

            // Verificar se a opção "Lembrar meus dados" foi selecionada
            if (isset($_POST['lembrar_dados'])) {
                // Definir cookies por 30 dias
                setcookie('email', $email, time() + (30 * 24 * 60 * 60), "/");
                setcookie('senha', $senha, time() + (30 * 24 * 60 * 60), "/"); // NÃO RECOMENDADO: armazenar a senha em texto plano
            } else {
                // Remover cookies se a opção não foi marcada
                setcookie('email', '', time() - 3600, "/");
                setcookie('senha', '', time() - 3600, "/");
            }

            if (strpos($email, '@admin') !== false) {
                // E-mail contém o domínio @admin, tratar como administrador
                $_SESSION['tipo_acesso'] = 1;
                header("Location: adm.php");
                exit();
            } else {
                // E-mail não contém o domínio @admin, tratar como usuário normal
                $_SESSION['tipo_acesso'] = 2;
                header("Location: user.php");
                exit();
            }
        } else {
            $error_message = 'Senha incorreta.';
        }
    } else {
        $error_message = 'E-mail não cadastrado, crie sua conta ao lado por favor.';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">

</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
           <a href="index.php"><img class="logo" src="assets/logo.svg" alt="topapirando"></a>
            <div class="search-bar">
                <input type="text" placeholder="Digite seu texto aqui">  
            </div>
            <div class="links">
                <a href="#">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="login.php">Entrar</a>
            </div>
            </header>
        <div class="menu">
            <a href="index.php">Início</a>
            <a href="#" class="restricted">Simulados</a>
            <a href="bancas.php">Bancas</a>
            <a href="#" class="restricted">Desempenho</a>
        </div>
    </div>

    <main>
        <div class="grid-duplo">
            <div class="esquerda">
                <img id="login" src="assets/login azul.svg" alt="">
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
                <img id="Log" src="assets/login verde.svg" alt="">
                <h1>Novo usuário</h1>
                <p>Criar uma conta é fácil! Informe seus dados e uma senha para <br> aproveitar todos os benefícios de ter uma conta.</p>
                <a href="cadastro.php"><button id="button-direita">Crie sua Conta</button></a>
            </div>
        </div>
    </main>

    <!-- Modal HTML -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        // Função para abrir o modal
        function showModal() {
            document.getElementById('errorModal').style.display = 'block';
        }

        // Função para fechar o modal
        function closeModal() {
            document.getElementById('errorModal').style.display = 'none';
        }

        // Mostrar modal com mensagens de erro se houver
        <?php if (!empty($error_message)): ?>
            showModal();
        <?php endif; ?>
    </script>
       <script>
        // Exemplo de controle de acesso
        document.addEventListener("DOMContentLoaded", function() {
            var loggedIn = false; // Substitua isso pela sua lógica de verificação de login
            
            // Se estiver logado, remova a classe de restrição dos links
            if (loggedIn) {
                document.querySelectorAll('.menu a.restricted').forEach(link => {
                    link.classList.remove('restricted');
                });
            }
        });
    </script>
</body>
</html>
