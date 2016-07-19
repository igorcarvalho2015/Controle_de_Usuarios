<?php

class RelAcoeUser_model extends CI_Model {

    private $_table = 'RelAcoeUser';

    public function listagem($campos = ''){
        //$rows = $this->db->get( $this->_table )->result_array();
		
		$sql = "SELECT AES_DECRYPT(strLogin, 'UNICARIOCA') as strLogin, acao, dtacao
		        FROM relacoeuser";
		$rows = $this->db->query($sql)->result_array();
        return $rows;
    }      
}