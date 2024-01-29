<?php
    include_once('../config/config.php');
    session_start();
?>

<?php
include '../grafico/functions.php'; 

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/style-principal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php 
        if (!isset($_SESSION['numero']) || empty($_SESSION['numero'])) {
            header('Location: ../index.php');
            exit();
        }
    ?>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <ul>
            <?php 

                $numero = $_SESSION['numero'];

                $query = "SELECT COUNT(*) as count FROM numero_adm WHERE numero = '$numero'";
                $resultado = mysqli_query($conexao, $query);
                $linha = mysqli_fetch_assoc($resultado);
                $admTrue = ($linha['count'] > 0);

                if ($admTrue) {
                    echo '<li><a href="gestor.php">Gestão</a></li>';
                    echo '<li><a href="../grafico/grafico.php">Gráfico</a></li>';
                }

                else {
                    
                }
            ?>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    
    <main style="height:fit-content; justify-content:center;">
        <div id="tabelaAgendamentos" class="tabela" style="height:auto">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
                <input type="date" id="data" name="data" placeholder="Indique a data" class="filtro__data">
                <select name="area_pista" id="area_pista" required>
                    <option value="">Área</option>
                    <?php 
                        $query_area = "SELECT area FROM area_pista";
                        $result_area = mysqli_query($conexao, $query_area);

                        while ($row = mysqli_fetch_assoc($result_area)) {
                            echo '<option value="' . $row['area'] . '">' . $row['area'] . '</option>';
                        }
                    ?>
                    <option value="todas_areas">Todas Áreas</option>
                </select>
                <input type="submit" value="Filtrar" class="submit">
            </form>
            <table>
            <?php 
                if (isset($_POST['data']) && isset($_POST['area_pista'])) {
                    $data = $_POST['data'];
                    $area_pista = $_POST['area_pista'];

                    if ($area_pista === 'todas_areas') {
                        $query = "SELECT * FROM agendamentos WHERE dia = '$data' AND status = 'Aprovado'";
                        $result = mysqli_query($conexao, $query);

                        echo '<caption>Todas Áreas</caption>';
                        echo '<tr>';
                        echo '<th>Início</th><th>Fim</th><th>Objetivo</th><th>Uso exclusivo?</th><th>Área</th>';
                        echo '</tr>';

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $row["hora_inicio"] . "</td><td>" . $row["hora_fim"] . "</td><td>" . $row["objtv"] . "</td><td>" . $row["exclsv"] . "</td><td>" . $row["area_pista"] . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Ainda não existem agendamentos para esta data e área.</td></tr>";
                        }
                    } else {
                        $query = "SELECT * FROM agendamentos WHERE dia = '$data' AND status = 'Aprovado' AND area_pista = '$area_pista' UNION SELECT * FROM agendamentos WHERE dia = '$data' AND status = 'Aprovado' AND area_pista = 'Pista Completa'";
                        $result = mysqli_query($conexao, $query);

                        echo '<caption>' . "$area_pista" . '</caption>';
                        echo '<tr>';
                        echo '<th>Início</th><th>Fim</th><th>Veículo</th><th>Objetivo</th><th>Uso exclusivo?</th><th>Área</th>';
                        echo '</tr>';

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $row["hora_inicio"] . "</td><td>" . $row["hora_fim"] . "</td><td>" . $row["objtv"] . "</td><td>" . $row["exclsv"] . "</td><td>" . $row["area_pista"] . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Ainda não existem agendamentos para esta data e área.</td></tr>";
                        }
                    }
                } else {
                    echo "<caption>Selecione os termos para consulta.</caption>";
                } 
                ?>
            </table>
        </div>
        <div class="div_agendar">
            <iframe class="iframeGrafico" id="iframeGrafico" src="../grafico/grafico_dia.php" width="100%" height="100%" frameborder="0"></iframe>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formularioAgendamento" class="form__agendamento novo__agendamento">
            <div class="titulo">
                <h2>Novo Agendamento</h2>
            </div>
            <div class="solicitante">
                <label for="solicitante">Solicitante:</label>
                <input type="text" name="solicitante" id="solicitante" value="<?= $_SESSION['nome'] ?>" readonly style="display:none">
                <h2 style="color: white;"><?= $_SESSION['nome'] ?></h2>
                <label for="solicitante">Empresa: <?= $_SESSION['empresa'] ?></label>
                <label for="solicitante">Área: <?= $_SESSION['area_solicitante'] ?></label>
            </div>
            <div class="dia grids">
                <label for="dia">Dia:</label>
                <input type="date" name="dia" id="dia" placeholder="Indique a data" oninput="diaGrafico()" required>
                <script>
                    flatpickr("#dia", {
                        dateFormat: "Y-m-d",
                        minDate: "today"
                    });
                </script>
            </div>
            <div class="hora_inicio grids">
                <label for="hora_inicio">Hora de Início:</label>
                <input type="time" id="hora_inicio" name="hora_inicio" min="07:00" max="19:00" required>
            </div>
            <div class="hora_fim grids">
                <label for="hora_fim">Hora do Fim:</label>
                <input type="time" id="hora_fim" name="hora_fim" min="07:00" max="19:00" required>
            </div>
            <div class="area_solicitante grids" style="display:none">
                <label for="area_solicitante">Área do Solicitante:</label>
                <input type="text" name="area_solicitante" value="<?= $_SESSION['area_solicitante'] ?>" readonly>
            </div>
            <div class="area_solicitada grids">
                <label for="area_solicitada">Área Solicitada:</label>
                <select name="area" id="area" required>
                    <option value="">Selecione a área da pista</option>
                    <?php
                        $query_area = "SELECT DISTINCT area FROM area_pista";
                        $result_area = mysqli_query($conexao, $query_area);
                        while ($row_area = mysqli_fetch_assoc($result_area)) {
                            echo '<option value="' . $row_area['area'] . '">' . $row_area['area'] . '</option>';
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
                            $selected = ($objtv == $row_objtv['objtv']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row_objtv['objtv']) . '" ' . $selected . '>' . htmlspecialchars($row_objtv['objtv']) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="exclsv grids">
                <label for="exclsv" style="margin-right: 50px; display: block;">Uso Exclusivo?</label>
                <label for="sim" style="font-size: smaller; width: 30%; margin-top: 5px; background-color: #001e50;">Sim:</label>
                <input type="radio" id="sim" name="resposta" value="Sim" style="width: 10%; margin-top: 10px;" required>
    
                <label for="nao" style="font-size: smaller; width: 30%; margin-top: 5px; background-color: #001e50;">Não:</label>
                <input type="radio" id="nao" name="resposta" value="Não" checked style="width: 10%; margin-top: 10px;">
            </div>
            <div style="display: none;">
                <label for="status">Status:</label>
                <input type="text" name="status" id="status" value="Pendente" readonly>
            </div>
            <div class="obs">
                <label for="obs" class="obs__label">Observações:</label>
                <textarea name="obs" id="obs" cols="48" rows="5" class="obs" maxlength="500"></textarea>
            </div>
            <div class="enviar">
                <input type="submit" name="submit" value="Agendar">
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
                $status = $_POST['status'];
                $obs = $_POST['obs'];

                $query = "SELECT * FROM agendamentos 
                    WHERE (area_pista = '$area' OR area_pista = 'Pista Completa')
                    AND dia = '$data' 
                    AND ((hora_inicio <= '$hora_inicio' AND hora_fim >= '$hora_inicio') 
                    OR (hora_inicio <= '$hora_fim' AND hora_fim >= '$hora_fim'))
                    AND (status = 'Aprovado' OR status = 'Pendente')";


                $result = mysqli_query($conexao, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'ATENÇÃO!',
                            html: 'O horário que você selecionou está ocupado! Verifique a tabela ao lado e selecione um horário disponível.',
                        });
                    </script>";
                } else {
                    // Preparar a declaração SQL
                    $stmt = $conexao->prepare("INSERT INTO agendamentos (area_pista, dia, hora_inicio, hora_fim, objtv, solicitante, numero_solicitante, empresa_solicitante, area_solicitante, exclsv, obs, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    // Vincular os parâmetros
                    $stmt->bind_param("ssssssssssss", $area, $data, $hora_inicio, $hora_fim, $objetivo, $solicitante, $numero_solicitante, $empresa_solicitante, $area_solicitante, $exclsv, $obs, $status);

                    $result = $stmt->execute();
                    $stmt->close();

                    if ($result) {
                        $affected_rows = mysqli_affected_rows($conexao);
                        if ($affected_rows > 0) {
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
                                        window.location.href = "tabela-agendamentos.php";
                                    }
                                });
                            </script>';
                            $query_email = "SELECT email FROM logins WHERE numero = '$numero'";
                            $result_email = mysqli_query($conexao, $query_email);
                            $row_email = mysqli_fetch_assoc($result_email);
                            $email = $row_email['email'];
                            
                            require("../PHPMailer-master/src/PHPMailer.php"); 
                            require("../PHPMailer-master/src/SMTP.php"); 
                            $mail = new PHPMailer\PHPMailer\PHPMailer(); 
                            $mail->IsSMTP();
                            $mail->SMTPDebug = 1;
                            $mail->SMTPAuth = true;
                            $mail->SMTPSecure = 'tsl'; 
                            $mail->Host = "equipzeentech.com"; 
                            $mail->Port = 587;
                            $mail->IsHTML(true); 
                            $mail->Username = "admin@equipzeentech.com"; 
                            $mail->Password = "Z3en7ech"; 
                            $mail->SetFrom("admin@equipzeentech.com", "Zeentech"); 
                            $mail->AddAddress($email); 
                            $mail->Subject = "Solicitação criada com sucesso!"; 
                            $mail->Body = utf8_decode('Sua soicitação foi criada com sucesso!<br>Assim que houver uma resposta do Gestor encarregado, você receberá um email dizendo se sua solicitação foi aprovada ou não.<br>Atenciosamente,<br>Equipe Zeentech.'); 
                            $mail->send();

                            $mail->ClearAddresses();

                            $mail->addAddress('crpereira@zeentech.com.br');
                            $mail->Subject = 'Nova solicitação de agendamento para pista a Pista de Teste!';
                            $mail->Body = utf8_decode('Uma nova solicitação para o agendamento da Pista de Teste foi criada pelo colaborador(a) ' . $solicitante .' no dia ' . $data . ' e horário de ' . $hora_inicio . ' até ' . $hora_fim . ' com objetivo de ' . $objetivo .'. Essa nova solicitação aguarda sua resposta!<br>Atenciosamente,<br>Equipe Zeentech.');
                            $mail->send();
                        } 
                        else {
                            echo '<script>
                                Swal.fire({
                                    icon: "warning",
                                    title: "ATENÇÃO!",
                                    html: "Ocorreu um erro no seu agendamento! Tente novamente.",
                                });
                            </script>';
                        }
                    }
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
            var valorData = 'atual';

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

</body>
</html>