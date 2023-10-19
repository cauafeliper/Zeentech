<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos</title>
    <link rel="shortcut icon" href="imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="estilos/style-principal.css">
</head>
<body>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="imgs/truckBus.png" alt="logo-truckbus" style="height: 90px;"></a>
        <ul>
            <?php 
            //    include_once('config.php');

            //    $chapa = $_SESSION['chapa'];

            //    $query = "SELECT COUNT(*) as count FROM chapa_adm WHERE chapa = '$chapa'";
            //    $resultado = mysqli_query($conexao, $query);
            //    $linha = mysqli_fetch_assoc($resultado);
            //    $admTrue = ($linha['count'] > 0);

            //    if ($admTrue) {
            //        echo '<li><a href="gestor.php">Gestão</a></li>';
            //    }
            ?>
            <li><a href="gestor.php">Gestão</a></li>
            <li><a href="agendamento.php">Novo Agendamento</a></li>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
</body>
</html>