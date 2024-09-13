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
            <img class="logo" src="assets/logo.svg" alt="topapirando">
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
                <h1>Gerenciar Questões</h1>

                <!-- PHP Code for Database Connection and CRUD Operations -->
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
    $pergunta = $_POST['pergunta'];
    $resposta1 = $_POST['resposta1'];
    $resposta2 = $_POST['resposta2'];
    $resposta3 = $_POST['resposta3'];
    $resposta4 = $_POST['resposta4'];
    $respostacorreta = $_POST['respostacorreta']; // Corrigido
    $disciplina_cod_disciplina = $_POST['disciplina_cod_disciplina'];
    $concurso_cod_concurso = $_POST['concurso_cod_concurso'];
    $nivel_cod_nivel = $_POST['nivel_cod_nivel'];
    $banca_cod_banca = $_POST['banca_cod_banca'];
    $prova_cod_prova = $_POST['prova_cod_prova'];
    $grau_cod_grau = $_POST['grau_cod_grau'];
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
            $sql = "UPDATE questao SET pergunta='$pergunta', resposta1='$resposta1', resposta2='$resposta2', resposta3='$resposta3', resposta4='$resposta4', respostacorreta='$respostacorreta', disciplina_cod_disciplina='$disciplina_cod_disciplina', concurso_cod_concurso='$concurso_cod_concurso', nivel_cod_nivel='$nivel_cod_nivel', banca_cod_banca='$banca_cod_banca', prova_cod_prova='$prova_cod_prova', grau_cod_grau='$grau_cod_grau' WHERE cod_questao=$cod_questao";
        } else {
            // Inserir novo registro
            $sql = "INSERT INTO questao (pergunta, resposta1, resposta2, resposta3, resposta4, respostacorreta, disciplina_cod_disciplina, concurso_cod_concurso, nivel_cod_nivel, banca_cod_banca, prova_cod_prova, grau_cod_grau) VALUES ('$pergunta', '$resposta1', '$resposta2', '$resposta3', '$resposta4', '$respostacorreta', '$disciplina_cod_disciplina', '$concurso_cod_concurso', '$nivel_cod_nivel', '$banca_cod_banca', '$prova_cod_prova', '$grau_cod_grau')";
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
$respostacorreta = ''; // Inicialize como string vazia
$disciplina_cod_disciplina = '';
$concurso_cod_concurso = '';
$nivel_cod_nivel = '';
$banca_cod_banca = '';
$prova_cod_prova = '';
$grau_cod_grau = '';

if ($cod_questao) {
    $result = $conn->query("SELECT * FROM questao WHERE cod_questao=$cod_questao");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pergunta = $row['pergunta'] ?? '';
        $resposta1 = $row['resposta1'] ?? '';
        $resposta2 = $row['resposta2'] ?? '';
        $resposta3 = $row['resposta3'] ?? '';
        $resposta4 = $row['resposta4'] ?? '';
        $respostacorreta = $row['respostacorreta'] ?? ''; // Garante que a variável está definida
        $disciplina_cod_disciplina = $row['disciplina_cod_disciplina'] ?? '';
        $concurso_cod_concurso = $row['concurso_cod_concurso'] ?? '';
        $nivel_cod_nivel = $row['nivel_cod_nivel'] ?? '';
        $banca_cod_banca = $row['banca_cod_banca'] ?? '';
        $prova_cod_prova = $row['prova_cod_prova'] ?? '';
        $grau_cod_grau = $row['grau_cod_grau'] ?? '';
    }
}

                ?>

                <?php if ($error_message): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
                <?php elseif ($success_message): ?>
                    <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
                <?php endif; ?>

                <form action="questao.php" method="POST">
                    <input type="hidden" name="cod_questao" value="<?php echo htmlspecialchars($cod_questao); ?>">
                    <div id="input">
                        <label for="pergunta">Pergunta:</label>
                        <input type="text" id="pergunta" name="pergunta" value="<?php echo htmlspecialchars($pergunta); ?>" placeholder="Preencha a pergunta" title="Preencha a pergunta" required>
                        <label for="resposta1">Resposta 1:</label>
                        <input type="text" id="resposta1" name="resposta1" value="<?php echo htmlspecialchars($resposta1); ?>" placeholder="Preencha a resposta 1" title="Preencha a resposta 1" required>

                        <label for="resposta2">Resposta 2:</label>
                        <input type="text" id="resposta2" name="resposta2" value="<?php echo htmlspecialchars($resposta2); ?>" placeholder="Preencha a resposta 2" title="Preencha a resposta 2" required>

                        <label for="resposta3">Resposta 3:</label>
                        <input type="text" id="resposta3" name="resposta3" value="<?php echo htmlspecialchars($resposta3); ?>" placeholder="Preencha a resposta 3" title="Preencha a resposta 3" required>

                        <label for="resposta4">Resposta 4:</label>
                        <input type="text" id="resposta4" name="resposta4" value="<?php echo htmlspecialchars($resposta4); ?>" placeholder="Preencha a resposta 4" title="Preencha a resposta 4" required>

                        <label for="resposta_correta">Resposta Correta:</label>
                        <input type="text" id="resposta_correta" name="respostacorreta" value="<?php echo htmlspecialchars($respostacorreta); ?>" placeholder="Preencha a resposta correta" title="Preencha a resposta correta" required>




                        <label for="disciplina_cod_disciplina">Disciplina:</label>
                        <select id="disciplina_cod_disciplina" name="disciplina_cod_disciplina" required>
                            <?php
                            $result = $conn->query("SELECT cod_disciplina, nome FROM disciplina");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_disciplina'] == $disciplina_cod_disciplina ? 'selected' : '';
                                echo "<option value='{$row['cod_disciplina']}' $selected>{$row['nome']}</option>";
                            }
                            ?>
                        </select>

                        <label for="concurso_cod_concurso">Concurso:</label>
                        <select id="concurso_cod_concurso" name="concurso_cod_concurso" required>
                            <?php
                            $result = $conn->query("SELECT cod_concurso, nome FROM concurso");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_concurso'] == $concurso_cod_concurso ? 'selected' : '';
                                echo "<option value='{$row['cod_concurso']}' $selected>{$row['nome']}</option>";
                            }
                            ?>
                        </select>

                        <label for="nivel_cod_nivel">Nível:</label>
                        <select id="nivel_cod_nivel" name="nivel_cod_nivel" required>
                            <?php
                            $result = $conn->query("SELECT cod_nivel, tipo_nivel FROM nivel");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_nivel'] == $nivel_cod_nivel ? 'selected' : '';
                                echo "<option value='{$row['cod_nivel']}' $selected>{$row['tipo_nivel']}</option>";
                            }
                            ?>
                        </select>

                        <label for="banca_cod_banca">Banca:</label>
                        <select id="banca_cod_banca" name="banca_cod_banca" required>
                            <?php
                            $result = $conn->query("SELECT cod_banca, nome FROM banca");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_banca'] == $banca_cod_banca ? 'selected' : '';
                                echo "<option value='{$row['cod_banca']}' $selected>{$row['nome']}</option>";
                            }
                            ?>
                        </select>

                        <label for="prova_cod_prova">Prova:</label>
                        <select id="prova_cod_prova" name="prova_cod_prova" required>
                            <?php
                            $result = $conn->query("SELECT cod_prova, nome FROM prova");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_prova'] == $prova_cod_prova ? 'selected' : '';
                                echo "<option value='{$row['cod_prova']}' $selected>{$row['nome']}</option>";
                            }
                            ?>
                        </select>

                        <label for="grau_cod_grau">Grau:</label>
                        <select id="grau_cod_grau" name="grau_cod_grau" required>
                            <?php
                            $result = $conn->query("SELECT cod_grau, tipo_grau FROM grau");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_grau'] == $grau_cod_grau ? 'selected' : '';
                                echo "<option value='{$row['cod_grau']}' $selected>{$row['tipo_grau']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="save-button">Salvar</button>
                        <button type="reset" class="clear-button">Limpar</button>
                    </div>
                </form>
                <h2></h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Pergunta</th>
            <th>Resposta 1</th>
            <th>Resposta 2</th>
            <th>Resposta 3</th>
            <th>Resposta 4</th>
            <th>Resposta Correta</th> <!-- Nova coluna adicionada -->
            <th>Disciplina</th>
            <th>Concurso</th>
            <th>Nível</th>
            <th>Banca</th>
            <th>Prova</th>
            <th>Grau</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
       $result = $conn->query("SELECT q.cod_questao, q.pergunta, q.resposta1, q.resposta2, q.resposta3, q.resposta4, q.respostacorreta, d.nome as disciplina, c.nome as concurso, n.tipo_nivel, b.nome as banca, p.nome as prova, g.tipo_grau FROM questao q JOIN disciplina d ON q.disciplina_cod_disciplina = d.cod_disciplina JOIN concurso c ON q.concurso_cod_concurso = c.cod_concurso JOIN nivel n ON q.nivel_cod_nivel = n.cod_nivel JOIN banca b ON q.banca_cod_banca = b.cod_banca JOIN prova p ON q.prova_cod_prova = p.cod_prova JOIN grau g ON q.grau_cod_grau = g.cod_grau");

       while ($row = $result->fetch_assoc()) {
           $resposta_correta = '';
       
           switch ($row['respostacorreta']) {
               case 'resposta1':
                   $resposta_correta = $row['resposta1'];
                   break;
               case 'resposta2':
                   $resposta_correta = $row['resposta2'];
                   break;
               case 'resposta3':
                   $resposta_correta = $row['resposta3'];
                   break;
               case 'resposta4':
                   $resposta_correta = $row['resposta4'];
                   break;
           }
       
           echo "<tr>
               <td>{$row['pergunta']}</td>
               <td>{$row['resposta1']}</td>
               <td>{$row['resposta2']}</td>
               <td>{$row['resposta3']}</td>
               <td>{$row['resposta4']}</td>
               <td>{$row['respostacorreta']}</td>
               <td>{$row['disciplina']}</td>
               <td>{$row['concurso']}</td>
               <td>{$row['tipo_nivel']}</td>
               <td>{$row['banca']}</td>
               <td>{$row['prova']}</td>
               <td>{$row['tipo_grau']}</td>
               <td class='actions'>
                   <a href='questao.php?edit={$row['cod_questao']}' class='edit-button' title='Editar'><i class='fas fa-pencil-alt'></i></a>
                   <a href='#' onclick='openModal(\"questao.php?delete={$row['cod_questao']}\"); return false;' class='delete-button' title='Excluir'><i class='fas fa-trash-alt'></i></a>
               </td>
           </tr>";
       }
       
        ?>
    </tbody>
</table>

<!-- Modal de confirmação -->
<div id="confirm-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <p>Você tem certeza que quer excluir?</p>
        <button id="confirm-delete">Excluir</button>
        <button onclick="closeModal()">Cancelar</button>
    </div>
</div>

<script>
    var modal = document.getElementById("confirm-modal");
    var confirmButton = document.getElementById("confirm-delete");

    function openModal(deleteUrl) {
        modal.style.display = "block";
        confirmButton.onclick = function() {
            window.location.href = deleteUrl;
        };
    }

    function closeModal() {
        modal.style.display = "none";
    }

    // Fechar o modal se o usuário clicar fora do conteúdo
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    };
</script>
</body>
</html>