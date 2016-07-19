
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab_1">Limpar Tabelas</a></li>					
                    <li><a data-toggle="tab" href="#tab_2">Back Up Base de Dados</a></li>
                    <!--					<li><a data-toggle="tab" href="#tab_3">Associa Lojas e Grupos</a></li> -->
                </ul>
                <div class="tab-content">
                    <div id="tab_1" class="tab-pane active">
                        <form modelo="lojas" id="form_pedidos" nome="form_pedidos">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-header">
                                            <div class="alert alert-danger alert-dismissable">
                                                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                                                <h4>
                                                    <i class="icon fa fa-ban"></i>
                                                    Alerta!
                                                </h4>
                                                Ao limpar as tabelas, voce ira deletar todos os registros nela contido, certifique-se
                                                que isso nao poderá gerar problemas futuros!
                                            </div>
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <ul class="todo-list">
                                                <li>
                                                    <!-- checkbox -->
                                                    <input type="checkbox" value="1" name="pedidos[]">
                                                    <!-- todo text -->
                                                    <span class="text">Pedidos</span>
                                                </li>
                                                <li>
                                                    <!-- checkbox -->
                                                    <input type="checkbox" value="2" name="pedidos[]">
                                                    <!-- todo text -->
                                                    <span class="text">Produtos</span>
                                                </li>
                                                <li>
                                                    <!-- checkbox -->
                                                    <input type="checkbox" value="3" name="pedidos[]">
                                                    <!-- todo text -->
                                                    <span class="text">Aviso</span>
                                                </li>														
                                            </ul>
                                        </div><!-- /.box-body -->
                                        <div class="box-footer clearfix no-border">
                                            <button type="button" class="btn btn-default pull-right" onclick="confirmar_limpa_pedidos();">Excluir dados </button>
                                        </div>
                                    </div><!-- /.box -->

                                </div>
                            </div>
                        </form>						   
                    </div>
                    <div id="tab_2" class="tab-pane">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="row">											
                                            <div class="col-md-12">
                                                <form action="<?php echo base_url(); ?>wg3/wg3_11/backup" id="form_download" name="form_download">
                                                    <div class="form-group">													
                                                        <div class="input-group">
                                                            <div class="input-group-btn">
                                                                <button class="btn btn-success"  id="gerar_arquivo" type="button">Gerar arquivo</button>
                                                            </div>														
                                                        </div>													
                                                    </div>
                                                </form>
                                            </div>														
                                        </div>								
                                    </div>					       
                                </div>
                            </div>
                        </div>
                    </div>									
                </div>			
            </div>
        </div>	
    </div>
</section>