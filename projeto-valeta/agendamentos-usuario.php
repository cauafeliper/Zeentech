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
    <link rel="stylesheet" href="estilos/style-tabela.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
    <style>
        .filtro {
            position: absolute;
            right: 8%;
            top: 2%;
            width: 80%;
            height: 160px;
            background-color: #9a1c1f;
            border-radius: 10px;
            padding: 8px;
            color: white;
        }

        .select_veic {
            margin-right: 15%;
        }

        .select_insp {
            margin-right: 15%;
        }

        .filtro form {
            padding: 10px;
            border-radius: 10px;
            height: 75%;
            background-color: white;
        }

        .filtro form label {
            background-color: #9A1C1F;
            border-radius: 5px;
            padding: 3px;
        }

        .filtro select {
            width: 15%;
            height: 35%;
            border-radius: 5px;
        }

        form hr {
            margin: 10px;
        }

        input[type=submit] {
            width: 100%;
            height: 35px;
            background-color: #9A1C1F;
            color: white;
            font-weight: bold;
            font-size: large;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            border: none;
        }

        .tabela {
            position: absolute;
            top: 28%;
            right: 8%;
            width: 80%;
            background-color: #9a1c1f;
            border-radius: 10px;
            padding: 10px;
            color: white;
        }

        table {
            background-color: white;
            border-radius: 10px;
            color: black;
            margin-bottom: 10px;
        }

        .paginacao {
            margin: 5px;
            border-radius: 5px;
        }

        .paginacao a {
            color: white;
            padding-left: 3px;
            padding-right: 3px;
            border-radius: 3px;
        }

    @media(max-width: 1033px){
        .filtro {
            position: absolute;
            right: 3%;
            top: 2%;
            width: 80%;
            height: 200px;
        }

        .filtro form {
            height: 75%;
            background-color: white;
        }

        .filtro form label {
            background-color: #9A1C1F;
            border-radius: 5px;
            padding: 3px;
        }

        .filtro select {
            width: 25%;
            height: 27%;
            margin-bottom: 5px;
        }

        .select_veic {
            margin-right: 0%;
        }

        .select_insp {
            margin-right: 0%;
        }

        form hr {
            margin: 10px;
        }

        input[type=submit] {
            width: 100%;
            height: 35px;
            background-color: #9A1C1F;
            color: white;
            font-weight: bold;
            font-size: large;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            border: none;
        }

        .tabela {
            position: absolute;
            top: 35%;
            right: 1%;
            width: 83%;
            background-color: #9a1c1f;
            border-radius: 10px;
            padding: 10px;
            color: white;
            overflow-x: auto;
        }

        table {
            background-color: white;
            border-radius: 10px;
            color: black;
            margin-bottom: 10px;
            width: 470px;
        }

        .paginacao {
            margin: 5px;
            border-radius: 5px;
        }

        .paginacao a {
            color: white;
            padding-left: 3px;
            padding-right: 3px;
            border-radius: 3px;
        }

        .sidebar {
            width: 15vw;
        }

        .sidebar__imgs {
            height: 12%;
        }
    }

    @media(max-width: 469px) {
        .filtro {
            position: absolute;
            right: 3%;
            top: 2%;
            width: 83%;
            height: 300px;
        }

        .filtro form {
            height: 85%;
            background-color: white;
        }

        .filtro form label {
            background-color: #9A1C1F;
            border-radius: 5px;
            padding: 3px;
            margin-bottom: 8px;
        }

        .filtro select {
            width: 100%;
            height: 13%;
            margin-bottom: 5px;
            margin-top: 2px;
        }

        .select_veic {
            margin-right: 0;
            width: 100%;
        }

        .select_insp {
            margin-right: 0;
            width: 100%;
        }

        .select_data {
            margin-right: 0;
            margin-top: 2px;
            width: 100%;
            height: 13%;
            border-radius: 5px;
            border: 0.5px solid gray;
        }

        form hr {
            margin: 7px;
        }

        input[type=submit] {
            width: 100%;
            height: 35px;
            background-color: #9A1C1F;
            color: white;
            font-weight: bold;
            font-size: large;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            border: none;
        }

        .tabela {
            position: absolute;
            top: 50%;
            right: 5px;
            width: 86.5%;
            background-color: #9a1c1f;
            border-radius: 10px;
            padding: 10px;
            color: white;
            overflow-x: auto;
        }

        table {
            background-color: white;
            border-radius: 10px;
            color: black;
            margin-bottom: 10px;
            width: 470px;
        }

        .paginacao {
            margin: 5px;
            border-radius: 5px;
        }

        .paginacao a {
            color: white;
            padding-left: 3px;
            padding-right: 3px;
            border-radius: 3px;
        }

        .sidebar {
            width: 12vw;
        }

        .sidebar__imgs {
            height: 10%;
        }

        .sidebar__imgs img {
            width: 100%;
        }
    }

    @media(max-width: 400px) {
        .filtro {
            position: absolute;
            right: 3%;
            top: 2%;
            width: 83%;
            height: 300px;
        }

        .filtro form {
            height: 85%;
            background-color: white;
        }

        .filtro form label {
            background-color: #9A1C1F;
            border-radius: 5px;
            padding: 3px;
            margin-bottom: 8px;
        }

        .filtro select {
            width: 100%;
            height: 13%;
            margin-bottom: 5px;
            margin-top: 2px;
        }

        .select_veic {
            margin-right: 0;
            width: 100%;
        }

        .select_insp {
            margin-right: 0;
            width: 100%;
        }

        .select_data {
            margin-right: 0;
            margin-top: 2px;
            width: 100%;
            height: 13%;
            border-radius: 5px;
            border: 0.5px solid gray;
        }

        form hr {
            margin: 7px;
        }

        input[type=submit] {
            width: 100%;
            height: 35px;
            background-color: #9A1C1F;
            color: white;
            font-weight: bold;
            font-size: large;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            border: none;
        }

        .tabela {
            position: absolute;
            top: 50%;
            right: 5px;
            width: 86.5%;
            background-color: #9a1c1f;
            border-radius: 10px;
            padding: 10px;
            color: white;
            overflow-x: auto;
        }

        table {
            background-color: white;
            border-radius: 10px;
            color: black;
            margin-bottom: 10px;
            width: 470px;
        }

        .paginacao {
            margin: 5px;
            border-radius: 5px;
        }

        .paginacao a {
            color: white;
            padding-left: 3px;
            padding-right: 3px;
            border-radius: 3px;
        }

        .sidebar {
            width: 12vw;
        }

        .sidebar__imgs {
            height: 10%;
        }

        .sidebar__imgs img {
            width: 100%;
        }
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar__content">
            <a href="tabela-agendamentos.php" class="sidebar__imgs" style="margin-top: 40px;">
                <abbr title="Tabela de Agendamentos.">
                    <div>
                        <img src="imgs/calendario.png" alt="icon-teste">
                    </div>
                    <span>Horários</span>
                </abbr>
            </a>
            <a href="agendamento/agendamento-valeta.php" class="sidebar__imgs" style="margin-top: 40px;">
                <abbr title="Tabela de Agendamentos.">
                    <div>
                        <img src="imgs/form.png" alt="icon-teste">
                    </div>
                    <span>Agendar</span>
                </abbr>
            </a>
            <a href="cadastro-login/sair.php" class="sidebar__sair">
                <button style="position: relative; top: 38%; background-color: transparent; border: none;color: white; font-weight: bold;">
                    Sair
                </button>
            </a>
        </div>
    </div>
    <main>
        <div class="filtro">
            <h2><img src="https://icons.iconarchive.com/icons/colebemis/feather/16/filter-icon.png" width="16" height="16">Filtros de Busca</h2>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
                <label for="veic">Veículo:</label>
                <select name="veic" id="idVeic" class="select_veic">
                    <option value="">Selecione</option>
                        <?php 
                            include_once('config.php');

                            $query_veics = "SELECT veic FROM veics";
                            $result_veics = mysqli_query($conexao, $query_veics);

                            while ($row_veic = mysqli_fetch_assoc($result_veics)) {
                                echo '<option value="' . $row_veic['veic'] . '">' . $row_veic['veic'] . '</option>';
                            }
                        ?>
                </select>
                <label for="inspetor">Inspetor:</label>
                <select name="insp" id="idInsp" class="select_insp">
                    <option value="">Selecione</option>
                        <?php 
                            include_once('config.php');

                            $query_insp = "SELECT insp FROM insp";
                            $result_insp = mysqli_query($conexao, $query_insp);

                            while ($row_insp = mysqli_fetch_assoc($result_insp)) {
                                echo '<option value="' . $row_insp['insp'] . '">' . $row_insp['insp'] . '</option>';
                           }
                        ?>
                </select>
                <label for="dia">Data:</label>
                <input type="date" name="dia" id="idDia" class="select_data">
                <hr>
                <input type="submit" value="Pesquisar">
            </form>
        </div>
        <section class="tabela">
            <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">Tabela de Agendamentos</h2>
            <div id="container" style="padding-bottom: 0;">
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
                <div class="paginacao">
                    <p style="display: inline; color: white;">Páginas:</p>

                    <?php
                    $num_paginas_visiveis = 5;

                    // Calcula a página inicial e final com base na página atual
                    $pagina_inicial = max(1, $pagina_atual - floor($num_paginas_visiveis / 2));
                    $pagina_final = min($total_paginas, $pagina_inicial + $num_paginas_visiveis - 1);

                    // Ajusta a página inicial se necessário
                    $pagina_inicial = max(1, $pagina_final - $num_paginas_visiveis + 1);

                    // Adiciona link para a primeira página
                    if ($pagina_inicial > 1) {
                        echo '<a href="?pagina=1';
                        if ($dia !== '') echo '&dia=' . $dia;
                        if ($veic !== '') echo '&veic=' . $veic;
                        if ($insp !== '') echo '&insp=' . $insp;
                        if ($eng !== '') echo '&eng=' . $eng;
                        echo '">1</a>';
                        echo '<span style="color: white;">...</span>';
                    }

                    // Adiciona links para as páginas visíveis
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

                    // Adiciona link para a última página
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
            </div>
        </section>
    </main>
</body>
</html>