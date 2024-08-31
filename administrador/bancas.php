<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title>
    <link rel="stylesheet" href="inicio.css">
</head>
<body>
    <header class="header-prc">
        <a href="index.php">
            <a href="index.php"><img class="logo" src="assets/logo.svg" alt="topapirando"></a>
        </a>
        <div class="search-bar">
            <input type="text" placeholder="Digite seu texto aqui">  
        </div>
        <div class="links">
            <a href="">Sobre</a>
            <a href="">Ajuda</a>
            <a href="login.php">Entrar</a>
        </div>
    </header>
    <div class="menu">
        <a href="index.php">Inicio</a>
        <a href="">Simulados</a>
        <a href="">Bancas</a> 
        <a href="">Desempenho</a>
    </div>
    <div id="enem" style="text-align: center" margin-top="50px";>
            <h1>Enem</h1> 
            <img class="logo-enem" src="assets/enemlogo.jpg" alt="img" width="400" height="auto">
        <p>
            Para mais informações, acesse o 
            <a href="https://www.gov.br/inep/pt-br/areas-de-atuacao/avaliacao-e-exames-educacionais/enem/provas-e-gabaritos" target="_blank">
                site oficial do Enem
            </a>.
        </p>
    </div>
    <div id="esa" style="text-align: center">
            <h1>ESA</h1> 
            <img src="assets/esa.png" alt="img" width="150" height="auto">
        <p id="link">
            Para mais informações, acesse o 
            <a  href=https://esa.eb.mil.br/index.php/pt/concurso/provas-anteriores target="_blank">
                site oficial da ESA
            </a>.
        </p>
    </div>;

    <style>
        #enem{
            margin-top: 100px;
            
        }
        .logo-enem{
            width: 300px;
        }


        #esa{
            margin-top: 100px;
        }

        #link{
            padding: 20px;
        }
    </style>
</body>
</html>
