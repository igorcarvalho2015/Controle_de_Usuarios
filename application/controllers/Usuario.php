<?php

/**
 * CRUD USUARIO
 * @author VAGNER/DOUGLAS/IGOR/RAFAEL (EQUIPE TCC)
 * @copyright TCC RESTRICAO DE INTEGRIDADE
 * @license
 * @package application
 * @subpackage controllers
 * @filesource usuario.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model("Usuario_model");
    }

    public function index($erro = '') {
        $this->load->model("Funcionario_model");
        $dados ['middle'] = 'usuario';
        $dados ['pagina_atual'] = 'Usu&aacute;rio';
        $dados ['datatables'] = true;
        $dados ['js_pagina'] = 'usuario';
        $dados ['funcionario'] = form_dropdown('id_Func', $this->Funcionario_model->lista_funcionarios(), '', 'id="id_Func" class="form-control" required');
        $this->load->view('header_footer/body', $dados);
    }

    function valida_formulario($acao = 'cadastrar') {

        if ($acao == 'cadastrar') {
            $this->form_validation->set_rules('idUsuario', 'id', 'max_length[11]|is_unique[usuario.idUsuario]', array(
                'is_unique' => 'Este %s ja existe.')
            );
            $this->form_validation->set_rules('strLogin', 'id', 'max_length[11]|is_unique[usuario.strLogin]|alpha_dash', array(
                'is_unique' => 'Este %s ja existe.')
            );
            $this->form_validation->set_rules('strSenha', 'Senha', 'required|max_length[100]|alpha_dash');
        } else {
            $this->form_validation->set_rules('strLogin', 'Login', 'required|alpha_dash|max_length[100]|callback_valida_login_usuario');
            $this->form_validation->set_rules('btAtivo', 'Status', 'required|max_length[11]');

            $this->form_validation->set_rules('strSenha', 'Senha', 'max_length[100]|alpha_dash');
        }

        $this->form_validation->set_rules('id_Func', 'Funcionario', 'required|max_length[11]');

        return $this->form_validation->run();
    }

    public function valida_login_usuario($str) {

        $dados = $this->input->post();

        $row = $this->Usuario_model->valida_uniques(array($dados['idUsuario'], $dados['strLogin']));
        if ($row > 0) {
            $this->form_validation->set_message('valida_login_usuario', 'Este login ja esta cadastrado');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function datatable() {

        $rows = $this->Usuario_model->listagem();

        $dados = '{
                   "data": [';
        $v = '';
        foreach ($rows as $row) {

            $dados .= $v . '[
                        "' . $row['strNome'] . '",
                        "' . $row['strLogin'] . '",
                        "' . $row['btAtivo'] . '",
                        "' . $this->monta_botao($row['idUsuario']) . '"
                       ]';
            $v = ",";
        }
        $dados .= ' ]
                }';

        echo $dados;
    }

    function cadastrar() {

        $data = $this->input->post();

        $validacao = $this->valida_formulario('cadastrar');

        $modelo = str_replace('_modelo', '', $data['modelo']);
        if ($validacao === true) {

            $data = $this->pre_cadastro($data);

            $sql = $this->estrutura_tabela($modelo);

            foreach ($sql as $row) {
                if ($row['Extra'] == 'auto_increment')
                    continue;

                if (isset($data[$row['Field']]) && !empty($data[$row['Field']])) {
                    $array_insert[$row['Field']] = $data[$row['Field']];
                }
            }
            $array_insert = $this->trata_campos($array_insert, 'cadastrar');

            /*             * ************************************************************* */
            $err = $this->Usuario_model->insere_modelo($array_insert);

            if ($err['code'] === 0) {
                $arrayRetorno['RETORNO'] = 1;
            } else {
                $arrayRetorno['RETORNO'] = 2;
            }
        } else {
            $error = $this->form_validation->error_array();
            $arrayRetorno['RETORNO'] = 0;
            $arrayRetorno['MSG'] = $error;
        }
        echo json_encode($arrayRetorno);
    }

    public function trata_campos($dados, $acao = 'cadastrar') {
        return $dados;
    }

    public function editar() {
        $data = $this->input->post();
        $chave = base64_decode($data['chave']);
        $modelo = str_replace('_modelo', '', $data['modelo']);
        $sql = $this->estrutura_tabela($modelo);

        $campo_chave = '';
        foreach ($sql as $row) {
            if ($row['Key'] == 'PRI') {
                $campo_chave = $row['Field'];
                break;
            }
        }
        $rows = $this->Usuario_model->edita_modelo($chave, $campo_chave);
        $err = $this->db->error();

        if ($err['code'] === 0) {
            $row = $rows->row_array();
            $row = $this->post_edicao($row);
            $arrayRetorno['RETORNO'] = 1;
            $arrayRetorno['DADOS'] = $row;
        } else {
            $arrayRetorno['RETORNO'] = 2;
        }
        echo json_encode($arrayRetorno);
    }

    public function post_edicao($dados) {
        unset($dados['strSenha']);
        return $dados;
    }

    public function alterar() {
        $data = $this->input->post();

        $validacao = $this->valida_formulario('alterar');

        $modelo = str_replace('_modelo', '', $data['modelo']);

        if ($validacao === true) {

            $data = $this->pre_cadastro($data);

            $sql = $this->estrutura_tabela($modelo);

            $id_field = '';

            foreach ($sql as $row) {
                if ($row['Extra'] == 'auto_increment') {
                    $id_field = $row['Field'];

                    continue;
                }
                if (isset($data[$row['Field']])) {
                    if ($data[$row['Field']] == '') {
                        $array_update[$row['Field']] = null;
                    } else {
                        $array_update[$row['Field']] = $data[$row['Field']];
                    }
                }
            }
            $array_update = $this->trata_campos($array_update, 'alterar');

            $err = $this->Usuario_model->altera_modelo($data[$id_field], $array_update);

            if ($err['code'] === 0) {
                $arrayRetorno['RETORNO'] = 1;
                $acao = "Sucesso: " . substr($this->db->last_query(), 0, 350);
            } else {
                $arrayRetorno['RETORNO'] = 2;
                $acao = "Erro: " . substr($err['message'], 0, 350);
            }
            $this->salva_logs($acao);
        } else {
            $error = $this->form_validation->error_array();
            $arrayRetorno['RETORNO'] = 0;
            $arrayRetorno['MSG'] = $error;
        }
        echo json_encode($arrayRetorno);
    }

}
