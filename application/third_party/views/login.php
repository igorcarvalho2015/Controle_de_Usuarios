<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->

    <!-- Mirrored from altair.tzdthemes.com/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Sep 2015 02:25:26 GMT -->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>TCC</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            body {
                background: #eee !important;
            }

            .wrapper {
                margin-top: 80px;
                margin-bottom: 80px;
            }

            .form-signin {
                max-width: 380px;
                padding: 15px 35px 45px;
                margin: 0 auto;
                background-color: #fff;
                border: 1px solid rgba(0, 0, 0, 0.1);
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
                margin-bottom: 30px;
            }
            .form-signin .checkbox {
                font-weight: normal;
            }
            .form-signin .form-control {
                position: relative;
                font-size: 16px;
                height: auto;
                padding: 10px;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .form-signin .form-control:focus {
                z-index: 2;
            }
            .form-signin input[type="text"] {
                margin-bottom: -1px;
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }
            .form-signin input[type="password"] {
                margin-bottom: 20px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }   

            .user_align img {
                margin-left: auto;
                margin-right: auto;
            }


        </style>
    </head>
    <body>
        <div class="wrapper">
            <form class="form-signin" action="<?php echo base_url(); ?>login/valida_login" method="post">       
                <div class="user_align">
                    <img src="<?php echo base_url(); ?>assets/img/create-user.jpg" width="80" height="80" class="img-responsive"/>
                </div>
                <input type="text" class="form-control" name="LOGIN" id="LOGIN" placeholder="Usu&aacute;rio" required="" autofocus="" />
                <input type="password" class="form-control" name="SENHA" id="SENHA" placeholder="Senha" required=""/>    
                <?php
                if ($RETORNO === false)
                    echo '<span style="color: #FF0000">' . $MSG . '</span>';
                ?>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>				
            </form>
        </div>
    </body>
</html>