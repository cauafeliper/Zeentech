<?php 
    session_start();
    include_once('../config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: ../index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento | Valeta</title>
    <link rel="stylesheet" href="estilos/agendamento.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<style>
    .li_1 {
    animation: slideIn 0.5s ease-out;
    }

    .li_2 {
        animation: slideIn 0.3s ease-out;
    }

    .li_3 {
        animation: slideIn 0.6s ease-out;
    }

    .li_4 {
        animation: slideIn 0.7s ease-out;
    }
</style>
<body>
<header>
        <div class="logo__header">
            <img src="../imgs/logo-zeentech.png" alt="logo-zeentech">
        </div>
        
        <div class="botoes__header">
            <a href="../tabela-agendamentos.php">
                <div class="alinhamento__header">
                    <img src="../imgs/tabelas.png" alt="icon-teste">
                </div>
                <span class="alinhamento__header sumir__texto">Tabelas</span>
            </a>
        </div>
       
        <a href="../cadastro-login/sair.php" class="botao__sair">
            <img src="../imgs/exit.png" alt="sair">
        </a>
    </header>
    <main>
        <ol>
            <li class="li_1"><div><img src="imgs/engineer.png" alt="valeta"><span class="sumir">Seleciona a Valeta</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
            <li class="pagina_atual li_2"><div><img src="imgs/calendar.png" alt="calendario"><span class="sumir">Selecione o Dia</span></div><img src="imgs/seta-direita.png" alt="seta" class="seta sumir_img"></li>
            <li class="li_3"><div><img src="imgs/clock.png" alt="horario"><span class="sumir">Selecione o(s) Horários</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
            <li class="li_4"><div><img src="imgs/truck.png" alt="veiculo"><span class="sumir">Indique o Veículo</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
        </ol>
        <form method="post" action="codigos_php/dia.php" onsubmit="return validateForm()">
            <input type="text" name="calendario" id="calendario" placeholder="Selecione o Dia Desejado" class="select__valeta">
            <input type="submit" value="Escolher o Horário" class="submit">
        </form>
    </main>
    <script>
        function validateForm() {
        const calendario = document.getElementById("calendario").value;
            if (calendario === "") {
                alert("Selecione um dia antes de prosseguir.");
                return false;
            }
            return true;
        }

        flatpickr("#calendario", {
            minDate: "today"
        });
    </script>
</body>
</html>