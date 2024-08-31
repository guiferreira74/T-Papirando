<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div id="conteudo-header">
        <header class="header-prc">
           <a href="index.php"><img class="logo" src="assets/logo.svg" alt="topapirando"></a>
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
            <a href="bancas.php">Bancas</a>
            <a href="#">Desempenho</a>
        </div>
    
     </div>

     <main>

        <div class="grid-duplo">
            <div class="esquerda">
                    <img id="login" src="login azul.svg" alt="">
                    <h1>já tem conta?</h1>
                    <p>Informe seus dados para acessa-lá </p>
                    
                    <input id="input-email" type="text" placeholder="E-mail">
                    <input id="input-senha" type="password" placeholder="Senha">

                    <div id="checa">  
                        <input  type="checkbox" id="checar">
                        <label for="checar">Lembrar meus dados</label>
                    </div>
                 
                    <button id="button-esquerda">Acessar Conta</button>
            </div>

            <div class="direita">

                <img id="Log" src="login verde.svg" alt="">
                <h1>Novo usuario</h1>
                <p>Criar uma conta é fácil! Informe seus dados e uma senha para <br> aproveitar todos os beneficios de ter uma conta.</p>
                <a href="cadastro.php"><button id="button-direita">Crie sua Conta</button></a>

            </div>
        </div>

     </main>
</body>
</html>

