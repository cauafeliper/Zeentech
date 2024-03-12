<?php
include_once('../config/config.php');
session_start();

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    // Token não fornecido, redirecionar ou mostrar uma mensagem de erro
    echo '<script>window.location.href = \'../index.php\';</script>';
    exit();
}

$stmt = $conexao->prepare("SELECT * FROM tokens WHERE token = ? AND expiracao > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Token inválido ou expirado, redirecionar ou mostrar uma mensagem de erro
    $tokenValido = false;
}
else {
    $tokenValido = true;
}
?>

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
        <?php if (($tokenValido == false)): ?>
            <?php 
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Token inválido ou expirado!",
                    html: "O link de recuperação de senha é inválido ou expirou. Por favor, solicite novamente."
                }).then(function() {
                    window.location.href = "../index.php";
                });
            </script>';    
            ?>
        <?php else: ?>
            <form id="form-container" action="processar_nova_senha.php" method="post">
                <div class="titulo-senha" style="color: black; text-align:center"><h1>Trocar senha</h1></div>
                <div class="senha-login">
                    <label for="novaSenha">Nova senha:</label>
                    <input type="password" name="novaSenha" id="novaSenha" required placeholder="Nova senha..." maxlength="100">
                </div>
                <div class="submit-login" style="margin-top: 5px">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="submit" name="submit" value="Enviar">
                </div>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
