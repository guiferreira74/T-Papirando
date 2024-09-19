<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Concursos</title>
    <link rel="stylesheet" href="concurso.css"> <!-- Atualizado conforme seu pedido -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header-prc">
        <a href="adm.php">
            <img class="logo" src="assets/logo_papirando_final.svg" alt="topapirando">
        </a>
        <div class="links">
            <a id="sobre" href="sobre.html">Sobre</a>
            <a href="#">Ajuda</a>
            <a href="#">Sair</a>
            <img id="user" src="assets/user.svg" alt="">
        </div>
    </header>

    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-light border-right">
            <div class="sidebar-header p-3">
                <h4>Menu</h4>
            </div>
            <ul class="list-unstyled components">
                <li><a href="adm.php">Início</a></li>
                <li><a href="#">Ajuda</a></li>
                <li><a href="#">Parâmetros</a></li>
                <hr>
                <p>Gerenciar Conteúdo</p>
                <li><a href="banca.php">Bancas</a></li>
                <li><a href="nivel.php">Níveis</a></li>
                <li><a href="grau.php">Graus</a></li>
                <li><a href="disciplina.php">Disciplinas</a></li>
                <li><a href="duracao.php">Durações</a></li>
                <li><a href="instituicao.php">Instituições</a></li>
                <li><a href="simulado.php">Simulados</a></li>
                <li><a href="prova.php">Provas</a></li>
                <li><a href="concurso.php">Concursos</a></li>
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

                // Verificar se o formulário foi enviado
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nome = $conn->real_escape_string($_POST['nome']);
                    $descricao = $conn->real_escape_string($_POST['descricao']);
                    $qtd_questoes = $conn->real_escape_string($_POST['qtd_questoes']);
                    $data = $conn->real_escape_string($_POST['data']);
                    $vagas = $conn->real_escape_string($_POST['vagas']);
                    $nivel_cod_nivel = (int)$_POST['nivel_cod_nivel'];
                    $banca_cod_banca = (int)$_POST['banca_cod_banca'];
                    $instituicao_cod_instituicao = (int)$_POST['instituicao_cod_instituicao'];
                    $cod_concurso = isset($_POST['cod_concurso']) ? (int)$_POST['cod_concurso'] : null;

                    // Verificar se o nome já está registrado
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
                            $sql = "UPDATE concurso SET nome='$nome', descricao='$descricao', qtd_questoes='$qtd_questoes', data='$data', vagas='$vagas', nivel_cod_nivel='$nivel_cod_nivel', banca_cod_banca='$banca_cod_banca', instituicao_cod_instituicao='$instituicao_cod_instituicao' WHERE cod_concurso=$cod_concurso";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO concurso (nome, descricao, qtd_questoes, data, vagas, nivel_cod_nivel, banca_cod_banca, instituicao_cod_instituicao) VALUES ('$nome', '$descricao', '$qtd_questoes', '$data', '$vagas', '$nivel_cod_nivel', '$banca_cod_banca', '$instituicao_cod_instituicao')";
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

                // Formulário para criar/atualizar registros
                $cod_concurso = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
                $nome = '';
                $descricao = '';
                $qtd_questoes = '';
                $data = '';
                $vagas = '';
                $nivel_cod_nivel = 0;
                $banca_cod_banca = 0;
                $instituicao_cod_instituicao = 0;

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
                        $banca_cod_banca = $row['banca_cod_banca'];
                        $instituicao_cod_instituicao = $row['instituicao_cod_instituicao'];
                    }
                }
                ?>

    
                
                <form action="concurso.php" method="POST">
                    <input type="hidden" name="cod_concurso" value="<?php echo htmlspecialchars($cod_concurso); ?>">
                    <div id="input">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome do concurso" title="Preencha o nome do concurso" required>

                        <label for="descricao">Descrição:</label>
                        <input type="text" id="descricao" name="descricao" value="<?php echo htmlspecialchars($descricao); ?>" placeholder="Preencha a descrição do concurso" title="Preencha a descrição do concurso" required>

                        <label for="qtd_questoes">Quantidade de Questões:</label>
                        <input type="text" id="qtd_questoes" name="qtd_questoes" value="<?php echo htmlspecialchars($qtd_questoes); ?>" placeholder="Preencha a quantidade de questões" title="Preencha a quantidade de questões" required>

                        <label for="data">Data:</label>
                        <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($data); ?>" required>

                        <label for="vagas">Vagas:</label>
                        <input type="text" id="vagas" name="vagas" value="<?php echo htmlspecialchars($vagas); ?>" placeholder="Preencha a quantidade de vagas" title="Preencha a quantidade de vagas" required>

                                                <label for="nivel_cod_nivel">Nível:</label>
                        <select id="nivel_cod_nivel" name="nivel_cod_nivel" required title="Selecione o Nível de Escolaridade">
                            <option value="" selected>Selecione o Nível de Escolaridade</option>
                            <?php
                            $result = $conn->query("SELECT cod_nivel, tipo_nivel FROM nivel");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_nivel'] == $nivel_cod_nivel ? 'selected' : '';
                                echo "<option value='{$row['cod_nivel']}' $selected>{$row['tipo_nivel']}</option>";
                            }
                            ?>
                            <option value="add_new">+ novo nível</option>
                        </select>

                        <label for="banca_cod_banca">Banca:</label>
                        <select id="banca_cod_banca" name="banca_cod_banca" required title="Selecione a Banca">
                            <option value="" selected>Selecione a Banca</option>
                            <?php
                            $result = $conn->query("SELECT cod_banca, nome FROM banca");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_banca'] == $banca_cod_banca ? 'selected' : '';
                                echo "<option value='{$row['cod_banca']}' $selected>{$row['nome']}</option>";
                            }
                            ?>
                            <option value="add_new">+ nova banca</option>
                        </select>

                        <label for="instituicao_cod_instituicao">Instituição:</label>
                        <select id="instituicao_cod_instituicao" name="instituicao_cod_instituicao" required title="Selecione a Instituição">
                            <option value="" selected>Selecione a Instituição</option>
                            <?php
                            $result = $conn->query("SELECT cod_instituicao, nome FROM instituicao");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_instituicao'] == $instituicao_cod_instituicao ? 'selected' : '';
                                echo "<option value='{$row['cod_instituicao']}' $selected>{$row['nome']}</option>";
                            }
                            ?>
                            <option value="add_new">+ nova instituição</option>
                        </select>

                        <script>
                            document.getElementById('nivel_cod_nivel').addEventListener('change', function() {
                                if (this.value === 'add_new') {
                                    window.location.href = 'nivel.php'; // Mude para a página do seu formulário
                                }
                            });

                            document.getElementById('banca_cod_banca').addEventListener('change', function() {
                                if (this.value === 'add_new') {
                                    window.location.href = 'banca.php'; // Mude para a página do seu formulário
                                }
                            });

                            document.getElementById('instituicao_cod_instituicao').addEventListener('change', function() {
                                if (this.value === 'add_new') {
                                    window.location.href = 'instituicao.php'; // Mude para a página do seu formulário
                                }
                            });
                        </script>


                    </div>
                    <div class="button-container">
                        <button type="submit" class="save-button">Salvar</button>
                        <button type="reset" class="clear-button">Limpar</button>
                    </div>
                </form>

                <h2></h2>
                <div class="table-container">
    <button id="toggle-concursos" style="background-color: blue; color: white; border: none; padding: 10px 15px; cursor: pointer;">Mostrar Concursos Cadastrados</button>
    <div id="concursos-content" style="display:none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Qtd. Questões</th>
                    <th>Data</th>
                    <th>Vagas</th>
                    <th>Nível</th>
                    <th>Banca</th>
                    <th>Instituição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("
                    SELECT c.cod_concurso, c.nome, c.descricao, c.qtd_questoes, c.data, c.vagas, n.tipo_nivel, b.nome as banca, i.nome as instituicao 
                    FROM concurso c 
                    JOIN nivel n ON c.nivel_cod_nivel = n.cod_nivel 
                    JOIN banca b ON c.banca_cod_banca = b.cod_banca 
                    JOIN instituicao i ON c.instituicao_cod_instituicao = i.cod_instituicao
                ");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['nome']) . "</td>
                        <td>" . htmlspecialchars($row['descricao']) . "</td>
                        <td>" . htmlspecialchars($row['qtd_questoes']) . "</td>
                        <td>" . htmlspecialchars($row['data']) . "</td>
                        <td>" . htmlspecialchars($row['vagas']) . "</td>
                        <td>" . htmlspecialchars($row['tipo_nivel']) . "</td>
                        <td>" . htmlspecialchars($row['banca']) . "</td>
                        <td>" . htmlspecialchars($row['instituicao']) . "</td>
                        <td class='actions'>
                            <a href='concurso.php?edit={$row['cod_concurso']}' class='edit-button' title='Editar'><i class='fas fa-pencil-alt'></i></a>
                            <a href='#' onclick='openModal(\"concurso.php?delete={$row['cod_concurso']}\"); return false;' class='delete-button' title='Excluir'><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

        </main>
        <script>
    document.getElementById("toggle-concursos").addEventListener("click", function() {
        var concursosContent = document.getElementById("concursos-content");
        if (concursosContent.style.display === "none") {
            concursosContent.style.display = "block";
            this.textContent = "Ocultar Concursos Cadastrados"; // Muda o texto do botão
        } else {
            concursosContent.style.display = "none";
            this.textContent = "Mostrar Concursos Cadastrados"; // Muda o texto do botão
        }
    });
</script>

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

<script>
    // Referência ao modal e aos botões
    var confirmModal = document.getElementById("confirm-modal");
    var confirmButton = document.getElementById("confirm-delete");

    // Função para abrir o modal
    function openModal(deleteUrl) {
        confirmModal.style.display = "block";
        confirmButton.onclick = function() {
            window.location.href = deleteUrl;
        };
    }

    // Função para fechar o modal
    function closeModal() {
        confirmModal.style.display = "none";
    }

    // Fechar o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        if (event.target === confirmModal) {
            closeModal();
        }
    };

    // Adicionar eventos de clique para os botões
    document.querySelector(".close-btn").onclick = closeModal;
    document.querySelector(".btn-cancel").onclick = closeModal;
</script>

          <!-- Modais de Sucesso e Erro -->
<div id="modal-erro" class="modal modal-erro">
    <div class="modal-content modal-content-erro">
        <span class="close-btn close-btn-erro" onclick="closeModal('erro')">&times;</span>
        <p id="erro-mensagem">Erro!</p>
        <button id="ok-btn-erro" class="ok-btn ok-btn-erro">OK</button>
    </div>
</div>

<div id="modal-sucesso" class="modal modal-sucesso">
    <div class="modal-content modal-content-sucesso">
        <span class="close-btn close-btn-sucesso" onclick="closeModal('sucesso')">&times;</span>
        <p id="sucesso-mensagem">Sucesso!</p>
        <button id="ok-btn-sucesso" class="ok-btn ok-btn-sucesso">OK</button>
    </div>
</div>



            <script>
                // Obter elementos dos modais e botões
                var modalErro = document.getElementById("modal-erro");
                var modalSucesso = document.getElementById("modal-sucesso");

                var okBtnErro = document.getElementById("ok-btn-erro");
                var okBtnSucesso = document.getElementById("ok-btn-sucesso");

                // Função para mostrar um modal específico
                function showModal(type, message) {
                    var modal = type === 'erro' ? modalErro : modalSucesso;
                    var messageElem = modal.querySelector('p');
                    messageElem.textContent = message;
                    modal.style.display = "block";
                }

                // Função para esconder o modal
                function closeModal(type) {
                    var modal = type === 'erro' ? modalErro : modalSucesso;
                    modal.style.display = "none";
                }

                // Adicionar eventos de clique para os botões OK
                okBtnErro.onclick = function() {
                    closeModal('erro');
                };
                okBtnSucesso.onclick = function() {
                    closeModal('sucesso');
                };

                // Fechar o modal se o usuário clicar fora dele
                window.onclick = function(event) {
                    if (event.target == modalErro || event.target == modalSucesso) {
                        closeModal(event.target === modalErro ? 'erro' : 'sucesso');
                    }
                };

                // Mostrar mensagens de erro ou sucesso baseadas nas variáveis PHP
                <?php if ($error_message): ?>
                    document.addEventListener('DOMContentLoaded', function() {
                        showModal('erro', '<?php echo htmlspecialchars($error_message); ?>');
                    });
                <?php elseif ($success_message): ?>
                    document.addEventListener('DOMContentLoaded', function() {
                        showModal('sucesso', '<?php echo htmlspecialchars($success_message); ?>');
                    });
                <?php endif; ?>
            </script>
    </div>
</body>
</html>
