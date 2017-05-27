<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}

header ('Content-type: text/html; charset=UTF-8');

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

if ($qtd > 0) {
    $html = "";
    $nome = 'Relatório Secundarias';
    $html .= '<table class="responstable" style="width: 100%; font-size: 12px">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Dt. Cadastro</th>';
    $html .= '<th>CNPJ</th>';
    $html .= '<th>Secundaria</th>';
    $html .= '<th>Código</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    while ($secundaria = $conexao->resultadoArray($res)) {   
        $html .= '<tr>';
        $html .= '<td>' . $secundaria["dtcadastro2"] . '</td>';
        $html .= '<td>' . $secundaria["nome"] . '</td>';
        $html .= '<td>' . $secundaria["cnpj"] . '</td>';
        $html .= '<td>' . $secundaria["texto"] . '</td>';
        $html .= '<td>' . $secundaria["codigo"] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';

    $_POST["html"] = preg_replace('/\s+/', ' ', str_replace("> <", "><", $html));
    $paisagem = "sim";
    if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
        include "./GeraExcel.php";
    } else {
        include "./GeraPdf.php";
    }
} else {
    echo '<script>alert("Sem secundaria encontrada!");window.close();</script>';
}
