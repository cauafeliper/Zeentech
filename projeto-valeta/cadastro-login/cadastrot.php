<?php 
    if(isset($_POST['submit']) && !empty($_POST['reCad']) && !empty($_POST['nomeC']) && !empty($_POST['email']) && !empty($_POST['senhaCad']))
    {
        include_once('../config.php');

        $reCad = $_POST['reCad'];
        $nome = $_POST['nomeC'];
        $email = $_POST['email'];
        $senhaCad = $_POST['senhaCad'];

        $re_query_funcionarios = "SELECT COUNT(*) as count FROM lista_re WHERE re = '$reCad'";
        $result_re_funcionarios = $conexao->query($re_query_funcionarios);
        $row_re_funcionarios = mysqli_fetch_assoc($result_re_funcionarios);

    if ($row_re_funcionarios['count'] == 0) {

        header('Location: semRE.php');
        exit();
    }
        $re_query = "SELECT COUNT(*) as count FROM logins WHERE re = '$reCad'";
        $result_re = $conexao->query($re_query);
        $row_re = mysqli_fetch_assoc($result_re);

        $email_query = "SELECT COUNT(*) as count FROM logins WHERE email = '$email'";
        $result_email = $conexao->query($email_query);
        $row_email = mysqli_fetch_assoc($result_email);

        if ($row_re['count'] > 0) {
            header('Location: cadastroB.php');
            exit();
        } elseif ($row_email['count'] > 0) {
            header('Location: cadastroB.php');
            exit();
        } else {
            $result = mysqli_query($conexao, "INSERT INTO logins(re, nome, email, senha) VALUES('$reCad','$nome','$email','$senhaCad')");

            header('Location: ../index.php');
        }
    }
    else
    {
        header('Location: cadastroB.php');
    }
?>