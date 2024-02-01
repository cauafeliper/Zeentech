<?php
    include_once('config/config.php');
    session_start();
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
            <div class="numero-login">
                <div class="numero-login-label">
                    <label for="numero"><img src="assets/phone-call.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Telefone:</label>
                </div>
                <div class="numero-login-input">
                    <input type="text" name="numero" id="numero" placeholder="Insira seu número de telefone cadastrado..." maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
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
        </form>
    </main>
    <?php
        if (isset($_POST['submit'])) {
            if (empty($_POST['numero']) || empty($_POST['senha']))
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
                $numero = $_POST['numero'];
                $senha = $_POST['senha'];

                $query = "SELECT * FROM logins WHERE numero = ? AND senha = ?";
                $stmt = $conexao->prepare($query);

                // Vincula os parâmetros
                $stmt->bind_param("ss", $numero, $senha);

                // Executa a consulta
                $stmt->execute();

                // Obtém os resultados, se necessário
                $result = $stmt->get_result();
                
                // Fechar a declaração
                $stmt->close();

                if(mysqli_num_rows($result) < 1)
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