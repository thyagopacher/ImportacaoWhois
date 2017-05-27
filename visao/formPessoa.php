<form action="../control/SalvarPessoa.php" id="fpessoa" name="fpessoa" method="post">
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
                    <input type="hidden" name="codpessoa" id="codpessoa" value="<?= $pessoap["codpessoa"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nivel</label>
                            <select name="codnivel" id="codnivel" class="form-control">
                                <?php
                                    $resnivel = $conexao->comando("select codnivel, nome from nivel order by nome");
                                    $qtdnivel = $conexao->qtdResultado($resnivel);
                                    if($qtdnivel > 0){
                                        echo '<option value="">--Selecione--</option>';
                                        while($nivel = $conexao->resultadoArray($resnivel)){
                                            if($nivel["codnivel"] == $pessoap["codnivel"]){
                                                echo '<option selected value="',$nivel["codnivel"],'">',$nivel["nome"],'</option>';
                                            }else{
                                                echo '<option value="',$nivel["codnivel"],'">',$nivel["nome"],'</option>';
                                            }
                                        }
                                    }else{
                                        echo '<option value="">--Nada encontrado--</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type='text' class="form-control" name="nome" id="nome" placeholder="" value="<?php if (isset($pessoap["nome"]) && $pessoap["nome"] != NULL && $pessoap["nome"] != "") {echo $pessoap["nome"];} ?>">
                        </div>
                    </div>
               
                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" name='email' id="email" placeholder="" value="<?php if (isset($pessoap["email"])) {echo $pessoap["email"];
                                   } ?>">
                        </div>  
                    </div>
                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" name='senha' id="senha" readonly onfocus="this.removeAttribute('readonly');" placeholder="" value="<?php if (isset($pessoap["senha"])) {echo $pessoap["senha"];} ?>">
                        </div>  
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="imagem">Imagem</label>
                            <input type='file' class="form-control" name="imagem" id="imagem"/>
                            <?php
                            if(isset($pessoap["imagem"]) && $pessoap["imagem"] != NULL && $pessoap["imagem"] != ""){
                            echo '<a target="_blank" href="../arquivos/', $pessoap["imagem"], '">Foto da pessoa</a>';
                            }
                            ?>
                        </div>                                        
                    </div><!-- /.col -->

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
                    echo '<input type="submit" name="submit" value="Salvar" class="btn btn-primary"/> ';
                    echo '<a href="javascript: btNovoPessoa()" id="btnovoPessoa"  class="btn btn-primary">Novo</a>';
                }
                ?>
            </div>                                        
        </div>
    </div>      
</form>