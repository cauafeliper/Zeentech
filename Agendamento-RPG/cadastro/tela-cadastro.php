<?php
    include_once('../config/config.php');
    session_start();

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>
<body>
    <main>
        <?php 
            function buscarOpcoes($tabela) {
                global $conexao;
                $opcoes = array();
        
                $query = "SELECT nome FROM $tabela";
                $result = mysqli_query($conexao, $query);
        
                while ($row = mysqli_fetch_assoc($result)) {
                    $opcoes[] = $row['nome'];
                }
        
                return $opcoes;
            }

            $opcoesEmpresas = buscarOpcoes('empresas');
            $opcoesAreas = buscarOpcoes('area_solicitante');
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="cadastro">
            <div class="titulo"><h1>Cadastre-se</h1></div>
            <div class="nome">
                <div class="label-nome">
                    <label for="nome"><img src="../assets/id-card-clip-alt.png" width="16" height="16" style="margin-right: 5px;">Nome:</label>
                </div>
                <div class="input-nome">
                    <input type="text" name="nome" id="nome" placeholder="Insira seu nome completo..." <?php if(isset($_POST['nome'])) { echo 'value="' . $_POST['nome'] . '"'; } ?>>
                </div>
            </div>
            <div class="empresa">
                <div class="label-empresa">
                    <label for="empresa"><img src="../assets/building.png" width="16" height="16" style="margin-right: 5px;">Empresa:</label>
                </div>
                <div class="input-empresa">
                    <select name="empresa" id="empresa">
                        <option value="">Selecione a empresa</option>
                        <?php
                            $query_empresa = "SELECT DISTINCT nome FROM empresas";
                            $result_empresa = mysqli_query($conexao, $query_empresa);
                            while ($row_empresa = mysqli_fetch_assoc($result_empresa)) {
                                $selected = (isset($_POST['empresa']) && $_POST['empresa'] == $row_empresa['nome']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row_empresa['nome']) . '" ' . $selected . '>' . htmlspecialchars($row_empresa['nome']) . '</option>';
                            }
                        ?>
                    </select>
                    <script>
                        $(document).ready(function () {
                        $('#empresa').select2();
                        });
                    </script>
                </div>
            </div>
            <div class="area">
                <div class="label-area">
                    <label for="area"><img src="../assets/briefcase.png" width="16" height="16" style="margin-right: 5px;">Área:</label>
                </div>
                <div class="input-area">
                    <select name="area" id="area" <?php if(isset($_POST['area'])) { echo 'value="' . $_POST['area'] . '"'; } ?>>
                        <option value="">Selecione a área</option>
                        <?php
                            $query_area = "SELECT DISTINCT nome FROM area_solicitante WHERE empresa = '$empresaSelecionada'";
                            $result_area = mysqli_query($conexao, $query_area);
                            while ($row_area = mysqli_fetch_assoc($result_area)) {
                                $selected = (isset($_POST['area']) && $_POST['area'] == $row_area['nome']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row_area['nome']) . '" ' . $selected . '>' . htmlspecialchars($row_area['nome']) . '</option>';
                            }
                        ?>
                    </select>
                    <script>
                        $(document).ready(function () {
                        $('#area').select2();
                        });
                    </script>
                </div>
            </div>
            <div class="numero">
                <div class="label-numero">
                    <label for="numero"><img src="../assets/phone-call.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Telefone:</label>
                </div>
                <div class="input-numero">
                    <input type="text" name="numero" id="numero" placeholder="Insira seu número de telefone..." maxlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" <?php if(isset($_POST['numero'])) { echo 'value="' . $_POST['numero'] . '"'; } ?>>
                </div>
            </div>
            <div class="email">
                <div class="label-email">
                    <label for="email"><img src="../assets/at.png" width="16" height="16" style="position: relative; top: 2px; margin-right: 5px;">Email:</label>
                </div>
                <div class="input-email">
                    <input type="email" name="email" id="email" placeholder="Insira seu email..." <?php if(isset($_POST['email'])) { echo 'value="' . $_POST['email'] . '"'; } ?> maxlength="100" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" <?php if(isset($_POST['email'])) { echo 'value="' . $_POST['email'] . '"'; } ?>>
                </div>
            </div>
            <div class="senha">
                <div class="label-senha">
                    <label for="senha"><img src="../assets/lock.png" width="16" height="16" style="margin-right: 5px;">Senha</label>
                </div>
                <div class="input-senha">
                    <input type="password" name="senha" id="senha" placeholder="Define, atentamente, sua senha...">
                </div>
                <hr>
            </div>
            <div class="senha_confirma">
                <div class="label-senha_confirma">
                    <label for="senha_confirma"><img src="../assets/lock.png" width="16" height="16" style="margin-right: 5px;">Confirmar senha</label>
                </div>
                <div class="input-senha_confirma">
                    <input type="password" name="senha_confirma" id="senha_confirma" placeholder="Confirme sua senha...">
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
            if (empty($_POST['nome']) || empty($_POST['empresa']) || empty($_POST['area']) || empty($_POST['numero']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['senha_confirma'])) {
                echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "Algum dos campos de cadastro está vazio!<br>Por favor, preencha todos os campos atentamente."
                    });
                </script>';
                /* exit(); */
            }
            elseif ($_POST['senha'] != $_POST['senha_confirma']) {
                echo '<script>
                    Swal.fire({
                        icon: "warning",
                        title: "ATENÇÃO!",
                        html: "As senhas não coincidem!<br>Por favor, digite a mesma senha nos dois campos."
                    });
                </script>';
                /* exit(); */
            } 
            else {
                $nome = $_POST['nome'];
                $empresa = $_POST['empresa'];
                $area = $_POST['area'];
                $numero = $_POST['numero'];
                $email = $_POST['email'];
                $senha = $_POST['senha'];

                $query = "SELECT COUNT(*) as count FROM cadastros WHERE email = ?";
                $stmt = $conexao->prepare($query);
                // Vincula os parâmetros
                $stmt->bind_param("s", $email);
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
                            html: "Seu email não se encontra no nosso banco de dados!<br>Entre em contato com o nosso suporte para mais informações.<br>Contato: crpereira@zeentech.com.br"
                        });
                    </script>';
                    /* exit(); */
                }
                else {
                    // Verificar se o e-mail já existe
                    $stmt_email = $conexao->prepare("SELECT COUNT(*) as count FROM logins WHERE email = ?");
                    $stmt_email->bind_param("s", $email);
                    $stmt_email->execute();
                    $result_email = $stmt_email->get_result();
                    $row_email = $result_email->fetch_assoc();
                    $stmt_email->close();

                    $stmt_email = $conexao->prepare("DELETE FROM logins_pendentes WHERE email = ?");
                    $stmt_email->bind_param("s", $email);
                    $stmt_email->execute();
                    $stmt_email->close();

                    if ($row_email['count'] > 0) {
                        echo '<script>
                            Swal.fire({
                                icon: "warning",
                                title: "ATENÇÃO!",
                                html: "Seu email já está cadastrado!<br>Tente logar com o seu email e senha."
                            });
                        </script>';
                        /* exit(); */
                    } else {
                        $token = bin2hex(random_bytes(16)); // 16 bytes, 32 caracteres hexadecimais
                        $tempoExpiracaoMinutos = 30;
                        $tempoExpiracaoSegundos = $tempoExpiracaoMinutos * 60;
                        $expiracao = date('Y-m-d H:i:s', strtotime("+$tempoExpiracaoSegundos seconds"));
                        
                        $query = "INSERT INTO logins_pendentes (numero, nome, empresa, area, email, senha, token, expiracao) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $conexao->prepare($query);

                        // Vinculando os parâmetros
                        $stmt->bind_param("ssssssss", $numero, $nome, $empresa, $area, $email, $senha, $token, $expiracao);

                        // Executando a consulta preparada
                        $result = $stmt->execute();

                        if ($result) {
                            $affected_rows = $stmt->affected_rows;
                            if ($affected_rows > 0) {
                                $linkLocal = 'http://localhost/Zeentech/Agendamento-RPG/cadastro/verificar_login.php?token='.$token;
                                $link = 'https://www.zeentech.com.br/volkswagen/Agendamento-RPG/cadastro/verificar_login.php?token='.$token;

                                require("../PHPMailer-master/src/PHPMailer.php"); 
                                require("../PHPMailer-master/src/SMTP.php"); 
                                $mail = new PHPMailer\PHPMailer\PHPMailer(); 
                                $mail->IsSMTP();
                                $mail->SMTPDebug = 0;
                                $mail->SMTPAuth = true;
                                $mail->SMTPSecure = 'tls'; 
                                $mail->Host = "equipzeentech.com"; 
                                $mail->Port = 587;
                                $mail->IsHTML(true); 
                                $mail->Username = "admin@equipzeentech.com"; 
                                $mail->Password = "Z3en7ech"; 
                                $mail->SetFrom("admin@equipzeentech.com", "Zeentech"); 
                                $mail->AddAddress($email);
                                
                                $mail->Subject = mb_convert_encoding("Verificação de email","Windows-1252","UTF-8"); 
                                $mail->Body = mb_convert_encoding('Voce fez cadastro com esse email no site do RPG. Para verificar seu email e confirmar seu cadastro, clique <a href="'.$linkLocal.'">aqui</a>. Esse link vai expirar em 30 minutos!<br>Caso a solicitação não tenha sido feita por você, apenas ignore este email.<br><br>Atenciosamente,<br>Equipe Zeentech.',"Windows-1252","UTF-8"); 

                                try{
                                    $mail->send();
                                    echo '<script>
                                        Swal.fire({
                                            icon: "success",
                                            title: "SUCESSO!",
                                            text: "Seu cadastro foi efetuado e aguarda para ser confirmado! Confira seu email para confirmar o cadastro.",
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
                                }
                                catch(Exception $e){
                                    echo '<script>
                                    Swal.fire({
                                        icon: "error",
                                        title: "Erro!",
                                        html: "O email não pôde ser enviado!<br>Por favor, tente novamente."
                                    });
                                    </script>';
                                }
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
        }
        ?>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#empresa').select2();

            var empresaSelecionada = $('#empresa').val();

            // Faz uma chamada AJAX para buscar as áreas correspondentes
            $.ajax({
                url: 'buscar_areas.php', // O script PHP que buscará as áreas
                method: 'POST',
                data: { empresa: empresaSelecionada },
                dataType: 'html',
                success: function (response) {
                    // Atualiza as opções do seletor de área com as opções recebidas
                    $('#area').html(response);
                    $('#area').val(valorSelecionado); // Define o valor selecionado anteriormente
                    $('#area').select2(); // Inicializa o select2 para o novo conteúdo
                },
                error: function () {
                    console.error('Erro ao buscar áreas.');
                }
            });

            // Adiciona um ouvinte de evento de alteração ao seletor de empresas
            $('#empresa').change(function () {
                // Obtém o valor da empresa selecionada
                var empresaSelecionada = $(this).val();
                valorSelecionado = $('#area').val();

                // Faz uma chamada AJAX para buscar as áreas correspondentes
                $.ajax({
                    url: 'buscar_areas.php', // O script PHP que buscará as áreas
                    method: 'POST',
                    data: { empresa: empresaSelecionada },
                    dataType: 'html',
                    success: function (response) {
                        // Atualiza as opções do seletor de área com as opções recebidas
                        $('#area').html(response);
                        $('#area').val(valorSelecionado); // Define o valor selecionado anteriormente
                        $('#area').select2(); // Inicializa o select2 para o novo conteúdo
                    },
                    error: function () {
                        console.error('Erro ao buscar áreas.');
                    }
                });
            });
        });
    </script>
</body>
</html>