<?php
    include_once('config/config.php');
    session_start();

    if (!isset($_SESSION['email']) OR !isset($_SESSION['senha'])) {
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
            session_destroy();
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
    <link rel="shortcut icon" href="imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="style/style_inicial.css">
    <title>Schadenstisch | Home</title>
</head>
<body>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="imgs/truckBus.png" alt="logo-truckbus" style="height: 100%;"></a>
        <ul>
            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    <main>
        <div class="div__botao__1">
            <button class="botao__1" onclick="botao1()">
                <div class="img__botao__1">
                    <img src="imgs/dashboard.png" alt="botao_dashboard">
                    <h2>Dashboard KPM</h2>
                </div>
            </button>
        </div>

        <button class="botao__2" onclick="botao2()">
            <div>
                <img src="imgs/visu.png" alt="botao_visu">
                <h2>Tabela KPM</h2>
            </div>
        </button>
    </main>

    <footer>
        <div>
            <span>Desenvolvido por:  <img src="imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2024 de Zeentech os direitos reservados</span>
        </div>
    </footer>
    <script>
    // Função para abrir a página 1 em uma nova janela
    function botao1() {
        window.open('telas/tela_bi.php', '_blank'); // Substitua 'pagina1.html' pelo caminho da sua página
    }

    // Função para abrir a página 2 em uma nova janela
    function botao2() {
        window.location.href = "telas/tela_principal.php"; // Substitua 'pagina2.html' pelo caminho da sua página
    }
</script>
</body>
</html>