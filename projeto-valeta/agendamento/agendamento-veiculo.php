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
    <link rel="stylesheet" href="../estilos/style-form-veic.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar__content">
            <a href="../tabela-agendamentos.php" class="sidebar__imgs" style="margin-top: 40px;">
                <abbr title="Tabela de Agendamentos.">
                    <div>
                        <img src="../imgs/calendario.png" alt="icon-teste">
                    </div>
                    <span>Horários</span>
                </abbr>
            </a>
            <a href="../cadastro-login/sair.php" class="sidebar__sair">
                <button style="position: relative; top: 38%; background-color: transparent; border: none;color: white; font-weight: bold;">
                    Sair
                </button>
            </a>
        </div>
    </div>
    <main>
        <form method="post" action="agendar.php">
            <ol class="formulario__ol">
                <li class="formulario__li__vermelha" style="margin-right: 15px;">1.Seleciona a Valeta</li>
                <li class="formulario__li__vermelha" style="margin-right: 15px;">2.Selecione o Dia</li>
                <li class="formulario__li__vermelha" style="margin-right: 15px;">3.Selecione o(s) Horários</li>
                <li class="formulario__li__vermelha" style="margin-right: 15px;">4.Indique o Veículo</li>
            </ol>
            <select name="veiculo" id="idVeiculo" style="margin-right: 13%;" class="select__veiculo" required>
                <option value="">Veículo</option>
                    <?php 
                        include_once('config.php');

                        $query_veics = "SELECT veic FROM veics";
                        $result_veics = mysqli_query($conexao, $query_veics);

                        while ($row_veic = mysqli_fetch_assoc($result_veics)) {
                            echo '<option value="' . $row_veic['veic'] . '">' . $row_veic['veic'] . '</option>';
                        }
                    ?>
            </select>
            
            <select name="insp" id="idInsp" style="margin-right: 13%;" class="select__insp" required>
                <option value="">Inspetor</option>
                    <?php 
                        include_once('config.php');

                        $query_insp = "SELECT insp FROM insp";
                        $result_insp = mysqli_query($conexao, $query_insp);

                        while ($row_insp = mysqli_fetch_assoc($result_insp)) {
                            echo '<option value="' . $row_insp['insp'] . '">' . $row_insp['insp'] . '</option>';
                        }
                    ?>
            </select>
            
            <div class="div__radio__eng">
                <label for="eng" style="font-size: 0.8em;">Valeta do engenheiro?</label>
                <div class="radio__eng">
                    <input type="radio" name="eng" id="idEng" value="sim" style="margin-right: 5px;" required />
                    <label for="eng" style="margin-right: 15px;">Sim</label>
                    <input type="radio" name="eng" id="idEng" value="nao" checked style="margin-right: 5px;"/>
                    <label for="eng">Não</label>
                </div>
            </div>
            <input type="text" name="solic" id="idSolic" value="<?php echo $_SESSION['re'];?>" style="display: none;" readonly>
            
            <input type="submit" value="Agendar" class="submit">
        </form>
    </main>
</body>
</html>