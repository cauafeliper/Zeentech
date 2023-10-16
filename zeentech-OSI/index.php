<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/logo-zeentech.png" type="image/x-icon">
    <title>Login| Zeentech - OSI</title>
    <link rel="stylesheet" href="estilos/style.css">
    <style>
        body {
            background-image: url("imgs/background.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-color: #494949c2;
        } 
    </style>
</head>
<body>
    <a href="https://www.zeentech.com/" target="_blank" type="external"><img src="imgs/lg-zeentech(titulo).png" alt="logoZeentech" style="position:relative; right:580px; top:50px;"></a>
    
    <a href="https://www.vwco.com.br/" target="_blank" type="external"><img src="imgs/logo-volks.png" alt="logoVolks" style="position:relative; left:590px; top:5px;"></a>

    <main style="position: relative; top:50px;">
        <form action="login.php" method="POST">
            <section style="background-color: #4C7397;"><h1 style="color: white; text-align:center;">Entre Com Seus Dados</h1></section>
            
            <label for="re"><img src="imgs/pes.png" alt="iconepessoa" style="position: relative; top:3px; right:2px;">RE:</label> 
            <input type="number" name="re" id="idRe" 
           placeholder="Digite seu RE..." style="border-color: black;" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
            
            <label for="senha"><img src="imgs/cad.png" alt="cadeado" style="position: relative; top:3px; right:2px;">Senha:</label> 
            <input type="password" name="senha" id="idsenha" placeholder="Digite sua senha..." style="border-color: black;" maxlength="8">
            
            <input type="submit" name="submit" id="botao" value="Efetuar Login">
            <hr>
            <a href="cadastro.php"><button type="button">Cadastrar-se</button></a>
            <!--<hr>
            <a href="trocarSenha.php"><button type="button">Esqueci minha senha</button></a>-->
        </form>
    </main>
</body>
</html>