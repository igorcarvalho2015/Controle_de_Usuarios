<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $logado = $this->session->userdata("logado");

        if ($logado != 1) {
            $entra = false;
        } else {
            $ret_perm = $this->permissao_acesso();
            $cargo = $this->session->userdata("idCargo");

            if ($cargo != 1) {
                if ($ret_perm === true) {
                    $entra = true;
                } else {
                    $entra = false;
                }
            }
            else {
                $entra = true;
            }
        }

        if ($entra === false) {
            redirect(base_url());
            exit;
        }
    }

    function estrutura_tabela($modelo) {
        $sql = $this->db->query("SHOW COLUMNS FROM {$modelo}")->result_array();
        return $sql;
    }

    public function permissao_acesso() {

        $projeto = $this->uri->segment(1);

        $arr_perm = array(
            "funcionario",
            "relatorio_func"
        );

        if (in_array($projeto, $arr_perm)) {
            return true;
        } else {
            return false;
        }
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

                if (isset($data[$row['Field']]) && $data[$row['Field']] != '') {
                    $array_insert[$row['Field']] = $data[$row['Field']];
                }
            }
            $array_insert = $this->trata_campos($array_insert, 'cadastrar');
            /*             * ************************************************************* */

            $this->db->insert($modelo, $array_insert);
            $err = $this->db->error();

            if ($err['code'] === 0) {
                $arrayRetorno['RETORNO'] = 1;

                $acao = htmlentities("Sucesso: " . substr($this->db->last_query(), 0, 350));

                //$this->post_insert($array_insert);
            } else {
                $acao = htmlentities("Erro: " . substr($err['message'], 0, 350));
                $arrayRetorno['RETORNO'] = 2;
            }
            $this->salva_logs($acao);
        } else {
            $error = $this->form_validation->error_array();
            $arrayRetorno['RETORNO'] = 0;
            $arrayRetorno['MSG'] = $error;
        }
        echo json_encode($arrayRetorno);
    }

    protected function salva_logs($acao) {
        $arr_log['acao'] = $acao;
        $arr_log['id_Usuario'] = $this->session->userdata("id_Usuario");
        $this->db->insert('logs', $arr_log);
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

        $sql = " SELECT * FROM {$modelo} WHERE {$campo_chave} = {$chave}";
        $rows = $this->db->query($sql);
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
            $this->db->where($id_field, $data[$id_field]);
            $this->db->update($modelo, $array_update);

            $err = $this->db->error();

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

    public function excluir() {
        $data = $this->input->post();

        $chave = base64_decode($data['id']);

        $modelo = str_replace('_modelo', '', $data['modelo']);

        $id_field = '';

        $sql = $this->estrutura_tabela($modelo);
        foreach ($sql as $row) {
            if ($row['Extra'] == 'auto_increment') {
                $id_field = $row['Field'];
                continue;
            }
        }

        $this->db->where($id_field, $chave);
        $this->db->delete($modelo);

        $err = $this->db->error();
        if ($err['code'] === 0) {
            $arrayRetorno['RETORNO'] = 1;
            $acao = "Sucesso: " . substr($this->db->last_query(), 0, 400);
        } else {
            $arrayRetorno['RETORNO'] = 2;
            $acao = "Erro: " . substr($err['message'], 0, 400);
        }
        $this->salva_logs($acao);
        echo json_encode($arrayRetorno);
    }

    function pre_cadastro($data) {
        return $data;
    }

    public function valida_formulario($acao = 'cadastrar') { // cadastrar ou alterar
        return true;
    }

    public function trata_campos($dados, $acao = 'cadastrar') { //ao cadastrar ou alterar
        return $dados;
    }

    public function post_edicao($dados) {
        return $dados;
    }

    public function monta_botao($chave) {
        return '<a data-id=\'' . base64_encode($chave) . '\'  href=\'javascript:;\';\' class=\'btn btn-success btn-xs editar\'><span class=\'glyphicon glyphicon-pencil\'/></a>&nbsp;<a href=\'javascript:;\' data-id=\'' . base64_encode($chave) . '\'  class=\'btn btn-xs btn-danger excluir\'><span class=\'glyphicon glyphicon-trash\'/></a>';
    }

    function mask_cpf($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    function data_pt_para_ing($data) {
        $data_ing = DateTime::createFromFormat('d/m/Y', $data);
        return $data_ing->format('Y-m-d');
    }

    function data_ing_para_pt($data) {
        return date('d/m/Y h:i:s', strtotime($data));
    }

    public function remove_quebra($str) {
        return preg_replace('/\s/', ' ', $str);
    }

}
