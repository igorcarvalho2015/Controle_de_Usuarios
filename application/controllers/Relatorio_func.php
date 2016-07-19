<?php

/**
 * RELATORIO DE FUNCIONARIOS
 * @author VAGNER/DOUGLAS/IGOR/RAFAEL (EQUIPE TCC)
 * @copyright TCC RESTRICAO DE INTEGRIDADE
 * @license
 * @package application
 * @subpackage controllers
 * @filesource Relatorio_func.php
 */
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/fpdf/pdf_mc_table.php');

class Relatorio_func extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $dados ['middle'] = 'relatorio_func';
        $dados ['pagina_atual'] = 'Relat&oacute;rio de funcion&aacute;rios';

        $this->load->view('header_footer/body', $dados);
    }

    public function gerar_pdf() {
        
        $this->load->model("RelEmpregAtivo_model");
        $result = $this->RelEmpregAtivo_model->listagem();

        $pdf = new MYPDF();

        $pdf->AliasNbPages();

        $pdf->AddPage('P', 'A4');

        $pdf->SetWidths(array(40, 80, 60));
        $pdf->SetAligns(array('R', 'L', 'L'));                

        if (count($result) > 0) {
            foreach ($result as $row) {
                $pdf->Row(array($row['Matricula'], $row['strNome'], $row['strCargo']));
            }
            $pdf->Cell(60, 8, 'Total ativos: '.count($result), 'B', 0, 'R');
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
        $this->Cell(60, 8, utf8_decode('RELATÓRIO DE FUNCIONÁRIOS ATIVOS'), 'B', 0, 'C');
        $this->SetFont('Arial', 'B', 8);        
        $this->Cell(60, 8, date('d/m/Y h:i:s'), 'B', 0, 'R');
        

        $this->Cell(9, 5, '', 0, 0, 'L');
        $this->Cell(80, 5, '', 0, 1, 'L');

        $this->Ln(3);
        $this->Cell(40, 6, utf8_decode('Matrícula'), 1, 0, 'C');
        $this->Cell(80, 6, 'Nome', 1, 0, 'C');
        $this->Cell(60, 6, 'Cargo', 1, 1, 'C');

        $this->Ln(6);
    }

}
