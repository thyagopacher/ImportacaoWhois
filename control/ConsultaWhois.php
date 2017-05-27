<?php

set_time_limit(0);
/*
 * @author Thyago Henrique Pacher - thyago.pacher@gmail.com
 */
header('Content-type: text/html; charset=iso-8859-1');
include('phpQuery-onefile.php');

function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$nome = '';
$qtdAtualizado = '';
$url = 'https://registro.br/2/whois';
$conexao = new Conexao();
$dominio = new Dominio($conexao);
$post['pesquisado'] = 'n';
$resDominio = $dominio->procurar($post, 30, true);
$qtdDominio = $conexao->qtdResultado($resDominio);

if ($qtdDominio > 0) {
    while ($dominiop = $conexao->resultadoArray($resDominio)) {
        $nome = '';
        $documento = '';
        $dados['qr'] = $dominiop["dominio"];
        $resultado = $dominio->AbreSite($url, $dados);

        phpQuery::newDocumentHTML($resultado, $charset = 'utf-8');

        $dados = array(
            'informacoes' => trim(pq('.col-md-9')->html())
        );

        $info = utf8_decode(strip_tags($dados["informacoes"]));
        $info = str_replace('Ã§Ã£o', 'ção', $info);
        $info = str_replace('Ã£', 'ã', $info);
        $separa_info = explode(' ', $info);

        $posDoc = 0;
        $documento = '';
        foreach ($separa_info as $key => $info2) {
            if (isset($info2) && $info2 != NULL && $info2 != "" && strstr($info2, '.')) {
                $info2 = $dominio->soNumero($info2);
                if(!isset($info2) || $info2 == NULL || $info2 == ""){
                    continue;
                }
                $documento = $info2;
                $posDoc = $key;
                break;
            }
        }
        for ($i = 0; $i < $posDoc; $i++) {
            $nome .= $separa_info[$i] . ' ';
        }

        $dominio = new Dominio($conexao);
        $dominio->coddominio = $dominiop["coddominio"];
        if($nome != ""){
            $dominio->razao = trim($nome);
        }
        if($documento != ""){
            $dominio->cnpj = trim($documento);
        }
        $dominio->jafoi = 's';
        $resAtualiza = $dominio->atualizar();
        if ($resAtualiza == FALSE) {
            die("Erro ao atualizar dominio via whois!" . mysqli_error($conexao->conexao));
        } else {
            $qtdAtualizado++;
        }
    }
    echo "Qtd. atualizado: $qtdAtualizado<br>";
}