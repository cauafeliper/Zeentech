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
    <title>Login</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/cadastro-login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>
<body>
    <main>
        <?php
            if(isset($_GET['token'])) {
                $token = $_GET['token'];
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['submit'])){       
                    // Verificar se o token existe na tabela logins_pendentes
                    $stmt = $conexao->prepare("SELECT * FROM logins_pendentes WHERE token = ? AND expiracao > NOW()");
                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
            
                    if($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
            
                        // Inserir na tabela logins
                        $stmt_insert = $conexao->prepare("INSERT INTO logins (numero, nome, empresa, area, email, senha) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt_insert->bind_param("ssssss", $row['numero'], $row['nome'], $row['empresa'], $row['area'], $row['email'], $row['senha']);
                        $stmt_insert->execute();
                        $stmt_insert->close();
            
                        // Excluir da tabela logins_pendentes
                        $stmt_delete = $conexao->prepare("DELETE FROM logins_pendentes WHERE token = ?");
                        $stmt_delete->bind_param("s", $token);
                        $stmt_delete->execute();
                        $stmt_delete->close();
            
                        echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "Email Verificado!",
                                    text: "Seu email foi verificado com sucesso. Agora você pode fazer login.",
                                    confirmButtonText: "Login",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redireciona o usuário para a página de login
                                        window.location.href = "../index.php";
                                    }
                                });
                            </script>';

                        // Verificar se o email está na tabela gestor
                        $stmt_gestor = $conexao->prepare("SELECT * FROM gestor WHERE email = ?");
                        $stmt_gestor->bind_param("s", $row['email']);
                        $stmt_gestor->execute();
                        $result_gestor = $stmt_gestor->get_result();
                        $stmt_gestor->close();

                        if($result_gestor->num_rows > 0) {
                            $gestorTrue = true;
                        } else {
                            $gestorTrue = false;
                        }

                        // Verificar se o email está na tabela lista_adm
                        $stmt_adm = $conexao->prepare("SELECT * FROM lista_adm WHERE email = ?");
                        $stmt_adm->bind_param("s", $row['email']);
                        $stmt_adm->execute();
                        $result_adm = $stmt_adm->get_result();
                        $stmt_adm->close();

                        if($result_adm->num_rows > 0) {
                            $admTrue = true;
                        } else {
                            $admTrue = false;
                        }

                        require("../PHPMailer-master/src/PHPMailer.php"); 
                        require("../PHPMailer-master/src/SMTP.php");
                        $mail = new PHPMailer\PHPMailer\PHPMailer();
                        $mail->IsSMTP();
                        $mail->SMTPDebug = 0;
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = 'tls'; 
                        $mail->Host = "equipzeentech.com"; 
                        $mail->Port = 587;
                        $mail->IsHTML(true); 
                        $mail->Username = "admin@equipzeentech.com"; 
                        $mail->Password = "Z3en7ech"; 
                        $mail->SetFrom("admin@equipzeentech.com", "Zeentech"); 
                        $mail->AddAddress($row['email']); 
                        $mail->Subject = mb_convert_encoding("Email verificado!","Windows-1252","UTF-8"); 
                        $mail->Body = mb_convert_encoding("Seu email foi verificado com sucesso! Segue em anexo o tutorial de uso da página.<br><br>Atenciosamente,<br>Equipe Zeentech.","Windows-1252","UTF-8");
                        $tutorialUsuario = '../anexos/tutorial_usuario.pdf';
                        $tutorialGestor = '../anexos/tutorial_gestor.pdf';
                        $tutorialAdm = '../anexos/tutorial_administrador.pdf';
                        $mail->addAttachment($tutorialUsuario, 'tutorial_usuario.pdf');
                        if($gestorTrue) {
                            $mail->addAttachment($tutorialGestor, 'tutorial_gestor.pdf');
                        }
                        if($admTrue) {
                            $mail->addAttachment($tutorialAdm, 'tutorial_administrador.pdf');
                            if (!$gestorTrue){
                                $mail->addAttachment($tutorialGestor, 'tutorial_gestor.pdf');
                            }
                        }

                        $mail->send();
                    } else {
                        echo '<script>
                                Swal.fire({
                                    icon: "warning",
                                    title: "Token Inválido!",
                                    text: "O token fornecido não é válido ou expirou.",
                                    confirmButtonText: "OK",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redireciona o usuário para a página de login
                                        window.location.href = "../index.php";
                                    }
                                });
                            </script>';
                    }
                    exit();
                }
            } else {
                echo '<script>
                        Swal.fire({
                            icon: "warning",
                            title: "Token Ausente!",
                            text: "O token não foi fornecido.",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#001e50",
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redireciona o usuário para a página de login
                                window.location.href = "../index.php";
                            }
                        });
                    </script>';
            }
        ?>

        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
            <input type="submit" name="submit" value="verificar">
        </form>

    </main>
</body>
</html>
