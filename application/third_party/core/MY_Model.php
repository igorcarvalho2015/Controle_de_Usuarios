<?php

class MY_Model extends CI_Model {

	protected $_table;
	protected $primary_key = 'id';

    /*function __construct() {
        parent::__construct();
    }*/

    function cadastrar($data) {

        $sql = $this->db->query("SHOW COLUMNS FROM {$data['modelo']}")->result_array();
		
        foreach ($sql as $row) {
            $valor = isset($data[$row['Field']]) ? $data[$row['Field']] : null;
            $array_insert[$row['Field']] = $valor;
        }
        /*         * ************************************************************* */
        $this->db->insert($data['modelo'], $array_insert);

        if ($this->db->affected_rows() > 0) {
            $arrayRetorno['MSG'] = 'Operacao realizada com sucesso!';
            $arrayRetorno['RETORNO'] = TRUE;
            $this->post_insert($data);
        } else {
            $arrayRetorno['MSG'] = 'Erro na Base ao exeutar a operacao!';
        }
    }

}
