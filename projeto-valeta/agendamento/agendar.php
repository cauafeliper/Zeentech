<?php 
    include_once('../config.php');
    session_start();
    if(isset($_SESSION['horario4'])) {
        $valeta = $_SESSION['valeta'];
        $dia = $_SESSION['dia'];
        $horario1 = $_SESSION['horario1'];
        $horario2 = $_SESSION['horario2'];
        $horario3 = $_SESSION['horario3'];
        $horario4 = $_SESSION['horario4'];
        $veiculo = $_POST['veiculo'];
        $insp = $_POST['insp'];
        $eng = $_POST['eng'];
        $solic = $_POST['solic'];

        $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND (hora = '$horario1' OR hora = '$horario2' OR hora = '$horario3' OR hora = '$horario4')";
        $checkResult = mysqli_query($conexao, $checkQuery);

        if ($checkResult->num_rows > 0) {
            unset($_SESSION['valeta']);
            unset($_SESSION['dia']);
            unset($_SESSION['horario1']); 
            unset($_SESSION['horario2']); 
            unset($_SESSION['horario3']);
            unset($_SESSION['horario4']);
            header('Location: ocupado.php');
        }
        else {
            $query1 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario1', '$veiculo', '$insp', '$eng', '$solic')";
            $result1 = mysqli_query($conexao, $query1);

            $query2 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario2', '$veiculo', '$insp', '$eng', '$solic')";
            $result2 = mysqli_query($conexao, $query2);

            $query3 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario3', '$veiculo', '$insp', '$eng', '$solic')";
            $result3 = mysqli_query($conexao, $query3);

            $query4 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario4', '$veiculo', '$insp', '$eng', '$solic')";
            $result4 = mysqli_query($conexao, $query4);

            unset($_SESSION['valeta']);
            unset($_SESSION['dia']);
            unset($_SESSION['horario1']); 
            unset($_SESSION['horario2']); 
            unset($_SESSION['horario3']);
            unset($_SESSION['horario4']);
            header('Location: fim.php');
        }
    }
    else {
        if(isset($_SESSION['horario3'])) {
            $valeta = $_SESSION['valeta'];
            $dia = $_SESSION['dia'];
            $horario1 = $_SESSION['horario1'];
            $horario2 = $_SESSION['horario2'];
            $horario3 = $_SESSION['horario3'];
            $veiculo = $_POST['veiculo'];
            $insp = $_POST['insp'];
            $eng = $_POST['eng'];
            $solic = $_POST['solic'];

            $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND (hora = '$horario1' OR hora = '$horario2' OR hora = '$horario3')";
            $checkResult = mysqli_query($conexao, $checkQuery);

            if ($checkResult->num_rows > 0) {
                unset($_SESSION['valeta']);
                unset($_SESSION['dia']);
                unset($_SESSION['horario1']); 
                unset($_SESSION['horario2']); 
                unset($_SESSION['horario3']);
                header('Location: ocupado.php');
            }
            else{
                $query1 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario1', '$veiculo', '$insp', '$eng', '$solic')";
                $result1 = mysqli_query($conexao, $query1);
        
                $query2 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario2', '$veiculo', '$insp', '$eng', '$solic')";
                $result2 = mysqli_query($conexao, $query2);
        
                $query3 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario3', '$veiculo', '$insp', '$eng', '$solic')";
                $result3 = mysqli_query($conexao, $query3);

                unset($_SESSION['valeta']);
                unset($_SESSION['dia']);
                unset($_SESSION['horario1']); 
                unset($_SESSION['horario2']); 
                unset($_SESSION['horario3']);
                header('Location: fim.php');
            }
        }
        else {
            if(isset($_SESSION['horario2'])) {
                $valeta = $_SESSION['valeta'];
                $dia = $_SESSION['dia'];
                $horario1 = $_SESSION['horario1'];
                $horario2 = $_SESSION['horario2'];
                $veiculo = $_POST['veiculo'];
                $insp = $_POST['insp'];
                $eng = $_POST['eng'];
                $solic = $_POST['solic'];

                $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND (hora = '$horario1' OR hora = '$horario2')";
                $checkResult = mysqli_query($conexao, $checkQuery);

                if ($checkResult->num_rows > 0) {
                    unset($_SESSION['valeta']);
                    unset($_SESSION['dia']);
                    unset($_SESSION['horario1']); 
                    unset($_SESSION['horario2']); 
                    header('Location: ocupado.php');
                }
                else {
                    $query1 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario1', '$veiculo', '$insp', '$eng', '$solic')";
                    $result1 = mysqli_query($conexao, $query1);
            
                    $query2 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario2', '$veiculo', '$insp', '$eng', '$solic')";
                    $result2 = mysqli_query($conexao, $query2);

                    unset($_SESSION['valeta']);
                    unset($_SESSION['dia']);
                    unset($_SESSION['horario1']); 
                    unset($_SESSION['horario2']);
                    header('Location: fim.php');
                }
            }
            else {
                $valeta = $_SESSION['valeta'];
                $dia = $_SESSION['dia'];
                $horario1 = $_SESSION['horario1'];
                $veiculo = $_POST['veiculo'];
                $insp = $_POST['insp'];
                $eng = $_POST['eng'];
                $solic = $_POST['solic'];

                $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND hora = '$horario1'";
                $checkResult = mysqli_query($conexao, $checkQuery);

                if ($checkResult->num_rows > 0) {
                    unset($_SESSION['valeta']);
                    unset($_SESSION['dia']);
                    unset($_SESSION['horario1']); 
                    header('Location: ocupado.php');
                }
                else {
                    $query1 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario1', '$veiculo', '$insp', '$eng', '$solic')";
                    $result1 = mysqli_query($conexao, $query1);

                    unset($_SESSION['valeta']);
                    unset($_SESSION['dia']);
                    unset($_SESSION['horario1']);
                    header('Location: fim.php');
                }
            }
        }
    }
?>