<?php

ini_set('display_errors', 0 );
error_reporting(0);

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
$pessoa = new Pessoa($conexao);
$res = $pessoa->procurar($_POST);
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    $html = "";
    $nome = 'Relatório Pessoas';
    $html .= '<table class="responstable" style="width: 100%; font-size: 12px">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Nome</th>';
    $html .= '<th>Nivel</th>';
    $html .= '<th>Dt. Cadastro</th>';
    $html .= '<th>E-mail</th>'; 
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    while ($pessoa = $conexao->resultadoArray($res)) {   
        $html .= '<tr>';
        $html .= '<td>' . $pessoa["nome"] . '</td>';
        $html .= '<td style="text-align: center;">' . $pessoa["nivel"] . '</td>';
        $html .= '<td style="text-align: center;">' . $pessoa["dtcadastro2"] . '</td>';
        $html .= '<td style="text-align: center;">' . $pessoa["email"] . '</td>';
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
    echo '<script>alert("Sem pessoa encontrada!");window.close();</script>';
}
