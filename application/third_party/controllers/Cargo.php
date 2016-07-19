<?php

defined('BASEPATH') OR exit('No direct script access allowed');
      
class Cargo extends MY_Controller {

    public function __construct() {

        //$this->setTabela("usuario_model");
        parent::__construct();
    }

    public function index($erro = '') {
        $dados ['middle'] = 'cargo';
        $dados ['pagina_atual'] = 'Cargo';
        $dados ['data_url'] = 'cargo/get_tables';
        $dados ['datatables'] = true; //$this->get_tables();
        $this->load->view('header_footer/body', $dados);
    }

    function pre_cadastro($data) {

        $data['MinSal'] = str_replace(',', '.', str_replace('.', '', $data['MinSal']));
        $data['MaxSal'] = str_replace(',', '.', str_replace('.', '', $data['MaxSal']));

        return $data;
    }

	function formater(){
		
	}
	
    public function get_tables() {

// DB table to use
        $table = 'usuario';

// Table's primary key
        $primaryKey = 'idUsuario';

        $columns = array(
            array('db' => 'strLogin', 'dt' => 0),
            array('db' => 'strSenha', 'dt' => 1),
            array('db' => 'btAtivo', 'dt' => 2),
            array('db' => 'strNome', 'dt' => 3),
			//array('db' => 'idUsuario', 'dt' => 4)
           /* array(
                'db' => 'start_date',
                'dt' => 4,
                'formatter' => function( $d, $row ) {
                    return date('jS M y', strtotime($d));
                }
            ),*/
            array(
                'db' => 'idUsuario',
                'dt' => 4,
                'formatter' => function( $d, $row ) {
				return	$this->monta_botao($d);
                
                }
            )
        );

// SQL server connection information
        $sql_details = array(
            'user' => 'root',
            'pass' => '',
            'db' => 'tcc',
            'host' => 'localhost'
        );


        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */
		 
		 $sql = " SELECT SQL_CALC_FOUND_ROWS a.strLogin, a.strSenha, a.btAtivo, b.strNome, idUsuario
		          FROM usuario a
				  LEFT JOIN funcionario b on
				     a.id_Func = b.idFuncionario";
		 
$this->load->library('ssp');
        echo json_encode(
                SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $sql)
        );
    }

    public function valida_formulario() {
        $config = array(
            array(
                'field' => 'strCargo',
                'label' => 'Cargo',
                'rules' => 'required|max_length[30]|alpha_numeric'
            ),
            array(
                'field' => 'strDescricao',
                'label' => 'Descricao',
                'rules' => 'max_length[60]'
            ),
            array(
                'field' => 'MinSal',
                'label' => 'Min Sal.',
                'rules' => 'required|max_length[10]'
            ),
            array(
                'field' => 'MaxSal',
                'label' => 'Max Sal.',
                'rules' => 'required|max_length[10]'
            )
        );

        $this->form_validation->set_rules($config);
        return $this->form_validation->run();
    }

}
