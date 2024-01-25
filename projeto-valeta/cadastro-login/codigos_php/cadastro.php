<?php 
    if(isset($_POST['submit']))
    {
        include_once('../config.php');
        if(!empty($_POST['reCad']) && !empty($_POST['nomeC']) && !empty($_POST['email']) && !empty($_POST['senhaCad'])){
            $reCad = $_POST['reCad'];
            $nome = $_POST['nomeC'];
            $email = $_POST['email'];
            $senhaCad = $_POST['senhaCad'];

            $re_query_funcionarios = "SELECT COUNT(*) as count FROM lista_re WHERE re = '$reCad'";
            $result_re_funcionarios = $conexao->query($re_query_funcionarios);
            $row_re_funcionarios = mysqli_fetch_assoc($result_re_funcionarios);

            if ($row_re_funcionarios['count'] == 0) {

                echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "Parece que seu RE não está no nosso banco de dados, entre em contato com o suporte para mais detalhes!"
                    });
                    </script>';
                    exit();
            }
            $re_query = "SELECT COUNT(*) as count FROM logins WHERE re = '$reCad'";
            $result_re = $conexao->query($re_query);
            $row_re = mysqli_fetch_assoc($result_re);

            $email_query = "SELECT COUNT(*) as count FROM logins WHERE email = '$email'";
            $result_email = $conexao->query($email_query);
            $row_email = mysqli_fetch_assoc($result_email);

            if ($row_re['count'] > 0) {
                echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    html: "Ocorreu algum erro durante o cadastro. Alguma das informações preenchidas já está cadastrada, verifique se você já não possui login!"
                });
                </script>';
                exit();
            } 
            elseif ($row_email['count'] > 0) {
                echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "ATENÇÃO!",
                    html: "Ocorreu algum erro durante o cadastro. Alguma das informações preenchidas já está cadastrada, verifique se você já não possui login!"
                });
                </script>';
                exit();
            } 
            else {
                $result = mysqli_query($conexao, "INSERT INTO logins(re, nome, email, senha) VALUES('$reCad','$nome','$email','$senhaCad')");

                header('Location: ../index.php');
            }
        }
        else {
            echo '<script>
            Swal.fire({
                icon: "warning",
                title: "ATENÇÃO!",
                html: "Alguns dos campos estava em branco, por favor preencha todos os campos atentamente antes de continuar."
            });
            </script>';
        }
    }
    else
    {
  
    }
?>