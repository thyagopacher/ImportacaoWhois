<?php

session_start();
set_time_limit(0);
header('Content-Type: text/html; charset=utf-8');

include("./excel/reader.php");
$data = new Spreadsheet_Excel_Reader();

function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}
$qtdjatinha = 0;
$qtdimportado = 0;
$msg_retorno = '';
$sit_retorno = true;
try {
    if (isset($_FILES['arquivo'])) {
        if (!empty($_FILES['arquivo']) && $_FILES['arquivo']['type'] != "application/vnd.ms-excel" && $_FILES["arquivo"]["type"] != "application/octet-stream") {
            $msg_retorno = "Só pode arquivo em formado XLS!";
            $sit_retorno = false;
        } else {
     
            $conexao = new Conexao();

            $data->setOutputEncoding('UTF-8');
            $data->read($_FILES['arquivo']['tmp_name']);
            
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                
                if (!isset($data->sheets[0]['cells'][$i])) {
                    break;
                }
                $site = trim($data->sheets[0]['cells'][$i][1]);
                if (!isset($site) || $site == NULL || $site == '') {
                    continue;
                }

                $sql = "select coddominio from dominio where dominio = '{$site}'";
                $dominiop = $conexao->comandoArray($sql);
                if (!isset($dominiop["coddominio"]) || $dominiop["coddominio"] == NULL || $dominiop["coddominio"] == "") {
                    $dominio = new Dominio($conexao);
                    $dominio->dominio = $site;
                    $resInserirDominio = $dominio->inserir();
                    if ($resInserirDominio == FALSE) {
                        die(json_encode(array('mensagem' => 'Erro ao inserir produto causado por:' . $conexao->mostraErro(), 'situacao' => false)));
                    } else {
                        $qtdimportado++;
                    }                    
                } else {
                    $qtdjatinha++;
                }
            }
        }
    } else {
        $msg_retorno = "Sem arquivo não é possivel realizar importação!";
        $sit_retorno = false;
    }

    if ($sit_retorno) {
        $msg_retorno = "Importação realizada com sucesso:
        - {$qtdjatinha} dominios já cadastrados";
        if ($qtdimportado > 0) {
            $msg_retorno .= "
             - {$qtdimportado} dominios importados";
        }
    }
} catch (Exception $ex) {
    $sit_retorno = false;
    $msg_retorno = "Erro ao realizar importação causado por:" . $ex;
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
