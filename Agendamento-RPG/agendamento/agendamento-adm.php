<?php
    include_once('../config/config.php');
    session_start();

    $expire_time = $_SESSION['expire_time'];

    // Verifica se a sessão existe e se o tempo de expiração foi atingido
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $expire_time)) {
        // Sessão expirada, destrói a sessão e redireciona para a página de login
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    // Atualiza o tempo da última atividade
    $_SESSION['last_activity'] = time();

include '../grafico/functions.php'; 

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/style-principal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php 
        if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
            session_unset();
            header('Location: ../index.php');
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
            header('Location: ../index.php');
        }

        if (!isset($_SESSION['avisoAgendamento'])){
            echo "
                <script>
                    Swal.fire({
                        title: 'Cuidado!',
                        text: 'Note que ao criar um agendamento por aqui, você estará agendando diretamente no banco de dados. Isso permite que o agendamento ignore as verificações de disponibilidade do horário e seja automaticamente aprovado, e deve ser usado apenas em ocasiões específicas onde tal ação é necessária. Certifique-se de que os dados inseridos estão corretos antes de prosseguir para evitar correr riscos.',
                        icon: 'warning',
                        allowOutsideClick: false,
                        confirmButtonText: 'Prosseguir'
                    });
                </script>
            ";
        }
        $_SESSION['avisoAgendamento'] = true;

    ?>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <ul>
            <li><a href="gerenciamento.php">Voltar</a></li>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    
    <main style="height:fit-content; justify-content:center;">
        <div class="div_agendar">
            <iframe class="iframeGrafico" id="iframeGrafico" src="../grafico/grafico_dia.php" width="100%" height="100%" frameborder="0"></iframe>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formularioAgendamento" class="form__agendamento novo__agendamento">
            <div class="titulo">
                <h2>Adicionar Agendamento</h2>
            </div>
            <div class="solicitante">
                <label for="solicitante">Solicitante:</label>
                <input type="text" name="solicitante" id="solicitante" value="<?= $_SESSION['nome'] ?>" readonly style="display:none">
                <h2 style="color: white;"><?= $_SESSION['nome'] ?></h2>
                <label for="solicitante"><p style="font-size: smaller;">Empresa: <?= $_SESSION['empresa'] ?></p></label>
                <label for="solicitante"><p style="font-size: smaller;">Área: <?= $_SESSION['area_solicitante'] ?></p></label>
            </div>
            <div class="dia grids">
                <label for="dia">Dia:</label>
                <input type="date" name="dia" id="dia" placeholder="Indique a data" oninput="diaGrafico()" required <?php if(isset($_POST['dia'])) { echo 'value="' . $_POST['dia'] . '"'; } ?>>
                <script>
                    flatpickr("#dia", {
                        dateFormat: "Y-m-d"
                    });
                </script>
            </div>
            <div class="hora_inicio grids">
                <label for="hora_inicio">Hora de Início:</label>
                <input type="time" id="hora_inicio" name="hora_inicio" min="07:00" max="19:00" required <?php if(isset($_POST['hora_inicio'])) { echo 'value="' . $_POST['hora_inicio'] . '"'; } ?>>
            </div>
            <div class="hora_fim grids">
                <label for="hora_fim">Hora do Fim:</label>
                <input type="time" id="hora_fim" name="hora_fim" min="07:00" max="19:00" required <?php if(isset($_POST['hora_fim'])) { echo 'value="' . $_POST['hora_fim'] . '"'; } ?>>
            </div>
            <div class="area_solicitante grids" style="display:none">
                <label for="area_solicitante">Área do Solicitante:</label>
                <input type="text" name="area_solicitante" value="<?= $_SESSION['area_solicitante'] ?>" readonly <?php if(isset($_POST['area_solicitante'])) { echo 'value="' . $_POST['area_solicitante'] . '"'; } ?>>
            </div>
            <div class="area_solicitada grids">
                <label for="area_solicitada">Área Solicitada:</label>
                <select name="area" id="area" required>
                    <option value="">Selecione a área da pista</option>
                    <?php
                        $query_area = "SELECT DISTINCT area FROM area_pista";
                        $result_area = mysqli_query($conexao, $query_area);
                        while ($row_area = mysqli_fetch_assoc($result_area)) {
                            $selected = (isset($_POST['area']) && $_POST['area'] == $row_area['area']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row_area['area']) . '" ' . $selected . '>' . htmlspecialchars($row_area['area']) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="objtv_teste grids">
                <label for="objetivo">Objetivo do Teste:</label>
                <select name="objetivo" id="objetivo" required>
                    <option value="">Selecione o Objetivo</option>
                    <?php
                        $query_objtv = "SELECT DISTINCT objtv FROM objtv_teste";
                        $result_objtv = mysqli_query($conexao, $query_objtv);
                        while ($row_objtv = mysqli_fetch_assoc($result_objtv)) {
                            $selected = (isset($_POST['objetivo']) && $_POST['objetivo'] == $row_objtv['objtv']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row_objtv['objtv']) . '" ' . $selected . '>' . htmlspecialchars($row_objtv['objtv']) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="exclsv grids">
                <label for="exclsv" style="width: 100%; margin-right: 50px; display: block;">Uso Exclusivo?</label>
                <label for="sim" style="font-size: smaller; width: 30%; margin-top: 5px; background-color: #001e50;">Sim:</label>
                <input type="radio" id="sim" name="resposta" value="Sim" style="width: 10%; margin-top: 10px;" <?php if(isset($_POST['resposta']) && $_POST['resposta'] === 'Sim') echo 'checked'; ?>>
    
                <label for="nao" style="font-size: smaller; width: 30%; margin-top: 5px; background-color: #001e50;">Não:</label>
                <input type="radio" id="nao" name="resposta" value="Não" style="width: 10%; margin-top: 10px;" <?php if(isset($_POST['resposta']) && $_POST['resposta'] === 'Não') echo 'checked'; ?>>
            </div>
            <div style="display: none;">
                <label for="status">Status:</label>
                <input type="text" name="status" id="status" value="Pendente" readonly>
            </div>
            <div class="obs">
                <label for="obs" class="obs__label">Observações:</label>
                <textarea name="obs" id="obs" cols="48" rows="5" class="obs" maxlength="500"><?php if(isset($_POST['obs'])) { echo htmlspecialchars($_POST['obs']); } ?></textarea>
            </div>
            <div class="enviar">
                <input type="submit" name="submit" value="Adicionar" id="submitBtn">
            </div>
        </form>
        </div>
        <?php
        if (isset($_POST['submit'])) {
            if (empty($_POST['solicitante']) OR empty($_POST['dia']) OR empty($_POST['hora_inicio']) OR empty($_POST['hora_fim']) OR empty($_POST['area_solicitante']) OR empty($_POST['area']) OR empty($_POST['objetivo']) OR empty($_POST['resposta'])) {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'ATENÇÃO!',
                        html: 'Algum dos campos está vazio! Por favor, preencha todos os campos atentamente.',
                    });
                </script>";
            } else {
                $solicitante = $_POST['solicitante'];
                $numero_solicitante = $_SESSION['numero'];
                $empresa_solicitante = $_SESSION['empresa'];
                $area_solicitante = $_POST['area_solicitante'];
                $data = $_POST['dia'];
                $hora_inicio = $_POST['hora_inicio'];
                $hora_fim = $_POST['hora_fim'];
                $area = $_POST['area'];
                $objetivo = $_POST['objetivo'];
                $exclsv = $_POST['resposta'];
                $status = 'Aprovado';
                $obs = $_POST['obs'];
                if ($area == 'Pista Completa') {
                    $exclsv = 'Sim';
                }

                if (($hora_fim <= $hora_inicio)) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'ATENÇÃO!',
                            html: 'O horário de início não pode ser maior ou igual que o horário de fim!',
                        });
                    </script>";
                }
                else {
                    // Preparar a declaração SQL
                    $stmt = $conexao->prepare("INSERT INTO agendamentos (area_pista, dia, hora_inicio, hora_fim, objtv, solicitante, numero_solicitante, empresa_solicitante, area_solicitante, exclsv, obs, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    // Vincular os parâmetros
                    $stmt->bind_param("ssssssssssss", $area, $data, $hora_inicio, $hora_fim, $objetivo, $solicitante, $numero_solicitante, $empresa_solicitante, $area_solicitante, $exclsv, $obs, $status);

                    $result = $stmt->execute();

                    if ($result) {
                        $affected_rows = $stmt->affected_rows;
                        if ($affected_rows > 0) {
                            
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
                                                    
                            $query_gestor = "SELECT email FROM gestor";
                            $result_gestor = mysqli_query($conexao, $query_gestor);
                            while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
                                $mail->addAddress($row_gestor['email']); //email pros gestores
                            }

                            $query_copias = "SELECT email FROM copias_email";
                            $result_copias = mysqli_query($conexao, $query_copias);
                            if ($result_copias->num_rows > 0) {
                                while ($row_copias = mysqli_fetch_assoc($result_copias)) {
                                    $email_frota = $row_copias['email'];
                                    $mail->AddCC($email_frota); //email pra copias
                                }
                            }

                            $hoje = new DateTime(date('Y-m-d'));
                            // Adicionar 30 dias
                            $hoje->add(new DateInterval('P30D'));
                            // Obter a nova data formatada
                            $data30 = $hoje->format('Y-m-d');
                            $linkLocal = "http://localhost/Zeentech/Agendamento-RPG/grafico/grafico31dias.php?diaInicio=".urlencode(date('Y-m-d'))."&diaFinal=".urlencode($data30);
                            $link = 'https://www.zeentech.com.br/volkswagen/Agendamento-RPG/grafico/grafico31dias.php?diaInicio='.urlencode(date('Y-m-d')).'&diaFinal='.urlencode($data30);
                            
                            $mail->IsHTML(true); 
                            $mail->Subject = mb_convert_encoding('Novo agendamento na Pista de Teste!',"Windows-1252","UTF-8");
                            $mail->Body = mb_convert_encoding("Um agendamento foi aprovado para a área da pista $area no dia $data de $hora_inicio até $hora_fim!<br>Para conferir a tabela de agendamentos dos próximos 30 dias, clique <a href=$link>aqui</a>.<br>Atenciosamente,<br><br>Equipe Zeentech.","Windows-1252","UTF-8");
                            $mail->send();

                            echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "SUCESSO!",
                                    text: "Seu agendamento foi criado com sucesso!",
                                    confirmButtonText: "OK",
                                    confirmButtonColor: "#001e50",
                                    allowOutsideClick: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "agendamento-adm.php";
                                    }
                                });
                            </script>';
                        } 
                        else {
                            echo '<script>
                                Swal.fire({
                                    icon: "warning",
                                    title: "ATENÇÃO!",
                                    html: "Ocorreu um erro no seu agendamento! Tente novamente.
                                    <br>"Erro: "'.$stmt->error.'",
                                });
                            </script>';
                        }
                    }
                    $stmt->close();
                }
            }
        }
        ?>
    </main>
    
    <footer>
        <div>
            <span>Desenvolvido por:  <img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2023 de Zeentech os direitos reservados</span>
        </div>
    </footer>
    <script>
        const formulario = document.getElementById("formularioAgendamento");

        formulario.addEventListener("submit", function (event) {
            const campos = formulario.querySelectorAll("input");
            let camposValidos = true;

            campos.forEach(function (campo) {
                if (!campo.validity.valid) {
                    camposValidos = false;
                }
            });

            if (!camposValidos) {
                alert("Preencha todos os campos obrigatórios.");
                event.preventDefault();
            }
        });
    </script>
    <script>
        function validateForm() {
        const calendario = document.getElementById("data").value;
            if (calendario === "") {
                alert("Selecione uma data antes de prosseguir.");
                return false;
            }
            return true;
        }
    </script>
    <script>
        flatpickr("#data", {
            dateFormat: "Y-m-d",
            minDate: "today"
        });
    </script>

    <script>
        function diaGrafico() {
        var valorData = document.getElementById('dia').value;

        // Use AJAX para enviar a nova data ao servidor PHP
        var xhr = new XMLHttpRequest();

        xhr.open('POST', '../grafico/atualizarDia.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // A resposta do servidor é recebida aqui
                console.log(xhr.responseText);

                // Recarregue o iframe para refletir a nova data
                document.getElementById('iframeGrafico').contentWindow.location.reload();
            }
        };

        xhr.send('novaData=' + valorData);
        };

        window.onload = function() {
            console.log('A página foi carregada!');
            var valorData = document.getElementById('dia').value;
            if (valorData === "") {
                valorData = 'atual';
            }

            // Use AJAX para enviar a nova data ao servidor PHP
            var xhr = new XMLHttpRequest();

            xhr.open('POST', '../grafico/atualizarDia.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // A resposta do servidor é recebida aqui
                    console.log(xhr.responseText);

                    // Recarregue o iframe para refletir a nova data
                    document.getElementById('iframeGrafico').contentWindow.location.reload();
                }
            };

            xhr.send('novaData=' + valorData);
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('submitBtn').addEventListener('click', function(event) {
                disablePage(event);
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