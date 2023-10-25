<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/style-principal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php 
        session_start();
        include_once('../config/config.php');
        if (!isset($_SESSION['chapa']) || empty($_SESSION['chapa'])) {
            session_unset();
            header('Location: ../index.php');
        }
        
        $chapa = $_SESSION['chapa'];
        $query = "SELECT * FROM chapa_adm WHERE chapa = '$chapa'";
        $result = mysqli_query($conexao, $query);
        
        if (!$result || mysqli_num_rows($result) === 0) {
            session_unset();
            header('Location: ../index.php');
        }
    ?>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus" style="height: 95%;"></a>
        <ul>
            <li><a href="tabela-agendamentos.php">Voltar</a></li>'

            <li><a href="sair.php">Sair</a></li>
        </ul>
    </header>
    
    <main>
        
    </main>
    
    <footer>
        <div>
            <span>Desenvolvido por:  <img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2023 de Zeentech os direitos reservados</span>
        </div>
    </footer>
</body>
</html>