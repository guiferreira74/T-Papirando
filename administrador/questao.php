<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Questões</title>
    <link rel="stylesheet" href="questao.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header-prc">
        <a href="adm.php">
            <img class="logo" src="assets/logo_papirando_final.svg" alt="topapirando">
        </a>
        <div class="links">
            <a id="sobre" href="sobre_adm.php">Sobre</a>
            <a href="ajuda_adm.php">Ajuda</a>
            <a href="sair.php">Sair</a>
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
                <li><a href="ajuda_adm.php">Ajuda</a></li>
                <li><a href="parametros.php">Parâmetros</a></li>
                <hr>
                <p>Gerenciar Conteúdo</p>
                <li><a href="banca.php">Bancas</a></li>
                <li><a href="escolaridade.php">Escolaridade</a></li>
                <li><a href="dificuldade.php">Dificuldade</a></li>
                <li><a href="disciplina.php">Disciplinas</a></li>
                <li><a href="duracao.php">Durações</a></li>
                <li><a href="instituicao.php">Instituições</a></li>
                <li><a href="questao.php">Questões</a></li>
            </ul>
        </div>

        <main id="main-container">
            <div id="corpo">
                <h1>Gerenciar Questões</h1>

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
                    $respostacorreta = $_POST['respostacorreta'];
                    $qtd_disciplina = $_POST['qtd_disciplina'];
                    $disciplina_cod_disciplina = $_POST['disciplina_cod_disciplina'];
                    $grau_cod_grau = $_POST['grau_cod_grau'];
                    $banca_cod_banca = $_POST['banca_cod_banca'];
                    $prova_cod_prova = $_POST['prova_cod_prova'];
                    $cod_questao = $_POST['cod_questao'] ?? null;

                    // Verificar se a questão já está registrada
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
                            $sql = "UPDATE questao SET pergunta='$pergunta', resposta1='$resposta1', resposta2='$resposta2', resposta3='$resposta3', resposta4='$resposta4', respostacorreta='$respostacorreta', qtd_disciplina='$qtd_disciplina', disciplina_cod_disciplina=$disciplina_cod_disciplina, grau_cod_grau=$grau_cod_grau, banca_cod_banca=$banca_cod_banca, prova_cod_prova=$prova_cod_prova WHERE cod_questao=$cod_questao";
                        } else {
                            // Inserir novo registro
                            $sql = "INSERT INTO questao (pergunta, resposta1, resposta2, resposta3, resposta4, respostacorreta, qtd_disciplina, disciplina_cod_disciplina, grau_cod_grau, banca_cod_banca, prova_cod_prova) VALUES ('$pergunta', '$resposta1', '$resposta2', '$resposta3', '$resposta4', '$respostacorreta', '$qtd_disciplina', $disciplina_cod_disciplina, $grau_cod_grau, $banca_cod_banca, $prova_cod_prova)";
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

                // Preencher os campos do modal para edição
                $cod_questao = $_GET['edit'] ?? null;
                $pergunta = '';
                $resposta1 = '';
                $resposta2 = '';
                $resposta3 = '';
                $resposta4 = '';
                $respostacorreta = '';
                $qtd_disciplina = '';
                $disciplina_cod_disciplina = '';
                $grau_cod_grau = '';
                $banca_cod_banca = '';
                $prova_cod_prova = '';

                if ($cod_questao) {
                    $result = $conn->query("SELECT * FROM questao WHERE cod_questao=$cod_questao");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $pergunta = $row['pergunta'];
                        $resposta1 = $row['resposta1'];
                        $resposta2 = $row['resposta2'];
                        $resposta3 = $row['resposta3'];
                        $resposta4 = $row['resposta4'];
                        $respostacorreta = $row['respostacorreta'];
                        $qtd_disciplina = $row['qtd_disciplina'];
                        $disciplina_cod_disciplina = $row['disciplina_cod_disciplina'];
                        $grau_cod_grau = $row['grau_cod_grau'];
                        $banca_cod_banca = $row['banca_cod_banca'];
                        $prova_cod_prova = $row['prova_cod_prova'];
                    }
                }

                // Obter as bancas
                $bancas_result = $conn->query("SELECT * FROM banca");
                ?>

                <div class="text-center mb-3">
                    <button class="btn btn-primary" onclick="openAddModal()">Adicionar Nova Questão</button>
                </div>

                <div class="table-container">
                    <?php
                    $result = $conn->query("SELECT * FROM questao");

                    if ($result->num_rows > 0) {
                        echo "<table class='table'>";
                        echo "<tr><th>Pergunta</th><th>Resposta 1</th><th>Resposta 2</th><th>Resposta 3</th><th>Resposta 4</th><th>Resposta Correta</th><th>Ações</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['pergunta']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['resposta1']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['resposta2']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['resposta3']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['resposta4']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['respostacorreta']) . "</td>";
                            echo "<td>
                                    <a href='?edit=" . $row['cod_questao'] . "' class='btn btn-warning'>Editar</a>
                                    <a href='?delete=" . $row['cod_questao'] . "' class='btn btn-danger'>Excluir</a>
                                  </td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Nenhuma questão encontrada.</p>";
                    }
                    ?>
                </div>

