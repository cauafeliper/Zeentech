<?php
    include_once('../config/config.php');
    session_start();

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/cadastro-login.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-bundle.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<?php
// Verificar se o token está definido na URL
if (isset($_GET['token']) && isset($_GET['email'])) {
    // Acessar o valor do token
    $token = $_GET['token'];
    $email = $_GET['email'];
}
$dataHoraAtual = new DateTime();
$dataHoraAtual = $dataHoraAtual->format('Y-m-d H:i:s');

$stmt = $conexao->prepare("SELECT * FROM tokens WHERE token = ? AND expiracao > ?");
$stmt->bind_param("ss", $token, $dataHoraAtual);
$stmt->execute();
echo $token, $dataHoraAtual;
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $tokenValido = false;
}
else{
    $tokenValido = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novaSenha'])) {

    // Obter os dados do POST
    $novaSenha = $_POST['novaSenha'];
    
    /* $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT); */

    // Preparar e executar a atualização no banco de dados
    $stmt = $conexao->prepare("UPDATE logins SET senha = ? WHERE email = ?");
    $stmt->bind_param("ss", $novaSenha, $email);
    $stmt->execute();
    $stmt->close();

    // Deletar o token da tabela tokens
    $stmt = $conexao->prepare("DELETE FROM tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();
    
    $stmt = $conexao->prepare("DELETE FROM tokens WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();$stmt->close();

    // Enviar uma resposta de sucesso ao JavaScript
    echo "sucesso";
    exit();
}


?>

</head>
<body>
    <main>
        <?php if (($tokenValido == false)): ?>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    html: "O link é inválido ou expirou!",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirecionar para a página index
                        window.location.href = "../index.php";
                    }
                });

            </script>
        <?php else: ?>
            <!-- Se o token for válido, exibir o formulário -->
            <div id="form-container">
                <input type="password" id="novaSenha" placeholder="Nova Senha">
                <input type="password" id="confirmarSenha" placeholder="Confirmar Senha">
                <button onclick="alterarSenha()">Enviar</button>
            </div>
        <?php endif; ?>
            
        </main>
        <script>
            function alterarSenha() {
                var novaSenha = document.getElementById('novaSenha').value.trim();
                var confirmarSenha = document.getElementById('confirmarSenha').value.trim();

                if (novaSenha === "" || confirmarSenha === "") {
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "Por favor, preencha todos os campos.",
                    });
                    return;
                }

                if (novaSenha !== confirmarSenha) {
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "As senhas não coincidem. Por favor, insira senhas iguais.",
                    });
                    return;
                }

                // Aqui você fará a requisição AJAX para o servidor
                $.ajax({
                    url: "<?php echo $_SERVER['PHP_SELF']; ?>", // Substitua pelo nome do seu arquivo PHP
                    type: "POST",
                    data: { novaSenha: novaSenha},
                    success: function (response) {
                        
                        // Manipular a resposta do servidor
                        
                            Swal.fire({
                                icon: "success",
                                title: "Senha alterada com sucesso!",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirecionar para a página index
                                    window.location.href = "../index.php";
                                }
                            });
                        
                        return false;
                    },
                    error: function (error) {
                        console.error("Erro na requisição AJAX:", error);
                        return false;
                    },
                });
            }
    </script>
</body>
</html>