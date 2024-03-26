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
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        session_unset();
        echo '<script>window.location.href = \'../index.php\';</script>';
    }
    
    $email = $_SESSION['email'];
    $query = "SELECT * FROM lista_adm WHERE email = ?";
    $stmt = $conexao->prepare($query);
    // Vincula os parâmetros
    $stmt->bind_param("s", $email);
    // Executa a consulta
    $stmt->execute();
    // Obtém os resultados, se necessário
    $result = $stmt->get_result();
    // Fechar a declaração
    $stmt->close();

    $email = $_SESSION['email'];
    $query = "SELECT * FROM gestor WHERE email = ?";
    $stmt = $conexao->prepare($query);
    // Vincula os parâmetros
    $stmt->bind_param("s", $email);
    // Executa a consulta
    $stmt->execute();
    // Obtém os resultados, se necessário
    $resultGestor = $stmt->get_result();
    // Fechar a declaração
    $stmt->close();
    
    if ((!$result || mysqli_num_rows($result) === 0) && (!$resultGestor || mysqli_num_rows($resultGestor) === 0)) {
        session_unset();
        echo '<script>window.location.href = \'../index.php\';</script>';
    }
?>

<?php
try {
    $email_frota = array();
    $query_copias = "SELECT email FROM lista_distribuicao";
    $result_copias = mysqli_query($conexao, $query_copias);
    if ($result_copias->num_rows > 0) {
        while ($row_copias = mysqli_fetch_assoc($result_copias)) {
            $email_frota[] = $row_copias['email'];
        }
    }
    // Convert arrays to comma-separated strings
    $email_frota_str = implode(",", $email_frota);
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
    if ($result_copias->num_rows > 0){
        // Utiliza a função exec para chamar o script Python com o valor como argumento
        $output = shell_exec("python ../email/enviar_email.py " . escapeshellarg('envio_grafico') . " " . escapeshellarg($email_frota_str));
        $output = trim($output);

        if ($output !== 'sucesso'){
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Erro no e-mail!",
                    html: "O valor foi adicionado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#001e50",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../agendamento/gestor.php";
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

?>
</body>
</html>