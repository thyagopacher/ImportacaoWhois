<form action="../control/ImportacaoDominio.php" id="fdominio" name="fdominio" method="post">
    <input type='hidden' name="tipo" id="tipo" value="pdf"/>
    <div class="row">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Dados Cadastrais</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="importacao">Importação</label>
                            <input type='file' class="form-control" name="arquivo" id="arquivo" required accept=".xls"/>
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-12">
                        * <a target="_blank" href="./recursos/docs/planilha_padrao.xls">Importação padrão</a>
                    </div>
                    <div style="display: none" class="col-md-12 progress">
                        <div id="progressbar" class="progress-bar" role="progressbar" aria-valuenow="70"
                             aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="sronly" class="sr-only">0% Complete</span>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <!--/.col (right) -->
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php
                if ($nivelp["inserir"] == 1 || $nivelp["atualizar"] == 1) {
                    echo '<input type="submit" name="submit" value="Enviar" class="btn btn-primary"/>';
                }
                ?>
            </div>                                        
        </div>
    </div>      
</form>