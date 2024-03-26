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
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="email">Email</label>
            <input type="text" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            <label for="token">Token</label>
            <input type="text" name="token">
            <input type="submit" name="submit" value="verificar">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
            
            $token = $_POST['token'];
            $email = $_POST['email'];
            // Verificar se o token existe na tabela logins_pendentes
            $stmt = $conexao->prepare("SELECT * FROM logins_pendentes WHERE token = ? AND expiracao > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            if($result->num_rows > 0) {
                try{
                    $row = $result->fetch_assoc();
                    // Inserir na tabela logins
                    $stmt_insert = $conexao->prepare("INSERT INTO logins (numero, nome, empresa, area, email, senha) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt_insert->bind_param("ssssss", $row['numero'], $row['nome'], $row['empresa'], $row['area'], $row['email'], $row['senha']);
                    $stmt_insert->execute();
                    $affected_rows = $stmt_insert->affected_rows;
                    $stmt_insert->close();
        
                    // Excluir da tabela logins_pendentes
                    $stmt_delete = $conexao->prepare("DELETE FROM logins_pendentes WHERE token = ?");
                    $stmt_delete->bind_param("s", $token);
                    $stmt_delete->execute();
                    $stmt_delete->close();
    
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
                }
                catch(Exception $e){
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            html: "Houve um problema na consulta sql:<br>'.$e->getMessage().'",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#001e50",
                        });
                    </script>';
                }
                finally{
                    if (!isset($affected_rows)) {
                        echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Erro!",
                                text: "Houve um erro na verificação do email.",
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
                        // Utiliza a função exec para chamar o script Python com o valor como argumento
                        $output = shell_exec("python ../email/enviar_email.py " . escapeshellarg('cadastro_verificado') . " " . escapeshellarg($email) . " " . escapeshellarg($gestorTrue) . " " . escapeshellarg($admTrue));
                        $output = trim($output);
            
                        if ($output !== 'sucesso'){
                            echo '<script>
                                Swal.fire({
                                    icon: "warning",
                                    title: "Erro no e-mail!",
                                    html: "O login foi criado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
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
                                    title: "Email Verificado!",
                                    text: "Seu email foi verificado com sucesso. Agora você pode realizar login.",
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
                        }    
                    }
                }
            } else {
                echo '<script>
                        Swal.fire({
                            icon: "warning",
                            title: "Token Inválido!",
                            text: "O token ou email fornecido não é válido ou expirou.",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#001e50",
                            allowOutsideClick: false,
                        })
                    </script>';
            }
        }
        ?>
    </main>
</body>
</html>
