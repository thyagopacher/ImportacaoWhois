<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Secundaria {

    public $codsecundaria;
    public $coddominio;
    public $codigo;
    public $dtcadastro;
    public $texto;
    private $conexao;

    public function __construct($conn) {
        $this->conexao = $conn;
    }

    public function __destruct() {
        unset($this);
    }

    public function inserir() {
        if (!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == "") {
            $this->dtcadastro = date('Y-m-d H:i:s');
        }
        return $this->conexao->inserir("secundaria", $this);
    }

    public function atualizar() {
        return $this->conexao->atualizar("secundaria", $this);
    }

    public function excluir() {
        return $this->conexao->excluir("secundaria", $this);
    }

    public function procuraCodigo() {
        return $this->conexao->procurarCodigo("secundaria", $this);
    }

    public function procurar($post, $limit = '', $rand = false) {
        $and = '';
        $orderby = '';
        if (isset($post["pesquisado"]) && $post["pesquisado"] != NULL && $post["pesquisado"] == "n") {
            $and .= " and (cnpj = '' or razao = '') and jafoi = 'n'";
        } elseif (isset($post["pesquisado"]) && $post["pesquisado"] != NULL && $post["pesquisado"] == "s") {
            $and .= " and cnpj <> ''";
        }
        if (isset($post["codigo"]) && $post["codigo"] != NULL && $post["codigo"] != "") {
            $and .= " and secundaria.codigo like '%{$post["codigo"]}%'";
        }
        if (isset($post["cnpj"]) && $post["cnpj"] != NULL && $post["cnpj"] != "") {
            $and .= " and cnpj like '%{$post["cnpj"]}%'";
        }
        if (isset($post["jafoi_receita"]) && $post["jafoi_receita"] != NULL && $post["jafoi_receita"] != "") {
            $and .= " and jafoi_receita = '{$post["jafoi_receita"]}'";
        }
        if (isset($post["cnpj"]) && $post["cnpj"] != NULL && $post["cnpj"] != "") {
            $and .= " and cnpj like '%{$post["cnpj"]}%'";
        }
        if (isset($post["razao"]) && $post["razao"] != NULL && $post["razao"] != "") {
            $and .= " and razao like '%{$post["razao"]}%'";
        }
        if (isset($post["dominio"]) && $post["dominio"] != NULL && $post["dominio"] != "") {
            $and .= " and dominio like '%{$post["dominio"]}%'";
        }
        if (isset($post["data1"]) && $post["data1"] != NULL && $post["data1"] != "") {
            $and .= " and secundaria.dtcadastro >= '{$post["data1"]}'";
        }
        if (isset($post["data2"]) && $post["data2"] != NULL && $post["data2"] != "") {
            $and .= " and secundaria.dtcadastro <= '{$post["data2"]}'";
        }
        if ($limit != "") {
            $limit = ' limit ' . $limit;
        }
        if ($rand) {
            $orderby = ' order by rand()';
        }
        $sql = "select distinct codsecundaria, texto, secundaria.dtcadastro, DATE_FORMAT(secundaria.dtcadastro, '%d/%m/%Y') as dtcadastro2,
            secundaria.codigo, dominio.cnpj
        from secundaria
        inner join dominio on dominio.coddominio = secundaria.coddominio
        where 1 = 1 " . $and . $orderby . $limit;
        return $this->conexao->comando($sql);
    }

}
