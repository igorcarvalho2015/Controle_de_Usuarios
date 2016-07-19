<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($erro = '') {

        $dados ['middle'] = 'home';
        $dados ['pagina_atual'] = 'Home';
        $this->load->view('header_footer/body', $dados);
    }

}
