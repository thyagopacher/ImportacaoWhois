<?php

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
$url = 'https://www.receitaws.com.br/v1/cnpj/';
$conexao = new Conexao();
$dominio = new Dominio($conexao);
$post['jafoi_receita'] = 'n';
$res = $dominio->procurar($post, 300, true);
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    while ($dominiop = $conexao->resultadoArray($res)) {
        echo "CNPJ: {$dominiop["cnpj"]}<br>";
        if (isset($dominiop["cnpj"]) && $dominiop["cnpj"] != NULL && $dominiop["cnpj"] != "") {
            $urlFinal = $url . $dominiop["cnpj"];
            $resultado = json_decode($dominio->AbreSite($urlFinal, $dados));
            echo $urlFinal . '<br>';
            $dominio = new Dominio($conexao);
            $dominio->coddominio = $dominiop["coddominio"];
            $dominio->situacao = $resultado->situacao;
            $dominio->capital_social = number_format($resultado->capital_social, 2, '.', '');
            echo "CAP: {$resultado->capital_social} CAP2: {$dominio->capital_social} para CNPJ: {$dominiop["cnpj"]}<br>";
            $dominio->telefone = $resultado->telefone;
            $dominio->uf = $resultado->uf;
            $dominio->municipio = $resultado->municipio;
            $dominio->bairro = $resultado->bairro;
            $dominio->cep = $resultado->cep;
            $dominio->complemento = $resultado->complemento;
            $dominio->numero = $resultado->numero;
            $dominio->logradouro = $resultado->logradouro;
            $dominio->natureza_juridica = $resultado->natureza_juridica;
            if (isset($resultado->abertura) && $resultado->abertura != NULL && $resultado->abertura != "") {
                $dominio->abertura = date("Y-m-d", strtotime($resultado->abertura));
            }
            $dominio->atividade_principal_texto = $resultado->atividade_principal[0]->text;
            $dominio->atividade_principal_code = $resultado->atividade_principal[0]->code;

            //Atividades Econômicas Secundárias
            if (count($resultado->atividades_secundarias) > 0) {
                foreach ($resultado->atividades_secundarias as $key => $sc) {
                    $secundariap = $conexao->comandoArray("select codsecundaria from secundaria where coddominio = {$dominiop["coddominio"]} and nome = '{$sc->code}'");
                    if (!isset($secundariap["codsecundaria"]) || $secundariap["codsecundaria"] == NULL || $secundariap["codsecundaria"] == "") {
                        $secundaria = new Secundaria($conexao);
                        $secundaria->coddominio = $dominiop["coddominio"];
                        $secundaria->codigo = $sc->code;
                        $secundaria->texto = $sc->text;
                        $resInserirSec = $secundaria->inserir();
                        if ($resInserirSec == FALSE) {
                            die("Erro ao inserir secundária via receita!" . mysqli_error($conexao->conexao));
                        } else {
                            echo "Adic. secundaria: {$dominiop["coddominio"]}<br>";
                            $qtdSecundaria++;
                        }
                    }
                }
            }

//        print_r($resultado->qsa);
            //Quadro de Sócios e Administradores
            if (count($resultado->qsa) > 0) {
                foreach ($resultado->qsa as $key => $qsa) {
                    $sql = "select codsocio from socio where coddominio = {$dominiop["coddominio"]} and nome = '{$qsa->nome}'";
                    $sociop = $conexao->comandoArray($sql);
                    if (!isset($sociop["codsocio"]) || $sociop["codsocio"] == NULL || $sociop["codsocio"] == "") {
                        $socio = new Socio($conexao);
                        $socio->coddominio = $dominiop["coddominio"];
                        $socio->qual = $qsa->qual;
                        $socio->qual_rep_legal = $qsa->qual_rep_legal;
                        $socio->nome_rep_legal = $qsa->nome_rep_legal;
                        $socio->nome = $qsa->nome;
                        $resInserirSocio = $socio->inserir();
                        if ($resInserirSocio == FALSE) {
                            die("Erro ao inserir sócios via receita!" . mysqli_error($conexao->conexao));
                        } else {
                            echo "Adic. socio: {$dominiop["coddominio"]}<br>";
                            $qtdSocios++;
                        }
                    }
                }
            }

            $dominio->jafoi_receita = 's';
            $resAtualiza = $dominio->atualizar();
            if ($resAtualiza == FALSE) {
                die("Erro ao atualizar dominio via receita!" . mysqli_error($conexao->conexao));
            } else {
                echo "Dominio: {$dominiop["coddominio"]} atualizado<br>";
                $qtdAtualizado++;
            }
        }
    }
}