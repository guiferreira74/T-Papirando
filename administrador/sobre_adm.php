<?php
session_start();

// Verifique se o administrador está logado e tem acesso apropriado
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 3) {
    header("Location: login.php");
    exit();
}

// Recupera o nome do administrador da sessão
$admin_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Administrador';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sobre_adm.css">
    <title>Sobre</title>
    <!-- Link para Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<header>
        <div class="interface">
            <!-- Botão para abrir a barra lateral -->
            <div class="controle-navegacao">
                
                <div class="button">
                    <button id="toggle-sidebar" class="toggle-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
               

                <div class="logo">
                    <img src="assets/logo_papirando_final.svg" alt="Logo">     
                </div><!-- logo -->
            </div><!-- controle-navegacao -->

            <div class="informacoes">
                <a href="sobre_adm.php">Sobre</a>
                <a href="ajuda_adm.php">Ajuda</a>
                <span class="mensagem-boas-vindas">Olá, <?php echo htmlspecialchars($admin_nome); ?>!</span>
                <a href="#" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">Sair</a>
              
            </div>
        </div><!-- interface -->
    </header>  
     
    
    <nav>
        <div id="sidebar">
            <ul>
                <li class="item-menu ">
                    <a href="adm.php">
                        <span class="icon"><i class="fas fa-home"></i></span> <!-- Ícone de casa -->
                        <span class="txt">Início</span>
                    </a>
                </li>

                <li class="item-menu">
                    <a href="ajuda_adm.php">
                        <span class="icon"><i class="fas fa-question-circle"></i></span> <!-- Ícone de livro -->
                        <span class="txt">Ajuda</span>
                    </a>
                </li>

                <li class="item-menu">
                    <a href="parametros.php">
                        <span class="icon"><i class="fas fa-trophy"></i></span> <!-- Ícone de troféu -->
                        <span class="txt">Parametros</span>
                    </a>
                </li>

                <hr>

                <h1 id="gr">Gerenciamento</h1>

                <li class="item-menu">
                    <a href="banca.php">
                        <span class="icon"><i class="fas fa-university"></i></span> <!-- Ícone de universidade -->
                        <span class="txt">Bancas</span>
                    </a>
                </li>


                <li class="item-menu">
                    <a href="concurso.php">
                        <span class="icon"><i class="fas fa-users"></i></span> <!-- Ícone de pessoas -->
                        <span class="txt">Concurso</span> 
                    </a>
                </li>

                <li class="item-menu">
                    <a href="questao.php">
                        <span class="icon"><i class="fas fa-book"></i></span> <!-- Ícone de pergunta -->
                        <span class="txt">Questões</span>
                    </a>
                </li>

                <li class="item-menu">
                    <a href="dificuldade.php">
                        <span class="icon"><i class="fas fa-chart-line"></i></span> <!-- Ícone de gráfico -->
                        <span class="txt">Dificuldade</span>
                    </a>
                </li>

                <li class="item-menu">
                    <a href="disciplina.php">
                        <span class="icon"><i class="fas fa-book-reader"></i></span> <!-- Ícone de leitura -->
                        <span class="txt">Disciplina</span>
                    </a>
                </li>

                <li class="item-menu">
                    <a href="duracao.php">
                        <span class="icon"><i class="fas fa-clock"></i></span> <!-- Ícone de relógio -->
                        <span class="txt">Duração</span>
                    </a>
                </li>

                <li class="item-menu">
                    <a href="instituicao.php">
                        <span class="icon"><i class="fas fa-school"></i></span> <!-- Ícone de instituição -->
                        <span class="txt">Instituições</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
        <!-- MANTENDO A SIDE BAR ATIVA -->
    <script>
        var menuitem = document.querySelectorAll('.item-menu'); // Corrigido para selecionar pela classe
    
        function selectlink() {
            menuitem.forEach((item) =>
                item.classList.remove('ativo') // Remove a classe 'ativo' de todos os itens
            );
            this.classList.add('ativo'); // Adiciona a classe 'ativo' ao item clicado
        }
    
        menuitem.forEach((item) =>
            item.addEventListener('click', selectlink) // Adiciona o evento de clique para cada item do menu
        );
    </script>

        <!-- SCRIPT PARA ABRIR E FECHAR A SIDE BAR -->
    <script>
        const toggleSidebarButton = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content'); // Caso tenha um container principal
    
        toggleSidebarButton.addEventListener('click', () => {
            sidebar.classList.toggle('closed'); // Alterna a classe 'closed' na sidebar
            mainContent.classList.toggle('sidebar-closed'); // Ajusta o layout do conteúdo principal, se aplicável
        });
    </script>


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
