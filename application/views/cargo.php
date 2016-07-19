<form name="form_cargo" id="form_cargo" modelo="cargo_modelo">   
    <div class="row">     
        <input type="hidden" class="form-control"  name="idCargo" id="idCargo"/>      
        <div class="col-xs-2 col-sm-3">   
            <label for="strCargo">Cargo</label>     
            <input type="text" class="form-control"  name="strCargo" id="strCargo" required="" maxlength="30"/>   
        </div> 
        <div class="col-xs-4 col-sm-5">           
            <label for="strDescricao">Descri&ccedil;&atilde;o</label>    
            <input type="text" class="form-control" name="strDescricao" id="strDescricao" maxlength="60"/> 
        </div>         
        <div class="col-xs-2 col-sm-2">              
            <label for="MinSal">Min. S&aacute;lario</label>       
            <input type="text" class="form-control money2" style="text-align: center" name="MinSal" id="MinSal" required="" maxlength="10"/>  
        </div>   
        <div class="col-xs-2 col-sm-2">     
            <label for="MaxSal">M&aacute;x. S&aacute;lario</label>        
            <input type="text" class="form-control money2" style="text-align: center"  name="MaxSal" id="MaxSal" required="" maxlength="10"/>     
        </div>         
    </div>   
    <br />  
    <div class="row">   
        <div class="col-xs-4 col-sm-2">        
            <input type="submit" value="Salvar" class="form-control btn btn-primary cadastrar_dados"/>   
        </div>            
        <div class="col-xs-4 col-sm-2">   
            <input type="reset" value="Cancelar" class="form-control btn btn-warning"/>   
        </div>                   
        <div class="rcol-xs-4 col-sm-8 form-valida-alert" style="display: none; text-align:center; font-size: 1em"> 
        </div>    
    </div>   
    <hr />  
    <br /> 
    <div class="row">     
        <div class="col-md-12">      
            <div class="table-responsive">    
                <table class="table table-bordered dataTable tabela_requisicao">          
                    <thead>                   
                        <tr>                 
                            <th style="width: 20%">Cargo</th>         
                            <th style="width: 40%">Descri&ccedil;&atilde;o</th>  
                            <th style="width: 15%">Min Salar</th>    
                            <th style="width: 15%;">M&aacute;x Salar</th>  
                            <th style="width: 10%; text-align: center">A&ccedil;&atilde;o</th>   
                        </tr>                           
                    </thead>       
                    <tbody>                                       
                    </tbody> 
                </table>     
            </div>    
        </div>    
    </div>
</form>
<hr />