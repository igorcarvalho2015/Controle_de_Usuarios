<?php

class Usuario_model extends CI_Model {

    public $_table = 'usuario';

    public function listagem($campos = '', $where = null) {
        $sql = "SELECT A.idUsuario, B.strNome, AES_DECRYPT(A.strLogin,'UNICARIOCA') as strLogin, 
                    CASE A.btAtivo WHEN 0 THEN 'INATIVO' ELSE 'ATIVO' END AS btAtivo
                FROM {$this->_table} A
				LEFT JOIN funcionario B on
                    A.id_Func = B.idFuncionario";
        $rows = $this->db->query($sql);
        return $rows->result_array();
    }

    public function valida_usuario($dados) {

        $sql = "SELECT A.strLogin, A.btAtivo, A.idUsuario, C.idCargo, B.strNome 
                FROM {$this->_table} A 
                INNER JOIN funcionario B ON
                       A.id_Func = B.idFuncionario                    
                INNER JOIN cargo C ON 
                       B.id_Cargo = C.idCargo
                WHERE A.strLogin = AES_ENCRYPT(?,'UNICARIOCA') AND 
                      A.strSenha= MD5(?)";
        $query = $this->db->query($sql, array($dados['LOGIN'], $dados['SENHA']));
		
		//QUERY SEM TRATAMENTO
       /* $sql = "SELECT A.strLogin, A.btAtivo, A.idUsuario, C.idCargo, B.strNome 
                FROM {$this->_table} A 
                INNER JOIN funcionario B ON
                       A.id_Func = B.idFuncionario                    
                INNER JOIN cargo C ON 
                       B.id_Cargo = C.idCargo
                WHERE A.strLogin = AES_ENCRYPT('{$dados['LOGIN']}','UNICARIOCA') AND 
                      A.strSenha= MD5('{$dados['SENHA']}')";
		
		if( ! $this->db->simple_query($sql))*/
		
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

    public function valida_uniques($dados) {
        $sql = "SELECT COUNT(idUsuario) as total FROM " . $this->_table . " WHERE idUsuario <> ? AND strLogin = AES_ENCRYPT(?,'UNICARIOCA')";
        $query = $this->db->query($sql, $dados)->row_array();
        return $query['total'];
    }

    public function insere_modelo($dados) {
        $dados['strLogin'] = $dados['strLogin'];
        $dados['strSenha'] = $dados['strSenha'];
        $dados['btAtivo'] = $dados['id_Func'];

        $this->db->query("call User_in('{$dados['strLogin']}','{$dados['strSenha']}','{$dados['id_Func']}')");

        $err = $this->db->error();
        return $err;
    }

    public function edita_modelo($chave, $campo_chave) {
        $sql = " SELECT idUsuario, AES_DECRYPT(strLogin, 'UNICARIOCA') strLogin, strSenha, btAtivo, id_Func 
                 FROM {$this->_table} WHERE {$campo_chave} = {$chave}";
        $rows = $this->db->query($sql);
        return $rows;
    }

    public function altera_modelo($chave, $campos) {

        $up_senha = null;
        if (trim($campos['strSenha']) != '') {
            $up_senha = ", strSenha =  MD5('{$campos['strSenha']}')";
        }// print_r($campos); exit;
        $sql = $this->db->query("UPDATE {$this->_table} SET strLogin = AES_ENCRYPT('{$campos['strLogin']}','UNICARIOCA')
                                                            {$up_senha},
                                                            btAtivo = '{$campos['btAtivo']}',
                                                            id_Func = '{$campos['id_Func']}'
                                                            WHERE idUsuario = '{$chave}'");
        $err = $this->db->error();
        return $err;
    }

}
