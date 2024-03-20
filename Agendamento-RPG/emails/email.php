<?php

$linkGrafico = 'https://bit.ly/grafico31diasRPG';
$linkVerificacao = 'https://bit.ly/verificacaoRPG';
$linkRecuperarSenha = 'https://bit.ly/recuperarSenhaRPG';
$tutorialCadastro = "https://bit.ly/tutorial_cadastroRPG";
$tutorialGestor = "https://bit.ly/tutorial_gestorRPG";
$tutorialAdm = "https://bit.ly/tutorial_administradorRPG";
$tutorialUsuario = "https://bit.ly/tutorial_usuarioRPG";

function EmailAddCadastro($address) {
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com";
    $mail->Password = "Z3en7ech";
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address); 
    $subject = "Tutorial de Cadastro!";
    $mail->Subject = mb_convert_encoding($subject, "Windows-1252", "UTF-8");
    global $tutorialCadastro;
    $body = "Seu email foi adicionado à lista de cadastros para o site de agendamento da Pista de Testes.\nSegue um link para o tutorial de como realizar o cadastro na página: $tutorialCadastro \n\nAtenciosamente,\nEquipe Zeentech.";
    $mail->Body = mb_convert_encoding($body, "Windows-1252", "UTF-8");
    $mail->send();
}

function EmailAddGestor($address){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; // Seu endereço de e-mail do Gmail
    $mail->Password = "Z3en7ech"; // Sua senha do Gmail
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address); 
    $subject = "Permissão de Gestor";
    $mail->Subject = mb_convert_encoding($subject, "Windows-1252", "UTF-8");
    global $tutorialGestor;
    $body = "Você foi adicionado como Gestor na página de agendamento da Pista de Testes!\nSegue um link para o tutorial de uso da página para gestores: $tutorialGestor \n\nAtenciosamente,\nEquipe Zeentech.";
    $mail->Body = mb_convert_encoding($body, "Windows-1252", "UTF-8");
    $mail->send();
}

function EmailAddAdm($address){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; // Seu endereço de e-mail do Gmail
    $mail->Password = "Z3en7ech"; // Sua senha do Gmail
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address); 
    $subject = "Permissão de Administrador";
    $mail->Subject = mb_convert_encoding($subject, "Windows-1252", "UTF-8");
    global $tutorialAdm;
    $body = "Você foi adicionado como Administradir na página de agendamento da Pista de Testes!\nSegue link para o tutorial de uso da página para administradores: $tutorialAdm \n\nAtenciosamente,\nEquipe Zeentech.";
    $mail->Body = mb_convert_encoding($body, "Windows-1252", "UTF-8");
    $mail->send();
}

function EmailSolicitacao($address, $data, $conexao){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address); 
    $mail->Subject = mb_convert_encoding("Solicitação criada com sucesso!","Windows-1252","UTF-8"); 
    $mail->Body = mb_convert_encoding("Sua solicitação de agendamento da área da pista {$data['area_pista']} para o dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']} foi criada com sucesso!\nAssim que houver uma resposta do Gestor encarregado, você receberá um email dizendo se sua solicitação foi aprovada ou não.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");
    $mail->send();

    $mail->ClearAddresses();
    
    $query_gestor = "SELECT email FROM gestor";
    $result_gestor = mysqli_query($conexao, $query_gestor);
    while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
        $mail->addAddress($row_gestor['email']); //email pros gestores
    }
    $mail->Subject = mb_convert_encoding('Nova solicitação de agendamento para pista a Pista de Teste!',"Windows-1252","UTF-8");
    $mail->Body = mb_convert_encoding("Uma nova solicitação para o agendamento da Pista de Teste foi criada pelo colaborador(a) {$data['solicitante']} na área da pista {$data['area_pista']} para o dia {$data['dia']} e horário de {$data['hora_inicio']} até {$data['hora_fim']} com objetivo {$data['objtv']}.\nEssa nova solicitação aguarda sua resposta!\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");
    $mail->send();
}

function EmailSolicitacaoAdm($data, $conexao){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com";  
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
                            
    $query_gestor = "SELECT email FROM gestor";
    $result_gestor = mysqli_query($conexao, $query_gestor);
    while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
        $mail->addAddress($row_gestor['email']); //email pros gestores
    }

    $query_copias = "SELECT email FROM copias_email";
    $result_copias = mysqli_query($conexao, $query_copias);
    if ($result_copias->num_rows > 0) {
        while ($row_copias = mysqli_fetch_assoc($result_copias)) {
            $email_frota = $row_copias['email'];
            $mail->AddCC($email_frota); //email pra copias
        }
    }
    
    global $linkGrafico;
    $mail->Subject = mb_convert_encoding('Novo agendamento na Pista de Teste!',"Windows-1252","UTF-8");
    $mail->Body = mb_convert_encoding("Um agendamento foi aprovado para a área da pista {$data['area_pista']} no dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']}!\nPara conferir a tabela de agendamentos dos próximos 30 dias, acesse: $linkGrafico.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");
    $mail->send();
}

function EmailAprovar($address, $data, $gestor, $conexao){
    require('../../PHPMailer-master/src/Exception.php');
    require("../../PHPMailer-master/src/PHPMailer.php"); 
    require("../../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com";  
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address);

    $mail->Subject = mb_convert_encoding("Solicitação Aprovada!","Windows-1252","UTF-8"); 
    $mail->Body = mb_convert_encoding("Sua solicitação de agendamento da área da pista {$data['area_pista']} para o dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']} foi Aprovada pelo gestor $gestor!\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8"); 

    $mail->send();

    $mail->ClearAddresses();

    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com";  
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG");

    $query_gestor = "SELECT email FROM gestor";
    $result_gestor = mysqli_query($conexao, $query_gestor);
    while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
        $mail->addAddress($row_gestor['email']); //email pros gestores
    }

    $query_copias = "SELECT email FROM copias_email";
    $result_copias = mysqli_query($conexao, $query_copias);
    while ($row_copias = mysqli_fetch_assoc($result_copias)) {
        $mail->AddCC($row_copias['email']); //email pra copias
    }

    global $linkGrafico;
    $mail->Subject = mb_convert_encoding('Novo agendamento na Pista de Teste!',"Windows-1252","UTF-8");
    $mail->Body = mb_convert_encoding("Um agendamento foi aprovado para a área da pista {$data['area_pista']} no dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']} pelo gestor $gestor!\nPara conferir a tabela de agendamentos dos próximos 30 dias, acesse: $linkGrafico.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");
    $mail->send();
}

function EmailCancelar($address, $data, $conexao){
    require('../../PHPMailer-master/src/Exception.php');
    require("../../PHPMailer-master/src/PHPMailer.php"); 
    require("../../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address); 

    $mail->Subject = mb_convert_encoding("Solicitação Cancelada com sucesso!","Windows-1252","UTF-8"); 
    $mail->Body = mb_convert_encoding("Você cancelou sua solicitação de agendamento da área da pista {$data['area_pista']} para o dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']}!\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8"); 

    $mail->send();

    $mail->ClearAddresses();
                                
    $query_gestor = "SELECT email FROM gestor";
    $result_gestor = mysqli_query($conexao, $query_gestor);
    while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
        $mail->addAddress($row_gestor['email']); //email pros gestores
    }

    if ($data['status'] == 'Aprovado'){
        $query_copias = "SELECT email FROM copias_email";
        $result_copias = mysqli_query($conexao, $query_copias);
        if ($result_copias->num_rows > 0) {
            while ($row_copias = mysqli_fetch_assoc($result_copias)) {
                $email_frota = $row_copias['email'];
                $mail->AddCC($email_frota); //email pra copias
            }
        }
        
        global $linkGrafico;
        $mail->Subject = mb_convert_encoding('Agendamento Cancelado!',"Windows-1252","UTF-8");
        $mail->Body = mb_convert_encoding("Um agendamento previamente aprovado para a área da pista {$data['area_pista']} no dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']} foi Cancelado pelo Solicitante {$data['solicitante']}!\nPara conferir a tabela de agendamentos dos próximos 30 dias, acesse: $linkGrafico.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");
    }
    else{
        global $linkGrafico;
        $mail->Subject = mb_convert_encoding('Solicitação Cancelada!',"Windows-1252","UTF-8");
        $mail->Body = mb_convert_encoding("Uma solicitação de agendamento para a área da pista {$data['area_pista']} no dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']} foi Cancelada pelo Solicitante {$data['solicitante']}!\nPara conferir a tabela de agendamentos dos próximos 30 dias, acesse: $linkGrafico.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");
    }

    $mail->send();
}

function EmailReprovar($address, $data, $motivoReprovacao, $gestor, $conexao){
    require('../../PHPMailer-master/src/Exception.php');
    require("../../PHPMailer-master/src/PHPMailer.php"); 
    require("../../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address); 

    $mail->Subject = mb_convert_encoding("Solicitação Reprovada!","Windows-1252","UTF-8"); 
    $mail->Body = mb_convert_encoding("Sua solicitação de agendamento da área da pista {$data['area_pista']} para o dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']} foi reprovada pelo gestor $gestor!\nMotivo: \"$motivoReprovacao\".\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8"); 

    $mail->send();

    if ($data['status'] == 'Aprovado'){
        $mail->ClearAddresses();
                                
        $query_gestor = "SELECT email FROM gestor";
        $result_gestor = mysqli_query($conexao, $query_gestor);
        while ($row_gestor = mysqli_fetch_assoc($result_gestor)) {
            $mail->addAddress($row_gestor['email']); //email pros gestores
        }

        $query_copias = "SELECT email FROM copias_email";
        $result_copias = mysqli_query($conexao, $query_copias);
        if ($result_copias->num_rows > 0) {
            while ($row_copias = mysqli_fetch_assoc($result_copias)) {
                $email_frota = $row_copias['email'];
                $mail->AddCC($email_frota); //email pra copias
            }
        }
        
        global $linkGrafico;
        $mail->Subject = mb_convert_encoding('Agendamento Reprovado!',"Windows-1252","UTF-8");
        $mail->Body = mb_convert_encoding("Um agendamento previamente aprovado para a área da pista {$data['area_pista']} no dia {$data['dia']} de {$data['hora_inicio']} até {$data['hora_fim']} foi Reprovado pelo Gestor $gestor!\nPara conferir a tabela de agendamentos dos próximos 30 dias, acesse: $linkGrafico.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");

        $mail->send();
    }
}

function EmailGrafico($result_email){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    
    global $linkGrafico;
    $mail->Subject = mb_convert_encoding("Gráfico de agendamentos dos próximos 30 dias","Windows-1252","UTF-8"); 
    $mail->Body = mb_convert_encoding("\nPara conferir a tabela de agendamentos dos próximos 30 dias, acesse: $linkGrafico.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8");

    while ($row_email = mysqli_fetch_assoc($result_email)) {
        $email = $row_email['email'];
        $mail->AddAddress($email);
    }
    $mail->send();
}

function EmailConfirmar($address, $token){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address);
    
    global $linkVerificacao;
    $mail->Subject = mb_convert_encoding("Verificação de email","Windows-1252","UTF-8"); 
    $mail->Body = mb_convert_encoding("Seu token de verificação é $token. Esse token vai expirar em 30 minutos! Para verificar seu email, acesse $linkVerificacao.\nCaso a solicitação não tenha sido feita por você, apenas ignore este email.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8"); 
    $mail->send();
}

function EmailVerificado($address, $gestorTrue, $admTrue){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address); 
    $mail->Subject = mb_convert_encoding("Email verificado!","Windows-1252","UTF-8");
    
    global $tutorialUsuario;
    global $tutorialGestor;
    global $tutorialAdm;
    $body =  "Seu email foi verificado com sucesso!\nSegue link para o tutorial de como utilizar a página: $tutorialUsuario";
    if($gestorTrue) {
        $body .= "\nSegue link para o tutorial de como utilizar a página como gestor: $tutorialGestor";
    }
    if($admTrue) {
        $body .= "\nSegue link para o tutorial de como utilizar a página como administrador: $tutorialAdm";
    }
    $body .= "\n\nAtenciosamente,\nEquipe Zeentech";
    $mail->Body = mb_convert_encoding($body,"Windows-1252","UTF-8");

    $mail->send();
}

function EmailRecuperarSenha($address, $token){
    require('../PHPMailer-master/src/Exception.php');
    require("../PHPMailer-master/src/PHPMailer.php"); 
    require("../PHPMailer-master/src/SMTP.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(); 
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "equipzeentech.com"; 
    $mail->Port = 587;
    $mail->Username = "admin@equipzeentech.com"; 
    $mail->Password = "Z3en7ech"; 
    $mail->SetFrom("admin@equipzeentech.com", "SISTEMA RPG"); 
    $mail->AddAddress($address);
    
    $mail->Subject = mb_convert_encoding("Recuperação da senha","Windows-1252","UTF-8"); 
    $mail->Body = mb_convert_encoding("Seu token de recuperação de senha é $token. Esse token vai expirar em 30 minutos!\nCaso a solicitação não tenha sido feita por você, apenas ignore este email.\n\nAtenciosamente,\nEquipe Zeentech.","Windows-1252","UTF-8"); 
    $mail->send();
}