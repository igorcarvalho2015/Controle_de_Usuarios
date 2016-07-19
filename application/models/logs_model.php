<?php

class Logs_model extends CI_Model {

    public $_table = 'logs';

    public function listagem($campos = ''){
        
        $sql = "SELECT A.acao,A.dtacao , COALESCE(C.strNome, '-') AS strNome FROM {$this->_table} A
                INNER JOIN usuario B ON
                   B.idUsuario = A.id_Usuario
                INNER JOIN funcionario C ON
                   C.idFuncionario = B.id_Func";
        $row = $this->db->query($sql)->result_array();        

        return $row;
    }  
}
