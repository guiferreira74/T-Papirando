<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Simulado</title>
    <link rel="stylesheet" href="banca.css">
</head>
<body>
    <header class="header-prc">
        <a href="topapirando.php">
            <img class="logo" src="assets/logo.svg" alt="topapirando">
        </a>
        <div class="search-bar">
            <input type="text" placeholder="Digite seu texto aqui">
        </div>
        <div class="links">
            <a href="">Sobre</a>
            <a href="">Ajuda</a>
            <a href="">Entrar</a>
        </div>
    </header>
    <div class="menu">
        <a href="">Inicio</a>
        <a href="simulado.php">Simulados</a>
        <a href="">Bancas</a>
        <a href="">Desempenho</a>
    </div>

    <div id="main-container">
        <div id="corpo">
            <h1>Cadastro de Simulado</h1>

            <form action="simulado.php" method="post">
                <div id="input">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" placeholder="Preencha o nome do simulado" title="Preencha o nome do simulado" required>

                    <label for="nivel">Nível:</label>
                    <select id="nivel" name="nivel_cod_nivel" required>
                        <!-- Opções serão carregadas via PHP -->
                        <?php
                        // Conectar ao banco de dados
                        $conn = new mysqli("localhost", "root", "admin", "topapirando");
                        if ($conn->connect_error) {
                            die("Falha na conexão: " . $conn->connect_error);
                        }

                        // Obter dados da tabela nivel
                        $result = $conn->query("SELECT cod_nivel, tipo_nivel FROM nivel");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['cod_nivel']}'>{$row['tipo_nivel']}</option>";
                        }
                        $conn->close();
                        ?>
                    </select>

                    <label for="disciplina">Disciplina:</label>
                    <select id="disciplina" name="disciplina_cod_disciplina" required>
                        <!-- Opções serão carregadas via PHP -->
                        <?php
                        // Conectar ao banco de dados
                        $conn = new mysqli("localhost", "root", "admin", "topapirando");
                        if ($conn->connect_error) {
                            die("Falha na conexão: " . $conn->connect_error);
                        }

                        // Obter dados da tabela disciplina
                        $result = $conn->query("SELECT cod_disciplina, nome FROM disciplina");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['cod_disciplina']}'>{$row['nome']}</option>";
                        }
                        $conn->close();
                        ?>
                    </select>

                    <label for="prova">Prova:</label>
                    <select id="prova" name="prova_cod_prova" required>
                        <!-- Opções serão carregadas via PHP -->
                        <?php
                        // Conectar ao banco de dados
                        $conn = new mysqli("localhost", "root", "admin", "topapirando");
                        if ($conn->connect_error) {
                            die("Falha na conexão: " . $conn->connect_error);
                        }

                        // Obter dados da tabela prova
                        $result = $conn->query("SELECT cod_prova, nome FROM prova");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['cod_prova']}'>{$row['nome']}</option>";
                        }
                        $conn->close();
                        ?>
                    </select>

                    <label for="concurso">Concurso:</label>
                    <select id="concurso" name="concurso_cod_concurso" required>
                        <!-- Opções serão carregadas via PHP -->
                        <?php
                        // Conectar ao banco de dados
                        $conn = new mysqli("localhost", "root", "admin", "topapirando");
                        if ($conn->connect_error) {
                            die("Falha na conexão: " . $conn->connect_error);
                        }

                        // Obter dados da tabela concurso
                        $result = $conn->query("SELECT cod_concurso, nome FROM concurso");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['cod_concurso']}'>{$row['nome']}</option>";
                        }
                        $conn->close();
                        ?>
                    </select>

                    <label for="duracao">Duração:</label>
                    <select id="duracao" name="duracao_cod_duracao" required>
                        <!-- Opções serão carregadas via PHP -->
                        <?php
                        // Conectar ao banco de dados
                        $conn = new mysqli("localhost", "root", "admin", "topapirando");
                        if ($conn->connect_error) {
                            die("Falha na conexão: " . $conn->connect_error);
                        }

                        // Obter dados da tabela duracao
                        $result = $conn->query("SELECT cod_duracao, tempo FROM duracao");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['cod_duracao']}'>{$row['tempo']}</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="button-container">
                    <button id="save" type="submit">Salvar</button>
                    <button id="limpar" type="reset">Limpar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
