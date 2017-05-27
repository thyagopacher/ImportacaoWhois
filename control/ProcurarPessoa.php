<?php
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
$pessoa = new Pessoa($conexao);
$res = $pessoa->procurar($_POST);
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    echo "Encontrou {$qtd} resultados<br>";
    ?>

    <table id="table_pessoa">
        <thead>
            <tr>
                <th>
                    Data Cad.
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Nivel
                </th>
               
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pessoa = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td data-order="<?= $pessoa["dtcadastro"] ?>">
                        <?= $pessoa["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= ($pessoa["nome"]) ?>
                    </td>
                    <td>
                        <?= ($pessoa["nivel"]) ?>
                    </td>
                   
                    <td>
                        <?php
                        echo '<a href="Pessoa.php?codpessoa=', $pessoa["codpessoa"], '" title="Clique aqui para editar"><img style="width: 20px;" src="./recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="javascript: excluirPessoa(', $pessoa["codpessoa"], ')" title="Clique aqui para excluir"><img style="width: 20px;" src="./recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    <?php
}
?>