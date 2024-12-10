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
    <title>Sobre</title>
    <link rel="stylesheet" href="sobre.css">
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
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

    <!-- Conteúdo Principal -->
    <main>
        <div class="sobre-container">
            <div class="desenvolvedores">
                <h2>Desenvolvedores</h2>
                <ul>
                    <li>Breno Soares Francisco</li>
                    <li>Luca Kalyl da Cunha Beckman</li>
                    <li>Guilherme Ferreira Alves Biserra</li>
                    <li>Marcos Antonio Pinheiro de Queiroz</li>
                </ul>
            </div>
            <div class="foto">
                <img src="../administrador/assets/grupo-faetec (2).jpeg" alt="Foto dos Desenvolvedores">
            </div>
        </div>

        <div class="creditos-container">
            <div class="foto">
                <img src="../administrador/assets/Faetec.svg" alt="Foto de Créditos">
            </div>
            <div class="creditos">
                <h2>Créditos e Agradecimentos</h2>
                <p>Gostaríamos de agradecer, primeiramente, a Deus, por nos conceder força e disposição para nos levantarmos todos os dias. Agradecemos também ao nosso professor orientador, Gustavo Mendonça, por toda a ajuda e esforço dedicados a nós ao longo de nosso percurso de aprendizado. Estendemos nossos agradecimentos à professora Lidiana Silva pela disponibilidade e atenção concedidas. Por fim, agradecemos à diretora da FAETEC CVT Nilópolis, Patrícia Monteiro, pelo grande apoio, não apenas ao nosso projeto, mas também pela dedicação diária aos alunos.</p>
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
