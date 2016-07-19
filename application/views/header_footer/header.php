
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="<?php echo base_url(); ?>assets/img/unicarioca.ico">

        <title>TCC</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/dashboard.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/my_css.css" rel="stylesheet">
        <?php
        if (isset($datatables)) {
            echo '<link href="' . base_url() . 'assets/css/dataTables.bootstrap.min.css" rel="stylesheet">';
        }
        ?>        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <script type="text/javascript">
        var base_url = "<?php echo base_url(); ?>";
    </script>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="">TCC</a>                    
                </div>                

                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="javascript:;"><?php echo $this->session->userdata("strNome") ?></a></li>
                        <li><a href="<?php echo base_url(); ?>login/logout  ">Logout</a></li>
                    </ul>    
                </div>

            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        
                        <?php 
                        
                        if($this->session->userdata("idCargo") != 1){ ?>
                        
                        <li><a href="<?php echo base_url(); ?>funcionario">Funcion&aacute;rio</a></li> 
                        <li><a href="<?php echo base_url(); ?>relatorio_func">Relat&oacute;rio funcion&aacute;rios</a></li>
                        <?php } else { ?>                                                
                                                                       
                        <li><a href="<?php echo base_url(); ?>funcionario">Funcion&aacute;rio</a></li>                        
                        <li><a href="<?php echo base_url(); ?>cargo">Cargo</a></li>
                        <li><a href="<?php echo base_url(); ?>usuario">Usu&aacute;rio</a></li>
                        <li><a href="<?php echo base_url(); ?>logs">Logs</a></li>                                                  
                        <li><a href="<?php echo base_url(); ?>relatorio_func">Relat&oacute;rio funcion&aacute;rios</a></li>                        
                        <li><a href="<?php echo base_url(); ?>relatorio_log">Relat&oacute;rio de Logs</a></li>
                        
                        <?php } ?>
                        
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header"><?php echo $pagina_atual; ?></h1>