<?php
    include_once('../config/config.php');
    session_start();
?>
<?php

include 'functions.php';

use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    
    <?php
        if (isset($_POST['dia'])) {
            $dia = $_POST['dia'];
        } else {
            $dia = date('Y-m-d');
        }
        if (isset($_POST['dia'])) {
            $dia = $_POST['dia'];
        } else {
            $dia = date('Y-m-d');
        }
        $data = strtotime($dia);
        $data = getdate($data);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

$listaPistas = array('VDA', 'NVH', 'Obstáculos', 'Rampa 12% e 20%', 'Rampa 40%', 'Rampa 60%', 'Asfalto', 'Pista Completa');
$listaPistasClasse = array('vda', 'nvh', 'obs', 'r_12_20', 'r_40', 'r_60', 'asf', 'pc');
$listaPistasAno = array('VDA', 'NVH', 'Obstáculos', 'Rampa 12% e 20%', 'Rampa 40%', 'Rampa 60%', 'Asfalto', 'Pista Completa', 'Total');
$listaPistasClasseAno = array('vda', 'nvh', 'obs', 'r_12_20', 'r_40', 'r_60', 'asf', 'pc', 'total');
$listaLetras = array('c', 'd', 'e', 'f', 'g', 'h', 'i', 'j');
$listaY = array('top', 'top', 'top', 'top', 'bottom', 'bottom', 'bottom', 'bottom');
$listaSemana = array('segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo');
$listaAno = array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembo', 'outubro', 'novembro', 'dezembro');
$semana = calcularDiasDaSemana($dia);
$mes = calcularDiasDoMes($dia);
$primeirosDiasDosMeses = obterPrimeirosDiasDosMesesDoAno($dia);
$listaAreasSolicitantes = criarListaAreas($conexao);
$listaAreasPista = array('VDA', 'NVH', 'Obstáculos', 'Rampa 12% e 20%', 'Rampa 40%', 'Rampa 60%', 'Asfalto', 'Pista Completa');

$ano = date('Y', strtotime($dia));
$dataInicial = date("$ano-01-01");
$dataFinal = date("$ano-12-31");

// preenche as classes com os horários agendados
for ($i = 0; $i < 8; $i++){
    CriarCSSdia($conexao, $dia, $listaPistas[$i], $listaLetras[$i], $listaPistasClasse[$i], $listaY[$i]);
}

for ($i = 0; $i < 7; $i++){
    $diaSemana = $semana[$i]; 
    CriarCSSsemana($conexao, $diaSemana, $listaPistas, $listaLetras, $listaPistasClasse, $listaY);
}

foreach ($mes as $diaMes){
    CriarCSSmes($conexao, $diaMes, $listaPistas, $listaLetras, $listaPistasClasse, $listaY);
}



$WidthGraficoMes = count($mes) * 140 + 78 + 78;

$listaMeses = CriarListaMeses($conexao, $dia, $listaPistas, $listaPistasClasse);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filtroData'])) {
    // Aqui você processa os dados do formulário e gera as informações para o novo gráfico

    $dataInicial = $_POST['dataInicial'];
    $dataFinal = $_POST['dataFinal'];

    // Chame a função para gerar as divs
    $novoGraficoHTML = CriarNovoGraficoSolicitante($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);
    $novoGraficoHTMLHoras = CriarNovoGraficoSolicitanteHoras($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);

    // Saída dos dados HTML gerados
    $graficosNovos = $novoGraficoHTML.'///divisao///'.$novoGraficoHTMLHoras;
    echo $graficosNovos;

    // Encerre a execução para evitar que o restante da página seja exibido desnecessariamente
    exit();
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
            <div style="display: flex; flex-flow: row; justify-content: center; align-items: center; width: 100%;">
                
                <div class='graf_container'>
                    <div class="circle circle-left">
                        <div class="arrow left_arrow" style="left: 10px;"></div>
                    </div>

                    <div id="graf_dia" class="div__grafico div__width ativo">

                        <div class="tit">
                        <?php
                            $diastr = strtotime($dia);
                            echo '<div class="all_tit"><h2 style="color: white;">Agendamentos por Dia ('.date('d/m/Y', $diastr).')</h2></div>'
                            ?>
                        </div>
                        <div class="out_grafico">
                            <div class="grafico" style="position: relative">
                                <hr style="width: 1px; position: absolute;left: 170px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 248px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 326px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 404px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 482px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 560px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 638px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 716px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 794px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 872px;height: 374px;z-index: 1;top: 11%;">
                                <hr style="width: 1px; position: absolute;left: 950px;height: 374px;z-index: 1;top: 11%;">
                                <div class="scl">
                                    <div class="quad_graf" style="border-bottom: none"></div>
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
                                    <div class="quad_graf" style="border: none"><div class="b13" style="z-index: 2;">18:00</div></div>
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
                                            CriarHTMLdia($conexao, $dia, $listaPistas[$i], $listaLetras[$i]);
                                            echo '</div>';
                                        }
                                    ?>
                                    </div>
                                    <div class="espaco"></div>
                                </div>
                            </div>
                        </div>
                        <div class="leg_mes">
                            <div class="k6 quad_graf"><p>Exclusivo</p></div>
                            <div class="k7 quad_graf"></div>
                            <div class="k8 quad_graf"><p>Não<br>Exlusivo</p></div>
                            <div class="k9 quad_graf"></div>
                        </div>
                    </div>

                    <div id="graf_semana" class="div__grafico div__width">

                        <div class="tit">
                            <?php
                            $diastr = strtotime($dia);
                            echo '<div class="all_tit"><h2 style="color: white;">Agendamentos por Semana (CW'.calcularCW($dia).')</h2></div>';
                            ?>
                        </div>
                        <div class="out_grafico">
                            <div class="grafico" style="position: relative">
                                <div class="scl">
                                <div class="quad_graf" style="border-bottom: none"></div>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="quad_graf_semana">
                                        <?php
                                        echo '<div class="quad_graf_semana segunda" style="border: none"><div class="b3 title_semana" style="z-index: 2;"><input type="submit" value="segunda"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="date" readonly name="dia" id="dia" value="'.$semana[0].'"></div></div>  ';
                                        ?>
                                    </form>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="quad_graf_semana">
                                        <?php
                                        echo '<div class="quad_graf_semana terca" style="border: none"><div class="b4 title_semana" style="z-index: 2;"><input type="submit" value="terça"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="date" readonly name="dia" id="dia" value="'.$semana[1].'"></div></div>  ';
                                        ?>
                                    </form>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="quad_graf_semana">
                                        <?php
                                        echo '<div class="quad_graf_semana quarta" style="border: none"><div class="b5 title_semana" style="z-index: 2;"><input type="submit" value="quarta"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="date" readonly name="dia" id="dia" value="'.$semana[2].'"></div></div>  ';
                                        ?>
                                    </form>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="quad_graf_semana">
                                        <?php
                                        echo '<div class="quad_graf_semana quinta" style="border: none"><div class="b6 title_semana" style="z-index: 2;"><input type="submit" value="quinta"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="date" readonly name="dia" id="dia" value="'.$semana[3].'"></div></div>  ';
                                        ?>
                                    </form>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="quad_graf_semana">
                                        <?php
                                        echo '<div class="quad_graf_semana sexta" style="border: none"><div class="b7 title_semana" style="z-index: 2;"><input type="submit" value="sexta"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="date" readonly name="dia" id="dia" value="'.$semana[4].'"></div></div>  ';
                                        ?>
                                    </form>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="quad_graf_semana">
                                        <?php
                                        echo '<div class="quad_graf_semana sabado" style="border: none"><div class="b8 title_semana" style="z-index: 2;"><input type="submit" value="sábado"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="date" readonly name="dia" id="dia" value="'.$semana[5].'"></div></div>  ';
                                        ?>
                                    </form>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="quad_graf_semana">
                                        <?php
                                        echo '<div class="quad_graf_semana domingo" style="border: none"><div class="b2 title_semana" style="z-index: 2;"><input type="submit" value="domingo"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="date" readonly name="dia" id="dia" value="'.$semana[6].'"></div></div>  ';
                                        ?>
                                    </form>
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
                                    <div class="grafico_linhas_semana">
                                    <?php
                                    // cria as divs com as classes para cada pista
                                        for ($i = 0; $i < 7; $i++){
                                            $diaSemana = $semana[$i];
                                            echo '<div id="'.$listaSemana[$i].'" class="semana">';  
                                            CriarHTMLsemana($conexao, $diaSemana, $listaPistas, $listaLetras, $listaPistasClasse);
                                            echo '</div>';
                                        }
                                    ?>
                                    </div>
                                    <div class="espaco"></div>
                                </div>
                            </div>
                        </div>
                        <div class="leg_mes">
                            <div class="k6 quad_graf"><p>Exclusivo</p></div>
                            <div class="k7 quad_graf"></div>
                            <div class="k8 quad_graf"><p>Não<br>Exlusivo</p></div>
                            <div class="k9 quad_graf"></div>
                        </div>
                    </div>

                    <div id="graf_mes" class="div__grafico grafico_mes">

                        <div class="tit">
                            <?php
                            $diastr = strtotime($dia);
                            echo '<div class="all_tit"><h2 style="color: white;">Agendamentos por Mês ('.ucfirst($listaAno[obterNumeroDoMes($dia)-1]).')</h2></div>'
                            ?>
                        </div>
                        <div class="out_grafico">
                            <div class = nome_pistas_over>
                                <div class="c1 quad_graf_over">VDA</div>
                                <div class="d1 quad_graf_over">NVH</div>
                                <div class="e1 quad_graf_over">Obstáculos</div>
                                <div class="f1 quad_graf_over">Rampa 12% e 20%</div>
                                <div class="g1 quad_graf_over">Rampa 40%</div>
                                <div class="h1 quad_graf_over">Rampa 60%</div>
                                <div class="i1 quad_graf_over">Asfalto</div>
                                <div class="j1 quad_graf_over">Pista Completa</div>
                            </div>
                            <?php
                                echo '<div class="grafico" style="position: relative; width: '.$WidthGraficoMes.'px;">';
                            ?>
                                <div class="scl" style="width: auto; display: flex;">
                                    <div class="quad_graf" style="border-bottom: none"></div>
                                    <?php
                                        foreach ($mes as $diaMes){
                                            echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST" class="quad_graf_semana">';
                                            echo '<div class="quad_graf_semana" style="border: none"><div class="title_mes" style="z-index: 2;"><input type="submit" value="'.date('d/m', strtotime($diaMes)).'"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="hidden" readonly name="dia" id="dia" value="'.$diaMes.'"></div></div>';
                                            echo '</form>';
                                        }
                                    ?>
                                </div>
                                <div class="grafico_preenchimentos_ano">
                                    <div class="grafico_linhas_semana">
                                    <?php
                                    // cria as divs com as classes para cada pista
                                        foreach ($mes as $diaMes){
                                            echo '<div id="'.$diaMes.'" class="semana">';  
                                            CriarHTMLmes($conexao, $diaMes, $listaPistas, $listaLetras, $listaPistasClasse);
                                            echo '</div>';
                                        }
                                    ?>
                                    </div>
                                    <div class="espaco"></div>
                                </div>
                            </div>
                        </div>
                        <div class="leg_mes">
                            <div class="k6 quad_graf"><p>Exclusivo</p></div>
                            <div class="k7 quad_graf"></div>
                            <div class="k8 quad_graf"><p>Não<br>Exlusivo</p></div>
                            <div class="k9 quad_graf"></div>
                        </div>
                    </div>

                    <div id="graf_ano" class="div__grafico div__width">

                        <div class="tit">
                            <?php
                            $diastr = strtotime($dia);
                            echo '<div class="all_tit"><h2 style="color: white;">Agendamentos por Ano ('.date('Y', $diastr).')</h2></div>'
                            ?>
                        </div>
                        <div class="out_grafico" style="height: 600px;">
                            <?php
                            echo '<div class="grafico grafico_ano" style="position: relative;">';
                            ?>
                                <div class="dupla_meses">
                                    <div id="ano" class="graf_barras" style="width: 800px; height:400px">
                                        <div class="barras_titulo"><h3>
                                            Ano Completo
                                        </h3></div>
                                        <?php
                                            echo '<div style="justify-content: center; display: flex"><p>Total: '.$listaMeses[12]['total'].'</p></div>';
                                        ?>
                                        <div class="barras">
                                            <?php
                                                $maior = 1;
                                                for ($i = 0; $i < 8; $i++){
                                                    if ($listaMeses[12][$listaPistasClasse[$i]] > $maior){
                                                        $maior = $listaMeses[12][$listaPistasClasse[$i]];
                                                    }
                                                }
                                                for ($i = 0; $i < 8; $i++){
                                                    if ($i % 2 == 0){
                                                        $cor = "#4C7397";
                                                    }
                                                    else{
                                                        $cor = "#001e50";
                                                    }
                                                    $porcentagemBarra = ($listaMeses[12][$listaPistasClasse[$i]] * 100) / $maior;
                                                    echo '<div class="barra_ano_'.$listaPistasClasse[$i].'"> <p style="color: white"> '.$listaMeses[12][$listaPistasClasse[$i]].' </p> </div>';
                                                    echo '<style>';
                                                    echo '.barra_ano_'.$listaPistasClasse[$i].' {background-color: '.$cor.'; width: 50px; display: flex; justify-content: center; align-items: end;}';
                                                    if ($listaMeses[12][$listaPistasClasse[$i]] > 0){
                                                        echo '.barra_ano_'.$listaPistasClasse[$i].' {height: '.$porcentagemBarra.'%;}';
                                                    }
                                                    else{
                                                        echo '.barra_ano_'.$listaPistasClasse[$i].' {height: 1%;}';
                                                    }
                                                    echo '</style>';
                                                }
                                            ?>
                                        </div>
                                        <div class="barras_legenda">
                                            <?php 
                                                for ($i = 0; $i < 8; $i++){
                                                    echo '<div class="legenda barra_legenda_tudo_'.$listaPistasClasse[$i].'"> '.$listaPistasAno[$i].' </div>';
                                                    echo '<style>';
                                                    echo '.barra_legenda_tudo_'.$listaPistasClasse[$i].' {color: black; transform: rotate(45deg); width: 80px; align-items: end; height: 100%; align-items:  start;  display: flex; justify-content: space-between; position: absolute; left: '.(-30 + ($i * 103)).'px; bottom: -20px;}';
                                                    echo '</style>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $maiorMeses = 1;
                                $z = 0;
                                for ($l = 0; $l < 12; $l++){
                                    for ($k = 0; $k < 8; $k++){
                                        if ($listaMeses[$l][$listaPistasClasse[$k]] > $maiorMeses){
                                            $maiorMeses = $listaMeses[$l][$listaPistasClasse[$k]];
                                        }
                                    }
                                }
                                for ($i = 0; $i < 6; $i++){                                    
                                    echo '<div id="'.$i.'_meses" class="dupla_meses">';
                                        for ($j = 0; $j < 2; $j++){
                                            echo '<div id="'.ucfirst($listaAno[$z]).'" class="graf_barras">
                                                <div class="barras_titulo"><form action="'.$_SERVER['PHP_SELF'].'" method="POST" class="quad_graf_ano"><div class="quad_graf_ano" style="border: none"><div class="title_ano" style="z-index: 2;"><input type="submit" value="'.ucfirst($listaAno[$z]).'"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="hidden" readonly name="dia" id="dia" value="'.date('Y-m-d', strtotime($primeirosDiasDosMeses[$z])).'"></div></div></form></div>
                                                <div style="justify-content: center; display: flex"><p>Total: '.$listaMeses[$z]['total'].'</p></div>
                                                <div class="barras">';
                                                    for ($l = 0; $l < 8; $l++){
                                                        if ($l % 2 == 0){
                                                            $cor = "#4C7397";
                                                        }
                                                        else{
                                                            $cor = "#001e50";
                                                        }
                                                        $porcentagemBarra = ($listaMeses[$z][$listaPistasClasse[$l]] * 100) / $maiorMeses;
                                                        echo '<div class="barra_'.$listaAno[$z].'_'.$listaPistasClasse[$l].'">';
                                                        if ($listaMeses[$z][$listaPistasClasse[$l]] > 0){
                                                        echo '<p style="color: white"> '.$listaMeses[$z][$listaPistasClasse[$l]].' </p>';
                                                        }
                                                        echo '</div>
                                                        <style>
                                                            .barra_'.$listaAno[$z].'_'.$listaPistasClasse[$l].' {background-color: '.$cor.'; width: 50px; display: flex; justify-content: center; align-items: end;}';
                                                            if ($listaMeses[$z][$listaPistasClasse[$l]] > 0){
                                                                echo '.barra_'.$listaAno[$z].'_'.$listaPistasClasse[$l].' {height: '.$porcentagemBarra.'%;}';
                                                            }
                                                            else{
                                                                echo '.barra_'.$listaAno[$z].'_'.$listaPistasClasse[$l].' {height: 1%;}';
                                                            }
                                                        echo'</style>';
                                                    }
                                                echo '</div>
                                                <div class="barras_legenda">';
                                                    for ($l = 0; $l < 8; $l++){
                                                        echo '<div class="legenda barra_legenda_'.$listaAno[$z].'_'.$listaPistasClasse[$l].'"> '.$listaPistasAno[$l].' </div>
                                                        <style>
                                                            .barra_legenda_'.$listaAno[$z].'_'.$listaPistasClasse[$l].' {color: black; transform: rotate(45deg); width: 80px; align-items: end; height: 100%; align-items:  start;  display: flex; justify-content: space-between; position: absolute; left: '.(-20 + ($l * 68)).'px; bottom: -20px;}
                                                        </style>';
                                                    }
                                                echo '</div>
                                            </div>';
                                            $z++;
                                        }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div id="graf_area" class="div__grafico div__width">
                        <div class="tit">
                            <?php
                            $diastr = strtotime($dia);
                            
                            $dataFormatadaInicial = date("d/m/Y", strtotime($dataInicial));
                            $dataFormatadaFinal = date("d/m/Y", strtotime($dataFinal));

                            echo '<div id="chart-title" class="all_tit"><h2 style="color: white;">Agendamentos por Área Solicitante ('.$dataFormatadaInicial.' -> '.$dataFormatadaFinal.')</h2></div>'
                            ?>
                        </div>
                        <div class="out_grafico" style="height: fit-content;">
                            <?php
                            echo '<div class="grafico grafico_ano" style="position: relative; width: 70rem;">';
                            ?>
                                <div id="filter-form">
                                    <form id="checkbox-form" class="form_filtro quad_filtro">
                                        <div class="filtro_data">
                                            <div style="width:100%">
                                            <label style="font-size: 20px;" nome='lblInicio' for="dataInicial">Data Inicial:</label>
                                            <div class="input"><input style="height:30px" type="date" name="dataInicial" id="dataInicial" required></div>
                                            </div>

                                            <div style="width:100%">
                                            <label style="font-size: 20px;" nome='lblFinal' for="dataFinal">Data Final:</label>
                                            <div class="input"><input style="height:30px" type="date" name="dataFinal" id="dataFinal" required></div>
                                            </div>

                                            <div class="submit" style="width: 100%; height:30px"><button style="font-size: 16px;" type="button" onclick="filtrarAgendamentos()">Filtrar</button></div>

                                            <div style="display:flex; flex-direction:column; justify-content:space-around; align-items:start; gap:5px; width:100%" id="filtro_opcao">
                                                <label style="font-size: 18px;">
                                                    <input style="font-size: 20px;" class="opcao" type="radio" name="opcaoFiltro" value="Quantidade" checked>
                                                    Quantidade
                                                </label>
                                                <label style="font-size: 18px;">
                                                    <input class="opcao" type="radio" name="opcaoFiltro" value="Horas">
                                                    Horas
                                                </label>
                                            </div>  
                                        </div>
                                        <hr class="linha">
                                        <div class="borda_filtros">
                                            <div style="height:25px; width: 100%;">
                                                <div style="font-size: 20px; display: flex; justify-content: center;">Áreas Solicitantes</div>
                                            </div>
                                            <div class="filtro_solicitante">
                                                
                                            <?php foreach ($listaAreasSolicitantes as $solicitante){
                                                echo'<div style="display:flex; flex-direction: row"; width:auto;>';
                                                echo '<label>
                                                    <input type="checkbox" class="filter-checkbox" checked>
                                                    '.$solicitante.'
                                                </label>';
                                                echo'</div>';
                                            }
                                            echo'</div>';
                                        echo'</div>';
                                        ?>
                                        <hr class="linha">
                                        <div>
                                            <div style="height:25px; width: 100%;">
                                                <div style="font-size: 20px; display: flex; justify-content: center;">Áreas da Pista</div>
                                            </div>
                                            <?php
                                            echo'<div class="filtro_pista">';
                                            foreach ($listaPistas as $pista){
                                                echo'<div>';
                                                echo '<label>
                                                    <input type="checkbox" class="filter-pista" data-pista="'.$pista.'" checked>
                                                    '.$pista.'
                                                </label>';
                                                echo'</div>';
                                            }
                                            ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="graf_solicit">
                                    <div id="bar_names" class="bar_names">
                                    <?php foreach ($listaAreasSolicitantes as $solicitante){
                                        echo '<div id="bar_name" class="bar_name">'.$solicitante.'</div>';
                                        }
                                    ?>
                                    </div>
                                    <div class="bar-chart" id="bar-chart-vezes" name="bar_vezes">
                                        <?php
                                        CriarGraficoSolicitante($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);
                                        ?>
                                    </div>
                                    <div class="bar-chart bar_inv" id="bar-chart-horas" name="bar_horas">
                                        <?php
                                        CriarGraficoSolicitanteHoras($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);
                                        ?>
                                    </div>
                                    
                                </div>
                                <div class="legenda_pistas">
                                    <?php
                                    $n = 0;
                                    foreach($listaPistasClasse as $pista){
                                        echo '<div class="legenda_div">';
                                            echo '<div class="legenda_cor" id="legenda_cor_'.$pista.'"></div>';
                                            echo '<div>';
                                            echo '<p>'.$listaPistas[$n].'</p>';
                                            echo '</div>';
                                        echo '</div>';
                                        switch($pista){
                                            case 'vda':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #6393BA;}';
                                                echo '</style>';
                                                break;
                                            case 'nvh':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #46ABFB;}';
                                                echo '</style>';
                                                break;
                                            case 'obs':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #5F6E7A;}';
                                                echo '</style>';
                                                break;
                                            case 'r_12_20':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #A58C65;}';
                                                echo '</style>';
                                                break;
                                            case 'r_40':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #FAB346;}';
                                                echo '</style>';
                                                break;
                                            case 'r_60':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #A1BFFB;}';
                                                echo '</style>';
                                                break;
                                            case 'asf':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #FADEA0;}';
                                                echo '</style>';
                                                break;
                                            case 'pc':
                                                echo '<style>';
                                                echo '#legenda_cor_'.$pista.' {background-color: #636BBA;}';
                                                echo '</style>';
                                                break;
                                        }
                                        $n++;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="circle circle-right">
                        <div class="arrow right_arrow" style="right: 10px;"></div>
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

    <!-- ///////////////////////////////////////////////////////// -->

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-bundle.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include Tippy.js CSS (you can customize the theme) -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js/dist/tippy.css" />
    <!-- Include Tippy.js script -->
    <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy-bundle.umd.js"></script>

    <!-- ///////////////////////////////////////////////////////// -->

    <script>
        const grafContainer = document.querySelector('.graf_container');
        const leftArrow = document.querySelector('.circle-left');
        const rightArrow = document.querySelector('.circle-right');
        const graficos = document.querySelectorAll('.div__grafico');
        const ativo = document.querySelector('.ativo');
        
        tippy('#bar_pista[name="VDA"]', {
            content: 'VDA',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });

        tippy('#bar_pista[name="NVH"]', {
            content: 'NVH',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });

        tippy('#bar_pista[name="Obstáculos"]', {
            content: 'Obstáculos',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });

        tippy('#bar_pista[name="Rampa 12% e 20%"]', {
            content: 'Rampa 12% e 20%',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });

        tippy('#bar_pista[name="Rampa 40%"]', {
            content: 'Rampa 40%',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });

        tippy('#bar_pista[name="Rampa 60%"]', {
            content: 'Rampa 60%',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });

        tippy('#bar_pista[name="Asfalto"]', {
            content: 'Asfalto',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });

        tippy('#bar_pista[name="Pista Completa"]', {
            content: 'Pista Completa',
            arrow: true,
            placement: 'top', // Tooltip placement
            theme: 'light', // Tooltip theme
            duration: 300, // Tooltip animation duration in milliseconds
        });
        
        

        let currentIndex = 0;

        leftArrow.addEventListener('click', function () {
            if (currentIndex > 0) {
                antigo = graficos[currentIndex];
                antigo.classList.remove('ativo');
                currentIndex--;
                novo = graficos[currentIndex];
                novo.classList.add('ativo');
            } else {
                // Se já estiver no primeiro gráfico, vá para o último (loop)
                graficos[currentIndex].classList.remove('ativo');
                currentIndex = graficos.length - 1;
                graficos[currentIndex].classList.add('ativo');
                
            }
        });

        rightArrow.addEventListener('click', function () {
            if (currentIndex < graficos.length - 1) {
                graficos[currentIndex].classList.remove('ativo');
                currentIndex++;
                graficos[currentIndex].classList.add('ativo');
            } else {
                // Se já estiver no último gráfico, volte para o primeiro (loop)
                graficos[currentIndex].classList.remove('ativo');
                currentIndex = 0;
                graficos[currentIndex].classList.add('ativo');
                
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.filter-checkbox');
            const checkboxesPista = document.querySelectorAll('.filter-pista');
            const options =document.querySelectorAll('.opcao');

            function updateChart() {
                checkboxes.forEach((checkbox, index) => {
                    const bar = document.querySelector(`#bar-chart-vezes #bar:nth-child(${index + 1})`);
                    bar.style.display = checkbox.checked ? 'flex' : 'none';

                    const barH = document.querySelector(`#bar-chart-horas #bar:nth-child(${index + 1})`);
                    barH.style.display = checkbox.checked ? 'flex' : 'none';

                    const nomes = document.querySelector(`#bar_names #bar_name:nth-child(${index + 1})`);
                    nomes.style.display = checkbox.checked ? 'flex' : 'none';
                });
            }

            function updateChartPista() {
                checkboxesPista.forEach((checkboxPista) => {
                    const pista = checkboxPista.getAttribute('data-pista');
                    const bars = document.querySelectorAll(`#bar-chart-vezes #bar_pista[name="${pista}"]`);
                    const barsH = document.querySelectorAll(`#bar-chart-horas #bar_pista[name="${pista}"]`);

                    bars.forEach((bar) => {
                        bar.style.display = checkboxPista.checked ? 'flex' : 'none';
                    });

                    barsH.forEach((bar) => {
                        bar.style.display = checkboxPista.checked ? 'flex' : 'none';
                    });
                });
            }

            function updateOption() {
                const bar_charts = document.querySelectorAll('.bar-chart');

                bar_charts.forEach((bar_chart) => {
                    if (bar_chart.getAttribute('name') == 'bar_vezes'){
                        if (options[0].checked){
                            bar_chart.style.display = 'flex';
                        }
                        else{
                            bar_chart.style.display = 'none';
                        }
                    }
                    else{
                        if (options[1].checked){
                            bar_chart.style.display = 'flex';
                        }
                        else{
                            bar_chart.style.display = 'none';
                        }
                    }
                });
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateChart);
            });

            checkboxesPista.forEach(checkbox => {
                checkbox.addEventListener('change', updateChartPista);
            });

            options.forEach(option => {
                option.addEventListener('change', updateOption)
            });

        });

        function filtrarAgendamentos() {
            // Obtém os valores dos campos do formulário
            var dataInicial = document.getElementById('dataInicial').value;
            var dataFinal = document.getElementById('dataFinal').value;

            if(dataInicial == '' || dataFinal == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Preencha os campos de data!',
                })
                return;
            }
            else{

                // Faz a requisição AJAX para chamar a função PHP
                $.ajax({
                    url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                    type: 'POST',
                    data: { dataInicial: dataInicial, dataFinal: dataFinal, filtroData: true },
                    success: function (graficosNovos) {
                        // Dividir a string com base no marcador '///divisao///'
                        var partes = graficosNovos.split('///divisao///');

                        // A primeira parte contém o texto antes do marcador
                        var antesMarcador = partes[0];

                        // A segunda parte contém o texto depois do marcador
                        var depoisMarcador = partes[1];

                        // Encontrar o índice do primeiro <div id="bar"
                        var startIndex = antesMarcador.indexOf('<div id="bar"');
                        // Extrair a parte relevante da string
                        var parteCorretaV = antesMarcador.substring(startIndex);

                        // Encontrar o índice do primeiro <div id="bar"
                        var startIndex = depoisMarcador.indexOf('<div id="bar"');
                        // Extrair a parte relevante da string
                        var parteCorretaH = depoisMarcador.substring(startIndex);

                        // Substitui o conteúdo do contêiner do gráfico com as novas divs geradas pelo PHP
                        document.getElementById('bar-chart-vezes').innerHTML = parteCorretaV;
                        document.getElementById('bar-chart-horas').innerHTML = parteCorretaH;

                        const chartTitle = document.getElementById('chart-title');


                       var parts = dataInicial.split('-');

                        // Formatar a data como 'dd/mm/yyyy'
                        var dataFormatadaInicial = parts[2] + '/' + parts[1] + '/' + parts[0];

                        var parts = dataFinal.split('-');

                        // Formatar a data como 'dd/mm/yyyy'
                        var dataFormatadaFinal = parts[2] + '/' + parts[1] + '/' + parts[0];

                        chartTitle.innerHTML = `<h2 style="color: white;">Agendamentos por Área Solicitante (${dataFormatadaInicial} -> ${dataFormatadaFinal})</h2>`;
                    },
                    error: function (error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            }
        }


    </script>


    <!-- ///////////////////////////////////////////////////////// -->
</body>
</html>