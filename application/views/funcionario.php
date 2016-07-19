
<form name="form_cargo" id="form_funcionario" modelo="funcionario_modelo" >   
    <div class="row">  

        <input type="hidden" class="form-control"  name="idFuncionario" id="idFuncionario"/>   

        <div class="col-xs-2 col-sm-6">                

            <label for="strNome">Nome</label>    

            <input type="text" class="form-control"  name="strNome" id="strNome" required="" maxlength="50"/>   

        </div>                     

        <div class="col-xs-4 col-sm-2">    

            <label for="Matricula">Matr&iacute;cula</label>   

            <input type="text" class="form-control" style="text-align: center" name="Matricula" required id="Matricula" maxlength="4"/> 

        </div>         

        <div class="col-xs-3 col-sm-2">    

            <label for="CPF">CPF</label>      

            <input type="text" class="form-control cpf" name="CPF" id="CPF" required="" maxlength="15"/> 

        </div>               

        <div class="col-xs-3 col-sm-2">              

            <label for="dtNascimento">Nascimento</label>     

            <input type="text" class="form-control date" required="" style="text-align: center" placeholder="Data"  name="dtNascimento" id="dtNascimento" maxlength="10"/>   

        </div>  
	</div>
	<div class="row">
        <div class="col-xs-3 col-sm-2">              

            <label for="Salario">S&aacute;lario</label>     

            <input type="text" class="form-control money2" style="text-align: center"  name="Salario" id="Salario" required maxlength="10"/>   

        </div>    
        <div class="col-xs-3 col-sm-2">              
            <label for="btAtivo">Status</label> 
            <select name="btAtivo" id="btAtivo" class="form-control" required="">				
				<option selected value="1">Ativo</option>
				<option value="0">Inativo</option>
			</select>
			

        </div> 
        <div class="col-xs-3 col-sm-2">              

            <label for="id_Cargo">Cargo</label>     

            <?php echo $cargos; ?>

        </div>    
        <div class="col-xs-3 col-sm-2">              

            <label for="id_Gerente">Gerente</label>     

            <?php echo $funcionario; ?>

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

                            <th style="width: 50%">Nome</th> 

                            <th style="width: 10%">Matr&iacute;cula</th> 

                            <th style="width: 20%">Cargo</th> 

                            <th style="width: 10%;">S&aacute;lario</th> 							
							
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


