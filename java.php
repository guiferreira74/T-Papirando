<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa Dinâmica</title>
    <style>
        /* Estilos básicos para o formulário */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        #search-box {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #result-box {
            margin-top: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            max-width: 300px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Pesquisa Dinâmica</h1>
    <input type="text" id="search-box" placeholder="Digite para pesquisar...">

    <div id="result-box"></div>

    <script>
        // Função para fazer a requisição AJAX
        document.getElementById('search-box').onkeyup = function() {
            let query = this.value;
            if (query.length > 2) { // Só faz a pesquisa se houver mais de 2 caracteres
                let xhr = new XMLHttpRequest();
                xhr.open("GET", "search.php?query=" + query, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('result-box').innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            } else {
                document.getElementById('result-box').innerHTML = "";
            }
        };
    </script>

</body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "topapirando";

// Crie a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT nome FROM usuarios WHERE nome LIKE '%$query%' OR sobrenome LIKE '%$query%' LIMIT 10";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<p>" . $row['nome'] . " " . $row['sobrenome'] . "</p>";
        }
    } else {
        echo "Nenhum resultado encontrado.";
    }
}

$conn->close();
?>
