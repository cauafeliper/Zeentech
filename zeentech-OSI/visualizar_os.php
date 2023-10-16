<?php
    session_start();
    include_once('config.php');

    if(isset($_GET['id'])) {
            $idOSI = $_GET['id'];

            $query_osi = "SELECT * FROM novas_osi WHERE id = $idOSI";
            $result_osi = mysqli_query($conexao, $query_osi);
            $row_osi = mysqli_fetch_assoc($result_osi);

            $nOsi = $row_osi['id'];
            $solic = $row_osi['solicitante'];
            $setor = $row_osi['setor'];
            $tipo = $row_osi['tipo'];
            $dtReali = $row_osi['dtReali'];
            $eng = $row_osi['eng'];
            $veic = $row_osi['veic'];
            $eja = $row_osi['eja'];
            $dtCria = $row_osi['dtCria'];
            $numPc1 = $row_osi['numPc1'];
            $qtd1 = $row_osi['qtd1'];
            $sist1 = $row_osi['sist1'];
            $rmv1 = $row_osi['rmv1'];
            $destRmv1 = $row_osi['destRmv1'];
            $pdm1 = $row_osi['pdm1'];
            $numPc2 = $row_osi['numPc2'];
            $qtd2 = $row_osi['qtd2'];
            $sist2 = $row_osi['sist2'];
            $rmv2 = $row_osi['rmv2'];
            $destRmv2 = $row_osi['destRmv2'];
            $pdm2 = $row_osi['pdm2'];
            $numPc3 = $row_osi['numPc3'];
            $qtd3 = $row_osi['qtd3'];
            $sist3 = $row_osi['sist3'];
            $rmv3 = $row_osi['rmv3'];
            $destRmv3 = $row_osi['destRmv3'];
            $pdm3 = $row_osi['pdm3'];
            $descServ = $row_osi['descServ'];
            $stts = $row_osi['stts'];
        }

        $allowed_extensions = ['png', 'jpg', 'pdf', 'xlsx', 'pptx'];

        function getFilePath($nOsi, $extension, $anexoNum) {
            $directory = "anexos/OSI_{$nOsi}/";
            $filename = "A{$anexoNum}_OSI_{$nOsi}.{$extension}";
            $filepath = $directory . $filename;

            if (file_exists($filepath)) {
                return $filepath;
            }

            return null;
        }

        $anexo1 = null;
        $anexo2 = null;
        $anexo3 = null;

        foreach ($allowed_extensions as $extension) {
            if (!$anexo1) {
                $anexo1 = getFilePath($nOsi, $extension, 1);
            }

            if (!$anexo2) {
                $anexo2 = getFilePath($nOsi, $extension, 2);
            }

            if (!$anexo3) {
                $anexo3 = getFilePath($nOsi, $extension, 3);
            }
        }

    $query_eng = "SELECT email FROM engs WHERE eng = '$eng'";
    $result_eng = mysqli_query($conexao, $query_eng);
    $row_eng = mysqli_fetch_assoc($result_eng);
    $eng_email = $row_eng['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar OSI</title>
    <link rel="stylesheet" href="estilos/estilo.css">
    <link rel="shortcut icon" href="imgs/logo-zeentech.png" type="image/x-icon">
    <style>
        body {
            background-image: url('imgs/background.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            min-width: 745px;
        }

        .botao:hover {
            background-color: transparent;
            border-radius: 5px;
            pointer-events: fill;
            }

        .encerrar {
            color: white; 
            background-color: #001e50; 
            font-size: larger;
            width: 100%; 
            align-items: center; 
            height: 50px; 
            border-radius: 5px; 
            border: 1px solid black;
            margin-bottom: 7px;
        }

        .encerrar:hover {
            background-color: #001538;
        }

        .andamento {
            color: white; 
            background-color: #4C7397; 
            font-size: larger; 
            width: 100%; 
            align-items: center; 
            height: 50px; 
            border-radius: 5px; 
            border: 1px solid black;
            margin-bottom: 7px;
        }

        .andamento:hover {
            background-color: #37536d;
        }
        
        .cancelar {
            background-color: #EE6352; 
            width: 48%; 
            float: right; 
            height: 50px; 
            border-radius: 5px; 
            border: 1px solid black;
            margin-bottom: 7px;
        }

        .botao-cancelar {
            font-size: x-large;
            color: white;
            background-color: transparent;
            border: none;
            display: inline;
            position: relative;
            left: 42%;
            top: 20%;
        }

        .cancelar:hover {
            background-color: darkred;
            border-radius: 5px;
            pointer-events: fill;
        }

        .aprovar {
            color: white; 
            background-color: #57A773; 
            font-size: larger; 
            width: 48%; 
            float: left; 
            height: 50px; 
            border-radius: 5px; 
            border: 1px solid black;
            margin-bottom: 7px;
        }

        .botao-aprovar {
            font-size: x-large;
            color: white;
            background-color: transparent;
            border: none;
            display: inline;
            position: relative;
            left: 42%;
            top: 20%;
        }

        .aprovar:hover {
            background-color: darkgreen;
            border-radius: 5px;
            pointer-events: fill;
        }

        header {
            background-color: rgba(195, 195, 195, 0.445);
            border-radius: 10px;
            padding: 15px 15px 15px 15px;
            margin-bottom: 5px;
            width: 100%;
        }
    </style>
</head>
<body onload="ajustaZoom()">
        <section style="display: flex; flex-direction: column; flex-wrap: nowrap; justify-content: center; position: relative; top: 15px; width: 80%; margin: auto;">
            <?php 
                if ($_SESSION['email'] === $eng_email) {
                    if ($stts === 'Pendente') {
                    echo '<header>
                        <a href="aprovar_osi.php?id=' . $nOsi . '" class="aprovar"><button type="button" class="botao-aprovar">Aprovar</button></a>
                        <a href="cancelar_osi.php?id=' . $nOsi . '" class="cancelar"><button type="button" class="botao-cancelar">Cancelar</button></a>
                    </header>';
                     }
                     elseif ($stts === 'Aprovada') {
                    echo '<header>
                        <a href="iniciar_osi.php?id=' . $nOsi . '" class="andamento"><button type="button" class="andamento">Iniciar Processo</button></a>
                    </header>';
                     }
                     elseif ($stts === 'Em andamento'){
                    echo '
                        <a href="encerrar_osi.php?id=' . $nOsi . '"><button type="button" class="encerrar">Encerrar Processo</button></a>
                    ';
                     }
                     elseif ($stts === 'Cancelada'){}
                     
                     elseif ($stts === 'Encerrada'){}
                }
            else {}
            ?>
            <form action="" style="padding-top: 5px;">
                <article>
                    <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">Dados da OSI</h2>

                        <label for="n">Nº da OSI:</label>
                        <input type="number" name="nOsi" id="idNosi" value="<?= $nOsi?>" readonly>

                        <label for="solicitante">Solicitante:</label>
                        <input type="text" name="solic" id="idSolic" value="<?= $solic?>" readonly>

                        <label for="setor">Setor:</label>
                        <input type="text" name="setor" id="idSetor" value="<?= $setor?>" readonly>

                        <label for="tipoOSI">Tipo de OSI:</label>
                        <input type="text" name="tipo" id="idTipo" value="<?= $tipo?>" readonly>
                        
                        <br>

                        <label for="dtReali">Data do serviço:</label>
                        <input type="text" name="dtReali" id="idDtReali" value="<?= $dtReali?>" readonly>
                    

                        <label for="eng">Engenheiro:</label>
                        <input type="text" name="eng" id="idEng" value="<?= $eng?>" readonly>

                        <label for="veic">Veículo:</label>
                        <input type="text" name="veic" id="idVeic" value="<?= $veic?>" readonly>

                        <label for="eja">EJA:</label>
                        <input type="text" name="eja" id="idEja" value="<?= $eja?>" readonly>

                        <br>

                        <label for="data">Data da OSI:</label>
                        <input type="date" id="idDtSolic" name="dtSolic" value="<?= $dtCria?>" readonly>

                        <label for="status">Status:</label>
                        <?php if($stts === 'Cancelada'){ echo "<input type=\"text\" name=\"pdm\" id=\"idPdm\" value=\"$stts\" readonly style=\"background-color: red; color: white;\">";} else{ echo "<input type=\"text\" name=\"pdm\" id=\"idPdm\" value=\"$stts\" readonly>";}?>

                        <hr>

                        <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">Peças</h2>

                        <label for="nPeca">Nº da peça:</label>
                        <input type="text" name="nPeca1" id="idNpeca1" value="<?= $numPc1?>" readonly>

                        <label for="qtd">Quantidade:</label>
                        <input type="number" name="qtd1" id="idQtd1" value="<?= $qtd1?>" readonly>

                        <label for="sist">Sistema:</label>
                        <input type="text" name="sist1" id="idSist1" value="<?= $sist1?>" readonly>

                        <label for="remv">Item à remover:</label>
                        <input type="text" name="rmv1" id="idRmv1" value="<?= $rmv1?>" readonly>

                        <label for="destRmv">Destino do item removido:</label>
                        <input type="text" name="destRmv1" id="idDestRmv1" value="<?= $destRmv1?>" readonly>
                        
                        <label for="pdm">PDM:</label>
                        <input type="text" name="pdm1" id="idPdm1" value="<?= $pdm1?>" readonly>

                        <br>
                        <br>

                        <label for="nPeca">Nº da peça:</label>
                        <input type="text" name="nPeca2" id="idNpeca2" value="<?= $numPc2?>" readonly>

                        <label for="qtd">Quantidade:</label>
                        <input type="number" name="qtd2" id="idQtd2" value="<?= $qtd2?>" readonly>

                        <label for="sist">Sistema:</label>
                        <input type="text" name="sist2" id="idSist2" value="<?= $sist2?>" readonly>

                        <label for="remv">Item à remover:</label>
                        <input type="text" name="rmv2" id="idRmv2" value="<?= $rmv2?>" readonly>

                        <label for="destRmv">Destino do item removido:</label>
                        <input type="text" name="destRmv2" id="idDestRmv2" value="<?= $destRmv2?>" readonly>
                        
                        <label for="pdm">PDM:</label>
                        <input type="text" name="pdm2" id="idPdm2" value="<?= $pdm2?>" readonly>

                        <br>
                        <br>

                        <label for="nPeca">Nº da peça:</label>
                        <input type="text" name="nPeca3" id="idNpeca3" value="<?= $numPc3?>" readonly>

                        <label for="qtd">Quantidade:</label>
                        <input type="number" name="qtd3" id="idQtd3" value="<?= $qtd3?>" readonly>

                        <label for="sist">Sistema:</label>
                        <input type="text" name="sist3" id="idSist3" value="<?= $sist3?>" readonly>

                        <label for="remv">Item à remover:</label>
                        <input type="text" name="rmv3" id="idRmv3" value="<?= $rmv3?>" readonly>

                        <label for="destRmv">Destino do item removido:</label>
                        <input type="text" name="destRmv3" id="idDestRmv3" value="<?= $destRmv3?>" readonly>
                        
                        <label for="pdm">PDM:</label>
                        <input type="text" name="pdm3" id="idPdm3" value="<?= $pdm3?>" readonly>
                    </article>
                    <br>
                    <hr>
                    <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">Anexos</h2>
                    <article>
                        <label for="anexo1">Anexo 1:</label>
                        <?php if (!empty($anexo1)) { ?>
                            <a href="<?php echo $anexo1; ?>" download>
                                <img src="https://icons.iconarchive.com/icons/custom-icon-design/mono-general-2/64/document-icon.png" width="50" height="50" style="position: relative; top: 17px;">
                            </a>
                        <?php } else { ?>
                            Nenhum anexo disponível
                        <?php } ?>

                        <label for="anexo2" style="margin-left: 50px;">Anexo 2:</label>
                        <?php if (!empty($anexo2)) { ?>
                            <a href="<?php echo $anexo2; ?>" download>
                                <img src="https://icons.iconarchive.com/icons/custom-icon-design/mono-general-2/64/document-icon.png" width="50" height="50" style="position: relative; top: 17px;">
                            </a>
                        <?php } else { ?>
                            Nenhum anexo disponível
                        <?php } ?>

                        <label for="anexo3" style="margin-left: 50px;">Anexo 3:</label>
                        <?php if (!empty($anexo3)) { ?>
                            <a href="<?php echo $anexo3; ?>" download>
                                <img src="https://icons.iconarchive.com/icons/custom-icon-design/mono-general-2/64/document-icon.png" width="50" height="50" style="position: relative; top: 17px;">
                            </a>
                        <?php } else { ?>
                            Nenhum anexo disponível
                        <?php } ?>
                    </article>
                <br>
                <hr>
                <br>	
                <label for="obs" style="margin-left: 0px; background-color: #001e50; font-weight: bold; display: block;">Descrição do serviço:</label>
                <textarea name="obs" id="idObs" style="margin-top: 5px; width: 100%; height: 100px;" readonly><?= $descServ ?></textarea>
            </form>
    </section>
    <script type="text/javascript">
        function ajustaZoom() {
            document.body.style.zoom = "90%" 
        }

        window.addEventListener('load', function() {
            setTimeout(function() {
                window.scrollTo(0, 0);
            }, 100);
        });
    </script>
</body>
</html>