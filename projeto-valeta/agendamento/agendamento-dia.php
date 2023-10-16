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
    <link rel="stylesheet" href="../estilos/style-form.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar__content">
            <a href="../tabela-agendamentos.php" class="sidebar__imgs" style="margin-top: 40px;">
                <abbr title="Tabela de Agendamentos.">
                    <div>
                        <img src="../imgs/calendario.png" alt="icon-teste">
                    </div>
                </abbr>
            </a>
            <a href="../cadastro-login/sair.php" class="sidebar__sair">
                <button style="position: relative; top: 38%; background-color: transparent; border: none;color: white; font-weight: bold;">
                    Sair
                </button>
            </a>
        </div>
    </div>
    <main>
        <form method="post" action="dia.php" onsubmit="return validateForm()">
            <ol class="formulario__ol">
                <li class="formulario__li__vermelha" style="margin-right: 15px;">1.Seleciona a Valeta</li>
                <li class="formulario__li__vermelha" style="margin-right: 15px;">2.Selecione o Dia</li>
                <li class="formulario__li__cinza" style="margin-right: 15px;">3.Selecione o(s) Horários</li>
                <li class="formulario__li__cinza" style="margin-right: 15px;">4.Indique o Veículo</li>
            </ol>
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