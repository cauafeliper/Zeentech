<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico | Agendamentos</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/grafico.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-bundle.min.js"></script>
    <?php
        if (isset($_POST['dia'])) {
            $dia = $_POST['dia'];
        } else {
            $dia = date('Y-m-d');
        }
        include_once('../config/config.php');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosVDA = array(
            '07:00' => 'c2', '08:00' => 'c3', '09:00' => 'c4', '10:00' => 'c5', 
            '11:00' => 'c6', '12:00' => 'c7', '13:00' => 'c8', '14:00' => 'c9',
            '15:00' => 'c10', '16:00' => 'c11', '17:00' => 'c12', '18:00' => 'c13',
            '19:00' => 'c14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'VDA' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosVDA[$horaInicio];
                $colFim = $mapeamentoHorariosVDA[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #4C7397; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #4C7397; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #4C7397; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosNVH = array(
            '07:00' => 'd2', '08:00' => 'd3', '09:00' => 'd4', '10:00' => 'd5', 
            '11:00' => 'd6', '12:00' => 'd7', '13:00' => 'd8', '14:00' => 'd9',
            '15:00' => 'd10', '16:00' => 'd11', '17:00' => 'd12', '18:00' => 'd13',
            '19:00' => 'd14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'NVH' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosNVH[$horaInicio];
                $colFim = $mapeamentoHorariosNVH[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #001e50; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #001e50; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #001e50; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosOBS = array(
            '07:00' => 'e2', '08:00' => 'e3', '09:00' => 'e4', '10:00' => 'e5', 
            '11:00' => 'e6', '12:00' => 'e7', '13:00' => 'e8', '14:00' => 'e9',
            '15:00' => 'e10', '16:00' => 'e11', '17:00' => 'e12', '18:00' => 'e13',
            '19:00' => 'e14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'Obstáculos' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosOBS[$horaInicio];
                $colFim = $mapeamentoHorariosOBS[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #4C7397; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #4C7397; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #4C7397; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosR12_20 = array(
            '07:00' => 'f2', '08:00' => 'f3', '09:00' => 'f4', '10:00' => 'f5', 
            '11:00' => 'f6', '12:00' => 'f7', '13:00' => 'f8', '14:00' => 'f9',
            '15:00' => 'f10', '16:00' => 'f11', '17:00' => 'f12', '18:00' => 'f13',
            '19:00' => 'f14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'NVH' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosR12_20[$horaInicio];
                $colFim = $mapeamentoHorariosR12_20[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #001e50; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #001e50; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #001e50; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosR40 = array(
            '07:00' => 'g2', '08:00' => 'g3', '09:00' => 'g4', '10:00' => 'g5', 
            '11:00' => 'g6', '12:00' => 'g7', '13:00' => 'g8', '14:00' => 'g9',
            '15:00' => 'g10', '16:00' => 'g11', '17:00' => 'g12', '18:00' => 'g13',
            '19:00' => 'g14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'Obstáculos' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosR40[$horaInicio];
                $colFim = $mapeamentoHorariosR40[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #4C7397; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #4C7397; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #4C7397; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosR60 = array(
            '07:00' => 'h2', '08:00' => 'h3', '09:00' => 'h4', '10:00' => 'h5', 
            '11:00' => 'h6', '12:00' => 'h7', '13:00' => 'h8', '14:00' => 'h9',
            '15:00' => 'h10', '16:00' => 'h11', '17:00' => 'h12', '18:00' => 'h13',
            '19:00' => 'h14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'NVH' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #001e50; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #001e50; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #001e50; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosASF = array(
            '07:00' => 'i2', '08:00' => 'i3', '09:00' => 'i4', '10:00' => 'i5', 
            '11:00' => 'i6', '12:00' => 'i7', '13:00' => 'i8', '14:00' => 'i9',
            '15:00' => 'i10', '16:00' => 'i11', '17:00' => 'i12', '18:00' => 'i13',
            '19:00' => 'i14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'Obstáculos' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosASF[$horaInicio];
                $colFim = $mapeamentoHorariosASF[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #4C7397; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #4C7397; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #4C7397; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosPC = array(
            '07:00' => 'j2', '08:00' => 'j3', '09:00' => 'j4', '10:00' => 'j5', 
            '11:00' => 'j6', '12:00' => 'j7', '13:00' => 'j8', '14:00' => 'j9',
            '15:00' => 'j10', '16:00' => 'j11', '17:00' => 'j12', '18:00' => 'j13',
            '19:00' => 'j14'
        );
        $sql = "SELECT hora_inicio, hora_fim FROM agendamentos WHERE area_pista = 'NVH' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            while ($row = $result->fetch_assoc()) {
                $horaInicio = $row["hora_inicio"];
                $horaFim = $row["hora_fim"];
                $colInicio = $mapeamentoHorariosPC[$horaInicio];
                $colFim = $mapeamentoHorariosPC[$horaFim];
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim);
            }
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];

                echo '.' . "$inicio" . '{background-color: #001e50; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                echo '.' . "$fim" . '{background-color: #001e50; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    echo '.' . "$col" . '{background-color: #001e50; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
            echo '</style>';
        }
    ?>
</head>
<body>
    <?php 
        include_once('../config/config.php');
        session_start();
        if (!isset($_SESSION['chapa']) || empty($_SESSION['chapa'])) {
            session_unset();
            header('Location: ../index.php');
        }
        
        $chapa = $_SESSION['chapa'];
        $query = "SELECT * FROM chapa_adm WHERE chapa = '$chapa'";
        $result = mysqli_query($conexao, $query);
        
        if (!$result || mysqli_num_rows($result) === 0) {
            session_unset();
            header('Location: ../index.php');
        }
    ?>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <ul>
            <li><a href="../agendamento/gestor.php">Gestão</a></li>

            <li><a href="../agendamento/tabela-agendamentos.php">Início</a></li>

            <li><a href="../agendamento/sair.php">Sair</a></li>
        </ul>
    </header>
        <main>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="container filtro__dia">
                <div class="titulo"><h3>Selecione o Dia Desejado</h3></div>
                <div class="label"><label for="dia">Dia:</label></div>
                <div class="input"><input type="date" name="dia" id="dia" required></div>
                <div class="submit"><input type="submit" value="Filtrar"></div>
            </form>
            <div class="div__grafico">
                <div class="grafico">
                    <div class="tit">
                        <div class="all_tit"><h2>Agendamentos por Dia</h2></div>
                    </div>
                    <div class="scl">
                        <div class="b1 quad_graf"></div>
                        <div class="b2 quad_graf">07:00</div>
                        <div class="b3 quad_graf">08:00</div>
                        <div class="b4 quad_graf">09:00</div>
                        <div class="b5 quad_graf">10:00</div>
                        <div class="b6 quad_graf">11:00</div>
                        <div class="b7 quad_graf">12:00</div>
                        <div class="b8 quad_graf">13:00</div>
                        <div class="b9 quad_graf">14:00</div>
                        <div class="b10 quad_graf">15:00</div>
                        <div class="b11 quad_graf">16:00</div>
                        <div class="b12 quad_graf">17:00</div>
                        <div class="b13 quad_graf">18:00</div>
                        <div class="b14 quad_graf">19:00</div>
                    </div>
                    <div class="vda">
                        <div class="c1 quad_graf">VDA</div>
                        <div class="c2 quad_graf"></div>
                        <div class="c3 quad_graf"></div>
                        <div class="c4 quad_graf"></div>
                        <div class="c5 quad_graf"></div>
                        <div class="c6 quad_graf"></div>
                        <div class="c7 quad_graf"></div>
                        <div class="c8 quad_graf"></div>
                        <div class="c9 quad_graf"></div>
                        <div class="c10 quad_graf"></div>
                        <div class="c11 quad_graf"></div>
                        <div class="c12 quad_graf"></div>
                        <div class="c13 quad_graf"></div>
                        <div class="c14 quad_graf"></div>
                    </div>
                    <div class="nvh">
                        <div class="d1 quad_graf">NVH</div>
                        <div class="d2 quad_graf"></div>
                        <div class="d3 quad_graf"></div>
                        <div class="d4 quad_graf"></div>
                        <div class="d5 quad_graf"></div>
                        <div class="d6 quad_graf"></div>
                        <div class="d7 quad_graf"></div>
                        <div class="d8 quad_graf"></div>
                        <div class="d9 quad_graf"></div>
                        <div class="d10 quad_graf"></div>
                        <div class="d11 quad_graf"></div>
                        <div class="d12 quad_graf"></div>
                        <div class="d13 quad_graf"></div>
                        <div class="d14 quad_graf"></div>
                    </div>
                    <div class="obs">
                        <div class="e1 quad_graf">Obsáculos</div>
                        <div class="e2 quad_graf"></div>
                        <div class="e3 quad_graf"></div>
                        <div class="e4 quad_graf"></div>
                        <div class="e5 quad_graf"></div>
                        <div class="e6 quad_graf"></div>
                        <div class="e7 quad_graf"></div>
                        <div class="e8 quad_graf"></div>
                        <div class="e9 quad_graf"></div>
                        <div class="e10 quad_graf"></div>
                        <div class="e11 quad_graf"></div>
                        <div class="e12 quad_graf"></div>
                        <div class="e13 quad_graf"></div>
                        <div class="e14 quad_graf"></div>
                    </div>
                    <div class="r_12_20">
                        <div class="f1 quad_graf">Rampa 12% e 20%</div>
                        <div class="f2 quad_graf"></div>
                        <div class="f3 quad_graf"></div>
                        <div class="f4 quad_graf"></div>
                        <div class="f5 quad_graf"></div>
                        <div class="f6 quad_graf"></div>
                        <div class="f7 quad_graf"></div>
                        <div class="f8 quad_graf"></div>
                        <div class="f9 quad_graf"></div>
                        <div class="f10 quad_graf"></div>
                        <div class="f11 quad_graf"></div>
                        <div class="f12 quad_graf"></div>
                        <div class="f13 quad_graf"></div>
                        <div class="f14 quad_graf"></div>
                    </div>
                    <div class="r_40">
                        <div class="g1 quad_graf">Rampa 40%</div>
                        <div class="g2 quad_graf"></div>
                        <div class="g3 quad_graf"></div>
                        <div class="g4 quad_graf"></div>
                        <div class="g5 quad_graf"></div>
                        <div class="g6 quad_graf"></div>
                        <div class="g7 quad_graf"></div>
                        <div class="g8 quad_graf"></div>
                        <div class="g9 quad_graf"></div>
                        <div class="g10 quad_graf"></div>
                        <div class="g11 quad_graf"></div>
                        <div class="g12 quad_graf"></div>
                        <div class="g13 quad_graf"></div>
                        <div class="g14 quad_graf"></div>
                    </div>
                    <div class="r_60">
                        <div class="h1 quad_graf">Rampa 60%</div>
                        <div class="h2 quad_graf"></div>
                        <div class="h3 quad_graf"></div>
                        <div class="h4 quad_graf"></div>
                        <div class="h5 quad_graf"></div>
                        <div class="h6 quad_graf"></div>
                        <div class="h7 quad_graf"></div>
                        <div class="h8 quad_graf"></div>
                        <div class="h9 quad_graf"></div>
                        <div class="h10 quad_graf"></div>
                        <div class="h11 quad_graf"></div>
                        <div class="h12 quad_graf"></div>
                        <div class="h13 quad_graf"></div>
                        <div class="h14 quad_graf"></div>
                    </div>
                    <div class="asf">
                        <div class="i1 quad_graf">Asfalto</div>
                        <div class="i2 quad_graf"></div>
                        <div class="i3 quad_graf"></div>
                        <div class="i4 quad_graf"></div>
                        <div class="i5 quad_graf"></div>
                        <div class="i6 quad_graf"></div>
                        <div class="i7 quad_graf"></div>
                        <div class="i8 quad_graf"></div>
                        <div class="i9 quad_graf"></div>
                        <div class="i10 quad_graf"></div>
                        <div class="i11 quad_graf"></div>
                        <div class="i12 quad_graf"></div>
                        <div class="i13 quad_graf"></div>
                        <div class="i14 quad_graf"></div>
                    </div>
                    <div class="pc">
                        <div class="j1 quad_graf">Pista Completa</div>
                        <div class="j2 quad_graf"></div>
                        <div class="j3 quad_graf"></div>
                        <div class="j4 quad_graf"></div>
                        <div class="j5 quad_graf"></div>
                        <div class="j6 quad_graf"></div>
                        <div class="j7 quad_graf"></div>
                        <div class="j8 quad_graf"></div>
                        <div class="j9 quad_graf"></div>
                        <div class="j10 quad_graf"></div>
                        <div class="j11 quad_graf"></div>
                        <div class="j12 quad_graf"></div>
                        <div class="j13 quad_graf"></div>
                        <div class="j14 quad_graf"></div>
                    </div>
                    <div class="leg">
                        <div class="k1 quad_graf"></div>
                        <div class="k2 quad_graf"></div>
                        <div class="k3 quad_graf"></div>
                        <div class="k4 quad_graf"></div>
                        <div class="k5 quad_graf"></div>
                        <div class="k6 quad_graf"></div>
                        <div class="k7 quad_graf"></div>
                        <div class="k8 quad_graf"><p>Agendamento</p></div>
                        <div class="k9 quad_graf"></div>
                        <div class="k10 quad_graf"></div>
                        <div class="k11 quad_graf"></div>
                        <div class="k12 quad_graf"></div>
                        <div class="k13 quad_graf"></div>
                    </div>
                </div>
            </div>
        </main>
    <footer>
        <div>
            <span>Desenvolvido por:<img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2023 de Zeentech os direitos reservados</span>
        </div>
    </footer>
</body>
</html>