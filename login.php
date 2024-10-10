<?php
session_start();

$error_message = '';
$success_message = '';

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

    // Verificar se o e-mail tem o domínio @gmail.com
    if (strpos($email, '@gmail.com') === false && strpos($email, '@admin') === false) {
        $error_message = 'Por favor, use um e-mail do domínio @gmail.com ou @admin.';
    } else {
        // Verificar se o e-mail contém o domínio @admin
        if (strpos($email, '@admin') !== false) {
            // Tipo de acesso: Administrador
            $tipo_acesso = 3;

            // Verificar se o e-mail existe na tabela de administradores
            $sql_admin = "SELECT nome, sobrenome, senha FROM administrador WHERE email = ?";
            $stmt_admin = $conn->prepare($sql_admin);
            $stmt_admin->bind_param("s", $email);
            $stmt_admin->execute();
            $stmt_admin->store_result();

            if ($stmt_admin->num_rows > 0) {
                $stmt_admin->bind_result($nome_admin, $sobrenome_admin, $hashed_senha_admin);
                $stmt_admin->fetch();

                // Verificar a senha
                if (password_verify($senha, $hashed_senha_admin)) {
                    // Definir sessão e redirecionar
                    $_SESSION['email'] = $email;
                    $_SESSION['nome'] = $nome_admin;
                    $_SESSION['sobrenome'] = $sobrenome_admin;
                    $_SESSION['tipo_acesso'] = $tipo_acesso; // Administrador

                    // Redirecionar para a página do administrador
                    header("Location: ./administrador/adm.php");
                    exit();
                } else {
                    $error_message = 'Senha incorreta.';
                }
            } else {
                $error_message = 'E-mail não cadastrado, crie sua conta ao lado por favor.';
            }

            // Fecha a declaração
            $stmt_admin->close();
        } else {
            // Tipo de acesso: Estudante
            $tipo_acesso = 2;

            // Verificar se o e-mail existe na tabela de estudantes
            $sql_estudante = "SELECT nome, sobrenome, senha FROM estudante WHERE email = ?";
            $stmt_estudante = $conn->prepare($sql_estudante);
            $stmt_estudante->bind_param("s", $email);
            $stmt_estudante->execute();
            $stmt_estudante->store_result();

            if ($stmt_estudante->num_rows > 0) {
                $stmt_estudante->bind_result($nome_estudante, $sobrenome_estudante, $hashed_senha_estudante);
                $stmt_estudante->fetch();

                // Verificar a senha para estudante
                if (password_verify($senha, $hashed_senha_estudante)) {
                    // Definir sessão e redirecionar
                    $_SESSION['email'] = $email;
                    $_SESSION['nome'] = $nome_estudante;
                    $_SESSION['sobrenome'] = $sobrenome_estudante;
                    $_SESSION['tipo_acesso'] = $tipo_acesso; // Estudante

                    // Redirecionar para a página do estudante
                    header("Location: ./estudante/user.php");
                    exit();
                } else {
                    $error_message = 'Senha incorreta.';
                }
            } else {
                $error_message = 'E-mail não cadastrado, crie sua conta ao lado por favor.';
            }

            // Fecha a declaração
            $stmt_estudante->close();
        }
    }

    // Fecha a conexão
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
                <a href="login.php">Entrar</a>
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
                <a href="#"><button id="enter">Entre como Administrador</button></a>
            </div>
                
            <div class="direita">
                <img id="Log" src="./administrador/assets/login verde.svg" alt="">
                <h1>Novo usuário</h1>
                <p>Criar uma conta é fácil! Informe seus dados e uma senha para <br> aproveitar todos os benefícios de ter uma conta.</p>
                <a href="cadastro.php"><button id="button-direita">Crie sua Conta</button></a>
            </div>
        </div>
    </main>
    <!-- Modal Login Administrador -->
<div id="modal-login-administrador" class="modal modal-custom">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Login Administrador</h2>
        <form id="form-login-admin" action="login.php" method="post">
            <input name="email" type="text" placeholder="E-mail" required>
            <input name="senha" type="password" placeholder="Senha" required>
            <button type="submit">Acessar</button>
        </form>
        <button onclick="closeModal()" class="btn-custom">Cancelar</button>
    </div>
</div>


    <!-- Modal Simulados -->
    <div id="modal-simulados" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o simulado.</p>
            <button id="ok-btn-simulados" class="btn-custom">OK</button>
        </div>
    </div>

    <!-- Modal Desempenho -->
    <div id="modal-desempenho" class="modal modal-custom">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <p>Por favor, faça o login para ver o seu desempenho.</p>
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
        document.querySelectorAll('.menu-desktop a').forEach(function(link) {
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
// Obter elemento do modal de login do administrador
var modalLoginAdministrador = document.getElementById("modal-login-administrador");

// Função para mostrar o modal de login do administrador
function showModalLoginAdministrador() {
    modalLoginAdministrador.style.display = "block";
}

// Adicionar evento de clique para o botão "Entre como Administrador"
document.getElementById("enter").onclick = function() {
    showModalLoginAdministrador();
};

        // Mostrar modal de erro com mensagens se houver
        <?php if (!empty($error_message)): ?>
            showModalErro();
        <?php endif; ?>
    </script>
</body>
</html>
