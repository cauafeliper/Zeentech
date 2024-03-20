<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/cadastro-login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>
<body>
    <main>

<?php
include_once('../config/config.php');
session_start();

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novaSenha'], $_POST['token'], $_POST['email'])) {
    $novaSenha = $_POST['novaSenha'];
    $token = $_POST['token'];
    $email = $_POST['email'];

    // Verificar se o token é válido e ainda não expirou
    $stmt = $conexao->prepare("SELECT email FROM tokens WHERE token = ? AND expiracao > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Atualizar a senha no banco de dados
        $stmt = $conexao->prepare("UPDATE logins SET senha = ? WHERE email = ?");
        $stmt->bind_param("ss", $novaSenha, $email);
        $stmt->execute();

        // Remover o token usado
        $stmt = $conexao->prepare("DELETE FROM tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // Redirecionar para a página de login ou mostrar uma mensagem de sucesso
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Senha alterada!",
                html: "Sua senha foi alterada com sucesso! Você já pode fazer login com a nova senha."
            }).then(function() {
                window.location.href = "../index.php";
            });
        </script>';
    } else {
        // Token inválido ou expirado, redirecionar ou mostrar uma mensagem de erro
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Token inválido ou expirado!",
                html: "O link de recuperação de senha é inválido ou expirou. Por favor, solicite novamente."
            }).then(function() {
                window.location.href = "../index.php";
            });
        </script>';
    }
} else {
    // Se não for uma requisição POST válida, redirecionar ou mostrar uma mensagem de erro
    echo '<script>window.location.href = \'../index.php\';</script>';
    exit();
}

?>

</main>
</body>
</html>