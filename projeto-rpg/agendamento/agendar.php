<?php 
    $solicitante = $_POST['solicitante'];
    $dia = $_POST['dia'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $area_solicitante = $_POST['area_solicitante'];
    $area_solicitada = $_POST['area'];
    $objetivo = $_POST['objetvo'];
    $veiculo = $_POST['veic'];
    $resp_veiculo = $_POST['resp_veic'];
    $exclsv = $_POST['resposta'];
    $status = $_POST['status'];
    $obs = $_POST['obs'];

    $query = "INSERT INTO $area_solicitada (dia, hora_inicio, hora_fim, objtv, solicitante, area_solicitante, veic, resp_veic, exclsv, obs, status) VALUES('$dia', '$hora_inicio', '$hora_fim', '$objetivo', '$solicitante', '$area_solicitante', '$veiculo', '$resp_veiculo', '$exclsv', '$obs', '$status')";
    $result = mysqli_query($conexao, $query);

    header('Location: sucesso.php');
?>