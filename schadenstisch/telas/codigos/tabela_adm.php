<?php include_once('../../config/config.php'); session_start();?>
<?php
function criarFiltroSelect($conexao, $nomeCampo, $coluna, $label, $onchangeFunction) {
    $html = '<th>';
    $html .= '<select name="select_' . $nomeCampo . '" id="select_' . $nomeCampo . '" onchange="' . $onchangeFunction . '(this.value)">';
    $html .= '<option value="">' . $label . '</option>';
    $html .= '<option value="Todos">Todos</option>';
    
    $query = "SELECT DISTINCT `$coluna` FROM kpm";
    $result = $conexao->query($query);
    while ($row = $result->fetch_assoc()) {
        $html .= '<option value="' . $row[$coluna] . '">' . $row[$coluna] . '</option>';
    }
    
    $html .= '</select>';
    $html .= '</th>';
    
    return $html;
}

// Exemplo de uso:
echo '<tr>';
echo '<th style="display: none;">Item</th>';
echo '<th>Programa</th>';
echo criarFiltroSelect($conexao, 'n_problem', 'n_problem', 'Nº do Problema &#5167;', 'armazenar_n_problem');
echo criarFiltroSelect($conexao, 'rank', 'rank', 'Ranking', 'armazenar_rank');
echo criarFiltroSelect($conexao, 'data_fecha', 'data_fecha', 'Data de Fechamento', 'armazenar_data_fecha');
echo '<th>Due Date</th>';
echo criarFiltroSelect($conexao, 'resumo', 'resumo', 'Resumo', 'armazenar_resumo');
echo criarFiltroSelect($conexao, 'veiculo', 'veiculo', 'Veículo', 'armazenar_veiculo');
echo criarFiltroSelect($conexao, 'status_kpm', 'status_kpm', 'Status KPM', 'armazenar_status_kpm');
echo '<th>Status Reunião</th>';
echo criarFiltroSelect($conexao, 'fg', 'fg', 'FG', 'armazenar_fg');
echo criarFiltroSelect($conexao, 'dias_aberto', 'dias_aberto', 'Dias em Aberto', 'armazenar_dias_aberto');
echo '<th>Data de Ins.</th>';
echo criarFiltroSelect($conexao, 'highlight', 'highlight', 'Highlight', 'armazenar_highlight');
echo '<th>Data(ER)</th>';
echo criarFiltroSelect($conexao, 'cw_er', 'cw(er)', 'CW(ER)', 'armazenar_cw_er');
echo criarFiltroSelect($conexao, 'causa', 'causa', 'Causa do Problema', 'armazenar_causa');
echo criarFiltroSelect($conexao, 'modelo', 'modelo', 'Modelo', 'armazenar_modelo');
echo '<th>Data<br>Registrada</th>';
echo criarFiltroSelect($conexao, 'aval_crit', 'aval_crit', 'Avaliação<br>Criticidade', 'armazenar_aval_crit');
echo criarFiltroSelect($conexao, 'aval_crit_2', 'aval_crit_2', 'Avaliação<br>Criticidade2', 'armazenar_aval_crit_2');
echo criarFiltroSelect($conexao, 'teste', 'teste', 'Teste', 'armazenar_teste');
echo criarFiltroSelect($conexao, 'kpm_dias_correntes', 'kpm_dias_correntes', 'KPM Dias<br>Correntes', 'armazenar_kpm_dias_correntes');
echo criarFiltroSelect($conexao, 'pn', 'pn', 'PN', 'armazenar_pn');
echo criarFiltroSelect($conexao, 'reclamante', 'reclamante', 'Reclamante', 'armazenar_reclamante');
echo '<th>Descrição do<br>Problema</th>';
echo '<th>Remarks</th>';
echo '<th>Status<br>Semanal</th>';
echo criarFiltroSelect($conexao, 'status_acao', 'status_acao', 'Status<br>Ação', 'armazenar_status_acao');
echo criarFiltroSelect($conexao, 'resp_acao', 'resp_acao', 'Responsável<br>Ação', 'armazenar_resp_acao');
echo '<th>Data(UA)</th>';
echo '<th>CW(UA)</th>';
echo '<th>Dias(DU)</th>';
echo criarFiltroSelect($conexao, 'status_du', 'status(du)', 'Status(DU)', 'armazenar_status_du');
echo criarFiltroSelect($conexao, 'info_du', 'info(du)', 'Info(DU)', 'armazenar_info_du');
echo criarFiltroSelect($conexao, 'dev_fk', 'dev(fk)', 'Dev(FK)', 'armazenar_dev_fk');
echo criarFiltroSelect($conexao, 'dur_fk', 'dur(fk)', 'Dur(FK)', 'armazenar_dur_fk');
echo '<th>Km Teste</th>';
echo '<th>Data(DCA)</th>';
echo '<th>Dias Est.<br>(DCA)</th>';
echo criarFiltroSelect($conexao, 'teste_trt', 'teste(trt)', 'Teste(TRT)', 'armazenar_teste_trt');
echo '<th>Km Plan.(TRT)</th>';
echo '<th>CW Estimados<br>(TRT)</th>';
echo '<th>Km(DA)</th>';
echo '<th>CW Estimados<br>(DA)</th>';
echo '<th>Km(TP)</th>';
echo '<th>CW Estimados<br>(TP)</th>';
echo '<th>Km(SDE)</th>';
echo '<th>CW Estimados<br>(SDE)</th>';
echo '<th>Porcentagem(SDE)</th>';
echo '<th>Porcentagem(SER)</th>';
echo criarFiltroSelect($conexao, 'status_anali_ser', 'status_anali(ser)', 'Status Análise(SER)', 'armazenar_status_anali_ser');
echo criarFiltroSelect($conexao, 'feedback_ser', 'feedback(ser)', 'Feedback(SER)', 'armazenar_feedback_ser');
echo criarFiltroSelect($conexao, 'resp', 'resp', 'Responsável', 'armazenar_resp');
echo criarFiltroSelect($conexao, 'timing_status', 'timing_status', 'Timing<br>Status', 'armazenar_timing_status');
echo criarFiltroSelect($conexao, 'nxt_frt_ans', 'nxt_frt_ans', 'Next Forecasted<br>Answer', 'armazenar_nxt_frt_ans');
echo '</tr>';
?>

<?php

// Armazena os valores dos filtros em variáveis de sessão
function atribuirFiltroSessao($filtro) {
    if (isset($_GET[$filtro])) {
        $_SESSION[$filtro] = $_GET[$filtro];
    }
}

// Atribuir os valores dos filtros em variáveis de sessão
$filtros = ['filtro_programa', 'filtro_n_problem', 'filtro_rank', 'filtro_data_fecha', 'filtro_resumo', 'filtro_veiculo', 'filtro_status_kpm', 'filtro_status_reuniao', 'filtro_fg', 'filtro_dias_aberto', 'filtro_highlight', 'filtro_cw_er', 'filtro_causa', 'filtro_modelo', 'filtro_aval_crit', 'filtro_aval_crit_2', 'filtro_teste', 'filtro_kpm_dias_correntes', 'filtro_pn', 'filtro_reclamante', 'filtro_status_acao', 'filtro_resp_acao', 'filtro_status_du', 'filtro_info_du', 'filtro_dev_fk', 'filtro_dur_fk', 'filtro_teste_trt', 'filtro_status_anali_ser', 'filtro_feedback_ser', 'filtro_timing_status', 'filtro_nxt_frt_ans'];

foreach ($filtros as $filtro) {
    echo atribuirFiltroSessao($filtro);
}


// Construa a consulta SQL com base nos filtros armazenados em variáveis de sessão
$query = "SELECT * FROM kpm WHERE 1=1";
echo "Consulta SQL: $query <br>";
// Adicione condições de filtro para cada coluna com base nos valores armazenados em variáveis de sessão
function adicionarFiltro(&$query, $filtro, $coluna) {
    if (isset($_SESSION[$filtro]) && $_SESSION[$filtro] != 'Todos') {
        if ($filtro == 'filtro_status_reuniao') {
            if ($_SESSION[$filtro] == 'Abertos') {
                $query .= " AND $coluna <> 5";
            } elseif ($_SESSION[$filtro] == 'Fechados') {
                $query .= " AND $coluna = 5";
            }
        } else {
            $query .= " AND $coluna = '" . $_SESSION[$filtro] . "'";
        }
    }
}

// Construir a consulta SQL com base nos filtros armazenados em variáveis de sessão
$query = "SELECT * FROM kpm WHERE 1=1";
echo "Consulta SQL: $query <br>";
// Adicionar condições de filtro para cada coluna com base nos valores armazenados em variáveis de sessão
$filtros = array('filtro_programa', 'filtro_n_problem', 'filtro_rank', 'filtro_data_fecha', 'filtro_resumo', 'filtro_veiculo', 'filtro_status_kpm', 'filtro_status_reuniao', 'filtro_fg', 'filtro_dias_aberto', 'filtro_highlight', 'filtro_cw_er', 'filtro_causa', 'filtro_modelo', 'filtro_aval_crit', 'filtro_aval_crit_2', 'filtro_teste', 'filtro_kpm_dias_correntes', 'filtro_pn', 'filtro_reclamante', 'filtro_status_acao', 'filtro_resp_acao', 'filtro_status_du', 'filtro_info_du', 'filtro_dev_fk', 'filtro_dur_fk', 'filtro_teste_trt', 'filtro_status_anali_ser', 'filtro_feedback_ser', 'filtro_timing_status', 'filtro_nxt_frt_ans');

$colunas = array('programa', 'n_problem', 'rank', 'data_fecha', 'resumo', 'veiculo', 'status_kpm', 'status_reuniao', 'fg', 'dias_aberto', 'highlight', 'cw(er)', 'causa', 'modelo', 'aval_crit', 'aval_crit_2', 'teste', 'kpm_dias_correntes', 'pn', 'reclamante', 'status_acao', 'resp_acao', 'status(du)', 'info(du)', 'dev(fk)', 'dur(fk)', 'teste(trt)', 'status_anali(ser)', 'feedback(ser)', 'timing_status', 'nxt_frt_ans');

$combinados = array_combine($filtros, $colunas);

foreach ($combinados as $filtro => $coluna) {
    echo adicionarFiltro($query, $filtro, $coluna);
}

// Adicione mais condições de filtro para outras colunas conforme necessário

// Verificar se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Atribuir os valores do POST às variáveis adequadas
    $item = $_POST['item'];
    $coluna = $_POST['coluna'];
    $valor = $_POST['valor'];

    if($coluna === 'data(er)' || $coluna === 'data_registrada' || $coluna === 'data(ua)' || $coluna === 'data(dca)' || $coluna === 'due_date' || $coluna === 'data_fecha') {
        $valor = date('Y-m-d', strtotime(str_replace('/', '-', $valor)));
    }
    elseif ($coluna === 'data_ins') {
        // Converter a data e hora para o formato aceito pelo SQL (Y-m-d H:i:s)
        $data_hora_php = date_create_from_format('d/m/Y - H:i', $valor);
        $valor = date_format($data_hora_php, 'Y-m-d H:i:s');
    }
    
    // Montar a consulta SQL para atualizar a tabela
    $query = "UPDATE kpm SET $coluna = ? WHERE item = ?";
    
    // Preparar a declaração SQL
    $stmt = $conexao->prepare($query);
    
    // Verificar se a preparação da declaração foi bem-sucedida
    if ($stmt) {
        // Vincular os parâmetros e executar a declaração
        $stmt->bind_param("ss", $valor, $item); // Supondo que o ID do item seja uma string; ajuste se necessário
        $stmt->execute();
        
        // Verificar se a atualização foi bem-sucedida
        if ($stmt->affected_rows > 0) {
            // Se a atualização foi bem-sucedida, exibir uma mensagem de sucesso ou fazer qualquer outra coisa que seja apropriada para o seu caso
            echo "Atualização bem-sucedida!";
        } else {
            // Se nenhum registro foi afetado, exibir uma mensagem de erro ou fazer qualquer outra coisa que seja apropriada para o seu caso
            echo "Nenhum registro foi atualizado.";
        }
        
        // Fechar a declaração
        $stmt->close();
    } else {
        // Se a preparação da declaração falhou, exibir uma mensagem de erro ou fazer qualquer outra coisa que seja apropriada para o seu caso
        echo "Erro ao preparar a declaração SQL: " . $conexao->error;
    }
} else {
    // Se os dados não foram enviados via POST, exibir uma mensagem de erro ou fazer qualquer outra coisa que seja apropriada para o seu caso
    echo "Erro: Os dados não foram enviados via POST.";
}
// Função para criar célula da tabela com input editável
function createTableCell($coluna, $valor) {
    return '<td value="' . $valor . '" id="td_tippy" name="'. $coluna .'"><input type="text" name="input_' . $coluna . '" class="input__td editavel" value="' . $valor . '"></td>';
}
// Execute a consulta SQL
$result = $conexao->query($query);
// Exibir os resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>

        <tr>
            <!-- Exibir a coluna "Item" -->
            <td style="display: none;" value="<?php echo $row['item']; ?>"><?php echo $row['item']; ?></td>

            <?php
            // Definir as colunas e valores correspondentes
            $colunas = ['programa', 'n_problem', 'rank', 'data_fecha', 'due_date', 'resumo', 'veiculo', 'status_kpm', 'status_reuniao', 'fg', 'dias_aberto', 'data_ins', 'highlight', 'data(er)', 'cw(er)', 'causa', 'modelo', 'data_registrada', 'aval_crit', 'aval_crit_2', 'teste', 'kpm_dias_correntes', 'pn', 'reclamante', 'desc_prob', 'remarks', 'status_semanal', 'status_acao', 'resp_acao', 'data(ua)', 'cw(ua)', 'dias(du)', 'status(du)', 'info(du)', 'dev(fk)', 'dur(fk)', 'km_teste', 'resp_acao', 'data(dca)', 'dias_est(dca)', 'teste(trt)', 'km_plan(trt)', 'cw_est(trt)', 'km(da)', 'cw_est(da)', 'km(tp)', 'cw_est(tp)', 'km(sde)', 'cw_est(sde)', 'porcen(sde)', 'porcen(ser)', 'status_anali(ser)', 'feedback(ser)', 'resp', 'timing_status', 'nxt_frt_ans'];

            // Obter os valores correspondentes às colunas
            $valores = [$row['programa'], $row['n_problem'], $row['rank'], date('d/m/Y', strtotime($row['data_fecha'])), date('d/m/Y', strtotime($row['due_date'])), $row['resumo'], $row['veiculo'], $row['status_kpm'], $row['status_reuniao'], $row['fg'], $row['dias_aberto'], date('d/m/Y - H:i', strtotime($row['data_ins'])), $row['highlight'], date('d/m/Y', strtotime($row['data(er)'])), $row['cw(er)'], $row['causa'], $row['modelo'], date('d/m/Y', strtotime($row['data_registrada'])), $row['aval_crit'], $row['aval_crit_2'], $row['teste'], $row['kpm_dias_correntes'], $row['pn'], $row['reclamante'], $row['desc_prob'], $row['remarks'], $row['status_semanal'], $row['status_acao'], $row['resp_acao'], date('d/m/Y', strtotime($row['data(ua)'])), $row['cw(ua)'], $row['dias(du)'], $row['status(du)'], $row['info(du)'], $row['dev(fk)'], $row['dur(fk)'], $row['km_teste'], $row['resp_acao'], date('d/m/Y', strtotime($row['data(dca)'])), $row['dias_est(dca)'], $row['teste(trt)'], $row['km_plan(trt)'], $row['cw_est(trt)'], $row['km(da)'], $row['cw_est(da)'], $row['km(tp)'], $row['cw_est(tp)'], $row['km(sde)'], $row['cw_est(sde)'], $row['porcen(sde)'], $row['porcen(ser)'], $row['status_anali(ser)'], $row['feedback(ser)'], $row['resp'], $row['timing_status'], $row['nxt_frt_ans']];

            // Criar as células da tabela para cada coluna e valor
            foreach (array_combine($colunas, $valores) as $coluna => $valor) {
                echo createTableCell($coluna, $valor);
            }
            ?>

        </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="6">Ainda não existem KPM\'s registrados!</td></tr>';
}
?>

<script>
    // Seleciona todas as <td> com o atributo 'value'
    const tds = document.querySelectorAll('#td_tippy');
 
    // Itera sobre as <td> encontradas
    tds.forEach(function(td_tippy) {
        // Obtém o valor do atributo 'value'
        const value = td_tippy.getAttribute('value');

        // Cria a instância do Tippy.js
        tippy(td_tippy, {
            content: value,
            arrow: true,
            placement: 'top',
            theme: 'custom-theme',
            // Adiciona a propriedade CSS para quebrar palavras
            appendTo: () => document.body,
            allowHTML: true,
            content: `<div style="word-wrap: break-word;">${value}</div>`,
        });
    });
</script>