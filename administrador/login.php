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
<html lang="pt-br">
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
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="login.php">Entrar</a>
            </div>
        </header>
        <div class="menu">
            <a href="index.php">Início</a>
            <a href="#" class="">Simulados</a>
            <a href="bancas.php">Bancas</a>
            <a href="#" class="">Desempenho</a>
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

    <!-- Modal Simulados -->
    <div id="modal-simulados" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, crie sua conta para ver o simulado.</p>
            <button id="ok-btn-simulados" class="btn-custom">OK</button>
        </div>
    </div>

    <!-- Modal Desempenho -->
    <div id="modal-desempenho" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, crie sua conta para ver o desempenho.</p>
            <button id="ok-btn-desempenho" class="btn-custom">OK</button>
        </div>
    </div>

    <!-- Modal Erro -->
    <div id="errorModal" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <button onclick="closeModal()" class="btn-custom">OK</button>
        </div>
    </div>

    <script>
        // Obter elementos dos modais e botões
        var modalSimulados = document.getElementById("modal-simulados");
        var modalDesempenho = document.getElementById("modal-desempenho");
        var modalErro = document.getElementById("errorModal");

        var closeBtns = document.getElementsByClassName("close-btn");
        var okBtnSimulados = document.getElementById("ok-btn-simulados");
        var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

        // Função para mostrar um modal específico
        function showModal(modal) {
            modal.style.display = "block";
        }

        // Função para esconder todos os modais
        function closeModal() {
            modalSimulados.style.display = "none";
            modalDesempenho.style.display = "none";
            modalErro.style.display = "none";
        }

        // Adicionar eventos de clique para os links Simulados e Desempenho
        document.querySelectorAll('.menu a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (this.textContent === "Simulados") {
                    e.preventDefault(); // Previne a navegação padrão
                    showModal(modalSimulados);
                } else if (this.textContent === "Desempenho") {
                    e.preventDefault(); // Previne a navegação padrão
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
            if (event.target == modalSimulados || event.target == modalDesempenho || event.target == modalErro) {
                closeModal();
            }
        }

        // Função para abrir o modal de erro
        function showModalErro() {
            document.getElementById('errorModal').style.display = 'block';
        }

        // Mostrar modal de erro com mensagens se houver
        <?php if (!empty($error_message)): ?>
            showModalErro();
        <?php endif; ?>
    </script>
</body>
</html>
