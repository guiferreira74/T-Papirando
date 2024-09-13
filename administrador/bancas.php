<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title>
    <link rel="stylesheet" href="bancas.css">
</head>
<body>
    <header class="header-prc">
        <a href="index.php">
            <img class="logo" src="assets/logo.svg" alt="topapirando">
        </a>
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
        <a href="#">Bancas</a> 
        <a href="#" class="restricted">Desempenho</a>
    </div>
    <div class="content-container">
        <div id="enem">
            <h1>Enem</h1> 
            <p>
                Para mais informações, acesse o 
                <a href="https://www.gov.br/inep/pt-br/areas-de-atuacao/avaliacao-e-exames-educacionais/enem/provas-e-gabaritos" target="_blank" class="external-link">
                    site oficial do Enem
                </a>.
            </p>
        </div>
        <div id="esa">
            <h1>ESA</h1> 
            <p id="link">
                Para mais informações, acesse o 
                <a href="https://esa.eb.mil.br/index.php/pt/concurso/provas-anteriores" target="_blank" class="external-link">
                    site oficial da ESA
                </a>.
            </p>
        </div>
    </div>

    <script>
        // Seleciona todos os links externos
        document.querySelectorAll('.external-link').forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault(); // Impede o clique padrão
                // Exibe o alerta personalizado
                if (confirm("Você será direcionado para outra página. Clique em 'OK' para continuar ou 'Cancelar' para permanecer na página atual.")) {
                    // Se o usuário clicar em 'OK', abre o link em uma nova guia
                    window.open(link.href, '_blank');
                }
            });
        });

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
