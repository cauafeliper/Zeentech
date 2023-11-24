<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico | Agendamentos</title>
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="../estilos/grafico.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-bundle.min.js"></script>
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
            <li><a href="../agendamento/gestor.php">Gestão</a></li>

            <li><a href="../agendamento/tabela-agendamentos.php">Início</a></li>

            <li><a href="../agendamento/sair.php">Sair</a></li>
        </ul>
    </header>
        <main>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="container filtro__dia">
                <div class="titulo"><h3>Agendamento por Área e Dia</h3></div>
                <div class="label"><label for="dia">Dia:</label></div>
                <div class="input"><input type="date" name="dia" id="dia" required></div>
                <div class="submit"><input type="submit" value="Filtrar"></div>
            </form>
            <div class="container">
                <div class="tit">
                    <div class="a1"></div>
                    <div class="a2"></div>
                    <div class="a3"></div>
                    <div class="a4"></div>
                    <div class="a5"></div>
                    <div class="a6"></div>
                    <div class="a7"></div>
                    <div class="a8"></div>
                    <div class="a9"></div>
                    <div class="a10"></div>
                    <div class="a11"></div>
                    <div class="a12"></div>
                    <div class="a13"></div>
                    <div class="a14"></div>
                </div>
                <div class="vda">
                    <div class="b1"></div>
                    <div class="b2"></div>
                    <div class="b3"></div>
                    <div class="b4"></div>
                    <div class="b5"></div>
                    <div class="b6"></div>
                    <div class="b7"></div>
                    <div class="b8"></div>
                    <div class="b9"></div>
                    <div class="b10"></div>
                    <div class="b11"></div>
                    <div class="b12"></div>
                    <div class="b13"></div>
                    <div class="b14"></div>
                </div>
                <div class="nvh">
                    <div class="c1"></div>
                    <div class="c2"></div>
                    <div class="c3"></div>
                    <div class="c4"></div>
                    <div class="c5"></div>
                    <div class="c6"></div>
                    <div class="c7"></div>
                    <div class="c8"></div>
                    <div class="c9"></div>
                    <div class="c10"></div>
                    <div class="c11"></div>
                    <div class="c12"></div>
                    <div class="c13"></div>
                    <div class="c14"></div>
                </div>
                <div class="obs">
                    <div class="d1"></div>
                    <div class="d2"></div>
                    <div class="d3"></div>
                    <div class="d4"></div>
                    <div class="d5"></div>
                    <div class="d6"></div>
                    <div class="d7"></div>
                    <div class="d8"></div>
                    <div class="d9"></div>
                    <div class="d10"></div>
                    <div class="d11"></div>
                    <div class="d12"></div>
                    <div class="d13"></div>
                    <div class="d14"></div>
                </div>
                <div class="r_12_20">
                    <div class="e1"></div>
                    <div class="e2"></div>
                    <div class="e3"></div>
                    <div class="e4"></div>
                    <div class="e5"></div>
                    <div class="e6"></div>
                    <div class="e7"></div>
                    <div class="e8"></div>
                    <div class="e9"></div>
                    <div class="e10"></div>
                    <div class="e11"></div>
                    <div class="e12"></div>
                    <div class="e13"></div>
                    <div class="e14"></div>
                </div>
                <div class="r_40">
                    <div class="f1"></div>
                    <div class="f2"></div>
                    <div class="f3"></div>
                    <div class="f4"></div>
                    <div class="f5"></div>
                    <div class="f6"></div>
                    <div class="f7"></div>
                    <div class="f8"></div>
                    <div class="f9"></div>
                    <div class="f10"></div>
                    <div class="f11"></div>
                    <div class="f12"></div>
                    <div class="f13"></div>
                    <div class="f14"></div>
                </div>
                <div class="r_60">
                    <div class="g1"></div>
                    <div class="g2"></div>
                    <div class="g3"></div>
                    <div class="g4"></div>
                    <div class="g5"></div>
                    <div class="g6"></div>
                    <div class="g7"></div>
                    <div class="g8"></div>
                    <div class="g9"></div>
                    <div class="g10"></div>
                    <div class="g11"></div>
                    <div class="g12"></div>
                    <div class="g13"></div>
                    <div class="g14"></div>
                </div>
                <div class="asf">
                    <div class="h1"></div>
                    <div class="h2"></div>
                    <div class="h3"></div>
                    <div class="h4"></div>
                    <div class="h5"></div>
                    <div class="h6"></div>
                    <div class="h7"></div>
                    <div class="h8"></div>
                    <div class="h9"></div>
                    <div class="h10"></div>
                    <div class="h11"></div>
                    <div class="h12"></div>
                    <div class="h13"></div>
                    <div class="h14"></div>
                </div>
                <div class="pc">
                    <div class="i1"></div>
                    <div class="i2"></div>
                    <div class="i3"></div>
                    <div class="i4"></div>
                    <div class="i5"></div>
                    <div class="i6"></div>
                    <div class="i7"></div>
                    <div class="i8"></div>
                    <div class="i9"></div>
                    <div class="i10"></div>
                    <div class="i11"></div>
                    <div class="i12"></div>
                    <div class="i13"></div>
                    <div class="i14"></div>
                </div>
                <div class="scl">
                    <div class="j1"></div>
                    <div class="j2"></div>
                    <div class="j3"></div>
                    <div class="j4"></div>
                    <div class="j5"></div>
                    <div class="j6"></div>
                    <div class="j7"></div>
                    <div class="j8"></div>
                    <div class="j9"></div>
                    <div class="j10"></div>
                    <div class="j11"></div>
                    <div class="j12"></div>
                    <div class="j13"></div>
                    <div class="j14"></div>
                </div>
                <div class="leg">
                    <div class="k1"></div>
                    <div class="k2"></div>
                    <div class="k3"></div>
                    <div class="k4"></div>
                    <div class="k5"></div>
                    <div class="k6"></div>
                    <div class="k7"></div>
                    <div class="k8"></div>
                    <div class="k9"></div>
                    <div class="k10"></div>
                    <div class="k11"></div>
                    <div class="k12"></div>
                    <div class="k13"></div>
                    <div class="k14"></div>
                </div>
            </div>
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