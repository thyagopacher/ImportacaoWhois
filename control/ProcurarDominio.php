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
$dominio = new Dominio($conexao);
$res = $dominio->procurar($_POST);
$qtd = $conexao->qtdResultado($res);
echo "Encontrou {$qtd} resultados<br>";
if ($qtd > 0) {
    ?>
    <table id="table_dominio">
        <thead>
            <tr>
                <th style="text-align: center;"><input type="checkbox" name="seleciona_tudo" id="seleciona_tudo" onclick="selecionaTudoChecked()"/></th>
                <th>Data Cad.</th>
                <th>
                    Dominio
                </th>
                <th>
                    Responsável
                </th>
                <th>
                    Documento
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($dominio = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td style="text-align: center;"><input onclick="verificaCheckbox();" class="check_dominio" type="checkbox" name="dominios[]" value="<?=$dominio["coddominio"]?>"/></td>
                    <td data-order="<?= $dominio["dtcadastro"] ?>">
                        <?= $dominio["dtcadastro2"] ?>
                    </td>
                    <td>
                        <a target="_blank" title="clique para abrir" href="http://<?= ($dominio["dominio"]) ?>"><?= ($dominio["dominio"]) ?></a>
                    </td>
                    <td><?= ($dominio["razao"]) ?></td>
                    <td><?= ($dominio["cnpj"]) ?></td>
                    <td>
                        <?php
                        echo '<a href="Dominio.php?coddominio=', $dominio["coddominio"], '" title="Clique aqui para editar"><img style="width: 20px;" src="./recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="javascript: excluirDominio(', $dominio["coddominio"], ')" title="Clique aqui para excluir"><img style="width: 20px;" src="./recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
