<?php 
    session_start();
    include_once('config.php');
    if (!isset($_SESSION['re']) || empty($_SESSION['re'])) {
        header('Location: index.php');
        exit();
    }
    $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/logo-zeentech.png" type="image/x-icon">
    <title>Nova OSI</title>
    <link rel="stylesheet" href="estilos/estilo.css">
    <style>
        article {
            background-color: rgba(195, 195, 195, 0.445);
            border-radius: 10px;
            padding: 15px 15px 15px 15px;
        }

        form {
            background-color: aliceblue;
        }

        h2{
            padding-bottom: 5px;
        }

        label {
            margin-left: 10px;
        }

        input[type=file] {
                border: 1px solid #001e50;
                padding: 5px;
                border-radius: 3px;
        }

        input[type=button], button {
            display: inline-block;
            width: 30px;
            height: 30px;
            margin-top: 7px;
            margin-right: 35px;
            float: right;
            border: 1px solid black;
            background-color: #4C7397;
            font-size: larger;
            color: white;
            border-radius: 4px; 
            cursor: pointer;
        }

        input[type=button]:hover, button:hover {
            background-color: #354d63;
        }

        input[type=button]:not(:hover), button:not(:hover) {
            transition-duration: 0.3s;
        }

        .inv {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <a href="https://zeentech.com/" target="_blank" class="principal"><img src="imgs/logo-zeentech.png" alt="logoZeentech" style="width: 7%; position: relative; top: 4px; margin-right: 5px;">Zeentech - OSI's</a>
        <nav>
            <ul class="menu">
                <li><a href="pagprincipal.php">Página Principal</a></li>
                <?php 
                include_once('config.php');

                $re = $_SESSION['re'];

                $query = "SELECT COUNT(*) as count FROM re_adm WHERE re = '$re'";
                $resultado = mysqli_query($conexao, $query);
                $linha = mysqli_fetch_assoc($resultado);
                $admTrue = ($linha['count'] > 0);

                if ($admTrue) {
                    echo '<li><a href="adm.php">Administrador</a></li>';
                }
                ?>
                <li><a href="sair.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1 class="bemvindo titulos">Criar Nova OSI</h1>
        <section>
            <form action="criacaoosi.php" method="POST" enctype="multipart/form-data">
                <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">1 | Dados da OSI</h2>
                <article>
                    <label for="solicitante">Solicitante:</label>
                    <input type="text" name="solic" id="idSolic" value="<?php echo $_SESSION['nome'];?>" readonly>

                    <label for="setor">Setor:</label>
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

                    <label for="tipoOSI">Tipo de OSI:</label>
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

                    <label for="dtReali">Data do serviço:</label>
                    <input type="date" name="dtReali" id="idDtReali">
                    <br>

                    <label for="eng">Engenheiro:</label>
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

                        <label for="veic">Veículo:</label>
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

                        <label for="eja">EJA:</label>
                        <select name="eja" id="idEja" readonly>
                            <option value="">Selecione</option>
                                <?php 
                                    include_once('config.php');

                                    $query_ejas = "SELECT eja FROM ejas";
                                    $result_ejas = mysqli_query($conexao, $query_ejas);

                                    while ($row_eja = mysqli_fetch_assoc($result_ejas)) {
                                        echo '<option value="' . $row_eja['eja'] . '">' . $row_eja['eja'] . '</option>';
                                    }
                                ?>
                        </select>

                        <label for="status" class="inv">Status:</label>
                        <input type="text" id="idStatus" name="status" style="display: none;" value="Pendente">

                            <!-- Input date que será preenchido automaticamente -->
                        <label for="data" class="inv">Data da OSI:</label>
                        <input type="date" id="idDtCria" name="dtCria" style="display: none;">

                        <script>
                            // Função para preencher o input date com a data atual
                            function preencherDataAtual() {
                                const dataInput = document.getElementById('idDtCria');

                                // Criar um objeto de data com a data atual
                                const dataAtual = new Date();

                                // Formatar a data no formato YYYY-MM-DD
                                const ano = dataAtual.getFullYear();
                                const mes = (dataAtual.getMonth() + 1).toString().padStart(2, '0');
                                const dia = dataAtual.getDate().toString().padStart(2, '0');
                                const dataFormatada = `${ano}-${mes}-${dia}`;

                                // Definir o valor do input date com a data atual formatada
                                dataInput.value = dataFormatada;
                            }

                            // Chamar a função quando a página for carregada
                            window.onload = preencherDataAtual;
                        </script>
                </article>
                <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">2 | Serviços a serem realizados</h2>
                <article>
                    <span id="servicos">
                        <div style="padding: 0; margin: 0; background-color: transparent;">
                            <label for="nPeca1">Nº da peça:</label>
                            <input type="text" name="nPeca1" id="idNpeca1">
                            <label for="qtd1">Quantidade:</label>
                            <input type="number" name="qtd1" id="idQtd1">
                            <label for="sist1">Sistema:</label>
                            <select name="sist1" id="idSist1">
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
                            <label for="remv1">Item à remover:</label>
                            <input type="text" name="rmv1" id="idRmv1" style="width: 10%;">
                            <label for="destRmv1">Destino dos itens removidos:</label>
                            <select name="destRmv1" id="idDestRmv1">
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
                            <label for="pdm1">PDM:</label>
                            <input type="text" name="pdm1" id="idPdm1">
                            <hr>
                        </div>
                    </span>
                    <input type="button" value="+" onclick="adicionarServico()" style="position: relative; bottom: 45px;">
                    <input type="button" value="-" onclick="removerServico()" style="position: relative; bottom: 45px;">
                </article>
                <script>
                    function adicionarServico() {
                        const servicoOriginal = document.querySelector("div:last-child", "#servicos");
                        const numServicos = document.querySelectorAll("div", "#servicos").length;

                        if (numServicos < 3) {
                            const novoServico = servicoOriginal.cloneNode(true);

                            // Atualize os IDs e names dos novos campos com base no número de serviços existentes
                            const inputs = novoServico.querySelectorAll("input, select");
                            inputs.forEach(function (element) {
                                element.value = '';
                                const originalId = element.getAttribute("id");
                                const originalName = element.getAttribute("name");

                                const newId = originalId.replace(/[0-9]+$/, numServicos + 1);
                                const newName = originalName.replace(/[0-9]+$/, numServicos + 1);

                                element.setAttribute("id", newId);
                                element.setAttribute("name", newName);
                            });

                            servicoOriginal.parentNode.insertBefore(novoServico, servicoOriginal.nextSibling);

                        } else {
                            alert("Você atingiu o número máximo de serviços permitidos (3).");
                        }
                    }
                    function removerServico() {
                        const servicos = document.querySelectorAll("div", "#servicos");

                        // Verifica se há mais de um serviço antes de permitir a remoção
                        if (servicos.length > 1) {
                            const ultimoServico = servicos[servicos.length - 1];
                            ultimoServico.remove();
                        } else {
                            alert("Você precisa manter pelo menos um serviço.");
                        }
                    }         
                </script>
                <h2><img src="https://icons.iconarchive.com/icons/icons8/ios7/16/Finance-Bill-icon.png" width="16" height="16">3 | Anexos</h2>
                <article>
                    <label for="anexo1">Anexo 1:</label>
                    <input type="file" name="anexo1" id="anexo1">

                    <label for="anexo2">Anexo 2:</label>
                    <input type="file" name="anexo2" id="anexo2">

                    <label for="anexo3">Anexo 3:</label>
                    <input type="file" name="anexo3" id="anexo3">
                </article>
                <br>
                <hr>
                <br>	
                <label for="obs" style="margin-left: 0px; background-color: #001e50; font-weight: bold; display: block;">Descrição do serviço:</label>
                <textarea name="descServ" id="idDescServ" style="margin-top: 5px; width: 100%; height: 100px;"></textarea>
                
                <hr style="margin-top: 15px;">
                <br>
                <input type="submit" value="Cria OSI">
            </form>
        </section>
    </main>
</body>
<footer>
    <h4>Zeentech - Passion for Inovation</h4>
    <h4 style="float: right;">Versão 1.0</h4>
</footer>
</html>