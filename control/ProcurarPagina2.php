<?php
include "../model/Conexao.php";
$conexao = new Conexao();

$and = '';
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and pagina.nome like '%{$_POST["nome"]}%'";
}
$res = $conexao->comando('select * from pagina where 1 = 1 ' . $and . ' order by nome');
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>
    <table width="100%" id="table_pagina">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" name="marcarTudo" id="marcarTudo" onclick="marcarTudoPagina();" value="s"/>
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Link
                </th>
                <th>
                    Titulo
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pagina = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <input class="checkbox_pagina" type="checkbox" name="paginas_selecao[]" value="<?= $pagina["codpagina"] ?>"/>
                    </td>
                    <td>
                        <?= utf8_encode($pagina["nome"]) ?>
                    </td>
                    <td>
                        <?= $pagina["link"] ?>
                    </td>
                    <td>
                        <?= utf8_encode($pagina["titulo"]) ?>
                    </td>
                    <td>
                        <?php
                        $arrayJavascript = "new Array('{$pagina["codpagina"]}', '{$pagina["nome"]}', '" . utf8_encode($pagina["titulo"]) . "', '{$pagina["link"]}', '{$pagina["codmodulo"]}', '{$pagina["abreaolado"]}')";
                        echo '<a href="javascript:setaEditarPagina(', $arrayJavascript, ')" title="Clique aqui para editar"><img src="./recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirPagina(', $pagina["codpagina"], ')" title="Clique aqui para excluir"><img src="./recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    <?php
    $classe_linha = "even";
}
?>