<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
    require 'PhpSpreadsheet/vendor/autoload.php';
    session_start();
    include_once('config.php');

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $solic = $_POST['solic'];
    $setor = $_POST['setor'];
    $tipoOSI = $_POST['tipoOSI'];
    $dtReali = $_POST['dtReali'];
    $eng = $_POST['eng'];
    $veic = $_POST['veic'];
    $eja = $_POST['eja'];
    $dtCria = $_POST['dtCria'];
    $nPeca1 = $_POST['nPeca1'];
    $qtd1 = $_POST['qtd1'];
    $sist1 = $_POST['sist1'];
    $rmv1 = $_POST['rmv1'];
    $destRmv1 = $_POST['destRmv1'];
    $pdm1 = $_POST['pdm1'];
    $nPeca2 = $_POST['nPeca2'];
    $qtd2 = $_POST['qtd2'];
    $sist2 = $_POST['sist2'];
    $rmv2 = $_POST['rmv2'];
    $destRmv2 = $_POST['destRmv2'];
    $pdm2 = $_POST['pdm2'];
    $nPeca3 = $_POST['nPeca3'];
    $qtd3 = $_POST['qtd3'];
    $sist3 = $_POST['sist3'];
    $rmv3 = $_POST['rmv3'];
    $destRmv3 = $_POST['destRmv3'];
    $pdm3 = $_POST['pdm3'];
    $anexo1 = $_POST['anexo1'];
    $anexo2 = $_POST['anexo2'];
    $anexo3 = $_POST['anexo3'];
    $descServ = $_POST['descServ'];
    $status = $_POST['status'];

    $result = mysqli_query($conexao, "INSERT INTO novas_osi(solicitante, setor, tipo, dtReali, eng, veic, eja, dtCria, numPc1, qtd1, sist1, rmv1, destRmv1, pdm1, numPc2, qtd2, sist2, rmv2, destRmv2, pdm2, numPc3, qtd3, sist3, rmv3, destRmv3, pdm3, descServ, stts) VALUES('$solic', '$setor', '$tipoOSI', '$dtReali', '$eng', '$veic', '$eja', '$dtCria', '$nPeca1', '$qtd1', '$sist1', '$rmv1', '$destRmv1', '$pdm1', '$nPeca2', '$qtd2', '$sist2', '$rmv2', '$destRmv2', '$pdm2', '$nPeca3', '$qtd3', '$sist3', '$rmv3', '$destRmv3', '$pdm3', '$descServ', '$status')");

    $osi_id = mysqli_insert_id($conexao);
    
    $query_email = "SELECT email FROM logins WHERE nome = '$solic'";
    $result_email = mysqli_query($conexao, $query_email);
    $row_email= mysqli_fetch_assoc($result_email);
    $email = $row_email['email'];

    $query_email_eng = "SELECT email FROM engs WHERE eng = '$eng'";
    $result_email_eng = mysqli_query($conexao, $query_email_eng);
    $row_email_eng= mysqli_fetch_assoc($result_email_eng);
    $eng_email = $row_email_eng['email'];
    
    $query_nome_eng = "SELECT eng FROM engs WHERE eng = '$eng'";
    $result_nome_eng = mysqli_query($conexao, $query_nome_eng);
    $row_nome_eng= mysqli_fetch_assoc($result_nome_eng);
    $eng_nome = $row_nome_eng['eng'];

    $templateFile = 'excel/Modelo-OSI.xlsx';
    $spreadsheet = IOFactory::load($templateFile);
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A3', 'Data planejada para realização do serviço: ' . $dtReali);
    $sheet->setCellValue('A6', 'Solicitante: ' . $solic);
    $sheet->setCellValue('A7', 'Engenheiro Responsável: ' . $eng);
    $sheet->setCellValue('G6', 'Contato: ' . $email);
    $sheet->setCellValue('G7', 'Contato Engenheiro: ' . $eng_email);
    $sheet->setCellValue('K2',  'Data da Solicitação: ' . $dtCria);
    $sheet->setCellValue('J6', '*' . $osi_id . '*');
    $sheet->setCellValue('J11', 'EJA: ' . $eja);
    $sheet->setCellValue('A18', $descServ);
    $sheet->setCellValue('A33', $nPeca1);
    $sheet->setCellValue('G33', $qtd1);
    $sheet->setCellValue('H33', $sist1);
    $sheet->setCellValue('I33', $destRmv1);
    $sheet->setCellValue('K33', $pdm1);
    $sheet->setCellValue('A34', $nPeca2);
    $sheet->setCellValue('G34', $qtd2);
    $sheet->setCellValue('H34', $sist2);
    $sheet->setCellValue('I34', $destRmv2);
    $sheet->setCellValue('K34', $pdm2);
    $sheet->setCellValue('A35', $nPeca3);
    $sheet->setCellValue('G35', $qtd3);
    $sheet->setCellValue('H35', $sist3);
    $sheet->setCellValue('I35', $destRmv3);
    $sheet->setCellValue('K35', $pdm3);

    $newExcelFileName = 'OSI_' . $osi_id . '.xlsx';
    $destinationDirectory = 'excel/';
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($destinationDirectory . $newExcelFileName);
    $excelFilePath = $destinationDirectory . $newExcelFileName;

    $diretorio_base = __DIR__ . "/anexos/";

    $destinationDirectory = 'anexos/OSI_' . $osi_id . '/';

    if (!file_exists($destinationDirectory)) {
        mkdir($destinationDirectory, 0777, true);
    }

    for ($i = 1; $i <= 3; $i++) {
        if ($_FILES["anexo{$i}"]["error"] === UPLOAD_ERR_OK) {
            $nome_arquivo = "A{$i}_OSI_{$osi_id}";
            $caminho_arquivo = $destinationDirectory . $nome_arquivo;

            $ext = pathinfo($_FILES["anexo{$i}"]["name"], PATHINFO_EXTENSION);
            $caminho_arquivo .= '.' . $ext;

            move_uploaded_file($_FILES["anexo{$i}"]["tmp_name"], $caminho_arquivo);
        }
    }
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require("PHPMailer-master/src/Exception.php");
    require("PHPMailer-master/src/PHPMailer.php");
    require("PHPMailer-master/src/SMTP.php");

    $mail = new PHPMailer(true);
    try
    {
      // Email para o solicitante.
      $mail->isSMTP();  
      $mail->SMTPAuth = true;
      $mail->Username = 'admin@equipzeentech.com';
      $mail->Password = 'Z3en7ech';
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'equipzeentech.com';
      $mail->Port = 587;
      // Define o remetente
      $mail->setFrom('admin@equipzeentech.com', 'Zeentech');
      // Define o destinatário
      $mail->addAddress($email);
      // Conteúdo da mensagem
      $mail->isHTML(true); // Seta o formato do e-mail para aceitar o conteúdo HTML
      $mail->Subject = 'Nova OSI criada com sucesso!';
      $mail->Body = utf8_decode('Sua nova OSI foi criada com <span style="color: green;">sucesso</span> e já foi enviada para o</br>engenheiro <u><b>'. $eng_nome .'</b></u> para que o mesmo possa aprová-la ou não.</br></br>Segue em anexo a sua nova OSI.</br></br></br>Atenciosamente,</br><span style="color: red;">Equipe Zeentech</span>.');
      $mail->AltBody = 'Sua nova OSI foi criada com sucesso e já foi enviada para o engenheiro '. $eng_nome .' para que o mesmo possa aprová-la ou não. Segue em anexo a sua nova OSI.Atenciosamente, Equipe Zeentech.';
      $mail->AddAttachment($excelFilePath, 'OSI_' . $osi_id . '.xlsx');
      $mail->send();
      
      $mail->ClearAddresses();
      // Email para o engenheiro.
      
      $mail->addAddress($eng_email);
      $mail->Subject = 'Nova OSI criada com sucesso!';
      $mail->Body = utf8_decode('Uma nova OSI foi criada pelo solocitante <u><b>'. $solic .'</b></u></br>e está aguardando sua aprovação.</br></br>Segue em anexo a OSI.</br></br></br>Atenciosamente,</br><span style="color: red;">Equipe Zeentech</span>.');
      $mail->AltBody = 'Uma nova OSI foi criada pelo solocitante '. $solic .' e está aguardando sua aprovação. Segue em anexo a OSI. Atenciosamente, Equipe Zeentech.';
      $mail->AddAttachment($excelFilePath, 'OSI_' . $osi_id . '.xlsx');
      // Enviar
      $mail->send();
      echo '<script>window.location.href = "osicriada.php";</script>';
    }
    catch (Exception $e)
    {
        echo "Talvez tenha ocorrido algum problema com o envio do seu email,";
    }
?>
</body>
</html>