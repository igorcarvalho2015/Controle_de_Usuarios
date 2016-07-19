<?php

/**
 * RELATORIO DE LOGS
 * @author VAGNER/DOUGLAS/IGOR/RAFAEL (EQUIPE TCC)
 * @copyright TCC RESTRICAO DE INTEGRIDADE
 * @license
 * @package application
 * @subpackage controllers
 * @filesource Relatorio_log.php
 */
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/fpdf/pdf_mc_table.php');

class Relatorio_log extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $dados ['middle'] = 'relatorio_log';
        $dados ['pagina_atual'] = 'Relat&oacute;rio de Logs';

        $this->load->view('header_footer/body', $dados);
    }

    public function gerar_pdf() {
        
        $this->load->model("RelAcoeUser_model");
        $result = $this->RelAcoeUser_model->listagem();

        $pdf = new MYPDF();

        $pdf->AliasNbPages();

        $pdf->AddPage('P', 'A4');

        $pdf->SetWidths(array(40, 110, 30));
        $pdf->SetAligns(array('L', 'L', 'C'));                

        if (count($result) > 0) {
            foreach ($result as $row) {
                $pdf->Row(array($row['strLogin'], $row['acao'], $this->data_ing_para_pt($row['dtacao'])));
            }
            $pdf->Cell(60, 8, 'Total : '.count($result), 'B', 0, 'R');
        }
        ob_clean();

        $pdf->Output('.funcionarios', 'I');
    }

}

class MYPDF extends PDF_MC_Table {

    function Header() {

        $image_file = FCPATH . 'assets/img/unicarioca.png';

        $this->Image($image_file, 8, 6, 20, 12);

        $this->SetFont('Arial', 'B', 15);
        $this->Cell(60, 8, '', 'B', 0, 'R');
        $this->Cell(60, 8, utf8_decode('RELATÓRIO DE LOGS'), 'B', 0, 'C');
        $this->SetFont('Arial', 'B', 8);        
        $this->Cell(60, 8, date('d/m/Y h:i:s'), 'B', 0, 'R');
        

        $this->Cell(9, 5, '', 0, 0, 'L');
        $this->Cell(80, 5, '', 0, 1, 'L');

        $this->Ln(3);
        $this->Cell(40, 6, 'Login', 1, 0, 'C');
        $this->Cell(110, 6, utf8_decode('Ação'), 1, 0, 'C');
        $this->Cell(30, 6, 'Data', 1, 1, 'C');

        $this->Ln(6);
    }

}
