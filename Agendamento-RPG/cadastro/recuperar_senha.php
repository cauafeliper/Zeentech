<?php
    include_once('../config/config.php');
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

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    
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
        // Gera um token aleatório
        $token = bin2hex(random_bytes(16)); // 16 bytes, 32 caracteres hexadecimais
        $tempoExpiracaoMinutos = 30;
        $tempoExpiracaoSegundos = $tempoExpiracaoMinutos * 60;

        $expiracao = date('Y-m-d H:i:s', strtotime("+$tempoExpiracaoSegundos seconds"));
        $query = "INSERT INTO tokens (token, expiracao, email) VALUES ('$token', '$expiracao', '$email')";
        $stmt = $conexao->prepare($query);
        $stmt->execute();        

        $linkLocal = 'http://localhost/Zeentech/Agendamento-RPG/cadastro/nova_senha.php?token='.$token.'&?email='.$email;
        $link = 'https://www.zeentech.com.br/volkswagen/Agendamento-RPG/cadastro/nova_senha.php?token='.$token.'&?email='.$email;

        require("../PHPMailer-master/src/PHPMailer.php"); 
        require("../PHPMailer-master/src/SMTP.php"); 
        $mail = new PHPMailer\PHPMailer\PHPMailer(); 
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls'; 
        $mail->Host = "equipzeentech.com"; 
        $mail->Port = 587;
        $mail->IsHTML(true); 
        $mail->Username = "admin@equipzeentech.com"; 
        $mail->Password = "Z3en7ech"; 
        $mail->SetFrom("admin@equipzeentech.com", "Zeentech"); 
        $mail->AddAddress($email);
        
        $mail->Subject = mb_convert_encoding("Recuperação da senha","Windows-1252","UTF-8"); 
        $mail->Body = mb_convert_encoding('Voce solicitou uma recuperação de senha para este email no site do RPG. Para recuperar sua senha, clique <a href="'.$linkLocal.'">aqui</a>. Esse link vai expirar em 30 minutos!<br>Caso a solicitação não tenha sido feita por você, apenas ignore este email.<br><br>Atenciosamente,<br>Equipe Zeentech.',"Windows-1252","UTF-8"); 

        try{
            $mail->send();
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Enviado!",
                html: "Foi enviado um email de recuperação de senha!"
            });
            </script>';
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

        echo $link;
        // Encerre a execução para evitar que o restante da página seja exibido desnecessariamente
        exit();
    }
}

$query = "DELETE FROM tokens WHERE expiracao < NOW()";
$stmt = $conexao->prepare($query);
$stmt->execute();

?>

</head>
<body>
    <main>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return recuperarSenha();">
            <div class="titulo-login"><h1>Recuperar senha</h1></div>
            <div class="email-login">
                <div class="email-login-label">
                    <label for="email"><img src="../assets/at.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Email:</label>
                </div>
                <div class="email-login-input">
                    <input type="text" name="email" id="email" required placeholder="Insira seu email cadastrado..." maxlength="30" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                </div>
            </div>
            <div class="submit-login">
                <input type="submit" name="submit" value="enviar">
            </div>
            <div class="login-tela">
                <a href="../index.php"><button type="button">Login</button></a>
            </div>
        </form>
    </main>
    <script>
        function recuperarSenha() {
            var email = document.getElementById('email').value.trim();
            if (!email.includes('@') || !email.includes('.')) {
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    html: "O email inserido é inválido!<br>Por favor, insira um email válido."
                });
            }
            else {
                // Faz a requisição AJAX para chamar a função PHP
                $.ajax({
                    url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                    type: 'POST',
                    data: { email},
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Enviado!",
                            html: "Foi enviado um email de recuperação de senha!"
                        });
                    },
                    error: function (error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            }
            return false;
        }
    </script>
</body>
</html>