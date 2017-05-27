<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

date_default_timezone_set('America/Sao_Paulo');

ini_set('mysql.connect_timeout', 0);
ini_set('default_socket_timeout', 0);
ini_set('mysql.charset', 'UTF-8');

set_time_limit(0);

class Conexao {

    public $host = 'mysql.hostinger.com.br';
    public $usuario = 'u258242175_domin';
    public $senha = 'gbzlft17';
    public $banco = 'u258242175_domin';
    public $porta = '3306';
    private $resultado;
    public $colunas = array();
    public $chave_primaria;
    public $conexao;

    function __construct($usuario = null, $senha = null, $enderecoip = null, $banco = null) {
        if (isset($banco) && $banco != NULL && $banco != "") {
            $this->banco = $banco;
        }
        if (isset($enderecoip) && $enderecoip != NULL && $enderecoip != "") {
            $this->host = $enderecoip;
        }
        if (isset($usuario) && $usuario != NULL && $usuario != "") {
            $this->usuario = $usuario;
        }
        if (isset($senha) && $senha != NULL && $senha != "") {
            $this->senha = $senha;
        }
        $this->conectar();
    }

    function __destruct() {
        if ($this->conexao != FALSE) {
            mysqli_close($this->conexao);
        }
    }

    public function conectar() {
        $this->conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->banco, $this->porta);
        if (isset($this->conexao) && $this->conexao != NULL) {
            mysqli_set_charset($this->conexao, 'utf8');
        } elseif (!$this->conexao) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
    }

    /* retorna mysql_query */

    public function comando($query) {
        return mysqli_query($this->conexao, $query);
    }

    public function comandoArray($query) {
        return mysqli_fetch_array($this->comando($query));
    }

    /*     * retorna a quantidade de resultados da consulta */

    public function qtdResultado($resultado) {
        return mysqli_num_rows($resultado);
    }

    public function resultadoArray($resultado = null) {
        if ($resultado != NULL) {
            $this->resultado = $resultado;
        }
        return mysqli_fetch_array($this->resultado);
    }

    public function inserir($tabela, $objeto) {
        $valores = '';
        $campos = '';
        $res = $this->comando('describe ' . $tabela);
        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {

                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];

                if (($campoChave != 'PRI' || $campoNome == "codempresa") && isset($objeto->$campoNome) && $objeto->$campoNome != NULL && $objeto->$campoNome != '') {
                    $objeto->$campoNome = addslashes($objeto->$campoNome);
                    $campos .= "{$campoNome},";
                    if ($campoNome == "dtcadastro" && ($objeto->$campoNome == NULL || $objeto->$campoNome == "")) {
                        $valores .= '"' . date("Y-m-d H:i:s") . '",';
                    } elseif ($campo['Type'] === 'text') {
                        $valores .= '"' . $objeto->$campoNome . '",';
                    } elseif ($campo['Type'] === 'date' && strpos($campo['Type'], '/')) {
                        $valores .= '"' . implode('-', array_reverse(explode('/', $objeto->$campoNome))) . '",';
                    } elseif ($campo['Type'] === 'double' && strpos($objeto->$campoNome, ',')) {
                        $valores .= '"' . str_replace(',', '.', $objeto->$campoNome) . '",';
                    } elseif ($campo['Type'] == "int(11)") {
                        $valores .= '"' . (int) $objeto->$campoNome . '",';
                    } elseif ($campoNome == "codempresa" && ($objeto->$campoNome == NULL || $objeto->$campoNome == "")) {
                        $valores .= '"' . (int) $_SESSION["codempresa"] . '",';
                    } elseif ($campoNome == "codfuncionario" && ($objeto->$campoNome == NULL || $objeto->$campoNome == "")) {
                        $valores .= '"' . (int) $_SESSION["codpessoa"] . '",';
                    } else {
                        $valores .= '"' . $objeto->$campoNome . '",';
                    }
                }
            }
        }

        $sql = 'insert into ' . $tabela . '(' . substr($campos, 0, strlen(trim($campos)) - 1) . ') values(' . substr($valores, 0, strlen(trim($valores)) - 1) . ')';
        $resInserir = mysqli_query($this->conexao, $sql);

        return $resInserir;
    }

    public function atualizar($tabela, $objeto) {
        $setar = '';
        $where = '';
        $chavePrimaria = 0;
        $res = $this->comando('describe ' . $tabela);
        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {
                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];
                $objeto->$campoNome = addslashes($objeto->$campoNome);
                if ($campoChave != 'PRI' && isset($objeto->$campoNome) && $objeto->$campoNome != NULL && $objeto->$campoNome != '') {

                    if ($campo['Type'] === 'text') {
                        $setar .= $campoNome . ' = "' . $objeto->$campoNome . '", ';
                    } elseif ($campo['Type'] === 'date' && strpos($campo['Type'], '/')) {
                        $setar .= $campoNome . ' = "' . implode('-', array_reverse(explode('/', $objeto->$campoNome))) . '", ';
                    } elseif ($campo['Type'] === 'double' && strpos($objeto->$campoNome, ',')) {
                        $setar .= $campoNome . ' = "' . (double) str_replace(',', '.', $objeto->$campoNome) . '", ';
                    } elseif ($campo['Type'] == "int(11)") {
                        $setar .= $campoNome . ' = "' . (int) $objeto->$campoNome . '", ';
                    } else {
                        $setar .= $campoNome . ' = "' . $objeto->$campoNome . '", ';
                    }
                } elseif ($campoChave === 'PRI') {
                    $chavePrimaria = $objeto->$campoNome;
                    $where .= $campoNome . ' = "' . $chavePrimaria . '"';
                }
            }
        }
        if ($setar != NULL && $setar != "") {
            $sql = 'update ' . $tabela . ' set ' . substr($setar, 0, strlen(trim($setar)) - 1) . ' where ' . $where;
            return mysqli_query($this->conexao, $sql);
        }
    }

    public function excluir($tabela, $objeto) {
        $where = '';
        $res = $this->comando('describe ' . $tabela);
        $chavePrimaria = 0;
        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {
                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];
                if ($campoChave == 'PRI') {
                    $chavePrimaria = $objeto->$campoNome;
                    $where .= $campoNome . '= "' . $chavePrimaria . '"';
                    break;
                }
            }
        }

        $sql = 'delete from ' . $tabela . ' where ' . $where;
        return mysqli_query($this->conexao, $sql);
    }

    public function procurarCodigo($tabela, $objeto) {
        $where = '';
        $res = $this->comando('describe ' . $tabela);
        $qtdTabela = $this->qtdResultado($res);
        if ($qtdTabela > 0) {
            while ($campo = $this->resultadoArray($res)) {
                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];
                if ($campoChave == 'PRI') {
                    $where .= $campoNome . '= "' . $objeto->$campoNome . '"';
                    break;
                }
            }
        }

        $sql = 'select * from ' . $tabela . ' where ' . $where;
        return $this->comandoArray($sql);
    }

    /**
     * método para montagem de pesquisa em geral
     * @param string $tabela passa o nome da tabela a ser pesquisada
     * @param array $colunas passa o array das colunas a serem usadas
     * @param array $post para ser usado no filtro
     * */
    public function procurarGeral($tabela, $colunas, $post) {
        $chave_primaria = "cod{$tabela}";
        $colunas_separadas = implode(",", $colunas);
        foreach ($post as $key => $valorPost) {
            if (!is_array($valorPost)) {
                $where .= " and {$tabela}.{$key} = '{$valorPost}'";
            } elseif (count($valorPost) == 2) {//para datas nos filtros
                $where .= " and {$tabela}.{$key} >= '{$valorPost[0]}'";
                $where .= " and {$tabela}.{$key} <= '{$valorPost[1]}'";
            } elseif (count($valorPost) > 2) {//para combos multiple
                $where .= " and {$tabela}.{$key} in(" . implode(",", $valorPost) . ")";
            }
        }
        foreach ($colunas as $key => $coluna) {
            if (strstr($coluna, 'cod') != FALSE) {
                $apelidoTabela2 = str_replace('cod', '', $coluna);
                if ($coluna == "codmorador" || $coluna == "codpessoa" || $coluna == "codfuncionario") {
                    $colunas_separadas .= ", {$apelidoTabela2}.apartamento, {$apelidoTabela2}.bloco, {$apelidoTabela2}.nome as {$apelidoTabela2}";
                } elseif ($coluna == "codcategoria" || $coluna == "codstatus" || $coluna == "codtipo" || $coluna == "codnivel") {
                    $colunas_separadas .= ", {$apelidoTabela2}.nome as {$apelidoTabela2}";
                } elseif ($coluna == "dtcadastro" || $coluna == "dtnascimento" || $coluna == "data") {
                    $colunas_separadas .= ", DATE_FORMAT({$apelidoTabela2}.{$coluna}, '%d/%m/%y %H:%i') as {$coluna}2";
                }
                $innerJoin .= " inner join {$apelidoTabela2} on {$apelidoTabela2}.{$coluna} = {$tabela}.{$coluna}";
            }
        }
        $sql = "select {$tabela}.{$chave_primaria} {$colunas_separadas} from {$tabela} {$innerJoin} where 1 = 1 {$where}";
        return $this->comando($sql);
    }

}
