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

        try {
            $query_cancelar = "UPDATE agendamentos SET status = 'Reprovado', motivo_reprovacao = ? WHERE id = ?";
            $stmt = $conexao->prepare($query_cancelar);
            $stmt->bind_param("ss", $motivoReprovacao, $id);
            $stmt->execute();
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
                // Convert the associative array to a string
                $dataInserida_str = implode(",", array_map(function ($key, $value) {
                    return "$key: '$value'";
                }, array_keys($dataInserida), $dataInserida));
    
                echo('prestes a enviar');
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
