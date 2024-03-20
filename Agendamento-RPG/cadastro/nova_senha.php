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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
</head>
<body>
    <main>

        <?php
            if ((isset($_GET['token']) && isset($_GET['email'])) && !empty($_GET['token']) && !empty($_GET['email'])){
                $token = $_GET['token'];
                $email = $_GET['email'];
                $tokenValido = true;
            }
            else {
                // Redirecionar para a página de login ou mostrar uma mensagem de erro
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Código inválido ou expirado!",
                        html: "O código de recuperação de senha é inválido ou expirou. Por favor, solicite novamente."
                    }).then(function() {
                        window.location.href = "../index.php";
                    });
                </script>';
                $tokenValido = false;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
                $novaSenha = $_POST['novaSenha'];
                $confirmaSenha = $_POST['confirmaSenha'];

                if ($novaSenha != $confirmaSenha) {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            html: "As senhas não coincidem."
                        });
                    </script>';
                }
                else{
                    $stmt = $conexao->prepare("SELECT * FROM tokens WHERE token = ? AND email = ? AND expiracao > NOW()");
                    $stmt->bind_param("ss", $token, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0) {
                        // Token inválido ou expirado, redirecionar ou mostrar uma mensagem de erro
                        echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Código inválido ou expirado!",
                                html: "O código de recuperação de senha é inválido ou expirou. Por favor, solicite novamente."
                            }).then(function() {
                                window.location.href = "../index.php";
                            });
                        </script>';
                    }
                    else {
                        $stmt = $conexao->prepare("UPDATE logins SET senha = ? WHERE email = ?");
                        $stmt->bind_param("ss", $novaSenha, $email);
                        $stmt->execute();
                        $stmt->close();

                        // Remover o token usado
                        $stmt = $conexao->prepare("DELETE FROM tokens WHERE token = ? OR email = ?");
                        $stmt->bind_param("ss", $token, $email);
                        $stmt->execute();
                        $stmt->close();

                        // Redirecionar para a página de login ou mostrar uma mensagem de sucesso
                        echo '<script>
                            Swal.fire({
                                icon: "success",
                                title: "Senha alterada!",
                                html: "Sua senha foi alterada com sucesso! Você já pode fazer login com a nova senha."
                            }).then(function() {
                                window.location.href = "../index.php";
                            });
                        </script>';
                    }
                }
            }
        ?>

        <?php if (($tokenValido == false)): ?>
            <?php 
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Código inválido ou expirado!",
                    html: "O código de recuperação de senha é inválido ou expirou. Por favor, solicite novamente."
                }).then(function() {
                    window.location.href = "../index.php";
                });
            </script>';    
            ?>
        <?php else: ?>
            <form id="form-container" action="<?php echo $_SERVER['PHP_SELF']; ?>?token=<?php echo $token; ?>&email=<?php echo $email; ?>" method="post">
                <div class="titulo-senha" style="color: black; text-align:center"><h1>Trocar senha</h1></div>
                <div class="senha-login">
                    <label for="email">Email:</label>
                    <h3 style="color:black"><?php echo $email ?></h3>
                    <label for="novaSenha">Nova senha:</label>
                    <input type="password" name="novaSenha" id="novaSenha" required placeholder="Nova senha..." maxlength="100">
                    <label for="confirmaSenha">Confirmar senha:</label>
                    <input type="password" name="confirmaSenha" id="confirmaSenha" required placeholder="Confirmar senha..." maxlength="100">
                </div>
                <div class="submit-login" style="margin-top: 5px">
                    <input type="submit" name="submit" value="Trocar">
                </div>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
