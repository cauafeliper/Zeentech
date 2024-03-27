<?php
    include_once('../config/config.php');
    session_start();

    if (!isset($_SESSION['email']) OR !isset($_SESSION['senha'])) {
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
            session_destroy();
            echo '<script>window.location.href = "../index.php";</script>';
            exit();
        }
        else {
            $query = "SELECT COUNT(*) as total FROM email_adm WHERE email = ?";

            $stmt = mysqli_prepare($conexao, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $total);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                if ($total <= 0) {
                    session_destroy();
                    echo '<script>window.location.href = "../index.php";</script>';
                } 
                else {
                }
            } 
            else {
                die('Erro na preparação da consulta: ' . mysqli_error($conexao));
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="style/style_adm.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js/dist/tippy.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <title>Schadenstisch | ADM</title>
    <style>
        .ativo {      
            background-color: var(--escuro);
            border: 1px solid var(--claro);
            color: white;
        }
    </style>
    <?php
        include_once('style/selectize.html');
    ?>
</head>
<body>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus"></a>
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
                                <li><a href="tela_principal.php">Início</a></li>
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
            <li><a href="../sair.php">Sair</a></li>
        </ul>
    </header>
    <main>
        <div class="div__programas">
            <h2>Programas</h2>
            <button class="botoes_programa">Todos</button>
            <div class="div__botoes_programas">
                <?php
                    $query_programa = "SELECT programa FROM programas";
                    $result_programa = $conexao->query($query_programa);
                    while ($row = $result_programa->fetch_assoc()) {
                        echo '<button value="' . $row['programa'] . '" class="botoes_programa">' . $row['programa'] . '</button>';
                    }
                ?>
            </div>
        </div>

        <div class="div__tabela">
            <h2>Tabela de KPM`s</h2>
            <div class="filtros">
                <button class="filtro-btn <?= $ativo_todos_class ?>" value="Todos">Todos</button>
                <button class="filtro-btn <?= $ativo_abertos_class ?>" value="Abertos">Abertos</button>
                <button class="filtro-btn <?= $ativo_fechados_class ?>" value="Fechados">Fechados</button>
            </div>
            <div>
                <table id="tabela-kpm">
                    
                </table>
            </div>
        </div>
    </main>

    <footer>
        <div>
            <span>Desenvolvido por:  <img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2024 de Zeentech os direitos reservados</span>
        </div>
        <script>
            $(document).ready(function() {
                // Função para atualizar a tabela
                function atualizarTabelaAdm() {
                    $.ajax({
                        url: 'codigos/tabela_adm.php',
                        type: 'GET',
                        dataType: 'html',
                        success: function(response) {
                            $('#tabela-kpm').html(response);
                        }
                    });
                }

                // Chama a função de atualização da tabela quando a página é carregada
                atualizarTabelaAdm();

                // Outro código do seu script...
            });

            // Remova a função inicializarSelectize() daqui

            // Use o evento ajaxComplete para inicializar o Selectize após cada requisição AJAX
            $(document).ajaxComplete(function() {
                // Inicialize o Selectize após cada requisição AJAX
                $('#select_n_problem').selectize({
                    // Opções adicionais, se necessário
                });
            });
        </script>
        <script>
           $(document).ready(function() {
            $('.div__programas .botoes_programa[value="Todos"]').addClass('ativo');

            $('.div__programas .botoes_programa, .div__botoes_programas button').click(function() {
                // Remove a classe 'ativo' de todos os botões de programa
                $('.div__programas .botoes_programa, .div__botoes_programas button').removeClass('ativo');
                // Adiciona a classe 'ativo' apenas ao botão clicado
                $(this).addClass('ativo');

                // Verifica se o botão clicado pertence aos botões de programa
                if ($(this).hasClass('botoes_programa')) {
                    var valorBotao = $(this).text();
                    $.ajax({
                        url: 'codigos/tabela_adm.php',
                        type: 'GET',
                        data: {filtro_programa: valorBotao},
                        success: function(response) {
                            $('#tabela-kpm').html(response);
                        }
                    });
                }
            });

            // Script para os filtros de status
            $('.filtros .filtro-btn[value="Todos"]').addClass('ativo');

            $('.filtros .filtro-btn').click(function() {
                // Remove a classe 'ativo' de todos os botões de filtro
                $('.filtros .filtro-btn').removeClass('ativo');
                // Adiciona a classe 'ativo' apenas ao botão clicado
                $(this).addClass('ativo');

                var valorBotao = $(this).text();
                $.ajax({
                    url: 'codigos/tabela_adm.php',
                    type: 'GET',
                    data: {filtro_status_reuniao: valorBotao},
                    success: function(response) {
                        $('#tabela-kpm').html(response);
                    }
                });
            });
// ------------------------------------------------------------------------------------------------------------------------------------
                $('.filtros .filtro-btn[value="Todos"]').addClass('ativo');

                $('.filtros .filtro-btn').click(function() {
                    // Remove a classe 'ativo' de todos os botões de filtro
                    $('.filtros .filtro-btn').removeClass('ativo');

                    // Adiciona a classe 'ativo' apenas ao botão clicado
                    $(this).addClass('ativo');

                    var valorBotao = $(this).text();
                    $.ajax({
                        url: 'codigos/tabela_adm.php',
                        type: 'GET',
                        data: {filtro_status_reuniao: valorBotao},
                        success: function(response) {
                            $('#tabela-kpm').html(response);
                        }
                    });
                });
            });
//---------------------------------------------------------------------------------------------------------------------------------
            <?php
                function criarFuncaoArmazenar($filtro) {
                    return "
                        function armazenar_$filtro(valor) {
                            $.ajax({
                                url: 'codigos/tabela_adm.php',
                                type: 'GET',
                                data: { filtro_$filtro: valor },
                                success: function(response) {
                                    // Atualize a tabela com os dados recebidos do servidor
                                    $('#tabela-adm-kpm').html(response);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        }
                    ";
                }

                // Lista de filtros
                $filtros = ['n_problem', 'rank', 'data_fecha', 'resumo', 'veiculo', 'status_kpm', 'fg', 'dias_aberto', 'highlight', 'cw_er', 'causa', 'modelo', 'aval_crit', 'aval_crit_2', 'teste', 'kpm_dias_correntes', 'pn', 'reclamante', 'status_acao', 'resp_acao', 'status_du', 'info_du', 'dev_fk', 'dur_fk', 'teste_trt', 'status_anali_ser', 'feedback_ser', 'timing_status', 'nxt_frt_ans'];

                // Criar as funções para cada filtro
                foreach ($filtros as $filtro) {
                    echo criarFuncaoArmazenar($filtro);
                }
            ?>

            $(document).on('keyup', function(event) {
                if (event.keyCode === 13 && $('.editavel:focus').length > 0) {
                    var novoValor = $('.editavel:focus').val();
                    var idItem = $('.editavel:focus').closest('tr').find('td:first-child').text();
                    var coluna = $('.editavel:focus').closest('td').attr('name'); // Aqui você pode precisar ajustar para obter o valor correto do atributo "value" da célula
                    $.ajax({
                        url: 'codigos/tabela_adm.php',
                        type: 'POST',
                        data: {
                            item: idItem,
                            coluna: coluna,
                            valor: novoValor
                        },
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

        </script>
    </footer>
</body>
</html>