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
    <link rel="stylesheet" href="ajuda_adm.css">
    <title>Ajuda</title>
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

    <main>
        <div class="topo">
            <div class="topo-content">
                <h1 class="topo-title">FAQ</h1>
                <p class="topo-desc">Dúvidas frequentes</p>
            </div>
            <div class="img">
                <img src="assets/faq2.svg" alt="">
            </div>
        </div>
        
        <div class="faq">
            <div class="faq-name"> <!-- Corrigido para "faq-name" -->
                <h1 class="faq-top"> Principais <br> Dúvidas</h1>
                <img class="faq-img" src="assets/faq.svg" alt="">
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
                    <label class="faq-title" for="faq-trigger-4">Posso fazer os simulados quantas vezes quiser?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Sim, você pode refazer os simulados quantas vezes quiser. A cada nova tentativa, as questões são embaralhadas, permitindo que você tenha uma experiência diferente e continue aprimorando seus conhecimentos.</p>
            </div>
        </div>
        <div class="faq-wrapper">
                    <input type="checkbox" class="faq-trigger" id="faq-trigger-5">
                    <label class="faq-title" for="faq-trigger-5">Como o site pode me ajudar a me preparar melhor para o concurso?</label>
                    <div class="faq-detail"> <!-- Corrigido: adicionado div -->
                        <p>Os simulados ajudam a identificar suas dificuldades e lacunas no conhecimento, oferecendo uma forma prática de estudar. Além disso, ao realizar os simulados com regularidade, você se familiariza com o formato das provas, desenvolve agilidade e aumenta sua confiança.</p>
            </div>
    </main>
    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmLogoutModalLabel">Sair</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja sair?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="confirmLogout" href="sair.php" class="btn btn-primary">Sair</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
