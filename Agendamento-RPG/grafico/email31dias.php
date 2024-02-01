<?php

include_once('../config/config.php');
session_start(); 

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #c9c9c9e8;">

<?php

if (isset($_GET['link'])) {
    $link = $_GET['link'];

    $query_email = "SELECT logins.email FROM logins WHERE EXISTS (SELECT 1 FROM gestor WHERE gestor.numero = logins.numero);";
    $result_email = mysqli_query($conexao, $query_email);
    if ($result_email->num_rows > 0){
        // Envie o email com o motivo de reprovação
        require("../PHPMailer-master/src/PHPMailer.php"); 
        require("../PHPMailer-master/src/SMTP.php"); 
        $mail = new PHPMailer\PHPMailer\PHPMailer(); 
        $mail->IsSMTP();
        $mail->SMTPDebug = 1;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tsl'; 
        $mail->Host = "equipzeentech.com"; 
        $mail->Port = 587;
        $mail->IsHTML(true); 
        $mail->Username = "admin@equipzeentech.com"; 
        $mail->Password = "Z3en7ech"; 
        $mail->SetFrom("admin@equipzeentech.com", "Zeentech"); 
        
        $mail->Subject = mb_convert_encoding("Gráfico de agendamentos dos próximos 30 dias","Windows-1252","UTF-8"); 
        $mail->Body = mb_convert_encoding('Segue o link para o gráfico de agendamentos da pista para os próximos 30 dias: <a href="'.$link.'">'.$link.'</a>',"Windows-1252","UTF-8"); 

        while ($row_email = mysqli_fetch_assoc($result_email)) {
            $email = $row_email['email'];
            $mail->AddAddress($email);
        }
        $mail->send();
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "ENVIADO!",
                    text: "Email enviado com sucesso!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#23CE6B",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redireciona o usuário para a página desejada
                        window.location.href = "grafico.php";
                    }
                });
            </script>';
    }
    else{
        echo '<script>
            Swal.fire({
                icon: "warning",
                title: "ATENÇÃO!",
                text: "Houve algum problema acessando os emails dos gestores! Verifique se há gestores cadastrasdos.",
                confirmButtonText: "OK",
                confirmButtonColor: "#23CE6B",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página desejada
                    window.location.href = "grafico.php";
                }
            });
        </script>';

    }
}
else{
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "ERRO!",
            text: "Houve algum problema.",
            confirmButtonText: "OK",
            confirmButtonColor: "#23CE6B",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                // Redireciona o usuário para a página desejada
                window.location.href = "grafico.php";
            }
        });
    </script>';
}
?>
</body>
</html>