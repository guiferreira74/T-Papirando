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
    <title>Bem-vindo Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adm.css">
<body>
    <div id="conteudo-header">
        <header class="header-prc">
           <a href="adm.php"><img class="logo" src="assets/logo_papirando_final.svg"/> </a>
            <div class="search-bar">
                <div class="links">
                    <a id="sobre" href="#">Sobre</a>
                    <a href="#">Ajuda</a>
                    <span>Olá, <?php echo htmlspecialchars($admin_nome); ?>!</span>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">Sair</a>
                    <img id="user" src="assets/user.svg" alt="">
                </div>
            </div>
        </header>
    </div>

    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-light border-right">
            <div class="sidebar-header p-3">
                <h4>Menu</h4>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="adm.php">Início</a>
                </li>
                <li>
                    <a href="#">Ajuda</a>
                </li>
                <li>
                    <a href="#">Parâmetros </a>
                </li>
                <hr>
                <p>Gerenciar Conteúdo</p>
                <li>
                    <a href="banca.php">Bancas</a>
                </li>
                <li>
                    <a href="nivel.php">Níveis</a>
                </li>
                <li>
                    <a href="grau.php">Graus</a>
                </li>
                <li>
                    <a href="disciplina.php">Disciplinas</a>
                </li>
                <li>
                    <a href="duracao.php">Durações</a>
                </li>
                <li>
                    <a href="instituicao.php">Instituições</a>
                </li>
                <li>
                    <a href="simulado.php">Simulados</a>
                </li>
                <li>
                    <a href="prova.php">Provas</a>
                </li>
                <li>
                    <a href="concurso.php">Concursos</a>
                </li>
                <li>
                    <a href="questao.php">Questões</a>
                </li>
            </ul>
        </div>

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
</html>
