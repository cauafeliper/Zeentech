<?php include_once('../../config/config.php'); session_start();?>
<tr>
    <th style="display: none;">Item</th>
    <th>Programa</th>
    <th>
        <select name="" id="">
            <option value="">Nº do Problema</option>
            <?php 
                $query = "SELECT DISTINCT n_problem FROM kpm";
                $result = $conexao->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['n_problem'] . '">' . $row['n_problem'] . '</option>';
                };
            ?>
        </select>
    </th>
    <th>
        <select name="" id="">
            <option value="">Ranking</option>
            <?php 
                $query = "SELECT DISTINCT rank FROM kpm";
                $result = $conexao->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['rank'] . '">' . $row['rank'] . '</option>';
                };
            ?>
        </select>
    </th>
    <th>
        <select name="" id="">
            <option value="">Resumo</option>
            <?php 
                $query = "SELECT DISTINCT resumo FROM kpm";
                $result = $conexao->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['resumo'] . '">' . $row['resumo'] . '</option>';
                };
            ?>
        </select>
    </th>
    <th>
        <select name="" id="">
            <option value="">Veículo</option>
            <?php 
                $query = "SELECT DISTINCT veiculo FROM kpm";
                $result = $conexao->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['veiculo'] . '">' . $row['veiculo'] . '</option>';
                };
            ?>
        </select>
    </th>
    <th>
        <select name="" id="">
            <option value="">Status KPM</option>
            <?php 
                $query = "SELECT DISTINCT status_kpm FROM kpm";
                $result = $conexao->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['status_kpm'] . '">' . $row['status_kpm'] . '</option>';
                };
            ?>
        </select>
    </th>
    <th>Status Reunião</th>
    <th>
        <select name="" id="">
            <option value="">FG</option>
            <?php 
                $query = "SELECT DISTINCT fg FROM kpm";
                $result = $conexao->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['fg'] . '">' . $row['fg'] . '</option>';
                };
            ?>
        </select>
    </th>
    <th>
        <select name="" id="">
            <option value="">Dias em Aberto</option>
            <?php 
                $query = "SELECT DISTINCT dias_aberto FROM kpm";
                $result = $conexao->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['dias_aberto'] . '">' . $row['dias_aberto'] . '</option>';
                };
            ?>
        </select>
    </th>
    <th >Due Date</th>
</tr>
<?php

// Armazena os valores dos filtros em variáveis de sessão
if (isset($_GET['filtro_programa'])) {
    $_SESSION['filtro_programa'] = $_GET['filtro_programa'];
}
if (isset($_GET['filtro_n_problem'])) {
    $_SESSION['filtro_n_problem'] = $_GET['filtro_n_problem'];
}
if (isset($_GET['filtro_rank'])) {
    $_SESSION['filtro_rank'] = $_GET['filtro_rank'];
}
if (isset($_GET['filtro_resumo'])) {
    $_SESSION['filtro_resumo'] = $_GET['filtro_resumo'];
}
if (isset($_GET['filtro_veiculo'])) {
    $_SESSION['filtro_veiculo'] = $_GET['filtro_veiculo'];
}
if (isset($_GET['filtro_status_kpm'])) {
    $_SESSION['filtro_status_kpm'] = $_GET['filtro_status_kpm'];
}
if (isset($_GET['filtro_status_reuniao'])) {
    $_SESSION['filtro_status_reuniao'] = $_GET['filtro_status_reuniao'];
}
if (isset($_GET['filtro_fg'])) {
    $_SESSION['filtro_fg'] = $_GET['filtro_fg'];
}

// Construa a consulta SQL com base nos filtros armazenados em variáveis de sessão
$query = "SELECT * FROM kpm WHERE 1=1";

// Adicione condições de filtro para cada coluna com base nos valores armazenados em variáveis de sessão
if (isset($_SESSION['filtro_programa']) && $_SESSION['filtro_programa'] != 'Todos') {
    $query .= " AND programa = '" . $_SESSION['filtro_programa'] . "'";
}
if (isset($_SESSION['filtro_n_problem']) && $_SESSION['filtro_n_problem'] != 'Todos') {
    $query .= " AND n_problem = '" . $_SESSION['filtro_n_problem'] . "'";
}
if (isset($_SESSION['filtro_rank']) && $_SESSION['filtro_rank'] != 'Todos') {
    $query .= " AND rank = '" . $_SESSION['filtro_rank'] . "'";
}
if (isset($_SESSION['filtro_resumo']) && $_SESSION['filtro_resumo'] != 'Todos') {
    $query .= " AND resumo = '" . $_SESSION['filtro_resumo'] . "'";
}
if (isset($_SESSION['filtro_veiculo']) && $_SESSION['filtro_veiculo'] != 'Todos') {
    $query .= " AND veiculo = '" . $_SESSION['filtro_veiculo'] . "'";
}
if (isset($_SESSION['filtro_status_kpm']) && $_SESSION['filtro_status_kpm'] != 'Todos') {
    $query .= " AND status_kpm = '" . $_SESSION['filtro_status_kpm'] . "'";
}
if (isset($_SESSION['filtro_status_reuniao']) && $_SESSION['filtro_status_reuniao'] != 'Todos') {
    if ($_SESSION['filtro_status_reuniao'] == 'Abertos') {
        $query .= " AND status_reuniao <> 5";
    } elseif ($_SESSION['filtro_status_reuniao'] == 'Fechados') {
        $query .= " AND status_reuniao = 5";
    }
}
if (isset($_SESSION['filtro_fg']) && $_SESSION['filtro_fg'] != 'Todos') {
    $query .= " AND fg = '" . $_SESSION['filtro_fg'] . "'";
}
// Adicione mais condições de filtro para outras colunas conforme necessário

// Execute a consulta SQL
$result = $conexao->query($query);

// Exibir os resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td style="display: none;"><?php echo $row['item']; ?></td>
            <td value="<?php echo $row['programa']; ?>" id="td_tippy"><?php echo $row['programa']; ?></td>
            <td value="<?php echo $row['n_problem']; ?>" id="td_tippy"><?php echo $row['n_problem']; ?></td>
            <td value="<?php echo $row['rank']; ?>" id="td_tippy"><?php echo $row['rank']; ?></td>
            <td value="<?php echo $row['resumo']; ?>" id="td_tippy"><?php echo $row['resumo']; ?></td>
            <td value="<?php echo $row['veiculo']; ?>" id="td_tippy"><?php echo $row['veiculo']; ?></td>
            <td value="<?php echo $row['status_kpm']; ?>" id="td_tippy"><?php echo $row['status_kpm']; ?></td>
            <td value="<?php echo $row['status_reuniao']; ?>" id="td_tippy"><?php echo $row['status_reuniao']; ?></td>
            <td value="<?php echo $row['fg']; ?>" id="td_tippy"><?php echo $row['fg']; ?></td>
            <td value="<?php echo $row['dias_aberto']; ?>" id="td_tippy"><?php echo $row['dias_aberto']; ?></td>
            <td value="<?php echo date('d/m/Y', strtotime($row['due_date'])); ?>" id="td_tippy"><?php echo date('d/m/Y', strtotime($row['due_date'])); ?></td>
        </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="10">Ainda não existem KPM\'s registrados!</td></tr>';
}
?>