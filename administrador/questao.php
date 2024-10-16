<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Questões</title>
    <link rel="stylesheet" href="questao.css"> <!-- Link para o CSS específico de questões -->
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
            <a href="ajuda_adm.php">Ajuda</a>
            <a href="sair.php">Sair</a>
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
                <li><a href="ajuda_adm.php">Ajuda</a></li>
                <li><a href="parametros.php">Parâmetros</a></li>
                <hr>
                <p>Gerenciar Conteúdo</p>
                <li><a href="banca.php">Bancas</a></li>
                <li><a href="dificuldade.php">Graus</a></li>
                <li><a href="disciplina.php">Disciplinas</a></li>
                <li><a href="duracao.php">Durações</a></li>
                <li><a href="instituicao.php">Instituições</a></li>
                <li><a href="prova.php">Provas</a></li>
                <li><a href="concurso.php">Concursos</a></li>
                <li><a href="questao.php">Questões</a></li>
            </ul>
        </div>

        <main id="main-container">
    <div id="corpo">
        <h1>Gerenciar Questões</h1>
        <?php
        // Conexão com o banco de dados
        $conn = new mysqli('localhost', 'root', 'admin', 'topapirando');

        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Inicializar variáveis
        $error_message = '';
        $success_message = '';
        $qtd_disciplina = isset($_POST['qtd_disciplina']) ? $_POST['qtd_disciplina'] : '';

        // Inserir ou atualizar registro
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pergunta = $_POST['pergunta'];
            $resposta1 = $_POST['resposta1'];
            $resposta2 = $_POST['resposta2'];
            $resposta3 = $_POST['resposta3'];
            $resposta4 = $_POST['resposta4'];
            $respostacorreta = $_POST['respostacorreta'];
            $qtd_disciplina = $_POST['qtd_disciplina'];
            $prova_cod_prova = $_POST['prova_cod_prova'];
            $concurso_cod_concurso = $_POST['concurso_cod_concurso'];
            $dificuldade_cod_dificuldade = $_POST['dificuldade_cod_dificuldade'];
            $cod_questao = $_POST['cod_questao'] ?? null;

            // Verificar se a pergunta já está registrada
            $check_sql = "SELECT * FROM questao WHERE pergunta='$pergunta'";
            if ($cod_questao) {
                $check_sql .= " AND cod_questao != $cod_questao";
            }
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                $error_message = "Erro: questão já registrada";
            } else {
                if ($cod_questao) {
                    // Atualizar registro
                    $sql = "UPDATE questao SET 
                        pergunta='$pergunta', 
                        resposta1='$resposta1', 
                        resposta2='$resposta2', 
                        resposta3='$resposta3', 
                        resposta4='$resposta4', 
                        respostacorreta='$respostacorreta', 
                        qtd_disciplina='$qtd_disciplina', 
                        prova_cod_prova='$prova_cod_prova', 
                        concurso_cod_concurso='$concurso_cod_concurso',
                        dificuldade_cod_dificuldade='$dificuldade_cod_dificuldade'
                    WHERE cod_questao=$cod_questao";
                } else {
                    // Inserir novo registro
                    $sql = "INSERT INTO questao (pergunta, resposta1, resposta2, resposta3, resposta4, respostacorreta, qtd_disciplina, prova_cod_prova, concurso_cod_concurso, dificuldade_cod_dificuldade) VALUES ('$pergunta', '$resposta1', '$resposta2', '$resposta3', '$resposta4', '$respostacorreta', '$qtd_disciplina', '$prova_cod_prova', '$concurso_cod_concurso', '$dificuldade_cod_dificuldade')";
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
            $cod_questao = $_GET['delete'];
            $sql = "DELETE FROM questao WHERE cod_questao=$cod_questao";
            if ($conn->query($sql) === TRUE) {
                $success_message = "Registro excluído com sucesso!";
            } else {
                $error_message = "Erro: " . $conn->error;
            }
        }

        $cod_questao = $_GET['edit'] ?? null;
        $pergunta = '';
        $resposta1 = '';
        $resposta2 = '';
        $resposta3 = '';
        $resposta4 = '';
        $respostacorreta = '';
        $qtd_disciplina = '';
        $prova_cod_prova = '';
        $concurso_cod_concurso = '';
        $dificuldade_cod_dificuldade = '';

        if ($cod_questao) {
            $result = $conn->query("SELECT * FROM questao WHERE cod_questao=$cod_questao");
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $pergunta = $row['pergunta'] ?? '';
                $resposta1 = $row['resposta1'] ?? '';
                $resposta2 = $row['resposta2'] ?? '';
                $resposta3 = $row['resposta3'] ?? '';
                $resposta4 = $row['resposta4'] ?? '';
                $respostacorreta = $row['respostacorreta'] ?? '';
                $qtd_disciplina = $row['qtd_disciplina'] ?? '';
                $prova_cod_prova = $row['prova_cod_prova'] ?? '';
                $concurso_cod_concurso = $row['concurso_cod_concurso'] ?? '';
                $dificuldade_cod_dificuldade = $row['dificuldade_cod_dificuldade'] ?? '';
            }
        }
        ?>

        <form action="questao.php" method="POST">
            <input type="hidden" name="cod_questao" value="<?php echo htmlspecialchars($cod_questao); ?>">
            <div id="input">
                <label for="pergunta">Pergunta:</label>
                <input type="text" id="pergunta" name="pergunta" value="<?php echo htmlspecialchars($pergunta); ?>" placeholder="Preencha a pergunta" required>

                <label for="resposta1">Resposta 1:</label>
                <input type="text" id="resposta1" name="resposta1" value="<?php echo htmlspecialchars($resposta1); ?>" placeholder="Preencha a resposta 1" required>

                <label for="resposta2">Resposta 2:</label>
                <input type="text" id="resposta2" name="resposta2" value="<?php echo htmlspecialchars($resposta2); ?>" placeholder="Preencha a resposta 2" required>

                <label for="resposta3">Resposta 3:</label>
                <input type="text" id="resposta3" name="resposta3" value="<?php echo htmlspecialchars($resposta3); ?>" placeholder="Preencha a resposta 3" required>

                <label for="resposta4">Resposta 4:</label>
                <input type="text" id="resposta4" name="resposta4" value="<?php echo htmlspecialchars($resposta4); ?>" placeholder="Preencha a resposta 4" required>

                <label for="resposta_correta">Resposta Correta:</label>
                <input type="text" id="resposta_correta" name="respostacorreta" value="<?php echo htmlspecialchars($respostacorreta); ?>" placeholder="Preencha a resposta correta" required>

                <label for="qtd_disciplina">Quantidade de Disciplina:</label>
                <input type="text" id="qtd_disciplina" name="qtd_disciplina" value="<?php echo htmlspecialchars($qtd_disciplina); ?>" placeholder="Preencha a quantidade" required>

                <label for="prova_cod_prova">Prova:</label>
                <select id="prova_cod_prova" name="prova_cod_prova" required>
                    <option value="" selected>Selecione a Prova</option>
                    <?php
                    $result = $conn->query("SELECT cod_prova, nome FROM prova");
                    while ($row = $result->fetch_assoc()) {
                        $selected = $row['cod_prova'] == $prova_cod_prova ? 'selected' : '';
                        echo "<option value='{$row['cod_prova']}' $selected>{$row['nome']}</option>";
                    }
                    ?>
                </select>

                <label for="concurso_cod_concurso">Concurso:</label>
                <select id="concurso_cod_concurso" name="concurso_cod_concurso" required>
                    <option value="" selected>Selecione o Concurso</option>
                    <?php
                    $result = $conn->query("SELECT cod_concurso, nome FROM concurso");
                    while ($row = $result->fetch_assoc()) {
                        $selected = $row['cod_concurso'] == $concurso_cod_concurso ? 'selected' : '';
                        echo "<option value='{$row['cod_concurso']}' $selected>{$row['nome']}</option>";
                    }
                    ?>
                </select>

                <label for="dificuldade_cod_dificuldade">Dificuldade:</label>
                <select id="dificuldade_cod_dificuldade" name="dificuldade_cod_dificuldade" required>
                    <option value="" selected>Selecione a Dificuldade</option>
                    <?php
                    $result = $conn->query("SELECT cod_dificuldade, tipo_dificuldade FROM dificuldade");
                    while ($row = $result->fetch_assoc()) {
                        $selected = $row['cod_dificuldade'] == $dificuldade_cod_dificuldade ? 'selected' : '';
                        echo "<option value='{$row['cod_dificuldade']}' $selected>{$row['tipo_dificuldade']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="button-container">
                <button type="submit" class="save-button">Salvar</button>
                <button type="reset" class="clear-button">Limpar</button>
            </div>
        </form>

        <div id="questoes-content">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pergunta</th>
                        <th>Resposta 1</th>
                        <th>Resposta 2</th>
                        <th>Resposta 3</th>
                        <th>Resposta 4</th>
                        <th>Resposta Correta</th>
                        <th>Disciplina</th>
                        <th>Qtd. Disciplina</th>
                        <th>Concurso</th>
                        <th>Dificuldade</th>
                        <th>Prova</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT q.cod_questao, q.pergunta, q.resposta1, q.resposta2, q.resposta3, q.resposta4, q.respostacorreta, 
                        q.qtd_disciplina, p.nome as prova, c.nome as concurso, d.tipo_dificuldade 
                        FROM questao q 
                        JOIN prova p ON q.prova_cod_prova = p.cod_prova
                        JOIN concurso c ON q.concurso_cod_concurso = c.cod_concurso
                        JOIN dificuldade d ON q.dificuldade_cod_dificuldade = d.cod_dificuldade");

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['pergunta']}</td>
                            <td>{$row['resposta1']}</td>
                            <td>{$row['resposta2']}</td>
                            <td>{$row['resposta3']}</td>
                            <td>{$row['resposta4']}</td>
                            <td>{$row['respostacorreta']}</td>
                            <td>{$row['qtd_disciplina']}</td>
                            <td>{$row['concurso']}</td>
                            <td>{$row['tipo_dificuldade']}</td>
                            <td>{$row['prova']}</td>
                            <td class='actions'>
                                <a href='questao.php?edit={$row['cod_questao']}' class='edit-button' title='Editar'><i class='fas fa-pencil-alt'></i></a>
                                <a href='#' onclick='openModal(\"questao.php?delete={$row['cod_questao']}\"); return false;' class='delete-button' title='Excluir'><i class='fas fa-trash-alt'></i></a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>




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