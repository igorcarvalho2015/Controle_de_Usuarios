<?php

/**
 * CRUD CARGO
 * @author VAGNER/DOUGLAS/IGOR/RAFAEL (EQUIPE TCC)
 * @copyright TCC RESTRICAO DE INTEGRIDADE
 * @license
 * @package application
 * @subpackage controllers
 * @filesource cargo.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model("Cargo_model");
    }

    public function index($erro = '') {
        $dados ['middle'] = 'cargo';
        $dados ['pagina_atual'] = 'Cargo';
        $dados ['datatables'] = true; //$this->get_tables();
        $this->load->view('header_footer/body', $dados);
    }

    public function trata_campos($data, $acao = 'cadastrar') {
        $data['MinSal'] = str_replace(',', '.', str_replace('.', '', $data['MinSal']));
        $data['MaxSal'] = str_replace(',', '.', str_replace('.', '', $data['MaxSal']));
        return $data;
    }

    function valida_formulario($acao = 'cadastrar') {

        if ($acao == 'cadastrar') {
            $this->form_validation->set_rules('idCargo', 'id', 'max_length[11]|is_unique[cargo.idCargo]', array(
                'is_unique' => 'Este %s ja existe.')
            );
        }
        
        $this->form_validation->set_rules('strCargo', 'Cargo', 'required|max_length[30]');
        $this->form_validation->set_rules('strDescricao', 'Descricao', 'max_length[60]');
        $this->form_validation->set_rules('MinSal', 'Min Sal', 'required|max_length[10]|callback_compara_salario');
        $this->form_validation->set_rules('MaxSal', 'Max Sal', 'required|max_length[10]');

        return $this->form_validation->run();
    }

    function compara_salario($str) {
        
        $min_sal = str_replace(',','.',str_replace('.','',$this->input->post('MinSal')));
        $max_sal = str_replace(',','.',str_replace('.','',$this->input->post('MaxSal')));

        
        if ( $min_sal > $max_sal ) {
            $this->form_validation->set_message('compara_salario', 'Salario Min Maior que Max');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function datatable() {

        $rows = $this->Cargo_model->listagem();

        $dados = '{
                   "data": [';
        $v = '';
        foreach ($rows as $row) {

            $dados .= $v . '[
                        "' . $row['strCargo'] . '",
                        "' . $row['strDescricao'] . '",
                        "' . number_format($row['MinSal'], 2, ',', '.') . '",
                        "' . number_format($row['MaxSal'], 2, ',', '.') . '",
                        "' . $this->monta_botao($row['idCargo']) . '"
                       ]';
            $v = ",";
        }
        $dados .= ' ]
                }';

        echo $dados;
    }

    public function post_edicao($dados) {
        $dados['MaxSal'] = number_format($dados['MaxSal'], 2, ',', '.');
        $dados['MinSal'] = number_format($dados['MinSal'], 2, ',', '.');
        return $dados;
    }

}
