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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda</title>
    <link rel="stylesheet" href="ajuda.css">
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
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
                <li><a href="simulados.php" class="simulados-link">Simulados</a></li>
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


    <main>
        <div class="topo">
            <div class="topo-content">
                <h1 class="topo-title">FAQ</h1>
                <p class="topo-desc">Dúvidas frequentes</p>
            </div>
            <div class="img">
                <img src="../administrador/assets/faq2.svg" alt="">
            </div>
        </div>
        
        <div class="faq">
            <div class="faq-name">
                <h1 class="faq-top"> Principais <br> Dúvidas</h1>
                <img class="faq-img" src="../administrador/assets/faq.svg" alt="">
            </div>
            <div class="faq-box">
                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-1">
                    <label class="faq-title" for="faq-trigger-1">O que é o TÔPAPIRANDO?</label>
                    <div class="faq-detail">
                        <p>O TÔPAPIRANDO é uma plataforma para concurseiros que oferece simulados, acesso direto aos sites das bancas organizadoras e ferramentas para acompanhar seu desempenho após a realização dos simulados.</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-2">
                    <label class="faq-title" for="faq-trigger-2">Como faço para me cadastrar no site?</label>
                    <div class="faq-detail">
                        <p>Basta clicar no botão "Cadastrar", preencher suas informações pessoais e criar uma senha. Após o cadastro, você terá acesso a todas as funcionalidades do site.</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-3">
                    <label class="faq-title" for="faq-trigger-3">Como funcionam os simulados?</label>
                    <div class="faq-detail">
                        <p>Os simulados são compostos por questões de provas anteriores e atualizadas. Você pode escolher a área de interesse, o nível de dificuldade e a quantidade de questões. Após a conclusão, você receberá um relatório de desempenho.</p>
                    </div>
                </div>
                
            </div>
        </div>
    </main>
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

</script>

<style>
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
