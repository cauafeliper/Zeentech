<?php
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];

        function validarEmail($email) {
            $padraoEmail = '/^extern\.\w+@volkswagen\.com\.br$/';
            return preg_match($padraoEmail, $email);
        }

        if (!validarEmail($email)) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Erro ao realizar cadastro!",
                    html: "Seu email não parece estar no formato correto (extern.\'...\'@volkswagen.com.br)!<br>Atenciosamente, Equipe Zeentech.<br>Contato: crpereira@zeentech.com.br",
                });
            </script>';
            exit;
        } else {
            if (!empty($_POST['email']) && !empty($_POST['nome']) && !empty($_POST['senha'])) {
                $email = $_POST['email'];
                $nome = $_POST['nome'];
                $senha = $_POST['senha'];

                // Consulta preparada para evitar SQL injection
                $query = "SELECT COUNT(*) as count FROM logins WHERE email = ?";
                $stmt = $conexao->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt_result = $stmt->get_result();
                $query_count = $stmt_result->fetch_assoc();

                if ($query_count['count'] > 0) {
                    echo '<script>
                        Swal.fire({
                            icon: "warning",
                            title: "ATENÇÃO!",
                            html: "Parece que seu email já está cadastrado!<br>Se você não consegue acessar o site, verifique se confirmou o seu cadastro por email!<br>Se mesmo assim encontrar problemas, entre em contato com nosso suporte!<br>Atenciosamente, Equipe Zeentech!<br>Contato: crpereira@zeentech.com.br"
                        });
                        </script>';
                    exit();
                } else {
                    // Consulta preparada para evitar SQL injection
                    $insert_query = "INSERT INTO logins(nome, email, senha, status) VALUES(?, ?, ?, 'PENDENTE')";
                    $insert_stmt = $conexao->prepare($insert_query);
                    $insert_stmt->bind_param("sss", $nome, $email, $senha);
                    $insert_stmt->execute();

                    echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Cadastro Realizado com Sucesso!",
                            html: "Agora você deve aguardar a aprovação do seu cadastro!<br>Atenciosamente, Equipe Zeentech.<br>Contato: crpereira@zeentech.com.br"
                        });
                        </script>';
                    exit();
                }
            } else {
                echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "Alguns dos campos estavam em branco, por favor preencha todos os campos atentamente antes de continuar."
                    });
                    </script>';
            }
        }
    } 
    else {
        
    }
?>
