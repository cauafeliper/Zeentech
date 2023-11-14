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
</head>
<body>
    <div class="sidebar">
        <div class="sidebar__content">
            <a href="../tabela-agendamentos.php" class="sidebar__imgs" style="margin-top: 40px;">
                <abbr title="Tabela de Agendamentos.">
                    <div>
                        <img src="../imgs/calendario.png" alt="icon-teste">
                    </div>
                    <span>Horários</span>
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
        <form method="post" action="valeta.php">
            <ol class="formulario__ol">
                <li class="formulario__li__vermelha" style="margin-right: 15px;">1.Seleciona a Valeta</li>
                <li class="formulario__li__cinza" style="margin-right: 15px;">2.Selecione o Dia</li>
                <li class="formulario__li__cinza" style="margin-right: 15px;">3.Selecione o(s) Horários</li>
                <li class="formulario__li__cinza" style="margin-right: 15px;">4.Indique o Veículo</li>
            </ol>
            <select name="valeta" id="idValeta" class="select__valeta" required>
                <option value="">Selecione uma Valeta</option>
                <option value="valeta_a">Valeta A</option>
                <option value="valeta_b">Valeta B</option>
                <option value="valeta_c">Valeta C</option>
                <option value="valeta_vw">Valeta VW</option>
            </select>
            <input type="submit" value="Escolher o Dia" class="submit">
        </form>
    </main>
</body>
</html>