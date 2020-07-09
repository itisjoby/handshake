<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {

	public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        $this->load->view('register');
    }
    
    public function saveUser() {
        

		//print_r($_POST);die;
		$form_data	=	$this->input->post();
		
		//array data
		
		$array['username']				=	(isset($form_data['uname']) && $form_data['uname']!='')?trim($form_data['uname']):null;
                $password                                       =       (isset($form_data['password']) && $form_data['password']!='')?trim($form_data['password']):null;
		$hashed                                         =	password_hash(trim($password), PASSWORD_DEFAULT);
                $array['password']                              =	$hashed;
		$array['role']                                  =	2;
		$array['status']                                =	'active';
		$array['last_password_change']                  =	date("Y-m-d");
		$array['last_login']				=	date("Y-m-d");
		$array['created_at']				=	date("Y-m-d");
		$array['updated_at']				=	date("Y-m-d");
                
		$this->db->trans_begin();
		$ins_id			=	$this->Base_model->saveData('users',$array);
		if(!$ins_id){
			$this->db->trans_rollback();
			echo json_encode(array("status" => 0,"message"=>"Failed! Registation failed. please try again later"));
			return;
		}
		
                $this->session->set_userdata('user_id', $ins_id);
                
                //generating default albums begins
                $albums_list                                    =   $this->config->item("default_albums");
                foreach($albums_list as $key=>$val){
                    $this->functions->generateDefaultPhotoAlbums($val['name'],$val['description'],$ins_id);
                }
                //album generation ends
		$this->db->trans_commit();
		echo json_encode(array("status" => 1,"message"=>"Your registration completed successfully."));
		return;
		
	}
        
        function forgotPassword() {
		$this->load->view('forgot_password');
	}
        function ResendPassword(){
            
            $email  =   $this->input->post("email");
            if(trim($email) == ''){
                echo json_encode(array("status"=>0,"msg"=>"Please provide your email to retrieve your account back"));
                die;
            }
            $query  =   "select u.id from users u where u.email='".$email."'";
            $result =   $this->Base_model->query($query)->result_array();
            if(!empty($result)){
                echo json_encode(array("status"=>1,"msg"=>"we send you an email on the provided address.please go through the email and follow the information correctly to regain access to your account"));
                die;
            }
            else{
                    echo json_encode(array("status"=>0,"msg"=>"we didnt find such email in our system.please contact support for additional help"));
                    die;
            }
            
        }
}
