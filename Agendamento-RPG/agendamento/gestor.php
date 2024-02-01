<?php
    include_once('../config/config.php');
    session_start();
    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos</title>
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
        if (!isset($_SESSION['numero']) || empty($_SESSION['numero'])) {
            session_unset();
            header('Location: ../index.php');
        }
        
        $numero = $_SESSION['numero'];
        $query = "SELECT * FROM numero_adm WHERE numero = '$numero'";
        $result = mysqli_query($conexao, $query);
        
        if (!$result || mysqli_num_rows($result) === 0) {
            session_unset();
            header('Location: ../index.php');
        }
    ?>
    <header>
        <a href="https://www.vwco.com.br/" tarGET="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <ul>
            <li><a href="../grafico/grafico.php">Gráfico</a></li>

            <li><a href="tabela-agendamentos.php">Início</a></li>

            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    
    <main>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="filtros">
            <div class="titulo">
                <h2><img src="https://icons.iconarchive.com/icons/pictogrammers/material/32/form-dropdown-icon.png" width="24" height="24" style="position: relative; top: 2px;">Filtros de Buscas</h2>
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
            <div class="solicitante">
                <div class="label_solicitante">
                    <label for="solicitante">Solicitante:</label>
                </div>
                <div class="input_solicitante">
                    <select name="solicitante" id="select_solicitante">
                        <option value="">Selecione o Solicitante</option>
                        <?php
                        $query_solicitante = "SELECT DISTINCT solicitante FROM agendamentos";
                        $result_solicitante = mysqli_query($conexao, $query_solicitante);
                        while ($row_solicitante = mysqli_fetch_assoc($result_solicitante)) {
                            $selected = ($solicitante == $row_solicitante['solicitante']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($row_solicitante['solicitante']) . '" ' . $selected . '>' . htmlspecialchars($row_solicitante['solicitante']) . '</option>';
                        }
                        ?>
                    </select>
                    <script>
                        $(document).ready(function () {
                        $('#select_solicitante').select2();
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
                        <option value="Reprovado">Reprovado</option>
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
        <div class="caption"><img src="https://icons.iconarchive.com/icons/iconsmind/outline/24/Calendar-4-icon.png" width="22" height="22" style="position: relative; top: 3px; margin-right: 5px;">Tabela de Agendamentos</div>
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
                    <th>Aprovar/<br>Reprovar</th>
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
                                    AND (solicitante LIKE '%$solicitante%')
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
                                        AND (solicitante LIKE '%$solicitante%')
                                        AND (area_pista LIKE '%$area_pista%')
                                        AND (exclsv LIKE '%$exclsv%')
                                        AND (status LIKE '%$status%')
                                        ORDER BY 
                                        CASE 
                                            WHEN status = 'Pendente' THEN 1
                                            WHEN status = 'Aprovado' THEN 2
                                            ELSE 3
                                        END,
                                        dia ASC
                                        LIMIT $registros_por_pagina OFFSET $offset";
                    $result_paginacao = mysqli_query($conexao, $query_paginacao);

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
                                <?php if ($row['status'] === 'Pendente') { ?>
                                    <a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>"><input type="button" value="✔" class="aprovar"></a>
                                    <input type="hidden" name="solicitante" value="<?php echo $row['solicitante']; ?>">
                                    <a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>"><input type="button" value="✖︎" class="cancelar"></a>
                                <?php } elseif ($row['status'] === 'Aprovado') { ?>
                                    <a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>"><input type="button" value="✖︎" class="cancelar"></a>
                                    <input type="hidden" name="solicitante" value="<?php echo $row['solicitante']; ?>">
                                <?php } else { ?>
                                    <a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>"><input type="button" value="✔" class="aprovar"></a>
                                    <input type="hidden" name="solicitante" value="<?php echo $row['solicitante']; ?>">
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

        <div class="addRmv">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmv">
                <div class="area_addRmv">
                    <div class="area_addRmv_label">
                        <h3>Áreas da Pista</h3>
                    </div>
                    <div class="area_add">
                        <h4>Adicionar</h4>
                        <input type="text" name="novoArea" placeholder="Nova Área">
                        <input type="submit" name="addArea" value="Adicionar">
                    </div>
                    <div class="area_rmv">
                        <h4>Remover</h4>
                        <select name="removerArea">
                            <option value="">Remover Área</option>
                            <?php
                            $query_area = "SELECT DISTINCT area FROM area_pista";
                            $result_area = mysqli_query($conexao, $query_area);
                            while ($row_area = mysqli_fetch_assoc($result_area)) {
                                $selected = ($area == $row_area['area']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row_area['area']) . '" ' . $selected . '>' . htmlspecialchars($row_area['area']) . '</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" name="rmvArea" value="Remover">
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmv">
                <div class="objtv_addRmv">
                    <div class="objtv_addRmv_label">
                        <h3>Objetivos</h3>
                    </div>
                    <div class="objtv_add">
                        <h4>Adicionar</h4>
                        <input type="text" name="novoObjtv" placeholder="Novo Objetivo">
                        <input type="submit" name="addObjtv" value="Adicionar">
                    </div>
                    <div class="objtv_rmv">
                        <h4>Remover</h4>
                        <select name="removerObjtv">
                            <option value="">Remover Objetivo</option>
                            <?php
                            $query_objtv = "SELECT DISTINCT objtv FROM objtv_teste";
                            $result_objtv = mysqli_query($conexao, $query_objtv);
                            while ($row_objtv = mysqli_fetch_assoc($result_objtv)) {
                                $selected = ($objtv == $row_objtv['objtv']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row_objtv['objtv']) . '" ' . $selected . '>' . htmlspecialchars($row_objtv['objtv']) . '</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" name="rmvObjtv" value="Remover">
                    </div>
                </div>
            </form>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="form_addRmv">
                <div class="solic_addRmv">
                    <div class="solic_addRmv_label">
                        <h3>Áreas Solicitantes</h3>
                    </div>
                    <div class="solic_add">
                        <h4>Adicionar</h4>
                        <input type="text" name="novoSolic" placeholder="Nova Área Solicitante">
                        <input type="submit" name="addSolic" value="Adicionar">
                    </div>
                    <div class="solic_rmv">
                        <h4>Remover</h4>
                        <select name="removerSolic">
                            <option value="">Remover Área Solicitante</option>
                            <?php
                            $query_solic = "SELECT DISTINCT nome FROM area_solicitante";
                            $result_solic = mysqli_query($conexao, $query_solic);
                            while ($row_solic = mysqli_fetch_assoc($result_solic)) {
                                $selected = ($solic == $row_solic['nome']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row_solic['nome']) . '" ' . $selected . '>' . htmlspecialchars($row_solic['nome']) . '</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" name="rmvSolic" value="Remover">
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addArea'])) {
            if (!empty($_GET['novoArea'])) {
                $novoArea = $_GET['novoArea'];
                $query_addArea = "INSERT INTO area_pista(area) VALUES (?)";

                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addArea);

                // Vincular os parâmetros
                $stmt->bind_param("s", $novoArea);

                // Executar a consulta
                $stmt->execute();

                // Fechar a declaração
                $stmt->close();

                echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvArea'])) {
            $removerArea = $_GET['removerArea'];
            $query_removerArea = "DELETE FROM area_pista WHERE area = ?";

            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_removerArea);

            // Vincular os parâmetros
            $stmt->bind_param("s", $removerArea);

            // Executar a consulta
            $stmt->execute();

            // Fechar a declaração
            $stmt->close();

            echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
        }


        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addObjtv'])) {
            if (!empty($_GET['novoObjtv'])) {
                $novoObjtv = $_GET['novoObjtv'];
                $query_addObjtv = "INSERT INTO objtv_teste(objtv) VALUES (?)";
        
                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addObjtv);
        
                // Vincular os parâmetros
                $stmt->bind_param("s", $novoObjtv);
        
                // Executar a consulta
                $stmt->execute();
        
                // Fechar a declaração
                $stmt->close();

                echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvObjtv'])) {
            $removerObjtv = $_GET['removerObjtv'];
            $query_removerObjtv = "DELETE FROM objtv_teste WHERE objtv = ?";
        
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_removerObjtv);
        
            // Vincular os parâmetros
            $stmt->bind_param("s", $removerObjtv);
        
            // Executar a consulta
            $stmt->execute();
        
            // Fechar a declaração
            $stmt->close();

            echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['addSolic'])) {
            if (!empty($_GET['novoSolic'])) {
                $novoSolic = $_GET['novoSolic'];
                $query_addSolic = "INSERT INTO area_solicitante(nome) VALUES (?)";
        
                // Preparar a declaração SQL
                $stmt = $conexao->prepare($query_addSolic);
        
                // Vincular os parâmetros
                $stmt->bind_param("s", $novoSolic);
        
                // Executar a consulta
                $stmt->execute();
        
                // Fechar a declaração
                $stmt->close();

                echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rmvSolic'])) {
            $removerSolic = $_GET['removerSolic'];
            $query_removerSolic = "DELETE FROM area_solicitante WHERE nome = ?";
        
            // Preparar a declaração SQL
            $stmt = $conexao->prepare($query_removerSolic);
        
            // Vincular os parâmetros
            $stmt->bind_param("s", $removerSolic);
        
            // Executar a consulta
            $stmt->execute();
        
            // Fechar a declaração
            $stmt->close();

            echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
        }
        
    ?>
    <footer>
        <div>
            <span>Desenvolvido por:  <img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2023 de Zeentech os direitos reservados</span>
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
            var botoesAprovar = document.querySelectorAll(".aprovar");
            var botoesCancelar = document.querySelectorAll(".cancelar");

            botoesAprovar.forEach(function (botao) {
                botao.addEventListener("click", function (event) {
                    event.preventDefault();
                    var linha = this.closest("tr");
                    
                    var id = linha.cells[0].innerText;
                    var data = linha.cells[1].innerText;
                    var horaInicio = linha.cells[2].innerText;
                    var horaFim = linha.cells[3].innerText;
                    var objetivo = linha.cells[5].innerText;
                    var solicitante = linha.cells[6].innerText;
                    var AreaSolicitante = linha.cells[9].innerText;

                    Swal.fire({
                        title: "Confirmação",
                        html: `<div style='text-align: start; padding: 0 2rem; line-height: 1.5rem'>
                        <h3 style="text-align: center;">Você tem certeza de que deseja APROVAR o seguinte agendamento?</h3><br>
                            Dia: ${data}<br>
                            Hora de Início: ${horaInicio}<br>
                            Hora do Fim: ${horaFim}<br>
                            Objetivo: ${objetivo}<br>
                            Solicitante: ${solicitante}<br>
                            Área do Solicitante: ${AreaSolicitante}
                        </div>`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sim, aprovar",
                        cancelButtonText: "Cancelar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "aprovar-reprovar/aprovar.php?id=" + id;
                        }
                    });
                });
            });

            botoesCancelar.forEach(function (botao) {
                botao.addEventListener("click", function (event) {
                    event.preventDefault();
                    var linha = this.closest("tr");

                    var id = linha.cells[0].innerText;
                    var data = linha.cells[1].innerText;
                    var horaInicio = linha.cells[2].innerText;
                    var horaFim = linha.cells[3].innerText;
                    var objetivo = linha.cells[5].innerText;
                    var solicitante = linha.cells[6].innerText;
                    var AreaSolicitante = linha.cells[9].innerText;

                    Swal.fire({
                        title: "Confirmação",
                        html: `<div style='text-align: start; padding: 0 2rem; line-height: 1.5rem'>
                            <h3 style="text-align: center;">Você tem certeza de que deseja REPROVAR o seguinte agendamento?</h3><br>
                            Dia: ${data}<br>
                            Hora de Início: ${horaInicio}<br>
                            Hora do Fim: ${horaFim}<br>
                            Objetivo: ${objetivo}<br>
                            Solicitante: ${solicitante}<br>
                            Área do Solicitante: ${AreaSolicitante}
                        </div>`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sim, reprovar",
                        cancelButtonText: "Cancelar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "aprovar-reprovar/cancelar.php?id=" + id;
                        }
                    });
                });
            });
        });
    </script>

</body>
</html>