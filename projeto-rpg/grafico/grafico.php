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

function porcentagemMinuto($minuto) {
    $minuto = intval($minuto);
    $porcentagem = ($minuto / 60) * 100;
    return $porcentagem;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
 $mapeamentoHorariosVDA = array(
            '07' => 'c2', '08' => 'c3', '09' => 'c4', '10' => 'c5',
            '11' => 'c6', '12' => 'c7', '13' => 'c8', '14' => 'c9',
            '15' => 'c10', '16' => 'c11', '17' => 'c12', '18' => 'c13',
            '19' => 'c14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'VDA' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                }
                else{
                    $porcentagemFim = 0;
                }      
                
                $tamanho = 0;
                for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                    $tamanho += 100;
                }
                $tamanho += $porcentagemInicio + $porcentagemFim;
                $tamanho = ($tamanho / 100) * 78;
                $leftTotal = 0;
                $indexInicio = ($horaInicio - 7) * 78;
                $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                $leftTotal += $porcentagemInicioMargin + $indexInicio;

                echo '.' . 'vda' . '{position: relative;}';
                echo '.' . 'c'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
       
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosNVH = array(
                '07' => 'd2', '08' => 'd3', '09' => 'd4', '10' => 'd5',
                '11' => 'd6', '12' => 'd7', '13' => 'd8', '14' => 'd9',
                '15' => 'd10', '16' => 'd11', '17' => 'd12', '18' => 'd13',
                '19' => 'd14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'NVH' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                }
                else{
                    $porcentagemFim = 0;
                }      
                
                $tamanho = 0;
                for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                    $tamanho += 100;
                }
                $tamanho += $porcentagemInicio + $porcentagemFim;
                $tamanho = ($tamanho / 100) * 78;
                $leftTotal = 0;
                $indexInicio = ($horaInicio - 7) * 78;
                $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                $leftTotal += $porcentagemInicioMargin + $indexInicio;

                echo '.' . 'nvh' . '{position: relative;}';
                echo '.' . 'd'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
       
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosOBS = array(
            '07' => 'e2', '08' => 'e3', '09' => 'e4', '10' => 'e5',
            '11' => 'e6', '12' => 'e7', '13' => 'e8', '14' => 'e9',
            '15' => 'e10', '16' => 'e11', '17' => 'e12', '18' => 'e13',
            '19' => 'e14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Obstáculos' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
                
                if ($horaInicio != $horaFim){
                    if($minutoInicio != '00'){
                        $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                        $porcentagemInicio = 100 - $porcentagemInicioMargin;
                    }
                    else{
                        $porcentagemInicio = 100;
                        $porcentagemInicioMargin = 0;
                    }
                    if($minutoFim != '00'){
                        $porcentagemFim = porcentagemMinuto($minutoFim);
                    }
                    else{
                        $porcentagemFim = 0;
                    }      
                    
                    $tamanho = 0;
                    for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                        $tamanho += 100;
                    }
                    $tamanho += $porcentagemInicio + $porcentagemFim;
                    $tamanho = ($tamanho / 100) * 78;
                    $leftTotal = 0;
                    $indexInicio = ($horaInicio - 7) * 78;
                    $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                    $leftTotal += $porcentagemInicioMargin + $indexInicio;
                }
                else{
                    $tamanho = 0;
                    $leftTotal = 0;
                    $minutos = $minutoFim - $minutoInicio;
                    $porcentagemMinutos = porcentagemMinuto($minutos);
                    $tamanho = ($porcentagemMinutos / 100) * 78;
                    if ($minutoInicio != '00'){
                        $porcentagemInicioMargin = 100 - $porcentagemMinutos;
                        $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                    }
                    else{
                        $porcentagemInicioMargin = 0;
                    }
                    $indexInicio = ($horaInicio - 7) * 78;
                    $leftTotal = $porcentagemInicioMargin + $indexInicio;
                }

                echo '.' . 'obs' . '{position: relative;}';
                echo '.' . 'e'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
                   
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosR12_20 = array(
            '07' => 'f2', '08' => 'f3', '09' => 'f4', '10' => 'f5',
            '11' => 'f6', '12' => 'f7', '13' => 'f8', '14' => 'f9',
            '15' => 'f10', '16' => 'f11', '17' => 'f12', '18' => 'f13',
            '19' => 'f14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Rampa 12% e 20%' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                }
                else{
                    $porcentagemFim = 0;
                }      
                
                $tamanho = 0;
                for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                    $tamanho += 100;
                }
                $tamanho += $porcentagemInicio + $porcentagemFim;
                $tamanho = ($tamanho / 100) * 78;
                $leftTotal = 0;
                $indexInicio = ($horaInicio - 7) * 78;
                $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                $leftTotal += $porcentagemInicioMargin + $indexInicio;

                echo '.' . 'r_12_20' . '{position: relative;}';
                echo '.' . 'f'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
       
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosR40 = array(
            '07' => 'g2', '08' => 'g3', '09' => 'g4', '10' => 'g5',
            '11' => 'g6', '12' => 'g7', '13' => 'g8', '14' => 'g9',
            '15' => 'g10', '16' => 'g11', '17' => 'g12', '18' => 'g13',
            '19' => 'g14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Rampa 40%' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                }
                else{
                    $porcentagemFim = 0;
                }      
                
                $tamanho = 0;
                for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                    $tamanho += 100;
                }
                $tamanho += $porcentagemInicio + $porcentagemFim;
                $tamanho = ($tamanho / 100) * 78;
                $leftTotal = 0;
                $indexInicio = ($horaInicio - 7) * 78;
                $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                $leftTotal += $porcentagemInicioMargin + $indexInicio;

                echo '.' . 'r_40' . '{position: relative;}';
                echo '.' . 'g'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
       
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosR60 = array(
            '07' => 'h2', '08' => 'h3', '09' => 'h4', '10' => 'h5',
            '11' => 'h6', '12' => 'h7', '13' => 'h8', '14' => 'h9',
            '15' => 'h10', '16' => 'h11', '17' => 'h12', '18' => 'h13',
            '19' => 'h14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Rampa 60%' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                }
                else{
                    $porcentagemFim = 0;
                }      
                
                $tamanho = 0;
                for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                    $tamanho += 100;
                }
                $tamanho += $porcentagemInicio + $porcentagemFim;
                $tamanho = ($tamanho / 100) * 78;
                $leftTotal = 0;
                $indexInicio = ($horaInicio - 7) * 78;
                $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                $leftTotal += $porcentagemInicioMargin + $indexInicio;

                echo '.' . 'r_60' . '{position: relative;}';
                echo '.' . 'h'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
       
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosASF = array(
            '07' => 'i2', '08' => 'i3', '09' => 'i4', '10' => 'i5',
            '11' => 'i6', '12' => 'i7', '13' => 'i8', '14' => 'i9',
            '15' => 'i10', '16' => 'i11', '17' => 'i12', '18' => 'i13',
            '19' => 'i14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Asfalto' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
            
            $j = 2;
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                }
                else{
                    $porcentagemFim = 0;
                }      
                
                $tamanho = 0;
                for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                    $tamanho += 100;
                }
                $tamanho += $porcentagemInicio + $porcentagemFim;
                $tamanho = ($tamanho / 100) * 78;
                $leftTotal = 0;
                $indexInicio = ($horaInicio - 7) * 78;
                $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                $leftTotal += $porcentagemInicioMargin + $indexInicio;

                echo '.' . 'asf' . '{position: relative;}';
                echo '.' . 'i'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
       
            echo '</style>';
        }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $mapeamentoHorariosPC = array(
            '07' => 'j2', '08' => 'j3', '09' => 'j4', '10' => 'j5',
            '11' => 'j6', '12' => 'j7', '13' => 'j8', '14' => 'j9',
            '15' => 'j10', '16' => 'j11', '17' => 'j12', '18' => 'j13',
            '19' => 'j14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Pista Completa' AND dia='$dia' AND status='Aprovado'";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {
            echo '<style>';
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosR60[$horaInicio];
                $colFim = $mapeamentoHorariosR60[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = porcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                }
                else{
                    $porcentagemFim = 0;
                }      
                
                $tamanho = 0;
                for ($i = $horaInicio + 1; $i < $horaFim; $i++) {
                    $tamanho += 100;
                }
                $tamanho += $porcentagemInicio + $porcentagemFim;
                $tamanho = ($tamanho / 100) * 78;
                $leftTotal = 0;
                $indexInicio = ($horaInicio - 7) * 78;
                $porcentagemInicioMargin = ($porcentagemInicioMargin / 100) * 78;
                $leftTotal += $porcentagemInicioMargin + $indexInicio;

                echo '.' . 'pc' . '{position: relative;}';
                echo '.' . 'j'. $j . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

                $j++;
            }
       
            echo '</style>';
        }
    ?> 
</head>
<body>
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
                    <div class="grafico_preenchimentos">
                        <div class = nome_pistas>
                            <div class="c1 quad_graf">VDA</div>
                            <div class="d1 quad_graf">NVH</div>
                            <div class="e1 quad_graf">Obstáculos</div>
                            <div class="f1 quad_graf">Rampa 12% e 20%</div>
                            <div class="g1 quad_graf">Rampa 40%</div>
                            <div class="h1 quad_graf">Rampa 60%</div>
                            <div class="i1 quad_graf">Asfalto</div>
                            <div class="j1 quad_graf">Pista Completa</div>
                        </div>
                        <div class="grafico_linhas">
                            <div class="vda">               
                                <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'VDA' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $j = 2;
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="c'.$j.'"></div>';
                                        $j++;
                                    }                                        
                                ?>
                            </div>
                            <div class="nvh">
                                <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'NVH' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $j = 2;
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="d'.$j.'"></div>';
                                        $j++;
                                    }                                        
                                ?>
                            </div>
                            <div class="obs">
                                <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Obstáculos' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $j = 2;
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="e'.$j.'"></div>';
                                        $j++;
                                    }                                        
                                ?>
                            </div>
                            <div class="r_12_20">
                                <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Rampa 12% e 20%' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $j = 2;
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="f'.$j.'"></div>';
                                        $j++;
                                    }                                        
                                ?>
                            </div>
                            <div class="r_40">
                                <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Rampa 40%' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $j = 2;
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="g'.$j.'"></div>';
                                        $j++;
                                    }                                        
                                ?>
                            </div>
                            <div class="r_60">
                                <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Rampa 60%' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $j = 2;
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="h'.$j.'"></div>';
                                        $j++;
                                    }                                        
                                ?>
                            </div>
                            <div class="asf">
                                <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Asfalto' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $j = 2;
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div class="i'.$j.'"></div>';
                                        $j++;
                                    }                                        
                                ?>
                            </div>
                            <div class="pc">
                            <?php 
                                    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = 'Asfalto' AND dia='$dia' AND status='Aprovado'";
                                    $result = $conexao->query($sql);
                                    $numLinha = $result->num_rows;
                                    if ($numLinha > 0) {
                                        for ($i = 2; $i < $numLinha+1; $i++) {
                                            echo '<div class="j'.$i.'"></div>';
                                        }                           
                                    }             
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="leg">
                        <div class="k1 quad_graf"></div>
                        <div class="k2 quad_graf"></div>
                        <div class="k3 quad_graf"></div>
                        <div class="k4 quad_graf"></div>
                        <div class="k5 quad_graf"></div>
                        <div class="k6 quad_graf"><p>Exclusivo</p></div>
                        <div class="k7 quad_graf"></div>
                        <div class="k8 quad_graf"><p>Não<br>Exlusivo</p></div>
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