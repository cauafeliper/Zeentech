<?php

use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

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
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    
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

function PorcentagemMinuto($minuto) { // retorna a porcentagem do minuto em relação a 60 minutos
    $minuto = intval($minuto);
    $porcentagem = ($minuto / 60) * 100;
    return $porcentagem;
}

function CriarCSS($conexao, $dia, $area_pista, $letra, $pistaClasse, $y){ // cria as classes com os horários agendados
    $sql = "SELECT hora_inicio, hora_fim, exclsv FROM agendamentos WHERE area_pista = '$area_pista' AND dia='$dia' AND status='Aprovado'";
    $result = $conexao->query($sql);
    $horariosMarcados = array();
    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $exclsv = $row["exclsv"];
            $horarioInicio = $row["hora_inicio"];
            $horarioFim = $row["hora_fim"];
            
            $horaInicio = $horarioInicio[0] . $horarioInicio[1];
            $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

            $horaFim = $horarioFim[0] . $horarioFim[1];
            $minutoFim = $horarioFim[3] . $horarioFim[4];

            $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';

            $horariosMarcados[] = array('exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim));
        }
        $j = 2;
        foreach ($horariosMarcados as $tarefa) {
            $cor = $tarefa['cor'];
            $horaInicio = $tarefa['horaInicio'];
            $horaFim = $tarefa['horaFim'];
            $minutoInicio = $tarefa['minutoInicio'];
            $minutoFim = $tarefa['minutoFim'];
            
            if ($horaInicio != $horaFim){
                if($minutoInicio != '00'){
                    $porcentagemInicioMargin = PorcentagemMinuto($minutoInicio);
                    $porcentagemInicio = 100 - $porcentagemInicioMargin;
                }
                else{
                    $porcentagemInicio = 100;
                    $porcentagemInicioMargin = 0;
                }
                if($minutoFim != '00'){
                    $porcentagemFim = PorcentagemMinuto($minutoFim);
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
                $porcentagemMinutos = PorcentagemMinuto($minutos);
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

            $classe = $letra.$j;
            $classeTip = 'tip_'.$classe;
            $horario = "$horaInicio - $horaFim";
            $leftTip = $leftTotal + ($tamanho/2) - 100;
            if ($leftTip < 0){
                $leftTip = 0;
            }
            if ($leftTip > 827){
                $leftTip = 827;
            }

            echo '<style>';
            echo '.' . $pistaClasse . '{position: relative;}';
            echo '.' . $classe . '{position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

            echo '.'.$classeTip. '{position: absolute; justify-content: center; '.$y.': 35px; width: 200px; height: fit-content; left: '.$leftTip.'px; border-top-left-radius: 15px; border-bottom-left-radius: 15px;  border-top-right-radius: 15px; border-bottom-right-radius: 15px; z-index: 3; display: none; text-align: start; padding: 5px;  flex-flow: column; background-color: #9cadddeb; font-size: 14px;}';
            echo ".$classe:hover + .$classeTip".'{display: flex;}';
            echo ".$classeTip:hover {display: flex;}";
            echo '</style>';

            $j++;
        }
    }
}

function CriarHTML($conexao, $dia, $area_pista, $letra){ // cria as divs com as classes para cada pista
    $sql = "SELECT hora_inicio, hora_fim, solicitante, area_solicitante, veic FROM agendamentos WHERE area_pista = '$area_pista' AND dia='$dia' AND status='Aprovado'";
    $result = $conexao->query($sql);
    $j = 2;
    while ($row = $result->fetch_assoc()) {
        $horarioInicio = $row["hora_inicio"];
        $horarioFim = $row["hora_fim"];
        $horario = "$horarioInicio - $horarioFim";
        $solicitante = $row["solicitante"];
        $areaSolicitante = $row["area_solicitante"];
        $veic = $row["veic"];
        $classe = "$letra".$j;
        echo '<div class="'.$classe.'"></div>';
        echo '<div class="tip_'.$classe.'" id="tip_'.$classe.'" style="color: #001e50;"><h3 style="display: flex; height: fit-content; justify-content: center;">'.$horario. '</h3>'.
            '<p><span style="color: #4C7397;">Solicitante: </span>'.$solicitante.'</p>'.
            '<p><span style="color: #4C7397;">Área Solicitante: </span>'."$areaSolicitante".'</p>'.
        '</div>';
        $j++;
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


$listaPistas = array('VDA', 'NVH', 'Obstáculos', 'Rampa 12% e 20%', 'Rampa 40%', 'Rampa 60%', 'Asfalto', 'Pista Completa');
$listaPistasClasse = array('vda', 'nvh', 'obs', 'r_12_20', 'r_40', 'r_60', 'asf', 'pista_comp');
$listaLetras = array('c', 'd', 'e', 'f', 'g', 'h', 'i', 'j');
$listaY = array('top', 'top', 'top', 'top', 'bottom', 'bottom', 'bottom', 'bottom');

// preenche as classes com os horários agendados
for ($i = 0; $i < 8; $i++){
    CriarCSS($conexao, $dia, $listaPistas[$i], $listaLetras[$i], $listaPistasClasse[$i], $listaY[$i]);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
                <div class="tit">
                    <div class="all_tit"><h2 style="color: white;">Agendamentos por Dia</h2></div>
                </div>
                <div class="out_grafico">
                    <div class="grafico" style="position: relative">
                        <hr style="width: 1px; position: absolute;left: 170px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 248px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 326px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 404px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 482px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 560px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 638px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 716px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 794px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 872px;height: 374px;z-index: 1;top: 10%;">
                        <hr style="width: 1px; position: absolute;left: 950px;height: 374px;z-index: 1;top: 10%;">
                        <div class="scl">
                            <div class="quad_graf"></div>
                            <div class="quad_graf" style="border: none"><div class="b2" style="z-index: 2;">07:00</div></div>                            
                            <div class="quad_graf" style="border: none"><div class="b3" style="z-index: 2;">08:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b4" style="z-index: 2;">09:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b5" style="z-index: 2;">10:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b6" style="z-index: 2;">11:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b7" style="z-index: 2;">12:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b8" style="z-index: 2;">13:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b9" style="z-index: 2;">14:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b10" style="z-index: 2;">15:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b11" style="z-index: 2;">16:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b12" style="z-index: 2;">17:00</div></div>
                            <div class="quad_graf"><div class="b13" style="z-index: 2;">18:00</div></div>
                            <div class="quad_graf" style="border: none"><div class="b14" style="z-index: 2;">19:00</div></div>
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
                            <?php
                            // cria as divs com as classes para cada pista
                                for ($i = 0; $i < 8; $i++){
                                    echo '<div class="'.$listaPistasClasse[$i].'">';
                                    CriarHTML($conexao, $dia, $listaPistas[$i], $listaLetras[$i]);
                                    echo '</div>';
                                }
                            ?>
                            </div>
                            <div class="espaco"></div>
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