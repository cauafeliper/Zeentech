<?php
    include_once('../config/config.php');
    session_start();

    $expire_time = $_SESSION['expire_time'];

    // Verifica se a sessão existe e se o tempo de expiração foi atingido
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $expire_time)) {
        // Sessão expirada, destrói a sessão e redireciona para a página de login
        session_unset();
        session_destroy();
        echo '<script>window.location.href = \'../index.php\';</script>';
        exit();
    }

    // Atualiza o tempo da última atividade
    $_SESSION['last_activity'] = time();

include '../grafico/functions.php'; 

date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/style-gestor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>
<body>
    <?php 
        if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
            echo '<script>window.location.href = \'../index.php\';</script>';
            exit();
        }
    ?>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <img src="../imgs/LogoCertificationTeam.png" alt="logo-certification-team" style="height: 95%;">
        <ul>
            <?php 

                $email = $_SESSION['email'];

                $query = "SELECT COUNT(*) as count FROM lista_adm WHERE email = ?";
                $stmt = mysqli_prepare($conexao, $query);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);
                $linha = mysqli_fetch_assoc($resultado);
                $admTrue = ($linha['count'] > 0);

                $query = "SELECT COUNT(*) as count FROM gestor WHERE email = ?";
                $stmt = mysqli_prepare($conexao, $query);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);
                $linha = mysqli_fetch_assoc($resultado);
                $gestorTrue = ($linha['count'] > 0);

                if ($admTrue || $gestorTrue) {
                    echo '<li><a href="gestor.php">Gestão</a></li>';
                    echo '<li><a href="../grafico/grafico.php">Gráfico</a></li>';
                }
            ?>
            <li><a href="tabela-agendamentos.php">Início</a></li>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>

    <main>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="filtros">
            <div class="titulo">
                <h2><img src="../assets/filter.png" width="24" height="24" style="position: relative; top: 2px;">Filtros de Buscas</h2>
            </div>
            <div class="dia">
                <div class="label_dia">
                    <label for="dia">Dia:</label>
                </div>
                <div class="input_dia">
                <input type="date" name="dia" id="dia" value="<?php echo htmlspecialchars($dia); ?>">
                    <script>
                        flatpickr("#dia", {
                            dateFormat: "Y-m-d"
                        });
                    </script>
                </div>
            </div>
            <div class="objetivo">
                <div class="label_objetivo">
                    <label for="objetivo">Objetivo:</label>
                </div>
                <div class="input_objetivo">
                    <select name="objetivo" id="select_objetivo">
                        <option value="">Selecione o Objetivo</option>
                        <?php
                            $query_objtv = "SELECT DISTINCT objtv FROM objtv_teste";
                            $result_objtv = mysqli_query($conexao, $query_objtv);
                            while ($row_objtv = mysqli_fetch_assoc($result_objtv)) {
                                $selected = ($objtv == $row_objtv['objtv']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row_objtv['objtv']) . '" ' . $selected . '>' . htmlspecialchars($row_objtv['objtv']) . '</option>';
                            }
                        ?>
                    </select>
                    <script>
                        $(document).ready(function () {
                        $('#select_objetivo').select2();
                        });
                    </script>
                </div>
            </div>
            <div class="area_pista">
                <div class="label_area_pista">
                    <label for="area_pista">Área da Pista</label>
                </div>
                <div class="input_area_pista">
                <select name="area_pista" id="select_area_pista">
                    <option value="">Selecione a área da pista</option>
                    <?php
                        $query_area = "SELECT DISTINCT area FROM area_pista";
                        $result_area = mysqli_query($conexao, $query_area);
                        while ($row_area = mysqli_fetch_assoc($result_area)) {
                            $selected = ($area == $row_area['area']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row_area['area']) . '" ' . $selected . '>' . htmlspecialchars($row_area['area']) . '</option>';
                        }
                    ?>
                </select>
                <script>
                    $(document).ready(function () {
                        $('#select_area_pista').select2({
                            tags: true,
                            tokenSeparators: [',', ' ']
                        });
                    });
                </script>
                </div>
            </div>
            <div class="exclsv">
                <div class="label_exclsv">
                    <label for="exclsv">Uso Exclusivo?</label>
                </div>
                <div class="input_exclsv">
                    <label for="sim">Sim</label>
                    <input type="radio" name="exclsv" id="exclsv" value="Sim">
                    <label for="nao">Não</label>
                    <input type="radio" name="exclsv" id="exclsv" value="Não">
                </div>
            </div>
            <div class="status">
                <div class="label_status">
                    <label for="status">Status:</label>
                </div>
                <div class="input_status">
                    <select name="status" id="select_status">
                        <option value="">-</option>
                        <option value="Pendente">Pendente</option>
                        <option value="Aprovado">Aprovado</option>
                    </select>
                    <script>
                        $(document).ready(function () {
                        $('#select_status').select2();
                        });
                    </script>
                </div>
            </div>
            <div class="submit">
                <hr>
                <input type="submit" value="Filtrar">
            </div>
        </form>
        <div class="tabela">
        <div class="caption"><img src="../assets/table-list.png" width="22" height="22" style="position: relative; top: 3px; margin-right: 5px;">Seus Agendamentos</div>
            <table>
                <tr>
                    <th style="display: none;">ID</th>
                    <th>Dia</th>
                    <th>Hora de Início</th>
                    <th>Hora do Fim</th>
                    <th>Área da Pista</th>
                    <th>Objetivo</th>
                    <th>Solicitante</th>
                    <th>Telefone</th>
                    <th>Empresa Solic.</th>
                    <th>Área do Solic.</th>
                    <th>Uso Exclusivo?</th>
                    <th>Observação</th>
                    <th>Status</th>
                    <th>Cancelar</th>
                </tr>
                <?php 
                    $registros_por_pagina = 10;
                    $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                    
                    $dia = isset($_GET['dia']) ? $_GET['dia'] : '';
                    $objetivo = isset($_GET['objetivo']) ? $_GET['objetivo'] : '';
                    $solicitante = isset($_GET['solicitante']) ? $_GET['solicitante'] : '';
                    $area_pista = isset($_GET['area_pista']) ? $_GET['area_pista'] : '';
                    $exclsv = isset($_GET['exclsv']) ? $_GET['exclsv'] : '';
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    
                    $query_count = "SELECT COUNT(*) as total FROM agendamentos WHERE
                                    (dia LIKE '%$dia%')
                                    AND (objtv LIKE '%$objetivo%')
                                    AND (solicitante = '$_SESSION[nome]')
                                    AND (area_pista LIKE '%$area_pista%')
                                    AND (exclsv LIKE '%$exclsv%')
                                    AND (status LIKE '%$status%')";
                    $result_count = mysqli_query($conexao, $query_count);
                    $total_registros = mysqli_fetch_assoc($result_count)['total'];
                    
                    $total_paginas = ceil($total_registros / $registros_por_pagina);
                    
                    $offset = ($pagina_atual - 1) * $registros_por_pagina;
                    
                    $query_paginacao = "SELECT * FROM agendamentos 
                                        WHERE (dia LIKE '%$dia%')
                                        AND (objtv LIKE '%$objetivo%')
                                        AND (solicitante = '$_SESSION[nome]')
                                        AND (area_pista LIKE '%$area_pista%')
                                        AND (exclsv LIKE '%$exclsv%')
                                        AND (status LIKE '%$status%')
                                        AND (status != 'Reprovado')
                                        ORDER BY 
                                        dia DESC,
                                        hora_inicio DESC,
                                        CASE 
                                            WHEN status = 'Pendente' THEN 1
                                            WHEN status = 'Aprovado' THEN 2
                                        END
                                        LIMIT $registros_por_pagina OFFSET $offset";
                    $result_paginacao = mysqli_query($conexao, $query_paginacao);

                    $horaAtual = date('H:i');
                    $hoje = date('Y-m-d');

                    if ($result_count->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($result_paginacao)) { ?>
                        <tr>
                            <td style="display: none;"><?php echo $row['id']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['dia'])); ?></td>
                            <td><?php echo $row['hora_inicio']; ?></td>
                            <td><?php echo $row['hora_fim']; ?></td>
                            <td id="td_tippy" value="<?php echo $row['area_pista']; ?>"><?php echo $row['area_pista']; ?></td>
                            <td id="td_tippy" value="<?php echo $row['objtv']; ?>"><?php echo $row['objtv']; ?></td>
                            <td id="td_tippy" value="<?php echo $row['solicitante']; ?>"><?php echo $row['solicitante']; ?></td>
                            <td id="td_tippy" value="<?php echo $row['numero_solicitante'];?>"><?php echo $row['numero_solicitante'];?></td>
                            <td id="td_tippy" value="<?php echo $row['empresa_solicitante'];?>"><?php echo $row['empresa_solicitante'];?></td>
                            <td id="td_tippy" value="<?php echo $row['area_solicitante'];?>"><?php echo $row['area_solicitante']; ?></td>
                            <td><?php echo $row['exclsv'];?></td>
                            <td id="td_tippy" value="<?php echo $row['obs'];?>"><?php echo $row['obs'];?></td>
                            <td <?php if($row['status'] === 'Aprovado'){echo 'style="background-color: #A6C48A;"';} elseif($row['status'] === 'Pendente'){echo 'style="background-color: #FFD275;"';} else { echo 'style="background-color: #E5625E;"';}?>><?php echo $row['status']; ?></td>
                            <td>
                                <?php if (($row['dia'] > "$hoje") OR ($row['dia'] == "$hoje" AND $row['hora_inicio'] > "$horaAtual")) { ?>
                                    <input type="hidden" name="solicitante" value="<?php echo $row['solicitante']; ?>">
                                    <a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>"><input type="button" value="✖︎" class="cancelar"></a>
                                <?php } else { ?>
                                    <p>Expirado</p>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } } 
                        else {
                            echo
                            '<tr>
                                <td colspan="10">Ainda não existem agendamentos sob essas condições!</td>
                            </tr>';
                        }
                    ?>
            </table>
        </div>
        <div class="paginacao">
            <p style="display: inline;">Páginas:</p>
            <?php
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo '<a href="?pagina=' . $i;
                if ($dia !== '') echo '&dia=' . $dia;
                if ($objetivo !== '') echo '&objetivo=' . $objetivo;
                if ($solicitante !== '') echo '&solicitante=' . $solicitante;
                if ($area_pista !== '') echo '&area_pista=' . $area_pista;
                if ($exclsv !== '') echo '&exclsv=' . $exclsv;
                if ($status !== '') echo '&status=' . $status;
                echo '"';

                if ($i == $pagina_atual) {
                    echo ' style="font-weight: bolder; border: 2.5px solid rgb(59, 59, 59);"';
                }
                echo ">$i</a>";
            }
            ?>
        </div>
    </main>

    <footer>
        <div>
            <span style="font-size: 16px">Desenvolvido por: <img src="../imgs/ZeentechIDT.png" alt="logo-zeentech" style="margin-left: 10px;"></span>
        </div>
        <div class="copyright">
            <span style="font-size: 14px">Copyright © 2024 de Zeentech, todos os direitos reservados.</span>
        </div>
    </footer>

    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <!-- Include Tippy.js CSS (you can customize the theme) -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js/dist/tippy.css" />
    <!-- Include Tippy.js script -->
    <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy-bundle.umd.js"></script>

    <script>

        // Seleciona todas as <td> com o atributo 'value'
        const tds = document.querySelectorAll('#td_tippy');

        // Itera sobre as <td> encontradas
        tds.forEach(function(td_tippy) {
            // Obtém o valor do atributo 'value'
            const value = td_tippy.getAttribute('value');

            // Cria a instância do Tippy.js
            tippy(td_tippy, {
                content: value,
                arrow: true,
                placement: 'top',
                theme: 'custom-theme',
                // Adiciona a propriedade CSS para quebrar palavras
                appendTo: () => document.body,
                allowHTML: true,
                content: `<div style="word-wrap: break-word;">${value}</div>`,
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            var botoesCancelar = document.querySelectorAll(".cancelar");

            botoesCancelar.forEach(function (botao) {
                botao.addEventListener("click", function (event) {
                    event.preventDefault();
                    var linha = this.closest("tr");

                    var id = linha.cells[0].innerText;
                    var data = linha.cells[1].innerText;
                    var horaInicio = linha.cells[2].innerText;
                    var horaFim = linha.cells[3].innerText;
                    var area_pista = linha.cells[4].innerText;
                    var objetivo = linha.cells[5].innerText;
                    var solicitante = linha.cells[6].innerText;
                    var numero_solicitante = linha.cells[7].innerText;
                    var empresa_solicitante = linha.cells[8].innerText;
                    var area_solicitante = linha.cells[9].innerText;
                    var exclsv = linha.cells[10].innerText;
                    var obs = linha.cells[11].innerText;
                    var status = linha.cells[12].innerText;

                    var horario = horaInicio + " - " + horaFim;

                    Swal.fire({
                        title: "Confirmação",
                        html: `<div style='text-align: start; padding: 0 2rem; line-height: 1.5rem'>
                            <h3 style="text-align: center;">Você tem certeza de que deseja CANCELAR o seguinte agendamento?</h3><br>
                            Id: `+id+`<br>
                            Área da Pista: `+area_pista+`<br>
                            Dia: `+data+`<br>
                            Horário: `+horario+`<br>
                            Objetivo: `+objetivo+`<br>
                            Solicitante: `+solicitante+`<br>
                            Numero do solicitante: `+numero_solicitante+`<br>
                            Empresa do solicitante: `+empresa_solicitante+`<br>
                            Área Solicitante: `+area_solicitante+`<br>
                            É exclusivo? `+exclsv+`<br>
                            Observação: `+obs+`<br>
                            Status: `+status+`<br>
                        </div>`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sim, Cancelar",
                        cancelButtonText: "Voltar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            disablePage();
                            window.location.href = "aprovar-reprovar/cancelar.php?id=" + id;
                        }
                    });
                });
            });
        });

        function disablePage() {
            // Desativa todos os elementos de entrada da página
            document.querySelectorAll('input, select, button').forEach(function(element) {
                element.disabled = true;
            });

            // Adicione um overlay para indicar que a página está em estado de "loading"
            var overlay = document.createElement('div');
            overlay.classList.add('loading-overlay'); // Adiciona a classe para identificação posterior
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            overlay.style.zIndex = '9999';
            overlay.innerHTML = '<div style="width:100%; height:100%; display:flex; justify-content:center; align-items:center; text-align: center; color:white;"><h1>Carregando...</h1></div>';
            document.body.appendChild(overlay);
        }
    </script>