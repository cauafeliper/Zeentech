<?php 
    $valeta = $_POST['valeta'];

    session_start();
    $_SESSION['valeta'] = $valeta;

    header('Location: ../agendamento-dia.php');
?>