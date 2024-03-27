<?php 
    include_once('../config/config.php');
    session_start();
    if (!isset($_SESSION['email']) OR !isset($_SESSION['senha'])) {
        session_destroy();
        echo '<script>window.location.href = "../index.php";</script>';
    }
    
    $email = $_SESSION['email'];
    $query = "SELECT * FROM email_adm WHERE email = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        session_destroy();
        echo '<script>window.location.href = "../index.php";</script>';
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="style/style_aprovacao.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <title>Schadenstisch | Aprovação de Login</title>
</head>
<body>
    <header>
        <a href="https://www.vwco.com.br/" target="_blank"><img src="../imgs/truckBus.png" alt="logo-truckbus"></a>
        <ul>
            <li><a href="../telas/tela_principal.php">Início</a></li>
            <li><a href="../sair.php">Sair</a></li>
        </ul>
    </header>

    <main>
        <article>
        <h3>Pendentes</h3>
        <?php
        function pendentes($conexao) {
            $query = "SELECT * FROM logins WHERE status = 'PENDENTE'";
            $stmt = mysqli_prepare($conexao, $query);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    echo '<div class="div__logins">';
                    echo '<span class="nome">Nome: ' . $linha['nome'] . '</span>';
                    echo '<span class="email">Email: ' . $linha['email'] . '</span>';
                    echo '<span class="status"> 
                            <a href="javascript:void(0);" data-id="'. $linha['id'] .'" class="aprovar-link">
                                <input type="button" value="✔" class="aprovar">
                            </a>
                            <a href="javascript:void(0);" data-id="'. $linha['id'] .'" class="cancelar-link">
                                <input type="button" value="✖︎" class="cancelar">
                            </a>
                        </span>';
                    echo '</div>';
                }
            } else {
                echo 'Nenhum login Pendente no momento.';
            }
        }

        pendentes($conexao);
        ?>
        </article>

        <article>
        <h3>Aprovados</h3>
        <?php
        function aprovados($conexao) {
            $query = "SELECT * FROM logins WHERE status = 'APROVADO'";
            $stmt = mysqli_prepare($conexao, $query);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    echo '<div class="div__logins">';
                    echo '<span class="nome">Nome: ' . $linha['nome'] . '</span>';
                    echo '<span class="email">Email: ' . $linha['email'] . '</span>';
                    echo '<span class="status">
                            <a href="javascript:void(0);" data-id="'. $linha['id'] .'" class="cancelar-link">
                                <input type="button" value="✖︎" class="cancelar">
                            </a>
                        </span>';
                    echo '</div>';
                }
            } else {
                echo 'Nenhum login Aprovado no momento.';
            }
        }

        aprovados($conexao);
        ?>
        </article>
            
        <article>
        <h3>Reprovados</h3>
        <?php
        function reprovados($conexao) {
            $query = "SELECT * FROM logins WHERE status = 'REPROVADO'";
            $stmt = mysqli_prepare($conexao, $query);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    echo '<div class="div__logins">';
                    echo '<span class="nome">Nome: ' . $linha['nome'] . '</span>';
                    echo '<span class="email">Email: ' . $linha['email'] . '</span>';
                    echo '<span class="status">
                            <a href="javascript:void(0);" data-id="'. $linha['id'] .'" class="aprovar-link">
                                <input type="button" value="✔" class="aprovar">
                            </a>
                        </span>';
                    echo '</div>';
                }
            } else {
                echo 'Nenhum login Reprovado no momento.';
            }
        }

        reprovados($conexao);
        ?>
        </article>
    </main>

    <footer>
        <div>
            <span>Desenvolvido por:  <img src="../imgs/lg-zeentech(titulo).png" alt="logo-zeentech"></span>
        </div>
        <div class="copyright">
            <span>Copyright © 2024 de Zeentech os direitos reservados</span>
        </div>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var botoesAprovar = document.querySelectorAll(".aprovar-link");
        var botoesCancelar = document.querySelectorAll(".cancelar-link");

        botoesAprovar.forEach(function (botao) {
            botao.addEventListener("click", function (event) {
                event.preventDefault();
                var id = this.dataset.id; // Obtendo o ID da linha

                var divLogin = this.closest(".div__logins");
                var nome = divLogin.querySelector(".nome").innerText;
                var email = divLogin.querySelector(".email").innerText;

                Swal.fire({
                    icon: 'question',
                    title: "Confirmação",
                    html: `
                        Você tem certeza de que deseja APROVAR o seguinte login?<br>
                        Nome: ${nome}<br>
                        Email: ${email}
                    `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim, aprovar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "aprovar-reprovar/aprovar.php?id=" + id;
                    }
                });
            });
        });

        botoesCancelar.forEach(function (botao) {
            botao.addEventListener("click", function (event) {
                event.preventDefault();
                var id = this.dataset.id; // Obtendo o ID da linha

                var divLogin = this.closest(".div__logins");
                var nome = divLogin.querySelector(".nome").innerText;
                var email = divLogin.querySelector(".email").innerText;

                Swal.fire({
                    icon: 'question',
                    title: "Confirmação",
                    html: `
                        Você tem certeza de que deseja CANCELAR o seguinte login?<br>
                        Nome: ${nome}<br>
                        Email: ${email}
                    `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim, cancelar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "aprovar-reprovar/cancelar.php?id=" + id;
                    }
                });
            });
        });
    });
    </script>
    <?php 
        mysqli_close($conexao);
    ?>
</body>
</html>