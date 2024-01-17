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