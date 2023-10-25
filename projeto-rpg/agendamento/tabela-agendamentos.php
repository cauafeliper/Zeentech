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
        include_once('../config/config.php');
        session_start();
        if (!isset($_SESSION['chapa']) || empty($_SESSION['chapa'])) {
            header('Location: ../index.php');
            exit();
        }
    ?>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <ul>
            <?php 
                include_once('../config/config.php');

                $chapa = $_SESSION['chapa'];

                $query = "SELECT COUNT(*) as count FROM chapa_adm WHERE chapa = '$chapa'";
                $resultado = mysqli_query($conexao, $query);
                $linha = mysqli_fetch_assoc($resultado);
                $admTrue = ($linha['count'] > 0);

                if ($admTrue) {
                    echo '<li><a href="gestor.php">Gestão</a></li>';
                }

                else {
                    
                }
            ?>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    
    <main>
        <div class="tabela">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
                <input type="date" id="data" name="data" placeholder="Indique a data" class="filtro__data">
                <select name="area" id="area" required>
                    <option value="">Área</option>
                    <option value="vda">Área de avaliação dinâmica(VDA)</option>
                    <option value="nvh">Pista de Pass by Noise(NVH)</option>
                    <option value="obstaculos">Pistas estruturais(obstáculos)</option>
                    <option value="rampa_12_20">Rampas 12% e 20%</option>
                    <option value="rampa_40">Rampa de 40%</option>
                    <option value="rampa_60">Rampa de 60%</option>
                    <option value="asfalto">Apenas asfalto</option>
                    <option value="todas_areas">Todas as Áreas</option>
                </select>
                <input type="submit" value="Filtrar" class="submit">
            </form>
            <table>
            <?php 
                include_once('../config/config.php');
                if($_SERVER["REQUEST_METHOD"] === "POST"){

                    if(isset($_POST['data']) & isset($_POST['area'])){
                        $data = $_POST['data'];
                        $area = $_POST['area'];

                        if($area === 'todas_areas') {
                            $query = "SELECT * FROM asfalto WHERE dia = '$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM exclusivo WHERE dia = '$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM nvh WHERE dia = '$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM obstaculos WHERE dia = '$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM rampa_12_20 WHERE dia = '$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM rampa_40 WHERE dia = '$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM rampa_60 WHERE dia = '$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM vda WHERE dia = '$data' AND status = 'Aprovado'";

                            $result = mysqli_query($conexao, $query);

                            $tabelaNomes = array(
                                "asfalto" => "Asfalto",
                                "exclusivo" => "Completa",
                                "nvh" => "NVH",
                                "obstaculos" => "Obstáculos",
                                "rampa_12_20" => "Rampa 12% e 20%",
                                "rampa_40" => "Rampa 40%",
                                "rampa_60" => "Rampa 60%",
                                "vda" => "VDA"
                            );

                            echo '<caption>Todas Áreas</caption>';

                            echo '<tr>';
                            echo '<th>Início</th><th>Fim</th><th>Veículo</th><th>Objetivo</th><th>Uso exclusivo?</th><th>Área</th>';
                            echo '</tr>';
                            echo '<tr>';
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $tabelaNome = $tabelaNomes[$area];
                                echo "<td>" . $row["hora_inicio"] . "</td><td>" . $row["hora_fim"] . "</td><td>" . $row["veic"] . "</td><td>" . $row["obtjv"] . "</td><td>" . $row["exclsv"] . "</td><td>" . $tabelaNome . "</td>";
                            }
                            else {
                                echo "<td>Ainda</td><td>não</td><td>existem</td><td>agendamentos</td><td>nesse</td><td>dia.</td>";
                            }
                            echo '</tr>';
                        }
                        else {
                            $query = "SELECT * FROM $area WHERE dia='$data' AND status = 'Aprovado'
                                    UNION
                                    SELECT * FROM exclusivo WHERE dia = '$data' AND status = 'Aprovado'";
                            $result = mysqli_query($conexao, $query);
                            
                            if ($area === 'vda') {
                                $titulo__tabela = 'Área de Avaliação Dinâmica(VDA)';
                            }
                            elseif ($area === 'nvh') {
                                $titulo__tabela = 'Pista de Pass by Noise(NVH)';
                            }
                            elseif ($area === 'obstaculos') {
                                $titulo__tabela = 'Pistas Estruturais(Obstáculos)';
                            }
                            elseif ($area === 'rampa_12_20') {
                                $titulo__tabela = 'Rampa 12% e 20%';
                            }
                            elseif ($area === 'rampa_40') {
                                $titulo__tabela = 'Rampa 40%';
                            }
                            elseif ($area === 'rampa_60') {
                                $titulo__tabela = 'Rampa 60%';
                            }
                            elseif ($area === 'asfalto') {
                                $titulo__tabela = 'Apenas Asfalto';
                            }
                            echo '<caption>' . "$titulo__tabela" . '</caption>';

                            echo '<tr>';
                            echo '<th>Início</th><th>Fim</th><th>Veículo</th><th>Objetivo</th><th>Uso exclusivo?</th>';
                            echo '</tr>';
                            echo '<tr>';
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo "<td>" . $row["hora_inicio"] . "</td><td>" . $row["hora_fim"] . "</td><td>" . $row["veic"] . "</td><td>" . $row["obtjv"] . "</td><td>" . $row["exclsv"] . "</td>";
                            }
                            else {
                                echo "<td>Ainda</td><td>não</td><td>existem</td><td>agendamentos</td><td>nesse dia.</td>";
                            }
                            echo '</tr>';
                        }
                    }
                }
                else {
                    echo "<caption>Selecione os termos para consulta.</caption>";
                } 
            ?>
            </table>
        </div>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="formularioAgendamento" class="form__agendamento novo__agendamento">
            <div class="titulo">
                <h2>Novo Agendamento</h2>
            </div>
            <div class="solicitante">
                <label for="solicitante">Solicitante:</label>
                <input type="text" name="solicitante" id="solicitante" value="<?= $_SESSION['nome'] ?>" readonly>
            </div>
            <div class="dia grids">
                <label for="dia">Dia:</label>
                <input type="date" name="dia" id="dia" placeholder="Indique a data">
                <script>
                    flatpickr("#dia", {
                        dateFormat: "Y-m-d",
                        minDate: "today"
                    });
                </script>
            </div>
            <div class="hora_inicio grids">
                <label for="hora_inicio">Hora de Início:</label>
                <input type="time" name="hora_inicio" id="hora_inicio">
            </div>
            <div class="hora_fim grids">
                <label for="hora_fim">Hora do Fim:</label>
                <input type="time" name="hora_fim" id="hora_fim">
            </div>
            <div class="area_solicitante grids">
                <label for="area_solicitante">Área do Solicitante:</label>
                <input type="text" name="area_solicitante" value="<?= $_SESSION['area_solicitante'] ?>" readonly>
            </div>
            <div class="area_solicitada grids">
                <label for="area_solicitada">Área Solicitada:</label>
                <select name="area" id="area" required>
                    <option value="">Selecione a área da pista</option>
                    <option value="vda">Área de avaliação dinâmica(VDA)</option>
                    <option value="nvh">Pista de Pass by Noise(NVH)</option>
                    <option value="obstaculos">Pistas estruturais(obstáculos)</option>
                    <option value="rampa_12_20">Rampas 12% e 20%</option>
                    <option value="rampa_40">Rampa de 40%</option>
                    <option value="rampa_60">Rampa de 60%</option>
                    <option value="asfalto">Apenas asfalto</option>
                    <option value="exclusivo">Pista Completa</option>
                </select>
            </div>
            <div class="objtv_teste grids">
                <label for="objetivo">Objetivo do Teste:</label>
                <select name="objetivo" id="objetivo" required>
                    <option value="">Selecione o Objetivo</option>
                </select>
            </div>
            <div class="veic grids">
                <label for="veiculo">Veículo:</label>
                <select name="veic" id="veic" required>
                    <option value="">Selecione o Veículo</option>
                    <?php
                        include_once('../config/config.php');
                        $query_veics = "SELECT veic FROM veics";
                        $result_veics = mysqli_query($conexao, $query_veics);
                        while ($row_veic = mysqli_fetch_assoc($result_veics)) {
                            echo '<option value="' . $row_veic['veic'] . '">' . $row_veic['veic'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="resp_veic grids">
                <label for="responsavel_veiculo">Resp. pelo Veículo:</label>
                <input type="text" name="resp_veic" id="resp_veic" placeholder="Indique o Responsável">
            </div>
            <div class="exclsv grids">
                <label for="exclsv" style="margin-right: 50px;">Uso Exclusivo?</label>
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
                <textarea name="obs" id="obs" cols="48" rows="5" style="resize: none; margin-top: 5px;"></textarea>
            </div>
            <div class="enviar">
                <input type="submit" value="Agendar">
            </div>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            if (empty($_POST['solicitante']) || empty($_POST['dia']) || empty($_POST['hora_inicio']) || empty($_POST['hora_fim']) || empty($_POST['area_solicitante']) || empty($_POST['area']) || empty($_POST['objetivo']) || empty($_POST['veic']) || empty($_POST['resp_veic']) || empty($_POST['resposta']) || empty($_POST['obs'])) {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'ATENÇÃO!',
                        html: 'Algum dos campos está vazio! Por favor, preencha todos os campos atentamente.',
                    });
                </script>";
            } else {
                $solicitante = $_POST['solicitante'];
                $area_solicitante = $_POST['area_solicitante']; // Corrigido o índice
                $data = $_POST['dia'];
                $hora_inicio = $_POST['hora_inicio'];
                $hora_fim = $_POST['hora_fim'];
                $area = $_POST['area'];
                $objetivo = $_POST['objetivo'];
                $veiculo = $_POST['veic'];
                $resp_veic = $_POST['resp_veic'];
                $exclsv = $_POST['resposta']; // Corrigido o índice
                $obs = $_POST['obs'];

                $query = "SELECT * FROM $area WHERE dia = '$data' 
                    AND ((hora_inicio <= '$hora_inicio' AND hora_fim >= '$hora_inicio') 
                    OR (hora_inicio <= '$hora_fim' AND hora_fim >= '$hora_fim'))
                    UNION
                    SELECT * FROM exclusivo WHERE dia = '$data' 
                    AND ((hora_inicio <= '$hora_inicio' AND hora_fim >= '$hora_inicio') 
                    OR (hora_inicio <= '$hora_fim' AND hora_fim >= '$hora_fim'))";

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
                    $query = "INSERT INTO $area_solicitante (dia, hora_inicio, hora_fim, objtv, solicitante, area_solicitante, veic, resp_veic, exclsv, obs, status) VALUES ('$data', '$hora_inicio', '$hora_fim', '$objetivo', '$solicitante', '$area_solicitante', '$veiculo', '$resp_veic', '$exclsv', '$obs', '$status')";
                    $result = mysqli_query($conexao, $query);

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
                                        // Redireciona o usuário para a página desejada
                                        window.location.href = "tabela-agendamentos.php";
                                    }
                                });
                            </script>';
                        } else {
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
</body>
</html>