<?php
session_start();

// Verifica se o usuário está logado e se tem o tipo de acesso correto
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: ../administrador/login.php");
    exit();
}

// Capturando o nome e sobrenome do usuário da sessão
$usuario_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário'; // Nome padrão
$sobrenome_usuario = isset($_SESSION['sobrenome']) ? $_SESSION['sobrenome'] : ''; // Sobrenome padrão

// Verifique se o estudante está logado
if (!isset($_SESSION['cod_estudante'])) {
    header("Location: login.php");
    exit();
}

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

// Obter o código do estudante da sessão
$cod_estudante = $_SESSION['cod_estudante'];

// Inicializar variáveis para evitar warnings
$nome = '';
$sobrenome = '';
$email = '';
$success_message = '';
$error_message = '';

// Buscar os dados atuais do estudante, incluindo o e-mail
$sql_estudante = "SELECT nome, sobrenome, email FROM estudante WHERE cod_estudante = ?";
$stmt_estudante = $conn->prepare($sql_estudante);
$stmt_estudante->bind_param("i", $cod_estudante);
$stmt_estudante->execute();
$stmt_estudante->bind_result($nome, $sobrenome, $email);
$stmt_estudante->fetch();
$stmt_estudante->close();

// Verificar se o formulário foi enviado para atualizar os dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']);
    $sobrenome = $conn->real_escape_string($_POST['sobrenome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha_atual = $conn->real_escape_string($_POST['current_password']);
    $nova_senha = $conn->real_escape_string($_POST['new_password']);
    $confirmar_senha = $conn->real_escape_string($_POST['confirm_password']);

    // Verificar se a senha atual está correta
    $sql_senha_atual = "SELECT senha FROM estudante WHERE cod_estudante = ?";
    $stmt_senha = $conn->prepare($sql_senha_atual);
    $stmt_senha->bind_param("i", $cod_estudante);
    $stmt_senha->execute();
    $stmt_senha->bind_result($senha_bd);
    $stmt_senha->fetch();
    $stmt_senha->close();

    if (password_verify($senha_atual, $senha_bd)) {
        if ($senha_atual === $nova_senha) {
            $error_message = "A nova senha não pode ser igual à senha atual.";
        } else {
            if ($nova_senha === $confirmar_senha) {
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

                // Atualizar os dados no banco de dados, incluindo o e-mail
                $sql_update = "UPDATE estudante SET nome = ?, sobrenome = ?, email = ?, senha = ? WHERE cod_estudante = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ssssi", $nome, $sobrenome, $email, $nova_senha_hash, $cod_estudante);

                if ($stmt_update->execute()) {
                    $success_message = "Dados atualizados com sucesso,<br>por favor registre novamente!";
                } else {
                    $error_message = "Erro ao atualizar os dados.";
                }

                $stmt_update->close();
            } else {
                $error_message = "As novas senhas não coincidem. Tente novamente.";
            }
        }
    } else {
        $error_message = "A senha atual está incorreta.";
    }
}

// Fechar conexão
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados do Estudante</title>
    <link rel="stylesheet" href="editar_dados_user.css">
    <link rel="stylesheet" href="user.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="user.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
        </div>
        <nav class="menu-desktop">
            <ul>
                <li><a href="user.php">Início</a></li>
                <li class="dropdown">
                    <a href="#" class="simulados-link" id="simulados-toggle">
                        Simulados <i class='bx bx-chevron-down'></i>
                    </a>
                    <ul class="dropdown-menu" id="simulados-dropdown">
                        <li><a href="#">Simulado por Disciplina</a></li>
                        <li><a href="simulados.php">Simulado por Concurso</a></li>
                    </ul>
                </li>
                <li><a href="bancas_user.php">Bancas</a></li>
                <li><a href="desempenhos.php" class="desempenho-link">Desempenho</a></li>
            </ul>
        </nav>

        <!-- Dropdown de Perfil -->
        <div class="info">
            <a href="sobre_user.php">Sobre</a>
            <a href="ajuda_user.php">Ajuda</a>
            
            <!-- Link de saudação com o nome e o dropdown -->
            <div class="profile-dropdown">
                <a href="#" class="profile-toggle" id="profile-toggle">
                    Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>
                    <i class='bx bx-chevron-down'></i> <!-- Ícone de seta para baixo -->
                </a>
                <ul class="profile-link" id="profile-dropdown">
                    <li><a href="editar_dados_user.php"><i class='bx bxs-user-circle icon'></i> Editar dados</a></li>
                    <li><a href="../administrador/sair.php"><i class='bx bxs-log-out-circle'></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="container-geral">
    <div class="formulario-container">
        <h1>Editar Dados do Estudante</h1>

        <!-- Formulário de edição de dados -->
        <form action="editar_dados_user.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>

            <div class="form-group">
                <label for="sobrenome">Sobrenome</label>
                <input type="text" id="sobrenome" name="sobrenome" placeholder="Digite seu sobrenome" value="<?php echo htmlspecialchars($sobrenome); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" value="<?php echo htmlspecialchars($email); ?>" required>
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

            <button type="submit" class="btn-save">Salvar Alterações</button>
        </form>
    </div>
</div>
</body>
</html>


<!-- Modal de erro -->
<?php if (!empty($error_message)): ?>
<div id="errorModal" class="modal modal-erro">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p><?php echo $error_message; ?></p>
        <button id="okBtnErro" class="ok-btn-erro">OK</button>
    </div>
</div>
<?php endif; ?>

<!-- Modal de sucesso -->
<?php if (!empty($success_message)): ?>
<div id="successModal" class="modal modal-sucesso">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p><?php echo $success_message; ?></p>
        <button id="okBtnSucesso" class="ok-btn-sucesso">OK</button>
    </div>
</div>
<?php endif; ?>

<script>
// Modal script
document.addEventListener('DOMContentLoaded', function () {
    // Modal de sucesso
    const successModal = document.getElementById('successModal');
    const okBtnSucesso = document.getElementById('okBtnSucesso');

    if (successModal) {
        successModal.style.display = 'block';
        okBtnSucesso.addEventListener('click', function () {
            window.location.href = '../index.php';
        });
    }

    // Modal de erro
    const errorModal = document.getElementById('errorModal');
    const okBtnErro = document.getElementById('okBtnErro');
    const closeBtnErro = document.querySelector('.modal-erro .close-btn');

    if (errorModal) {
        errorModal.style.display = 'block';
        okBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });

        closeBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });
    }
});
</script>

<script>
  // Mostrar e esconder o dropdown quando o usuário clica
const profileToggle = document.getElementById('profile-toggle');
const profileDropdown = document.getElementById('profile-dropdown');

profileToggle.addEventListener('click', function (e) {
    e.preventDefault(); // Evita o comportamento padrão do link
    profileDropdown.classList.toggle('show'); // Alterna a classe "show"
});

// Fechar o dropdown quando o usuário clica fora dele
window.addEventListener('click', function (e) {
    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
});


// Mostrar e esconder o dropdown quando o usuário clica em "Simulados"
const simuladosToggle = document.getElementById('simulados-toggle');
const simuladosDropdown = document.getElementById('simulados-dropdown');

simuladosToggle.addEventListener('click', function (e) {
    e.preventDefault();
    simuladosDropdown.classList.toggle('show');
});

// Fechar o dropdown de "Simulados" ao clicar fora
window.addEventListener('click', function (e) {
    if (!simuladosToggle.contains(e.target) && !simuladosDropdown.contains(e.target)) {
        simuladosDropdown.classList.remove('show');
    }
});

</script>


<style>
    
    /* Estilo para o dropdown *//* Estilo para o dropdown de Simulados */
.menu-desktop ul .dropdown {
    position: relative;
}

.menu-desktop ul .dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1;
    list-style: none;
    margin: 0;
    padding: 0;
    border-radius: 8px;
    min-width: 200px;
}

.menu-desktop ul .dropdown-menu.show {
    display: block;
}

.menu-desktop ul .dropdown-menu li {
    border-bottom: 1px solid #ddd;
}

.menu-desktop ul .dropdown-menu li a {
    display: block;
    padding: 10px;
    color: #333;
    text-decoration: none;
    white-space: nowrap;
}

.menu-desktop ul .dropdown-menu li a:hover {
    background-color: #f4f4f4;
}
/* Estilo para o dropdown */
.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-toggle {
    color: white; /* Cor do texto de saudação */
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.profile-toggle i {
    margin-left: 5px;
}

.profile-link {
    display: none; /* O dropdown estará oculto inicialmente */
    position: absolute;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    right: 0;
    z-index: 1;
    border-radius: 8px;
    min-width: 150px;
    padding: 10px 0;
    text-align: left;
}

.profile-link.show {
    display: block;
}

.profile-link li {
    list-style-type: none;
}

.profile-link li a {
    white-space: nowrap; /* Evita que o texto quebre a linha */
    display: flex;
    align-items: center;
    padding: 10px;
    color: #000; /* Cor do texto */
    text-decoration: none;
}

.profile-link li a i {
    margin-right: 8px; /* Espaço entre o ícone e o texto */
    font-size: 18px; /* Ajuste do tamanho dos ícones */
    color: #000; /* Cor dos ícones */
}

.profile-link li a:hover {
    background-color: #f1f1f1; /* Muda a cor ao passar o mouse */
}

/* Estilo adicional para o visual arredondado do dropdown */
.profile-link {
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 10px;
}

</style>
</body>
</html>
