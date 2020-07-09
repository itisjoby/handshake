<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MY_Controller extends CI_Controller {

    public $controller_name;
    public $method_name;
    
    function __construct() {
        parent::__construct();
        
        date_default_timezone_set($this->config->item('php_timezone'));
        
        // Login checking
        $this->controller_name          =   $this->router->fetch_class();
        $this->method_name              =   $this->router->fetch_method();

        if (strtolower($this->controller_name) != 'authentication' && strtolower($this->controller_name) != 'scheduledtask') {
            if (!$this->session->has_userdata('user_id')) { 
                if ((isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO']))) {
                    $this->session->set_userdata('referer_url', $_SERVER['PATH_INFO']);
                }

                if (!$this->input->is_ajax_request()) {
                    $this->session->set_userdata('message_content', "Your session has expired. Please login again.");
                    $this->session->set_userdata('message_class', "alert alert-warning");
                    redirect(base_url());
                    exit;
                }
                else {
                    $this->session->set_userdata('message_content', "Your session has expired. Please login again.");
                    $this->session->set_userdata('message_class', "alert alert-danger");
                    $rtn_value = array("status" => 1, "message" => "Your session has expired. Please login again.");
                    
                    echo json_encode($rtn_value);
                    exit;
                }  
            }
            else { 
                    if((trim($this->session->userdata('first_name'))== '' || trim($this->session->userdata('last_name')) == '') && strtolower($this->controller_name) != 'member'){
                        redirect(site_url("Member/getEditProfile"));
                    }
            }
        }
    }

    /**
     * Get logged user id
     * @return boolean
     */
    public function getLoggedUserId() {
        if ($this->session->has_userdata('user_id')) {
            return $this->session->userdata('user_id');
        } else {
            return false;
        }
    }

   
    
    /**
     * Function to check user have permission on the current/specified controller
     * @param type $controller Controller name
     * @return type Array specifing each permission true/false
     */
    public function checkPermission($controller=NULL) {
        if($controller===NULL || $controller=="")
            $controller = $this->controller_name;
        $user_id        = $this->getLoggedUserId();
        $permissions    =   array();
        
        $select         =   "p.ViewEnabled,p.AddEnabled,p.EditEnabled,p.DeleteEnabled,r.IsModuleDependant,un.OwnerType UserOwnerType,m.OwnerType MenuOwnerType";
        
        $join[0]	=   array('table' => 'RoleMenuPermissions p', 'condition' => 'p.Menu_ID=m.ID and  p.Status=\'active\'',"type"=>"");
        $join[1]	=   array('table' => 'Roles r', 'condition' => 'r.ID=p.Role_ID AND r.Status=\'Active\'',"type"=>"");
        $join[2]	=   array('table' => 'Users u', 'condition' => 'u.Role_ID=r.ID AND u.ID=\''.$user_id.'\' AND u.Status=\'Active\'',"type"=>"");
        $join[3]	=   array('table' => 'Units un', 'condition' => 'un.ID=u.Unit_ID',"type"=>"left");
        $cond           =   "m.ControllerName='$controller' AND m.Status='active'";
        
        $permission_arr =   $this->Base_model->getData($select,'Menus m',$join,$cond);
        
      //echo $this->db->last_query();exit;
        $permissions['controller']    =   isset($permission_arr[0]['ViewEnabled']) && trim(strtolower($permission_arr[0]['ViewEnabled']))==="yes"? 1:0;
        $permissions['view']    =   isset($permission_arr[0]['ViewEnabled']) && trim(strtolower($permission_arr[0]['ViewEnabled']))==="yes"? 1:0;
        $permissions['add']     =   isset($permission_arr[0]['AddEnabled']) && trim(strtolower($permission_arr[0]['AddEnabled']))==="yes"? 1:0;
        $permissions['edit']    =   isset($permission_arr[0]['EditEnabled']) && trim(strtolower($permission_arr[0]['EditEnabled']))==="yes"? 1:0;
        $permissions['delete']  =   isset($permission_arr[0]['DeleteEnabled']) && trim(strtolower($permission_arr[0]['DeleteEnabled']))==="yes"? 1:0;

        if(isset($permission_arr[0]['IsModuleDependant']) && trim(strtolower($permission_arr[0]['IsModuleDependant']))=='yes'  && $permission_arr[0]['MenuOwnerType'] != NULL){
            if(isset($permission_arr[0]['UserOwnerType'])&&isset($permission_arr[0]['MenuOwnerType'])&&trim($permission_arr[0]['UserOwnerType'])==trim($permission_arr[0]['MenuOwnerType'])){
                return $permissions;
            }
            else {
                return array('controller'   => $controller, 
                            'view'      => 0, 
                            'add'       => 0, 
                            'edit'      => 0, 
                            'delete'    => 0);
            }
        }
        else {
            return $permissions;
        }
        
    }      
}
?>