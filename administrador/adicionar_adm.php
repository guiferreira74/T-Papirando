<?php
session_start();

// Verificar se o administrador está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_adm.php");
    exit();
}

// Recuperar o nome do administrador da sessão
$admin_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Administrador';

// Variáveis de mensagem de erro e sucesso
$error_message = '';
$success_message = '';

// Inicializar as variáveis de campos para manter os dados preenchidos
$nome = $sobrenome = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar ao banco de dados
    $conn = new mysqli('localhost', 'root', 'admin', 'Topapirando');
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Receber os dados do formulário e evitar a remoção em caso de erro
    $nome = $conn->real_escape_string($_POST['nome']);
    $sobrenome = $conn->real_escape_string($_POST['sobrenome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verificar se as senhas coincidem
    if ($_POST['senha'] !== $_POST['confirmar_senha']) {
        $error_message = "As senhas não coincidem.<br>Por favor, tente novamente.";
    } else {
        // Verificar se o e-mail já existe
        $check_email_sql = "SELECT email FROM administrador WHERE email = ?";
        $stmt = $conn->prepare($check_email_sql);
        
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = "Este e-mail já está cadastrado.";
            } else {
                // Inserir o novo administrador
                $insert_sql = "INSERT INTO administrador (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($insert_sql);
                
                if ($stmt_insert) {
                    $stmt_insert->bind_param('ssss', $nome, $sobrenome, $email, $senha);

                    if ($stmt_insert->execute()) {
                        $success_message = "Administrador adicionado com sucesso!";
                        // Limpar os campos após o sucesso
                        $nome = $sobrenome = $email = '';
                    } else {
                        $error_message = "Erro ao adicionar administrador.<br> Por favor, tente novamente.";
                    }

                    $stmt_insert->close(); // Fechar o $stmt de inserção
                } else {
                    $error_message = "Erro na preparação da inserção do administrador.";
                }
            }
            $stmt->close(); // Fechar o $stmt de verificação de e-mail
        } else {
            $error_message = "Erro na preparação da consulta de e-mail.";
        }
    }
    
    $conn->close(); // Fechar a conexão com o banco de dados
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Administrador</title>
    <link rel="stylesheet" href="adicionar_adm.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="adm.css">
</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="adm.php" class="brand"><i class='bx bxs-smile icon'></i> TÔPAPIRANDO</a>
    <ul class="side-menu">
        <li><a href="adm.php" class="active"><i class='bx bxs-dashboard icon'></i> Início</a></li>
        <li><a href="ajuda_adm.php"><i class='bx bx-help-circle icon'></i> Ajuda</a></li>
        <li><a href="parametros.php"><i class='bx bx-cog icon'></i> Parâmetros</a></li>
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
        <li><a href="duracao.php"><i class='bx bx-time-five icon'></i> Duração</a></li>
        <li><a href="disciplina.php"><i class='bx bx-time-five icon'></i>Disciplina</a></li>
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
    <a href="#" class="nav-link">
        <i class='bx bxs-bell icon'></i>
    </a>
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

<!-- Formulário de adição -->
<div class="container-geral">
    <div class="formulario-container">
        <h1>Adicionar Novo Administrador</h1>
        
        <!-- Formulário de adição -->
        <form action="adicionar_adm.php" method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>

            <div class="form-group">
                <label for="sobrenome">Sobrenome</label>
                <input type="text" id="sobrenome" name="sobrenome" placeholder="Digite o sobrenome" value="<?php echo htmlspecialchars($sobrenome); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite o e-mail" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite a senha" required>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a senha" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-save">Adicionar Administrador</button>
            </div>
        </form>
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
// Script para exibir os modais
document.addEventListener('DOMContentLoaded', function () {
    // Modal de sucesso
    const successModal = document.getElementById('successModal');
    const okBtnSucesso = document.getElementById('okBtnSucesso');

    if (successModal) {
        successModal.style.display = 'block';
        okBtnSucesso.addEventListener('click', function () {
            successModal.style.display = 'none';
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

    // Validação de senha e confirmação
    const form = document.querySelector('form');
    const senha = document.getElementById('senha');
    const confirmarSenha = document.getElementById('confirmar_senha');

    form.addEventListener('submit', function (e) {
        if (senha.value !== confirmarSenha.value) {
            e.preventDefault(); // Evita o envio do formulário
            alert('As senhas não coincidem. Por favor, verifique e tente novamente.');
        }
    });
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
