<?php
header('Content-type: text/html; charset=utf-8');
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao = new Conexao();
$secundaria = new Secundaria($conexao);
$res = $secundaria->procurar($_POST);
$qtd = $conexao->qtdResultado($res);
echo "Encontrou {$qtd} resultados<br>";
if ($qtd > 0) {
    ?>
    <table id="table_secundaria">
        <thead>
            <tr>
                <th>Data Cad.</th>
                <th>CNPJ</th>
                <th>Secundaria</th>
                <th>Código</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($secundaria = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td data-order="<?= $secundaria["dtcadastro"] ?>"><?= $secundaria["dtcadastro2"] ?></td>
                    <td><?= ($secundaria["cnpj"]) ?></td>
                    <td><?= ($secundaria["texto"]) ?></td>
                    <td><?= ($secundaria["codigo"]) ?></td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
