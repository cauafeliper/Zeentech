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

class Coordenada {
    public string $Id;
    public $HasItem = false;
    public string $hora;

    public function __construct(string $id) {
        $this->Id = $id;
        switch ($id[]){}
        $this->horario = ;
    }
}

function porcentagemMinuto($minuto) {
    $minuto = intval($minuto);
    $porcentagem = intval(($minuto / 60) * 100);
    return $porcentagem;
}

function identificarLinha($pista) {
    $linha = '';
    switch ($pista) {
        case 'VDA':
            $linha = 'c';
            break;
        case 'NVH':
            $linha = 'd';
            break;
        case 'Obstáculos':
            $linha = 'e';
            break;
        case 'Rampa 12% e 20%':
            $linha = 'f';
            break;
        case 'Rampa 40%':
            $linha = 'g';
            break;
        case 'Rampa 60%':
            $linha = 'h';
            break;
        case 'Asfalto':
            $linha = 'i';
            break;
        case 'Pista Completa':
            $linha = 'j';
            break;
    }
    return $linha;
}

function mostrarAgendamentos($conexao, $dia){
    $listaPista = array('VDA', 'NVH', 'Obstáculos', 'Rampa 12% e 20%', 'Rampa 40%', 'Rampa 60%', 'Asfalto', 'Pista Completa');
    

    foreach($listaPista as $pista){
        $l = identificarLinha($pista);
        $mapeamentoHorariosVDA = array(
        '07' => $l.'2', '08' => $l.'3', '09' => $l.'4', '10' => $l.'5',
        '11' =>  $l.'6', '12' =>  $l.'7', '13' =>  $l.'8', '14' =>  $l.'9',
        '15' =>  $l.'10', '16' =>  $l.'11', '17' =>  $l.'12', '18' =>  $l.'13',
        '19' =>  $l.'14'
        );
        $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista= '$pista' AND dia='$dia' AND status='Aprovado'";
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

                $colInicio = $mapeamentoHorariosVDA[$horaInicio];
                $colFim = $mapeamentoHorariosVDA[$horaFim];

                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';

                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }

            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                
                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }                

                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }

            echo '</style>';
        }
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

$listaLetra = array('c', 'd', 'e', 'f', 'g', 'h', 'i', 'j');
$coordenadas = array();
for ($i = 2; $i <= 14; $i++) {
    foreach ($listaLetra as $letra) {
        $coord = new Coordenada($letra . $i);
        array_push($coordenadas, $coordenada);
    }
}

foreach($coordenadas as $coordenada) {
    if (strlen($coordenada->horario) == 1){
        $coordenada->horario = '0' . $coordenada->horario;
    }
}



mostrarAgendamentos($conexao, $dia);

//////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
 /* $mapeamentoHorariosVDA = array(
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
       
                $colInicio = $mapeamentoHorariosVDA[$horaInicio];
                $colFim = $mapeamentoHorariosVDA[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
                
                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }                
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'c' . $i;
                    
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
       
            echo '</style>';
        }  *//*
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
       
                $colInicio = $mapeamentoHorariosNVH[$horaInicio];
                $colFim = $mapeamentoHorariosNVH[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }      
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'd' . $i;
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
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
       
                $colInicio = $mapeamentoHorariosOBS[$horaInicio];
                $colFim = $mapeamentoHorariosOBS[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }      
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'e' . $i;
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
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
       
                $colInicio = $mapeamentoHorariosR12_20[$horaInicio];
                $colFim = $mapeamentoHorariosR12_20[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }      
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'f' . $i;
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
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
       
                $colInicio = $mapeamentoHorariosR40[$horaInicio];
                $colFim = $mapeamentoHorariosR40[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }      
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'g' . $i;
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
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
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];
       
                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }      
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'h' . $i;
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
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
       
            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];
       
                $colInicio = $mapeamentoHorariosASF[$horaInicio];
                $colFim = $mapeamentoHorariosASF[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];

                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }      
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'i' . $i;
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
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
       
                $colInicio = $mapeamentoHorariosPC[$horaInicio];
                $colFim = $mapeamentoHorariosPC[$horaFim];
       
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
       
                $horariosMarcados[] = array('inicio' => $colInicio, 'fim' => $colFim, 'exclsv' => $exclsv, 'cor' => $cor);
            }
       
            foreach ($horariosMarcados as $tarefa) {
                $inicio = $tarefa['inicio'];
                $fim = $tarefa['fim'];
                $cor = $tarefa['cor'];

                if($minutoInicio != '00'){
                    $porcentagemInicio = porcentagemMinuto($minutoInicio);
                    $porcentagemInicioMargin = 100 - $porcentagemInicio;
                    
                    echo '.' . "$inicio" . '{width: '.$porcentagemInicio.'%; margin: 0 0 0 '.$porcentagemInicioMargin.'%; justify-self: right; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$inicio" . '{background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                if($minutoFim != '00'){
                    $porcentagemFim = porcentagemMinuto($minutoFim);
                    $porcentagemFimMargin = 100 - $porcentagemFim;
                    
                    echo '.' . "$fim" . '{width: '.$porcentagemFim.'%; margin: 0 '.$porcentagemFimMargin.'% 0 0; justify-self: left; background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
                else{
                    echo '.' . "$fim" . '{background-color: '.$cor.'; border-top-right-radius: 15px; border-bottom-right-radius: 15px;  border-top: 10px solid white; border-bottom: 10px solid white;}';
                }      
       
                for ($i = intval(substr($inicio, 1)) + 1; $i < intval(substr($fim, 1)); $i++) {
                    $col = 'j' . $i;
                    echo '.' . "$col" . '{background-color: '.$cor.'; border-top: 10px solid white; border-bottom: 10px solid white;}';
                }
            }
       
            echo '</style>';
        }*/
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
                    <div class="vda">
                        <div class="c1 quad_graf">VDA</div>
                        <div class="quad_graf">
                            <div class="c2 preenchimento"></div>
                        </div>
                        <div class="quad_graf"><div class="c3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="c14 preenchimento"></div></div>
                    </div>
                    <div class="nvh">
                        <div class="d1 quad_graf">NVH</div>
                        <div class="quad_graf"><div class="d2 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="d14 preenchimento"></div></div>
                    </div>
                    <div class="obs">
                        <div class="e1 quad_graf">Obsáculos</div>
                        <div class="quad_graf"><div class="e2 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="e14 preenchimento"></div></div>
                    </div>
                    <div class="r_12_20">
                        <div class="f1 quad_graf">Rampa 12% e 20%</div>
                        <div class="quad_graf"><div class="f2 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="f14 preenchimento"></div></div>
                    </div>
                    <div class="r_40">
                        <div class="g1 quad_graf">Rampa 40%</div>
                        <div class="quad_graf"><div class="g2 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="g14 preenchimento"></div></div>
                    </div>
                    <div class="r_60">
                        <div class="h1 quad_graf">Rampa 60%</div>
                        <div class="quad_graf"><div class="h2 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="h14 preenchimento"></div></div>
                    </div>
                    <div class="asf">
                        <div class="i1 quad_graf">Asfalto</div>
                        <div class="quad_graf"><div class="i2 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="i14 preenchimento"></div></div>
                    </div>
                    <div class="pc">
                        <div class="j1 quad_graf">Pista Completa</div>
                        <div class="quad_graf"><div class="j2 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j3 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j4 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j5 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j6 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j7 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j8 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j9 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j10 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j11 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j12 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j13 preenchimento"></div></div>
                        <div class="quad_graf"><div class="j14 preenchimento"></div></div>
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