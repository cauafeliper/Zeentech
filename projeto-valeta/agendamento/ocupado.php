<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocupado</title>
    <link rel="stylesheet" href="../estilos/style.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
</head>
<body>
    <main class="main__erro">
        <form action="">
            <section class="section__erro" style="margin-bottom: 10px;"><h1 style="color: white; text-align:center; font-size: medium;">Algum dos horários que você selecionou está ocupado, verifique na tabela principal quais horários estão disponíveis!</h1></section>
            <hr>
            <a href="agendamento-horarios.php" class="botoes"><button type="button" class="botoes">Voltar</button></a>
        </form>
    </main>
</body>
</html>