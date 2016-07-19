<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Logs_model");
    }

    public function index($erro = '') {

        $dados ['middle'] = 'Logs';
        $dados ['pagina_atual'] = 'Logs';
        $dados ['datatables'] = true;
        $this->load->view('header_footer/body', $dados);
    }

    function datatable() {

        $rows = $this->Logs_model->listagem();

        $dados = '{
                   "data": [';
        $v = "";
        
        foreach ($rows as $row) {             
            $dados .= $v . '[
                        "' . $row['strNome'] . '",
                        "' . date('d/m/Y h:i:s', strtotime($row['dtacao'])) . '",
                        "' .  $this->remove_quebra($row['acao']) . '"

                       ]';
            $v = ",";
        }
        $dados .= ' ]
                }';

        echo $dados;
    }

}