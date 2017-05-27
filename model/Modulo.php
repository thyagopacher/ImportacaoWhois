<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Modulo{
    
    public $codmodulo;
    public $nome;
    public $titulo;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->inserir("modulo", $this);
    }
    
    public function atualizar(){
        return $this->conexao->atualizar("modulo", $this);
    }  
    
    public function excluir($codmodulo){
        return $this->conexao->comando("delete from modulo where codmodulo = '{$codmodulo}'");
    }
    
    public function procuraCodigo($codmodulo){
        return $this->conexao->comandoArray(("select * from modulo where codmodulo = '{$codmodulo}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from modulo where nome like '%{$nome}%' order by nome");
    } 
    
}