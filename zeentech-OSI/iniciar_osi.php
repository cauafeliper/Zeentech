<?php
include_once('config.php');

if(isset($_GET['id'])) {
    $idOSI = $_GET['id'];

    // Atualizar o status da OSI para 'Aprovada'
    $query_aprovar = "UPDATE novas_osi SET stts = 'Em andamento' WHERE id = $idOSI";
    mysqli_query($conexao, $query_aprovar);

    // Redirecionar de volta para a página de visualização da OSI
    header('Location: visualizar_os.php?id=' . $idOSI);
} 
else {}
?>