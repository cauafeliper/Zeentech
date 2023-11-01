<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include_once('../../config/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query_cancelar = "UPDATE agendamentos SET status = 'Aprovado' WHERE id = $id";
    
    if (mysqli_query($conexao, $query_cancelar)) {
        $affected_rows = mysqli_affected_rows($conexao);
        if ($affected_rows > 0) {
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