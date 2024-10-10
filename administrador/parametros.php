<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parâmetros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="parametros.css">
</head>
<body>  
    <header>
        <div class="interface">
            <div class="controle-navegacao">
                <div class="logo">
                    <img src="assets/logo_papirando_final.svg" alt="Logo">     
                </div><!-- logo -->
            </div><!-- controle-navegacao -->

         

            <div class="informacoes">
                <a href="sobre_adm.php">Sobre</a>
                <a href="ajuda_adm.php">Ajuda</a>
                <a href="sair.php">Sair</a>
            </div>
        </div><!-- interface -->
    </header>  

    <div class="flex">
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
                        <a href="ajuda_adm.php">
                            <span class="icon"><i class="fas fa-question-circle"></i></span>
                            <span class="txt">Ajuda</span>
                        </a>
                    </li>
                    <li>
                        <a href="parametros.php">
                            <span class="icon"><i class="fas fa-trophy"></i></span>
                            <span class="txt">Parâmetros</span>
                        </a>
                    </li>
                    <hr>
                    <h1>Gerenciamento</h1>
                    <li>
                        <a href="banca.php">
                            <span class="icon"><i class="fas fa-university"></i></span>
                            <span class="txt">Bancas</span>
                        </a>
                    </li>
                    <li>
                        <a href="escolaridade.php">
                            <span class="icon"><i class="fas fa-graduation-cap"></i></span>
                            <span class="txt">Escolaridade</span>
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
                        <a href="dificuldade.php">
                            <span class="icon"><i class="fas fa-chart-line"></i></span>
                            <span class="txt">Dificuldade</span>
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

        <div class="container">
            <h1>Parâmetros</h1>
            <form>
                <label for="questoes">Quantidade de Questões:</label>
                <input type="number" id="questoes" name="questoes" required>
                
                <label for="tempo">Tempo (em minutos):</label>
                <input type="number" id="tempo" name="tempo" required>
                
                <div class="button-container">
                    <button class="save-button">Salvar</button>
                    <button class="clear-button">Limpar</button>
                </div>
            </form>
        </div>
        
    </div>
</body>
</html>