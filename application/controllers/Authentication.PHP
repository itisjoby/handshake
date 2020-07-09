<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        // If already loggedin then redirect to the dashboard
        if ($this->session->has_userdata('user_id')) {
            redirect(site_url('dashboard'));
        }

        $data['browser'] = 1;


        $this->load->view('login', $data);
    }

    function login() {

        $this->load->library('form_validation');
        $update_err_data = array();
        $this->form_validation->set_rules('Username', 'Username', 'trim|required');
        $this->form_validation->set_rules('Password', 'Password', 'required');

        $ldap_bypass = true; //_LDAP_BYPASS_AUTHENTICATION;

        if ($this->form_validation->run() == FALSE) {
            redirect('authentication');
        } else {
            $username = $this->input->post('Username');
            $password = $this->input->post('Password');



            $user_det_query = "select u.consicutive_failed_login,u.created_at,u.email,u.first_name,u.last_login,u.id,u.last_name,
                                                u.last_password_change,u.password,u.phone_number,u.updated_at,u.username,r.id as role_id,
                                                r.name as role_name,p.link as pro_pic_url
                                                from users u 
                                                join roles r on r.id=u.role and r.status='active'
                                                join posts p on p.id=u.profile_pic_id
                                                where u.username='" . $username . "' and u.status='active'
                                                order by u.created_at asc";
            $user_details   = $this->Base_model->query($user_det_query)->result_array();

            if (count($user_details) > 0) {

                // Checking consecutive login failed
                if ($user_details[0]['updated_at'] != "") {
                    $last_modified_on = $user_details[0]['updated_at'];
                } else {
                    $last_modified_on = '1970-01-01 00:00:00';
                }

                $next_login_time = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($last_modified_on)));

                if ($user_details[0]['consicutive_failed_login'] >= $this->config->item('consecutive_failed_login_count') && $next_login_time > date('Y-m-d H:i:s') && $this->config->item('_ENABLE_ACCOUNT_LOGIN_POLICY')) {
                    $this->session->set_userdata('message_content', "Your account has been blocked temporarily due to too many failed login attempts. Please try after sometime.");
                    $this->session->set_userdata('message_class', "alert alert-warning");
                    redirect('authentication');
                } else {

                    if ($ldap_bypass) {
                        $login_status = $this->authenticate($user_details, $password);
                    } else {
                        $login_status = $this->ldapUserAuthenticate($user_details[0]['username'], $password);
                    }

                    if ($login_status) {

                        $password_change_days       = $this->config->item('_PASSWORD_EXPIRE_DAY');
                        $last_password_updated_date = $user_details[0]['last_password_change'];





                        //Get Menus assigned to a user and set it in session. From this session value the menu is created during each call.

                        $menu_query = "select m.controller_name,m.icon,m.name as menu_name,mo.name as module_name
                                        from role_menu_permissions rp 
                                        left join menu m on m.id=rp.menu_id and m.status='active'
                                        left join module mo on mo.id=m.module_id and mo.status='active'
                                        where rp.status='active' and  rp.role_id=" . $user_details[0]['role_id'] . "
                                        order by mo.priority asc,m.priority asc";


                        $menu_array = $this->Base_model->query($menu_query)->result_array();


                        if (isset($menu_array) && !empty($menu_array))
                            $this->session->set_userdata('menu_array', $menu_array);

                        //set account policies
                        if ($ldap_bypass) {
                            if ($last_password_updated_date == '')
                                $last_password_updated_date = "1970-01-01";
                            $next_password_updated_date = date('Y-m-d', strtotime($last_password_updated_date . "+" . $password_change_days . " days"));
                            if ($next_password_updated_date < date('Y-m-d') && $this->config->item('_ENABLE_ACCOUNT_LOGIN_POLICY'))
                                $this->session->set_userdata('password_change', "yes");
                            else
                                $this->session->set_userdata('password_change', "no");
                        }
                        else {
                            $this->session->set_userdata('password_change', "no");
                        }
                        //print_r($user_details);die;
                        // Setting User details in session
                        $this->session->set_userdata('user_id', $user_details[0]['id']);
                        $this->session->set_userdata('first_name', $user_details[0]['first_name']);
                        $this->session->set_userdata('last_name', $user_details[0]['last_name']);
                        $this->session->set_userdata('role_id', $user_details[0]['role_id']);
                        $this->session->set_userdata('role_name', $user_details[0]['role_name']);
                        $this->session->set_userdata('pro_pic_url', $user_details[0]['pro_pic_url']);

                        $upcond  = array('id' => $user_details[0]['id']);
                        $updata  = array('consicutive_failed_login' => NULL, 'last_login' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));
                        $up_retn = $this->Base_model->updateData('Users', $updata, $upcond);
//                        print_r($_SESSION);
//                        print_r($menu_array);die;
                        if ($this->session->has_userdata('referer_url') && $this->session->userdata('referer_url') != "") {
                            $referer_url = $this->session->userdata('referer_url');
                            $this->session->unset_userdata('referer_url');
                            //redirect($referer_url);
                            echo json_encode(array("status" => 1, "url" => $referer_url));
                            die;
                        } else {
                            echo json_encode(array("status" => 1, "url" => 'dashboard/index'));
                            die;
                            //redirect('dashboard/index');
                        }
                    } else {

                        $upcond  = array('ID' => $user_details[0]['ID']);
                        $updata  = array('ConsecutiveFailedLogin' => ($user_details[0]['consicutive_failed_login'] + 1), 'LastModifiedOn' => date('Y-m-d H:i:s'));
                        $up_retn = $this->Base_model->updateData('Users', $updata, $upcond);

                        $this->session->set_userdata('message_content', "The entered User Id/Password is incorrect.");
                        $this->session->set_userdata('message_class', "alert alert-warning");
                        echo json_encode(array("status" => 0, "url" => 'authentication'));
                        die;
                        //redirect('authentication');
                    }
                }
            } else {
                $this->session->set_userdata('message_content', "Login details not available. Please contact administrator.");
                $this->session->set_userdata('message_class', "alert alert-warning");
                echo json_encode(array("status" => 0, "url" => 'authentication'));
                die;
                //redirect('authentication');
            }
        }
    }

    private function authenticate($result, $password) {
        return 1;
        if ($result != "" && $password != "") {
            $hash = $result[0]['Password'];

            if (password_verify($password, $hash)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url());
        exit;
    }

    /*
     * function written by joby
     * FOR CHANGE PASSWORD MODEL
     */

    function loadchangePassword() {
        $data['page_action'] = 'change_password_modal';
        $html                = $this->load->view('change_password', $data, TRUE);
        echo json_encode(array("STATUS" => "SUCCESS", "HTML" => $html));
    }

    /*
     * function written by joby
     */

    function updatePassword() {
        $form_data = $this->input->post();

        if (isset($form_data['currentpass']) && $form_data['currentpass'] != '' && isset($form_data['newpass']) && $form_data['newpass'] != '' && isset($form_data['confirmpass']) && $form_data['confirmpass'] != '') {
            if ($form_data['newpass'] != $form_data['confirmpass']) {
                echo json_encode(array("STATUS" => "FAILED", "MESSAGE" => "Passwords not matching."));
                return;
            }
            if (strlen($form_data['newpass']) < 8) {
                echo json_encode(array("STATUS" => "FAILED", "MESSAGE" => "Password must contain atleast 8 characters"));
                exit;
            }

            $uppercase   = preg_match('@[A-Z]@', $form_data['newpass']); //has uppercase or not
            $lowercase   = preg_match('@[a-z]@', $form_data['newpass']); //HAS lowercase or not
            $firstletter = preg_match("/^[A-Za-z]+/", $form_data['newpass']); //FIRST LETTER letter or not
            $number      = preg_match('@[0-9]@', $form_data['newpass']); //has number or not

            $uppercase_confirmpass   = preg_match('@[A-Z]@', $form_data['confirmpass']);
            $lowercase_confirmpass   = preg_match('@[a-z]@', $form_data['confirmpass']);
            $firstletter_confirmpass = preg_match("/^[A-Za-z]+/", $form_data['confirmpass']);
            $number_confirmpass      = preg_match('@[0-9]@', $form_data['newpass']);

            $uppercase_currentpass   = preg_match('@[A-Z]@', $form_data['currentpass']);
            $lowercase_currentpass   = preg_match('@[a-z]@', $form_data['currentpass']);
            $firstletter_currentpass = preg_match("/^[A-Za-z]+/", $form_data['currentpass']);
            $number_currentpass      = preg_match('@[0-9]@', $form_data['newpass']);


            if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(array("status" => 0, "MESSAGE" => "Invalid email"));
                exit;
            }

            if (!$firstletter || !$firstletter_confirmpass || !$firstletter_currentpass) {
                echo json_encode(array("STATUS" => "FAILED", "MESSAGE" => "First character of Password must be a letter"));
                exit;
            }

            if (!$uppercase || !$uppercase_confirmpass || !$uppercase_currentpass) {
                echo json_encode(array("STATUS" => "FAILED", "MESSAGE" => "Password must contain atleast one uppercase character"));
                exit;
            }

            if (!$lowercase || !$lowercase_confirmpass || !$lowercase_currentpass) {
                echo json_encode(array("STATUS" => "FAILED", "MESSAGE" => "Password must contain atleast one lowercase character"));
                exit;
            }
            if (!$number || !$number_confirmpass || !$number_currentpass) {
                echo json_encode(array("STATUS" => "FAILED", "MESSAGE" => "Password must contain atleast one number"));
                exit;
            }



            $this->validatePassword($form_data);
        } else {
            echo json_encode(array("STATUS" => "FAILED", "MESSAGE" => "Password fields cannot be empty."));
            exit;
        }
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
