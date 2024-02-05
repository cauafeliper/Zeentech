<?php
include_once('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Certifique-se de validar e limpar os dados recebidos para evitar SQL injection

    $empresaSelecionada = $_POST['empresa'];

    // Use a variável $empresaSelecionada para buscar as áreas correspondentes no banco de dados
    $query_area = "SELECT DISTINCT nome FROM area_solicitante WHERE empresa = '$empresaSelecionada'";
    $result_area = mysqli_query($conexao, $query_area);

    $options = '<option value="">Selecione a área</option>';
    while ($row_area = mysqli_fetch_assoc($result_area)) {
        $options .= '<option value="' . htmlspecialchars($row_area['nome']) . '">' . htmlspecialchars($row_area['nome']) . '</option>';
    }

    echo $options;
} else {
    http_response_code(400); // Bad Request
    echo 'Requisição inválida.';
}
