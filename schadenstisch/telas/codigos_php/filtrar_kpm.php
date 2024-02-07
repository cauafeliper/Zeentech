<?php
// Inclua o arquivo de configuração do banco de dados
include_once('../../config/config.php');

// Recupere o programa selecionado da URL
$programa = isset($_GET['programa']) ? $_GET['programa'] : '';

// Construa a consulta SQL com base no programa selecionado
$query = "SELECT * FROM kpm";
if (!empty($programa)) {
    $query .= " WHERE programa = '" . mysqli_real_escape_string($conexao, $programa) . "'";
}

$result = $conexao->query($query);

if (!$result) {
    die('Erro na execução da consulta: ' . mysqli_error($conexao));
}

$output = '<tr>';
$output .= '<th style="display: none;">Item</th>';
$output .= '<th>Programa</th>';
$output .= '<th>Nº do Problema</th>';
$output .= '<th>Ranking</th>';
$output .= '<th>Resumo</th>';
$output .= '<th>Veículo</th>';
$output .= '<th>Status KPM</th>';
$output .= '<th>Status Reunião</th>';
$output .= '<th>FG</th>';
$output .= '<th>Dias em Aberto</th>';
$output .= '<th>Due Date</th>';
$output .= '</tr>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '<tr>';
        $output .= '<td style="display: none;">' . $row['item'] . '</td>';
        $output .= '<td>' . $row['programa'] . '</td>';
        $output .= '<td>' . $row['n_problem'] . '</td>';
        $output .= '<td>' . $row['rank'] . '</td>';
        $output .= '<td>' . $row['resumo'] . '</td>';
        $output .= '<td>' . $row['veiculo'] . '</td>';
        $output .= '<td>' . $row['status_kpm'] . '</td>';
        $output .= '<td>' . $row['status_reuniao'] . '</td>';
        $output .= '<td>' . $row['fg'] . '</td>';
        $output .= '<td>' . $row['dias_aberto'] . '</td>';
        $output .= '<td>' . date('d/m/Y', strtotime($row['due_date'])) . '</td>';
        $output .= '</tr>';
    }
} else {
    $output .= '<tr><td colspan="10">Nenhum resultado encontrado.</td></tr>';
}

echo $output;
mysqli_close($conexao);
?>
