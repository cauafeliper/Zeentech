<?php
    include_once('../config/config.php');
    session_start();

    if (!isset($_SESSION['email']) OR !isset($_SESSION['senha'])) {
        echo '<script>window.location.href = "../index.php";</script>';
        exit();
    } else {
        $email = $_SESSION['email'];
        $senha = $_SESSION['senha'];

        // Concatena as variáveis diretamente na string da consulta e escapa-as
        $query = "SELECT COUNT(*) as total FROM logins WHERE email = '" . mysqli_real_escape_string($conexao, $email) . "' AND senha = '" . mysqli_real_escape_string($conexao, $senha) . "'";

        $result = $conexao->query($query);

        if (!$result) {
            die('Erro na execução da consulta: ' . mysqli_error($conexao));
        }

        $row = mysqli_fetch_assoc($result);
        $total = $row['total'];

        if ($total <= 0) {
            session_destroy();
            echo '<script>window.location.href = "../index.php";</script>';
            exit();
        }
        else {}
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="style/style_bi.css">
    <title>Schadenstisch | BI</title>
</head>
<body>
    <header>
        <img src="../imgs/truckBus.png" alt="logo-volks">

        <img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech-idt" class="logo__zeentech">
    </header>
    <main>
        <iframe title="Controle Schadenstisch Gerencial" src="https://app.powerbi.com/reportEmbed?reportId=ab18bd18-e88d-4326-bdcc-e1525de6034c&autoAuth=true&ctid=959ebf68-bcaf-4dec-b44a-6934403eb9f3" frameborder="0"></iframe>
    </main>
    <footer>
        .
    </footer>
</body>
</html>