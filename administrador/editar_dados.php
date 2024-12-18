<?php
session_start();
// Verificar se o administrador está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_adm.php");
    exit();
}

// Recuperar o nome do administrador da sessão
$admin_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Administrador';

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', 'admin', 'Topapirando');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializando variáveis de formulário para manter os valores inseridos
$nome = '';
$sobrenome = '';
$email = '';
$pergunta = '';
$resposta = '';

// Verifica o administrador logado pela sessão
$cod_administrador = $_SESSION['cod_administrador'];

// Busca os dados do administrador no banco de dados
$sql = "SELECT nome, sobrenome, email, pergunta, resposta FROM administrador WHERE cod_administrador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $cod_administrador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nome = $row['nome'];
    $sobrenome = $row['sobrenome'];
    $email = $row['email'];
    $pergunta = $row['pergunta'];
    $resposta = $row['resposta'];
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $pergunta = $_POST['pergunta'];
    $resposta = $_POST['resposta'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Busca a senha atual do administrador no banco de dados
    $sql = "SELECT senha FROM administrador WHERE cod_administrador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $cod_administrador);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $senha_atual_hash = $row['senha'];

        // Verificar se a senha atual está correta
        if (password_verify($current_password, $senha_atual_hash)) {
            if (password_verify($new_password, $senha_atual_hash)) {
                $error_message = "A nova senha não pode ser igual à senha atual.";
            } else {
                if ($new_password === $confirm_password) {
                    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $update_sql = "
                        UPDATE administrador 
                        SET nome = ?, sobrenome = ?, email = ?, senha = ?, pergunta = ?, resposta = ?
                        WHERE cod_administrador = ?
                    ";

                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param(
                        'ssssssi',
                        $nome,
                        $sobrenome,
                        $email,
                        $new_password_hash,
                        $pergunta,
                        $resposta,
                        $cod_administrador
                    );

                    if ($stmt->execute()) {
                        $success_message = "Dados atualizados com sucesso,<br>por favor registre-se novamente";
                    } else {
                        $error_message = "Erro ao atualizar os dados. Por favor, tente novamente.";
                    }
                } else {
                    $error_message = "As novas senhas não são iguais.";
                }
            }
        } else {
            $error_message = "A senha atual está incorreta.";
        }
    } else {
        $error_message = "Administrador não encontrado.";
    }
}
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados de Login</title>
    <link rel="stylesheet" href="editar_dados.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../administrador/assets/favicon t.png" type="image/x-icon">
    <link rel="stylesheet" href="adm.css">
</head>
<body>

<section id="sidebar">
    <a href="adm.php" class="brand"><i class='bx bxs-smile icon'></i> TÔPAPIRANDO</a>
    <ul class="side-menu">
        <li><a href="adm.php" class="active"><i class='bx bxs-dashboard icon'></i> Início</a></li>
        <li><a href="ajuda_adm.php"><i class='bx bx-help-circle icon'></i> Ajuda</a></li>
        <li class="divider" data-text="Gerenciamento">Gerenciamento</li>
        <li class="dropdown">
            <a href="#"><i class='bx bxs-folder-open icon'></i> Simulado <i class='bx bx-chevron-right icon-right'></i></a>
            <ul class="side-dropdown">
                <li><a href="concurso.php">Concurso</a></li>
                <li><a href="prova.php">Prova</a></li>
                <li><a href="questao.php">Questão</a></li>
            </ul>
            <hr>
        </li>
        <li><a href="banca.php"><i class='bx bx-building icon'></i> Bancas</a></li>
        <li><a href="dificuldade.php"><i class='bx bx-layer icon'></i> Dificuldade</a></li>
        <li><a href="instituicao.php"><i class='bx bxs-graduation icon'></i> Instituições</a></li>
        <li><a href="disciplina.php"><i class='bx bx-book-open icon'></i> Disciplina</a></li>
    </ul>
</section>

<section id="content">
	<nav>
    <i class='bx bx-menu toggle-sidebar' id="hg"></i> <!-- Ícone do menu primeiro -->
    <a href="adm.php" class="brand">
        <img src="assets/logo_papirando_final.svg" alt="Logo" class="logo"> <!-- Logo após o menu -->
    </a>
    <form action="#"></form>
    <a href="sobre_adm.php" class="sobre">Sobre</a>

        <span class="divider"></span>
        <div class="profile">
            <a href="#" class="adm-link" id="profile-toggle">Olá, <?php echo htmlspecialchars($admin_nome); ?> <i class='bx bx-chevron-down'></i></a>
            <ul class="profile-link" id="profile-dropdown">
                <li><a href="editar_dados.php"><i class='bx bxs-user-circle icon'></i> Editar dados</a></li>
                <li><a href="adicionar_adm.php"><i class='bx bxs-cog'></i> Adicionar Adm</a></li>
                <li><a href="sair.php"><i class='bx bxs-log-out-circle'></i> Sair</a></li>
            </ul>
        </div>
    </nav>
</section>

<div class="container-geral">
    <div class="formulario-container">
        <h1>Editar Dados de Login</h1>

        <!-- Formulário de edição de dados -->
        <form action="editar_dados.php" method="POST">
        <div class="form-group">
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" placeholder="Digite seu nome" value="<?php echo htmlspecialchars($nome); ?>" required>
</div>

    <div class="form-group">
        <label for="sobrenome">Sobrenome</label>
        <input type="text" id="sobrenome" name="sobrenome" placeholder="Digite seu sobrenome" value="<?php echo htmlspecialchars($sobrenome); ?>" required>
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Digite seu e-mail" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>

    <div class="form-group">
    <label for="pergunta">Pergunta de Segurança</label>
    <input type="text" id="pergunta" name="pergunta" placeholder="Digite a pergunta de segurança" value="<?php echo htmlspecialchars($pergunta); ?>" required>
</div>

<div class="form-group">
    <label for="resposta">Resposta de Segurança</label>
    <input type="text" id="resposta" name="resposta" placeholder="Digite a resposta de segurança" value="<?php echo htmlspecialchars($resposta); ?>" required>
</div>


    <div class="form-group">
        <label for="current_password">Senha atual</label>
        <input type="password" id="current_password" name="current_password" placeholder="Digite sua senha atual" required>
    </div>

    <div class="form-group">
        <label for="new_password">Nova senha</label>
        <input type="password" id="new_password" name="new_password" placeholder="Digite a nova senha" required>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirme a nova senha</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme a nova senha" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn-save">Salvar Alterações</button>
    </div>

        </form>
    </div>

    <div class="imagem-container">
        <img src="assets/editar.svg" alt="Editar Dados">
    </div>
</div>

<!-- Modal de erro -->
<?php if (!empty($error_message)): ?>
<div id="errorModal" class="modal modal-erro">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p><?php echo $error_message; ?></p>
        <button id="okBtnErro" class="ok-btn-erro">OK</button>
    </div>
</div>
<?php endif; ?>

<!-- Modal de sucesso -->
<?php if (!empty($success_message)): ?>
<div id="successModal" class="modal modal-sucesso">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p><?php echo $success_message; ?></p>
        <button id="okBtnSucesso" class="ok-btn-sucesso">OK</button>
    </div>
</div>
<?php endif; ?>

<script>
// Modal script
document.addEventListener('DOMContentLoaded', function () {
    // Modal de sucesso
    const successModal = document.getElementById('successModal');
    const okBtnSucesso = document.getElementById('okBtnSucesso');

    if (successModal) {
        successModal.style.display = 'block';
        okBtnSucesso.addEventListener('click', function () {
            window.location.href = '../index.php';
        });
    }

    // Modal de erro
    const errorModal = document.getElementById('errorModal');
    const okBtnErro = document.getElementById('okBtnErro');
    const closeBtnErro = document.querySelector('.modal-erro .close-btn');

    if (errorModal) {
        errorModal.style.display = 'block';
        okBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });

        closeBtnErro.addEventListener('click', function () {
            errorModal.style.display = 'none';
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // SIDEBAR DROPDOWN
        const allDropdown = document.querySelectorAll('#sidebar .side-dropdown');
        const sidebar = document.getElementById('sidebar');

        allDropdown.forEach(item=> {
            const a = item.parentElement.querySelector('a:first-child');
            a.addEventListener('click', function (e) {
                e.preventDefault();

                if(!this.classList.contains('active')) {
                    allDropdown.forEach(i=> {
                        const aLink = i.parentElement.querySelector('a:first-child');
                        aLink.classList.remove('active');
                        i.classList.remove('show');
                    });
                }

                this.classList.toggle('active');
                item.classList.toggle('show');
            });
        });

        // SIDEBAR COLLAPSE
        const toggleSidebar = document.querySelector('nav .toggle-sidebar');
        const allSideDivider = document.querySelectorAll('#sidebar .divider');

        if(sidebar.classList.contains('hide')) {
            allSideDivider.forEach(item=> {
                item.textContent = '-';
            });
            allDropdown.forEach(item=> {
                const a = item.parentElement.querySelector('a:first-child');
                a.classList.remove('active');
                item.classList.remove('show');
            });
        } else {
            allSideDivider.forEach(item=> {
                item.textContent = item.dataset.text;
            });
        }

        toggleSidebar.addEventListener('click', function () {
            sidebar.classList.toggle('hide');

            if(sidebar.classList.contains('hide')) {
                allSideDivider.forEach(item=> {
                    item.textContent = '-';
                });

                allDropdown.forEach(item=> {
                    const a = item.parentElement.querySelector('a:first-child');
                    a.classList.remove('active');
                    item.classList.remove('show');
                });
            } else {
                allSideDivider.forEach(item=> {
                    item.textContent = item.dataset.text;
                });
            }
        });

        sidebar.addEventListener('mouseleave', function () {
            if(this.classList.contains('hide')) {
                allDropdown.forEach(item=> {
                    const a = item.parentElement.querySelector('a:first-child');
                    a.classList.remove('active');
                    item.classList.remove('show');
                });
                allSideDivider.forEach(item=> {
                    item.textContent = '-';
                });
            }
        });

        sidebar.addEventListener('mouseenter', function () {
            if(this.classList.contains('hide')) {
                allDropdown.forEach(item=> {
                    const a = item.parentElement.querySelector('a:first-child');
                    a.classList.remove('active');
                    item.classList.remove('show');
                });
                allSideDivider.forEach(item=> {
                    item.textContent = item.dataset.text;
                });
            }
        });

        // PROFILE DROPDOWN
        const profileToggle = document.getElementById('profile-toggle');
        const profileDropdown = document.getElementById('profile-dropdown');

        profileToggle.addEventListener('click', function (e) {
            e.preventDefault();  // Evita o comportamento padrão do link
            profileDropdown.classList.toggle('show');  // Alterna o dropdown
        });

        // Fechar dropdown ao clicar fora
        window.addEventListener('click', function (e) {
            if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');  // Fecha o dropdown ao clicar fora
            }
        });

        // APEXCHARTS EXEMPLO
        var options = {
            series: [{
                name: 'series1',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'series2',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

</body>
</html>
