<?php
    include_once('../../config/config.php');
    session_start();

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <style>* {color: white;}</style>
</head>
<body>
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $query_cancelar = "UPDATE agendamentos SET status = 'Aprovado', motivo_reprovacao = '' WHERE id = $id";
        $result_cancelar = mysqli_query($conexao, $query_cancelar);
        $affected_rows = mysqli_affected_rows($conexao);

        // Prepare a SELECT statement to fetch the data of the last inserted row
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

        $query_email = "SELECT email FROM logins WHERE nome = '$solicitante'";
        $result_email = mysqli_query($conexao, $query_email);
        $row_email = mysqli_fetch_assoc($result_email);
        $email = $row_email['email'];
    } catch (Exception $e) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Erro!",
                html: "Houve um problema ao adicionar o agendamento no banco de dados:<br>'.$e->getMessage().'",
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
                    text: "Houve um erro na criação do agendamento.",
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
            $email_gestor = array();
            $query_gestor = "SELECT email FROM gestor";
            $result_gestor = mysqli_query($conexao, $query_gestor);
            while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
                $email_gestor[] = $row_gestor['email']; //email pros gestores
            }

            $email_frota = array();
            $query_copias = "SELECT email FROM copias_email";
            $result_copias = mysqli_query($conexao, $query_copias);
            if ($result_copias->num_rows > 0) {
                while ($row_copias = mysqli_fetch_assoc($result_copias)) {
                    $email_frota[] = $row_copias['email'];
                }
            }

            $hoje = new DateTime(date('Y-m-d'));
            // Adicionar 30 dias
            $hoje->add(new DateInterval('P30D'));
            // Obter a nova data formatada
            $data30 = $hoje->format('Y-m-d');
            /* $link = "http://localhost/Zeentech/Agendamento-RPG/grafico/grafico31dias.php?diaInicio=".urlencode(date('Y-m-d'))."&diaFinal=".urlencode($data30); */
            $link = "https://www.zeentech.com.br/volkswagen/Agendamento-RPG/grafico/grafico31dias.php?diaInicio=".urlencode(date('Y-m-d'))."&diaFinal=".urlencode($data30);

            // Convert arrays to comma-separated strings
            $email_gestor_str = implode(",", $email_gestor);
            // Convert arrays to comma-separated strings
            $email_frota_str = implode(",", $email_frota);
            // Convert the associative array to a string
            $dataInserida_str = implode(",", array_map(function ($key, $value) {
                return "$key: '$value'";
            }, array_keys($dataInserida), $dataInserida));

            echo('prestes a enviar');
            // Utiliza a função exec para chamar o script Python com o valor como argumento
            $output = shell_exec("python ../../email/enviar_email.py " . escapeshellarg('agendamento_aprovado') . " " . escapeshellarg($email_gestor_str) . " " . escapeshellarg($email_frota_str) . " " . escapeshellarg($email) . " " . escapeshellarg($dataInserida_str) . " " . escapeshellarg($link));
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
                        title: "Valor adicionado!",
                        text: "O valor foi adicionado à tabela com sucesso.",
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