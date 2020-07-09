<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ChatHome extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        //$this->functions->resetSessions();
        $user_id               = $this->session->userdata("user_id");
        $data['user_id']       = $user_id;
        $data['unknown_error'] = 0;
        $data['left_group']    = 0;
        $this->load->library("DataEasyAccess");
        $this->load->library("ChatLibrary");
        $data['chat_history']  = $this->chatlibrary->getChatHistory($user_id);
        $data['my_info']     = $this->dataeasyaccess->getBasicData($user_id);
//        echo "<pre>";
//        print_r($data);
//        die;
        //print_r($result);
        //die;

        $data['page']       = 'chathome';
        $data['page_title'] = 'chat';

        $data['action']   = "home";
        $data['js_files'] = array('chathome.js');
        $this->load->view('layout/layout', $data);
    }

}
