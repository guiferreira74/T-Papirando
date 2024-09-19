<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Provas</title>
    <link rel="stylesheet" href="simulado.css">
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
                    <a href="#">Parâmetros</a>
                </li>
                <hr>
                <p>Gerenciar Conteudo</p>
                <li>
                    <a href="banca.php">Bancas</a>
                </li>
                <li>
                    <a href="nivel.php">Niveis</a>
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

    <main id="main-container">
        <div id="corpo">
            <h1>Gerenciar Provas</h1>

            <?php
            // Conexão com o banco de dados
            $servername = "localhost";
            $username = "root";
            $password = "admin";
            $dbname = "topapirando";

            // Cria a conexão
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica se há erros na conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            $error_message = '';
            $success_message = '';
            
            // Inserir ou atualizar registro
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $acao = $_POST['acao'] ?? '';
                $cod_prova = $_POST['cod_prova'] ?? null;
                $nome = $_POST['nome'];
                $tempo = $_POST['tempo'];
                $banca_cod_banca = $_POST['banca_cod_banca'] ?? '';
            
                // Verificar se o registro já existe
                $sql_check = "SELECT * FROM prova WHERE nome='$nome' AND banca_cod_banca='$banca_cod_banca' AND cod_prova != '$cod_prova'";
                $result_check = $conn->query($sql_check);
            
                if ($result_check->num_rows > 0) {
                    $error_message = "Já existe uma prova com este nome para a banca selecionada.";
                } else {
                    // Validar formato de tempo
                    if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $tempo)) {
                        $error_message = "Formato de tempo inválido. Use HH:MM:SS.";
                    } else {
                        if ($acao == 'inserir') {
                            // Validação básica
                            if (!empty($nome) && !empty($banca_cod_banca)) {
                                $sql = "INSERT INTO prova (nome, tempo, banca_cod_banca) VALUES ('$nome', '$tempo', '$banca_cod_banca')";
                                if ($conn->query($sql) === TRUE) {
                                    $success_message = "Prova inserida com sucesso!";
                                } else {
                                    $error_message = "Erro: " . $conn->error;
                                }
                            } else {
                                $error_message = "Por favor, insira todos os campos.";
                            }
                        } elseif ($acao == 'alterar') {
                            // Atualizar registro
                            if (!empty($cod_prova) && !empty($nome) && !empty($banca_cod_banca)) {
                                $sql = "UPDATE prova SET nome='$nome', tempo='$tempo', banca_cod_banca='$banca_cod_banca' WHERE cod_prova='$cod_prova'";
                                if ($conn->query($sql) === TRUE) {
                                    $success_message = "Prova alterada com sucesso!";
                                } else {
                                    $error_message = "Erro: " . $conn->error;
                                }
                            } else {
                                $error_message = "Por favor, insira todos os campos.";
                            }
                        }
                    }
                }
            }
            // Excluir registro
            if (isset($_GET['delete'])) {
                $cod_prova = $_GET['delete'];
                $sql = "DELETE FROM prova WHERE cod_prova=$cod_prova";
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Prova excluída com sucesso!";
                } else {
                    $error_message = "Erro: " . $conn->error;
                }
            }

            // Formulário para criar/atualizar registros
            $cod_prova = $_GET['edit'] ?? null;
            $nome = '';
            $tempo = '';
            $banca_cod_banca = '';

            if ($cod_prova) {
                $result = $conn->query("SELECT * FROM prova WHERE cod_prova=$cod_prova");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $nome = $row['nome'];
                    $tempo = $row['tempo'];
                    $banca_cod_banca = $row['banca_cod_banca'];
                }
            }
            ?>


            <form action="prova.php" method="POST">
                <input type="hidden" name="cod_prova" value="<?php echo htmlspecialchars($cod_prova); ?>">
                <input type="hidden" name="acao" value="<?php echo $cod_prova ? 'alterar' : 'inserir'; ?>">
                <div id="input">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da prova" title="Preencha o nome da prova" required>

                    <label for="tempo">Tempo (HH:MM:SS):</label>
                    <input type="text" id="tempo" name="tempo" value="<?php echo htmlspecialchars($tempo); ?>" placeholder="Preencha o tempo (HH:MM:SS)" title="Preencha o tempo" required>

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
                    <script>
                    document.getElementById('banca_cod_banca').addEventListener('change', function() {
                        if (this.value === 'add_new') {
                            window.location.href = 'banca.php'; // Mude para a página do seu formulário
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
    <button id="toggle-table" style="background-color: blue; color: white; border: none; padding: 10px 15px; cursor: pointer;">Mostrar Provas Cadastradas</button>
    <div id="table-content" style="display:none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tempo</th>
                    <th>Banca</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT p.cod_prova, p.nome, p.tempo, b.nome as banca FROM prova p JOIN banca b ON p.banca_cod_banca = b.cod_banca");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['nome']) . "</td>
                        <td>" . htmlspecialchars($row['tempo']) . "</td>
                        <td>" . htmlspecialchars($row['banca']) . "</td>
                        <td class='actions'>
                            <a href='prova.php?edit={$row['cod_prova']}' class='edit-button' title='Editar'><i class='fas fa-pencil-alt'></i></a>
                            <a href='#' onclick='openModal(\"prova.php?delete={$row['cod_prova']}\"); return false;' class='delete-button' title='Excluir'><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        </div>

    </main>
    <script>
    document.getElementById("toggle-table").addEventListener("click", function() {
        var tableContent = document.getElementById("table-content");
        if (tableContent.style.display === "none") {
            tableContent.style.display = "block";
            this.textContent = "Ocultar Provas Cadastradas"; // Muda o texto do botão
        } else {
            tableContent.style.display = "none";
            this.textContent = "Mostrar Provas Cadastradas"; // Muda o texto do botão
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
</body>
</html>
