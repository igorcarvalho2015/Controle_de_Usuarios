<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $logado = $this->session->userdata("logado");

        if ($logado != 1)
            redirect(base_url());
    }

    function cadastrar() {
        
        $data = $this->input->post();
        
        $validacao = $this->valida_formulario();

        $modelo = str_replace('_modelo', '', $data['modelo']);
        if ($validacao === true) {
            
            $data = $this->pre_cadastro($data);
            
            $sql = $this->db->query("SHOW COLUMNS FROM {$modelo}")->result_array();

            foreach ($sql as $row) {
                if ($row['Extra'] == 'auto_increment')
                    continue;
                $valor = isset($data[$row['Field']]) ? $data[$row['Field']] : null;
                $array_insert[$row['Field']] = $valor;
            }

            /*             * ************************************************************* */
            $this->db->insert($modelo, $array_insert);
            $err = $this->db->error();
            
            if ($err['code'] === 0) {
                $arrayRetorno['RETORNO'] = 1;
                //$this->post_insert($array_insert);
            } else {
                $arrayRetorno['RETORNO'] = 2;
            }
        } else {
            $error = $this->form_validation->error_array();
            $arrayRetorno['RETORNO'] = 0;
            $arrayRetorno['MSG']  = $error;
        }
        echo json_encode($arrayRetorno);
    }
    function pre_cadastro($data){
        return $data;
    }
    
    public function valida_formulario() {
        return true;
    }
    
    function monta_table($dados) {
        $sTable = $dados['tabela'];
        $aColumns = $dados['colunas'];
        $sIndexColumn = $dados['IndexColumn'];
        //$sIndexColumn = "id";
        //$aColumns = array('id', 'name', 'email', 'mobile', 'start_date');
        
        // QUERY LIMIT
        $sLimit = "";
        $disp_st = $this->input->get('start');
        $leng = $this->input->get('length');
        if (isset($disp_st) && $leng != '-1') {
            $sLimit = "LIMIT " . intval($disp_st) . ", " . intval($leng);
        }

        // QUERY ORDER
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ( $_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc' ) . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY")
                $sOrder = "";
        }
        
        // QUERY SEARCH
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        // BUILD QUERY
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "")
                    $sWhere = "WHERE ";
                else
                    $sWhere .= " AND ";
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }
        
        // FETCH
       // $sQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . " FROM $sTable $sWhere $sOrder $sLimit ";
        
        $sQuery = "SELECT COUNT(*) " . str_replace(" , ", " ", implode(", ", $aColumns)) . " FROM $sTable $sWhere $sOrder $sLimit";
        
        $rResult = $this->db->query($sQuery);
        
        $rResultFilterTotal = $this->db->query($sQuery);
        $aResultFilterTotal = $rResultFilterTotal->result_array();
        
        $iFilteredTotal = $aResultFilterTotal[0];
        $sQuery = " SELECT COUNT(" . $sIndexColumn . ") FROM $sTable ";
        
        //$rResultTotal = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
        $rResultTotal = $this->db->query($sQuery);
        $aResultTotal = $rResultTotal->result_array();
        $iTotal = $aResultTotal[0];
        
      //  print_r($aColumns);
       // print_r($rResult->result_array()); exit;
        foreach ( $rResult->result_array() as $aRow) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version")
                    $row[] = ( $aRow[$aColumns[$i]] == "0" ) ? '-' : $aRow[$aColumns[$i]];
                else if ($aColumns[$i] != ' ')
                   // print_r($aRow); exit;
                    $row[] = $aRow[$aColumns[$i]];
            }
            $output['aaData'][] = array_merge($row, array('<a data-id="row-' . $row[0] . '" href="javascript:editRow(' . $row[0] . ');" class="btn btn-md btn-success">edit</a>&nbsp;<a href="javascript:removeRow(' . $row[0] . ');" class="btn btn-default btn-md" style="background-color: #c83a2a;border-color: #b33426; color: #ffffff;">remove</a>'));
        }

        // RETURN IN JSON
        die(json_encode($output));
    }    
	public function monta_botao($chave) {
		return '<a data-id="row-' . $chave . '" href="javascript:editRow(' . $chave . ');" class="btn btn-md btn-success">edit</a>&nbsp;
		<a href="javascript:removeRow(' . $chave . ');" class="btn btn-default btn-md" style="background-color: #c83a2a;border-color: #b33426; color: #ffffff;">remove</a>';
	}	
}
