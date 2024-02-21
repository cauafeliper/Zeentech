<?php
    if(isset($_POST['submit'])) {
        if(isset($_POST['horario4'])) {
            include_once('../config.php');

            $horario4 = $_POST['horario4'];
            $_SESSION['horario4'] = $horario4;

            $horario3 = $_POST['horario3'];
            $_SESSION['horario3'] = $horario3;

            $horario2 = $_POST['horario2'];
            $_SESSION['horario2'] = $horario2;

            $horario1 = $_POST['horario1'];
            $_SESSION['horario1'] = $horario1;

            $valeta = $_SESSION['valeta'];
            $dia = $_SESSION['dia'];

            $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND (hora = '$horario1' OR hora = '$horario2' OR hora = '$horario3' OR hora = '$horario4')";
            $checkResult = mysqli_query($conexao, $checkQuery);

            if ($checkResult->num_rows > 0) {
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
                echo '
                <script>
                Swal.fire({
                    icon: \'success\',
                    title: \'Sucesso!\',
                    text: \'Horário disponível. Redirecionando para a próxima página.\',
                }).then(function() {
                    window.location.href = \'agendamento-veiculo.php\';
                });
                </script>';
            }
        }
        else {
            if(isset($_POST['horario3'])) {
                include_once('../config.php');

                $horario3 = $_POST['horario3'];
                $_SESSION['horario3'] = $horario3;

                $horario2 = $_POST['horario2'];
                $_SESSION['horario2'] = $horario2;

                $horario1 = $_POST['horario1'];
                $_SESSION['horario1'] = $horario1;

                $valeta = $_SESSION['valeta'];
                $dia = $_SESSION['dia'];

                $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND (hora = '$horario1' OR hora = '$horario2' OR hora = '$horario3')";
                $checkResult = mysqli_query($conexao, $checkQuery);

                if ($checkResult->num_rows > 0) {
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
                    echo '
                    <script>
                    Swal.fire({
                        icon: \'success\',
                        title: \'Sucesso!\',
                        text: \'Horário disponível. Redirecionando para a próxima página.\',
                    }).then(function() {
                        window.location.href = \'agendamento-veiculo.php\';
                    });
                    </script>';
                }
            }
            else {
                if(isset($_POST['horario2'])) {
                    include_once('../config.php');

                    $horario2 = $_POST['horario2'];
                    $_SESSION['horario2'] = $horario2;

                    $horario1 = $_POST['horario1'];
                    $_SESSION['horario1'] = $horario1;

                    $valeta = $_SESSION['valeta'];
                    $dia = $_SESSION['dia'];

                    $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND (hora = '$horario1' OR hora = '$horario2')";
                    $checkResult = mysqli_query($conexao, $checkQuery);

                    if ($checkResult->num_rows > 0) {
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
                        echo '
                            <script>
                            Swal.fire({
                                icon: \'success\',
                                title: \'Sucesso!\',
                                text: \'Horário disponível. Redirecionando para a próxima página.\',
                            }).then(function() {
                                window.location.href = \'agendamento-veiculo.php\';
                            });
                            </script>';
                    }
                }
                else {
                    include_once('../config.php');
                    
                    $horario1 = $_POST['horario1'];
                    $_SESSION['horario1'] = $horario1;
                    
                    $valeta = $_SESSION['valeta'];
                    $dia = $_SESSION['dia'];

                    $checkQuery = "SELECT * FROM $valeta WHERE dia = '$dia' AND (hora = '$horario1')";
                    $checkResult = mysqli_query($conexao, $checkQuery);

                    if ($checkResult->num_rows > 0) {
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
                        echo '
                            <script>
                            Swal.fire({
                                icon: \'success\',
                                title: \'Sucesso!\',
                                text: \'Horário disponível. Redirecionando para a próxima página.\',
                            }).then(function() {
                                window.location.href = \'agendamento-veiculo.php\';
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