<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $dbname = "Topapirando";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $token = $_POST['token'];
    $new_password = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verificar o token
    $sql = "SELECT cod_administrador, reset_token_expires FROM administrador WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($cod_administrador, $reset_token_expires);
        $stmt->fetch();

        // Verificar se o token ainda é válido
        if (strtotime($reset_token_expires) > time()) {
            // Atualizar a senha no banco de dados
            $update_sql = "UPDATE administrador SET senha = ?, reset_token = NULL, reset_token_expires = NULL WHERE cod_administrador = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_password, $cod_administrador);
            $update_stmt->execute();

            echo "Sua senha foi redefinida com sucesso!";
        } else {
            echo "O token de redefinição de senha expirou.";
        }
    } else {
        echo "Token inválido.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="login_adm.css"> <!-- Adapte conforme o estilo do seu site -->
</head>
<body>
    <h2>Redefinir Senha</h2>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <input type="password" name="senha" placeholder="Digite sua nova senha" required>
        <button type="submit">Redefinir Senha</button>
    </form>
</body>
</html>
