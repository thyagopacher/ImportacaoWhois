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
$socio = new Socio($conexao);
$res = $socio->procurar($_POST);
$qtd = $conexao->qtdResultado($res);
echo "Encontrou {$qtd} resultados<br>";
if ($qtd > 0) {
    ?>
    <table id="table_socio">
        <thead>
            <tr>
                <th>Data Cad.</th>
                <th>CNPJ</th>
                <th>
                    Socio
                </th>
                <th>
                    Qualificação
                </th>
                <th>
                    Repres. Legal
                </th>
                <th>
                    Qual. Rep. Legal
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($socio = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td data-order="<?= $socio["dtcadastro"] ?>"><?= $socio["dtcadastro2"] ?></td>
                    <td><?= ($socio["cnpj"]) ?></td>
                    <td><?= ($socio["nome"]) ?></td>
                    <td><?= ($socio["qual"]) ?></td>
                    <td><?= ($socio["nome_rep_legal"]) ?></td>
                    <td><?= ($socio["qual_rep_legal"]) ?></td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
