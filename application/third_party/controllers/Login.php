<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($erro = '') {
        $retorno['RETORNO'] = TRUE;
        $retorno['MSG'] = '';
        $this->load->view('login', $retorno);
    }

    public function logar() {
        $valida = $this->valida_login();
    }

    public function valida_login() {

        $retorno['RETORNO'] = FALSE;

        $dados = $this->valida_formulario();

        if ($dados) {
            $this->load->model("usuario_model");
            $ret_valida_user = $this->usuario_model->valida_usuario($this->input->post());

            if ($ret_valida_user['RETORNO']) {
                if ($ret_valida_user['DADOS']->num_rows() > 0) {
                    $rows = $ret_valida_user['DADOS']->row_array();
                    if ($rows ["btAtivo"] == 1) {
                        $retorno['RETORNO'] = TRUE;
                    } else {
                        $retorno['MSG'] = 'Usu&aacute;rio bloqueado!';
                    }
                } else {
                    $retorno['MSG'] = 'Usu&aacute;rio ou Senha Incorreto!';
                }
            } else {
                $retorno['MSG'] = 'Erro no banco ' . $ret_valida_user['MSG'];
            }
        } else {
            $retorno['RETORNO'] = FALSE;
            $retorno['MSG'] = $this->form_validation->error_string();
        }

        if ($retorno['RETORNO']) { //validou		
            $this->session->set_userdata("logado", 1);
            $this->session->set_userdata("usuario", $rows['strLogin']);
            redirect(base_url() . 'home', 'refresh');
        } else {
            $this->load->view('login', $retorno);
        }
    }

    public function valida_formulario($retorno = TRUE) {
        $this->form_validation->set_rules('LOGIN', 'LOGIN', 'required');
        $this->form_validation->set_rules('SENHA', 'SENHA', 'required');

        if ($this->form_validation->run() == FALSE) { // Erro
            $retorno = FALSE;
        }

        return $retorno;
    }

    public function logout() {
        $this->session->unset_userdata("logado");
        $this->session->unset_userdata("usuario");
        redirect(base_url());
    }
}
