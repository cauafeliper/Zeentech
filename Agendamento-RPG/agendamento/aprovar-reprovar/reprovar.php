<?php
    include_once('../../config/config.php');
    include_once('../../emails/email.php');
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
    <link rel="stylesheet" href="../../estilos/style-gestor.css">
</head>
<body>
    <script>
        var overlay = document.createElement('div');
        overlay.classList.add('loading-overlay'); // Adiciona a classe para identificação posterior
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.zIndex = '1';
        overlay.innerHTML = '<div class="overlay"><img class="gifOverlay" src="../../assets/truck-unscreen2.gif"><h1>Carregando...</h1></div>';
        document.body.appendChild(overlay);
    </script>
<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Adicione um verificador para verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmarReprovacao'])) {
        // Obtém o motivo da reprovação do formulário
        if (isset($_POST['motivoReprovacao'])) {
            $motivoReprovacao = $_POST['motivoReprovacao'];
        }
        else {
            $motivoReprovacao = 'Motivo não especificado.';
        }

        $query_cancelar = "UPDATE agendamentos SET status = 'Reprovado', motivo_reprovacao = ? WHERE id = ?";
        $stmt = $conexao->prepare($query_cancelar);
        $stmt->bind_param("ss", $motivoReprovacao, $id);
        
        $query = "SELECT * FROM agendamentos WHERE id = $id";
        $result = mysqli_query($conexao, $query);
        $row = mysqli_fetch_assoc($result);
        $solicitante = $row['solicitante'];

        $query_email = "SELECT email FROM logins WHERE nome = '$solicitante'";
        $result_email = mysqli_query($conexao, $query_email);
        $row_email = mysqli_fetch_assoc($result_email);
        $email = $row_email['email'];
        
        if ($stmt->execute()) {
            $affected_rows = mysqli_affected_rows($conexao);
            if ($affected_rows > 0) {
                // Envie o email com o motivo de reprovação
                EmailReprovar($email, $row, $motivoReprovacao, $_SESSION['nome'], $conexao);

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        zindex: 2,
                        title: "SUCESSO!",
                        text: "Agendamento reprovado com sucesso!",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#23CE6B",
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
    } else {
        // Se o formulário não foi enviado, mostre o popup para inserir o motivo de reprovação
        echo '<script>
            Swal.fire({
                title: "Motivo da Reprovação",
                input: "textarea",
                inputPlaceholder: "Digite o motivo da reprovação (limite de 255 caracteres)",
                inputAttributes: {
                    maxlength: 255
                },
                showCancelButton: true,
                confirmButtonText: "Confirmar Reprovação",
                cancelButtonText: "Cancelar",
                preConfirm: (motivoReprovacao) => {
                    // Envia o formulário com o motivo de reprovação
                    const form = document.createElement("form");
                    form.method = "post";
                    form.action = "";
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "confirmarReprovacao";
                    input.value = "1";
                    form.appendChild(input);
                    const textarea = document.createElement("textarea");
                    textarea.name = "motivoReprovacao";
                    textarea.value = motivoReprovacao;
                    form.appendChild(textarea);
                    document.body.appendChild(form);
                    form.submit();
                    textarea.style.display = "none";
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    // Se o botão de cancelar for clicado, redirecione para "../gestor.php"
                    window.location.href = "../gestor.php";
                }
            });
        </script>';
    }    
} 
?>
</body>
</html>
