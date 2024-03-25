<?php
include_once('../config/config.php');
include_once('../emails/email.php');
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
    
    try {
        $query_email = "SELECT email FROM lista_distribuicao";
        $result_email = mysqli_query($conexao, $query_email);
        mysqli_close($conexao);
    }
    catch (Exception $e) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "ERRO! ' . $e->getMessage() . '",
                text: "Houve algum problema.",
                confirmButtonText: "OK",
                confirmButtonColor: "#23CE6B",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página desejada
                    window.location.href = "../agendamento/gestor.php";
                }
            });
        </script>';
    }
    finally {
        if ($result_email->num_rows > 0){
            // Utiliza a função exec para chamar o script Python com o valor como argumento
            $output = shell_exec("python ../../email/enviar_email.py " . escapeshellarg('agendamento_reprovado') . " " . escapeshellarg($email_gestor_str) . " " . escapeshellarg($email_frota_str) . " " . escapeshellarg($email) . " " . escapeshellarg($dataInserida_str));
            $output = trim($output);
            echo($output);

            if ($output !== 'sucesso'){
                echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "Erro no e-mail!",
                        html: "O agendamento foi criado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#001e50",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../gestor.php";
                        }
                    });
                </script>';  
            }
            else{
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
                            window.location.href = "../agendamento/gestor.php";
                        }
                    });
                </script>';
            }
        }
        else{
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    text: "Houve algum problema acessando os emails da Lista de Distribuição! Verifique se há emails cadastrasdos.",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#23CE6B",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redireciona o usuário para a página desejada
                        window.location.href = "../agendamento/gestor.php";
                    }
                });
            </script>';
    
        }
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
                window.location.href = "../agendamento/gestor.php";
            }
        });
    </script>';
}
?>
</body>
</html>