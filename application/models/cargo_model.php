<?php

class Cargo_model extends CI_Model {

    public $_table = 'cargo';

    public function listagem($campos = ''){
        $row = $this->db->get($this->_table )->result_array();
        return $row;
    }  
    
    public function lista_cargos($campos = '', $where = null){
        $arr_retorno = array(''=>'Selecione...');
        $sql = "SELECT idCargo, strCargo, strDescricao
                FROM {$this->_table}
                {$where}";
                
        $rows = $this->db->query($sql);
        foreach($rows->result_array() as $row){
            $arr_retorno[$row['idCargo']] = $row['strCargo'];
        }
        return $arr_retorno;
    }
}
