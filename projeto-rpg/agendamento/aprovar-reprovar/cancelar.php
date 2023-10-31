<?php
include_once('../../config/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query_cancelar = "UPDATE agendamentos SET status = 'Reprovado' WHERE id = $id";
    
    if (mysqli_query($conexao, $query_cancelar)) {
        $affected_rows = mysqli_affected_rows($conexao);
        if ($affected_rows > 0) {
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "SUCESSO!",
                    text: "Agendamento reprovado com sucesso!",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#001e50",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redireciona o usuário para a página desejada
                        window.location.href = "../gestor.php";
                    }
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    text: "Ocorreu um erro ao reprovar o agendamento. Tente novamente.",
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
