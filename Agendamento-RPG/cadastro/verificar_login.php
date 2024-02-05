<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/cadastro-login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>
<body>
    <main>

<?php
    include_once('../config/config.php');
    session_start();

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo

    if(isset($_GET['token'])) {
        $token = $_GET['token'];

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

    </main>
</body>
</html>