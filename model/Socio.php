<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Socio{
    
    public $codsocio;
    public $coddominio;
    public $qual;
    public $qual_rep_legal;
    public $nome_rep_legal;
    public $nome;
    public $dtcadastro;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        if(!isset($this->dtcadastro) || $this->dtcadastro == NULL || $this->dtcadastro == ""){
            $this->dtcadastro = date('Y-m-d H:i:s');
        }         
        return $this->conexao->inserir("socio", $this);
    }
    
    public function atualizar(){      
        return $this->conexao->atualizar("socio", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("socio", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("socio", $this);
    }
    
    public function procurar($post, $limit = '', $rand = false){
        $and = '';
        $orderby = '';
        if(isset($post["pesquisado"]) && $post["pesquisado"] != NULL && $post["pesquisado"] == "n"){
            $and .= " and (cnpj = '' or razao = '') and jafoi = 'n'";
        }elseif(isset($post["pesquisado"]) && $post["pesquisado"] != NULL && $post["pesquisado"] == "s"){
           $and .= " and cnpj <> ''"; 
        }
        if(isset($post["cnpj"]) && $post["cnpj"] != NULL && $post["cnpj"] != ""){
            $and .= " and cnpj like '%{$post["cnpj"]}%'";
        }
        if(isset($post["jafoi_receita"]) && $post["jafoi_receita"] != NULL && $post["jafoi_receita"] != ""){
            $and .= " and jafoi_receita = '{$post["jafoi_receita"]}'";
        }
        if(isset($post["cnpj"]) && $post["cnpj"] != NULL && $post["cnpj"] != ""){
            $and .= " and cnpj like '%{$post["cnpj"]}%'";
        }
        if(isset($post["razao"]) && $post["razao"] != NULL && $post["razao"] != ""){
            $and .= " and razao like '%{$post["razao"]}%'";
        }
        if(isset($post["dominio"]) && $post["dominio"] != NULL && $post["dominio"] != ""){
            $and .= " and dominio like '%{$post["dominio"]}%'";
        }
        if(isset($post["data1"]) && $post["data1"] != NULL && $post["data1"] != ""){
            $and .= " and socio.dtcadastro >= '{$post["data1"]}'";
        }
        if(isset($post["data2"]) && $post["data2"] != NULL && $post["data2"] != ""){
            $and .= " and socio.dtcadastro <= '{$post["data2"]}'";
        }
        if($limit != ""){
            $limit = ' limit '. $limit;
        }
        if($rand){
            $orderby = ' order by rand()';
        }
        $sql = "select distinct codsocio, nome, socio.dtcadastro, DATE_FORMAT(socio.dtcadastro, '%d/%m/%Y') as dtcadastro2,
            qual, qual_rep_legal, nome_rep_legal, dominio.cnpj
        from socio
        inner join dominio on dominio.coddominio = socio.coddominio
        where 1 = 1 ". $and. $orderby. $limit;
        return $this->conexao->comando($sql);      
    }
      
}