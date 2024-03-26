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
    <link rel="shortcut icon" href="../../imgs/logo-volks.png" type="image/x-icon">
</head>
<body>
<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtém o motivo da reprovação do formulário
    $motivoReprovacao = 'Cancelado pelo solicitante';

    try {
        $stmt = $conexao->prepare("SELECT * FROM agendamentos WHERE id = ?");
        $stmt->bind_param("i", $id);
        // Execute the SELECT statement
        $stmt->execute();
        // Get the result
        $result = $stmt->get_result();
        // Fetch the data and put it into an associative array
        $dataInserida = $result->fetch_assoc();
        // Fechar a declaração
        $stmt->close();
        $solicitante = $dataInserida['solicitante'];

        $query_cancelar = "UPDATE agendamentos SET status = 'Reprovado', motivo_reprovacao = ? WHERE id = ?";
        $stmt = $conexao->prepare($query_cancelar);
        $stmt->bind_param("ss", $motivoReprovacao, $id);
        $stmt->execute();
        $affected_rows = mysqli_affected_rows($conexao);
        $stmt->close();

        $query_email = "SELECT email FROM logins WHERE nome = '$solicitante'";
        $result_email = mysqli_query($conexao, $query_email);
        $row_email = mysqli_fetch_assoc($result_email);
        $email = $row_email['email'];
        
    } catch (Exception $e) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Erro!",
                html: "Houve um problema na consulta sql:<br>'.$e->getMessage().'",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../gestor.php";
                }
            });
        </script>';
    }
    finally{
        if (!isset($affected_rows)) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: "Houve um erro no cancelamento do agendamento.",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#001e50",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../gestor.php";
                    }
                });
            </script>';
        }
        else{
            // Convert the associative array to a string
            $dataInserida_str = implode(",", array_map(function ($key, $value) {
                return "$key: '$value'";
            }, array_keys($dataInserida), $dataInserida));

            // Utiliza a função exec para chamar o script Python com o valor como argumento
            $output = shell_exec("python ../../email/enviar_email.py " . escapeshellarg('agendamento_cancelado') . " " . escapeshellarg($email_gestor_str) . " " . escapeshellarg($email_frota_str) . " " . escapeshellarg($email) . " " . escapeshellarg($dataInserida_str));
            $output = trim($output);

            if ($output !== 'sucesso'){
                echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "Erro no e-mail!",
                        html: "O agendamento foi cancelado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
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
                        title: "Agendamento cancelado!",
                        text: "O agendamento foi cancelado com sucesso.",
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
        }
    }
} 
?>
</body>
</html>
