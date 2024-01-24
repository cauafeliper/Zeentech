<?php 
    session_start();
    include_once('../config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: ../index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento | Veículo</title>
    <link rel="stylesheet" href="estilos/agendamento.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .li_1 {
    animation: slideIn 0.5s ease-out;
    }

    .li_2 {
        animation: slideIn 0.6s ease-out;
    }

    .li_3 {
        animation: slideIn 0.7s ease-out;
    }

    .li_4 {
        animation: slideIn 0.3s ease-out;
    }

    form select {
        margin: 10px;
    }
</style>
<body>
<header>
        <div class="logo__header">
            <img src="../imgs/logo-zeentech.png" alt="logo-zeentech">
        </div>
        
        <div class="botoes__header">
            <a href="../tabela-agendamentos.php">
                <div class="alinhamento__header">
                    <img src="../imgs/tabelas.png" alt="icon-teste">
                </div>
                <span class="alinhamento__header sumir__texto">Tabelas</span>
            </a>
        </div>
        <a href="../cadastro-login/sair.php" class="botao__sair">
            <img src="../imgs/exit.png" alt="sair">
        </a>
    </header>
    <main> 
        <ol>
            <li class="li_1"><div><img src="imgs/engineer.png" alt="valeta"><span class="sumir">Seleciona a Valeta</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
            <li class="li_2"><div><img src="imgs/calendar.png" alt="calendario"><span class="sumir">Selecione o Dia</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
            <li class="li_3"><div><img src="imgs/clock.png" alt="horario"><span class="sumir">Selecione o(s) Horários</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
            <li class="pagina_atual li_4"><div><img src="imgs/truck.png" alt="veiculo"><span class="sumir">Indique o Veículo</span></div><img src="imgs/seta-direita.png" alt="seta" class="seta sumir_img"></li>
        </ol>
        <form method="post" action="<?= $_SERVER["PHP_SELF"]; ?>">
            <select name="veiculo" id="idVeiculo" required>
                <option value="">Veículo</option>
                    <?php 
                        include_once('../config.php');

                        $query_veics = "SELECT veic FROM veics";
                        $result_veics = mysqli_query($conexao, $query_veics);

                        while ($row_veic = mysqli_fetch_assoc($result_veics)) {
                            echo '<option value="' . $row_veic['veic'] . '">' . $row_veic['veic'] . '</option>';
                        }
                    ?>
            </select>
            
            <select name="insp" id="idInsp" required>
                <option value="">Inspetor</option>
                    <?php 
                        include_once('../config.php');

                        $query_insp = "SELECT insp FROM insp";
                        $result_insp = mysqli_query($conexao, $query_insp);

                        while ($row_insp = mysqli_fetch_assoc($result_insp)) {
                            echo '<option value="' . $row_insp['insp'] . '">' . $row_insp['insp'] . '</option>';
                        }
                    ?>
            </select>
            
            <div class="div__radio__eng">
                <label for="eng">Valeta do engenheiro?</label>
                <div class="radio__eng">
                    <input type="radio" name="eng" id="idEng" value="sim" style="margin-right: 5px;" required />
                    <label for="eng" style="margin-right: 15px;">Sim</label>
                    <input type="radio" name="eng" id="idEng" value="nao" checked style="margin-right: 5px;"/>
                    <label for="eng">Não</label>
                </div>
            </div>
            <input type="text" name="solic" id="idSolic" value="<?php echo $_SESSION['re'];?>" style="display: none;" readonly>
            
            <input type="submit" name="submit" value="Agendar" class="submit">
        </form>
    </main>
    <?php include_once('codigos_php/agendar.php'); ?>
</body>
</html>