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
    <link rel="stylesheet" href="estilos/style-tabela-prin2.css">
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
    <div class="div__header__data">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="date" id="data" name="data" placeholder="Indique a data" class="header__data"style="font-size: larger; font-weight: 500; color: gray;">
            <input type="submit" value="Filtrar" class="header__submit">
        </form>
    </div>
    <script>
        flatpickr("#data", {
            dateFormat: "Y-m-d",
            minDate: "today"
        });
    </script>
</header>
    <div class="sidebar">
        <div class="sidebar__content">
            <?php 
                $re = $_SESSION['re'];

                $query = "SELECT COUNT(*) as count FROM re_adm WHERE re = '$re'";
                $resultado = mysqli_query($conexao, $query);
                $linha = mysqli_fetch_assoc($resultado);
                $admTrue = ($linha['count'] > 0);

                if ($admTrue) {
                    echo '<a href="tabela-pv.php" class="sidebar__imgs"><abbr title="Tela para Administradores.">
                        <div><img src="imgs/tabela-pv.png" alt="tabela-pv"></div><span>ADM</span>
                    </abbr></a>';
                }
            ?>
            <a href="agendamento/agendamento-valeta.php" class="sidebar__imgs" style="margin-top: 40px;">
                <abbr title="Criar novo Agendamento.">
                    <div>
                        <img src="imgs/form.png" alt="icon-teste">
                    </div>
                    <span>Agendar</span>
                </abbr>
            </a>
            <a href="agendamentos-usuario.php" class="sidebar__imgs" style="margin-top: 40px;">
                <abbr title="Seus agendamentos.">
                    <div>
                        <img src="https://icons.iconarchive.com/icons/icons8/ios7/72/Users-Administrator-icon.png" width="72" height="72">
                    </div>
                    <span>Usuário</span>
                </abbr>
            </a>
            <a href="cadastro-login/sair.php" class="sidebar__sair">
                <button style="position: relative; top: 38%; background-color: transparent; border: none;color: white; font-weight: bold;">
                    Sair
                </button>
            </a>
        </div>
    </div>
    <main>
        <div alt="valetaA" class="valeta">
            <div class="valeta__titulo">Valeta A</div>
            <hr>
            <table id="tabelaValetaA">
            <tr>
                <th>Horário</th>
                <th>Estado</th>
                <th>Veículo</th>
            </tr>
            <?php
                include_once('config.php');
                if($_SERVER["REQUEST_METHOD"] === "POST"){
                    if(isset($_POST['data'])){
                        $data = $_POST['data'];

                        $horarios = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];

                        foreach ($horarios as $horario) {
                            $query = "SELECT * FROM valeta_a WHERE hora='$horario' AND dia='$data'";
                            $result = mysqli_query($conexao, $query);

                            echo "<tr>";
                            echo "<td>$horario</td>";

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo "<td>Ocupado</td><td>" . $row["veículo"] . "</td>";
                                } 
                                else {
                                    echo "<td>Livre</td><td>-</td>";
                                }
                            }
                        }
                }
                else {
                    echo "<td>Esperando</td><td>uma</td><td>data</td>";
                }
                echo "</tr>";
            ?>   
            </table>
        </div>
        
        <div alt="valetaB" class="valeta">
            <div class="valeta__titulo">Valeta B</div>
            <hr>
            <table id="tabelaValetaB">
            <tr>
                <th>Horário</th>
                <th>Estado</th>
                <th>Veículo</th>
            </tr>
                <?php
                    include_once('config.php');
                    if($_SERVER["REQUEST_METHOD"] === "POST"){
                        if(isset($_POST['data'])){
                            $data = $_POST['data'];

                            $horarios = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];

                            foreach ($horarios as $horario) {
                                $query = "SELECT * FROM valeta_b WHERE hora='$horario' AND dia='$data'";
                                $result = mysqli_query($conexao, $query);

                                echo "<tr>";
                                echo "<td>$horario</td>";

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        echo "<td>Ocupado</td><td>" . $row["veículo"] . "</td>";
                                    } 
                                    else {
                                        echo "<td>Livre</td><td>-</td>";
                                    }
                                }
                            }
                    }
                    else {
                        echo "<td>Esperando</td><td>uma</td><td>data</td>";
                    }
                    echo "</tr>";
                ?> 
            </table>
        </div>
        
        <div alt="valetaC" class="valeta">
            <div class="valeta__titulo">Valeta C</div>
            <hr>
            <table id="tabelaValetaC">
                <tr>
                    <th>Horário</th>
                    <th>Estado</th>
                    <th>Veículo</th>
                </tr>
                    <?php
                        include_once('config.php');
                        if($_SERVER["REQUEST_METHOD"] === "POST"){
                            if(isset($_POST['data'])){
                                $data = $_POST['data'];

                                $horarios = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];

                                foreach ($horarios as $horario) {
                                    $query = "SELECT * FROM valeta_c WHERE hora='$horario' AND dia='$data'";
                                    $result = mysqli_query($conexao, $query);

                                    echo "<tr>";
                                    echo "<td>$horario</td>";

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            echo "<td>Ocupado</td><td>" . $row["veículo"] . "</td>";
                                        } 
                                        else {
                                            echo "<td>Livre</td><td>-</td>";
                                        }
                                    }
                                }
                        }
                        else {
                            echo "<td>Esperando</td><td>uma</td><td>data</td>";
                        }
                        echo "</tr>";
                    ?> 
            </table>
        </div>
        <div alt="valetaVW" class="valeta valeta_vw">
            <div class="valeta__titulo">Valeta VW</div>
            <hr>
            <table id="tabelaValetaVW">
            <tr>
                    <th>Horário</th>
                    <th>Estado</th>
                    <th>Veículo</th>
                </tr>
                <?php
                    include_once('config.php');
                    if($_SERVER["REQUEST_METHOD"] === "POST"){
                        if(isset($_POST['data'])){
                            $data = $_POST['data'];

                            $horarios = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];

                            foreach ($horarios as $horario) {
                                $query = "SELECT * FROM valeta_vw WHERE hora='$horario' AND dia='$data'";
                                $result = mysqli_query($conexao, $query);

                                echo "<tr>";
                                echo "<td>$horario</td>";

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        echo "<td>Ocupado</td><td>" . $row["veículo"] . "</td>";
                                    } 
                                    else {
                                        echo "<td>Livre</td><td>-</td>";
                                    }
                                }
                            }
                    }
                    else {
                        echo "<td>Esperando</td><td>uma</td><td>data</td>";
                    }
                    echo "</tr>";
                ?> 
            </table>
        </div>
    </main>
</body>
</html>