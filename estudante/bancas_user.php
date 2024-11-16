<?php
session_start();

// Verifica se o usuário está logado e se tem o tipo de acesso correto
if (!isset($_SESSION['email']) || $_SESSION['tipo_acesso'] != 2) {
    header("Location: ../administrador/login.php");
    exit();
}

// Capturando o nome e sobrenome do usuário da sessão
$usuario_nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário'; // Nome padrão
$sobrenome_usuario = isset($_SESSION['sobrenome']) ? $_SESSION['sobrenome'] : ''; // Sobrenome padrão
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title>
    <link rel="stylesheet" href="bancas_user.css">
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="user.php"><img class="logo" src="../administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
        </div>
        <nav class="menu-desktop">
            <ul>
                <li><a href="user.php">Início</a></li>
                <li class="dropdown">
                    <a href="#" class="simulados-link" id="simulados-toggle">
                        Simulados <i class='bx bx-chevron-down'></i>
                    </a>
                    <ul class="dropdown-menu" id="simulados-dropdown">
                        <li><a href="#">Simulado por Disciplina</a></li>
                        <li><a href="simulados.php">Simulado por Concurso</a></li>
                    </ul>
                </li>
                <li><a href="bancas_user.php">Bancas</a></li>
                <li><a href="desempenhos.php" class="desempenho-link">Desempenho</a></li>
            </ul>
        </nav>

        <!-- Dropdown de Perfil -->
        <div class="info">
            <a href="sobre_user.php">Sobre</a>
            <a href="ajuda_user.php">Ajuda</a>
            
            <!-- Link de saudação com o nome e o dropdown -->
            <div class="profile-dropdown">
                <a href="#" class="profile-toggle" id="profile-toggle">
                    Olá, <?php echo htmlspecialchars($usuario_nome . ' ' . $sobrenome_usuario); ?>
                    <i class='bx bx-chevron-down'></i> <!-- Ícone de seta para baixo -->
                </a>
                <ul class="profile-link" id="profile-dropdown">
                    <li><a href="editar_dados_user.php"><i class='bx bxs-user-circle icon'></i> Editar dados</a></li>
                    <li><a href="../administrador/sair.php"><i class='bx bxs-log-out-circle'></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<script>
  // Mostrar e esconder o dropdown quando o usuário clica
const profileToggle = document.getElementById('profile-toggle');
const profileDropdown = document.getElementById('profile-dropdown');

profileToggle.addEventListener('click', function (e) {
    e.preventDefault(); // Evita o comportamento padrão do link
    profileDropdown.classList.toggle('show'); // Alterna a classe "show"
});

// Fechar o dropdown quando o usuário clica fora dele
window.addEventListener('click', function (e) {
    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
});


// Mostrar e esconder o dropdown quando o usuário clica em "Simulados"
const simuladosToggle = document.getElementById('simulados-toggle');
const simuladosDropdown = document.getElementById('simulados-dropdown');

simuladosToggle.addEventListener('click', function (e) {
    e.preventDefault();
    simuladosDropdown.classList.toggle('show');
});

// Fechar o dropdown de "Simulados" ao clicar fora
window.addEventListener('click', function (e) {
    if (!simuladosToggle.contains(e.target) && !simuladosDropdown.contains(e.target)) {
        simuladosDropdown.classList.remove('show');
    }
});

</script>

<style>
        /* Estilo atualizado */
        .banca {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 250px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            margin: 10px;
            position: relative;
        }

        .banca h2 {
            font-size: 18px;
            color: #333333;
            margin-bottom: 10px;
            text-align: center;
        }
        .banca img {
        display: block;
        margin: 0 auto;
        width: 100px; 
        height: 100px; 
        object-fit: contain; 
        margin-bottom: 15px;
        max-height: 100px; 
        }




        .banca button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            margin-top: auto;
        }
    </style>

<style>

/* Estilo para o dropdown *//* Estilo para o dropdown de Simulados */
.menu-desktop ul .dropdown {
    position: relative;
}

.menu-desktop ul .dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1;
    list-style: none;
    margin: 0;
    padding: 0;
    border-radius: 8px;
    min-width: 200px;
}

.menu-desktop ul .dropdown-menu.show {
    display: block;
}

.menu-desktop ul .dropdown-menu li {
    border-bottom: 1px solid #ddd;
}

.menu-desktop ul .dropdown-menu li a {
    display: block;
    padding: 10px;
    color: #333;
    text-decoration: none;
    white-space: nowrap;
}

.menu-desktop ul .dropdown-menu li a:hover {
    background-color: #f4f4f4;
}

/* Estilo para o dropdown */
.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-toggle {
    color: white; /* Cor do texto de saudação */
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.profile-toggle i {
    margin-left: 5px;
}

.profile-link {
    display: none; /* O dropdown estará oculto inicialmente */
    position: absolute;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    right: 0;
    z-index: 1;
    border-radius: 8px;
    min-width: 150px;
    padding: 10px 0;
    text-align: left;
}

.profile-link.show {
    display: block;
}

.profile-link li {
    list-style-type: none;
}

.profile-link li a {
    white-space: nowrap; /* Evita que o texto quebre a linha */
    display: flex;
    align-items: center;
    padding: 10px;
    color: #000; /* Cor do texto */
    text-decoration: none;
}

.profile-link li a i {
    margin-right: 8px; /* Espaço entre o ícone e o texto */
    font-size: 18px; /* Ajuste do tamanho dos ícones */
    color: #000; /* Cor dos ícones */
}

.profile-link li a:hover {
    background-color: #f1f1f1; /* Muda a cor ao passar o mouse */
}

/* Estilo adicional para o visual arredondado do dropdown */
.profile-link {
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 10px;
}

</style>
<h1 class="centered-title" style="font-size: 34px; font-weight: 600; text-align: center; color: #333333; margin-bottom: 20px; position: relative; padding-bottom: 10px;">
    Banca de Prova
    <span style="display: block; width: 80px; height: 3px; background-color: #007bff; margin: 10px auto 0; border-radius: 3px;"></span>
</h1>


<div class="banca-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; padding: 20px;">

    <?php
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', 'admin', 'topapirando');

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

     // Buscar as bancas cadastradas
     $result = $conn->query("SELECT * FROM banca");

     if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
             echo "<div class='banca' style='width: 250px; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 15px; text-align: center;'>";
             echo "<h2 style='font-size: 18px; color: #333333; margin-bottom: 10px;'>" . htmlspecialchars($row['nome']) . "</h2>";
             echo "<img src='../administrador/" . htmlspecialchars($row['upload']) . "' alt='Imagem da Banca' class='banca-img'>";
 
             // Botão que chama o modal com a classe 'ok'
             echo "<button class='open-modal button ok' data-link='" . htmlspecialchars($row['link']) . "' style='background-color: #007bff; color: #ffffff; border: none; border-radius: 5px; padding: 10px 20px; cursor: pointer; font-size: 14px;'>Acesse o site oficial " . htmlspecialchars($row['nome']) . "</button>";
             echo "</div>";
         }
     } else {
         echo "<p>Nenhuma banca cadastrada.</p>";
     }
     ?>
 </div>
        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Tôpapirando informa: Você está sendo direcionado para outra página.</p>
                <div class="button-container">
                    <button id="confirmRedirect" class="button ok">OK</button>
                    <button class="button cancel" id="cancelRedirect">Cancelar</button>
                </div>
            </div>
        </div>

        <script>
            // Seleciona todos os botões que abrem o modal
            document.querySelectorAll('.open-modal').forEach(button => {
                button.addEventListener('click', (event) => {
                    const link = event.target.getAttribute('data-link');
                    event.preventDefault(); // Impede o clique padrão

                    // Exibe o modal
                    const modal = document.getElementById('myModal');
                    modal.style.display = 'block';

                    // Confirm button event
                    document.getElementById('confirmRedirect').onclick = function() {
                        window.open(link, '_blank');
                        modal.style.display = 'none'; // Fecha o modal
                    }

                    // Cancel button event
                    document.getElementById('cancelRedirect').onclick = function() {
                        modal.style.display = 'none'; // Fecha o modal
                    }
                });
            });

            // Fechar modal ao clicar no "x"
            document.querySelector('.close').onclick = function() {
                document.getElementById('myModal').style.display = 'none';
            }

            // Fechar modal ao clicar fora do modal
            window.onclick = function(event) {
                const modal = document.getElementById('myModal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            }

            // Save scroll position
            window.onbeforeunload = function() {
                localStorage.setItem('scrollPosition', window.scrollY);
            };

            // Restore scroll position
            window.onload = function() {
                const scrollPosition = localStorage.getItem('scrollPosition');
                if (scrollPosition) {
                    window.scrollTo(0, scrollPosition);
                    localStorage.removeItem('scrollPosition'); // Clear it after using
                }
            };
        </script>

    </main>
</body>
</html>
