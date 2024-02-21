<?php
include_once('../../config/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "UPDATE logins SET status = 'APROVADO' WHERE id = ?";

    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../aprovacao_login.php');
        exit();
    } else {
        header('Location: ../aprovacao_login.php');
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    header('Location: ../aprovacao_login.php');
    exit();
}

mysqli_close($conexao);
?>
