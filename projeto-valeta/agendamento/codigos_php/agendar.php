<?php 
    if(isset($_POST['submit'])) {
        include_once('../config.php');
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
                echo '
                <script>
                Swal.fire({
                        icon: \'warning\',
                        title: \'ATENCÃO!\',
                        html: \'Algum dos horário que você selecionou está ocupado!<br>Por favor, verifique na página principal quais horários<br>estão disponíveis!\',
                    })
                </script>';
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
                echo '<script>
                Swal.fire({
                icon: \'success\',
                title: \'SUCESSO!\',
                html: \'Seu agendamento foi criado com sucesso!\',
                }).then((result) => {
                if (result.isConfirmed) {
                    // Redirecionar para a página desejada
                    window.location.href = \'../tabela-agendamentos.php\';
                }
                });
                </script>';
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
                    echo '
                    <script>
                    Swal.fire({
                            icon: \'warning\',
                            title: \'ATENCÃO!\',
                            html: \'Algum dos horário que você selecionou está ocupado!<br>Por favor, verifique na página principal quais horários<br>estão disponíveis!\',
                        })
                    </script>';
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
                    echo '<script>
                        Swal.fire({
                        icon: \'success\',
                        title: \'SUCESSO!\',
                        html: \'Seu agendamento foi criado com sucesso!\',
                        }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirecionar para a página desejada
                            window.location.href = \'../tabela-agendamentos.php\';
                        }
                        });
                        </script>';
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
                        echo '
                        <script>
                        Swal.fire({
                                icon: \'warning\',
                                title: \'ATENCÃO!\',
                                html: \'Algum dos horário que você selecionou está ocupado!<br>Por favor, verifique na página principal quais horários<br>estão disponíveis!\',
                            })
                        </script>';
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
                        echo '<script>
                        Swal.fire({
                        icon: \'success\',
                        title: \'SUCESSO!\',
                        html: \'Seu agendamento foi criado com sucesso!\',
                        }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirecionar para a página desejada
                            window.location.href = \'../tabela-agendamentos.php\';
                        }
                        });
                        </script>';
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
                        echo '
                        <script>
                        Swal.fire({
                                icon: \'warning\',
                                title: \'ATENCÃO!\',
                                html: \'Algum dos horário que você selecionou está ocupado!<br>Por favor, verifique na página principal quais horários<br>estão disponíveis!\',
                            })
                        </script>';
                    }
                    else {
                        $query1 = "INSERT INTO $valeta (dia, hora, veículo, inspetor, eng, solic) VALUES('$dia', '$horario1', '$veiculo', '$insp', '$eng', '$solic')";
                        $result1 = mysqli_query($conexao, $query1);

                        unset($_SESSION['valeta']);
                        unset($_SESSION['dia']);
                        unset($_SESSION['horario1']);
                        echo '<script>
                        Swal.fire({
                        icon: \'success\',
                        title: \'SUCESSO!\',
                        html: \'Seu agendamento foi criado com sucesso!\',
                        }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirecionar para a página desejada
                            window.location.href = \'../tabela-agendamentos.php\';
                        }
                        });
                        </script>';
                    }
                }
            }
        }
    }
    else {

    }
?>