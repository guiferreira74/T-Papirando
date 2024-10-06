<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banca</title>
    <link rel="stylesheet" href="bancas_user.css">
    <link rel="icon" href="assets/Sorriso2.svg" type="image/x-icon">
    
</head>
<body>
    <header>
        <div class="interface">
            <div class="logo">
                <a href="user.php">
                    <img src="../administrador/assets/logo_papirando_final.svg" alt="Logo" />
                </a>
            </div>

            <nav class="menu-desktop">
                <ul>
                    <li><a href="user.php">Início</a></li>
                    <li><a href="simulados.php">Simulados</a></li>
                    <li><a href="bancas.php">Bancas</a></li>
                    <li><a href="desempenhos.php">Desempenho</a></li>
                </ul>
            </nav>

            <div class="info">
                <a href="sobre.php">Sobre</a>
                <a href="ajuda.php">Ajuda</a>
                <a href="../administrador/sair.php">Sair</a>
            </div>
        </div> <!-- interface -->
    </header>

    <main class="content-container">
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
        echo "<div class='banca'>";
        echo "<h2>" . htmlspecialchars($row['nome']) . "</h2>";
        echo "<img src='../administrador/" . htmlspecialchars($row['upload']) . "' alt='Imagem da Banca' style='width: 100px;'>";
        
        // Botão que chama o modal com a classe 'ok'
        echo "<button class='open-modal button ok' data-link='" . htmlspecialchars($row['link']) . "'>Acesse o site oficial " . htmlspecialchars($row['nome']) . "</button>";
        
        echo "</div>";
    }
} else {
    echo "<p>Nenhuma banca cadastrada.</p>";
}
?>


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
</script>

</body>
</htmml>