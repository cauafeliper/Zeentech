<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Login</title>
    <link rel="stylesheet" href="../estilos/style.css">
    <link rel="stylesheet" href="../estilos/style2.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
</head>
<?php 
    session_start();
    unset($_SESSION['re']);
    unset($_SESSION['senha']);
?>
<body>
    <main class="main__erro">
        <form action="">
            <section style="margin-bottom: 10px;"><h1 style="color: black;text-align:center; font-size: medium;">Email ou senha estão incorretos! Caso esteja tendo problemas com sua senha, como esquecimento, entre em contato com o suporte.</h1></section>
            
            <section class="section__erro"><h2 style="color: white; text-align:center; font-size: medium;">Contato: crpereira@zeentech.com.br</h2></section>
            <hr>
            <a href="../index.php" class="botoes" style="height: 20%;"><button type="button" class="botoes">Voltar</button></a>
        </form>
    </main>
</body>
</html>