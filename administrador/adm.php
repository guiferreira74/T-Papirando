<?php
session_start();

// Verifique se o administrador está logado e tem acesso apropriado
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 1) {
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
    <link rel="stylesheet" href="adm.css">
    <title>Administrador</title>
    <!-- Link para Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <header>
        <div class="interface">
            <div class="logo">
            <a href="adm.php"><img class="logo" src="assets/logo_papirando_final.svg" alt="Logo"/></a>   
            </div><!--logo-->

            <div class="info">
                <a href="">Sobre</a>
                <a href="">Ajuda</a>
                <span class="mensagem-boas-vindas">Olá, <?php echo htmlspecialchars($admin_nome); ?>!</span>
                <a href="#" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">Sair</a>
                <img id="user" src="assets/user.svg" alt="">
            </div>
        </div> <!--interface-->
    </header>

    <nav>
        <div id="sidebar">
            <ul>
                <li>
                    <a href="adm.php">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span class="txt">Início</span>
                    </a>
                </li>
                <li>
                    <a href="ajuda.php">
                        <span class="icon"><i class="fas fa-question-circle"></i></span>
                        <span class="txt">Ajuda</span>
                    </a>
                </li>
                <li>
                    <a href="parametros.php">
                        <span class="icon"><i class="fas fa-trophy"></i></span>
                        <span class="txt">Parametros</span>
                    </a>
                </li>

                <hr>

                <h1 style="font-size: 24px;">Gerenciamento</h1>


                <li>
                    <a href="banca.php">
                        <span class="icon"><i class="fas fa-university"></i></span>
                        <span class="txt">Bancas</span>
                    </a>
                </li>
                <li>
                    <a href="nivel.php">
                        <span class="icon"><i class="fas fa-graduation-cap"></i></span>
                        <span class="txt">Niveis</span>
                    </a>
                </li>
                <li>
                    <a href="concurso.php">
                        <span class="icon"><i class="fas fa-users"></i></span>
                        <span class="txt">Concurso</span> 
                    </a>
                </li>
                <li>
                    <a href="questao.php">
                        <span class="icon"><i class="fas fa-book"></i></span>
                        <span class="txt">Questões</span>
                    </a>
                </li>
                <li>
                    <a href="grau.php">
                        <span class="icon"><i class="fas fa-chart-line"></i></span>
                        <span class="txt">Grau de dificuldade</span>
                    </a>
                </li>
                <li>
                    <a href="disciplina.php">
                        <span class="icon"><i class="fas fa-book-reader"></i></span>
                        <span class="txt">Disciplina</span>
                    </a>
                </li>
                <li>
                    <a href="duracao.php">
                        <span class="icon"><i class="fas fa-clock"></i></span>
                        <span class="txt">Duração</span>
                    </a>
                </li>
                <li>
                    <a href="instituicao.php">
                        <span class="icon"><i class="fas fa-school"></i></span>
                        <span class="txt">Instituições</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <div class="container">
            <div class="encima">
                <h1 id="titulo">O que você está procurando?</h1>
                <input id="pesquisa" type="text" placeholder="Digite seu texto aqui">
            </div>

            <div class="enbaixo">
                <img src="assets/imagem celular.svg" alt="Imagem celular">
                <img src="assets/imagem noteboooke.svg" alt="Imagem notebook">
            </div>
        </div>
    </div>

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
</body>
</html>
