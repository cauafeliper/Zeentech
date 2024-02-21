<?php
    include_once('config/config.php');

    session_start();

    // Tempo de expiração da sessão em segundos (30 minutos)
    $expire_time = 30 * 60;

    // Verifica se a sessão existe e se o tempo de expiração foi atingido
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $expire_time)) {
        // Sessão expirada, destrói a sessão e redireciona para a página de login
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    // Atualiza o tempo da última atividade
    $_SESSION['last_activity'] = time();
    $_SESSION['expire_time'] = $expire_time;

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" href="imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="estilos/cadastro-login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="login">
            <div class="titulo-login"><h1>Login</h1></div>
            <div class="email-login">
                <div class="email-login-label">
                    <label for="email"><img src="assets/at.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Email:</label>
                </div>
                <div class="email-login-input">
                    <input type="text" name="email" id="email" placeholder="Insira seu email cadastrado..." maxlength="100" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" <?php if(isset($_POST['email'])) { echo 'value="' . $_POST['email'] . '"'; } ?>>
                </div>
            </div>
            <div class="senha-login">
                <div class="senha-login-label">
                    <label for="senha"><img src="assets/lock.png" width="16" height="16" style="margin-right: 5px;">Senha:</label>
                </div>
                <div class="senha-login-input">
                    <input type="password" name="senha" id="senha" placeholder="Insira sua senha...">
                </div>
                <hr>
            </div>
            <div class="submit-login">
                <input type="submit" name="submit" value="Entrar">
            </div>
            <div class="cadastrar">
                <a href="cadastro/tela-cadastro.php"><button type="button">Cadastrar-se</button></a>
            </div>
            <div class="esqueceu">
                <a href="cadastro/recuperar_senha.php"><button type="button">Esqueci minha senha</button></a>
            </div>
        </form>
    </main>
    <?php
        if (isset($_POST['submit'])) {
            if (empty($_POST['email']) || empty($_POST['senha']))
            {
                echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    html: "Algum dos campos está vazio!<br>Por favor, preencha todos os campos atentamente."
                });
                </script>';  
            }
            else {
                $email = $_POST['email'];
                $senha = $_POST['senha'];

                $query = "SELECT * FROM logins WHERE email = ? AND senha = ?";
                $stmt = $conexao->prepare($query);

                // Vincula os parâmetros
                $stmt->bind_param("ss", $email, $senha);

                // Executa a consulta
                $stmt->execute();

                // Obtém os resultados, se necessário
                $result = $stmt->get_result();
                
                // Fechar a declaração
                $stmt->close();

                $query = "SELECT * FROM logins_pendentes WHERE email = ? AND senha = ?";
                $stmt = $conexao->prepare($query);

                // Vincula os parâmetros
                $stmt->bind_param("ss", $email, $senha);

                // Executa a consulta
                $stmt->execute();

                // Obtém os resultados, se necessário
                $result_pendente = $stmt->get_result();
                
                // Fechar a declaração
                $stmt->close();

                if(mysqli_num_rows($result_pendente) > 0)
                {
                    echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "Seu email ainda não foi verificado.<br>Por favor, verifique seu email e clique no link de verificação."
                    });
                    </script>';
                }
                else if(mysqli_num_rows($result) < 1)
                {
                    echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "Seu numero ou senha estão incorretos.<br>Verifique se você já realizou um cadastro ou se digitou algo incorretamente."
                    });
                    </script>';
                }
                else
                {
                    $row = mysqli_fetch_assoc($result);
                    $nome = $row['nome'];
                    $numero = $row['numero'];
                    $email = $row['email'];
                    $area_solicitante = $row['area'];
                    $empresa = $row['empresa'];

                    $_SESSION['numero'] = $numero;
                    $_SESSION['nome'] = $nome;
                    $_SESSION['email'] = $email;
                    $_SESSION['area_solicitante'] = $area_solicitante;
                    $_SESSION['empresa'] = $empresa;

                header("Location: agendamento/tabela-agendamentos.php");
                exit();

                }
            }
        }
        else
        {
            
        }
    ?>
</body>
</html>