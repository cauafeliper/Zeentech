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
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
</head>
<body>
<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtém o motivo da reprovação do formulário
    $motivoReprovacao = 'Cancelado pelo solicitante';

    $query_cancelar = "UPDATE agendamentos SET status = 'Reprovado', motivo_reprovacao = ? WHERE id = ?";
    $stmt = $conexao->prepare($query_cancelar);
    $stmt->bind_param("ss", $motivoReprovacao, $id);
    
    $query = "SELECT * FROM agendamentos WHERE id = $id";
    $result = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($result);
    $solicitante = $row['solicitante'];
    $dia = $row['dia'];
    $hora_inicio = $row['hora_inicio'];
    $hora_fim = $row['hora_fim'];
    $area_pista = $row['area_pista'];
    $status_previo = $row['status'];

    $query_email = "SELECT email FROM logins WHERE nome = '$solicitante'";
    $result_email = mysqli_query($conexao, $query_email);
    $row_email = mysqli_fetch_assoc($result_email);
    $email = $row_email['email'];
    
    if ($stmt->execute()) {
        $affected_rows = mysqli_affected_rows($conexao);
        if ($affected_rows > 0) {
            // Envie o email com o motivo de reprovação
            require("../../PHPMailer-master/src/PHPMailer.php"); 
            require("../../PHPMailer-master/src/SMTP.php"); 
            $mail = new PHPMailer\PHPMailer\PHPMailer(); 
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls'; 
            $mail->Host = "equipzeentech.com"; 
            $mail->Port = 587;
            $mail->Username = "admin@equipzeentech.com"; 
            $mail->Password = "Z3en7ech"; 
            $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
            $mail->AddAddress($email); 

            $mail->Subject = mb_convert_encoding("Solicitação Cancelada com sucesso!","Windows-1252","UTF-8"); 
            $mail->Body = mb_convert_encoding("Você cancelou sua solicitação de agendamento da área da pista $area_pista para o dia $dia de $hora_inicio até $hora_fim!\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8"); 

            $mail->send();

            $mail->ClearAddresses();
                                        
            $query_gestor = "SELECT email FROM gestor";
            $result_gestor = mysqli_query($conexao, $query_gestor);
            while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
                $mail->addAddress($row_gestor['email']); //email pros gestores
            }

            if ($status_previo == 'Aprovado'){
                $query_copias = "SELECT email FROM copias_email";
                $result_copias = mysqli_query($conexao, $query_copias);
                if ($result_copias->num_rows > 0) {
                    while ($row_copias = mysqli_fetch_assoc($result_copias)) {
                        $email_frota = $row_copias['email'];
                        $mail->AddCC($email_frota); //email pra copias
                    }
                }

                $hoje = new DateTime(date('Y-m-d'));
                // Adicionar 30 dias
                $hoje->add(new DateInterval('P30D'));
                // Obter a nova data formatada
                $data30 = $hoje->format('Y-m-d');
                /* $link = "http://localhost/Zeentech/Agendamento-RPG/grafico/grafico31dias.php?diaInicio=".urlencode(date('Y-m-d'))."&diaFinal=".urlencode($data30); */
                $link = 'https://www.zeentech.com.br/volkswagen/Agendamento-RPG/grafico/grafico31dias.php?diaInicio='.urlencode(date('Y-m-d')).'&diaFinal='.urlencode($data30);

                $mail->Subject = mb_convert_encoding('Agendamento Cancelado!',"Windows-1252","UTF-8");
                $mail->Body = mb_convert_encoding("Um agendamento previamente aprovado para a área da pista $area_pista no dia $dia de $hora_inicio até $hora_fim foi Cancelado pelo Solicitante!\nPara conferir a tabela de agendamentos dos próximos 30 dias, acesse: $link.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");
            }
            else{
                $hoje = new DateTime(date('Y-m-d'));
                // Adicionar 30 dias
                $hoje->add(new DateInterval('P30D'));
                // Obter a nova data formatada
                $data30 = $hoje->format('Y-m-d');
                $linkLocal = "http://localhost/Zeentech/Agendamento-RPG/grafico/grafico31dias.php?diaInicio=".urlencode(date('Y-m-d'))."&diaFinal=".urlencode($data30);
                $link = 'https://www.zeentech.com.br/volkswagen/Agendamento-RPG/grafico/grafico31dias.php?diaInicio='.urlencode(date('Y-m-d')).'&diaFinal='.urlencode($data30);
                
                $mail->IsHTML(true); 
                $mail->Subject = mb_convert_encoding('Solicitação Cancelada!',"Windows-1252","UTF-8");
                $mail->Body = mb_convert_encoding("Uma solicitação de agendamento para a área da pista $area_pista no dia $dia de $hora_inicio até $hora_fim foi Cancelada pelo Solicitante!<br>Para conferir a tabela de agendamentos dos próximos 30 dias, clique <a href=$linkLocal>aqui</a>.<br>Atenciosamente,<br><br>Equipe Zeentech.","Windows-1252","UTF-8");
            }

            $mail->send();

            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "SUCESSO!",
                    text: "Agendamento cancelado com sucesso!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#23CE6B",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redireciona o usuário para a página desejada
                        window.location.href = "../historico.php";
                    }
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    text: "Ocorreu um erro ao cancelar o agendamento. Tente novamente.",
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
