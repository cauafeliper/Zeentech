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
    <link rel="shortcut icon" href="imgs/logo-zeentech.png" type="image/x-icon">
    <title>Home</title>
    <link rel="stylesheet" href="estilos/estilo.css">
    <style>
        body {
            min-width: 535px;
        }

        th {
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <a href="https://zeentech.com/" target="_blank" class="principal"><img src="imgs/logo-zeentech.png" alt="logoZeentech" style="width: 7%; position: relative; top: 4px; margin-right: 5px;">Zeentech - OSI's</a>
        <nav>
            <ul class="menu">
                <li><a href="criarosi.php">Nova OSI</a></li>
                <li><a href="sair.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1 class="bemvindo titulos">Bem vindo, <?= $_SESSION['nome']?>!</h1>
        <section>
            <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">Resumos Gerais</h2>
            <ul class="container wrap">
            <?php 
                $query = "SELECT COUNT(*) as total FROM novas_osi";
                $result = mysqli_query($conexao, $query);

                $row = mysqli_fetch_assoc($result);
                $todas = $row['total'];
            ?>
                <li class="infoPrincipal infosMaiores liResumos"><h3>Todas(<?= $todas?>)</h3>
                <img src="imgs/todas.png" alt="todas" style="display: flex-block;"><p style="font-weight: lighter; font-size:small;">Todas as OSI's criadas.</p>
                </li>
            <?php 
                $pndnt = 'Pendente';

                $query = "SELECT COUNT(*) as total_pendente FROM novas_osi WHERE stts = '$pndnt'";
                $result = mysqli_query($conexao, $query);
                
                // Verifica se a consulta foi bem-sucedida
                if ($result) {
                    // Obtém o resultado da contagem
                    $row = mysqli_fetch_assoc($result);
                    $qtd_pendente = $row['total_pendente'];
                }
            ?>
                <li class="infoAmarela liResumos"><h3><?= "$qtd_pendente";?></h3>
                    <img src="imgs/pendentes.png" alt="pendentes"><p style="font-weight: lighter; font-size:small;">OSI's com confirmação pendente.</p>
                </li>
            <?php  
                $cncld = 'Cancelada';

                $query = "SELECT COUNT(*) as total_cancelada FROM novas_osi WHERE stts = '$cncld'";
                $result = mysqli_query($conexao, $query);
                
                // Verifica se a consulta foi bem-sucedida
                if ($result) {
                    // Obtém o resultado da contagem
                    $row = mysqli_fetch_assoc($result);
                    $qtd_cancelada = $row['total_cancelada'];
                }
            ?>
                <li class="infoVermelha liResumos"><h3><?= "$qtd_cancelada";?></h3>
                    <img src="imgs/canceladas.png" alt="canceladas"><p style="font-weight: lighter; font-size:small;">OSI's Canceladas/Reprovadas.</p>
                </li>
            <?php 
                $aprvd = 'Aprovada';

                $query = "SELECT COUNT(*) as total_aprovada FROM novas_osi WHERE stts = '$aprvd'";
                $result = mysqli_query($conexao, $query);
                
                // Verifica se a consulta foi bem-sucedida
                if ($result) {
                    // Obtém o resultado da contagem
                    $row = mysqli_fetch_assoc($result);
                    $qtd_aprvd = $row['total_aprovada'];
                }
            ?>
                <li class="infoAprovadas infosMenores liResumos"><h3><?= "$qtd_aprvd";?></h3>
                    <img src="imgs/aprovadas.png" alt="aprovadas"><p style="font-weight: lighter; font-size:small;">Aprovadas e Agendadas.</p>
                </li>
            <?php 
                $andmnt = 'Em andamento';

                $query = "SELECT COUNT(*) as total_andamento FROM novas_osi WHERE stts = '$andmnt'";
                $result = mysqli_query($conexao, $query);
                
                // Verifica se a consulta foi bem-sucedida
                if ($result) {
                    // Obtém o resultado da contagem
                    $row = mysqli_fetch_assoc($result);
                    $qtd_andamento = $row['total_andamento'];
                }
            ?>
                <li class="infoAndamento infosMenores liResumos"><h3><?= "$qtd_andamento";?></h3>
                    <img src="imgs/andamento.png" alt="andamento"><p style="font-weight: lighter; font-size:small;">Em andamento.</p>
                </li>
            <?php 
                $encrrd = 'Encerrada';

                $query = "SELECT COUNT(*) as total_encerrada FROM novas_osi WHERE stts = '$encrrd'";
                $result = mysqli_query($conexao, $query);
                
                // Verifica se a consulta foi bem-sucedida
                if ($result) {
                    // Obtém o resultado da contagem
                    $row = mysqli_fetch_assoc($result);
                    $qtd_encerrada = $row['total_encerrada'];
                }
            ?>
                <li class="infoEncerradas infosMaiores liResumos"><h3><?= "$qtd_encerrada";?></h3>
                <img src="imgs/encerradas.png" alt="encerradas"><p style="font-weight: lighter; font-size:small;">Encerradas.</p>
                </li>
            </ul>
        </section>
        <section>
            <h2><img src="https://icons.iconarchive.com/icons/colebemis/feather/16/filter-icon.png" width="16" height="16">Filtros de Busca</h2>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
                <label for="numero">Número da OSI:</label>
                <input type="number" name="numeroOSI" id="idNumeroOSI">
                
                <label for="tipoOSI" style="margin-left: 10px;">Tipo de OSI:</label>
                    <select name="tipoOSI" id="idTipoOSI">
                        <option value="">Selecione</option>
                                <?php 
                                    include_once('config.php');

                                    $query_tipos = "SELECT tipo FROM tipoosi";
                                    $result_tipos = mysqli_query($conexao, $query_tipos);

                                    while ($row_tipo = mysqli_fetch_assoc($result_tipos)) {
                                        echo '<option value="' . $row_tipo['tipo'] . '">' . $row_tipo['tipo'] . '</option>';
                                    }
                                ?>
                    </select>

                <label for="status" style="margin-left: 10px;">Status:</label>
                <select name="status" id="idStatus">
                    <option value="">Selecione</option>
                    <option value="Aprovada">Aprovada</option>
                    <option value="Cancelada">Cancelada</option>
                    <option value="Pendente">Pendente</option>
                    <option value="Andamento">Andamento</option>
                    <option value="Encerrada">Encerrada</option>
                </select>

                <label for="eng" style="margin-left: 10px;">Engenheiro:</label>
                <select name="eng" id="idEng">
                    <option value="">Selecione</option>
                    <?php 
                        include_once('config.php');

                        $query_engs = "SELECT eng FROM engs";
                        $result_engs = mysqli_query($conexao, $query_engs);

                        while ($row = mysqli_fetch_assoc($result_engs)) {
                            echo '<option value="' . $row['eng'] . '">' . $row['eng'] . '</option>';
                        }
                    ?>
                </select>

                <label for="area" style="margin-left: 10px;">Área Requisitada:</label>
                <select name="setor" id="idSetor">
                        <option value="">Selecione</option>
                            <?php 
                                include_once('config.php');

                                $query_setores = "SELECT setor FROM setores";
                                $result_setores = mysqli_query($conexao, $query_setores);

                                while ($row_setor = mysqli_fetch_assoc($result_setores)) {
                                    echo '<option value="' . $row_setor['setor'] . '">' . $row_setor['setor'] . '</option>';
                                }
                            ?>
                </select>

                <label for="veic" style="margin-left: 10px;">Veículo:</label>
                        <select name="veic" id="idVeic">
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

                <hr>
                
                <input type="submit" value="Pesquisar" style="margin-top: 15px;">
            </form>
        </section>

        <section>
            <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">Lista de OS</h2>
            <div id="container" style="padding-bottom: 0;">
                <table>
                    <thead>
                        <th scope="col"><img src="https://icons.iconarchive.com/icons/ionic/ionicons/16/options-icon.png" width="16" height="16"></th>
                        <th scope="col">OS</th>
                        <th scope="col">Veículo</th>
                        <th scope="col">EJA</th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Data da Solicitação</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Status</th>
                        <th scope="col">Engenheiro</th>
                        <th scope="col">Data do Serviço</th>
                    </thead>
                    <tbody>
                    <?php 
                            $registros_por_pagina = 10;
                            $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                            
                            $numeroOSI = isset($_GET['numeroOSI']) ? $_GET['numeroOSI'] : '';
                            $tipoOSI = isset($_GET['tipoOSI']) ? $_GET['tipoOSI'] : '';
                            $status = isset($_GET['status']) ? $_GET['status'] : '';
                            $eng = isset($_GET['eng']) ? $_GET['eng'] : '';
                            $setor = isset($_GET['setor']) ? $_GET['setor'] : '';
                            $veic = isset($_GET['veic']) ? $_GET['veic'] : '';
                            
                            $query_count = "SELECT COUNT(*) as total FROM novas_osi WHERE
                                            (id LIKE '%$numeroOSI%')
                                            AND (tipo LIKE '%$tipoOSI%')
                                            AND (stts LIKE '%$status%')
                                            AND (eng LIKE '%$eng%')
                                            AND (setor LIKE '%$setor%')
                                            AND (veic LIKE '%$veic%')";
                            $result_count = mysqli_query($conexao, $query_count);
                            $total_registros = mysqli_fetch_assoc($result_count)['total'];
                            
                            $total_paginas = ceil($total_registros / $registros_por_pagina);
                            
                            $offset = ($pagina_atual - 1) * $registros_por_pagina;
                            
                            $query_paginacao = "SELECT * FROM novas_osi WHERE
                                                (id LIKE '%$numeroOSI%')
                                                AND (tipo LIKE '%$tipoOSI%')
                                                AND (stts LIKE '%$status%')
                                                AND (eng LIKE '%$eng%')
                                                AND (setor LIKE '%$setor%')
                                                AND (veic LIKE '%$veic%')
                                                LIMIT $registros_por_pagina OFFSET $offset";
                            $result_paginacao = mysqli_query($conexao, $query_paginacao);

                            while ($row = mysqli_fetch_assoc($result_paginacao)) { ?>
                                <tr>
                                <td style="width: 5%; background-color: rgba(195, 195, 195, 0.445);">
                                    <select name="acoes" id="idAcoes" class="selectTabela" onchange="handleSelectChange(this)">
                                        <option value="0" disabled selected>Selecione uma ação</option>  	                            
                                        <option value="1">Visualizar OS</option>
                                        <option value="2">Baixar OS</option>
                                    </select>
                                </td>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['veic']; ?></td>
                                    <td><?php echo $row['eja']; ?></td>
                                    <td><?php echo $row['solicitante']; ?></td>
                                    <td><?php echo $row['dtCria']; ?></td>
                                    <td><?php echo $row['tipo']; ?></td>
                                    <td><?php echo $row['stts']; ?></td>
                                    <td><?php echo $row['eng']; ?></td>
                                    <td><?php echo $row['dtReali']; ?></td>
                                 </tr>
                            <?php } ?>
                    </tbody>
                </table>

                <div class="paginacao">
                    <p style="display: inline; color: white;">Páginas:</p>
                    <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                        <a href="?pagina=<?php echo $i;
                            // Adicione os parâmetros de filtro à URL dos links de paginação
                            if ($numeroOSI !== '') echo '&numeroOSI=' . $numeroOSI;
                            if ($tipoOSI !== '') echo '&tipoOSI=' . $tipoOSI;
                            if ($status !== '') echo '&status=' . $status;
                            if ($eng !== '') echo '&eng=' . $eng;
                            if ($setor !== '') echo '&setor=' . $setor;
                            if ($veic !== '') echo '&veic=' . $veic;
                        ?>" <?php if ($i == $pagina_atual) echo 'style="font-weight: bolder;
                        border: 2.5px solid rgb(59, 59, 59);"';?> ><?php echo $i; ?></a>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>
    <footer>
                <h4>Zeentech - Passion for Inovation</h4>
                <h4 style="float: right;">Versão 1.0</h4>
    </footer>
    <script>
    function handleSelectChange(select) {
        var selectedOption = select.options[select.selectedIndex].value;
        var row = select.parentNode.parentNode;
        var osId = row.cells[1].textContent; // ID da OS da linha selecionada
        
            if (selectedOption === '1') {
                // Abrir a página de visualização em uma nova janela
                window.open('visualizar_os.php?id=' + osId, '_blank');
            } else if (selectedOption === '2') {
                // Baixar o arquivo Excel da OS com o ID da OS
                var downloadLink = 'excel/OSI_' + osId + '.xlsx';
                window.location.href = downloadLink;
            }
        }
    </script>
</body>
</html>