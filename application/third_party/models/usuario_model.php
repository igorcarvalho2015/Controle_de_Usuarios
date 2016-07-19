<?php

class Usuario_model extends CI_Model {

	public $_table = 'usuario';
    /*public function __construct() {
        //$this->load->model('usuario_model');
        parent::__construct();
    }*/

    //protected $_name = 'usuario';

    public function valida_usuario($dados) {

	$sql = "SELECT * FROM {$this->_table} WHERE strLogin = ? AND strSenha= ?";
        $query = $this->db->query($sql, array($dados['LOGIN'], $dados['SENHA']));
        $err = $this->db->error();

        if ($err['code'] === 0) {
            $retorno['RETORNO'] = TRUE;
            $retorno['MSG'] = 'SUCESSO!';
            $retorno['DADOS'] = $query;
        } else {
            $retorno['RETORNO'] = FALSE;
            $retorno['MSG'] = 'Erro 500!';
            //$retorno['MSG'] = $err['message'];
        }

        return $retorno;
    }

}
