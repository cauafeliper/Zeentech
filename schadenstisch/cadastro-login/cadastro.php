<?php 
    include_once('../config/config.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../imgs/logo-volks.png" type="image/x-icon">
    <link rel="stylesheet" href="style/style_cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Schadenstisch | Cadastro</title>
</head>
<body>
    <main>
        <div>
            <span>
                <h1>BEM VINDO!</h1>
                <h2>JÁ POSSUI CADASTRO?</h2>
            </span>
            
            <a href="../index.php"><button>Login</button></a>

            <img src="../imgs/Logo Zeentech.png" alt="logo-zeentech">
        </div>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h1>FAÇA SEU CADASTRO!</h1>
            <span><img src="imgs/nome.png" alt="nome"><input type="text" name="nome" id="nome" placeholder="Digite seu nome completo..." required></span>
            <span><img src="imgs/email.png" alt="email"><input type="text" name="email" id="email" placeholder="Digite seu email..." required></span>
            <span><img src="imgs/password.png" alt="senha"><input type="password" name="senha" id="senha" placeholder="Digite sua senha..." required></span>
            <input type="submit" name="submit" value="Realizar Cadastro">
        </form>
    </main>
    <?php include_once('codigos-php/cadastro.php');?>
</body>
</html>