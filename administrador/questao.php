<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Questões</title>
    <link rel="stylesheet" href="questao.css">
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
                <li><a href="dificuldade.php">Dificuldade</a></li>
                <li><a href="disciplina.php">Disciplinas</a></li>
                <li><a href="duracao.php">Durações</a></li>
                <li><a href="instituicao.php">Instituições</a></li>
                <li><a href="prova.php">Provas</a></li>
                <li><a href="concurso.php">Concursos</a></li>
                <li><a href="questao.php">Questões</a></li>
            </ul>
        </div>
        <style>
            .modal-content {
                width: 800px;
                max-width: 90%;
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
                border-radius: 8px;
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

        <main id="main-container">
            <div id="corpo">
                <h1></h1>
                <?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', 'admin', 'topapirando');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$error_message = '';
$success_message = '';

// Inserir ou atualizar questão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pergunta = mysqli_real_escape_string($conn, $_POST['pergunta']);
    $resposta1 = mysqli_real_escape_string($conn, $_POST['resposta1']);
    $resposta2 = mysqli_real_escape_string($conn, $_POST['resposta2']);
    $resposta3 = mysqli_real_escape_string($conn, $_POST['resposta3']);
    $resposta4 = mysqli_real_escape_string($conn, $_POST['resposta4']);
    $respostacorreta = mysqli_real_escape_string($conn, $_POST['respostacorreta']);
    $dificuldade_cod_dificuldade = (int)$_POST['dificuldade_cod_dificuldade'];
    $disciplina_cod_disciplina = (int)$_POST['disciplina_cod_disciplina'];
    $cod_questao = $_POST['cod_questao'] ?? null;

    // Verificar se a questão já está registrada
    $check_sql = "SELECT * FROM questao WHERE pergunta='$pergunta'";
    if ($cod_questao) {
        $check_sql .= " AND cod_questao != $cod_questao";
    }
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $error_message = "Erro: questão já registrada";
    } else {
        if ($cod_questao) {
            // Atualizar questão
            $sql = "UPDATE questao SET pergunta='$pergunta', resposta1='$resposta1', resposta2='$resposta2', resposta3='$resposta3', resposta4='$resposta4', respostacorreta='$respostacorreta', dificuldade_cod_dificuldade='$dificuldade_cod_dificuldade', disciplina_cod_disciplina='$disciplina_cod_disciplina' WHERE cod_questao=$cod_questao";
        } else {
            // Inserir nova questão
            $sql = "INSERT INTO questao (pergunta, resposta1, resposta2, resposta3, resposta4, respostacorreta, dificuldade_cod_dificuldade, disciplina_cod_disciplina) VALUES ('$pergunta', '$resposta1', '$resposta2', '$resposta3', '$resposta4', '$respostacorreta', '$dificuldade_cod_dificuldade', '$disciplina_cod_disciplina')";
        }

        if ($conn->query($sql) === TRUE) {
            $success_message = "Registro salvo com sucesso!";
        } else {
            $error_message = "Erro: " . $conn->error;
        }
    }
}

// Excluir questão
if (isset($_GET['delete'])) {
    $cod_questao = (int)$_GET['delete'];
    $sql = "DELETE FROM questao WHERE cod_questao=$cod_questao";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Registro excluído com sucesso!";
    } else {
        $error_message = "Erro: " . $conn->error;
    }
}

// Preencher os campos do modal para edição
$cod_questao = $_GET['edit'] ?? null;
$pergunta = '';
$resposta1 = '';
$resposta2 = '';
$resposta3 = '';
$resposta4 = '';
$respostacorreta = '';
$dificuldade_cod_dificuldade = '';
$disciplina_cod_disciplina = '';

if ($cod_questao) {
    $result = $conn->query("SELECT * FROM questao WHERE cod_questao=$cod_questao");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pergunta = $row['pergunta'];
        $resposta1 = $row['resposta1'];
        $resposta2 = $row['resposta2'];
        $resposta3 = $row['resposta3'];
        $resposta4 = $row['resposta4'];
        $respostacorreta = $row['respostacorreta'];
        $dificuldade_cod_dificuldade = $row['dificuldade_cod_dificuldade'];
        $disciplina_cod_disciplina = $row['disciplina_cod_disciplina'];
    }
}
?>

<div class="table-container container-principal">
    <h2>Gerenciar Questões</h2>
    <button class="btn-adicionar" onclick="openAddModal()">Adicionar Nova Questão</button>

    <?php
    // Ajustar a consulta SQL para buscar os dados das tabelas estrangeiras
    $result = $conn->query("
        SELECT q.*, d.tipo_dificuldade, disc.nome as nome_disciplina
        FROM questao q
        JOIN dificuldade d ON q.dificuldade_cod_dificuldade = d.cod_dificuldade
        JOIN disciplina disc ON q.disciplina_cod_disciplina = disc.cod_disciplina
    ");

    if ($result->num_rows > 0) {
        echo "<table id='questaoTable' class='tabela-registros'>";
        echo "<thead><tr><th>Pergunta</th><th>Resposta 1</th><th>Resposta 2</th><th>Resposta 3</th><th>Resposta 4</th><th>Resposta Correta</th><th>Dificuldade</th><th>Disciplina</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['pergunta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta1']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta2']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta3']) . "</td>";
            echo "<td>" . htmlspecialchars($row['resposta4']) . "</td>";
            echo "<td>" . htmlspecialchars($row['respostacorreta']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipo_dificuldade']) . "</td>";  // Mostra o nome da dificuldade
            echo "<td>" . htmlspecialchars($row['nome_disciplina']) . "</td>";  // Mostra o nome da disciplina
            echo "<td class='actions'>";
            echo "<button class='btn-editar' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . ")'><i class='fas fa-edit'></i></button>";
            echo "<button class='btn-excluir' onclick='openModal(\"questao.php?delete=" . $row['cod_questao'] . "\")'><i class='fas fa-trash-alt'></i></button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p class='text-muted text-center'>Nenhum registro encontrado.</p>";
    }
    ?>
</div>


                <!-- Modal de Adicionar/Editar Questão -->
                <div id="add-modal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeAddModal()">&times;</span>
                        <form action="questao.php" method="POST">
                            <input type="hidden" id="cod_questao" name="cod_questao" value="<?php echo htmlspecialchars($cod_questao); ?>">

                            <div class="grid-container">
                                <div class="grid-item">
                                    <label for="pergunta_modal">Pergunta:</label>
                                    <input type="text" id="pergunta_modal" name="pergunta" value="<?php echo htmlspecialchars($pergunta); ?>" placeholder="Preencha a pergunta" required>
                                </div>
                                <div class="grid-item">
                                    <label for="resposta1_modal">Resposta 1:</label>
                                    <input type="text" id="resposta1_modal" name="resposta1" value="<?php echo htmlspecialchars($resposta1); ?>" placeholder="Preencha a resposta 1" required>
                                </div>
                                <div class="grid-item">
                                    <label for="resposta2_modal">Resposta 2:</label>
                                    <input type="text" id="resposta2_modal" name="resposta2" value="<?php echo htmlspecialchars($resposta2); ?>" placeholder="Preencha a resposta 2" required>
                                </div>
                                <div class="grid-item">
                                    <label for="resposta3_modal">Resposta 3:</label>
                                    <input type="text" id="resposta3_modal" name="resposta3" value="<?php echo htmlspecialchars($resposta3); ?>" placeholder="Preencha a resposta 3" required>
                                </div>
                                <div class="grid-item">
                                    <label for="resposta4_modal">Resposta 4:</label>
                                    <input type="text" id="resposta4_modal" name="resposta4" value="<?php echo htmlspecialchars($resposta4); ?>" placeholder="Preencha a resposta 4" required>
                                </div>
                                <div class="grid-item">
                                    <label for="respostacorreta_modal">Resposta Correta:</label>
                                    <input type="text" id="respostacorreta_modal" name="respostacorreta" value="<?php echo htmlspecialchars($respostacorreta); ?>" placeholder="Preencha a resposta correta" required>
                                </div>
                                <div class="grid-item">
                                    <label for="dificuldade_cod_dificuldade">Dificuldade:</label>
                                    <select id="dificuldade_cod_dificuldade" name="dificuldade_cod_dificuldade" required>
                                        <option value="">Selecione uma Dificuldade</option>
                                        <?php
                                        $dificuldades = $conn->query("SELECT * FROM dificuldade");
                                        while ($dificuldade = $dificuldades->fetch_assoc()) {
                                            $selected = (isset($dificuldade_cod_dificuldade) && $dificuldade['cod_dificuldade'] == $dificuldade_cod_dificuldade) ? ' selected' : '';
                                            echo "<option value='" . $dificuldade['cod_dificuldade'] . "'" . $selected . ">" . htmlspecialchars($dificuldade['tipo_dificuldade']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="grid-item">
                                    <label for="disciplina_cod_disciplina">Disciplina:</label>
                                    <select id="disciplina_cod_disciplina" name="disciplina_cod_disciplina" required>
                                        <option value="">Selecione uma Disciplina</option>
                                        <?php
                                        $disciplinas = $conn->query("SELECT * FROM disciplina");
                                        while ($disciplina = $disciplinas->fetch_assoc()) {
                                            $selected = (isset($disciplina_cod_disciplina) && $disciplina['cod_disciplina'] == $disciplina_cod_disciplina) ? ' selected' : '';
                                            echo "<option value='" . $disciplina['cod_disciplina'] . "'" . $selected . ">" . htmlspecialchars($disciplina['nome']) . "</option>";
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
                    document.cookie = name + "=" + (value || "") + expires + "; path=/";
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
                    setCookie("cod_questao", document.getElementById("cod_questao").value, 7);
                    setCookie("pergunta", document.getElementById("pergunta_modal").value, 7);
                    setCookie("resposta1", document.getElementById("resposta1_modal").value, 7);
                    setCookie("resposta2", document.getElementById("resposta2_modal").value, 7);
                    setCookie("resposta3", document.getElementById("resposta3_modal").value, 7);
                    setCookie("resposta4", document.getElementById("resposta4_modal").value, 7);
                    setCookie("respostacorreta", document.getElementById("respostacorreta_modal").value, 7);
                    setCookie("dificuldade_cod_dificuldade", document.getElementById("dificuldade_cod_dificuldade").value, 7);
                    setCookie("disciplina_cod_disciplina", document.getElementById("disciplina_cod_disciplina").value, 7);
                }

                // Função para carregar cookies nos campos do formulário
                function loadCookies() {
                    document.getElementById("cod_questao").value = getCookie("cod_questao") || '';
                    document.getElementById("pergunta_modal").value = getCookie("pergunta") || '';
                    document.getElementById("resposta1_modal").value = getCookie("resposta1") || '';
                    document.getElementById("resposta2_modal").value = getCookie("resposta2") || '';
                    document.getElementById("resposta3_modal").value = getCookie("resposta3") || '';
                    document.getElementById("resposta4_modal").value = getCookie("resposta4") || '';
                    document.getElementById("respostacorreta_modal").value = getCookie("respostacorreta") || '';
                    document.getElementById("dificuldade_cod_dificuldade").value = getCookie("dificuldade_cod_dificuldade") || '';
                    document.getElementById("disciplina_cod_disciplina").value = getCookie("disciplina_cod_disciplina") || '';
                }

                // Função para mostrar o modal de sucesso
                function showSuccess(message) {
                    document.getElementById('sucesso-mensagem').textContent = message;
                    document.getElementById('modal-sucesso').style.display = 'block';
                }

                // Função para fechar os modais
                function closeModal(modalType) {
                    if (modalType === 'sucesso') {
                        document.getElementById('modal-sucesso').style.display = 'none';
                    } else if (modalType === 'erro') {
                        document.getElementById('modal-erro').style.display = 'none';
                    } else {
                        document.getElementById('confirm-modal').style.display = 'none';
                    }
                }

                // Função para abrir o modal de confirmação
                function openModal(url) {
                    document.getElementById('confirm-modal').style.display = 'block';
                    document.getElementById('confirm-delete').onclick = function () {
                        window.location.href = url;
                    };
                }

                // Função para abrir o modal de adição/edição de questão
                function openAddModal() {
                    document.getElementById('add-modal').style.display = 'block';
                }

                // Função para fechar o modal de adição/edição de questão
                function closeAddModal() {
                    document.getElementById('add-modal').style.display = 'none';
                }

                // Função para abrir o modal de edição de questão com os dados carregados
                function openEditModal(data) {
                    document.getElementById('cod_questao').value = data.cod_questao;
                    document.getElementById('pergunta_modal').value = data.pergunta;
                    document.getElementById('resposta1_modal').value = data.resposta1;
                    document.getElementById('resposta2_modal').value = data.resposta2;
                    document.getElementById('resposta3_modal').value = data.resposta3;
                    document.getElementById('resposta4_modal').value = data.resposta4;
                    document.getElementById('respostacorreta_modal').value = data.respostacorreta;
                    document.getElementById('dificuldade_cod_dificuldade').value = data.dificuldade_cod_dificuldade;
                    document.getElementById('disciplina_cod_disciplina').value = data.disciplina_cod_disciplina;
                    openAddModal();
                }

                // Função para limpar o formulário
                function clearForm() {
                    document.getElementById('cod_questao').value = '';
                    document.getElementById('pergunta_modal').value = '';
                    document.getElementById('resposta1_modal').value = '';
                    document.getElementById('resposta2_modal').value = '';
                    document.getElementById('resposta3_modal').value = '';
                    document.getElementById('resposta4_modal').value = '';
                    document.getElementById('respostacorreta_modal').value = '';
                    document.getElementById('dificuldade_cod_dificuldade').selectedIndex = 0;
                    document.getElementById('disciplina_cod_disciplina').selectedIndex = 0;
                }

                // Adicionando funcionalidade aos botões OK dos modais de erro e sucesso
                document.getElementById("ok-btn-erro").onclick = function() {
                    closeModal('erro');
                };
                document.getElementById("ok-btn-sucesso").onclick = function() {
                    closeModal('sucesso');
                };

                // Mostrar mensagens de erro ou sucesso com base nas variáveis PHP
                document.addEventListener('DOMContentLoaded', function() {
                    <?php if ($error_message): ?>
                        document.getElementById('erro-mensagem').textContent = '<?php echo htmlspecialchars($error_message); ?>';
                        document.getElementById('modal-erro').style.display = "block";
                    <?php elseif ($success_message): ?>
                        showSuccess('<?php echo htmlspecialchars($success_message); ?>');
                    <?php endif; ?>
                    
                    // Carregar cookies ao carregar a página
                    loadCookies();
                });

                // Salvar automaticamente os dados quando a página for recarregada ou fechada
                window.addEventListener('beforeunload', function (event) {
                    saveCookies();
                });

                // Salvar os dados sempre que houver mudança nos campos
                document.querySelectorAll('#add-modal input, #add-modal select').forEach(function (element) {
                    element.addEventListener('input', function () {
                        saveCookies();
                    });
                });
                </script>


    </body>
</html>
