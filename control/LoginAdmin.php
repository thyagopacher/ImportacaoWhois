<?php

session_start();
function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

if(!isset($_POST["usuario"]) || $_POST["usuario"] == NULL || $_POST["usuario"] == ""){
    die(json_encode(array('mensagem' => 'Por favor preencha e-mail!', 'situacao' => false)));
}
if(!isset($_POST["senha"]) || $_POST["senha"] == NULL || $_POST["senha"] == ""){
    die(json_encode(array('mensagem' => 'Por favor preencha senha!', 'situacao' => false)));
}

$conexao = new Conexao();
$pessoa = new Pessoa($conexao);

$pessoa->email = $_POST["usuario"];
$pessoa->senha = $_POST["senha"];
$pessoa2 = $pessoa->login();
if (!isset($pessoa2["nome"]) || $pessoa2["nome"] == NULL || $pessoa2["nome"] == "") {
    die(json_encode(array('mensagem' => 'Erro ao entrar, e-mail ou senha inválidos!!!', 'situacao' => false)));
} else {

    $_SESSION["dtcadastro"] = $pessoa2["dtcadastro"];
    $_SESSION["codpessoa"] = $pessoa2["codpessoa"];
    $_SESSION["codnivel"] = $pessoa2["codnivel"];
    $_SESSION["dtcadastro"] = $pessoa2["dtcadastro"];
    $_SESSION["nome"] = $pessoa2["nome"];

    die(json_encode(array('mensagem' => 'Logado com sucesso!!!', 'situacao' => true,
        'nome' => $pessoa2["nome"], 'senha' => $_POST["senha"], 'codpessoa' => $pessoa2["codpessoa"],
        'imagem' => $pessoa2["imagem"], 'dtcadastro' => $pessoa2["dtcadastro"])));
}    