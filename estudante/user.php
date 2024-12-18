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
    <title>Estudante</title>
    <link rel="stylesheet" href="user.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
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
                <li><a href="simulados.php">Simulados</a></li>
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
    <!-- Primeira seção -->
    <section class="section-light">
        <div class="content">
            <div class="text animate-text">
                <h1>Simulados para aprimorar seus conhecimentos</h1>
                <p>Realizar simulados é fundamental para identificar seus pontos fortes e fracos. Descubra onde melhorar para alcançar seus objetivos.</p>
               <a href="simulados.php"> <button class="btn-simulado">Realizar Simulado</button></a>
            </div>
            <div class="image animate-image">
                <img src="/administrador/assets/simulados.svg" alt="Imagem sobre simulados">
            </div>
        </div>
    </section>

    <!-- Segunda seção -->
    <section class="section-dark">
        <div class="content">
            <div class="text animate-text">
                <h1>Acompanhe e analise seu desempenho</h1>
                <p>Entenda como você está evoluindo ao longo do tempo. Monitorar seu desempenho é essencial para o sucesso nos concursos.</p>
                <a href="desempenhos.php"><button class="btn-desempenho">Ver Desempenho</button></a>
            </div>
            <div class="image animate-image">
                <img src="/administrador/assets/desempenho2.svg" alt="Imagem sobre desempenho">
            </div>
        </div>
    </section>
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

/* Estilo para o dropdown de Simulados */
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

</style>

</body>
</html>