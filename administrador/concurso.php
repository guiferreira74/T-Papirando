<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Concursos</title>
    <link rel="stylesheet" href="concurso.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header-prc">
        <a href="adm.php">
            <img class="logo" src="assets/logo_papirando_final.svg" alt="topapirando">
        </a>
        <div class="links">
            <a id="sobre" href="sobre_adm.php">Sobre</a>
            <a href="ajuda_adm.php">Ajuda</a>
            <a href="sair.php">Sair</a>
            <img id="user" src="assets/user.svg" alt="">
        </div>
    </header>

    <div class="d-flex">
        <div id="sidebar" class="bg-light border-right">
            <div class="sidebar-header p-3">
                <h4>Menu</h4>
            </div>
            <ul class="list-unstyled components">
                <li><a href="adm.php">Início</a></li>
                <li><a href="ajuda_adm.php">Ajuda</a></li>
                <li><a href="parametros.php">Parâmetros</a></li>
                <hr>
                <p>Gerenciar Conteúdo</p>
                <li><a href="banca.php">Bancas</a></li>
                <li><a href="nivel.php">Níveis</a></li>
                <li><a href="grau.php">Graus</a></li>
                <li><a href="disciplina.php">Disciplinas</a></li>
                <li><a href="duracao.php">Durações</a></li>
                <li><a href="instituicao.php">Instituições</a></li>
                <li><a href="prova.php">Provas</a></li>
                <li><a href="concurso.php" class="active">Concursos</a></li>
                <li><a href="questao.php">Questões</a></li>
            </ul>
        </div>

        <main id="main-container">
            <div id="corpo">
                <h1>Gerenciar Concursos</h1>

                <?php
                // Conexão com o banco de dados
                $conn = new mysqli('localhost', 'root', 'admin', 'topapirando');

                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }

                $error_message = '';
                $success_message = '';

                // Inserir ou atualizar registro
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
                    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
                    $qtd_questoes = mysqli_real_escape_string($conn, $_POST['qtd_questoes']);
                    $data = mysqli_real_escape_string($conn, $_POST['data']);
                    $vagas = mysqli_real_escape_string($conn, $_POST['vagas']);
                    $nivel_cod_nivel = (int)$_POST['nivel_cod_nivel'];
                    $instituicao_cod_instituicao = (int)$_POST['instituicao_cod_instituicao'];
                    $banca_cod_banca = (int)$_POST['banca_cod_banca'];
                    $cod_concurso = $_POST['cod_concurso'] ?? null;

                    // Verificar se o concurso já está registrado
                    $check_sql = "SELECT * FROM concurso WHERE nome='$nome'";
                    if ($cod_concurso) {
                        $check_sql .= " AND cod_concurso != $cod_concurso";
                    }
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Erro: concurso já registrado";
                    } else {
                        if ($cod_concurso) {
                            // Atualizar registro
                            $sql = "UPDATE concurso SET nome='$nome', descricao='$descricao', qtd_questoes='$qtd_questoes', data='$data', vagas='$vagas', nivel_cod_nivel='$nivel_cod_nivel', instituicao_cod_instituicao='$instituicao_cod_instituicao', banca_cod_banca='$banca_cod_banca' WHERE cod_concurso=$cod_concurso";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO concurso (nome, descricao, qtd_questoes, data, vagas, nivel_cod_nivel, instituicao_cod_instituicao, banca_cod_banca) VALUES ('$nome', '$descricao', '$qtd_questoes', '$data', '$vagas', '$nivel_cod_nivel', '$instituicao_cod_instituicao', '$banca_cod_banca')";
                        }

                        if ($conn->query($sql) === TRUE) {
                            $success_message = "Registro salvo com sucesso!";
                        } else {
                            $error_message = "Erro: " . $conn->error;
                        }
                    }
                }

                // Excluir registro
                if (isset($_GET['delete'])) {
                    $cod_concurso = (int)$_GET['delete'];
                    $sql = "DELETE FROM concurso WHERE cod_concurso=$cod_concurso";
                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Registro excluído com sucesso!";
                    } else {
                        $error_message = "Erro: " . $conn->error;
                    }
                }

                // Preencher os campos do modal para edição
                $cod_concurso = $_GET['edit'] ?? null;
                $nome = '';
                $descricao = '';
                $qtd_questoes = '';
                $data = '';
                $vagas = '';
                $nivel_cod_nivel = '';
                $instituicao_cod_instituicao = '';
                $banca_cod_banca = '';

                if ($cod_concurso) {
                    $result = $conn->query("SELECT * FROM concurso WHERE cod_concurso=$cod_concurso");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $nome = $row['nome'];
                        $descricao = $row['descricao'];
                        $qtd_questoes = $row['qtd_questoes'];
                        $data = $row['data'];
                        $vagas = $row['vagas'];
                        $nivel_cod_nivel = $row['nivel_cod_nivel'];
                        $instituicao_cod_instituicao = $row['instituicao_cod_instituicao'];
                        $banca_cod_banca = $row['banca_cod_banca'];
                    }
                }
                ?>

                <div class="text-center mb-3">
                    <button class="btn btn-primary" onclick="openAddModal()">Adicionar Novo Concurso</button>
                </div>

                <div class="table-container">
                    <?php
                    $result = $conn->query("SELECT c.*, n.tipo_nivel, i.nome AS nome_instituicao, b.nome AS nome_banca FROM concurso c
                                            JOIN nivel n ON c.nivel_cod_nivel = n.cod_nivel
                                            JOIN instituicao i ON c.instituicao_cod_instituicao = i.cod_instituicao
                                            JOIN banca b ON c.banca_cod_banca = b.cod_banca");

                    if ($result->num_rows > 0) {
                        echo "<table class='table'>";
                        echo "<tr><th>Nome do Concurso</th><th>Descrição</th><th>Qtd Questões</th><th>Data</th><th>Vagas</th><th>Nível</th><th>Instituição</th><th>Banca</th><th>Ações</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['qtd_questoes']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['data']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vagas']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tipo_nivel']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome_instituicao']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome_banca']) . "</td>";
                            echo "<td class='actions'>";
                            echo "<a class='edit-button' href='#' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . "); return false;' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
                            echo "<a class='delete-button' href='#' onclick='openModal(\"concurso.php?delete=" . $row['cod_concurso'] . "\"); return false;' title='Excluir'><i class='fas fa-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Nenhum registro encontrado.</p>";
                    }
                    ?>
                </div>
            </div>
        </main>

        <!-- Modal de Adicionar/Editar Concurso -->
        <div id="add-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddModal()">&times;</span>
        <form action="concurso.php" method="POST">
            <input type="hidden" id="cod_concurso" name="cod_concurso" value="<?php echo htmlspecialchars($cod_concurso); ?>">

            <div class="grid-container">
                <!-- Primeira linha com 3 inputs -->
                <div class="grid-item">
                    <label for="nome_modal">Nome do Concurso:</label>
                    <input type="text" id="nome_modal" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome do concurso" required>
                </div>
                <div class="grid-item">
                    <label for="descricao_modal">Descrição:</label>
                    <input type="text" id="descricao_modal" name="descricao" value="<?php echo htmlspecialchars($descricao); ?>" placeholder="Preencha a descrição do concurso" required>
                </div>
                <div class="grid-item">
                    <label for="qtd_questoes_modal">Quantidade de Questões:</label>
                    <input type="text" id="qtd_questoes_modal" name="qtd_questoes" value="<?php echo htmlspecialchars($qtd_questoes); ?>" placeholder="Preencha a quantidade de questões" required>
                </div>

                <!-- Segunda linha com 3 inputs -->
                <div class="grid-item">
                    <label for="data_modal">Data:</label>
                    <input type="date" id="data_modal" name="data" value="<?php echo htmlspecialchars($data); ?>" required>
                </div>
                <div class="grid-item">
                    <label for="vagas_modal">Vagas:</label>
                    <input type="text" id="vagas_modal" name="vagas" value="<?php echo htmlspecialchars($vagas); ?>" placeholder="Preencha a quantidade de vagas" required>
                </div>
                <div class="grid-item">
                    <label for="nivel_cod_nivel">Nível:</label>
                    <select id="nivel_cod_nivel" name="nivel_cod_nivel" required>
                        <option value="">Selecione um nível</option>
                        <?php
                        $nivels = $conn->query("SELECT * FROM nivel");
                        while ($nivel = $nivels->fetch_assoc()) {
                            $selected = (isset($nivel_cod_nivel) && $nivel['cod_nivel'] == $nivel_cod_nivel) ? ' selected' : '';
                            echo "<option value='" . $nivel['cod_nivel'] . "'" . $selected . ">" . htmlspecialchars($nivel['tipo_nivel']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Terceira linha com 2 inputs -->
                <div class="grid-item">
                    <label for="instituicao_cod_instituicao">Instituição:</label>
                    <select id="instituicao_cod_instituicao" name="instituicao_cod_instituicao" required>
                        <option value="">Selecione uma instituição</option>
                        <?php
                        $instituicoes = $conn->query("SELECT * FROM instituicao");
                        while ($instituicao = $instituicoes->fetch_assoc()) {
                            $selected = (isset($instituicao_cod_instituicao) && $instituicao['cod_instituicao'] == $instituicao_cod_instituicao) ? ' selected' : '';
                            echo "<option value='" . $instituicao['cod_instituicao'] . "'" . $selected . ">" . htmlspecialchars($instituicao['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="grid-item">
                    <label for="banca_cod_banca">Banca:</label>
                    <select id="banca_cod_banca" name="banca_cod_banca" required>
                        <option value="">Selecione uma banca</option>
                        <?php
                        $bancas = $conn->query("SELECT * FROM banca");
                        while ($banca = $bancas->fetch_assoc()) {
                            $selected = (isset($banca_cod_banca) && $banca['cod_banca'] == $banca_cod_banca) ? ' selected' : '';
                            echo "<option value='" . $banca['cod_banca'] . "'" . $selected . ">" . htmlspecialchars($banca['nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="save-button">Salvar</button>
                <button type="button" class="clear-button" onclick="clearForm()">Limpar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-content {
        width: 800px; /* Aumentando a largura do modal */
        max-width: 90%; /* Responsividade para telas menores */
        padding: 20px;
        background-color: white;
        border-radius: 10px;
    }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 10px;
    }

    .grid-item {
        display: flex;
        flex-direction: column;
    }

    input[type="text"], input[type="date"], select {
        border-radius: 8px; /* Adicionando borda arredondada aos inputs */
        padding: 10px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
        font-size: 14px;
    }

    .button-container {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
    }

    button {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
    }

   
</style>



    <!-- Modal de confirmação -->
    <div id="confirm-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <div class="modal-body">
                    <p>Você tem certeza que quer excluir?</p>
                    <div class="button-container">
                        <button id="confirm-delete" class="btn-delete">Excluir</button>
                        <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modais de Sucesso e Erro -->
<div id="modal-erro" class="modal modal-erro">
    <div class="modal-content modal-content-erro">
        <span class="close-btn" onclick="closeModal('erro')">&times;</span>
        <p id="erro-mensagem">Erro!</p>
        <button id="ok-btn-erro" class="ok-btn ok-btn-erro">OK</button>
    </div>
</div>

<div id="modal-sucesso" class="modal modal-sucesso">
    <div class="modal-content modal-content-sucesso">
        <span class="close-btn" onclick="closeModal('sucesso')">&times;</span>
        <p id="sucesso-mensagem">Sucesso!</p>
        <button id="ok-btn-sucesso" class="ok-btn ok-btn-sucesso">OK</button>
    </div>
</div>


<script>
    // Função para definir um cookie
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/"; // path=/ para acessibilidade em todas as páginas
        console.log(`Cookie definido: ${name}=${value}`); // Log para verificar cookies
    }

    // Função para obter o valor de um cookie
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length));
        }
        return null;
    }

    // Função para salvar cookies
    function saveCookies() {
        if (!validateForm()) return;

        setCookie("cod_concurso", document.getElementById("cod_concurso").value, 7);
        setCookie("nome", document.getElementById("nome_modal").value, 7);
        setCookie("descricao", document.getElementById("descricao_modal").value, 7);
        setCookie("qtd_questoes", document.getElementById("qtd_questoes_modal").value, 7);
        setCookie("data", document.getElementById("data_modal").value, 7);
        setCookie("vagas", document.getElementById("vagas_modal").value, 7);
        setCookie("nivel_cod_nivel", document.getElementById("nivel_cod_nivel").value, 7);
        setCookie("instituicao_cod_instituicao", document.getElementById("instituicao_cod_instituicao").value, 7);
        setCookie("banca_cod_banca", document.getElementById("banca_cod_banca").value, 7);
    }

    // Função para carregar cookies nos campos do formulário
    function loadCookies() {
        document.getElementById("cod_concurso").value = getCookie("cod_concurso") || '';
        document.getElementById("nome_modal").value = getCookie("nome") || '';
        document.getElementById("descricao_modal").value = getCookie("descricao") || '';
        document.getElementById("qtd_questoes_modal").value = getCookie("qtd_questoes") || '';
        document.getElementById("data_modal").value = getCookie("data") || '';
        document.getElementById("vagas_modal").value = getCookie("vagas") || '';
        document.getElementById("nivel_cod_nivel").value = getCookie("nivel_cod_nivel") || '';
        document.getElementById("instituicao_cod_instituicao").value = getCookie("instituicao_cod_instituicao") || '';
        document.getElementById("banca_cod_banca").value = getCookie("banca_cod_banca") || '';
        console.log('Cookies carregados:', document.cookie); // Log para verificar cookies
    }

    // Função para abrir o modal de sucesso
    function showSuccess(message) {
        document.getElementById('sucesso-mensagem').textContent = message;
        document.getElementById('modal-sucesso').style.display = 'block';
    }

    // Função para fechar modais
    function closeModal(modalType) {
        if (modalType === 'sucesso') {
            document.getElementById('modal-sucesso').style.display = 'none';
        } else if (modalType === 'erro') {
            document.getElementById('modal-erro').style.display = 'none';
        } else {
            document.getElementById('confirm-modal').style.display = 'none';
        }
    }

    // Adicionando funcionalidade aos botões OK dos modais
    document.getElementById("ok-btn-erro").onclick = function() {
        closeModal('erro');
    };
    document.getElementById("ok-btn-sucesso").onclick = function() {
        closeModal('sucesso');
    };

    // Mostrar mensagens de erro ou sucesso baseadas nas variáveis PHP
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($error_message): ?>
            document.getElementById('erro-mensagem').textContent = '<?php echo htmlspecialchars($error_message); ?>';
            document.getElementById('modal-erro').style.display = "block";
        <?php elseif ($success_message): ?>
            showSuccess('<?php echo htmlspecialchars($success_message); ?>');
        <?php endif; ?>
        
        loadCookies(); // Carregar cookies ao carregar a página
    });

    // Função para limpar o formulário
    function clearForm() {
        document.getElementById('cod_concurso').value = '';
        document.getElementById('nome_modal').value = '';
        document.getElementById('descricao_modal').value = '';
        document.getElementById('qtd_questoes_modal').value = '';
        document.getElementById('data_modal').value = '';
        document.getElementById('vagas_modal').value = '';
        document.getElementById('nivel_cod_nivel').selectedIndex = 0;
        document.getElementById('instituicao_cod_instituicao').selectedIndex = 0;
        document.getElementById('banca_cod_banca').selectedIndex = 0;
    }

    // Função para abrir o modal de edição
    function openEditModal(data) {
        document.getElementById('cod_concurso').value = data.cod_concurso;
        document.getElementById('nome_modal').value = data.nome;
        document.getElementById('descricao_modal').value = data.descricao;
        document.getElementById('qtd_questoes_modal').value = data.qtd_questoes;
        document.getElementById('data_modal').value = data.data;
        document.getElementById('vagas_modal').value = data.vagas;
        document.getElementById('nivel_cod_nivel').value = data.nivel_cod_nivel;
        document.getElementById('instituicao_cod_instituicao').value = data.instituicao_cod_instituicao;
        document.getElementById('banca_cod_banca').value = data.banca_cod_banca;
        openAddModal();
    }

    // Função para abrir o modal de confirmação
    function openModal(url) {
        document.getElementById('confirm-modal').style.display = 'block';
        document.getElementById('confirm-delete').onclick = function () {
            window.location.href = url;
        };
    }

    // Função para abrir o modal de adição
    function openAddModal() {
        document.getElementById('add-modal').style.display = 'block';
    }

    // Função para fechar o modal de adição
    function closeAddModal() {
        document.getElementById('add-modal').style.display = 'none';
    }
</script>


</body>
</html>
