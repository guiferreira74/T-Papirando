<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós</title>
    <link rel="stylesheet" href="sobre.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
            <a href="index.php"><img class="logo" src="assets/logo.svg" alt="topapirando"></a>
            <div class="search-bar">
                <input type="text" placeholder="Digite seu texto aqui">  
            </div>
            <div class="links">
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="login.php">Entrar</a>
            </div>
        </header>
        <div class="menu">
            <a href="index.php">Início</a>
            <a href="#" class="restricted">Simulados</a>
            <a href="bancas.php">Bancas</a>
            <a href="#" class="restricted">Desempenho</a>
        </div>
    </div>
    
    <div class="container">
        <div class="encima">
            <div class="dev">
                <h1>Desenvolvedores</h1>
                <ul>
                    <li>Luca Kaly da Cunha</li>
                    <li>Breno Soares Francisco</li>
                    <li>Marcos Antonio</li>
                    <li>Guilherme Ferreira</li>
                </ul>
            </div>
            <img class="grupo" src="assets/grupo.svg" alt="Grupo">
        </div>

        <div class="enbaixo">
            <h2 id="img-enbaixo">FAETEC</h2>
            <div class="text">
                <h3>Créditos e Agradecimentos</h3>
                <p>Gostaríamos de agradecer primeiramente a Deus, por nos dar força e disposição para nos levantar todos os dias. Agradecemos também nosso professor orientador Gustavo Mendonça por toda ajuda e esforço dedicado a nós por todo o nosso percurso de aprendizado.</p>
                <p>Agradecemos a Professora Lidiana Silva por toda disponibilidade e atenção para tirar dúvidas e reforço dado a nós durante a realização do nosso trabalho de conclusão de curso.</p>
                <p>Agradecemos também à diretora da FAETEC CVT Nilópolis, Patrícia Monteiro, pelo grande apoio não só em nosso projeto, mas pela dedicação em estar todos os dias procurando o melhor para seus alunos.</p>
                <p>Agradecemos também a todos os profissionais da instituição que trabalham todos os dias para que nosso ambiente escolar fique o melhor possível.</p>
            </div>
        </div>
    </div>

    <script>
        // Exemplo de controle de acesso
        document.addEventListener("DOMContentLoaded", function() {
            var loggedIn = false; // Substitua isso pela sua lógica de verificação de login
            
            // Se estiver logado, remova a classe de restrição dos links
            if (loggedIn) {
                document.querySelectorAll('.menu a.restricted').forEach(link => {
                    link.classList.remove('restricted');
                });
            }
        });
    </script>
</body>
</html>
