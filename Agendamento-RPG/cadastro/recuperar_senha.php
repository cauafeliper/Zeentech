<?php
    include_once('../config/config.php');
    include_once('../emails/email.php');
    session_start();

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/cadastro-login.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-bundle.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <main style="display: flex; gap: 1rem; flex-direction: column;">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="titulo-login">
                <h1>Recuperar senha</h1>
            </div>
            <div class="email-login">
                <div class="email-login-label">
                    <label for="email">
                        <img src="../assets/at.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Email:
                    </label>
                </div>
                <div class="email-login-input">
                    <input type="text" name="email" id="email" required placeholder="Insira seu email cadastrado..." maxlength="100" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                </div>
            </div>
            <div class="submit-login">
                <input type="submit" name="submit" value="enviar">
            </div>
            <div class="login-tela">
                <a href="../index.php">
                    <button type="button">Login</button>
                </a>
            </div>
        </form>
        <form id="formToken" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="token">Token:</label>
            <input type="text" name="token" id="token" required placeholder="Insira o token enviado" maxlength="6">
            <input type="text" name="email" id="email" style="display:none" <?php if(isset($_POST['email'])) { echo 'value="' . $_POST['email'] . '"'; } ?>>
            <div class="submit-login">
                <input type="submit" name="submit" value="verificar">
            </div>
        </form>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && $_POST['submit'] === "enviar") {
    
    $email = $_POST['email'];

    $stmt = $conexao->prepare("SELECT * FROM logins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo '<script>
        Swal.fire({
            icon: "warning",
            title: "ATENÇÃO!",
            html: "O email inserido não está cadastrado!<br>Por favor, insira um email cadastrado."
        });
        </script>';
        exit();
    }
    else{
        $stmt = $conexao->prepare("DELETE FROM tokens WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
        // Gera um token aleatório
        $token = mt_rand(100000, 999999);
        $tempoExpiracaoMinutos = 30;
        $tempoExpiracaoSegundos = $tempoExpiracaoMinutos * 60;

        $expiracao = date('Y-m-d H:i:s', strtotime("+$tempoExpiracaoSegundos seconds"));
        $query = "INSERT INTO tokens (token, expiracao, email) VALUES ('$token', '$expiracao', '$email')";
        $stmt = $conexao->prepare($query);
        $stmt->execute();
        $stmt->close();

        try{
            EmailRecuperarSenha($email, $token);
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Enviado!",
                html: "Foi enviado um email de recuperação de senha!"
            });
            </script>';

            echo '<style>
                #formToken {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }
            </style>';
        }
        catch(Exception $e){
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Erro!",
                html: "O email não pôde ser enviado!<br>Por favor, tente novamente."
            });
            </script>';
        }

        echo '<script>
            document.getElementById("formToken").style.display = "flex";
        </script>';
        // Encerre a execução para evitar que o restante da página seja exibido desnecessariamente
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && $_POST['submit'] === "verificar") {
    // Código para verificar o token e recuperar a senha
    $token = $_POST['token'];
    $email = $_POST['email'];
    $stmt = $conexao->prepare("SELECT * FROM tokens WHERE token = ? AND expiracao > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo '<script>
        Swal.fire({
            icon: "warning",
            title: "ATENÇÃO!",
            html: "O token inserido não existe ou está expirado!"
        });
        </script>';
        exit();
    }
    else{
        echo "<script> window.location.href = 'nova_senha.php?token=$token&email=$email' </script>";
    }
}

$query = "DELETE FROM tokens WHERE expiracao < NOW()";
$stmt = $conexao->prepare($query);
$stmt->execute();

?>
    </main>
</body>
</html>