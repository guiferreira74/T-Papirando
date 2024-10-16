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
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
</head>
<body>
    <!-- Cabeçalho -->
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
            <div class="info">
                <a href="sobre_user.php">Sobre</a>
                <a href="ajuda_user.php">Ajuda</a>
                <span class="saudacao">Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>!</span>
                <a href="../administrador/sair.php">Sair</a>
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
                <img src="../administrador/assets/grupo.svg" alt="Foto dos Desenvolvedores">
            </div>
        </div>

        <div class="creditos-container">
            <div class="foto">
                <img src="../administrador/assets/Faetec.svg" alt="Foto de Créditos">
            </div>
            <div class="creditos">
                <h2>Créditos e Agradecimentos</h2>
                <p>Gostaríamos de agradecer primeiramente a Deus, por nos dar força e disposição para nos levantar todos os dias. Agradecemos também nosso professor orientador Gustavo Mendonça por toda ajuda e esforço dedicado a nós por todo o nosso percurso de aprendizado. Agradecemos a Professora, Lidiana Silva por toda disponibilidade e atenção para tirar dúvidas e reforço dado a nós durante a realização do nosso trabalho de conclusão de curso. Agradecemos também à diretora da FAETEC CVT Nilópolis, Patrícia Monteiro, pelo grande apoio não só em nosso projeto, mas pela dedicação em estar todos os dias procurando o melhor para seus alunos. Agradecemos também a todos os profissionais da instituição que trabalham todos os dias para que nosso ambiente escolar fique o melhor possível.</p>
            </div>
        </div>
    </main>

   
</body>
</html>
