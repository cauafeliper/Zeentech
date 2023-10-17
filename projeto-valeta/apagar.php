<?php
include_once('config.php');

if (isset($_GET['id']) && isset($_GET['tabela'])) {
    $id = $_GET['id'];
    $tabela_nome = $_GET['tabela'];

    $tabelas_correspondentes = [
        'Valeta A' => 'valeta_a',
        'Valeta B' => 'valeta_b',
        'Valeta C' => 'valeta_c',
        'Valeta VW' => 'valeta_vw'
    ];

    if (array_key_exists($tabela_nome, $tabelas_correspondentes)) {
        $tabela = $tabelas_correspondentes[$tabela_nome];
        $query = "DELETE FROM $tabela WHERE id = $id";
        if (mysqli_query($conexao, $query)) {
            header('Location: agendamentos-usuario.php');
            exit();
        } else {
            echo "Erro ao excluir registro: " . mysqli_error($conexao);
        }
    } else {
        echo "Tabela não permitida.";
    }
} else {
    echo "ID ou tabela não fornecidos.";
}
?>