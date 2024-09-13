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

                <?php if ($error_message): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
                <?php elseif ($success_message): ?>
                    <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
                <?php endif; ?>
                
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

                        <label for="instituicao_cod_instituicao">Instituição:</label>
                        <select id="instituicao_cod_instituicao" name="instituicao_cod_instituicao" required>
                            <?php
                            $result = $conn->query("SELECT cod_instituicao, nome FROM instituicao");
                            while ($row = $result->fetch_assoc()) {
                                $selected = $row['cod_instituicao'] == $instituicao_cod_instituicao ? 'selected' : '';
                                echo "<option value='{$row['cod_instituicao']}' $selected>{$row['nome']}</option>";
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
                <table>
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
                                <td>{$row['nome']}</td>
                                <td>{$row['descricao']}</td>
                                <td>{$row['qtd_questoes']}</td>
                                <td>{$row['data']}</td>
                                <td>{$row['vagas']}</td>
                                <td>{$row['tipo_nivel']}</td>
                                <td>{$row['banca']}</td>
                                <td>{$row['instituicao']}</td>
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
        </main>

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
    </div>
</body>
</html>
