<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Dominio{
    
    public $coddominio;
    public $dominio;
    public $cnpj;
    public $razao;
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
        return $this->conexao->inserir("dominio", $this);
    }
    
    public function atualizar(){      
        return $this->conexao->atualizar("dominio", $this);
    }  
    
    public function excluir(){
        return $this->conexao->excluir("dominio", $this);
    }
    
    public function procuraCodigo(){
        return $this->conexao->procurarCodigo("dominio", $this);
    }
    
    public function procurar($post, $limit = '', $rand = false){
        $and = '';
        $orderby = '';
        if(isset($post["pesquisado"]) && $post["pesquisado"] != NULL && $post["pesquisado"] == "n"){
            $and .= " and (cnpj = '' or razao = '') and jafoi = 'n'";
        }elseif(isset($post["pesquisado"]) && $post["pesquisado"] != NULL && $post["pesquisado"] == "s"){
           $and .= " and cnpj <> ''"; 
        }
        if(isset($post["jafoi_receita"]) && $post["jafoi_receita"] != NULL && $post["jafoi_receita"] != ""){
            $and .= " and jafoi_receita = '{$post["jafoi_receita"]}' and cnpj <> ''";
        }
        if(isset($post["coddominio"]) && $post["coddominio"] != NULL && $post["coddominio"] != ""){
            $and .= " and dominio.coddominio = '{$post["coddominio"]}'";
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
            $and .= " and data1 >= '{$post["data1"]}'";
        }
        if(isset($post["data2"]) && $post["data2"] != NULL && $post["data2"] != ""){
            $and .= " and dtcadastro <= '{$post["data2"]}'";
        }
        if($limit != ""){
            $limit = ' limit '. $limit;
        }
        if($rand){
            $orderby = ' order by rand()';
        }
        $sql = "select coddominio, dominio, cnpj, dtcadastro, 
            razao, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2,
            situacao, capital_social, telefone, uf, municipio, bairro, cep, 
            complemento, numero, logradouro, natureza_juridica, abertura,
            atividade_principal_texto, atividade_principal_code
        from dominio where 1 = 1 ". $and. $orderby. $limit;
        return $this->conexao->comando($sql);
    }
    

    public function AbreSite($url, $dados = NULL) {
        $site_url = $url;
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $site_url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        if (isset($dados) && $dados != NULL) {
            //parametros em post
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
        }
        ob_start();
        curl_exec($ch);
        curl_close($ch);
        $file_contents = ob_get_contents();
        ob_end_clean();
        return $file_contents;
    }   
    
    public function soNumero($str) {
        return preg_replace("/[^0-9]/", "", $str);
    }      
}