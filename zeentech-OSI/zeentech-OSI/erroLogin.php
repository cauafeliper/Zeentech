<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Login</title>
    <link rel="stylesheet" href="estilos/style.css">
    <style>
        body {
            background-image: url("imgs/background.jpg");
            background-repeat: no-repeat;
            background-size: cover;
        } 
        
        section {
            background-color: rgb(112, 112, 112);
            border: 2px solid #4C7397;
        } 
    </style>
</head>
<?php 
    session_start();
    unset($_SESSION['re']);
    unset($_SESSION['senha']);
?>
<body>
    <a href="https://www.zeentech.com/" target="_blank" type="external"><img src="imgs/lg-zeentech(titulo).png" alt="logoZeentech" style="position:relative; right:580px; top:50px;"></a>
    
    <a href="https://www.vwco.com.br/" target="_blank" type="external"><img src="imgs/logo-volks.png" alt="logoVolks" style="position:relative; left:590px; top:5px;"></a>
    <main>
    <form action="">
            <section><h1 style="color: white; text-align:center; font-size: medium;">Email ou senha estão incorretos! Caso esteja tendo problemas com sua senha, como esquecimento, entre em contato com o suporte.</h1></section>
            
            <section><h2 style="color: white; text-align:center; font-size: medium;">Contato: crpereira@zeentech.com.br</h2></section>
            <hr>
            <a href="login.php"><button type="button">Voltar</button></a>
        </form>
    </main>
</body>
</html>