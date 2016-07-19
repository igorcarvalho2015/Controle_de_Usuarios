<?php

class RelEmpregAtivo_model extends CI_Model {

    private $_table = 'RelEmpregAtivo';

    public function listagem($campos = ''){
        $rows = $this->db->get($this->_table )->result_array();
        return $rows;
    }      
}