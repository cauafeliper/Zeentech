<?php
    include_once('../config/config.php');
    session_start();
?>

<?php
// Conteúdo da página com o gráfico
date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo

// Certifique-se de incluir qualquer lógica ou bibliotecas necessárias para gerar o gráfico
include 'functions.php'; // Substitua pelo caminho correto
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Se houver estilos específicos para a página com o gráfico, você pode incluí-los aqui -->
    <link rel="stylesheet" href="../estilos/grafico.css">
</head>
<body>
    <?php 
    
    
    ?>

    <!-- Adicione o código do gráfico aqui -->
    <div id="graf_dia" class="div__grafico div__width ativo">
        <style> .div__grafico{height: max-content; width: 100%;} .leg_mes{width: 100%;} </style>
        <div class="tit">
        <?php

            $dia = '2024-01-28';

            $listaPistas = array('VDA', 'NVH', 'Obstáculos', 'Rampa 12% e 20%', 'Rampa 40%', 'Rampa 60%', 'Asfalto', 'Pista Completa');
            $listaPistasClasse = array('vda', 'nvh', 'obs', 'r_12_20', 'r_40', 'r_60', 'asf', 'pc');
            $listaLetras = array('c', 'd', 'e', 'f', 'g', 'h', 'i', 'j');
            $listaY = array('top', 'top', 'top', 'top', 'bottom', 'bottom', 'bottom', 'bottom');
            
            for ($i = 0; $i < 8; $i++){
                CriarCSSdia($conexao, $dia, $listaPistas[$i], $listaLetras[$i], $listaPistasClasse[$i], $listaY[$i]);
            }

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

</body>
</html>

</html>

</html>




