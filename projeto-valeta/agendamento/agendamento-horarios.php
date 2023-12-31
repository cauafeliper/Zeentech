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
    <link rel="stylesheet" href="../estilos/style-form-hora.css">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        Swal.fire({
                icon: 'warning',
                title: 'ATENCÃO!',
                html: 'Ao selecionar um horário, você tem os próximos 30 minutos para utilizar aquela valeta!<br>Por exemplo, ao selecionar o horário das 08:00 você tem até as 08:29 para retirar o caminhão da valeta!',
            })
    </script>
    <div class="sidebar">
        <div class="sidebar__content">
            <a href="../tabela-agendamentos.php" class="sidebar__imgs">
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
        <form method="post" action="horarios.php">
            <ol class="formulario__ol">
                <li class="formulario__li__vermelha" style="margin-right: 15px;">1.Seleciona a Valeta</li>
                <li class="formulario__li__vermelha" style="margin-right: 15px;">2.Selecione o Dia</li>
                <li class="formulario__li__vermelha" style="margin-right: 15px;">3.Selecione o(s) Horários</li>
                <li class="formulario__li__cinza" style="margin-right: 15px;">4.Indique o Veículo</li>
            </ol>
            <span id="horarios" class="span__horarios">
                <div class="div__select__horario">
                    <select name="horario1" id="idHorario1" class="select__horario" required>
                        <option value="">Selecione um Horário</option>
                        <?php 
                            $horarios = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];

                            foreach ($horarios as $horario) {
                                echo "<option value=" . $horario . ">" . $horario . "</option>";
                            }
                        ?>
                    </select>
                </div>
            </span>
            <input type="button" value="Estender horário" onclick="adicionarHorario()" class="add__horario"> 
            <button id="popAdd" type="button" class="botaozin"><img src="https://icons.iconarchive.com/icons/bootstrap/bootstrap/16/Bootstrap-exclamation-circle-icon.png" width="20" height="20"></button>
            <script>
                const popAdd = document.getElementById('popAdd');
                popAdd.addEventListener('click', () => {
                    Swal.fire({
                        icon: 'info',
                        html: 'Selecione o botão ao lado até o horário que você deseja utilizar a valeta.<br>Lembre-se, você pode utilizar a valeta até 29 minutos depois do último horário selecionado.<br>Por exemplo, se o último horário selecionado for 15:00 você tem até ás 15:29 para retirar o caminhão da valeta!'
                        })
                 });
            </script>
            <input type="button" value="Reduzir horário" onclick="removerHorario()" class="rmv__horario">
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
            <input type="submit" value="Indicar o Veículo" class="submit">
        </form>
    </main>
</body>
</html>