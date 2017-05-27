<?php
include "validacaoLogin.php";
if(isset($_GET["coddominio"]) && $_GET["coddominio"] != NULL && $_GET["coddominio"] != ""){
    $dominio = $conexao->comandoArray("select * from dominio where coddominio = {$_GET["coddominio"]}");
}
?>  
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Painel ADM.</title>
        <?php include 'head.php';?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">

        <div class="wrapper">

            <?php include "header.php"; ?>
            <?php include "menu.php"; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Gestão de dominios</h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#"><?= $nivelp["modulo"] ?></a></li>
                        <li class="active"><?= $nivelp["pagina"] ?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Cadastro</a></li>
                            <li><a href="#tabs-2">Procurar</a></li>
                            <li><a href="#tabs-3">Sócio</a></li>
                            <li><a href="#tabs-4">Secundárias</a></li>
                        </ul>   
                        <div id="tabs-1"><?php include("formDominio.php"); ?></div>
                        <div id="tabs-2"><?php include("formProcurarDominio.php"); ?></div>
                        <div id="tabs-3"><?php include("formProcurarSocio.php"); ?></div>
                        <div id="tabs-4"><?php include("formProcurarSecundaria.php"); ?></div>
                    </div>

                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php" ?>
        </div><!-- ./wrapper -->

        <?php include './javascriptFinal.php';?>
        <script type="text/javascript" src="./recursos/js/jquery.form.min.js"></script>
        <script type="text/javascript" src="./recursos/js/ajax/Dominio.js?123"></script>
        <script type="text/javascript" src="./recursos/js/ajax/Socio.js?123"></script>
        <script type="text/javascript" src="./recursos/js/ajax/Secundaria.js?123"></script>
        <?php if(isset($_GET['procurar']) && $_GET['procurar'] != NULL && $_GET['procurar'] == "1"){?>
        <script>
            $("#tabs").tabs({active: 1});
            procurarDominio(true);
        </script>        
        <?php }?>
    </body>
</html>
