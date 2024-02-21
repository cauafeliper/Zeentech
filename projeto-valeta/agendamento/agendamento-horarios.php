<?php 
    session_start();
    include_once('../config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: ../index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento | Valeta</title>
    <link rel="stylesheet" href="estilos/agendamento.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .li_1 {
    animation: slideIn 0.5s ease-out;
    }

    .li_2 {
        animation: slideIn 0.6s ease-out;
    }

    .li_3 {
        animation: slideIn 0.3s ease-out;
    }

    .li_4 {
        animation: slideIn 0.7s ease-out;
    }

    form select {
        height: 50px;
        width: 220px;

        margin: 5px;
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
            <li class="li_2"><div><img src="imgs/calendar.png" alt="calendario"><span class="sumir">Selecione o Dia</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
            <li class="pagina_atual li_3"><div><img src="imgs/clock.png" alt="horario"><span class="sumir">Selecione o(s) Horários</span></div><img src="imgs/seta-direita.png" alt="seta" class="seta sumir_img"></li>
            <li class="li_4"><div><img src="imgs/truck.png" alt="veiculo"><span class="sumir">Indique o Veículo</span></div><img src="imgs/seta-baixo.png" alt="seta" class="seta sumir_img"></li>
        </ol>
        <form method="post" action="<?= $_SERVER["PHP_SELF"]; ?>">
            <div id="horarios">
                <select name="horario1" id="idHorario1" required>
                    <option value="">Selecione um Horário</option>
                    <?php 
                        $horarios = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];

                        foreach ($horarios as $horario) {
                            echo "<option value=" . $horario . ">" . $horario . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="botoes_horarios">
                <input type="button" value="Estender horário" onclick="adicionarHorario()">
                <button id="popAdd" type="button" class="botaozin"><img src="imgs/attention.png" style="width: 20px; height: 20px;"></button>
                <input type="button" value="Reduzir horário" onclick="removerHorario()">
            </div>
            <input type="submit" name="submit" value="Indicar o Veículo" class="submit">
        </form>
    </main>
    <?php include_once('codigos_php/horarios.php');?>
</body>
</html>



<script>
        const popAdd = document.getElementById('popAdd');
        popAdd.addEventListener('click', () => {
            Swal.fire({
                icon: 'info',
                html: 'Selecione o botão ao lado até o horário que você deseja utilizar a valeta.<br>Lembre-se, você pode utilizar a valeta até 29 minutos depois do último horário selecionado.<br>Por exemplo, se o último horário selecionado for 15:00 você tem até ás 15:29 para retirar o caminhão da valeta!'
                })
            });
    </script>
    <script>
        let numHorarios = 1;

        function adicionarHorario() {
            if (numHorarios < 6) {
                numHorarios++;
                const horarios = document.getElementById("horarios");
                const novoHorario = document.createElement("div");
                novoHorario.classList.add("div__select__horario");

                // Obtém o último horário selecionado
                const ultimoHorarioSelecionado = document.getElementById(`idHorario${numHorarios - 1}`).value;
                // Calcula o próximo horário
                const proximoHorario = calcularProximoHorario(ultimoHorarioSelecionado);

                novoHorario.innerHTML = `<select name="horario${numHorarios}" id="idHorario${numHorarios}" class="select__horario">
                    <option value="">Selecione um Horário</option>
                    <?php 
                        $horarios = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];

                        foreach ($horarios as $horario) {
                            echo "<option value=" . $horario . ">" . $horario . "";
                        }
                    ?>
                </select>`;

                novoHorario.querySelector(`#idHorario${numHorarios}`).value = proximoHorario;

                horarios.appendChild(novoHorario);
            } else {
                alert("Você atingiu o número máximo de horários permitidos (6).");
            }
        }

        function calcularProximoHorario(horarioAnterior) {
            // Converte o horário para minutos para facilitar os cálculos
            const [horasAnterior, minutosAnterior] = horarioAnterior.split(':').map(Number);
            const minutosTotaisAnterior = horasAnterior * 60 + minutosAnterior;

            // Adiciona 30 minutos para o próximo horário
            const minutosProximo = minutosTotaisAnterior + 30;

            // Converte o próximo horário de volta para o formato 'HH:mm'
            const horasProximo = Math.floor(minutosProximo / 60);
            const minutosProximoFormatado = minutosProximo % 60 === 0 ? '00' : minutosProximo % 60;

            return `${horasProximo.toString().padStart(2, '0')}:${minutosProximoFormatado.toString().padStart(2, '0')}`;
        }

        function removerHorario() {
            if (numHorarios > 1) {
                const horarios = document.getElementById("horarios");
                horarios.removeChild(horarios.lastElementChild);
                numHorarios--;
            } else {
                alert("Você precisa manter pelo menos um horário.");
            }
        }
    </script>