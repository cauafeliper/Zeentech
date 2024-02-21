<?php 
    include_once('config/config.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="cadastro-login/style/style_login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Schadenstisch | Login</title>
</head>
<body>
    <header>

    </header>

    <main>
        <div>
            <span>
                <h1>BEM VINDO!</h1>
                <h2>NÃO POSSUI CADASTRO?</h2>
            </span>
            
            <a href="cadastro-login/cadastro.php"><button>Cadastrar-se</button></a>

            <img src="imgs/Logo Zeentech.png" alt="logo-zeentech">
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h1>FAÇA SEU LOGIN!</h1>
            <span><img src="cadastro-login/imgs/email.png" alt="email"><input type="email" name="email" id="email" placeholder="Digite seu email..." required></span>
            <span><img src="cadastro-login/imgs/password.png" alt="senha"><input type="password" name="senha" id="senha" placeholder="Digite sua senha..." required></span>
            <input type="submit" value="Realizar Login" name="submit">
        </form>
    </main>

    <footer>

    </footer>
    <?php include_once('cadastro-login/codigos-php/login.php');?>
</body>
</html>