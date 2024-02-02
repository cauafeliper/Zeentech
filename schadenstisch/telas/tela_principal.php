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
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            </form>
        </div>

        <div class="div__tabela">
            <h2>Tabela de KPM`s</h2>
            <div class="filtros">
                <button class="filtro-btn ativo" onclick="filtrar('todos')">Todos</button>
                <button class="filtro-btn" onclick="filtrar('abertos')">Abertos</button>
                <button class="filtro-btn" onclick="filtrar('fechados')">Fechados</button>
            </div>
            <div>
                <table id="tabela-kpm">
                    <tr>
                        <th style="display: none;">Item</th>
                        <th onclick="filtrarPorColuna(1)" data-coluna="1">Programa</th>
                        <th onclick="filtrarPorColuna(2)" data-coluna="2">Nº do Problema</th>
                        <th onclick="filtrarPorColuna(3)" data-coluna="3">Ranking</th>
                        <th onclick="filtrarPorColuna(4)" data-coluna="4">Resumo</th>
                        <th onclick="filtrarPorColuna(5)" data-coluna="5">Veículo</th>
                        <th onclick="filtrarPorColuna(6)" data-coluna="6">Status KPM</th>
                        <th onclick="filtrarPorColuna(7)" data-coluna="7">Status Reunião</th>
                        <th onclick="filtrarPorColuna(8)" data-coluna="8">FG</th>
                        <th onclick="filtrarPorColuna(9)" data-coluna="9">Dias em Aberto</th>
                        <th onclick="filtrarPorColuna(10)" data-coluna="10">Due Date</th>
                    </tr>
                    <?php
                    $query = "SELECT * FROM kpm";
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
                        <?php }
                    } else {
                        echo '<tr><td colspan="10">Ainda não existem KPM\'s registrados!</td></tr>';
                    }
                    ?>
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
    </footer>
    <script>
        // Função para realizar a filtragem por estado
        function filtrar(estado) {
            // Remove a classe 'ativo' de todos os botões
            document.querySelectorAll('.filtro-btn').forEach(btn => {
                btn.classList.remove('ativo');
            });

            // Adiciona a classe 'ativo' ao botão clicado
            event.currentTarget.classList.add('ativo');

            // Obtém os valores do filtro de coluna, se existirem
            let filtroColuna = document.querySelector('.ativo select');
            let filtroColunaParametros = '';
            if (filtroColuna) {
                let coluna = filtroColuna.getAttribute('data-coluna');
                let valor = filtroColuna.value;
                filtroColunaParametros = `&filtroColuna=${coluna}&valorFiltro=${valor}`;
            }

            // Faz uma requisição Ajax para o arquivo que tratará a filtragem no servidor
            fetch(`codigos_php/filtrar_kpm.php?estado=${estado}${filtroColunaParametros}`)
                .then(response => response.text())
                .then(data => {
                    // Atualiza o conteúdo da tabela com os resultados filtrados
                    document.querySelector('.div__tabela table').innerHTML = data;
                })
                .catch(error => console.error('Erro:', error));
        }

        // Função para realizar a filtragem por coluna
        function filtrarPorColuna(coluna) {
            // Remove a classe 'ativo' de todos os botões
            document.querySelectorAll('.filtro-btn').forEach(btn => {
                btn.classList.remove('ativo');
            });

            // Adiciona a classe 'ativo' ao botão clicado
            event.currentTarget.classList.add('ativo');

            // Obtém os valores únicos da coluna selecionada
            let valoresUnicos = [...new Set(Array.from(document.querySelectorAll(`#tabela-kpm td:nth-child(${coluna})`)).map(td => td.textContent.trim()))];

            // Cria um seletor de opções
            let select = document.createElement('select');
            select.innerHTML = '<option value="">Todos</option>';

            valoresUnicos.forEach(valor => {
                let option = document.createElement('option');
                option.value = valor;
                option.text = valor;
                select.appendChild(option);
            });

            // Adiciona um ouvinte de evento para alterações no seletor
            select.addEventListener('change', function () {
                let estado = select.value ? `&filtroColuna=${coluna}&valorFiltro=${select.value}` : '';
                // Faz uma requisição Ajax para o arquivo que tratará a filtragem no servidor
                fetch(`codigos_php/filtrar_kpm.php?estado=todos${estado}`)
                    .then(response => response.text())
                    .then(data => {
                        // Atualiza o conteúdo da tabela com os resultados filtrados
                        document.querySelector('.div__tabela table').innerHTML = data;
                    })
                    .catch(error => console.error('Erro:', error));
            });

            // Substitui o conteúdo do th pelo seletor
            let th = document.querySelector(`#tabela-kpm th:nth-child(${coluna})`);
            th.innerHTML = '';
            th.appendChild(select);
        }
    </script>
    <?php 
        mysqli_close($conexao);
    ?>
</body>
</html>