<?php
//    ini_set('display_errors', 0);
//    ini_set('display_startup_erros', 0);

    set_time_limit(0);
    include "../control/mpdf/mpdf.php";
    define('MPDF_PATH', '../control/mpdf/');
    if(isset($paisagem) && $paisagem != NULL && $paisagem != ''){
        $mpdf=new mPDF('utf-8', 'A4-L', 0, '', 0, 0, 0, 0, 0, 0);
    }else{
        $mpdf = new mPDF();
    }
    
    $mpdf->useSubstitutions = false;
    $mpdf->simpleTables = true;
    $mpdf->SetDisplayMode("fullpage");
    
    if($ncabecalho != true){
        $empresa    = $conexao->comandoArray("select logo, razao from empresa where codempresa = 1");
        $cabecalho  = '<h3 style="width: 220px; margin: 0 auto;">'.$nome.'</h3>';    
        $mpdf->WriteHTML('<link rel="stylesheet" href="visao/recursos/css/tabela.min.css" type="text/css"><style>.nrelatorio{display: none}</style>'.$cabecalho, 0, true, false);
    }
    
    $mpdf->WriteHTML($html, 0, true, false);
    if($ncabecalho != true){
        $mpdf->SetHTMLFooter('<p style="float: left; color: black;width: 180px;text-align: left; font-size: 12px;">Data: '.date('d/m/Y H:i:s').'</p><p style="float: right; color: grey;width: 10%;text-align: right;">@ '.($empresa["razao"]).'</p>');
    }
    $mpdf->charset_in='windows-1252';
    $mpdf->Output();
    

    
