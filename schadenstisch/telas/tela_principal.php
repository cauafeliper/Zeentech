<?php
    include_once('../config/config.php');
    session_start();

    if (!isset($_SESSION['email'])) {
        echo '<script>window.location.href = "../index.php";</script>';
        exit();
    } else {
        $email = $_SESSION['email'];
        $senha = $_SESSION['senha'];

        // Concatena as variáveis diretamente na string da consulta e escapa-as
        $query = "SELECT COUNT(*) as total FROM logins WHERE email = '" . mysqli_real_escape_string($conexao, $email) . "' AND senha = '" . mysqli_real_escape_string($conexao, $senha) . "'";

        $result = $conexao->query($query);

        if (!$result) {
            die('Erro na execução da consulta: ' . mysqli_error($conexao));
        }

        $row = mysqli_fetch_assoc($result);
        $total = $row['total'];

        if ($total <= 0) {
            echo '<script>window.location.href = "../index.php";</script>';
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="style/style_principal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js/dist/tippy.css" />
    <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy-bundle.umd.js"></script>
    <title>Schadenstisch | Home</title>
</head>
<body>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 100%;"></a>
        <ul>
            <?php
            $query = "SELECT COUNT(*) as total FROM email_adm WHERE email = ?";

            $stmt = mysqli_prepare($conexao, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $total);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                if ($total > 0) {
                    echo '
                            <li><a href="../tela_adm.php">ADM</a></li>
                            <li><a href="../cadastro-login/aprovacao_Login.php">Logins</a></li>';
                } else {
                    echo '
                            <span style="color: #001e50;">.</span>
                            <span style="color: #001e50;">.</span>';
                }
            } else {
                die('Erro na preparação da consulta: ' . mysqli_error($conexao));
            }
            ?>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>

    <main>
        <div class="div__programas">
            <h2>Programas</h2>
            <button class="botoes__programas programa__ativo">Todos</button>
            <div class="div__botoes_programas">
                <?php
                    $query_programa = "SELECT programa FROM programas";
                    $result_programa = $conexao->query($query_programa);
                    while ($row = $result_programa->fetch_assoc()) {
                        echo '<button>' . $row['programa'] . '</button>';
                    }
                ?>
            </div>
        </div>

        <div class="div__tabela">
            <h2>Tabela de KPM`s</h2>
            <div class="filtros">
                <button class="filtro-btn ativo">Todos</button>
                <button class="filtro-btn">Abertos</button>
                <button class="filtro-btn">Fechados</button>
            </div>
            <div>
                <table id="tabela-kpm">
                    <tr>
                        <th style="display: none;">Item</th>
                        <th>Programa</th>
                        <th>Nº do Problema</th>
                        <th>Ranking</th>
                        <th>Resumo</th>
                        <th>Veículo</th>
                        <th>Status KPM</th>
                        <th>Status Reunião</th>
                        <th>FG</th>
                        <th>Dias em Aberto</th>
                        <th >Due Date</th>
                    </tr>
                    <?php
                        // Inicialize as variáveis de filtro
                        $filtro_programa = isset($_GET['filtro_programa']) ? $_GET['filtro_programa'] : 'Todos';
                        $filtro_n_problem = isset($_GET['filtro_n_problem']) ? $_GET['filtro_n_problem'] : 'Todos';
                        $filtro_rank = isset($_GET['filtro_rank']) ? $_GET['filtro_rank'] : 'Todos';
                        $filtro_resumo = isset($_GET['filtro_resumo']) ? $_GET['filtro_resumo'] : 'Todos';
                        $filtro_veiculo = isset($_GET['filtro_veiculo']) ? $_GET['filtro_veiculo'] : 'Todos';
                        $filtro_status_kpm = isset($_GET['filtro_status_kpm']) ? $_GET['filtro_status_kpm'] : 'Todos';
                        $filtro_status_reuniao = isset($_GET['filtro_status_reuniao']) ? $_GET['filtro_status_reuniao'] : 'Todos';
                        $filtro_fg = isset($_GET['filtro_fg']) ? $_GET['filtro_fg'] : 'Todos';
                        // Adicione mais variáveis de filtro conforme necessário

                        // Construa a consulta SQL com base nos filtros
                        $query = "SELECT * FROM kpm WHERE 1=1";

                        // Adicione condições de filtro para cada coluna conforme necessário
                        if ($filtro_programa != 'Todos') {
                            $query .= " AND programa = '$filtro_programa'";
                        }

                        if ($filtro_n_problem != 'Todos') {
                            $query .= " AND n_problem = '$filtro_n_problem'";
                        }

                        if ($filtro_rank != 'Todos') {
                            $query .= " AND rank = '$filtro_rank'";
                        }

                        if ($filtro_resumo != 'Todos') {
                            $query .= " AND resumo = '$filtro_resumo'";
                        }

                        if ($filtro_veiculo != 'Todos') {
                            $query .= " AND veiculo = '$filtro_veiculo'";
                        }

                        if ($filtro_status_kpm != 'Todos') {
                            $query .= " AND status_kpm = '$filtro_status_kpm'";
                        }

                        if ($filtro_status_reuniao != 'Todos') {
                            $query .= " AND status_reuniao = '$filtro_status_reuniao'";
                        }

                        if ($filtro_fg != 'Todos') {
                            $query .= " AND fg = '$filtro_fg'";
                        }
                        // Adicione mais condições de filtro para outras colunas conforme necessário

                        // Execute a consulta SQL
                        $result = $conexao->query($query);

                        // Exibir os resultados
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td style="display: none;"><?php echo $row['item']; ?></td>
                                    <td value="<?php echo $row['programa']; ?>" id="td_tippy"><?php echo $row['programa']; ?></td>
                                    <td value="<?php echo $row['n_problem']; ?>" id="td_tippy"><?php echo $row['n_problem']; ?></td>
                                    <td value="<?php echo $row['rank']; ?>" id="td_tippy"><?php echo $row['rank']; ?></td>
                                    <td value="<?php echo $row['resumo']; ?>" id="td_tippy"><?php echo $row['resumo']; ?></td>
                                    <td value="<?php echo $row['veiculo']; ?>" id="td_tippy"><?php echo $row['veiculo']; ?></td>
                                    <td value="<?php echo $row['status_kpm']; ?>" id="td_tippy"><?php echo $row['status_kpm']; ?></td>
                                    <td value="<?php echo $row['status_reuniao']; ?>" id="td_tippy"><?php echo $row['status_reuniao']; ?></td>
                                    <td value="<?php echo $row['fg']; ?>" id="td_tippy"><?php echo $row['fg']; ?></td>
                                    <td value="<?php echo $row['dias_aberto']; ?>" id="td_tippy"><?php echo $row['dias_aberto']; ?></td>
                                    <td value="<?php echo date('d/m/Y', strtotime($row['due_date'])); ?>" id="td_tippy"><?php echo date('d/m/Y', strtotime($row['due_date'])); ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="10">Ainda não existem KPM\'s registrados!</td></tr>';
                        }

                        // Feche a conexão com o banco de dados
                        mysqli_close($conexao);
                    ?>
                </table>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Seleciona todos os botões de programa
                        var botoesPrograma = document.querySelectorAll('.div__botoes_programas button');

                        // Adiciona um evento de clique a cada botão de programa
                        botoesPrograma.forEach(function(botao) {
                            botao.addEventListener('click', function() {
                                // Recupera o valor do programa associado a este botão
                                var programaSelecionado = botao.textContent.trim();

                                // Envia uma solicitação AJAX ao PHP com o valor do programa selecionado
                                var xhr = new XMLHttpRequest();
                                xhr.open('GET', 'filtros.php?filtro_programa=' + encodeURIComponent(programaSelecionado));
                                xhr.send();

                                // Lida com a resposta da solicitação AJAX
                                xhr.onload = function() {
                                    // Atualiza a tabela com os dados retornados sem recarregar a página
                                    document.getElementById('tabela-kpm').innerHTML = xhr.responseText;
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </main>

    <footer>
        <div>
            <span>Desenvolvido por:  <img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2023 de Zeentech os direitos reservados</span>
        </div>
    </footer>
</body>
</html>