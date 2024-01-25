<?php 
    $dia = $_POST['calendario'];

    session_start();
    $_SESSION['dia'] = $dia;

    header('Location: ../agendamento-horarios.php');
?>