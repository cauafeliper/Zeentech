<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/logo-zeentech.png" type="image/x-icon">
    <title>Cadastro</title>
    <link rel="stylesheet" href="estilos/style.css">
    <style>
        body {
            background-image: url("imgs/background.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <picture><a href="https://www.zeentech.com/" target="_blank" type="external"><img src="imgs/lg-zeentech(titulo).png" alt="logoZeentech" style="position:relative; right:580px; top:50px;"></a></picture>

    <a href="https://www.vwco.com.br/" target="_blank" type="external"><img src="imgs/logo-volks.png" alt="logoVolks" style="position:relative; left:590px; top:5px;"></a>
    
    <main style="position: relative; top: -30px;">
        <form action="cadastrot.php" method="post" style="background-color: white;">
            <section style="background-color: #4C7397;"><h1 style="height: 20px; color: white; text-align: center;">Cadastre-se</h1></section>

            <label for="re"><img src="imgs/pes.png" alt="pessoa" style="position: relative; top:3px; right:2px;">RE:</label>
            <input type="number" name="reCad" id="idRe" placeholder="Digite seu RE..." maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">

            <label for="nomeC"><img src="imgs/nome.png" alt="nomeC" style="position: relative; top:3px; right:2px;">Nome Completo:</label>
            <input type="text" name="nomeC" id="idNomeC" placeholder="Digite seu nome completo...">

            <label for="email"><img src="imgs/email.png" alt="email" style="position: relative; top:3px; right:2px;">Email:</label>
            <input type="email" name="email" id="idEmail" placeholder="Digite seu email...">

            <label for="senha"><img src="imgs/cad.png" alt="cadeado" style="position: relative; top:3px; right:2px;">Senha:</label>
            <input type="password" name="senhaCad" id="idSenha" placeholder="Digite sua senha..." maxlength="8">
            
            <!-- <label for="senha"><img src="imgs/cad.png" alt="cadeado" style="position: relative; top:3px; right:2px;">Confirme a Senha:</label>
            <input type="password" name="cSenha" id="idCsenha" placeholder="Digite sua senha novamente..."> -->

            <input type="submit" name="submit" value="Cadastrar">

            <hr>
            
            <a href="index.php"><button type="button">Voltar</button></a>
        </form>
    </main>
</body>
</html>