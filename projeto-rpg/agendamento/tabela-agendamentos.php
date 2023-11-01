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
                    <?php 
                        include_once('../config/config.php');

                        $query_area = "SELECT area FROM area_pista";
                        $result_area = mysqli_query($conexao, $query_area);

                        while ($row = mysqli_fetch_assoc($result_area)) {
                            echo '<option value="' . $row['area'] . '">' . $row['area'] . '</option>';
                        }
                    ?>
                </select>
                <input type="submit" value="Filtrar" class="submit">
            </form>
            <table>
            <?php 
                if($_SERVER["REQUEST_METHOD"] === "POST"){

                    if(isset($_POST['data']) & isset($_POST['area'])){
                        $data = $_POST['data'];
                        $area = $_POST['area'];

                        if($area === 'todas_areas') {
                            $query = "SELECT * FROM agendamentos WHERE dia = '$data' AND status = 'Aprovado'";

                            $result = mysqli_query($conexao, $query);

                            echo '<caption>Todas Áreas</caption>';

                            echo '<tr>';
                            echo '<th>Início</th><th>Fim</th><th>Veículo</th><th>Objetivo</th><th>Uso exclusivo?</th><th>Área</th>';
                            echo '</tr>';
                            echo '<tr>';
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo "<td>" . $row["hora_inicio"] . "</td><td>" . $row["hora_fim"] . "</td><td>" . $row["veic"] . "</td><td>" . $row["objtv"] . "</td><td>" . $row["exclsv"] . "</td><td>" . $row["area_pista"] . "</td>";
                            }
                            else {
                                echo "<td>Ainda</td><td>não</td><td>existem</td><td>agendamentos</td><td>nesse</td><td>dia.</td>";
                            }
                            echo '</tr>';
                        }
                        else {
                               $query = "SELECT * FROM agendamentos WHERE dia = '$data' AND status = 'Aprovado' AND area_pista = '$area'
                               UNION
                               SELECT * FROM agendamentos WHERE dia = '$data' AND status = 'Aprovado' AND area_pista = 'Pista Completa'";
                                $result = mysqli_query($conexao, $query);

                                echo '<caption>' . "$area" . '</caption>';
    
                                echo '<tr>';
                                echo '<th>Início</th><th>Fim</th><th>Veículo</th><th>Objetivo</th><th>Uso exclusivo?</th><th>Área</th>';
                                echo '</tr>';
                                echo '<tr>';
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo "<td>" . $row["hora_inicio"] . "</td><td>" . $row["hora_fim"] . "</td><td>" . $row["veic"] . "</td><td>" . $row["objtv"] . "</td><td>" . $row["exclsv"] . "</td><td>" . $row["area_pista"] . "</td>";
                                }
                                else {
                                    echo "<td>Ainda</td><td>não</td><td>existem</td><td>agendamentos</td><td>nesse</td><td>dia.</td>";
                                }
                                echo '</tr>';
                            }
                           
                            }
                            else {
                                echo "<td>Ainda</td><td>não</td><td>existem</td><td>agendamentos</td><td>nesse dia.</td>";
                            }
                            echo '</tr>';
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
            <div class="veic grids">
                <label for="veiculo">Veículo:</label>
                <select name="veic" id="veic" required>
                    <option value="">Selecione o Veículo</option>
                    <?php
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
                <input type="submit" name="submit" value="Agendar">
            </div>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            if (empty($_POST['solicitante']) OR empty($_POST['dia']) OR empty($_POST['hora_inicio']) OR empty($_POST['hora_fim']) OR empty($_POST['area_solicitante']) OR empty($_POST['area']) OR empty($_POST['objetivo']) OR empty($_POST['veic']) OR empty($_POST['resp_veic']) OR empty($_POST['resposta']) OR empty($_POST['obs'])) {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'ATENÇÃO!',
                        html: 'Algum dos campos está vazio! Por favor, preencha todos os campos atentamente.',
                    });
                </script>";
            } else {
                $solicitante = $_POST['solicitante'];
                $area_solicitante = $_POST['area_solicitante'];
                $data = $_POST['dia'];
                $hora_inicio = $_POST['hora_inicio'];
                $hora_fim = $_POST['hora_fim'];
                $area = $_POST['area'];
                $objetivo = $_POST['objetivo'];
                $veiculo = $_POST['veic'];
                $resp_veic = $_POST['resp_veic'];
                $exclsv = $_POST['resposta'];
                $status = $_POST['status'];
                $obs = $_POST['obs'];

                $query = "SELECT * FROM agendamentos WHERE (area_pista = '$area' OR area_pista = 'Pista Completa')
                    AND dia = '$data' 
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
                    $query = "INSERT INTO agendamentos (area_pista, dia, hora_inicio, hora_fim, objtv, solicitante, area_solicitante, veic, resp_veic, exclsv, obs, status) VALUES ('$area', '$data', '$hora_inicio', '$hora_fim', '$objetivo', '$solicitante', '$area_solicitante', '$veiculo', '$resp_veic', '$exclsv', '$obs', '$status')";
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