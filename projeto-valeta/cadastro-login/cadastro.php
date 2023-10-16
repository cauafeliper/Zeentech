<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../imgs/favicon.png" type="image/x-icon">
    <title>Agendamento | Cadastro</title>
    <link rel="stylesheet" href="../estilos/style.css">
    <link rel="stylesheet" href="../estilos/style2.css">
</head>
<style>
    body {
        background-image: linear-gradient(to top, rgba(116, 3, 3, 0.637), rgba(83, 82, 82, 0.801));
        background-repeat: no-repeat;
        background-size: cover;  
    } 
</style>
<body>    
    <main class="main__erro">
        <form action="cadastrot.php" method="post">
            <section class="section__erro" style="margin-bottom: 10px;"><h1>Cadastre-se</h1></section>

            <label for="re"><img src="../imgs/pes.png" alt="pessoa" style="position: relative; top:3px; right:2px;">RE:</label>
            <input type="number" name="reCad" id="idRe" placeholder="Digite seu RE..." maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">

            <label for="nomeC"><img src="../imgs/nome.png" alt="nomeC" style="position: relative; top:3px; right:2px;">Nome Completo:</label>
            <input type="text" name="nomeC" id="idNomeC" placeholder="Digite seu nome completo...">

            <label for="email"><img src="../imgs/email.png" alt="email" style="position: relative; top:3px; right:2px;">Email:</label>
            <input type="email" name="email" id="idEmail" placeholder="Digite seu email...">

            <label for="senha"><img src="../imgs/cad.png" alt="cadeado" style="position: relative; top:3px; right:2px;">Senha:</label>
            <input type="password" name="senhaCad" id="idSenha" placeholder="Digite sua senha..." maxlength="8">

            <input type="submit" name="submit" value="Cadastrar" class="botoes">

            <hr>
            
            <a href="../index.php" class="botoes"><button type="button" class="botoes">Voltar</button></a>
        </form>
    </main>
</body>
</html>