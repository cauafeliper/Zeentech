<?php
    include_once('../../config/config.php');
    session_start();

    if (!isset($_SESSION['email']) OR !isset($_SESSION['senha'])) {
        echo '<script>window.location.href = "../../index.php";</script>';
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
            echo '<script>window.location.href = "../../index.php";</script>';
            exit();
        }
        else {}
    }
?>
<?php
    // Verifica se o parâmetro item foi enviado na URL
    if(isset($_GET['item'])) {
        // Armazena o valor do item em uma variável PHP
        $item = $_GET['item'];
    }

    $query = "SELECT * FROM kpm WHERE item = '$item'";

    $result = $conexao->query($query);

    $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="style/style_apresentacao.css">
    <title>Schadenstisch | Presentation</title>
</head>
<body>
    <img src="../../imgs/logo-volks.png" alt="logo-volks" class="logo__volks">

    <img src="../../imgs/Logo Zeentech.png" alt="logo-zeentech-idt" class="logo__zeentech">
    <main>  
        <div class="bloco_1">
            <div class="div__kpm">
                <?php if(empty($row['highlight'])) {echo '-';}else { echo $row['highlight'];}?>
                <span>
                    <?= $row['n_problem']?>
                </span>
            </div>
            <div class="div__resumo">
                RESUMO DE FALHA
                <span>
                    <?= $row['resumo']?>
                </span>
            </div>
            <div class="div__programa">
                PROGRAMA
                <span>
                    <?= $row['programa']?>
                </span>
            </div>
            <div class="div__teste">
                TESTE
                <span>
                    <?= $row['teste']?>
                </span>
            </div>
            <div class="div__fg">
                FG
                <span>
                    <?= $row['fg']?>
                </span>
            </div>
            <div class="div__dias__correntes">
                KPM DIAS CO.
                <span>
                    <?php echo $row['kpm_dias_correntes'] .' DIAS';?>
                </span>
            </div>  
        </div>

<!-- ////////////////////////////////////////////// -->

        <div class="bloco_2">
            <div class="div__status__kpm">
                STATUS KPM
                <span>
                    <?= $row['status_kpm']?>
                </span>
            </div>
            <div class="div__status__reuniao">
                STATUS REUNIÃO
                <span>
                    <?= $row['status_reuniao']?>
                </span>
            </div>
            <div class="div__data__regis">
                DATA REGISTRADA
                <span>
                    <?php echo date('d/m/Y', strtotime($row['data_registrada'])); ?>
                </span>
            </div>
            <div class="div__av__crit">
                AV. CRIT.
                <span>
                    <?= $row['aval_crit']?>
                </span>
            </div>
            <div class="div__causa__kpm">
                CAUSA DO KPM
                <span>
                    <?= $row['causa']?>
                </span>
            </div>
            <div class="div__origem__kpm">
                ORIGEM KPM
                <span>
                    DUR
                </span>
            </div>
            <div class="div__veiculo">
                VEÍCULO
                <span>
                    <?= $row['veiculo']?>
                </span>
            </div>
            <div class="div__modelo">
                MODELO
                <span>
                    <?= $row['modelo']?>
                </span>
            </div>
            <div class="div__dias__reaberto">
                DIAS REABERTO
                <span>
                    -
                </span>
            </div>
            <div class="div__reabertura">
                REABERTURA DO KPM
                <span>
                    -
                </span>
            </div>
        </div>

<!-- ////////////////////////////////////////////// -->

        <div class="bloco_3">
            <div class="bloco_31">
                <div class="div__desc__info">
                    DESCRIÇÃO E INFORMAÇÕES DA FALHA
                    <span>
                        <?= $row['desc_prob']?>
                    </span>
                </div>
                <div class="bloco_31_2">
                    <div>
                        RECLAMANTE
                        <span>
                            <?php if(empty($row['reclamante'])) {
                                echo '-';
                            } else {
                                echo $row['reclamante'];
                            } ?>
                        </span>
                    </div>
                    <div>
                        PN
                        <span>
                            <?php if(empty($row['pn'])) {
                                echo '-';
                            } else {
                                echo $row['pn'];
                            } ?>
                        </span>
                    </div>
                    <div>
                        SP
                        <span>
                            <?php if(empty($row['sp'])) {
                                echo '-';
                            } else {
                                echo $row['sp'];
                            } ?>
                        </span>
                    </div>
                    <div>
                        AEKO
                        <span>
                            <?php if(empty($row['aeko'])) {
                            echo '-';
                            } else {
                                echo $row['aeko'];
                            } ?>  
                        </span>
                    </div>
                </div>
                <div class="div__remarks">
                    REMARKS
                    <span>
                        <?php if(empty($row['remarks'])) {
                            echo '-';
                        } else {
                            echo $row['remarks'];
                        } ?>
                    </span>
                </div>
                <div class="div__plano__cont">
                    PLANO DE CONTENÇÃO
                    <span>
                        .
                    </span>
                </div>
            </div>
            <!-- ////////////////////////////////////////////// -->
            <div class="bloco_32">
                <div class="bloco_32_1">
                    MINUTA
                    <span>
                        <img src="../../imgs/logo-volks-bg.png" alt="logo-background"class="img__minuta">
                        <?php if(empty($row['status_semanal'])) {
                            echo '-';
                        } else {
                            echo $row['status_semanal'];
                        } ?>
                    </span>
                </div>
                <div class="bloco_32_2">
                    <div>
                        STATUS DA AÇÃO
                        <span>
                            <?php if(empty($row['status_acao'])) {
                                echo '-';
                            } else {
                                echo $row['status_acao'];
                            } ?>
                        </span>
                    </div>
                    <div>
                        RESP. DA AÇÃO
                        <span>
                            <?php if(empty($row['resp_acao'])) {
                                echo '-';
                            } else {
                                echo $row['resp_acao'];
                            } ?>
                        </span>
                    </div>
                    <div>
                        RESP. DO KPM
                        <span>
                            <?php if(empty($row['resp'])) {
                                echo '-';
                            } else {
                                echo $row['resp'];
                            } ?>
                        </span>
                    </div>
                    <div>
                        DUE DATE
                        <span>
                            <?php echo date('d/m/Y', strtotime($row['due_date'])); ?>
                        </span>
                    </div>
                </div>
            </div>
            <!-- ////////////////////////////////////////////// -->
            <div class="bloco_33">
                INFORMAÇÕES DO TESTE
                <div>
                    PLANEJAMENTO
                    <span>
                        250.000 KM
                    </span>
                </div>
                <div>
                    TEMPO PLAN.
                    <span>
                        49 SEMANAS - CW 50
                    </span>
                </div>
                <div>
                    DAILY ACUM.
                    <span>
                        188.140 KM
                    </span>
                </div>
                <div>
                    TESTE PENDENTE
                    <span>
                        12 SEMANAS - CW 30
                    </span>
                </div>
                <div>
                    TEMPO ACUM.
                    <span>
                        96 CICLOS
                    </span>
                </div>
                <div>
                    TEMPO PENDENTE
                    <span>
                        21 SEMANAS - CW 35
                    </span>
                </div>
                <div>
                    TESTE ATÉ E-RELEASE
                    <span>
                        104 SEMANAS - XW 80
                    </span>
                </div>
                <div>
                    % ATÉ O E-RELEASE
                    <span>
                        95%
                    </span>
                </div>
                UPDATE
                <div>
                    DATA INICIO DE TESTE
                    <span>
                        29/12/2021
                    </span>
                </div>
                <div>
                    UPDATE AÇÃO
                    <span>
                        22/06/2023
                    </span>
                </div>
                <div>
                    DIAS ATÉ O UPDATE
                    <span>
                        365 DIAS
                    </span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>