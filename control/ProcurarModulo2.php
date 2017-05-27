<?php
include "../model/Conexao.php";
$conexao = new Conexao();

$and = '';
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and modulo.nome like '%{$_POST["nome"]}%'";
}
$res = $conexao->comando('select * from modulo where 1 = 1 ' . $and . ' order by nome');
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>
    <table id="table_modulo">
        <thead>
            <tr>
                <th>
                    Nome
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
            <?php while ($modulo = $conexao->resultadoArray($res)) {
                ?>
                <tr>
                    <td>
                        <?= ($modulo["nome"]) ?>
                    </td> 
                    <td>
                        <?= ($modulo["titulo"]) ?>
                    </td>
                    <td>
                        <?php
                        $arrayJavascript = "new Array('{$modulo["codmodulo"]}', '" . ($modulo["nome"]) . "', '" . ($modulo["titulo"]) . "')";
                        echo '<a href="javascript:setaEditarModulo(', $arrayJavascript, ')" title="Clique aqui para editar"><img src="./recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirModulo2(', $modulo["codmodulo"], ')" title="Clique aqui para excluir"><img src="./recursos/img/excluir.png" alt="botão excluir"/></a>';
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