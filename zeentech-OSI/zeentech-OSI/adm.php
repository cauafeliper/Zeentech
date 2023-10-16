<?php 
    session_start();
    include_once('config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: index.php');
        exit();
    }
    
    $re = $_SESSION['re'];
    $query = "SELECT * FROM re_adm WHERE re = '$re'";
    $result = mysqli_query($conexao, $query);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        header('Location: sair.php');
        exit();
    }
?>

<?php
    // Lógica para adicionar novo tipo de OSI
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addSetor'])) {
        include_once('config.php');

        if (!empty($_POST['novoSetor'])) {
            include_once('config.php');

            $novoSetor = $_POST['novoSetor'];
            $query_addSetor = "INSERT INTO setores (setor) VALUES ('$novoSetor')";
            mysqli_query($conexao, $query_addSetor);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvSetor'])) {
        include_once('config.php');

        $removerSetor = $_POST['removerSetor'];
        $query_removerSetor = "DELETE FROM setores WHERE setor = '$removerSetor'";
        mysqli_query($conexao, $query_removerSetor);
    }
?>

<?php
    // Lógica para adicionar novo tipo de OSI
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTipo'])) {
        include_once('config.php');

        if (!empty($_POST['novoTipo'])) {
            include_once('config.php');

            $novoTipo = $_POST['novoTipo'];
            $query_addTipo = "INSERT INTO tipoosi(tipo) VALUES ('$novoTipo')";
            mysqli_query($conexao, $query_addTipo);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvTipo'])) {
        include_once('config.php');

        $removerTipo = $_POST['removerTipo'];
        $query_removerTipo = "DELETE FROM tipoosi WHERE tipo = '$removerTipo'";
        mysqli_query($conexao, $query_removerTipo);
    }
?>

<?php
    // Lógica para adicionar novo tipo de OSI
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addSistema'])) {
        include_once('config.php');

        if (!empty($_POST['novoSistema'])) {
            include_once('config.php');

            $novoSistema = $_POST['novoSistema'];
            $query_addSistema = "INSERT INTO sistema(sist) VALUES ('$novoSistema')";
            mysqli_query($conexao, $query_addSistema);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvSistema'])) {
        include_once('config.php');

        $removerSistema = $_POST['removerSistema'];
        $query_removerSistema = "DELETE FROM sistema WHERE sist = '$removerSistema'";
        mysqli_query($conexao, $query_removerSistema);
    }
?>

<?php
    // Lógica para adicionar novo tipo de OSI
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addDestino'])) {
        include_once('config.php');

        if (!empty($_POST['novoDestino'])) {
            include_once('config.php');

            $novoDestino = $_POST['novoDestino'];
            $query_addDestino = "INSERT INTO destrmv(dest) VALUES ('$novoDestino')";
            mysqli_query($conexao, $query_addDestino);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvDestino'])) {
        include_once('config.php');

        $removerDestino = $_POST['removerDestino'];
        $query_removerDestino = "DELETE FROM destrmv WHERE dest = '$removerDestino'";
        mysqli_query($conexao, $query_removerDestino);
    }
?>

<?php
    // Lógica para adicionar novo tipo de OSI
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEngenheiro'])) {
        include_once('config.php');

        if (!empty($_POST['novoEngenheiro'])) {
            include_once('config.php');

            $novoEngenheiro = $_POST['novoEngenheiro'];
            $novoEngEmail = $_POST['novoEngEmail'];
            $query_addEngenheiro = "INSERT INTO engs(eng, email) VALUES ('$novoEngenheiro', '$novoEngEmail')";
            mysqli_query($conexao, $query_addEngenheiro);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvEngenheiro'])) {
        include_once('config.php');

        $removerEngenheiro = $_POST['removerEngenheiro'];
        $query_removerEngenheiro = "DELETE FROM engs WHERE eng = '$removerEngenheiro'";
        mysqli_query($conexao, $query_removerEngenheiro);
    }
?>

<?php
    // Lógica para adicionar novo tipo de OSI
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addVeiculo'])) {
        include_once('config.php');

        if (!empty($_POST['novoVeiculo'])) {
            include_once('config.php');

            $novoVeiculo = $_POST['novoVeiculo'];
            $query_addVeiculo = "INSERT INTO veics(veic) VALUES ('$novoVeiculo')";
            mysqli_query($conexao, $query_addVeiculo);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvVeiculo'])) {
        include_once('config.php');

        $removerVeiculo = $_POST['removerVeiculo'];
        $query_removerVeiculo = "DELETE FROM veics WHERE veic = '$removerVeiculo'";
        mysqli_query($conexao, $query_removerVeiculo);
    }
?>

<?php
    // Lógica para adicionar novo tipo de OSI
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEJA'])) {
        include_once('config.php');

        if (!empty($_POST['novoEJA'])) {
            include_once('config.php');

            $novoEJA = $_POST['novoEJA'];
            $query_addEJA = "INSERT INTO ejas(eja) VALUES ('$novoEJA')";
            mysqli_query($conexao, $query_addEJA);
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rmvEJA'])) {
        include_once('config.php');

        $removerEJA = $_POST['removerEJA'];
        $query_removerEJA = "DELETE FROM ejas WHERE eja = '$removerEJA'";
        mysqli_query($conexao, $query_removerEJA);
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="estilos/estilo.css">
    <link rel="shortcut icon" href="imgs/logo-zeentech.png" type="image/x-icon">
    <style>
        body {
            background-color: #494949c2;
            background-image: linear-gradient(to bottom, #4C7397, #001e50);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
        }
        
        h3 {
            text-align: center;
            margin-bottom: 2px;
        }

        .addRmv {
            width: 48%;
            display: inline-block;
        }

        section {
            margin-bottom: 10px; 
            display: flex; 
            flex-wrap: wrap; 
            justify-content: center; 
            align-items: flex-start;
            padding: 20px 20px 10px 20px;
        }

        div {
            display: inline-block;
            width: 22%;
            margin: 10px;
        }

        div > form {
            display: inline-block;
        }

        form > input[type=submit] {
            padding: 8px;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <a href="https://zeentech.com/" target="_blank" class="principal"><img src="imgs/logo-zeentech.png" alt="logoZeentech" style="width: 7%; position: relative; top: 4px; margin-right: 5px;">Zeentech - OSI's</a>
        <nav>
            <ul class="menu">
                <li><a href="criarosi.php">Voltar</a></li>
                
                <li><a href="sair.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1 class="bemvindo titulos">Administrador</h1>
            <section>
                    <div>
                        <h3>Setores</h3>
                        <form method="post" class="addRmv">
                            <input type="text" name="novoSetor" placeholder="Novo Setor" style="width: 100%;">
                            <input type="submit" name="addSetor" value="Adicionar">
                        </form>
                        
                        <form method="post" class="addRmv">
                            <select name="removerSetor" style="width: 100%;">
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
                            <input type="submit" name="rmvSetor" value="Remover">
                        </form>
                    </div>
                    
                    <div>
                        <h3>Tipos de OSI</h3>
                        <form method="post" class="addRmv">
                            <input type="text" name="novoTipo" placeholder="Novo Tipo" style="width: 100%;">
                            <input type="submit" name="addTipo" value="Adicionar">
                        </form>
                        
                        <form method="post" class="addRmv">
                            <select name="removerTipo" style="width: 100%;">
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
                            <input type="submit" name="rmvTipo" value="Remover">
                        </form>
                    </div>
                    <div>
                        <h3>Sistemas</h3>
                        <form method="post" class="addRmv">
                            <input type="text" name="novoSistema" placeholder="Novo Sistema" style="width: 100%;">
                            <input type="submit" name="addSistema" value="Adicionar">
                        </form>
                        
                        <form method="post" class="addRmv">
                            <select name="removerSistema" style="width: 100%;">
                                <option value="">Selecione</option>
                                            <?php
                                                include_once('config.php');
                                                $query_sistema = "SELECT sist FROM sistema";
                                                $result_sistema = mysqli_query($conexao, $query_sistema);
                                                while ($row_sist = mysqli_fetch_assoc($result_sistema)) {
                                                    echo '<option value="' . $row_sist['sist'] . '">' . $row_sist['sist'] . '</option>';
                                                }
                                            ?>
                            </select>
                            <input type="submit" name="rmvSistema" value="Remover">
                        </form>
                    </div>
                    <div>
                        <h3>Dest. de Itens Removidos</h3>
                        <form method="post" class="addRmv">
                            <input type="text" name="novoDestino" placeholder="Novo Sistema" style="width: 100%;">
                            <input type="submit" name="addDestino" value="Adicionar">
                        </form>
                        
                        <form method="post" class="addRmv">
                            <select name="removerDestino" style="width: 100%;">
                                <option value="">Selecione</option>
                                            <?php
                                                include_once('config.php');
                                                $query_destino = "SELECT dest FROM destrmv";
                                                $result_destino = mysqli_query($conexao, $query_destino);
                                                while ($row_dest = mysqli_fetch_assoc($result_destino)) {
                                                    echo '<option value="' . $row_dest['dest'] . '">' . $row_dest['dest'] . '</option>';
                                                }
                                            ?>
                            </select>
                            <input type="submit" name="rmvDestino" value="Remover">
                        </form>
                    </div>

                    
                <div>
                    <h3>Engenheiros</h3>
                    <form method="post" class="addRmv">
                        <input type="text" name="novoEngenheiro" placeholder="Engenheiro" style="width: 48%; display: inline;">
                        <input type="text" name="novoEngEmail" placeholder="E-mail" style="width: 48%; display: inline; float: right;">
                        <input type="submit" name="addEngenheiro" value="Adicionar">
                    </form>
                    
                    <form method="post" class="addRmv">
                        <select name="removerEngenheiro" style="width: 100%;">
                            <option value="">Selecione</option>
                                        <?php
                                            include_once('config.php');
                                            $query_engenheiro = "SELECT eng FROM engs";
                                            $result_engenheiro = mysqli_query($conexao, $query_engenheiro);
                                            while ($row_eng = mysqli_fetch_assoc($result_engenheiro)) {
                                                echo '<option value="' . $row_eng['eng'] . '">' . $row_eng['eng'] . '</option>';
                                            }
                                        ?>
                        </select>
                        <input type="submit" name="rmvEngenheiro" value="Remover">
                    </form>
                </div>

                <div>
                    <h3>Veículos</h3>
                    <form method="post" class="addRmv">
                        <input type="text" name="novoVeiculo" placeholder="Novo Veículo" style="width: 100%;">
                        <input type="submit" name="addVeiculo" value="Adicionar">
                    </form>
                    
                    <form method="post" class="addRmv">
                        <select name="removerVeiculo" style="width: 100%;">
                            <option value="">Selecione</option>
                                        <?php
                                            include_once('config.php');
                                            $query_veiculo = "SELECT veic FROM veics";
                                            $result_veiculo = mysqli_query($conexao, $query_veiculo);
                                            while ($row_veic = mysqli_fetch_assoc($result_veiculo)) {
                                                echo '<option value="' . $row_veic['veic'] . '">' . $row_veic['veic'] . '</option>';
                                            }
                                        ?>
                        </select>
                        <input type="submit" name="rmvVeiculo" value="Remover">
                    </form>
                </div>

                <div>
                    <h3>EJA</h3>
                    <form method="post" class="addRmv">
                        <input type="text" name="novoEJA" placeholder="Novo EJA" style="width: 100%;">
                        <input type="submit" name="addEJA" value="Adicionar">
                    </form>
                    
                    <form method="post" class="addRmv">
                        <select name="removerEJA" style="width: 100%;">
                            <option value="">Selecione</option>
                                        <?php
                                            include_once('config.php');
                                            $query_EJA = "SELECT eja FROM ejas";
                                            $result_EJA = mysqli_query($conexao, $query_EJA);
                                            while ($row_eja = mysqli_fetch_assoc($result_EJA)) {
                                                echo '<option value="' . $row_eja['eja'] . '">' . $row_eja['eja'] . '</option>';
                                            }
                                        ?>
                        </select>
                        <input type="submit" name="rmvEJA" value="Remover">
                    </form>
                </div>
            </section>
    </main>
    <footer>
        <h4>Zeentech - Passion for Inovation</h4>
        <h4 style="float: right;">Versão 1.0</h4>
    </footer>
</body>
</html>