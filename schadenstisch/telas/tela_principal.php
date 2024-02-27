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
    <link rel="stylesheet" href="style/selectize.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js/dist/tippy.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <title>Schadenstisch | Home</title>
    <?php include_once('style/selectize.html');?>
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
                            <li><a href="tela_adm.php">ADM</a></li>
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
            <button class="botoes__programas">Todos</button>
            <div class="div__botoes_programas">
                <?php
                    $query_programa = "SELECT programa FROM programas";
                    $result_programa = $conexao->query($query_programa);
                    while ($row = $result_programa->fetch_assoc()) {
                        echo '<button value="' . $row['programa'] . '">' . $row['programa'] . '</button>';
                    }
                ?>
            </div>
        </div>

        <div class="div__tabela">
            <h2>Tabela de KPM`s</h2>
            <div class="filtros">
                <button class="filtro-btn" value="Todos">Todos</button>
                <button class="filtro-btn" value="Abertos">Abertos</button>
                <button class="filtro-btn" value="Fechados">Fechados</button>
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
            <span>Copyright © 2023 de Zeentech os direitos reservados</span>
        </div>
        <script>
            $(document).ready(function(){
                function atualizarTabela() {
                    $.ajax({
                        url: 'codigos/tabela.php', // Substitua 'seu_script_php.php' pelo nome do seu script PHP que processa a consulta ao banco de dados e retorna os resultados
                        type: 'GET',
                        dataType: 'html',
                        success: function(response) {
                            $('#tabela-kpm').html(response); // Atualiza o conteúdo da tabela com os dados retornados do script PHP
                        }
                    });
                }
                // Chama a função de atualização da tabela quando a página é carregada
                atualizarTabela();
            });
        </script>
        <script>
           $(document).ready(function() {
                $('.botoes__programas').click(function() {
                    var valorBotao = $(this).text();
                    $.ajax({
                        url: 'codigos/tabela.php',
                        type: 'GET',
                        data: {filtro_programa: valorBotao},
                        success: function(response) {
                            // Atualize a tabela com os dados recebidos do servidor
                            $('#tabela-kpm').html(response);
                        }
                    });
                });

                $('.div__botoes_programas').on('click', 'button', function() {
                    var valorBotao = $(this).val();
                    $.ajax({
                        url: 'codigos/tabela.php',
                        type: 'GET',
                        data: {filtro_programa: valorBotao},
                        success: function(response) {
                            // Atualize a tabela com os dados recebidos do servidor
                            $('#tabela-kpm').html(response);
                        }
                    });
                });
// ------------------------------------------------------------------------------------------------------------------------------------
                $('.filtros').on('click', '.filtro-btn', function() {
                    var valorBotao = $(this).text();
                    $.ajax({
                        url: 'codigos/tabela.php',
                        type: 'GET',
                        data: {filtro_status_reuniao: valorBotao},
                        success: function(response) {
                            // Atualize a tabela com os dados recebidos do servidor
                            $('#tabela-kpm').html(response);
                        }
                    });
                });

                $('.filtros').on('click', '.filtro-btn', function() {
                    var valorBotao = $(this).text();
                    $.ajax({
                        url: 'codigos/tabela.php',
                        type: 'GET',
                        data: {filtro_status_reuniao: valorBotao},
                        success: function(response) {
                            // Atualize a tabela com os dados recebidos do servidor
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
                            url: 'codigos/tabela.php',
                            type: 'GET',
                            data: { filtro_$filtro: valor },
                            success: function(response) {
                                // Atualize a tabela com os dados recebidos do servidor
                                $('#tabela-kpm').html(response);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                ";
            }

            // Lista de filtros
            $filtros = ['n_problem', 'rank', 'resumo', 'veiculo', 'status_kpm', 'fg', 'dias_aberto'];

            // Criar as funções para cada filtro
            foreach ($filtros as $filtro) {
                echo criarFuncaoArmazenar($filtro);
            }
            ?>

            $(document).on('keyup', function(event) {
                if (event.keyCode === 13 && $('.editavel:focus').length > 0) {
                    var novoValor = $('.editavel:focus').val();
                    var idItem = $('.editavel:focus').closest('tr').find('td:first-child').text(); // Aqui você pode precisar ajustar para obter o valor correto do ID do item
                    $.ajax({
                        url: 'codigos/tabela.php',
                        type: 'POST', // Certifique-se de que está enviando via POST
                        data: {
                            item: idItem, // Enviar o ID do item
                            coluna: 'rank',
                            valor: novoValor
                        },
                        success: function(response) {
                            console.log(response); // Verificar a resposta do servidor no console do navegador
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Exibir mensagens de erro no console do navegador
                        }
                    });
                }
            });
        </script>
    </footer>
</body>
</html>