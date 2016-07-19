<?php

/**
 * CRUD FUNCIONARIO
 * @author VAGNER/DOUGLAS/IGOR/RAFAEL (EQUIPE TCC)
 * @copyright TCC RESTRICAO DE INTEGRIDADE
 * @license
 * @package application
 * @subpackage controllers
 * @filesource funcionario.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionario extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model("Funcionario_model");
    }

    public function index($erro = '') {
        $this->load->model("Cargo_model");
        $dados ['middle'] = 'funcionario';
        $dados ['pagina_atual'] = 'Funcion&aacute;rio';
        $dados ['datatables'] = true;
        $dados['js_pagina'] = 'funcionario';
        $dados ['cargos'] = form_dropdown('id_Cargo', $this->Cargo_model->lista_cargos(), '', 'id="id_Cargo" required="" class="form-control"');
        $dados ['funcionario'] = form_dropdown('id_Gerente', $this->Funcionario_model->lista_funcionarios(), '', 'id="id_Gerente" class="form-control"');
        $this->load->view('header_footer/body', $dados);
    }

    public function trata_campos($data, $acao = 'cadastrar') {
        $data['Salario'] = str_replace(',', '.', str_replace('.', '', $data['Salario']));
        $data['dtNascimento'] = $this->data_pt_para_ing($data['dtNascimento']);
        $data['CPF'] = str_replace(array('-', '.'), '', $data['CPF']);

        return $data;
    }

    function valida_formulario($acao = 'cadastrar') {

        if ($acao == 'cadastrar') {
            $this->form_validation->set_rules('idFuncionario', 'id', 'max_length[11]|is_unique[funcionario.idFuncionario]', array(
                'is_unique' => 'Este %s ja existe.')
            );
            $this->form_validation->set_rules('CPF', 'CPF', 'required|max_length[14]|callback_valida_cpf_funcionario');
            
            $this->form_validation->set_rules('Matricula', 'Matricula', 'max_length[4]|is_unique[funcionario.Matricula]', array(
                'is_unique' => 'Este %s ja existe.')
            );
        } else {
            $this->form_validation->set_rules('CPF', 'CPF', 'required|max_length[14]|callback_valida_cpf_funcionario');
            $this->form_validation->set_rules('Matricula', 'Matricula', 'required|max_length[4]|numeric|callback_valida_matr');
        }

        $this->form_validation->set_rules('strNome', 'Nome', 'required|max_length[50]');
        $this->form_validation->set_rules('dtNascimento', 'Nascimento', 'required|max_length[10]');
        $this->form_validation->set_rules('Salario', 'Salario', 'required|max_length[10]');
        $this->form_validation->set_rules('btAtivo', 'Status', 'required|max_length[1]');

        return $this->form_validation->run();
    }

    public function valida_cpf_funcionario($str) {  
        
        $ID_func = $this->input->post('idFuncionario');
        $CPF = str_replace(array('-', '.'), '', $this->input->post('CPF'));

        $row = $this->Funcionario_model->valida_uniques(array($ID_func, $CPF));
        if ($row > 0) {
            $this->form_validation->set_message('valida_cpf_funcionario', 'Este CPF ja esta cadastrado');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function valida_matr() {
        $ID_func = $this->input->post('idFuncionario');
        $matr = $this->input->post('Matricula');

        $row = $this->Funcionario_model->valida_uniques_matr(array($ID_func, $matr));
        if ($row > 0) {
            $this->form_validation->set_message('valida_matr', 'Esta Matricula ja esta cadastrada');
            return FALSE;
        } else {
            return TRUE;
        }
    }
        function datatable() {

            $rows = $this->Funcionario_model->lista_funcionarios_join();

            $dados = '{
                   "data": [';
            $v = '';
            foreach ($rows as $row) {

                $dados .= $v . '[
                        "' . $row['strNome'] . '",
                        "' . $row['Matricula'] . '",
                        "' . $row['strCargo'] . '",
                        "' . number_format($row['Salario'], 2, ',', '.') . '",
                        "' . $this->monta_botao($row['idFuncionario']) . '"
                       ]';
                $v = ",";
            }
            $dados .= ' ]
                }';

            echo $dados;
        }

        public

        function post_edicao($dados) {
            $dados['Salario'] = number_format($dados['Salario'], 2, ',', '.');
            $dados['dtNascimento'] = date('d/m/Y', strtotime($dados['dtNascimento']));
            $dados['CPF'] = $this->mask_cpf($dados['CPF'], '###.###.###-##');

            return $dados;
        }

    }
    