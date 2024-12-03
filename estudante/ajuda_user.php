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
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div id="conteudo-header">
    <header>
        <div class="interface">
            <div class="logo">
                <a href="user.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
            </div>

            <nav class="menu-desktop">
                <ul>
                    <li><a href="user.php">Início</a></li>
                    <li><a href="simulados.php">Simulados</a></li>
                    <li><a href="bancas_user.php">Bancas</a></li>
                    <li><a class="desempenhos." href="desempenhos.php">Desempenho</a></li>
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
            <div class="faq-name"> <!-- Corrigido para "faq-name" -->
                <h1 class="faq-top"> Principais <br> Dúvidas</h1>
                <img class="faq-img" src="../administrador/assets/faq.svg" alt="">
            </div>
            <div class="faq-box">
                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-1">
                    <label class="faq-title" for="faq-trigger-1">O que é o TÔPAPIRANDO?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>O TÔPAPIRANDO é uma plataforma para concurseiros que oferece simulados, acesso direto aos sites das bancas organizadoras e ferramentas para acompanhar seu desempenho após a realização dos simulados.</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-2">
                    <label class="faq-title" for="faq-trigger-2">Como faço para me cadastrar no site?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Basta clicar no botão "Cadastrar", preencher suas informações pessoais e criar uma senha. Após o cadastro, você terá acesso a todas as funcionalidades do site.</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-3">
                    <label class="faq-title" for="faq-trigger-3">Como funcionam os simulados?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Os simulados são compostos por questões de provas anteriores e atualizadas. Você pode escolher a área de interesse, o nível de dificuldade e a quantidade de questões. Após a conclusão, você receberá um relatório de desempenho.</p>
                    </div>
                </div>


                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-4">
                    <label class="faq-title" for="faq-trigger-4">Como posso atualizar minhas informações pessoais?
                    </label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Para atualizar suas informações pessoais, faça login e vá até a aba "Perfil". No canto direito da página inicial, você verá seu nome de usuário. Clique nele para abrir um menu suspenso (drop-down) e selecione "Editar Conta". Lá, você pode editar suas informações de contato, senha</p>
                    </div>
                </div>

                <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-5">
                    <label class="faq-title" for="faq-trigger-5">Como acessar os simulados disponíveis?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Depois de fazer login, vá até a aba "Simulados". Lá, você encontrará uma lista de simulados organizados por disciplina, nível e banca. Selecione o simulado desejado e clique em "Iniciar Simulado" para começar..</p>
                    </div>
                </div>
                
    </main>

    
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