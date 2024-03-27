<?php
    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']))
    {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $query = "SELECT * FROM logins WHERE email = ? AND senha = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $senha);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) < 1)
        {
            echo '<script>
            Swal.fire({
                icon: "warning",
                title: "ATENÇÃO!",
                html: "Email ou senha incorretos! Caso esteja tendo problemas com sua senha, como esquecimento, entre em contato com o suporte. Se ainda não possui um login, realize seu cadastro.<br>Atenciosamente, Equipe Zeentech.<br>Contato: crpereira@zeentech.com"
            });
            </script>';
        }
        else
        {
            $row = mysqli_fetch_assoc($result);
            $nome = $row['nome'];
            $email = $row['email'];
            $status = $row['status'];

            if($status === 'APROVADO') {
                echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "SEJA BEM VINDO!",
                    html: "Login realizado com sucesso!<br>Atenciosamente, Equipe Zeentech.<br>Contato: crpereira@zeentech.com"
                }).then(function() {
                    window.location.href = \'tela_inicial.php\';
                });
                </script>';
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['senha'] = $senha;
            }
            elseif($status === 'PENDENTE'){
                echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Em processo!",
                    html:"Encontramos seu login, mas parece que ele ainda está em fase de aprovação!<br>Atenciosamente, Equipe Zeentech.<br>Contato: crpereira@zeentech.com"
                });
                </script>';
            }
            else {
                echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Problemas!",
                    html:"Parece que existe algum problema com seu login, por favor, entre em contato com nosso suporte!<br>Atenciosamente, Equipe Zeentech.<br>Contato: crpereira@zeentech.com"
                });
                </script>';
            }
        } 
    }
    else
    {
        
    }
?>