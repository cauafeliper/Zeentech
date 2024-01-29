<?php
    include_once('../config/config.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/cadastro-login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="cadastro">
            <div class="titulo"><h1>Cadastre-se</h1></div>
            <div class="nome">
                <div class="label-nome">
                    <label for="nome"><img src="https://icons.iconarchive.com/icons/iconsmind/outline/16/Boy-icon.png" width="16" height="16" style="margin-right: 5px;">Nome:</label>
                </div>
                <div class="input-nome">
                    <input type="text" name="nome" id="nome" placeholder="Insira seu nome completo...">
                </div>
            </div>
            <div class="area">
                <div class="label-area">
                    <label for="area"><img src="https://icons.iconarchive.com/icons/iconsmind/outline/16/Office-icon.png" width="16" height="16" style="margin-right: 5px;">Área:</label>
                </div>
                <div class="input-area">
                    <input type="text" name="area" id="area" placeholder="Insira sua area de atuação. Exemplo: RH.">
                </div>
            </div>
            <div class="numero">
                <div class="label-numero">
                    <label for="numero"><img src="https://icons.iconarchive.com/icons/iconsmind/outline/16/ID-Card-icon.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Telefone:</label>
                </div>
                <div class="input-numero">
                    <input type="text" name="numero" id="numero" placeholder="Insira seu número de telefone..." maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                </div>
            </div>
            <div class="email">
                <div class="label-email">
                    <label for="email"><img src="https://icons.iconarchive.com/icons/iconsmind/outline/16/Email-icon.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Email:</label>
                </div>
                <div class="input-email">
                    <input type="email" name="email" id="email" placeholder="Insira seu email...">
                </div>
            </div>
            <div class="senha">
                <div class="label-senha">
                    <label for="senha"><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/User-Interface-Password-icon.png" width="16" height="16" style="margin-right: 5px;">Senha</label>
                </div>
                <div class="input-senha">
                    <input type="password" name="senha" id="senha" placeholder="Define, atentamente, sua senha...">
                </div>
                <hr>
            </div>
            <div class="submit">
                <input type="submit" name="submit" value="Cadastrar">
            </div>
            <div class="login-tela">
                <a href="../index.php"><button type="button">Login</button></a>
            </div>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            if (empty($_POST['nome']) || empty($_POST['area']) || empty($_POST['numero']) || empty($_POST['email']) || empty($_POST['senha'])) {
                echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "Algum dos campos de cadastro está vazio!<br>Por favor, preencha todos os campos atentamente."
                    });
                </script>';
                exit();
            } else {
                $nome = $_POST['nome'];
                $area = $_POST['area'];
                $numero = $_POST['numero'];
                $email = $_POST['email'];
                $senha = $_POST['senha'];

                $query = "SELECT COUNT(*) as count FROM numero_lista WHERE numero = ?";
                $stmt = $conexao->prepare($query);
                // Vincula os parâmetros
                $stmt->bind_param("s", $numero);
                // Executa a consulta
                $stmt->execute();
                // Obtém os resultados, se necessário
                $result = $stmt->get_result();
                // Fechar a declaração
                $stmt->close();
                $row = mysqli_fetch_assoc($result);

                if ($row['count'] == 0) {
                    echo '<script>
                        Swal.fire({
                            icon: "warning",
                            title: "ATENÇÃO!",
                            html: "Seu número não se encontra no nosso banco de dados!<br>Entre em contato com o nosso suporte para mais informações.<br>Contato: crpereira@zeentech.com.br"
                        });
                    </script>';
                    exit();
                }
                
                $numero_query = "SELECT COUNT(*) as count FROM logins WHERE numero = '$numero'";
                $result_numero = $conexao->query($numero_query);
                $row_numero = mysqli_fetch_assoc($result_numero);

                // Verificar se o número já existe
                $stmt_numero = $conexao->prepare("SELECT COUNT(*) as count FROM logins WHERE numero = ?");
                $stmt_numero->bind_param("s", $numero);
                $stmt_numero->execute();
                $result_numero = $stmt_numero->get_result();
                $row_numero = $result_numero->fetch_assoc();
                $stmt_numero->close();

                // Verificar se o e-mail já existe
                $stmt_email = $conexao->prepare("SELECT COUNT(*) as count FROM logins WHERE email = ?");
                $stmt_email->bind_param("s", $email);
                $stmt_email->execute();
                $result_email = $stmt_email->get_result();
                $row_email = $result_email->fetch_assoc();
                $stmt_email->close();

                if ($row_numero['count'] > 0) {
                    echo '<script>
                        Swal.fire({
                            icon: "warning",
                            title: "ATENÇÃO!",
                            html: "Seu número já está cadastrado!<br>Caso não esteja conseguindo logar no site mesmo com seu número e senha, entre em contato com nosso suporte.<br>Contato: crpereira@zeentech.com.br"
                        });
                    </script>';
                    exit();
                } elseif ($row_email['count'] > 0) {
                    echo '<script>
                        Swal.fire({
                            icon: "warning",
                            title: "ATENÇÃO!",
                            html: "Seu email já está cadastrado!<br>Tente logar com o seu número e senha."
                        });
                    </script>';
                    exit();
                } else {
                    $result = mysqli_query($conexao, "INSERT INTO logins(numero, nome, area, email, senha) VALUES('$numero','$nome','$area','$email', '$senha')");

                    if ($result) {
                        $affected_rows = mysqli_affected_rows($conexao);
                        if ($affected_rows > 0) {
                            echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "SUCESSO!",
                                    text: "Seu cadastro foi efetuado com sucesso!",
                                    confirmButtonText: "Login",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redireciona o usuário para a página desejada
                                        window.location.href = "../index.php";
                                    }
                                });
                            </script>';
                        } else {
                            echo '<script>
                                Swal.fire({
                                    icon: "warning",
                                    title: "ATENÇÃO!",
                                    html: "Ocorreu um erro no seu cadastro!<br>Tente novamente."
                                });
                            </script>';
                        }
                    }
                }
            }
        }
        ?>
    </main>
</body>
</html>