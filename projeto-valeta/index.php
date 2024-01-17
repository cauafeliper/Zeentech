<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento | Login</title>
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="cadastro-login/style/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<<<<<<< Updated upstream
<style>
    body {
        background-image: linear-gradient(to top, rgba(116, 3, 3, 0.637), rgba(83, 82, 82, 0.801));
        background-repeat: no-repeat;
        background-size: cover;  
    } 
</style>
=======
>>>>>>> Stashed changes
<body>
    <main>
        <div>
            <span>
                <h1 class="sumir">BEM-VINDO</h1>
                <h2>NÃO POSSUI LOGIN?</h2>
            </span>

            <a href="cadastro-login/cadastro.php"><button type="button" class="botao__cadastro">Cadastrar-se</button></a>

            <img src="cadastro-login/imgs/Logo Zeentech.png" alt="logo-zeentech">
        </div>

        <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
            <artile>
                <h1 class="sumir__bd">BEM-VINDO</h1>
                <h2 class="login_resp">FAÇA SEU LOGIN</h2>
            </artile>
            
            <div>
                <img src="cadastro-login/imgs/login-re.png" alt="re" class="form__img">
                <input type="number" name="re" id="idRe" placeholder="RE" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
            </div> 

            <div>
                <img src="cadastro-login/imgs/login-senha.png" alt="senha" class="form__img">
                <input type="password" name="senha" id="idsenha" placeholder="SENHA" maxlength="8">
            </div>

            <input type="submit" name="submit" id="botao" value="Efetuar Login" class="botoes">
        </form>
    </main>
    <?php
        include_once('cadastro-login/codigos_php/login.php');
    ?>
</body>
</html>