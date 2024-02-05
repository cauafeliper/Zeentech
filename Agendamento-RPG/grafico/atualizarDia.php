<?php
date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para São Paulo

if (isset($_POST['novaData'])) {
    $novaData = $_POST['novaData'];

    // Atualize a variável $dia no arquivo grafico_dia.php
    $caminhoArquivo = 'grafico_dia.php';

    // Use o bloqueio para evitar concorrência de escrita
    $arquivo = fopen($caminhoArquivo, 'r+');
    if (flock($arquivo, LOCK_EX)) {
        // Leitura do conteúdo do arquivo
        $conteudo = fread($arquivo, filesize($caminhoArquivo));

        if ($novaData == 'atual'){
            $novaData = date('Y-m-d');
            $conteudoAtualizado = preg_replace('/(\$dia\s*=\s*[\'"]?)[^;]+[\'"]?\s*;/', '$dia = '."'$novaData';", $conteudo);
        }
        else{
        // Atualização da variável $dia
            $conteudoAtualizado = preg_replace('/(\$dia\s*=\s*[\'"]?)[^;]+[\'"]?\s*;/', '$dia = '."'$novaData';", $conteudo);
        }
        // Rebobina e escreve de volta
        fseek($arquivo, 0);
        fwrite($arquivo, $conteudoAtualizado);

        // Desbloqueia o arquivo
        flock($arquivo, LOCK_UN);
    }

    fclose($arquivo);
    echo "Variável \$dia atualizada com sucesso para $novaData!";
} else {
    echo "Erro: Nenhuma nova data recebida.";
}