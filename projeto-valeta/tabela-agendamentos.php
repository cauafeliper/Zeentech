<?php 
    session_start();
    include_once('config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela | Valeta</title>
    <link rel="stylesheet" href="estilos/style-tabela-prin.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <?php
        require 'vendor/autoload.php';

        use Carbon\Carbon;
        use Carbon\CarbonInterface;
        
        $hoje = Carbon::now();

        $dataEnvio = $hoje->format('Y-m-d');
    ?>

    <header>
        <div class="logo__header">
            <img src="imgs/logo-zeentech.png" alt="logo-zeentech">
        </div>
        
        <div class="botoes__header">
            <?php
                $re = $_SESSION['re'];
                $query = "SELECT COUNT(*) as count FROM re_adm WHERE re = '$re'";
                $resultado = mysqli_query($conexao, $query);
                $linha = mysqli_fetch_assoc($resultado);
                $admTrue = ($linha['count'] > 0);
                if ($admTrue) {
                    echo '<a href="tabela-pv.php">
                            <div class="alinhamento__header">
                                <img src="imgs/ADM.png" alt="ADM">
                            </div>
                            <span class="alinhamento__header sumir__texto">ADM</span>
                        </a>';
                }
            ?>
            <a href="agendamento/agendamento-valeta.php">
                <div class="alinhamento__header">
                    <img src="imgs/calend.png" alt="icon-teste">
                </div>
                <span class="alinhamento__header sumir__texto">Agendar</span>
            </a>
            <a href="agendamentos-usuario.php">
                <div class="alinhamento__header">
                    <img src="imgs/tabela-pv.png" alt="tabela-pv">
                </div>
                <span class="alinhamento__header sumir__texto">Usuário</span>
            </a>
        </div>
       
        <a href="cadastro-login/sair.php" class="botao__sair">
            <img src="imgs/exit.png" alt="sair">
        </a>
    </header>
    <main>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="date" id="data" name="data" placeholder="Indique a data" class="input__data">
            <div><input type="image" src="imgs/lupa.png" alt="enviar"></div>
        </form>
        <script>
            flatpickr("#data", {
                dateFormat: "Y-m-d",
                minDate: "today"
            });
        </script>
        <div class="valetas">
            <?php
                function gerarValeta($titulo, $idTabela, $codigoValeta, $conexao) {
                    echo '<div alt="valeta' . $titulo . '" class="valeta">';
                    echo '<div class="valeta__titulo">' . $titulo . '</div>';
                    echo '<hr>';
                    echo '<table id="' . $idTabela . '">';
                    echo '<tr>';
                    echo '<th>Horário</th>';
                    echo '<th>Estado</th>';
                    echo '<th>Veículo</th>';
                    echo '</tr>';
                    include_once('tabelas/codigos_php/codigos_valetas/' . $codigoValeta . '.php');
                    echo '</table>';
                    echo '</div>';
                }
                gerarValeta('Valeta A', 'tabelaValetaA', 'valeta_A', $conexao);
                gerarValeta('Valeta B', 'tabelaValetaB', 'valeta_B', $conexao);
                gerarValeta('Valeta C', 'tabelaValetaC', 'valeta_C', $conexao);
                gerarValeta('Valeta VW', 'tabelaValetaVW', 'valeta_VW', $conexao);
            ?>
        </div>
    </main>
</body>
</html>