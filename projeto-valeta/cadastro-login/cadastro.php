<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento | Cadastro</title>
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style/cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main>
        <div>
            <span>
                <h1 class="sumir">BEM-VINDO</h1>
                <h2>JÁ POSSUI LOGIN?</h2>
            </span>

            <a href="../index.php"><button type="button" class="botao__voltar">Voltar Para Login</button></a>

            <img src="imgs/Logo Zeentech.png" alt="logo-zeentech">
        </div>

        <form action="<?= $_SERVER["PHP_SELF"];?>" method="post">
            <span class="titulo alinhamento">
                <h1 class="sumir__bd">BEM-VINDO</h1>
                <h2 class="cadastro_resp">FAÇA SEU CADASTRO</h2>
            </span>
            
            <span class="re alinhamento">
                <div>
                    <img src="imgs/login-re.png" alt="re" class="form__img">
                    <input type="number" name="reCad" id="idRe" placeholder="RE" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                </div>
            </span> 

            <span class="nomeC alinhamento">
                <div>
                    <img src="imgs/login-nome.png" alt="nome" class="form__img">
                    <input type="text" name="nomeC" id="idNomeC" placeholder="NOME COMPLETO">
                </div>
            </span>

            <span class="email alinhamento">
                <div>
                    <img src="imgs/login-email.png" alt="email" class="form__img">
                    <input type="email" name="email" id="idEmail" placeholder="EMAIL">
                </div>
            </span>

            <span class="senha alinhamento">
                <div>
                    <img src="imgs/login-senha.png" alt="senha" class="form__img">
                    <input type="password" name="senhaCad" id="idSenha" placeholder="SENHA" maxlength="8">
                </div>
            </span>

            <span class="botao alinhamento">
                <input type="submit" name="submit" id="botao" value="Cadastrar-se" class="botao__cadastrar">
            </span>
        </form>
        <?php 
            include_once('codigos_php/cadastro.php');
        ?>
    </main>
</body>
</html>