<?php
include './validacaoLogin.php';
?>
<!DOCTYPE html>
<html> 
    <head>
        <title>Home | Painel</title>
        <?php include 'head.php'; ?>
       
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php include 'header.php'; ?>
            <!-- Left side column. contains the logo and sidebar -->
            <?php include "menu.php"; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dashboard
                        <small>Painel de controle</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <?php
                                    $qtdPessoa = $conexao->comandoArray("select count(1) as qtd from dominio where razao = '' and cnpj = ''");
                                    echo '<h3>', $qtdPessoa["qtd"], '</h3>';
                                    echo 'Novos Dominios';
                                    ?>                                    
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="Dominio.php?procurar=1&pesquisado=n" title="clique para abrir relatório de vendas" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <?php
                                    $qtdPessoa = $conexao->comandoArray("select count(1) as qtd from dominio where razao <> '' and cnpj <> ''");
                                    echo '<h3>', $qtdPessoa["qtd"], '</h3>';
                                    echo 'Dominios já pesquisados';
                                    ?>    
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="Dominio.php?procurar=1&pesquisado=s" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <?php
                                    $qtdPessoa = $conexao->comandoArray('select count(1) as qtd from pessoa');
                                    echo '<h3>', $qtdPessoa["qtd"], '</h3>';
                                    echo 'Usuários Registrados';
                                    ?>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="Pessoa.php?procurar=1" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                        
                    </div><!-- /.row -->
                    
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
<?php include 'footer.php'; ?>
        </div><!-- ./wrapper -->

<?php include './javascriptFinal.php'; ?>

    </body>
</html>
