<?php 
    session_start();
    include_once('config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: index.php');
        exit();
    }
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addVeic'])) {
        include_once('config.php');

        if (!empty($_POST['novoVeic'])) {
            include_once('config.php');

            $novoVeic = $_POST['novoVeic'];
            $query_addVeic = "INSERT INTO veics(veic) VALUES ('$novoVeic')";
            mysqli_query($conexao, $query_addVeic);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvVeic'])) {
        include_once('config.php');

        $removerVeiculo = $_POST['removerVeiculo'];
        $query_removerVeiculo = "DELETE FROM veics WHERE veic = '$removerVeiculo'";
        mysqli_query($conexao, $query_removerVeiculo);
    }
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addInsp'])) {
        include_once('config.php');

        if (!empty($_POST['novoInsp'])) {
            include_once('config.php');

            $novoInsp = $_POST['novoInsp'];
            $query_addInsp = "INSERT INTO insp(insp) VALUES ('$novoInsp')";
            mysqli_query($conexao, $query_addInsp);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvInsp'])) {
        include_once('config.php');

        $removerInsp = $_POST['removerInsp'];
        $query_removerInsp = "DELETE FROM insp WHERE insp = '$removerInsp'";
        mysqli_query($conexao, $query_removerInsp);
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
            height: 20%;
            background-color: #9a1c1f;
            border-radius: 10px;
            padding: 8px;
            color: white;
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
            top: 24%;
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

        .addRmv {
            position: absolute;
            top: 85%;
            left: 27%;
            width: 50%;
            height: 30%;
            background-color: #9a1c1f;
            border-radius: 10px;
            padding: 8px;
            color: white;
            margin-bottom: 15px;
        }

        .addRmv div {
            padding: 10px;
            border-radius: 10px;
            height: 85%;
            background-color: white;
            width: 48%;
            text-align: center;
            color: black;
        }

        .addRmv div form {
            padding: 10px;
            height: 85%;
            width: 48%;
            background-color: lightcoral;
            text-align: center;
            border-radius: 10px;
        }

        .addRmvInputs {
            margin-top: 1.1rem;
            height: 25%;
            text-align: center;
            border-radius: 7px;
            margin-bottom: 1.2rem;
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
                <select name="veic" id="idVeic" style="margin-right: 13%;">
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
                <select name="insp" id="idInsp" style="margin-right: 13%;">
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
                <label for="eng">Valeta do engenheiro?</label>
                <select name="eng" id="idEng">
                    <option value="">Selecione</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
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
                        <th scope="col">Valeta eng?</th>
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
                                                UNION ALL
                                                SELECT *, 'valeta_b' as valeta FROM valeta_b WHERE
                                                    (dia LIKE '%$dia%')
                                                    AND (veículo LIKE '%$veic%')
                                                    AND (inspetor LIKE '%$insp%')
                                                    AND (eng LIKE '%$eng%')
                                                UNION ALL
                                                SELECT *, 'valeta_c' as valeta FROM valeta_c WHERE
                                                    (dia LIKE '%$dia%')
                                                    AND (veículo LIKE '%$veic%')
                                                    AND (inspetor LIKE '%$insp%')
                                                    AND (eng LIKE '%$eng%')
                                                UNION ALL
                                                SELECT *, 'valeta_vw' as valeta FROM valeta_vw WHERE
                                                    (dia LIKE '%$dia%')
                                                    AND (veículo LIKE '%$veic%')
                                                    AND (inspetor LIKE '%$insp%')
                                                    AND (eng LIKE '%$eng%')
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
                                                    UNION ALL
                                                    SELECT *, 'valeta_b' as valeta FROM valeta_b WHERE
                                                        (dia LIKE '%$dia%')
                                                        AND (veículo LIKE '%$veic%')
                                                        AND (inspetor LIKE '%$insp%')
                                                        AND (eng LIKE '%$eng%')
                                                    UNION ALL
                                                    SELECT *, 'valeta_c' as valeta FROM valeta_c WHERE
                                                        (dia LIKE '%$dia%')
                                                        AND (veículo LIKE '%$veic%')
                                                        AND (inspetor LIKE '%$insp%')
                                                        AND (eng LIKE '%$eng%')
                                                    UNION ALL
                                                    SELECT *, 'valeta_vw' as valeta FROM valeta_vw WHERE
                                                        (dia LIKE '%$dia%')
                                                        AND (veículo LIKE '%$veic%')
                                                        AND (inspetor LIKE '%$insp%')
                                                        AND (eng LIKE '%$eng%')
                                                ) as todas_valetas
                                                LIMIT $registros_por_pagina OFFSET $offset";

                            $result_paginacao = mysqli_query($conexao, $query_paginacao);

                            while ($row = mysqli_fetch_assoc($result_paginacao)) {
                                echo '<tr>';
                                echo '<td>' . $row['Valeta'] . '</td>'; // Mostra a valeta (tabela)
                                echo '<td>' . $row['data_formatada'] . '</td>'; // Mostra a data formatada
                                echo '<td>' . $row['veículo'] . '</td>';
                                echo '<td>' . $row['inspetor'] . '</td>';
                                echo '<td>' . $row['eng'] . '</td>';
                                echo '</tr>';
                            }
                        }
                    ?>
                    </tbody>
                </table>
                <div class="paginacao">
                    <p style="display: inline; color: white;">Páginas:</p>
                    <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                        <a href="?pagina=<?php echo $i;
                            // Adicione os parâmetros de filtro à URL dos links de paginação
                            if ($dia !== '') echo '&dia=' . $dia;
                            if ($veic !== '') echo '&veic=' . $veic;
                            if ($insp !== '') echo '&insp=' . $insp;
                            if ($eng !== '') echo '&eng=' . $eng;
                        ?>" <?php if ($i == $pagina_atual) echo 'style="font-weight: bolder;
                        border: 2.5px solid rgb(59, 59, 59);"';?> ><?php echo $i; ?></a>
                    <?php } ?>
                </div>
            </div>
        </section>
        <section class="addRmv">
            <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Users-Administrator-icon.png" width="16" height="16">Administrador</h2>
            <div style="float: left;">
                <h3>Veículos</h3>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" style="float: left;">
                    <h4>Adicionar</h4>
                    <input type="text" name="novoVeic" placeholder="Novo Veículo" style="width: 100%;" class="addRmvInputs">
                    <input type="submit" name="addVeic" value="Adicionar">
                </form>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" style="float: right;">
                    <h4>Remover</h4>
                    <select name="removerVeiculo" style="width: 100%;" class="addRmvInputs">
                        <option value="">Remover Veículo</option>
                            <?php
                                include_once('config.php');
                                $query_veiculo = "SELECT veic FROM veics";
                                $result_veiculo = mysqli_query($conexao, $query_veiculo);
                                while ($row_veic = mysqli_fetch_assoc($result_veiculo)) {
                                    echo '<option value="' . $row_veic['veic'] . '">' . $row_veic['veic'] . '</option>';
                                }
                            ?>
                    </select>
                    <input type="submit" name="rmvVeic" value="Remover">   
                </form>
            </div>
            <div style="float: right;">
                <h3>Inspetores</h3>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" style="float: left;">
                    <h4>Adicionar</h4>
                    <input type="text" name="novoInsp" placeholder="Novo Inspetor" style="width: 100%;" class="addRmvInputs">
                    <input type="submit" name="addInsp" value="Adicionar">
                </form>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post" style="float: right;">
                    <h4>Remover</h4>
                    <select name="removerInsp" style="width: 100%;" class="addRmvInputs">
                        <option value="">Remover Inspetor</option>
                            <?php
                                include_once('config.php');
                                $query_inspetor = "SELECT insp FROM insp";
                                $result_inspetor = mysqli_query($conexao, $query_inspetor);
                                while ($row_insp = mysqli_fetch_assoc($result_inspetor)) {
                                    echo '<option value="' . $row_insp['insp'] . '">' . $row_insp['insp'] . '</option>';
                                }
                            ?>
                    </select>
                    <input type="submit" name="rmvInsp" value="Remover">   
                </form>
            </div>
        </section>
    </main>
</body>
</html>