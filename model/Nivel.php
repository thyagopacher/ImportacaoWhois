<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Nivel{
    
    public $codnivel;
    public $nome;
    public $codempresa;
    public $codfuncionario;
    public $nivel_padrao = array("Adm. Condominio", "Morador", "Sindico");
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    
    
    public function inserir(){
        return $this->conexao->comando("insert into nivel(nome, codempresa, codfuncionario) values('{$this->nome}', '{$this->codempresa}', '{$this->codfuncionario}');");
    }
    
    public function atualizar(){
        return $this->conexao->comando("update nivel set nome = '{$this->nome}', codempresa = '{$this->codempresa}', codfuncionario = '{$this->codfuncionario}' where codnivel = '{$this->codnivel}';");
    }  
    
    public function excluir($codnivel){
        $sql = "delete from nivel where codnivel = '{$codnivel}' and codempresa = '{$this->codempresa}'";
        return $this->conexao->comando($sql);
    }
    
    public function procuraCodigo($codnivel){
        return $this->conexao->comandoArray(("select * from nivel where codnivel = '{$codnivel}' and codempresa = '{$this->codempresa}'"));
    }
    
    public function procuraNome($nome){
        return $this->conexao->comando("select * from nivel where nome like '%{$nome}%' and codempresa = '{$this->codempresa}' order by nome");
    } 
    
    public function procuraNomeExato($nome){
        return $this->conexao->comandoArray("select * from nivel where nome = '{$nome}' and codempresa = '{$this->codempresa}' order by nome");
    } 
    
    public function procuraNome2($nome){
        return $this->conexao->comando("select * from nivel where nome like '%{$nome}%' and codempresa = '{$this->codempresa}' and codnivel <> '1' order by nome");
    } 
    
    public function inseriNivelPadrao(){
        foreach ($this->nivel_padrao as $key => $nivel_padrao) {
            $nivelp = $this->procuraNomeExato($nivel_padrao);
            if(!isset($nivelp) || $nivelp["codnivel"] == NULL || $nivelp["codnivel"] == ""){
                $this->nome = $nivel_padrao;
                $resInserir = $this->inserir();
                if($resInserir == FALSE){
                    return $resInserir;
                }
            }
        }
    }
}