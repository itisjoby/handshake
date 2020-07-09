<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {


        $data['page']       = 'games/index';
        $data['page_title'] = 'Dashboard';

        $data['action']   = "home";
        $data['js_files'] = array('games/index.js');
        $this->load->view('layout/layout', $data);
    }

}
