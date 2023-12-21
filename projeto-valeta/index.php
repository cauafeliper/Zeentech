<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento | Login</title>
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="estilos/style.css">
    <link rel="stylesheet" href="estilos/style2.css">
</head>
<style>
        body {
            background-image: linear-gradient(to top, rgba(116, 3, 3, 0.637), rgba(83, 82, 82, 0.801));
            background-repeat: no-repeat;
            background-size: cover;  
        } 
    </style>
<body>
    <main>
        <form action="cadastro-login/login.php" method="post">
            <div class="div__h1">
                <h1>Insira seus dados</h1>
            </div>
            <label for="re"><img src="imgs/pes.png" alt="iconepessoa" style="position: relative; top:3px; right:2px;">RE:</label> 
            <input type="number" name="re" id="idRe" placeholder="Digite seu RE..." maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"> 

            <label for="senha"><img src="imgs/cad.png" alt="cadeado" style="position: relative; top:3px; right:2px;">Senha:</label> 
            <input type="password" name="senha" id="idsenha" placeholder="Digite sua senha..." style="border-color: black;" maxlength="8">

            <input type="submit" name="submit" id="botao" value="Efetuar Login" class="botoes" style="margin-top: 15px;">
            <hr>
            <a href="cadastro-login/cadastro.php" class="botoes"><button type="button" class="botoes">Cadastrar-se</button></a>
        </form>
    </main>
</body>
</html>