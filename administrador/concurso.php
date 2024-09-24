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

                // Inserir ou atualizar registro
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nome = $_POST['nome'];
                    $descricao = $_POST['descricao'];
                    $qtd_questoes = $_POST['qtd_questoes'];
                    $data = $_POST['data'];
                    $vagas = $_POST['vagas'];
                    $nivel_cod_nivel = $_POST['nivel_cod_nivel'];
                    $banca_cod_banca = $_POST['banca_cod_banca'];
                    $instituicao_cod_instituicao = $_POST['instituicao_cod_instituicao'];
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
                    $cod_concurso = $_GET['delete'];
                    $sql = "DELETE FROM concurso WHERE cod_concurso=$cod_concurso";
                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Registro excluído com sucesso!";
                    } else {
                        $error_message = "Erro: " . $conn->error;
                    }
                }

                // Preencher os campos do modal para edição
                $cod_concurso = $_GET['edit'] ?? null;
                $nome = $descricao = $qtd_questoes = $data = $vagas = $nivel_cod_nivel = $banca_cod_banca = $instituicao_cod_instituicao = '';

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

                <div class="text-center mb-3">
                    <button class="btn btn-primary" onclick="openAddModal()">Adicionar Novo Concurso</button>
                </div>

                <div class="table-container">
                    <?php
                    $result = $conn->query("SELECT * FROM concurso");

                    if ($result->num_rows > 0) {
                        echo "<table class='table'>";
                        echo "<tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Qtd. Questões</th>
                                <th>Data</th>
                                <th>Vagas</th>
                                <th>Nível</th>
                                <th>Banca</th>
                                <th>Instituição</th>
                                <th>Ações</th>
                              </tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['qtd_questoes']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['data']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vagas']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nivel_cod_nivel']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['banca_cod_banca']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['instituicao_cod_instituicao']) . "</td>";
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
                    <div id="input">
                        <label for="nome_modal">Nome:</label>
                        <input type="text" id="nome_modal" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome do concurso" required>
                    </div>
                    <div id="input">
                        <label for="descricao_modal">Descrição:</label>
                        <textarea id="descricao_modal" name="descricao" placeholder="Preencha a descrição do concurso" required><?php echo htmlspecialchars($descricao); ?></textarea>
                    </div>
                    <div id="input">
                        <label for="qtd_questoes_modal">Qtd. Questões:</label>
                        <input type="number" id="qtd_questoes_modal" name="qtd_questoes" value="<?php echo htmlspecialchars($qtd_questoes); ?>" required>
                    </div>
                    <div id="input">
                        <label for="data_modal">Data:</label>
                        <input type="date" id="data_modal" name="data" value="<?php echo htmlspecialchars($data); ?>" required>
                    </div>
                    <div id="input">
                        <label for="vagas_modal">Vagas:</label>
                        <input type="number" id="vagas_modal" name="vagas" value="<?php echo htmlspecialchars($vagas); ?>" required>
                    </div>
                    <div id="input">
                        <label for="nivel_cod_nivel_modal">Nível:</label>
                        <input type="text" id="nivel_cod_nivel_modal" name="nivel_cod_nivel" value="<?php echo htmlspecialchars($nivel_cod_nivel); ?>" required>
                    </div>
                    <div id="input">
                        <label for="banca_cod_banca_modal">Banca:</label>
                        <input type="text" id="banca_cod_banca_modal" name="banca_cod_banca" value="<?php echo htmlspecialchars($banca_cod_banca); ?>" required>
                    </div>
                    <div id="input">
                        <label for="instituicao_cod_instituicao_modal">Instituição:</label>
                        <input type="text" id="instituicao_cod_instituicao_modal" name="instituicao_cod_instituicao" value="<?php echo htmlspecialchars($instituicao_cod_instituicao); ?>" required>
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
            // Função para criar um cookie
            function setCookie(name, value, days) {
                const d = new Date();
                d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
                const expires = "expires=" + d.toUTCString();
                document.cookie = name + "=" + value + ";" + expires + ";path=/";
            }

            // Função para obter um cookie
            function getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            // Referência aos modais
            var confirmModal = document.getElementById("confirm-modal");
            var addModal = document.getElementById("add-modal");
            var modalErro = document.getElementById("modal-erro");
            var modalSucesso = document.getElementById("modal-sucesso");
            var confirmButton = document.getElementById("confirm-delete");

            // Função para abrir o modal de adicionar
            function openAddModal() {
                addModal.style.display = "block";
            }

            // Função para fechar o modal de adicionar
            function closeAddModal() {
                addModal.style.display = "none";
            }

            // Fechar o modal se o usuário clicar fora dele
            window.onclick = function(event) {
                if (event.target === confirmModal) {
                    closeModal();
                }
                if (event.target === addModal) {
                    closeAddModal();
                }
                if (event.target === modalErro) {
                    closeModal('erro');
                }
                if (event.target === modalSucesso) {
                    closeModal('sucesso');
                }
            };

            // Função para abrir o modal de confirmação
            function openModal(deleteUrl) {
                confirmModal.style.display = "block";
                confirmButton.onclick = function() {
                    window.location.href = deleteUrl;
                };
            }

            // Limpar formulário
            function clearForm() {
                document.getElementById("nome_modal").value = '';
                document.getElementById("descricao_modal").value = '';
                document.getElementById("qtd_questoes_modal").value = '';
                document.getElementById("data_modal").value = '';
                document.getElementById("vagas_modal").value = '';
                document.getElementById("nivel_cod_nivel_modal").value = '';
                document.getElementById("banca_cod_banca_modal").value = '';
                document.getElementById("instituicao_cod_instituicao_modal").value = '';
            }

            // Mostrar mensagens de erro ou sucesso baseadas nas variáveis PHP
            <?php if ($error_message): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('erro-mensagem').textContent = '<?php echo htmlspecialchars($error_message); ?>';
                    modalErro.style.display = "block";
                });
            <?php elseif ($success_message): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('sucesso-mensagem').textContent = '<?php echo htmlspecialchars($success_message); ?>';
                    modalSucesso.style.display = "block";
                });
            <?php endif; ?>

            // Adicionando funcionalidade aos botões OK dos modais
            document.getElementById("ok-btn-erro").onclick = function() {
                closeModal('erro');
            };
            document.getElementById("ok-btn-sucesso").onclick = function() {
                closeModal('sucesso');
            };

            // Função para abrir o modal de edição
            function openEditModal(data) {
                document.getElementById('cod_concurso').value = data.cod_concurso;
                document.getElementById('nome_modal').value = data.nome;
                document.getElementById('descricao_modal').value = data.descricao;
                document.getElementById('qtd_questoes_modal').value = data.qtd_questoes;
                document.getElementById('data_modal').value = data.data;
                document.getElementById('vagas_modal').value = data.vagas;
                document.getElementById('nivel_cod_nivel_modal').value = data.nivel_cod_nivel;
                document.getElementById('banca_cod_banca_modal').value = data.banca_cod_banca;
                document.getElementById('instituicao_cod_instituicao_modal').value = data.instituicao_cod_instituicao;
                addModal.style.display = "block";
            }

            // Função para fechar modais
            function closeModal(modalType) {
                if (modalType === 'erro') {
                    modalErro.style.display = "none";
                } else if (modalType === 'sucesso') {
                    modalSucesso.style.display = "none";
                } else {
                    confirmModal.style.display = "none";
                }
            }
        </script>
    </body>
</html>
