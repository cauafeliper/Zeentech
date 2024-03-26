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
            echo '<script>window.location.href = \'../index.php\';</script>';
            exit();
        }
    ?>
    <header>
        <div class="header_logos">
            <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus"></a>
            <img src="../imgs/LogoCertificationTeam.png" alt="logo-certification-team" style="height: 95%;">
        </div>
        <ul>
            <li><a href="historico.php">Meus Agendamentos</a></li>
            <?php 
                $email = $_SESSION['email'];

                $query = "SELECT COUNT(*) as count FROM lista_adm WHERE email = ?";
                $stmt = mysqli_prepare($conexao, $query);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);
                $linha = mysqli_fetch_assoc($resultado);
                $admTrue = ($linha['count'] > 0);

                $query = "SELECT COUNT(*) as count FROM gestor WHERE email = ?";
                $stmt = mysqli_prepare($conexao, $query);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);
                $linha = mysqli_fetch_assoc($resultado);
                $gestorTrue = ($linha['count'] > 0);

                if ($admTrue || $gestorTrue) {
                    echo '<li><a href="gestor.php">Gestão</a></li>';
                    echo '<li><a href="../grafico/grafico.php">Gráfico</a></li>';
                }
            ?>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    
    <main style="height:fit-content; justify-content:center;">
        <div class="div_agendar">
            <iframe class="iframeGrafico" id="iframeGrafico" src="../grafico/grafico_dia.php" width="100%" height="100%" frameborder="0"></iframe>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formularioAgendamento" class="form__agendamento novo__agendamento">
                <div class="head_form">
                    <div class="titulo">
                        <h2>Novo Agendamento</h2>
                    </div>
                    <div class="solicitante">
                        <label for="solicitante">Solicitante:</label>
                        <input type="text" name="solicitante" id="solicitante" value="<?= $_SESSION['nome'] ?>" readonly style="display:none">
                        <h2 style="color: white;"><?= $_SESSION['nome'] ?></h2>
                        <label for="solicitante"><p style="font-size: smaller;">Empresa: <?= $_SESSION['empresa'] ?></p></label>
                        <label for="solicitante"><p style="font-size: smaller;">Área: <?= $_SESSION['area_solicitante'] ?></p></label>
                    </div>
                </div>
                <div class="dia grids">
                    <label for="dia">Dia: <span class="obrigatorio">*</span></label>
                    <input type="date" name="dia" id="dia" placeholder="Indique a data" oninput="diaGrafico()"  <?php if(isset($_POST['dia'])) { echo 'value="' . $_POST['dia'] . '"'; } ?>>
                    <script>
                        flatpickr("#dia", {
                            dateFormat: "Y-m-d",
                            minDate: "today"
                        });
                    </script>
                </div>
                <div class="hora_inicio grids">
                    <label for="hora_inicio">Hora de Início: <span class="obrigatorio">*</span></label>
                    <input type="time" id="hora_inicio" name="hora_inicio" <?php if(isset($_POST['hora_inicio'])) { echo 'value="' . $_POST['hora_inicio'] . '"'; } ?>>
                </div>
                <div class="hora_fim grids">
                    <label for="hora_fim">Hora do Fim: <span class="obrigatorio">*</span></label>
                    <input type="time" id="hora_fim" name="hora_fim" <?php if(isset($_POST['hora_fim'])) { echo 'value="' . $_POST['hora_fim'] . '"'; } ?>>
                </div>
                <div class="area_solicitante grids" style="display:none">
                    <label for="area_solicitante">Área do Solicitante:</label>
                    <input type="text" name="area_solicitante" value="<?= $_SESSION['area_solicitante'] ?>" readonly <?php if(isset($_POST['area_solicitante'])) { echo 'value="' . $_POST['area_solicitante'] . '"'; } ?>>
                </div>
                <div class="area_solicitada grids">
                    <label for="area_solicitada">Área Solicitada: <span class="obrigatorio">*</span></label>
                    <select name="area" id="area" >
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
                    <label for="objetivo">Objetivo do Teste: <span class="obrigatorio">*</span></label>
                    <select name="objetivo" id="objetivo" >
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
                    <label for="exclsv">Uso Exclusivo? <span class="obrigatorio">*</span></label>
                    <div style="display:flex; height: fit-content; width: 100%; align-items: center; justify-content: start;">
                        <label for="sim" style="font-size: smaller; margin-top: 5px; background-color: #001e50;">Sim:</label>
                        <input type="radio" id="sim" name="resposta" value="Sim" style="width: 20%;" <?php if(isset($_POST['resposta']) && $_POST['resposta'] === 'Sim') echo 'checked'; ?>>
            
                        <label for="nao" style="font-size: smaller; margin-top: 5px; background-color: #001e50;">Não:</label>
                        <input type="radio" id="nao" name="resposta" value="Não" style="width: 20%;" <?php if(isset($_POST['resposta']) && $_POST['resposta'] === 'Não') echo 'checked'; ?>>
                    </div>
                </div>
                <div style="display: none;">
                    <label for="status">Status:</label>
                    <input type="text" name="status" id="status" value="Pendente" readonly>
                </div>
                <div class="centro_custo grids">
                    <label for="centro_custo" class="centro_custo__label">Centro de Custo: <span class="obrigatorio">*</span></label>
                    <input type="text" name="centro_custo" id="centro_custo" class="centro_custo_txt" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?php if(isset($_POST['centro_custo'])) { echo 'value="' . $_POST['centro_custo']. '" '; } ?>>
                </div>
                <div class="carro grids">
                    <label for="carro" class="carro__label">Carro:</label>
                    <input type="text" name="carro" id="carro" class="carro_txt" maxlength="30" <?php if(isset($_POST['carro'])) { echo 'value="' . htmlspecialchars($_POST['carro']) . '" '; } ?>>
                </div>
                <div class="obs grids">
                    <label for="obs" class="obs__label">Observações:</label>
                    <textarea name="obs" id="obs" cols="48" rows="5" class="obs_txt" maxlength="500"><?php if(isset($_POST['obs'])) { echo htmlspecialchars($_POST['obs']); } ?></textarea>
                </div>
                <div class="enviar">
                    <input type="submit" name="submit" value="Agendar" id="submitBtn">
                </div>
            </form>
        </div>
        <?php
        if (isset($_POST['submit'])) {
            if (empty($_POST['solicitante']) OR empty($_POST['dia']) OR empty($_POST['hora_inicio']) OR empty($_POST['hora_fim']) OR empty($_POST['area_solicitante']) OR empty($_POST['area']) OR empty($_POST['objetivo']) OR empty($_POST['resposta']) OR empty($_POST['centro_custo'])) {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'ATENÇÃO!',
                        html: 'Algum dos campos obrigatórios está vazio! Por favor, preencha todos os campos atentamente.',
                    });
                </script>
                <style>
                .obrigatorio {
                    visibility: visible;
                }
                </style>";
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
                $centro_custo = $_POST['centro_custo'];
                $carro = $_POST['carro'];
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
                else if (!(strtotime($hora_inicio) >= strtotime('07:00') && strtotime($hora_inicio) <= strtotime('19:00')) || !(strtotime($hora_fim) >= strtotime('07:00') && strtotime($hora_fim) <= strtotime('19:00'))) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'ATENÇÃO!',
                            html: 'O horário deve estar entre 07:00 e 19:00!',
                        });
                    </script>";
                }
                else {
                    if ($exclsv == 'Sim'){
                        $query = "SELECT * FROM agendamentos 
                            WHERE dia = '$data' 
                            AND ((hora_inicio >= '$hora_inicio' AND hora_inicio <= '$hora_fim') 
                            OR (hora_fim >= '$hora_inicio' AND hora_fim <= '$hora_fim') OR ('$hora_inicio' >= hora_inicio AND '$hora_inicio' <= hora_fim) OR ('$hora_fim' >= hora_inicio AND '$hora_fim' <= hora_fim))
                            AND (status = 'Aprovado' OR status = 'Pendente')";
                    }
                    else {
                        if ($area == 'Obstáculos'){
                            $query = "SELECT * FROM agendamentos 
                                WHERE (area_pista = 'Pista Completa')
                                AND dia = '$data' 
                                AND ((hora_inicio >= '$hora_inicio' AND hora_inicio <= '$hora_fim') 
                                OR (hora_fim >= '$hora_inicio' AND hora_fim <= '$hora_fim') OR ('$hora_inicio' >= hora_inicio AND '$hora_inicio' <= hora_fim) OR ('$hora_fim' >= hora_inicio AND '$hora_fim' <= hora_fim))
                                AND (status = 'Aprovado' OR status = 'Pendente')";
                        }
                        else{
                            $query = "SELECT * FROM agendamentos 
                                WHERE (area_pista = '$area' OR area_pista = 'Pista Completa')
                                AND dia = '$data' 
                                AND ((hora_inicio >= '$hora_inicio' AND hora_inicio <= '$hora_fim') 
                                OR (hora_fim >= '$hora_inicio' AND hora_fim <= '$hora_fim') OR ('$hora_inicio' >= hora_inicio AND '$hora_inicio' <= hora_fim) OR ('$hora_fim' >= hora_inicio AND '$hora_fim' <= hora_fim))
                                AND (status = 'Aprovado' OR status = 'Pendente')";
                        }
                    }
    
                    $queryExc = "SELECT * FROM agendamentos 
                        WHERE exclsv = 'Sim'
                        AND dia = '$data' 
                        AND ((hora_inicio >= '$hora_inicio' AND hora_inicio <= '$hora_fim') 
                            OR (hora_fim >= '$hora_inicio' AND hora_fim <= '$hora_fim') OR ('$hora_inicio' >= hora_inicio AND '$hora_inicio' <= hora_fim) OR ('$hora_fim' >= hora_inicio AND '$hora_fim' <= hora_fim)) 
                        AND (status = 'Aprovado' OR status = 'Pendente')";
    
                    $result = mysqli_query($conexao, $query);
                    $resultExc = mysqli_query($conexao, $queryExc);
    
                    if ($result && mysqli_num_rows($result) > 0) {
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'ATENÇÃO!',
                                html: 'O horário que você selecionou está ocupado! Verifique a tabela ao lado e selecione um horário disponível.',
                            });
                        </script>";
                    } else {
                        if ($resultExc && mysqli_num_rows($resultExc) > 0) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'ATENÇÃO!',
                                    html: 'O horário que você selecionou está ocupado! Verifique a tabela ao lado e selecione um horário disponível.',
                                });
                            </script>";
                        }
                        else {
                            try {
                                // Preparar a declaração SQL
                                $stmt = $conexao->prepare("INSERT INTO agendamentos (area_pista, dia, hora_inicio, hora_fim, objtv, solicitante, numero_solicitante, empresa_solicitante, area_solicitante, exclsv, centro_de_custo, carro, obs, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                // Vincular os parâmetros
                                $stmt->bind_param("ssssssssssssss", $area, $data, $hora_inicio, $hora_fim, $objetivo, $solicitante, $numero_solicitante, $empresa_solicitante, $area_solicitante, $exclsv, $centro_custo, $carro, $obs, $status);
                                // Executar a consulta
                                $stmt->execute();
                                $affected_rows = $stmt->affected_rows;
                                // Get the last inserted id
                                $last_id = $conexao->insert_id;
                                // Prepare a SELECT statement to fetch the data of the last inserted row
                                $stmt = $conexao->prepare("SELECT * FROM agendamentos WHERE id = ?");
                                $stmt->bind_param("i", $last_id);
                                // Execute the SELECT statement
                                $stmt->execute();
                                // Get the result
                                $result = $stmt->get_result();
                                // Fetch the data and put it into an associative array
                                $dataInserida = $result->fetch_assoc();
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
                                            text: "Houve um erro na criação do agendamento.",
                                            confirmButtonText: "Ok",
                                            confirmButtonColor: "#001e50",
                                        });
                                    </script>';
                                }
                                else{
                                    $email_gestor = array();
                                    $query_gestor = "SELECT email FROM gestor";
                                    $result_gestor = mysqli_query($conexao, $query_gestor);
                                    while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
                                        $email_gestor[] = $row_gestor['email']; //email pros gestores
                                    }
        
                                    // Convert arrays to comma-separated strings
                                    $email_gestor_str = implode(",", $email_gestor);
                                    // Convert the associative array to a string
                                    $dataInserida_str = implode(",", array_map(function ($key, $value) {
                                        return "$key: '$value'";
                                    }, array_keys($dataInserida), $dataInserida));
        
                                    // Utiliza a função exec para chamar o script Python com o valor como argumento
                                    $output = shell_exec("python ../email/enviar_email.py " . escapeshellarg('agendamentoadm') . " " . escapeshellarg($email_gestor_str) . " " . escapeshellarg($email) . " " . escapeshellarg($dataInserida_str));
                                    $output = trim($output);
            
                                    if ($output !== 'sucesso'){
                                        echo '<script>
                                            Swal.fire({
                                                icon: "warning",
                                                title: "Erro no e-mail!",
                                                html: "O agendamento foi criado, porém houve um problema no envio do e-mail automático:<br>'.$output.'",
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
                                                title: "Solicitação criada!",
                                                text: "O agendamento foi criado com sucesso.",
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
            }
        }
        ?>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('submitBtn').addEventListener('click', function(event) {
                disablePage(event);
            });
        });

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