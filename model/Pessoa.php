<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Pessoa {

    public $codpessoa;
    public $nome;
    public $email;
    public $senha;
    public $codnivel;
    public $dtcadastro;
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
        return $this->conexao->inserir("pessoa", $this);
    }

    public function atualizar() {
        return $this->conexao->atualizar("pessoa", $this);
    }

    public function excluir() {
        return $this->conexao->excluir("pessoa", $this);
    }

    public function procuraCodigo() {
        return $this->conexao->procurarCodigo("pessoa", $this);
    }

    public function procurar($post) {
        $and = '';
        if (isset($post["codnivel"]) && $post["codnivel"] != NULL && $post["codnivel"] != "") {
            $and .= " and pessoa.codnivel = '{$post["codnivel"]}'";
        }
        if (isset($post["nome"]) && $post["nome"] != NULL && $post["nome"] != "") {
            $and .= " and pessoa.nome like '%{$post["nome"]}%'";
        }
        if (isset($post["data1"]) && $post["data1"] != NULL && $post["data1"] != "") {
            $and .= " and pessoa.dtcadastro >= '{$post["data1"]}'";
        }
        if (isset($post["data2"]) && $post["data2"] != NULL && $post["data2"] != "") {
            $and .= " and pessoa.dtcadastro <= '{$post["data2"]}'";
        }
        $sql = "select pessoa.codpessoa, pessoa.codnivel, pessoa.dtcadastro, pessoa.nome, pessoa.email, 
        DATE_FORMAT(pessoa.dtcadastro, '%d/%m/%Y') as dtcadastro2, nivel.nome as nivel 
        from pessoa 
        inner join nivel on nivel.codnivel = pessoa.codnivel
        where 1 = 1 " . $and;
        return $this->conexao->comando($sql);
    }

    public function login() {
        $sql = "SELECT nome, codnivel, dtcadastro, codpessoa, imagem
        from pessoa where email = '" . addslashes($this->email) . "' and senha = '" . addslashes($this->senha) . "'";
        return $this->conexao->comandoArray($sql);
    }

}
