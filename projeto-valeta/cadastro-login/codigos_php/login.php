<?php
    if(isset($_POST['submit']) && !empty($_POST['re']) && !empty($_POST['senha']))
    {
        include_once('config.php');
        $re = $_POST['re'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM logins WHERE re = '$re' and senha = '$senha'";

        $result = $conexao->query($sql);

        if(mysqli_num_rows($result) < 1)
        {
            session_start();
            unset($_SESSION['re']);
            unset($_SESSION['senha']);
            echo '<script>
            Swal.fire({
                icon: "warning",
                title: "ATENÇÃO!",
                html: "RE ou senha estão incorretos! Caso esteja tendo problemas com sua senha, como esquecimento, entre em contato com o suporte. Se ainda não possui um login, realize seu cadastro."
            });
            </script>';
        }
        else
        {
            $row = mysqli_fetch_assoc($result);
            $nomeC = $row['nome'];
            $email = $row['email'];

            session_start();
            $_SESSION['re'] = $re;
            $_SESSION['senha'] = $senha;
            $_SESSION['nome'] = $nomeC;
            $_SESSION['email'] = $email;
            header('Location: tabela-agendamentos.php');
        } 
    }
    else
    {
        
    }
?>