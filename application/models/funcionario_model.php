<?php

class Funcionario_model extends CI_Model {

    public $_table = 'funcionario';

    public function listagem() {
        $row = $this->db->get($this->_table)->result_array();
        return $row;
    }

    public function lista_funcionarios($campos = '*', $where = null) {
        $arr_retorno = array('' => 'Selecione...');

        $sql = "SELECT idFuncionario, strNome
                FROM {$this->_table}
                {$where}";

        $rows = $this->db->query($sql);
        foreach ($rows->result_array() as $row) {
            $arr_retorno[$row['idFuncionario']] = $row['strNome'];
        }
        return $arr_retorno;
    }

    public function valida_uniques($dados) {
        $sql = "SELECT COUNT(idFuncionario) as total FROM " . $this->_table . " WHERE idFuncionario <> ? AND CPF = ?";
        $query = $this->db->query($sql, $dados)->row_array();
        return $query['total'];
    }
    
    public function valida_uniques_matr($dados) {
        $sql = "SELECT COUNT(idFuncionario) as total FROM " . $this->_table . " WHERE idFuncionario <> ? AND Matricula = ?";
        $query = $this->db->query($sql, $dados)->row_array();
        return $query['total'];
    }    

    public function lista_funcionarios_join($campos = '', $join = null, $where = null) {

        $sql = "SELECT DISTINCT(A.idFuncionario) AS idFuncionario, A.strNome, A.Matricula, C.strCargo, A.Salario
                FROM {$this->_table} A
				LEFT JOIN {$this->_table} B ON
				      A.idFuncionario = B.id_Gerente
			    INNER JOIN cargo C on
				      A.id_Cargo = C.idCargo					  
                {$where}";

        $rows = $this->db->query($sql)->result_array();

        return $rows;
    }

}
