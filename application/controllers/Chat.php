<?php

defined('BASEPATH') OR exit('No direct script access allowed');
define('HOST_NAME', "localhost");
define('PORT', "80");
$null = NULL;

class Chat extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($reciever_id, $type) {

        //$this->functions->resetSessions();
        $user_id              = $this->session->userdata("user_id");
        $data['reciever_id']  = $reciever_id;
        $data['user_id']      = $user_id;
        $data['unknown_error'] =   0;
        $data['left_group']=0;
        $this->load->library("DataEasyAccess");
        $this->load->library("ChatLibrary");
        $data['chat_history'] = $this->chatlibrary->getChatHistory($user_id);
        if ($type == 'group') {
            $data['is_private'] = 0;
            $gp_member_q        = "select u.id,mp.status from users u
                                    join msg_participants mp on mp.user_id=u.id 
                                    join msg_groups mg on mg.id=mp.group_id and mg.status='active'
                                    where mg.id=" . $reciever_id;
            $group_members_arr  = $this->Base_model->query($gp_member_q)->result_array();

            $existing_participant=array_search($user_id,array_column($group_members_arr,"id"));
             
            if($existing_participant!== False){
                 if($group_members_arr[$existing_participant]['status']=='deleted'){
                     $data['left_group']=1;
                 }
            }
            else{
                 $data['unknown_error'] =   1;
            }
            
            
            if (!empty($group_members_arr)) {
                $group_members       = array_column($group_members_arr, "id");
                $data['friend_info'] = $this->dataeasyaccess->getBasicData($group_members);

                if (!empty($data['friend_info'])) {
                    foreach ($data['friend_info'] as $k => $v) {
                        $temp[$v['id']] = $v;
                    }
                }
                $data['friend_info'] = $temp;
                $data['msg_history'] = $this->chatlibrary->getGroupMessageHistory($reciever_id, $user_id);
                $data['group_data']  = $this->chatlibrary->getGroupDetails($reciever_id);
            }
        } else {
            $data['is_private']  = 1;
            $data['friend_info'] = $this->dataeasyaccess->getBasicData($reciever_id);
            $data['my_info']     = $this->dataeasyaccess->getBasicData($user_id);
            $data['msg_history'] = $this->chatlibrary->getMessageHistory($reciever_id, $user_id);
            //echo "<pre>";print_r($data['friend_info']);die;
        }
//        echo "<pre>";
//        print_r($data);
//        die;
        //print_r($result);
        //die;

        $data['page']       = 'chat';
        $data['page_title'] = 'chat';

        $data['action']   = "home";
        $data['js_files'] = array('chat.js');
        $this->load->view('layout/layout', $data);
    }

    function saveMessages() {

        $user_id   = $this->session->userdata("user_id");
        $form_data = $this->input->post();
        $file_link = null;
        $this->db->trans_begin();





        //FILE UPLOAD BEGINS
        if (isset($_FILES['file']['name']) && count($_FILES['file']['name']) > 0) {

            //finding last index of array to use as a count
            end($_FILES['file']['name']);
            $count = key($_FILES['file']['name']);

            for ($j = 0; $j <= $count; $j++) {

                if (isset($_FILES['file']['name'][$j]) && $_FILES['file']['name'][$j] != "") {

                    //converting files from multi dimentional array format to  normal file format
                    //$_FILES['image']['name'][0] to $_FILES['image']['name']
                    $_FILES['temp']['name']     = $_FILES['file']['name'][$j];
                    $_FILES['temp']['type']     = $_FILES['file']['type'][$j];
                    $_FILES['temp']['tmp_name'] = $_FILES['file']['tmp_name'][$j];
                    $_FILES['temp']['error']    = $_FILES['file']['error'][$j];
                    $_FILES['temp']['size']     = $_FILES['file']['size'][$j];
                    //doing upload
                    $file_upload                = $this->functions->fileUpload("temp", "Doc_" . date("Y m d h is") . $j, 'photos');



                    if ($file_upload['STATUS'] == 'SUCCESS') {



                        $file_link = $file_upload['FILE_NAME'];
                    }
                }
            }
        }




        $ev_arrays['f_name']    = 'sender_id';
        $ev_arrays['f_value']   = $user_id;
        $ev_arrays['is_arabic'] = 0;
        $main_arr[]             = $ev_arrays;
        $ev_arrays              = array();

        $ev_arrays['f_name']    = 'reciever_id';
        $ev_arrays['f_value']   = $form_data['reciever_id']; //groupid,userid
        $ev_arrays['is_arabic'] = 0;
        $main_arr[]             = $ev_arrays;
        $ev_arrays              = array();

        $ev_arrays['f_name']    = 'is_private';
        $ev_arrays['f_value']   = $form_data['is_private'];
        $ev_arrays['is_arabic'] = 0;
        $main_arr[]             = $ev_arrays;
        $ev_arrays              = array();

        $ev_arrays['f_name']    = 'message';
        $ev_arrays['f_value']   = trim($form_data['message']);
        $ev_arrays['is_arabic'] = 1;
        $main_arr[]             = $ev_arrays;
        $ev_arrays              = array();

        $ev_arrays['f_name']    = 'sent_time';
        $ev_arrays['f_value']   = date("Y-m-d H:i:s");
        $ev_arrays['is_arabic'] = 0;
        $main_arr[]             = $ev_arrays;
        $ev_arrays              = array();

        $ev_arrays['f_name']    = 'status';
        $ev_arrays['f_value']   = "active";
        $ev_arrays['is_arabic'] = 0;
        $main_arr[]             = $ev_arrays;
        $ev_arrays              = array();

        if (trim($form_data['action']) == 'quoted') {
            $ev_arrays['f_name']    = 'quoted_msg_id';
            $ev_arrays['f_value']   = $form_data['quoted_msg_id'];
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();

            $ev_arrays['f_name']    = 'action';
            $ev_arrays['f_value']   = $form_data['action'];
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();
        } else if (trim($form_data['action']) == 'forwarded') {
            $ev_arrays['f_name']    = 'forwarded_from_userid';
            $ev_arrays['f_value']   = $form_data['forwarded_from_userid'];
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();

            $ev_arrays['f_name']    = 'action';
            $ev_arrays['f_value']   = $form_data['action'];
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();
        }
        if (trim($file_link) == '') {
            $ev_arrays['f_name']    = 'msgtype';
            $ev_arrays['f_value']   = 'msg';
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();
        } else {
            $ev_arrays['f_name']    = 'msgtype';
            $ev_arrays['f_value']   = 'file';
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();

            $ev_arrays['f_name']    = 'filelink';
            $ev_arrays['f_value']   = $file_link;
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();
        }

        $ins = $this->Base_model->saveArabicData('messages', $main_arr);
        if (!$ins) {
            $this->db->trans_rollback();
            echo json_encode(array("status" => 0, "message" => 'Failed! unable to sent messages. Please try again later'));
            die;
        }
        $this->db->trans_commit();
        echo json_encode(array("status" => 1, "message" => "Message sent successfully!..."));

        die;
    }

    function checkNewMessages() {

        $personal_recipients = $this->input->post('personal_recipients');
        $lastseen            = $this->input->post('lastseen');
        if (!empty($personal_recipients)) {
            $personal_recipients = array_values(array_filter(array_unique($personal_recipients)));
            $this->ReadPersonalMessage($personal_recipients, $lastseen);
        }
        $group_recipients = $this->input->post('group_recipients');
        if (!empty($group_recipients)) {
            $group_recipients = array_values(array_filter(array_unique($group_recipients)));
            $this->ReadGroupMessage($group_recipients, $lastseen);
        }

        //update readstatus 
        sleep(2);
        echo json_encode(array("status" => 1));
    }

    function ReadPersonalMessage($personal_recipients) {

        $date   = date("Y-m-d H:i:s");
        $query  = "update messages set read_time='" . $date . "' where read_time is null and sent_time< '" . $lastseen . "' and sender_id in ('" . implode("', '", $personal_recipients) . "')";
        $update = $this->Base_model->query($query);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    function ReadGroupMessage($group_recipients, $lastseen) {
        $user_id = $this->session->userdata("user_id");

        $q          = "select m.id,mg.id as group_id from msg_groups mg
                        join msg_participants mp on mp.group_id=mg.id
                        join messages m on m.reciever_id=mg.id
                        left join msg_seen_history sh on sh.group_id=mg.id and sh.user_id=mp.user_id and m.id=sh.msg_id
                        where mg.id in ('" . implode("', '", $group_recipients) . "') and sent_time< '" . $lastseen . "'  and mp.user_id=" . $user_id . " and sh.id is null";
        //echo $q;
        $msg_id_arr = $this->Base_model->query($q)->result_array();
        if (!empty($msg_id_arr)) {
            $msg_arr = [];
            foreach ($msg_id_arr as $key => $val) {

                $array['group_id']   = $val['group_id'];
                $array['user_id']    = $user_id;
                $array['msg_id']     = $val['id'];
                $array['status']     = 'active';
                $array['created_at'] = date("Y-m-d H:i:s");
                $msg_arr[]           = $array;
            }
            if (!empty($msg_arr)) {
                $insert = $this->Base_model->saveBatchData('msg_seen_history', $msg_arr);
                if ($insert) {
                    return true;
                }
            }
        }

        return false;
    }

    function loadcreateGroupChat() {


        $data['action'] = "create_group";
        $html           = $this->load->view("chat", $data, true);
        echo json_encode(['status' => 1, 'html' => $html]);
        die;
    }

    function createGroupChat() {

        $form_data           = $this->input->post();
        $user_id             = $this->session->userdata("user_id");
        $array['name']       = $form_data['groupname'];
        $array['status']     = 'active';
        $array['created_by'] = $user_id;
        $array['created_at'] = date("Y-m-d H:i:s");
        $this->db->trans_begin();
        $ins                 = $this->Base_model->saveData("msg_groups", $array);
        if (!$ins) {
            $this->db->trans_rollback();
            echo json_encode(array("status" => 0, "message" => 'Failed! unable to create group. Please try again later'));
            die;
        }


        $part_array['group_id']  = $ins;
        $part_array['user_id']   = $user_id;
        $part_array['privilage'] = 'admin';
        $part_array['status']    = 'active';
        $part_array['added_on']  = date("Y-m-d H:i:s");
        $part_array['msgcleared_on']  = date("Y-m-d H:i:s");
        $part_array['priority']  = 0;


        $ins_part = $this->Base_model->saveData("msg_participants", $part_array);
        if (!$ins_part) {
            $this->db->trans_rollback();
            echo json_encode(array("status" => 0, "message" => 'Failed! unable to create group. Please try again later'));
            die;
        }

        $msg_array['sender_id']   = $user_id;
        $msg_array['reciever_id'] = $ins;
        $msg_array['message']     = "Group created";
        $msg_array['is_private']  = 0;
        $msg_array['sent_time']   = date("Y-m-d H:i:s");
        $msg_array['status']      = 'active';
        $msg_array['msgtype']     = 'system';

        $ins_msg = $this->Base_model->saveData("messages", $msg_array);
        if (!$ins_msg) {
            $this->db->trans_rollback();
            echo json_encode(array("status" => 0, "message" => 'Failed! unable to create group. Please try again later'));
            die;
        }
        $this->session->set_flashdata('success_msg', 'new group created successfully!...');
        $this->db->trans_commit();
        echo json_encode(array("status" => 1, "groupid" => $ins));

        die;
    }

    function viewMembers($group_id) {
        //$group_id                 = $this->input->post('group_id');
        $this->load->library("ChatLibrary");
        $this->load->library("DataEasyAccess");
        $user_id                     = $this->session->userdata("user_id");
        $data['friend_list']         = $this->dataeasyaccess->getMyFriendList($user_id);
        $data['pending_friend_list'] = $this->dataeasyaccess->getPendingFriendList($user_id);
        $data['group_member_arr']    = $this->chatlibrary->getGroupMembers($group_id);
        $data['group_id']            = $group_id;
        $data['action']              = "view_member";
        $data['user_id']             = $user_id;
        $data['page']                = 'chat';
        $data['page_title']          = 'chat';
        $data['js_files']            = array('chat.js');
        $this->load->view('layout/layout', $data);
    }

    function loadAddGroupMember() {
        $group_id                 = $this->input->post('group_id');
        $this->load->library("ChatLibrary");
        $this->load->library("DataEasyAccess");
        $user_id                  = $this->session->userdata("user_id");
        $data['friend_list']      = $this->dataeasyaccess->getMyFriendList($user_id);
        $data['group_member_arr'] = $this->chatlibrary->getGroupMembers($group_id);
        $data['group_id']         = $group_id;
        $data['action']           = "add_group_member";
        $data['user_id']          = $user_id;
        $html                     = $this->load->view('chat', $data, true);
        echo json_encode(array("status" => 1, "html" => $html));
        die;
    }

    function addGroupMember() {
        $user_id   = $this->session->userdata("user_id");
        $form_data = $this->input->post();


        if (!empty($form_data['friendlist'])) {
            $this->db->trans_begin();
            foreach ($form_data['friendlist'] as $key => $val) {
                $part_array['group_id']      = $form_data['group_id'];
                $part_array['user_id']       = $val;
                $part_array['privilage']     = 'member';
                $part_array['status']        = 'active';
                $part_array['added_on']      = date("Y-m-d H:i:s");
                $part_array['msgcleared_on'] = date("Y-m-d H:i:s");
                $part_array['priority']      = 0;
                $array[]                     = $part_array;
            }

            $ins_part = $this->Base_model->saveBatchData("msg_participants", $array);
            if (!$ins_part) {
                $this->db->trans_rollback();
                echo json_encode(array("status" => 0, "message" => 'Failed! unable to add members. Please try again later'));
                die;
            }
            $this->db->trans_commit();
            echo json_encode(array("status" => 1, "message" => "Selected members added to the group successfully"));
            exit;
        }
        $this->db->trans_rollback();
        echo json_encode(array("status" => 0, "message" => 'Failed! unable to add members. Please try again later'));
        die;
    }

    function leaveMsgGroup() {
        $user_id   = $this->session->userdata("user_id");
        $form_data = $this->input->post();
        $q         = "update msg_participants set status='deleted',message_paused_on='".date("Y-m-d H:i:s")."' where user_id=" . $user_id . " and group_id=" . $form_data['id'];
        $update    = $this->Base_model->query($q);
        if ($update) {
            echo json_encode(array("status" => 1, "message" => 'Success! left from group successfully'));
            die;
        } else {
            echo json_encode(array("status" => 0, "message" => 'Failed! Unable to leave group. Please try again later'));
            die;
        }
    }

    function savePicture($src_path) {


        $this->load->helper('download');

        $ext  = pathinfo($src_path, PATHINFO_EXTENSION);
        $name = "File" . time() . $ext;

        $src_path = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $src_path;
        $data     = file_get_contents($src_path); // Read the file's contents
        force_download($name, $data);
    }

    function changePicture() {
        $data['action']   = "photo_upload";
        $data['group_id'] = $this->input->post("group_id");
        $html             = $this->load->view("chat", $data, true);
        echo json_encode(['status' => 1, 'html' => $html]);
        die;
    }

    function changeGroupPic() {
        $file_name = $this->upload();
        $form_data = $this->input->post();
        $update    = $this->Base_model->updateData('msg_groups', array('icon' => $file_name), array('id' => $form_data['group_id']));
        if (!$update) {

            echo json_encode(array("status" => 0, "message" => 'Failed! unable to change picture. Please try again later'));
            die;
        }

        echo json_encode(array("status" => 1, "message" => "picture changed successfully","groupid"=>$form_data['group_id']));
        exit;
    }

    function upload() {

        if ($_FILES['file']['name'] != "" && $_FILES['file']['name'] != "") {
            // File upload, upload both images
            $up_brtn = $this->functions->fileUpload('file', 'front_' . date('YmdHis'), 'photos');



            if ($up_brtn['STATUS'] != "SUCCESS") {
                ///error
                return false;
            }

            // Image conversions, greyscale(b&w), rotate and croping
            $up_back_file = FCPATH . 'uploads\photos\\' . $up_brtn['FILE_NAME']; //$file_image;
            $image        = new Imagick($up_back_file);
            if (isset($_POST['dataRotate']) && $_POST['dataRotate'] > 0) {
                $image->rotateImage('white', $_POST['dataRotate']);
            }
            $image->cropImage($_POST['width'], $_POST['height'], $_POST['x'], $_POST['y']);
            $image->writeImage(FCPATH . 'uploads/photos/' . $up_brtn['FILE_NAME'] . "_edited.jpg");
            $image->clear();

            $up_back_file = FCPATH . 'uploads/photos/' . $up_brtn['FILE_NAME'] . "_edited.jpg";

            $image = new Imagick($up_back_file);
            //convert to grey scale
            // $image->modulateImage(100, 0, 100);
            //Reduces the speckle noise 
            $image->despeckleImage();
            //set resolution to 300 dpi
            //$image->setImageResolution( 300, 300 ); 

            $image->writeImage(FCPATH . 'uploads/photos/' . $up_brtn['FILE_NAME'] . "_edited.jpg");

            $image->clear();


            return $up_brtn['FILE_NAME'] . "_edited.jpg";
        }
    }
    function deleteGroup(){
        $group_id   =   $this->input->post("group_id");
        $user_id   = $this->session->userdata("user_id");
        $date   = date("Y-m-d H:i:s");
        $query  = "update msg_participants set msgcleared_on='" . $date . "' where  group_id =".$group_id." and user_id=".$user_id;
        $update = $this->Base_model->query($query);
        if ($update) {
            echo json_encode(array("status"=>1));die;
        } else {
           echo json_encode(array("status"=>0));die;
        }
        
    }

}
