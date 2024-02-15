<?php
    /* include_once('../config/config.php');
    session_start(); */
?>
<?php 

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo

function PorcentagemMinuto($minuto) { // retorna a porcentagem do minuto em relação a 60 minutos
    $minuto = intval($minuto);
    $porcentagem = ($minuto / 60) * 100;
    return $porcentagem;
}

// Função para calcular os dias da semana com base em uma data
function calcularDiasDaSemana($dataSelecionada) {
    $data = new DateTime($dataSelecionada);

    // Obtém o número do dia da semana (1 = segunda, 2 = terça, ..., 7 = domingo)
    $diaDaSemana = $data->format('N');

    // Calcula o início da semana (segunda) subtraindo o número do dia da semana
    $inicioDaSemana = clone $data;
    $inicioDaSemana->sub(new DateInterval('P' . ($diaDaSemana - 1) . 'D'));

    // Calcula o final da semana (domingo) adicionando o restante dos dias
    $finalDaSemana = clone $inicioDaSemana;
    $finalDaSemana->add(new DateInterval('P6D'));

    // Cria um array para armazenar as datas da semana
    $datasDaSemana = [];

    // Preenche o array com as datas da semana
    while ($inicioDaSemana <= $finalDaSemana) {
        $datasDaSemana[] = $inicioDaSemana->format('Y-m-d');
        $inicioDaSemana->add(new DateInterval('P1D')); // Adiciona um dia
    }

    return $datasDaSemana;
}


function calcularDiasDoMes($dataSelecionada) {
    $data = new DateTime($dataSelecionada);

    // Obtém o primeiro dia do mês
    $primeiroDiaDoMes = new DateTime($data->format('Y-m-01'));

    // Obtém o último dia do mês
    $ultimoDiaDoMes = new DateTime($data->format('Y-m-t'));

    // Cria um array para armazenar as datas do mês
    $datasDoMes = [];

    // Preenche o array com as datas do mês
    while ($primeiroDiaDoMes <= $ultimoDiaDoMes) {
        $datasDoMes[] = $primeiroDiaDoMes->format('Y-m-d');
        $primeiroDiaDoMes->add(new DateInterval('P1D')); // Adiciona um dia
    }

    return $datasDoMes;
}

function obterDiasEntreDatas($dataInicial, $dataFinal) {
    $dias = array();

    $dataAtual = new DateTime($dataInicial);
    $dataFinalObj = new DateTime($dataFinal);

    // Adiciona a data inicial ao array
    $dias[] = $dataAtual->format('Y-m-d');

    // Loop para adicionar os dias intermediários
    while ($dataAtual < $dataFinalObj) {
        $dataAtual->add(new DateInterval('P1D'));
        $dias[] = $dataAtual->format('Y-m-d');
    }

    return $dias;
}

function CriarCSSdia($conexao, $dia, $area_pista, $letra, $pistaClasse, $y){ // cria as classes com os horários agendados
    $sql = "SELECT hora_inicio, hora_fim, exclsv, status FROM agendamentos WHERE area_pista = '$area_pista' AND dia='$dia' AND (status='Aprovado' OR status='Pendente')";
    $result = $conexao->query($sql);
    $horariosMarcados = array();
    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $exclsv = $row["exclsv"];
            $horarioInicio = $row["hora_inicio"];
            $horarioFim = $row["hora_fim"];
            $status = $row["status"];
            
            $horaInicio = $horarioInicio[0] . $horarioInicio[1];
            $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

            $horaFim = $horarioFim[0] . $horarioFim[1];
            $minutoFim = $horarioFim[3] . $horarioFim[4];

            if ($status === 'Aprovado'){
                $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
            }
            else{
                $cor = ($exclsv === 'Sim') ? '#808080' : '#bcb8b8';
            }

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
            echo '.' . $classe . '{transition: opacity 0.3s ease-in-out;position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-radius: 15px; border-top: 10px solid white; border-bottom: 10px solid white;}';

            echo '.'.$classeTip. '{visibility:hidden; opacity:0; transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out; position: absolute; justify-content: center; '.$y.': 35px; width: 200px; height: fit-content; left: '.$leftTip.'px; border-radius: 15px; z-index: 3; display: flex; text-align: start; padding: 5px;  flex-flow: column; background-color: #9cadddeb; font-size: 14px;}';
            echo ".$classe:hover + .$classeTip".'{visibility:visible;opacity:1}';
            echo ".$classeTip:hover {visibility:visible;opacity:1}";
            echo ".$classe:hover {opacity: 0.5;}";
            echo '</style>';

            $j++;
        }
    }
}

function CriarHTMLdia($conexao, $dia, $area_pista, $letra){ // cria as divs com as classes para cada pista
    $sql = "SELECT * FROM agendamentos WHERE area_pista = '$area_pista' AND dia='$dia' AND (status='Aprovado' OR status='Pendente')";
    $result = $conexao->query($sql);
    $j = 2;
    while ($row = $result->fetch_assoc()) {
        $horarioInicio = $row["hora_inicio"];
        $horarioFim = $row["hora_fim"];
        $horario = "$horarioInicio - $horarioFim";
        $solicitante = $row["solicitante"];
        $areaSolicitante = $row["area_solicitante"];
        $numero = $row["numero_solicitante"];
        $empresa = $row["empresa_solicitante"];
        $status = $row["status"];

        $classe = "$letra".$j;
        echo '<div class="'.$classe.'" style="cursor:pointer" onclick="PopupAgendamento(\''.$row['id'].'\',\''.$row['area_pista'].'\',\''.$row['dia'].'\',\''.$horario.'\',\''.$row['objtv'].'\',\''.$row['solicitante'].'\',\''.$row['numero_solicitante'].'\',\''.$row['empresa_solicitante'].'\',\''.$row['area_solicitante'].'\',\''.$row['exclsv'].'\',\''.$row['obs'].'\',\''.$row['status'].'\')"></div>';
        echo '<div class="tip_'.$classe.'" id="tip_'.$classe.'" style="color: #001e50"><h3 style="display: flex; height: fit-content; justify-content: center;">'.$horario. '</h3>'.
            '<p><span style="color: #4C7397;">Solicitante: </span>'.$solicitante.'</p>'.
            '<p><span style="color: #4C7397;">Empresa: </span>'."$empresa".'</p>'.
            '<p><span style="color: #4C7397;">Área Solicitante: </span>'."$areaSolicitante".'</p>'.
            '<p><span style="color: #4C7397;">Telefone: </span>'."$numero".'</p>'.
            '<p><span style="color: #4C7397;">Status: </span>'."$status".'</p>'.
        '</div>';
        $j++;
    }
}

function CriarHTMLsemana($conexao, $dia, $listaPistas, $listaLetras, $listaPistaClasse){ // cria as divs com as classes para cada pista
    for ($i = 0; $i < 8; $i++){
        $sql = "SELECT * FROM agendamentos WHERE area_pista = '$listaPistas[$i]' AND dia='$dia' AND (status='Aprovado' OR status='Pendente')";
        $result = $conexao->query($sql);
        $j = 2;
        echo '<div class="'.$listaPistaClasse[$i].'" style="width: 140px">';
        while ($row = $result->fetch_assoc()) {
            $horarioInicio = $row["hora_inicio"];
            $horarioFim = $row["hora_fim"];
            $horario = "$horarioInicio - $horarioFim";
            $solicitante = $row["solicitante"];
            $areaSolicitante = $row["area_solicitante"];
            $numero = $row["numero_solicitante"];
            $empresa = $row["empresa_solicitante"];
            $id = $row["id"];
            $status = $row["status"];

            $classe = "$listaLetras[$i]".$j.'_semana_'.$id;
            echo '<div class="'.$classe.'" style="cursor:pointer" onclick="PopupAgendamento(\''.$row['id'].'\',\''.$row['area_pista'].'\',\''.$row['dia'].'\',\''.$horario.'\',\''.$row['objtv'].'\',\''.$row['solicitante'].'\',\''.$row['numero_solicitante'].'\',\''.$row['empresa_solicitante'].'\',\''.$row['area_solicitante'].'\',\''.$row['exclsv'].'\',\''.$row['obs'].'\',\''.$row['status'].'\')"></div>';
            echo '<div class="tip_'.$classe.'" id="tip_'.$classe.'" style="color: #001e50"><h3 style="display: flex; height: fit-content; justify-content: center;">'.$horario. '</h3>'.
                '<p><span style="color: #4C7397;">Solicitante: </span>'.$solicitante.'</p>'.
                '<p><span style="color: #4C7397;">Empresa: </span>'."$empresa".'</p>'.
                '<p><span style="color: #4C7397;">Área Solicitante: </span>'."$areaSolicitante".'</p>'.
                '<p><span style="color: #4C7397;">Telefone: </span>'."$numero".'</p>'.
                '<p><span style="color: #4C7397;">Status: </span>'."$status".'</p>'.
            '</div>';
            $j++;
        }
        echo '</div>';
    
    }
}

function CriarCSSsemana($conexao, $dia, $listaPistas, $listaLetras, $listaPistaClasse, $listaY){ // cria as classes com os horários agendados
    for ($i = 0; $i < 8; $i++){
        $sql = "SELECT * FROM agendamentos WHERE area_pista = '$listaPistas[$i]' AND dia='$dia' AND (status='Aprovado' OR status='Pendente')";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                $id = $row["id"];
                $status = $row["status"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];

                if ($status === 'Aprovado'){
                    $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
                }
                else{
                    $cor = ($exclsv === 'Sim') ? '#808080' : '#bcb8b8';
                }

                $horariosMarcados[] = array('exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim), 'id' => $id);
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
                $id = $tarefa['id'];
                
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
                    for ($k = $horaInicio + 1; $k < $horaFim; $k++) {
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

                $tamanho = ($tamanho / 936) * 140;
                
                $leftTotal = ($leftTotal / 936) * 140;
                

                $classe = $listaLetras[$i].$j.'_semana_'.$id;
                $classeTip = 'tip_'.$classe;
                $leftTip = $leftTotal + ($tamanho/2) - 100;
                if ($leftTip < 0){
                    $leftTip = 0;
                }
                if ($leftTip > 827){
                    $leftTip = 827;
                }

                echo '<style>';
                echo '.' . $listaPistaClasse[$i] . '{position: relative;}';
                echo '.' . $classe . '{transition: opacity 0.3s ease-in-out;position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-radius: 40%; border-top: 10px solid white; border-bottom: 10px solid white;}';

                echo '.'.$classeTip. '{visibility:hidden; opacity:0; transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;position: absolute; justify-content: center; '.$listaY[$i].': 35px; width: 200px; height: fit-content; left: '.$leftTip.'px; border-radius: 15px; z-index: 3; display: flex; text-align: start; padding: 5px;  flex-flow: column; background-color: #9cadddeb; font-size: 14px;}';
                echo ".$classe:hover + .$classeTip".'{visibility:visible;opacity:1}';
                echo ".$classeTip:hover {visibility:visible;opacity:1}";
                echo ".$classe:hover {opacity: 0.5;}";
                echo '</style>';

                $j++;
            }
        }
    }
}

function CriarHTMLmes($conexao, $dia, $listaPistas, $listaLetras, $listaPistaClasse){ // cria as divs com as classes para cada pista
    for ($i = 0; $i < 8; $i++){
        $sql = "SELECT * FROM agendamentos WHERE area_pista = '$listaPistas[$i]' AND dia='$dia' AND (status='Aprovado' OR status='Pendente')";
        $result = $conexao->query($sql);
        $j = 2;
        echo '<div class="'.$listaPistaClasse[$i].'" style="width: 140px">';
        while ($row = $result->fetch_assoc()) {
            $horarioInicio = $row["hora_inicio"];
            $horarioFim = $row["hora_fim"];
            $horario = "$horarioInicio - $horarioFim";
            $solicitante = $row["solicitante"];
            $areaSolicitante = $row["area_solicitante"];
            $numero = $row["numero_solicitante"];
            $empresa = $row["empresa_solicitante"];
            $id = $row["id"];
            $status = $row["status"];

            $classe = "$listaLetras[$i]".$j.'_mes_'.$id;
            echo '<div class="'.$classe.'" style="cursor:pointer" onclick="PopupAgendamento(\''.$row['id'].'\',\''.$row['area_pista'].'\',\''.$row['dia'].'\',\''.$horario.'\',\''.$row['objtv'].'\',\''.$row['solicitante'].'\',\''.$row['numero_solicitante'].'\',\''.$row['empresa_solicitante'].'\',\''.$row['area_solicitante'].'\',\''.$row['exclsv'].'\',\''.$row['obs'].'\',\''.$row['status'].'\')"></div>';
            echo '<div class="tip_'.$classe.'" id="tip_'.$classe.'" style="color: #001e50"><h3 style="display: flex; height: fit-content; justify-content: center;">'.$horario. '</h3>'.
                '<p><span style="color: #4C7397;">Solicitante: </span>'.$solicitante.'</p>'.
                '<p><span style="color: #4C7397;">Empresa: </span>'."$empresa".'</p>'.
                '<p><span style="color: #4C7397;">Área Solicitante: </span>'."$areaSolicitante".'</p>'.
                '<p><span style="color: #4C7397;">Telefone: </span>'."$numero".'</p>'.
                '<p><span style="color: #4C7397;">Status: </span>'."$status".'</p>'.
            '</div>';
            $j++;
        }
        echo '</div>';
    
    }
}

function CriarCSSmes($conexao, $dia, $listaPistas, $listaLetras, $listaPistaClasse, $listaY){ // cria as classes com os horários agendados
    for ($i = 0; $i < 8; $i++){
        $sql = "SELECT * FROM agendamentos WHERE area_pista = '$listaPistas[$i]' AND dia='$dia' AND (status='Aprovado' OR status='Pendente')";
        $result = $conexao->query($sql);
        $horariosMarcados = array();
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $exclsv = $row["exclsv"];
                $horarioInicio = $row["hora_inicio"];
                $horarioFim = $row["hora_fim"];
                $id = $row["id"];
                $status = $row["status"];
                
                $horaInicio = $horarioInicio[0] . $horarioInicio[1];
                $minutoInicio = $horarioInicio[3] . $horarioInicio[4];

                $horaFim = $horarioFim[0] . $horarioFim[1];
                $minutoFim = $horarioFim[3] . $horarioFim[4];

                if ($status === 'Aprovado'){
                    $cor = ($exclsv === 'Sim') ? '#001e50' : '#4C7397';
                }
                else{
                    $cor = ($exclsv === 'Sim') ? '#808080' : '#bcb8b8';
                }

                $horariosMarcados[] = array('exclsv' => $exclsv, 'cor' => $cor, 'horaInicio' => intval($horaInicio), 'horaFim' => intval($horaFim), 'minutoInicio' => intval($minutoInicio), 'minutoFim' => intval($minutoFim), 'id' => $id);
            }
            $j = 2;
            foreach ($horariosMarcados as $tarefa) {
                $cor = $tarefa['cor'];
                $horaInicio = $tarefa['horaInicio'];
                $horaFim = $tarefa['horaFim'];
                $minutoInicio = $tarefa['minutoInicio'];
                $minutoFim = $tarefa['minutoFim'];
                $id = $tarefa['id'];
                
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
                    for ($k = $horaInicio + 1; $k < $horaFim; $k++) {
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

                $tamanho = ($tamanho / 936) * 140;
                
                $leftTotal = ($leftTotal / 936) * 140;
                

                $classe = $listaLetras[$i].$j.'_mes_'.$id;
                $classeTip = 'tip_'.$classe;
                $leftTip = $leftTotal + ($tamanho/2) - 100;
                if ($leftTip < 0){
                    $leftTip = 0;
                }
                if ($leftTip > 827){
                    $leftTip = 827;
                }

                echo '<style>';
                echo '.' . $listaPistaClasse[$i] . '{position: relative;}';
                echo '.' . $classe . '{transition: opacity 0.3s ease-in-out;position: absolute; width: '.$tamanho.'px; height: 43px; left: '.$leftTotal.'px; background-color: '.$cor.'; border-radius: 40%; border-top: 10px solid white; border-bottom: 10px solid white;}';

                echo '.'.$classeTip. '{visibility:hidden; opacity:0; transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;position: absolute; justify-content: center; '.$listaY[$i].': 35px; width: 200px; height: fit-content; left: '.$leftTip.'px; border-radius: 15px; z-index: 3; display: flex; text-align: start; padding: 5px;  flex-flow: column; background-color: #9cadddeb; font-size: 14px;}';
                echo ".$classe:hover + .$classeTip".'{visibility:visible;opacity:1}';
                echo ".$classeTip:hover {visibility:visible;opacity:1}";
                echo ".$classe:hover {opacity: 0.5;}";
                echo '</style>';

                $j++;
            }
        }
    }
}

function CriarListaMeses($conexao, $dia, $listaPistas, $listaPistasClasse){ // cria as divs com as classes para cada pista
    $listaMeses = [$Janeiro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Fevereiro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Março = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Abril = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Maio = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Junho = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0], $Julho = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Agosto = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Setembro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Outubro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Novembro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Dezembro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $totalAno = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0]];
    $primeirosDias = obterPrimeirosDiasDosMesesDoAno($dia);
    for ($i = 0; $i < 12; $i++){
        $total = 0;
        $anoMes = substr($primeirosDias[$i], 0, 7); // Extrai os primeiros 7 caracteres ('Y-m') da data
        for ($j = 0; $j < 8; $j++){
            $sql = "SELECT area_pista, dia, hora_inicio, hora_fim FROM agendamentos WHERE area_pista = '$listaPistas[$j]' AND SUBSTRING(dia, 1, 7) = '$anoMes' AND status='Aprovado'";
            $result = $conexao->query($sql);
            while ($row = $result->fetch_assoc()) {
                $listaMeses[$i][$listaPistasClasse[$j]]++;
                $total++;
            }
            $listaMeses[12][$listaPistasClasse[$j]] += $listaMeses[$i][$listaPistasClasse[$j]]; 
        }
        $listaMeses[$i]['total'] = $total;
        $listaMeses[12]['total'] += $total;
    }
    return $listaMeses;
}

function CriarListaMesesH($conexao, $dia, $listaPistas, $listaPistasClasse){ // cria as divs com as classes para cada pista
    $listaMeses = [$Janeiro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Fevereiro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Março = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Abril = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Maio = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Junho = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0], $Julho = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Agosto = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Setembro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Outubro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Novembro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $Dezembro = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0], $totalAno = ['vda' => 0, 'nvh' => 0, 'obs' => 0, 'r_12_20' => 0, 'r_40' => 0, 'r_60' => 0, 'asf' => 0, 'pc' => 0, 'total' => 0]];
    $primeirosDias = obterPrimeirosDiasDosMesesDoAno($dia);
    for ($i = 0; $i < 12; $i++){
        $total = 0;
        $anoMes = substr($primeirosDias[$i], 0, 7); // Extrai os primeiros 7 caracteres ('Y-m') da data
        for ($j = 0; $j < 8; $j++){
            $sql = "SELECT area_pista, dia, hora_inicio, hora_fim FROM agendamentos WHERE area_pista = '$listaPistas[$j]' AND SUBSTRING(dia, 1, 7) = '$anoMes' AND status='Aprovado'";
            $result = $conexao->query($sql);
            while ($row = $result->fetch_assoc()) {
                $horasInicio = $row["hora_inicio"][0].$row["hora_inicio"][1];
                $minutosInicio = $row["hora_inicio"][3].$row["hora_inicio"][4];
                $horasFim = $row["hora_fim"][0].$row["hora_fim"][1];
                $minutosFim = $row["hora_fim"][3].$row["hora_fim"][4];

                $horas = intval($horasFim) - intval($horasInicio);
                $minutos = intval($minutosFim) - intval($minutosInicio);

                $tempo = $horas * 60 + $minutos;

                $listaMeses[$i][$listaPistasClasse[$j]] += $tempo;
                $total += $tempo;
            }
            $listaMeses[12][$listaPistasClasse[$j]] += $listaMeses[$i][$listaPistasClasse[$j]]; 
        }
        $listaMeses[$i]['total'] = $total;
        $listaMeses[12]['total'] += $total;
    }
    return $listaMeses;
}

function obterPrimeirosDiasDosMesesDoAno($data) {
    $dataObj = new DateTime($data);
    $ano = $dataObj->format('Y');

    $primeirosDiasDosMeses = [];

    for ($mes = 1; $mes <= 12; $mes++) {
        $primeiroDiaDoMes = new DateTime("$ano-$mes-01");
        $primeirosDiasDosMeses[] = $primeiroDiaDoMes->format('Y-m-d');
    }

    return $primeirosDiasDosMeses;
}

function obterNumeroDoMes($data) {
    // Converte a string de data para um objeto DateTime
    $dataObj = new DateTime($data);

    // Obtém o número do mês (1 para janeiro, 2 para fevereiro, etc.)
    $numeroDoMes = (int)$dataObj->format('n');

    return $numeroDoMes;
}

function calcularCW($dataSelecionada) {
    $data = new DateTime($dataSelecionada);

    // Obtém o número da semana (CW) da data
    $numeroDaSemana = $data->format('W');


    return $numeroDaSemana;
}

function criarListaAreas($conexao){
    $sql = "SELECT nome FROM area_solicitante";
    $result = $conexao->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $listaAreasSolicitantes[] = $row["nome"];
        }
    }
    return $listaAreasSolicitantes;
}

function valorAreas($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas){
    $listaValorAreas = array();
    $maiorVezes = 0;
    $maiorTempo = 0;
    foreach ($listaAreasSolicitantes as $solicitante){
        $listaValorAreas["$solicitante"] = array();
    }
    foreach ($listaAreasSolicitantes as $solicitante){
        $somaVezes = 0;
        $somaTempo = 0;
        foreach ($listaPistas as $pista){
            $vezes = 0;
            $tempoTotal = 0;
            $sql = "SELECT hora_inicio, hora_fim, area_pista, dia FROM agendamentos WHERE area_pista = '$pista' AND area_solicitante = '$solicitante' AND status='Aprovado' AND STR_TO_DATE(dia, '%Y-%m-%d') BETWEEN '$dataInicial' AND '$dataFinal'";
            $result = $conexao->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    
                    $inicioHora = intval($row['hora_inicio'][0].$row['hora_inicio'][1]);
                    $fimHora = intval($row['hora_fim'][0].$row['hora_fim'][1]);
                    
                    $inicioMinuto = intval($row['hora_inicio'][3].$row['hora_inicio'][4]);
                    $fimMinuto = intval($row['hora_fim'][3].$row['hora_fim'][4]);

                    $inicio = $inicioHora * 60 + $inicioMinuto;
                    $fim = $fimHora * 60 + $fimMinuto;
                    
                    $tempo = $fim - $inicio;
                    $tempoTotal += $tempo;
                    
                    $vezes ++;
                }
            }
            $listaValorAreas[$solicitante][$pista] = array('vezes' => $vezes, 'tempo' => $tempoTotal, 'solicitante' => $solicitante, 'pista' => $pista);
            $somaVezes += $vezes;
            $somaTempo += $tempoTotal;
        }
        if ($somaVezes > $maiorVezes){
            $maiorVezes = $somaVezes;
        }
        if ($somaTempo > $maiorTempo){
            $maiorTempo = $somaTempo;
        }
    }
    return [$listaValorAreas, [$maiorVezes, $maiorTempo]];
}

function CriarGraficoSolicitante($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas) {
    $valores = valorAreas($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);
    $listaValorAreas = $valores[0];
    $maiorVezes = $valores[1][0];
    $keys = array_keys($listaValorAreas);
    $n = 0;
    $cor = 'steelblue';
    foreach ($listaValorAreas as $solicitante){
        $totalVezes = 0;
        echo '<div id="bar" name="barra_'.$keys[$n].'" class="barra_solicitante">';
        foreach ($solicitante as $pista){
            $totalVezes += $pista['vezes'];
            if ($maiorVezes == 0){
                $tamanho = 0;
            }
            else{
                $tamanho = ($pista['vezes'] / $maiorVezes) * 100;
            }
            if ($tamanho != 0){
                switch ($pista['pista']){
                    case 'VDA':
                        $cor = '#6393BA';
                        break;
                    case 'NVH':
                        $cor = '#46ABFB';
                        break;
                    case 'Obstáculos':
                        $cor = '#5F6E7A';
                        break;
                    case 'Rampa 12% e 20%':
                        $cor = '#A58C65';
                        break;
                    case 'Rampa 40%':
                        $cor = '#FAB346';
                        break;
                    case 'Rampa 60%':
                        $cor = '#A1BFFB';
                        break;
                    case 'Asfalto':
                        $cor = '#FADEA0';
                        break;
                    case 'Pista Completa':
                        $cor = '#636BBA';
                        break;
                }
                echo '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="width: '.$tamanho.'%; background-color: '.$cor.'; display:flex;justify-content:center"><p style="display:flex;justify-content:center; align-items:center">'.$pista['vezes'].'</p></div>';
            }
            else{
                echo '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="display: none; width: 0"></div>';
            }
        }
        if ($totalVezes > 0){
            echo  '<div id="bar_total" class="bar_total">'.$totalVezes.'</div>';
        }
        echo '</div>';
        $n++;
    }
}

function CriarGraficoSolicitanteHoras($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas) {
    $valores = valorAreas($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);
    $listaValorAreas = $valores[0];
    $maiorTempo = $valores[1][1];
    $keys = array_keys($listaValorAreas);
    $n = 0;
    $cor = 'steelblue';
    foreach ($listaValorAreas as $solicitante){
        $totalTempo = 0;
        echo '<div id="bar" name="barra_'.$keys[$n].'" class="barra_solicitante">';
        foreach ($solicitante as $pista){
            $totalTempo += $pista['tempo'];
            if ($maiorTempo == 0){
                $tamanho = 0;
            }
            else{
                $tamanho = ($pista['tempo'] / $maiorTempo) * 100;
            }
            if ($tamanho != 0){
                switch ($pista['pista']){
                    case 'VDA':
                        $cor = '#6393BA';
                        break;
                    case 'NVH':
                        $cor = '#46ABFB';
                        break;
                    case 'Obstáculos':
                        $cor = '#5F6E7A';
                        break;
                    case 'Rampa 12% e 20%':
                        $cor = '#A58C65';
                        break;
                    case 'Rampa 40%':
                        $cor = '#FAB346';
                        break;
                    case 'Rampa 60%':
                        $cor = '#A1BFFB';
                        break;
                    case 'Asfalto':
                        $cor = '#FADEA0';
                        break;
                    case 'Pista Completa':
                        $cor = '#636BBA';
                        break;
                }
                $hora = $pista['tempo'] / 60;
                $minuto = $pista['tempo'] % 60;
                if ($minuto == 0){
                    $minuto = '';
                }
                echo '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="width: '.$tamanho.'%; background-color: '.$cor.'; display:flex;justify-content:center"><p style="display:flex;justify-content:center; align-items:center">'.intval($hora).'h'.$minuto.'</p></div>';
            }
            else{
                echo '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="display: none; width: 0"></div>';
            }
        }
        $tempoHoras = intval($totalTempo / 60);
        $tempoMinutos = $totalTempo % 60;
        if ($tempoMinutos == 0){
            $tempoMinutos = '';
        }
        if ($tempoHoras > 0 || $tempoMinutos > 0){
            echo  '<div id="bar_total" class="bar_total">'.$tempoHoras.'h'.$tempoMinutos.'</div>';
        }
        echo '</div>';
        $n++;
    }
}

function CriarNovoGraficoSolicitante($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas) {
    $valores = valorAreas($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);
    $listaValorAreas = $valores[0];
    $maiorVezes = $valores[1][0];
    $novoGraficoHTML = '';

    $keys = array_keys($listaValorAreas);
    $n = 0;
    $cor = 'steelblue';
    foreach ($listaValorAreas as $solicitante){
        $totalVezes = 0;
        $novoGraficoHTML .= '<div id="bar" name="barra_'.$keys[$n].'" class="barra_solicitante">';
        foreach ($solicitante as $pista){
            $totalVezes += $pista['vezes'];
            if ($maiorVezes == 0){
                $tamanho = 0;
            }
            else{
                $tamanho = ($pista['vezes'] / $maiorVezes) * 100;
            }
            if ($tamanho != 0){
                switch ($pista['pista']){
                    case 'VDA':
                        $cor = '#6393BA';
                        break;
                    case 'NVH':
                        $cor = '#46ABFB';
                        break;
                    case 'Obstáculos':
                        $cor = '#5F6E7A';
                        break;
                    case 'Rampa 12% e 20%':
                        $cor = '#A58C65';
                        break;
                    case 'Rampa 40%':
                        $cor = '#FAB346';
                        break;
                    case 'Rampa 60%':
                        $cor = '#A1BFFB';
                        break;
                    case 'Asfalto':
                        $cor = '#FADEA0';
                        break;
                    case 'Pista Completa':
                        $cor = '#636BBA';
                        break;
                }
                $novoGraficoHTML .= '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="width: '.$tamanho.'%; background-color: '.$cor.';display:flex;justify-content:center"><p style="display:flex;justify-content:center; align-items:center">'.$pista['vezes'].'</div>';
            }
            else{
                $novoGraficoHTML .= '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="display: none; width: 0"></div>';
            }
        }
        if ($totalVezes > 0){
            $novoGraficoHTML .= '<div id="bar_total" class="bar_total">'.$totalVezes.'</div>';
        }
        $novoGraficoHTML .= '</div>';
        $n++;
    }
    return $novoGraficoHTML;
}

function CriarNovoGraficoSolicitanteHoras($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas) {
    $valores = valorAreas($conexao, $listaAreasSolicitantes, $dataInicial, $dataFinal, $listaPistas);
    $listaValorAreas = $valores[0];
    $maiorTempo = $valores[1][1];
    $novoGraficoHTML = '';
    $keys = array_keys($listaValorAreas);
    $n = 0;
    $cor = 'steelblue';
    foreach ($listaValorAreas as $solicitante){
        $totalTempo = 0;
        $novoGraficoHTML .= '<div id="bar" name="barra_'.$keys[$n].'" class="barra_solicitante">';
        foreach ($solicitante as $pista){
            $totalTempo += $pista['tempo'];
            if ($maiorTempo == 0){
                $tamanho = 0;
            }
            else{
                $tamanho = ($pista['tempo'] / $maiorTempo) * 100;
            }
            if ($tamanho != 0){
                switch ($pista['pista']){
                    case 'VDA':
                        $cor = '#6393BA';
                        break;
                    case 'NVH':
                        $cor = '#46ABFB';
                        break;
                    case 'Obstáculos':
                        $cor = '#5F6E7A';
                        break;
                    case 'Rampa 12% e 20%':
                        $cor = '#A58C65';
                        break;
                    case 'Rampa 40%':
                        $cor = '#FAB346';
                        break;
                    case 'Rampa 60%':
                        $cor = '#A1BFFB';
                        break;
                    case 'Asfalto':
                        $cor = '#FADEA0';
                        break;
                    case 'Pista Completa':
                        $cor = '#636BBA';
                        break;
                }
                $hora = $pista['tempo'] / 60;
                $minuto = $pista['tempo'] % 60;
                if ($minuto == 0){
                    $minuto = '';
                }
                $novoGraficoHTML .= '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="width: '.$tamanho.'%; background-color: '.$cor.'; display:flex;justify-content:center"><p style="display:flex;justify-content:center; align-items:center">'.intval($hora).'h'.$minuto.'</p></div>';
            }
            else{
                $novoGraficoHTML .= '<div id="bar_pista" class="bar" name="'.$pista['pista'].'" style="display: none; width: 0"></div>';
            }
        }
        $tempoHoras = intval($totalTempo / 60);
        $tempoMinutos = $totalTempo % 60;
        if ($tempoMinutos == 0){
            $tempoMinutos = '';
        }
        if ($tempoHoras > 0 || $tempoMinutos > 0){
            $novoGraficoHTML .=  '<div id="bar_total" class="bar_total">'.$tempoHoras.'h'.$tempoMinutos.'</div>';
        }
        $novoGraficoHTML .= '</div>';
        $n++;
    }
    return $novoGraficoHTML;
}
