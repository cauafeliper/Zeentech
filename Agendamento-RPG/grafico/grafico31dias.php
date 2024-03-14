<?php
    include_once('../config/config.php');
    session_start();
?>

<?php
date_default_timezone_set('America/Sao_Paulo');
include 'functions.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Gráfico</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/grafico.css">
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">

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
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    
</head>
<body>
<?php 
$dia = date('Y-m-d');

$listaPistas = array('VDA', 'NVH', 'Obstáculos', 'Rampa 12% e 20%', 'Rampa 40%', 'Rampa 60%', 'Asfalto', 'Pista Completa');
$listaPistasClasse = array('vda', 'nvh', 'obs', 'r_12_20', 'r_40', 'r_60', 'asf', 'pc');
$listaLetras = array('c', 'd', 'e', 'f', 'g', 'h', 'i', 'j');
$listaY = array('top', 'top', 'top', 'top', 'bottom', 'bottom', 'bottom', 'bottom');
$listaSemana = array('segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo');
$listaAno = array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembo', 'outubro', 'novembro', 'dezembro');
$semana = calcularDiasDaSemana($dia);

$hoje = $dia;
$data30 = new DateTime(date('Y-m-d'));
$data30->add(new DateInterval('P30D'));
$data30 = $data30->format('Y-m-d');
$diaFinal = $data30;

$mes = obterDiasEntreDatas($hoje, $diaFinal);

$WidthGraficoMes = count($mes) * 140 + 78 + 78;

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
?>
    <main>
        <div id="graf_mes" class="div__grafico grafico_mes ativo">
            <div class="tit">
                <?php
                $diastr = strtotime($dia);
                echo '<div class="all_tit"><h2 style="color: white;">Agendamentos em 31 dias</h2></div>'
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
                                echo '<div class="quad_graf_semana" style="border: none"><div class="title_mes_30" style="z-index: 2;"><input readonly  value="'.date('d/m', strtotime($diaMes)).'"><input style="background-color: unset; color:#001e50; text-align: center; cursor: unset; padding-left: 10%;" type="hidden" readonly name="dia" id="dia" value="'.$diaMes.'"></div></div>';
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
                <div style="width: 100%; overflow-x: auto">
                <div class="leg_mes" style="display: flex; justify-content: center; flex-direction:row;">
                    <div class="k6 quad_graf"><p>Exclusivo</p></div>
                    <div class="k7 quad_graf"></div>
                    <div class="k8 quad_graf"><p>Não<br>Exlusivo</p></div>
                    <div class="k9 quad_graf"></div>
                    <div class="k10 quad_graf"><p>Exclusivo Pendente</p></div>
                    <div class="k11 quad_graf"></div>
                    <div class="k12 quad_graf"><p>Não Excl Pendente</p></div>
                    <div class="k13 quad_graf"></div>
                </div>
                </div>
        </div>
    </main>

    <script>
        function PopupAgendamento(id, area_pista, dia, horario, objetivo, solicitante, numero_solicitante, empresa_solicitante, area_solicitante, exclsv, obs, status){
            var classeAgendamento = document.getElementById(id);
            console.log('clicou na '+id);

            Swal.fire({
                icon: 'info',
                title: "Informações do agendamento",
                html:"<div style='text-align: start; padding: 0 2rem; line-height: 1.5rem'>"+
                "Id: "+id+"<br>"+
                "Área da Pista: "+area_pista+"<br>"+
                "Dia: "+dia+"<br>"+
                "Horário: "+horario+"<br>"+
                "Objetivo: "+objetivo+"<br>"+
                "Solicitante: "+solicitante+"<br>"+
                "Numero do solicitante: "+numero_solicitante+"<br>"+
                "Empresa do solicitante: "+empresa_solicitante+"<br>"+
                "Área Solicitante: "+area_solicitante+"<br>"+
                "É exclusivo? "+exclsv+"<br>"+
                "Observação: "+obs+"<br>"+
                "Status: "+status+"<br>"+
                "</div>"
                ,
                showConfirmButton: false,
                showCloseButton: true,
                allowOutsideClick: false,
            })
        }


    </script>

</body>
</html>