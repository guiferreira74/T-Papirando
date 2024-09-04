<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title>
    <link rel="stylesheet" href="inicio.css">
    <style>
        .content-container {
            display: flex;
            justify-content: space-between;
            margin-top: 100px; /* Ajuste conforme necessário */
        }

        #enem, #esa {
            flex: 1; /* Faz com que os elementos se ajustem igualmente */
            text-align: center;
            margin: 0 20px; /* Espaçamento entre os elementos */
        }

        .logo-enem {
            width: 300px;
        }

        #link {
            padding: 20px;
        }
    </style>
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
            <a href="#">Sobre</a>
            <a href="#">Ajuda</a>
            <a href="login.php">Entrar</a>
        </div>
    </header>
    <div class="menu">
        <a href="index.php">Inicio</a>
        <a href="#">Simulados</a>
        <a href="#">Bancas</a> 
        <a href="#">Desempenho</a>
    </div>
    <div class="content-container">
        <div id="enem">
            <h1>Enem</h1> 
            <img class="logo-enem" src="assets/enemlogo.jpg" alt="img" width="400" height="auto">
            <p>
                Para mais informações, acesse o 
                <a href="https://www.gov.br/inep/pt-br/areas-de-atuacao/avaliacao-e-exames-educacionais/enem/provas-e-gabaritos" target="_blank" class="external-link">
                    site oficial do Enem
                </a>.
            </p>
        </div>
        <div id="esa">
            <h1>ESA</h1> 
            <img src="assets/esa.png" alt="img" width="150" height="auto">
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
    </script>
</body>
</html>