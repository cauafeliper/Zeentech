<?php
    include_once('../../config/config.php');
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
    <style>* {color: white;}</style>
</head>
<body>
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query_cancelar = "UPDATE agendamentos SET status = 'Aprovado', motivo_reprovacao = '' WHERE id = $id";

    $query = "SELECT * FROM agendamentos WHERE id = $id";
    $result = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($result);
    $solicitante = $row['solicitante'];
    $dia = $row['dia'];
    $hora_inicio = $row['hora_inicio'];
    $hora_fim = $row['hora_fim'];
    $area_pista = $row['area_pista'];

    $query_email = "SELECT email FROM logins WHERE nome = '$solicitante'";
    $result_email = mysqli_query($conexao, $query_email);
    $row_email = mysqli_fetch_assoc($result_email);
    $email = $row_email['email'];
    
    if (mysqli_query($conexao, $query_cancelar)) {
        $affected_rows = mysqli_affected_rows($conexao);
        if ($affected_rows > 0) {
            require("../../PHPMailer-master/src/PHPMailer.php"); 
            require("../../PHPMailer-master/src/SMTP.php"); 
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
            $mail->AddAddress($email); 

            $mail->Subject = mb_convert_encoding("Solicitação Aprovada!","Windows-1252","UTF-8"); 
            $mail->Body = mb_convert_encoding("Sua solicitação de agendamento da área da pista $area_pista para o dia $dia de $hora_inicio até $hora_fim foi Aprovada!<br>Atenciosamente,<br><br>Equipe Zeentech.","Windows-1252","UTF-8"); 

            $query_copias = "SELECT email FROM copias_email";
            $result_copias = mysqli_query($conexao, $query_copias);
            if ($result_copias->num_rows > 0) {
                while ($row_copias = mysqli_fetch_assoc($result_copias)) {
                    $email_copia = $row_copias['email'];
                    $mail->AddCC($email_copia);
                }
            }

            $mail->send();

            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "SUCESSO!",
                    text: "Agendamento aprovado com sucesso!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#23CE6B",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../gestor.php";
                    }
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    text: "Ocorreu um erro ao aprovar o agendamento. Tente novamente.",
                });
            </script>';
        }
    } else {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "ERRO!",
                text: "Falha na consulta SQL. Tente novamente.",
            });
        </script>';
    }
} 
?>
</body>
</html>