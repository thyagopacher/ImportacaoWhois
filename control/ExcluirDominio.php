<?php

/*
 * @author Thyago Henrique Pacher - thyago.pacher@gmail.com
 */

session_start();
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
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

$msg_retorno = '';
$sit_retorno = true;

if (isset($_POST["dominios"]) && $_POST["dominios"] != NULL && count($_POST["dominios"]) > 0) {
    foreach ($_POST["dominios"] as $key => $coddominio) {
        $conexao->comando("delete from secundaria where coddominio = {$coddominio}");
        $conexao->comando("delete from socio where coddominio = {$coddominio}");
        $dominio = new Dominio($conexao);
        $dominio->coddominio = $coddominio;
        $resExcluir = $dominio->excluir();
        if ($resExcluir === FALSE) {
            $msg_retorno = 'Erro ao excluir dominio! Causado por:' . mysqli_error($conexao->conexao);
            $sit_retorno = false;
            break;
        }
    }
}else{
    $conexao->comando("delete from secundaria where coddominio = {$_POST["coddominio"]}");
    $conexao->comando("delete from socio where coddominio = {$_POST["coddominio"]}");
        
    $dominio = new Dominio($conexao);
    $dominio->coddominio = $_POST["coddominio"];
    $resExcluir = $dominio->excluir();
}


if ($resExcluir == FALSE) {
    $msg_retorno = 'Erro ao excluir dominio! Causado por:' . mysqli_error($conexao->conexao);
    $sit_retorno = false;
} else {
    $msg_retorno = "Dominio excluido com sucesso!";
    $sit_retorno = true;
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
