<?php
session_start();

// Verifique se o administrador está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../administrador/login.php");
    exit();
}

// Recupera o nome do administrador da sessão
$admin_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Administrador';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Bancas</title>
    <link rel="stylesheet" href="banca.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<header>
    <div class="interface">
        <!-- Botão para abrir a barra lateral -->
        <div class="controle-navegacao">
            <div class="button">
                <button id="toggle-sidebar" class="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="logo">
                <img src="assets/logo_papirando_final.svg" alt="Logo">
            </div>
        </div>

        <div class="informacoes">
            <a href="sobre_adm.php">Sobre</a>
            <a href="ajuda_adm.php">Ajuda</a>
            <span class="mensagem-boas-vindas">Olá, <?php echo htmlspecialchars($admin_nome); ?>!</span>
            <a href="#" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">Sair</a>
        </div>
    </div>
</header>

<nav>
    <div id="sidebar">
        <ul>
            <li class="item-menu">
                <a href="adm.php">
                    <span class="icon"><i class="fas fa-home"></i></span>
                    <span class="txt">Início</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="ajuda_adm.php">
                    <span class="icon"><i class="fas fa-question-circle"></i></span>
                    <span class="txt">Ajuda</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="parametros.php">
                    <span class="icon"><i class="fas fa-trophy"></i></span>
                    <span class="txt">Parametros</span>
                </a>
            </li>

            <hr>

            <h1 id="gr">Gerenciamento</h1>

            <li class="item-menu">
                <a href="banca.php">
                    <span class="icon"><i class="fas fa-university"></i></span>
                    <span class="txt">Bancas</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="concurso.php">
                    <span class="icon"><i class="fas fa-users"></i></span>
                    <span class="txt">Concurso</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="questao.php">
                    <span class="icon"><i class="fas fa-book"></i></span>
                    <span class="txt">Questões</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="dificuldade.php">
                    <span class="icon"><i class="fas fa-chart-line"></i></span>
                    <span class="txt">Dificuldade</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="disciplina.php">
                    <span class="icon"><i class="fas fa-book-reader"></i></span>
                    <span class="txt">Disciplina</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="duracao.php">
                    <span class="icon"><i class="fas fa-clock"></i></span>
                    <span class="txt">Duração</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="instituicao.php">
                    <span class="icon"><i class="fas fa-school"></i></span>
                    <span class="txt">Instituições</span>
                </a>
            </li>
        </ul>
    </div>
</nav>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSidebarButton = document.getElementById('toggle-sidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    // Alterna a visibilidade da sidebar ao clicar no botão de toggle
    toggleSidebarButton.addEventListener('click', function() {
        sidebar.classList.toggle('closed');
        mainContent.classList.toggle('sidebar-closed');
    });
});


</script>

    
        <main id="main-container">
            <div id="corpo">
               

                <?php
                // Conexão com o banco de dados
                $conn = new mysqli('localhost', 'root', 'admin', 'topapirando');

                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }

                $error_message = '';
                $success_message = '';

                // Diretório para upload
                $uploadPath = 'uploads/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Inserir ou atualizar registro
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nome = $_POST['nome'];
                    $link = $_POST['link'];
                    $cod_banca = $_POST['cod_banca'] ?? null;
                    $upload = $_FILES['upload'] ?? null;
                    $uploadFile = null;

                    // Verificar se a banca já está registrada
                    $check_sql = "SELECT * FROM banca WHERE nome='$nome'";
                    if ($cod_banca) {
                        $check_sql .= " AND cod_banca != $cod_banca";
                    }
                    $check_result = $conn->query($check_sql);

                    if ($check_result->num_rows > 0) {
                        $error_message = "Erro: banca já registrada";
                    } else {
                        if ($upload && $upload['error'] === UPLOAD_ERR_OK) {
                            // Mover arquivo para o diretório de uploads
                            $uploadFile = $uploadPath . basename($upload['name']);
                            if (!move_uploaded_file($upload['tmp_name'], $uploadFile)) {
                                $error_message = "Erro ao mover o arquivo.";
                            }
                        }

                        if ($cod_banca) {
                            // Atualizar registro
                            $sql = "UPDATE banca SET nome='$nome', link='$link'";
                            if ($uploadFile) {
                                $sql .= ", upload='$uploadFile'";
                            }
                            $sql .= " WHERE cod_banca=$cod_banca";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO banca (nome, link, upload) VALUES ('$nome', '$link', " . ($uploadFile ? "'$uploadFile'" : "NULL") . ")";
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
                    $cod_banca = $_GET['delete'];
                    $sql = "DELETE FROM banca WHERE cod_banca=$cod_banca";
                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Registro excluído com sucesso!";
                    } else {
                        $error_message = "Erro: " . $conn->error;
                    }
                }

                // Preencher os campos do modal para edição
                $cod_banca = $_GET['edit'] ?? null;
                $nome = '';
                $link = '';

                if ($cod_banca) {
                    $result = $conn->query("SELECT * FROM banca WHERE cod_banca=$cod_banca");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $nome = $row['nome'];
                        $link = $row['link'];
                    }
                }
                ?>

<div class="text-center mb-3">
    <!-- Removido o botão 'Adicionar Nova Banca' -->
</div>

<div class="table-container container-principal">
    <h2>Gerenciar Bancas</h2>
    <button class="btn-adicionar" onclick="openAddModal()">Adicionar Nova Banca</button>

    <?php
    $result = $conn->query("SELECT * FROM banca");

    if ($result->num_rows > 0) {
        echo "<table id='bancaTable' class='tabela-registros'>";
        echo "<thead><tr><th>Nome da Banca</th><th>Link</th><th>Imagem</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td><a href='" . htmlspecialchars($row['link']) . "' target='_blank'>" . htmlspecialchars($row['link']) . "</a></td>";
            echo "<td><img src='" . htmlspecialchars($row['upload']) . "' alt='Imagem da Banca' style='width:50px;'></td>";
            echo "<td class='actions'>";
            // Botão de adicionar, editar e excluir com o novo layout
            echo "<button class='btn-editar' onclick='openEditModal(" . htmlspecialchars(json_encode($row)) . ")'><i class='fas fa-edit'></i></button>";
            echo "<button class='btn-excluir' onclick='openModal(\"banca.php?delete=" . $row['cod_banca'] . "\")'><i class='fas fa-trash-alt'></i></button>";
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

         <!-- Modal de Adicionar/Editar Banca -->
        <div id="add-modal" class="modal" style="overflow: hidden;">
            <div class="modal-content">
                <span class="close-btn" onclick="closeAddModal()">&times;</span>
                <form action="banca.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="cod_banca" name="cod_banca" value="<?php echo htmlspecialchars($cod_banca); ?>">
                    <div id="input">
                        <label for="nome_modal">Nome da Banca:</label>
                        <input type="text" id="nome_modal" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Preencha o nome da banca" required>
                    </div>
                    <div id="input">
                        <label for="link_modal">Link da Banca:</label>
                        <input type="text" id="link_modal" name="link" value="<?php echo htmlspecialchars($link); ?>" placeholder="Preencha o link da banca" required>
                    </div>
                    <div id="input">
                        <label for="upload_modal">Imagem da Banca:</label>
                        <input type="file" id="upload_modal" name="upload" accept="image/*">
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
                document.getElementById('nome_modal').value = getCookie('banca_nome') || '';
                document.getElementById('link_modal').value = getCookie('banca_link') || '';
                addModal.style.display = "block";
            }

            // Função para fechar o modal de adicionar
            function closeAddModal() {
                setCookie('banca_nome', document.getElementById('nome_modal').value, 1);
                setCookie('banca_link', document.getElementById('link_modal').value, 1);
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
                document.getElementById("link_modal").value = '';
                document.getElementById("upload_modal").value = '';
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
                document.getElementById('cod_banca').value = data.cod_banca;
                document.getElementById('nome_modal').value = data.nome;
                document.getElementById('link_modal').value = data.link;
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
    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmLogoutModalLabel">Sair</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja sair?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="confirmLogout" href="sair.php" class="btn btn-primary">Sair</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>