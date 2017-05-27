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

$conexao  = new Conexao();
$dominio  = new Dominio($conexao);
$resDominio = $dominio->procurar($_POST);
$qtdDominio = $conexao->qtdResultado($resDominio);

if ($qtdDominio > 0) {
    $nome = "Relatório de Dominio";
    $html  = '<table width="100%" id="table_dominio">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Dt. Cadastro</th>';
    $html .= '<th>Dominio</th>';
    $html .= '<th>Documento</th>';
    $html .= '<th>Responsavel</th>';
    if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
        $html .= '<th>Abertura</th>';
        $html .= '<th>Logradouro</th>';
        $html .= '<th>Num</th>';
        $html .= '<th>Compl.</th>';
        $html .= '<th>CEP</th>';
        $html .= '<th>Bairro</th>';
        $html .= '<th>Municipio</th>';
        $html .= '<th>UF</th>';
        $html .= '<th>Telefone</th>';
        $html .= '<th>Cap. Social</th>';
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            $html .= '<th>'.utf8_decode('Situação').'</th>';
        }else{
            $html .= '<th>Situação</th>';
        }        
        $html .= '<th>Nat. Juridica</th>';
        $html .= '<th>At. Princ. Codigo</th>';
        $html .= '<th>At. Princ. Desc.</th>';

    }
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    while ($dominio = $conexao->resultadoArray($resDominio)) {
        $html .= '<tr>';
        $html .= '<td>' . ($dominio["dtcadastro2"]) . '</td>';
        $html .= '<td>' . ($dominio["dominio"]) . '</td>';
        $html .= '<td>' . ($dominio["cnpj"]) . '</td>';
        $html .= '<td>';
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            $html .= utf8_decode($dominio["razao"]);
        }else{
            $html .= ($dominio["razao"]);
        }
        $html .= '</td>';
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            if(isset($dominio["abertura"]) && $dominio["abertura"] != NULL && $dominio["abertura"] != "0000-00-00"){
                $html .= '<td>' . date("d/m/Y",$dominio["abertura"]) . '</td>';
            }else{
                $html .= '<td> -- </td>';
            }
            $html .= '<td>' . $dominio["logradouro"] . '</td>';
            $html .= '<td>' . $dominio["numero"] . '</td>';
            $html .= '<td>' . $dominio["complemento"] . '</td>';
            $html .= '<td>' . $dominio["cep"] . '</td>';
            $html .= '<td>' . $dominio["bairro"] . '</td>';
            $html .= '<td>' . $dominio["municipio"] . '</td>';
            $html .= '<td>' . $dominio["uf"] . '</td>';
            $html .= '<td>' . $dominio["telefone"] . '</td>';
            $html .= '<td>' . number_format($dominio["capital_social"], 2, ',', '.') . '</td>';
            $html .= '<td>' . $dominio["situacao"] . '</td>';
            $html .= '<td>' . $dominio["natureza_juridica"] . '</td>';
            $html .= '<td>' . $dominio["atividade_principal_code"] . '</td>';
            $html .= '<td>';
            if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
                $html .= utf8_decode($dominio["atividade_principal_texto"]);
            }else{
                $html .= ($dominio["atividade_principal_texto"]);
            }
            $html .= '</td>';            
        }
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
    echo '<script>alert("Sem dominios encontrados!");window.close();</script>';
}
