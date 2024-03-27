<?php
    include_once('../config/config.php');
    session_start();

    $expire_time = $_SESSION['expire_time'];

    // Verifica se a sessão existe e se o tempo de expiração foi atingido
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $expire_time)) {
        // Sessão expirada, destrói a sessão e redireciona para a página de login
        session_unset();
        session_destroy();
        echo '<script>window.location.href = \'../index.php\';</script>';
        exit();
    }

    // Atualiza o tempo da última atividade
    $_SESSION['last_activity'] = time();

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/style-gestor.css">
    <link rel="stylesheet" href="../estilos/gerenciamento.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

</head>
<body>
<?php
        if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
            session_unset();
            echo '<script>window.location.href = \'../index.php\';</script>';
        }
        
        $email = $_SESSION['email'];

        $query = "SELECT * FROM lista_adm WHERE email = ?";
        $stmt = $conexao->prepare($query);
        // Vincula os parâmetros
        $stmt->bind_param("s", $email);
        // Executa a consulta
        $stmt->execute();
        // Obtém os resultados, se necessário
        $result = $stmt->get_result();
        // Fechar a declaração
        $stmt->close();
        
        if (!$result || mysqli_num_rows($result) === 0) {
            session_unset();
            echo '<script>window.location.href = \'../index.php\';</script>';
        }

        if (!isset($_SESSION['aviso'])){
            echo "
                <script>
                    Swal.fire({
                        title: 'Cuidado!',
                        text: 'Note que ao adicionar valores por aqui, você estará modificando diretamente o banco de dados. Certifique-se de que os dados inseridos estão corretos antes de prosseguir para evitar correr riscos.',
                        icon: 'warning',
                        allowOutsideClick: false,
                        confirmButtonText: 'Prosseguir'
                    });
                </script>
            ";
        }
        $_SESSION['aviso'] = true;
    ?>
    <?php ////////////////////////////////////////////////////////////////////////////////////

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addObjtv'])) {
        if (!empty($_GET['novoObjtv'])) {
            $novoObjtv = $_GET['novoObjtv'];

            $query_verifica = "select * from objtv_teste where objtv = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novoObjtv);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe um Objetivo com esse nome no nosso banco de dados! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {
                $query_addObjtv = "INSERT INTO objtv_teste(objtv) VALUES (?)";
                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addObjtv);
                // Vincular os parâmetros
                $stmt->bind_param("s", $novoObjtv);
                // Executar a consulta
                $stmt->execute();
                // Fechar a declaração
                $stmt->close();

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Valor adicionado!",
                        text: "O valor foi adicionado à tabela com sucesso.",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#001e50",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona o usuário para a página de login
                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                        }
                    });
                </script>';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvObjtv'])) {
        $removerObjtv = $_GET['removerObjtv'];
        $query_removerObjtv = "DELETE FROM objtv_teste WHERE objtv = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerObjtv);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerObjtv);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addSolic'])) {
        if (!empty($_GET['novoSolic']) && !empty($_GET['empresa'])) {
            $novoSolic = $_GET['novoSolic'];
            $empresaSelec = $_GET['empresa'];

            $query_verifica = "select * from area_solicitante where nome = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novoSolic);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe uma Área Solicitante com esse nome no nosso banco de dados! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {

                $query_addSolic = "INSERT INTO area_solicitante(nome, empresa) VALUES (?,?)";

                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addSolic);

                // Vincular os parâmetros
                $stmt->bind_param("ss", $novoSolic, $empresaSelec);

                // Executar a consulta
                $stmt->execute();

                // Fechar a declaração
                $stmt->close();

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Valor adicionado!",
                        text: "O valor foi adicionado à tabela com sucesso.",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#001e50",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona o usuário para a página de login
                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                        }
                    });
                </script>';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvSolic'])) {
        $removerSolic = $_GET['removerSolic'];
        $query_removerSolic = "DELETE FROM area_solicitante WHERE nome = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerSolic);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerSolic);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addEmpresa'])) {
        if (!empty($_GET['novoEmpresa'])) {
            $novoEmpresa = $_GET['novoEmpresa'];

            $query_verifica = "select * from empresas where nome = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novoEmpresa);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe uma Empresa com esse nome no nosso banco de dados! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {

                $query_addEmpresa = "INSERT INTO empresas(nome) VALUES (?)";

                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addEmpresa);

                // Vincular os parâmetros
                $stmt->bind_param("s", $novoEmpresa);

                // Executar a consulta
                $stmt->execute();

                // Fechar a declaração
                $stmt->close();

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Valor adicionado!",
                        text: "O valor foi adicionado à tabela com sucesso.",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#001e50",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona o usuário para a página de login
                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                        }
                    });
                </script>';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvEmpresa'])) {
        $removerEmpresa = $_GET['removerEmpresa'];
        $query_removerEmpresa = "DELETE FROM empresas WHERE nome = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerEmpresa);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerEmpresa);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        $query_removerEmpresa = "DELETE FROM area_solicitante WHERE empresa = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerEmpresa);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerEmpresa);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addCadastro'])) {
        if (!empty($_GET['novoCadastro'])) {
            $novoCadastro = $_GET['novoCadastro'];

            $query_verifica = "select * from cadastros where email = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novoCadastro);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe um Cadastro com esse email no nosso banco de dados! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {
                try {
                    $query_addCadastro = "INSERT INTO cadastros(email) VALUES (?)";
                    // Preparar a declaração SQL
                    $stmt = $conexao->prepare($query_addCadastro);
                    // Vincular os parâmetros
                    $stmt->bind_param("s", $novoCadastro);
                    // Executar a consulta
                    $stmt->execute();
                    $affected_rows = $stmt->affected_rows;
                    // Fechar a declaração
                    $stmt->close();
                } catch (Exception $e) {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            html: "Houve um problema na consulta sql:<br>'.$e->getMessage().'",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#001e50",
                        });
                    </script>';
                }
                finally{
                    if (!isset($affected_rows)) {
                        echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Erro!",
                                text: "Houve um erro ao adicionar o valor à tabela.",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#001e50",
                            });
                        </script>';
                    }
                    else{
                        // Utiliza a função exec para chamar o script Python com o valor como argumento
                        $output = shell_exec("python ../email/enviar_email.py " . escapeshellarg('addcadastro') . " " . escapeshellarg($novoCadastro));
                        $output = trim($output);

                        if ($output !== 'sucesso'){
                            echo '<script>
                                Swal.fire({
                                    icon: "warning",
                                    title: "Erro no e-mail!",
                                    html: "O valor foi adicionado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
                                    confirmButtonText: "Ok",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false
                                })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                    }
                                });
                            </script>';  
                        }
                        else{
                            echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "Valor adicionado!",
                                    text: "O valor foi adicionado à tabela com sucesso.",
                                    confirmButtonText: "Ok",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                    }
                                });
                            </script>';
                        }    
                    }
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvCadastro'])) {
        $removerCadastro = $_GET['removerCadastro'];
        $query_removerCadastro = "DELETE FROM cadastros WHERE email = ?";
        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerCadastro);
        // Vincular os parâmetros
        $stmt->bind_param("s", $removerCadastro);
        // Executar a consulta
        $stmt->execute();
        // Fechar a declaração
        $stmt->close();

        $query_removerCadastro = "DELETE FROM logins WHERE email = ?";
        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerCadastro);
        // Vincular os parâmetros
        $stmt->bind_param("s", $removerCadastro);
        // Executar a consulta
        $stmt->execute();
        // Fechar a declaração
        $stmt->close();

        $query_removerCadastro = "DELETE FROM logins_pendentes WHERE email = ?";
        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerCadastro);
        // Vincular os parâmetros
        $stmt->bind_param("s", $removerCadastro);
        // Executar a consulta
        $stmt->execute();
        // Fechar a declaração
        $stmt->close();

        $query_removerCadastro = "DELETE FROM lista_adm WHERE email = ?";
        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerCadastro);
        // Vincular os parâmetros
        $stmt->bind_param("s", $removerCadastro);
        // Executar a consulta
        $stmt->execute();
        // Fechar a declaração
        $stmt->close();

        $query_removerCadastro = "DELETE FROM gestor WHERE email = ?";
        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerCadastro);
        // Vincular os parâmetros
        $stmt->bind_param("s", $removerCadastro);
        // Executar a consulta
        $stmt->execute();
        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addCopia'])) {
        if (!empty($_GET['novaCopia'])) {
            $novaCopia = $_GET['novaCopia'];

            $query_verifica = "select * from copias_email where email = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novaCopia);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe uma Frota com esse email no nosso banco de dados! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {

                $query_addCopia = "INSERT INTO copias_email(email) VALUES (?)";

                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addCopia);

                // Vincular os parâmetros
                $stmt->bind_param("s", $novaCopia);

                // Executar a consulta
                $stmt->execute();

                // Fechar a declaração
                $stmt->close();

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Valor adicionado!",
                        text: "O valor foi adicionado à tabela com sucesso.",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#001e50",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona o usuário para a página de login
                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                        }
                    });
                </script>';
                }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvCopia'])) {
        $removerCopia = $_GET['removerCopia'];
        $query_removerCopia = "DELETE FROM copias_email WHERE email = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerCopia);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerCopia);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addDistrib'])) {
        if (!empty($_GET['novaDistrib'])) {
            $novaDistrib = $_GET['novaDistrib'];

            $query_verifica = "select * from lista_distribuicao where email = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novaDistrib);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe esse email no nosso banco de dados da Lista de Distribuição! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {

                $query_addCopia = "INSERT INTO lista_distribuicao(email) VALUES (?)";

                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addCopia);

                // Vincular os parâmetros
                $stmt->bind_param("s", $novaDistrib);

                // Executar a consulta
                $stmt->execute();

                // Fechar a declaração
                $stmt->close();

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Valor adicionado!",
                        text: "O valor foi adicionado à tabela com sucesso.",
                        confirmButtonText: "Ok",
                        confirmButtonColor: "#001e50",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redireciona o usuário para a página de login
                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                        }
                    });
                </script>';
                }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvDistrib'])) {
        $removerCopia = $_GET['removerDistrib'];
        $query_removerCopia = "DELETE FROM lista_distribuicao WHERE email = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerCopia);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerCopia);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addGestor'])) {
        if (!empty($_GET['novoGestor'])) {
            $novoGestor = $_GET['novoGestor'];

            $query_verifica = "select * from gestor where email = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novoGestor);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe um Gestor com esse email no nosso banco de dados! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {
                try {
                    $query_addGestor = "INSERT INTO gestor(email) VALUES (?)";
                    // Preparar a declaração SQL
                    $stmt = $conexao->prepare($query_addGestor);
                    // Vincular os parâmetros
                    $stmt->bind_param("s", $novoGestor);
                    // Executar a consulta
                    $stmt->execute();
                    $affected_rows = $stmt->affected_rows;
                    // Fechar a declaração
                    $stmt->close();
                } catch (Exception $e) {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            html: "Houve um problema na consulta sql:<br>'.$e->getMessage().'",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#001e50",
                        });
                    </script>';
                }
                finally{
                    if (!isset($affected_rows)) {
                        echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Erro!",
                                text: "Houve um erro ao adicionar o valor à tabela.",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#001e50",
                            });
                        </script>';
                    }
                    else{
                        // Verificar se o email está na tabela logins
                        $stmt_login = $conexao->prepare("SELECT * FROM logins WHERE email = ?");
                        $stmt_login->bind_param("s", $novoGestor);
                        $stmt_login->execute();
                        $result_login = $stmt_login->get_result();
                        $stmt_login->close();

                        if($result_login->num_rows > 0) {
                            $loginTrue = true;
                        } else {
                            $loginTrue = false;
                        }

                        if ($loginTrue) {
                            // Utiliza a função exec para chamar o script Python com o valor como argumento
                            $output = shell_exec("python ../email/enviar_email.py " . escapeshellarg('addgestor') . " " . escapeshellarg($novoGestor));
                            $output = trim($output);

                            if ($output !== 'sucesso'){
                                echo '<script>
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Erro no e-mail!",
                                        html: "O valor foi adicionado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
                                        confirmButtonText: "Ok",
                                        confirmButtonColor: "#001e50",
                                        allowOutsideClick: false
                                    })
                                    .then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                        }
                                    });
                                </script>';  
                            }
                            else{
                                echo '<script>
                                    Swal.fire({
                                        icon: "success",
                                        title: "Valor adicionado!",
                                        text: "O valor foi adicionado à tabela com sucesso.",
                                        confirmButtonText: "Ok",
                                        confirmButtonColor: "#001e50",
                                        allowOutsideClick: false
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                        }
                                    });
                                </script>';
                            }    
                        }
                        else{
                            echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "Valor adicionado!",
                                    text: "O valor foi adicionado à tabela com sucesso.",
                                    confirmButtonText: "Ok",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                    }
                                });
                            </script>';
                        }
                    }
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvGestor'])) {
        $removerGestor = $_GET['removerGestor'];
        $query_removerGestor = "DELETE FROM gestor WHERE email = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerGestor);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerGestor);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addAdm'])) {
        if (!empty($_GET['novoAdm'])) {
            $novoAdm = $_GET['novoAdm'];

            $query_verifica = "select * from lista_adm where email = ?";
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_verifica);
            // Vincular os parâmetros
            $stmt->bind_param("s", $novoAdm);
            // Executar a consulta
            $stmt->execute();
            // Armazenar o resultado
            $stmt->store_result();
            // Obter o número de linhas
            $num_linhas = $stmt->num_rows;
            // Fechar a declaração
            $stmt->close();

            if ($num_linhas > 0) {
                echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Valor repetido!",
                        text: "Já existe um Administrador com esse email no nosso banco de dados! Insira um valor que ainda não exista!"
                    });
                </script>';
            } else {
                try {
                    $query_addAdm = "INSERT INTO lista_adm(email) VALUES (?)";
                    // Preparar a declaração SQL
                    $stmt = $conexao->prepare($query_addAdm);
                    // Vincular os parâmetros
                    $stmt->bind_param("s", $novoAdm);
                    // Executar a consulta
                    $stmt->execute();
                    $affected_rows = $stmt->affected_rows;
                    // Fechar a declaração
                    $stmt->close();
                } catch (Exception $e) {
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            html: "Houve um problema na consulta sql:<br>'.$e->getMessage().'",
                            confirmButtonText: "Ok",
                            confirmButtonColor: "#001e50",
                        });
                    </script>';
                }
                finally{
                    if (!isset($affected_rows)) {
                        echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Erro!",
                                text: "Houve um erro ao adicionar o valor à tabela.",
                                confirmButtonText: "Ok",
                                confirmButtonColor: "#001e50",
                            });
                        </script>';
                    }
                    else{
                        // Verificar se o email está na tabela logins
                        $stmt_login = $conexao->prepare("SELECT * FROM logins WHERE email = ?");
                        $stmt_login->bind_param("s", $novoAdm);
                        $stmt_login->execute();
                        $result_login = $stmt_login->get_result();
                        $stmt_login->close();

                        if($result_login->num_rows > 0) {
                            $loginTrue = true;
                        } else {
                            $loginTrue = false;
                        }

                        if ($loginTrue) {
                            // Utiliza a função exec para chamar o script Python com o valor como argumento
                            $output = shell_exec("python ../email/enviar_email.py " . escapeshellarg('addadm') . " " . escapeshellarg($novoAdm));
                            $output = trim($output);

                            if ($output !== 'sucesso' || !isset($output)){
                                echo '<script>
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Erro no e-mail!",
                                        html: "O valor foi adicionado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
                                        confirmButtonText: "Ok",
                                        confirmButtonColor: "#001e50",
                                        allowOutsideClick: false
                                    })
                                    .then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                        }
                                    });
                                </script>';  
                            }
                            else{
                                echo '<script>
                                    Swal.fire({
                                        icon: "success",
                                        title: "Valor adicionado!",
                                        text: "O valor foi adicionado à tabela com sucesso.",
                                        confirmButtonText: "Ok",
                                        confirmButtonColor: "#001e50",
                                        allowOutsideClick: false
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                        }
                                    });
                                </script>';
                            }    
                        }
                        else{
                            echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "Valor adicionado!",
                                    text: "O valor foi adicionado à tabela com sucesso.",
                                    confirmButtonText: "Ok",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "'.$_SERVER['PHP_SELF'].'";
                                    }
                                });
                            </script>';
                        }
                    }
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvAdm'])) {
        $removerAdm = $_GET['removerAdm'];
        $query_removerAdm = "DELETE FROM lista_adm WHERE email = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerAdm);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerAdm);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addAgendamento'])) {
        echo '<script>window.location.href = "agendamento-adm.php";</script>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvAgendamento'])) {
        $removerAgendamento = $_GET['removerAgendamento'];
        $query_removerAgendamento = "DELETE FROM agendamentos WHERE id = ?";

        // Preparar a declaração SQL
        $stmt = $conexao->prepare($query_removerAgendamento);

        // Vincular os parâmetros
        $stmt->bind_param("s", $removerAgendamento);

        // Executar a consulta
        $stmt->execute();

        // Fechar a declaração
        $stmt->close();

        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Valor removido!",
                text: "O valor foi removido da tabela com sucesso.",
                confirmButtonText: "Ok",
                confirmButtonColor: "#001e50",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona o usuário para a página de login
                    window.location.href = "'.$_SERVER['PHP_SELF'].'";
                }
            });
        </script>';
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////
    ?>
    <header>
        <a href="https://www.vwco.com.br/" tarGET="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <img src="../imgs/LogoCertificationTeam.png" alt="logo-certification-team" style="height: 95%;">
        <ul>
            <li><a href="gestor.php">Gestão</a></li>

            <li><a href="../grafico/grafico.php">Gráfico</a></li>

            <li><a href="tabela-agendamentos.php">Início</a></li>

            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    
    <main>
        <div class="addRmvG">

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="objtv_addRmv">
                    <div class="addRmv_label">
                        <h2>Objetivos</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.8rem;" type="text" name="novoObjtv" placeholder="Novo Objetivo">
                            <input style="height: 1.5rem;" type="submit" name="addObjtv" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerObjtv" id="selec_objtv">
                                <option value="">Remover Objetivo</option>
                                <?php
                                $query_objtv = "SELECT DISTINCT objtv FROM objtv_teste";
                                $result_objtv = mysqli_query($conexao, $query_objtv);
                                while ($row_objtv = mysqli_fetch_assoc($result_objtv)) {
                                    $selected = ($objtv == $row_objtv['objtv']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row_objtv['objtv']) . '" ' . $selected . '>' . htmlspecialchars($row_objtv['objtv']) . '</option>';
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_objtv').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvObjtv" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="solic_addRmv">
                    <div class="addRmv_label">
                        <h2>Áreas Solicitantes</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.5rem;" style="height: 1.8rem;" type="text" name="novoSolic" placeholder="Nova Área Solicitante">
                            <select name="empresa" id="empresa">
                                <option value="">Empresa</option>
                                <?php
                                    $query_empresa = "SELECT DISTINCT nome FROM empresas";
                                    $result_empresa = mysqli_query($conexao, $query_empresa);
                                    while ($row_empresa = mysqli_fetch_assoc($result_empresa)) {
                                        $selected = ($empresa == $row_empresa['nome']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_empresa['nome']) . '" ' . $selected . '>' . htmlspecialchars($row_empresa['nome']) . '</option>';
                                    }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#empresa').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="addSolic" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerSolic" id="selec_solic">
                                <option value="">Remover Área Solicitante</option>
                                <?php
                                $query_solic = "SELECT DISTINCT nome FROM area_solicitante";
                                $result_solic = mysqli_query($conexao, $query_solic);
                                while ($row_solic = mysqli_fetch_assoc($result_solic)) {
                                    $selected = ($solic == $row_solic['nome']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row_solic['nome']) . '" ' . $selected . '>' . htmlspecialchars($row_solic['nome']) . '</option>';
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_solic').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvSolic" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="empresa_addRmv">
                    <div class="addRmv_label">
                        <h2>Empresas</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.8rem;" type="text" name="novoEmpresa" placeholder="Nova Empresa">
                            <input style="height: 1.5rem;" type="submit" name="addEmpresa" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerEmpresa" id="selec_empresa">
                                <option value="">Remover Empresa</option>
                                <?php
                                $query_empresa = "SELECT DISTINCT nome FROM empresas";
                                $result_empresa = mysqli_query($conexao, $query_empresa);
                                if ($result_empresa && mysqli_num_rows($result_empresa) > 0){
                                    while ($row_empresa = mysqli_fetch_assoc($result_empresa)) {
                                        $selected = ($empresa == $row_empresa['nome']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_empresa['nome']) . '" ' . $selected . '>' . htmlspecialchars($row_empresa['nome']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_empresa').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvEmpresa" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="empresa_addRmv">
                    <div class="addRmv_label">
                        <h2>Cadastros</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.8rem;" type="text" name="novoCadastro" placeholder="Email novo">
                            <input style="height: 1.5rem;" type="submit" name="addCadastro" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerCadastro" id="selec_Cadastro">
                                <option value="">Remover Cadastro</option>
                                <?php
                                $query_cadastro = "SELECT DISTINCT email FROM cadastros";
                                $result_cadastro = mysqli_query($conexao, $query_cadastro);
                                if ($result_cadastro && mysqli_num_rows($result_cadastro) > 0){
                                    while ($row_cadastro = mysqli_fetch_assoc($result_cadastro)) {
                                        $selected = ($cadastro == $row_cadastro['email']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_cadastro['email']) . '" ' . $selected . '>' . htmlspecialchars($row_cadastro['email']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_Cadastro').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvCadastro" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="empresa_addRmv">
                    <div class="addRmv_label">
                        <h2>Frota</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.8rem;" type="text" name="novaCopia" placeholder="Email novo">
                            <input style="height: 1.5rem;" type="submit" name="addCopia" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerCopia" id="selec_Copia">
                                <option value="">Remover email</option>
                                <?php
                                $query_copia = "SELECT DISTINCT email FROM copias_email";
                                $result_copia = mysqli_query($conexao, $query_copia);
                                if ($result_copia && mysqli_num_rows($result_copia) > 0){
                                    while ($row_copia = mysqli_fetch_assoc($result_copia)) {
                                        $selected = ($copia == $row_copia['email']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_copia['email']) . '" ' . $selected . '>' . htmlspecialchars($row_copia['email']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_Copia').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvCopia" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="empresa_addRmv">
                    <div class="addRmv_label">
                        <h2>Lista de Distribuição</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.8rem;" type="text" name="novaDistrib" placeholder="Email novo">
                            <input style="height: 1.5rem;" type="submit" name="addDistrib" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerDistrib" id="selec_Distrib">
                                <option value="">Remover email</option>
                                <?php
                                $query_copia = "SELECT DISTINCT email FROM lista_distribuicao";
                                $result_copia = mysqli_query($conexao, $query_copia);
                                if ($result_copia && mysqli_num_rows($result_copia) > 0){
                                    while ($row_copia = mysqli_fetch_assoc($result_copia)) {
                                        $selected = ($copia == $row_copia['email']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_copia['email']) . '" ' . $selected . '>' . htmlspecialchars($row_copia['email']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_Distrib').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvDistrib" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="empresa_addRmv">
                    <div class="addRmv_label">
                        <h2>Gestores</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.8rem;" type="text" name="novoGestor" placeholder="Email novo">
                            <input style="height: 1.5rem;" type="submit" name="addGestor" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerGestor" id="selec_Gestor">
                                <option value="">Remover Gestor</option>
                                <?php
                                $query_gestor = "SELECT DISTINCT email FROM gestor";
                                $result_gestor = mysqli_query($conexao, $query_gestor);
                                if ($result_gestor && mysqli_num_rows($result_gestor) > 0){
                                    while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
                                        $selected = ($gestor == $row_gestor['email']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_gestor['email']) . '" ' . $selected . '>' . htmlspecialchars($row_gestor['email']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_Gestor').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvGestor" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>
            
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="empresa_addRmv">
                    <div class="addRmv_label">
                        <h2>Administradores</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.8rem;" type="text" name="novoAdm" placeholder="Email novo">
                            <input style="height: 1.5rem;" type="submit" name="addAdm" value="Adicionar" id="submitBtn">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerAdm" id="selec_Adm">
                                <option value="">Remover Adm</option>
                                <?php
                                $query_adm = "SELECT DISTINCT email FROM lista_adm";
                                $result_adm = mysqli_query($conexao, $query_adm);
                                if ($result_adm && mysqli_num_rows($result_adm) > 0){
                                    while ($row_adm = mysqli_fetch_assoc($result_adm)) {
                                        $selected = ($adm == $row_adm['email']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_adm['email']) . '" ' . $selected . '>' . htmlspecialchars($row_adm['email']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_Adm').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvAdm" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmvG">
                <div class="empresa_addRmv">
                    <div class="addRmv_label">
                        <h2>Agendamentos</h2>
                    </div>
                    <div class="valores">
                        <div class="add">
                            <h4>Adicionar</h4>
                            <input style="height: 1.5rem;" type="submit" name="addAgendamento" value="Adicionar">
                        </div>
                        <div class="rmv">
                            <h4>Remover</h4>
                            <select name="removerAgendamento" id="selec_Agendamento">
                                <option value="">Remover por id</option>
                                <?php
                                $query_copia = "SELECT DISTINCT id FROM agendamentos";
                                $result_copia = mysqli_query($conexao, $query_copia);
                                if ($result_copia && mysqli_num_rows($result_copia) > 0){
                                    while ($row_copia = mysqli_fetch_assoc($result_copia)) {
                                        $selected = ($copia == $row_copia['id']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row_copia['id']) . '" ' . $selected . '>' . htmlspecialchars($row_copia['id']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                $(document).ready(function () {
                                $('#selec_Agendamento').select2({width: '100%'});
                                });
                            </script>
                            <input style="height: 1.5rem;" type="submit" name="rmvAgendamento" value="Remover" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </main>
    
    <footer>
        <div>
            <span style="font-size: 16px">Desenvolvido por: <img src="../imgs/ZeentechIDT.png" alt="logo-zeentech" style="margin-left: 10px;"></span>
        </div>
        <div class="copyright">
            <span style="font-size: 14px">Copyright © 2024 de Zeentech, todos os direitos reservados.</span>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var submitBtns = document.querySelectorAll('input#submitBtn');
            submitBtns.forEach(function(submitBtn) {
                submitBtn.addEventListener('click', function(event) {
                    disablePage(event);
                });
            });
        });

        function disablePage() {
            // Adicione um overlay para indicar que a página está em estado de "loading"
            var overlay = document.createElement('div');
            overlay.classList.add('loading-overlay'); // Adiciona a classe para identificação posterior
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.zIndex = '9999';
            overlay.innerHTML = '<div class="overlay"><img class="gifOverlay" src="../assets/truck-unscreen2.gif"><h1>Carregando...</h1></div>';
            document.body.appendChild(overlay);
        }
    </script>
</body>
</html>