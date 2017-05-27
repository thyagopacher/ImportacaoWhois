<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class NivelPagina{
    
    public $codnivel;
    public $codpagina;
    public $inserir;
    public $atualizar;
    public $excluir;
    public $procurar;
    public $mostrar;
    public $gerapdf;
    public $geraexcel;
    private $conexao;
    
    public function __construct($conn) {
        $this->conexao = $conn;
    }
    
    public function __destruct() {
        unset($this);
    }    

    public function salvar($nivelpagina){
        $nivel_pagina = $this->procuraCodigo($this->codnivel, $this->codpagina);
        if(!isset($nivel_pagina) || !isset($nivel_pagina["codnivel"])){
            $retorno = $this->inserir($nivelpagina);
        }else{
            $retorno = $this->atualizar($nivelpagina);
        }   
        return $retorno;
    }
    
    public function inserir(){
        $sql = "insert into nivelpagina(codnivel, codpagina, inserir, atualizar, excluir, mostrar, procurar, gerapdf, geraexcel)
        values('{$this->codnivel}', '{$this->codpagina}', '{$this->inserir}',
       '{$this->atualizar}', '{$this->excluir}', '{$this->mostrar}', '{$this->procurar}', '{$this->gerapdf}', '{$this->geraexcel}');";
        return $this->conexao->comando($sql);
    }
    
    public function atualizar(){
        return $this->conexao->comando("update nivelpagina set 
        codnivel = '{$this->codnivel}', inserir = '{$this->inserir}', atualizar = '{$this->atualizar}',
        excluir = '{$this->excluir}', mostrar = '{$this->mostrar}', procurar = '{$this->procurar}', gerapdf = '{$this->gerapdf}', geraexcel = '{$this->geraexcel}' 
        where codnivel = '{$this->codnivel}' and codpagina = '{$this->codpagina}';");
    }  
    
    public function excluir($codnivel){
        return $this->conexao->comando("delete from nivelpagina where codnivel = '{$codnivel}'");
    }
    
    public function procuraCodigo($codnivel, $codpagina){
        return $this->conexao->comandoArray("SELECT nivelpagina . * 
        FROM nivelpagina
        WHERE nivelpagina.codnivel =  '{$codnivel}'
        AND nivelpagina.codpagina =  '{$codpagina}'");
    }

}