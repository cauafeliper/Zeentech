<?php 
    session_start();
    include_once('config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador | Valeta</title>
    <link rel="stylesheet" href="estilos/style-tabela_usuario.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo__header">
            <img src="imgs/logo-zeentech.png" alt="logo-zeentech">
        </div>
        
        <div class="botoes__header">
            <a href="tabela-agendamentos.php">
                <div class="alinhamento__header">
                    <img src="imgs/tabelas.png" alt="icon-teste">
                </div>
                <span class="alinhamento__header sumir__texto">Tabelas</span>
            </a>
            <a href="agendamento/agendamento-dia.php">
                <div class="alinhamento__header">
                    <img src="imgs/calend.png" alt="icon-teste">
                </div>
                <span class="alinhamento__header sumir__texto">Agendar</span>
            </a>
        </div>
       
        <a href="cadastro-login/sair.php" class="botao__sair">
            <img src="imgs/exit.png" alt="sair">
        </a>
    </header>
    <main>
    <div class="filtro">
            <h2>Filtros de Busca</h2>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
                <div>
                    <?php
                        include_once('config.php');
                        function select($tabela, $variavel, $placeH, $conexao) {
                                    echo '<select name="$variavel" id="$variavel">
                                        <option value="">'. $placeH .'</option>';
                                                $query_variavel = "SELECT $variavel FROM $tabela";
                                                $result_variavel = mysqli_query($conexao, $query_variavel);
                                                while ($row_variavel = mysqli_fetch_assoc($result_variavel)) {
                                                    echo '<option value="' . $row_variavel[''. $variavel .''] . '">' . $row_variavel[''. $variavel .''] . '</option>';
                                            };
                                    echo '</select>';
                        }
                        select('veics', 'veic', 'Selecione o Veículo', $conexao);
                        select('insp', 'insp', 'Seleciono o Inspetor', $conexao);
                    ?>
                    <select name="eng" id="idEng">
                        <option value="">Valent ENG?</option>
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                </div>
                <input type="submit" value="Pesquisar">
            </form>
        </div>
        <section class="tabela">
            <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">Tabela de Agendamentos</h2>
            <div class="container">
                <table>
                    <thead>
                        <th scope="col">Valeta</th>
                        <th scope="col">Dia</th>
                        <th scope="col">Veículo</th>
                        <th scope="col">Inspetor</th>
                        <th scope="col">Apagar</th>
                    </thead>
                    <tbody>
                    <?php
                        include_once('config.php');
                        $registros_por_pagina = 10;
                        $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

                        $dia = isset($_GET['dia']) ? $_GET['dia'] : '';
                        $veic = isset($_GET['veic']) ? $_GET['veic'] : '';
                        $insp = isset($_GET['insp']) ? $_GET['insp'] : '';
                        $eng = isset($_GET['eng']) ? $_GET['eng'] : '';

                        $query_paginacao = "SELECT *, 
                                            CASE 
                                                WHEN valeta = 'valeta_a' THEN 'Valeta A'
                                                WHEN valeta = 'valeta_b' THEN 'Valeta B'
                                                WHEN valeta = 'valeta_c' THEN 'Valeta C'
                                                WHEN valeta = 'valeta_vw' THEN 'Valeta VW'
                                                ELSE 'Desconhecida'
                                            END as Valeta,
                                            DATE_FORMAT(dia, '%d/%m/%Y') as data_formatada
                                            FROM (
                                                SELECT *, 'valeta_a' as valeta FROM valeta_a WHERE
                                                    (dia LIKE '%$dia%')
                                                    AND (veículo LIKE '%$veic%')
                                                    AND (inspetor LIKE '%$insp%')
                                                    AND (eng LIKE '%$eng%')
                                                    AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                                UNION ALL
                                                SELECT *, 'valeta_b' as valeta FROM valeta_b WHERE
                                                    (dia LIKE '%$dia%')
                                                    AND (veículo LIKE '%$veic%')
                                                    AND (inspetor LIKE '%$insp%')
                                                    AND (eng LIKE '%$eng%')
                                                    AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                                UNION ALL
                                                SELECT *, 'valeta_c' as valeta FROM valeta_c WHERE
                                                    (dia LIKE '%$dia%')
                                                    AND (veículo LIKE '%$veic%')
                                                    AND (inspetor LIKE '%$insp%')
                                                    AND (eng LIKE '%$eng%')
                                                    AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                                UNION ALL
                                                SELECT *, 'valeta_vw' as valeta FROM valeta_vw WHERE
                                                    (dia LIKE '%$dia%')
                                                    AND (veículo LIKE '%$veic%')
                                                    AND (inspetor LIKE '%$insp%')
                                                    AND (eng LIKE '%$eng%')
                                                    AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                            ) as todas_valetas";

                        $result_paginacao = mysqli_query($conexao, $query_paginacao);

                        if ($result_paginacao) {
                            $total_registros = mysqli_num_rows($result_paginacao);
                            $total_paginas = ceil($total_registros / $registros_por_pagina);

                            $offset = ($pagina_atual - 1) * $registros_por_pagina;

                            $query_paginacao = "SELECT *, 
                                                CASE 
                                                    WHEN valeta = 'valeta_a' THEN 'Valeta A'
                                                    WHEN valeta = 'valeta_b' THEN 'Valeta B'
                                                    WHEN valeta = 'valeta_c' THEN 'Valeta C'
                                                    WHEN valeta = 'valeta_vw' THEN 'Valeta VW'
                                                    ELSE 'Desconhecida'
                                                END as Valeta,
                                                DATE_FORMAT(dia, '%d/%m/%Y') as data_formatada
                                                FROM (
                                                    SELECT *, 'valeta_a' as valeta FROM valeta_a WHERE
                                                        (dia LIKE '%$dia%')
                                                        AND (veículo LIKE '%$veic%')
                                                        AND (inspetor LIKE '%$insp%')
                                                        AND (eng LIKE '%$eng%')
                                                        AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                                    UNION ALL
                                                    SELECT *, 'valeta_b' as valeta FROM valeta_b WHERE
                                                        (dia LIKE '%$dia%')
                                                        AND (veículo LIKE '%$veic%')
                                                        AND (inspetor LIKE '%$insp%')
                                                        AND (eng LIKE '%$eng%')
                                                        AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                                    UNION ALL
                                                    SELECT *, 'valeta_c' as valeta FROM valeta_c WHERE
                                                        (dia LIKE '%$dia%')
                                                        AND (veículo LIKE '%$veic%')
                                                        AND (inspetor LIKE '%$insp%')
                                                        AND (eng LIKE '%$eng%')
                                                        AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                                    UNION ALL
                                                    SELECT *, 'valeta_vw' as valeta FROM valeta_vw WHERE
                                                        (dia LIKE '%$dia%')
                                                        AND (veículo LIKE '%$veic%')
                                                        AND (inspetor LIKE '%$insp%')
                                                        AND (eng LIKE '%$eng%')
                                                        AND (solic = '{$_SESSION['re']}')  -- Adicionando a condição aqui
                                                ) as todas_valetas
                                                LIMIT $registros_por_pagina OFFSET $offset";

                            $result_paginacao = mysqli_query($conexao, $query_paginacao);

                            while ($row = mysqli_fetch_assoc($result_paginacao)) {
                                echo '<tr>';
                                echo '<td>' . $row['Valeta'] . '</td>';
                                echo '<td>' . $row['data_formatada'] . '</td>';
                                echo '<td>' . $row['veículo'] . '</td>';
                                echo '<td>' . $row['inspetor'] . '</td>';
                                echo '<td><a href="apagar.php?tabela=' . $row['Valeta'] . '&id=' . $row['id'] . '"><img src="https://icons.iconarchive.com/icons/steve/zondicons/24/Trash-icon.png" style="width: 1.5em; height: 1.5em;"></a></td>';
                                echo '</tr>';
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
                <div class="paginacao">
                    <span>Páginas:</span>

                    <?php
                    $num_paginas_visiveis = 5;
                    $pagina_inicial = max(1, $pagina_atual - floor($num_paginas_visiveis / 2));
                    $pagina_final = min($total_paginas, $pagina_inicial + $num_paginas_visiveis - 1);
                    $pagina_inicial = max(1, $pagina_final - $num_paginas_visiveis + 1);

                    if ($pagina_inicial > 1) {
                        echo '<a href="?pagina=1';
                        if ($dia !== '') echo '&dia=' . $dia;
                        if ($veic !== '') echo '&veic=' . $veic;
                        if ($insp !== '') echo '&insp=' . $insp;
                        if ($eng !== '') echo '&eng=' . $eng;
                        echo '">1</a>';
                        echo '<span style="color: white;">...</span>';
                    }

                    for ($i = $pagina_inicial; $i <= $pagina_final; $i++) {
                        echo '<a href="?pagina=' . $i;
                        if ($dia !== '') echo '&dia=' . $dia;
                        if ($veic !== '') echo '&veic=' . $veic;
                        if ($insp !== '') echo '&insp=' . $insp;
                        if ($eng !== '') echo '&eng=' . $eng;
                        echo '"';

                        if ($i == $pagina_atual) {
                            echo ' style="font-weight: bolder; border: 2.5px solid rgb(59, 59, 59);"';
                        }

                        echo '>' . $i . '</a>';
                    }

                    if ($pagina_final < $total_paginas) {
                        echo '<span style="color: white;">...</span>';
                        echo '<a href="?pagina=' . $total_paginas;
                        if ($dia !== '') echo '&dia=' . $dia;
                        if ($veic !== '') echo '&veic=' . $veic;
                        if ($insp !== '') echo '&insp=' . $insp;
                        if ($eng !== '') echo '&eng=' . $eng;
                        echo '">' . $total_paginas . '</a>';
                    }
                    ?>
                </div>
            
        </section>
    </main>
</body>
</html>