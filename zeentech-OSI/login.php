<?php
    session_start();
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
            header('Location: erroLogin.php');
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
            header('Location: pagprincipal.php');
        } 
    }
    else
    {
        header('Location: index.php');
    }
?>