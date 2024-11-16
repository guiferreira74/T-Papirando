<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title> 
    <link rel="stylesheet" href="bancas.css">
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
<header>
    <div class="interface">
        <div class="logo">
            <a href="index.php"><img class="logo" src="./administrador/assets/logo_papirando_final.svg" alt="Logo"/></a> 
        </div>

        <nav class="menu-desktop">
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a class="simulados" href="#">Simulados</a></li>
                <li><a href="bancas.php">Bancas</a></li>
                <li><a class="desempenho" href="#">Desempenho</a></li>
            </ul>
        </nav>

        <div class="info">
            <a href="./estudante/sobre.php">Sobre</a>
            <a href="./estudante/ajuda.php">Ajuda</a>
            <a href=""><i class="fa-solid fa-gear" id="gear" title="Acesso restrito"></i></a>
            </div>
    </div> <!--interface-->
</header>

<!-- Modal Simulados -->
<div id="modal-simulados" class="modal modal-custom" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p style="color: red;">Por favor, faça o login para ver o simulado.</p>
        <button id="ok-btn-simulados" class="ok-btn">OK</button>
    </div>
</div>

<!-- Modal Desempenho -->
<div id="modal-desempenho" class="modal modal-custom" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p style="color: red;">Por favor, faça o login para ver o seu desempenho.</p>
        <button id="ok-btn-desempenho" class="ok-btn">OK</button>
    </div>
</div>

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
                <p style="color: black;">Tôpapirando informa: Você está sendo direcionado para outra página.</p>
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

<!-- Modal de confirmação -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p style="color: black;">Tôpapirando informa: Você está sendo direcionado para outra página.</p>
        <div class="button-container">
            <button id="confirmRedirect" class="button ok">OK</button>
            <button class="button cancel" id="cancelRedirect">Cancelar</button>
        </div>
    </div>
</div>

<script>
    // Obter elementos dos modais
    var modalSimulados = document.getElementById("modal-simulados");
    var modalDesempenho = document.getElementById("modal-desempenho");
    var modalConfirmacao = document.getElementById("myModal");

    var closeBtns = document.getElementsByClassName("close-btn");
    var okBtnSimulados = document.getElementById("ok-btn-simulados");
    var okBtnDesempenho = document.getElementById("ok-btn-desempenho");

    // Função para mostrar um modal específico
    function showModal(modal) {
        modal.style.display = "block";
    }

    // Função para esconder todos os modais
    function closeModal() {
        modalSimulados.style.display = "none";
        modalDesempenho.style.display = "none";
        modalConfirmacao.style.display = "none";
    }

    // Adicionar eventos de clique para os links Simulados e Desempenho
    document.querySelectorAll('.menu-desktop a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (this.classList.contains("simulados")) {
                e.preventDefault(); 
                showModal(modalSimulados);
            } else if (this.classList.contains("desempenho")) {
                e.preventDefault(); 
                showModal(modalDesempenho);
            }
        });
    });

    // Adicionar eventos de clique para os botões de fechar e os botões OK
    Array.from(closeBtns).forEach(function(btn) {
        btn.onclick = closeModal;
    });
    okBtnSimulados.onclick = closeModal;
    okBtnDesempenho.onclick = closeModal;

    // Fechar o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        if (event.target === modalSimulados || event.target === modalDesempenho) {
            closeModal();
        } else if (event.target === modalConfirmacao) {
            modalConfirmacao.style.display = "none";
        }
    }

    // Seleciona todos os links externos
    document.querySelectorAll('.external-link').forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Impede o clique padrão

            // Exibe o modal de confirmação
            modalConfirmacao.style.display = 'block';

            // Confirm button event
            document.getElementById('confirmRedirect').onclick = function() {
                window.open(link.href, '_blank');
                modalConfirmacao.style.display = 'none'; // Fecha o modal
            }

            // Cancel button event
            document.getElementById('cancelRedirect').onclick = function() {
                modalConfirmacao.style.display = 'none'; // Fecha o modal
            }
        });
    });

    // Fechar modal ao clicar no "x"
    document.querySelector('.close').onclick = function() {
        modalConfirmacao.style.display = 'none';
    }
</script>

<!-- Modal Acesso Restrito -->
<div id="modal-acesso" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span> <!-- Certifique-se que a classe é "close-btn" -->
        <p class="modal-text">Acesso restrito, deseja continuar?</p>
        <div class="modal-buttons">
            <button id="ok-btn-acesso" class="btn-ok">OK</button>
            <button id="cancel-btn-acesso" class="btn-cancel">Cancelar</button>
        </div>
    </div>
</div>


<script>
    // Obter o modal e os botões
    var modalAcesso = document.getElementById("modal-acesso");
    var btnGear = document.getElementById("gear");
    var okBtnAcesso = document.getElementById("ok-btn-acesso");
    var cancelBtnAcesso = document.getElementById("cancel-btn-acesso");
    var closeBtn = document.querySelector("#modal-acesso .close-btn"); // Certifique-se de selecionar o botão X corretamente

    // Quando o ícone da engrenagem for clicado, exibir o modal
    btnGear.addEventListener("click", function(event) {
        event.preventDefault(); // Prevenir a navegação imediata
        modalAcesso.style.display = "block";
    });

    // Se o usuário clicar em "OK", fechar o modal e continuar com o redirecionamento
    okBtnAcesso.addEventListener("click", function() {
        modalAcesso.style.display = "none";
        window.location.href = "login_adm.php"; // Redirecionar para a página de login
    });

    // Se o usuário clicar em "Cancelar", apenas fechar o modal
    cancelBtnAcesso.addEventListener("click", function() {
        modalAcesso.style.display = "none";
    });

    // Fechar o modal se o usuário clicar no X
    closeBtn.addEventListener("click", function() {
        modalAcesso.style.display = "none";
    });

    // Fechar o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        if (event.target == modalAcesso) {
            modalAcesso.style.display = "none";
        }
    };
</script>

</body>
</html>
