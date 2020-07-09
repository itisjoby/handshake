<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Functions
{

    public $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    /* Function to push notification into table
     * @param $sender_id int user table id
     * @param $message string notification to be listed
     * @param $message_type string [Optional] Alert/ Notification/ Reminder. Default value is Notification
     * @param $receiver_user_id array [Optional] one dimension array of user table ids. Either $receiver_user_id or $receiver_ms_user_id is mandatory.
     * @param $receiver_ms_user_id array [Optional] one dimension array of mystery shopper user table ids. Either $receiver_user_id or $receiver_ms_user_id is mandatory.
     * @param $due_date integer [Optional] duedate of notification
     * @param $menu_id integer [Optional] menu table id of the controller to be opened
     */
    public function push_notification($sender_id, $message, $message_type = "Notification", $receiver_user_id = "", $receiver_ms_user_id = "", $due_date = "", $menu_id = "", $controller_name = "", $ms_user_type = "MS", $table_name = "Notifications")
    {

        if (!$receiver_user_id && !$receiver_ms_user_id) {
            return array("STATUS" => "FAILED", "MESSAGE" => "Mandatory fields missing.");
        } else {
            $select                 =   "(ISNULL(MAX(Code),0)+1) AS Code";
            $table                  =   'Notifications';
            $code                   =   $this->ci->Base_model->getData($select, $table);

            if (isset($receiver_user_id) && !empty($receiver_user_id)) {

                //insert for each $receiver_user_id
                $check_flag = true;
                for ($i = 0; $i < count($receiver_user_id); $i++) {
                    $check    =   $this->add_notification($code[0]['Code'], $message, $message_type, $due_date, $menu_id, $sender_id, $receiver_user_id[$i], "", $controller_name, $ms_user_type, $table_name);
                    if (!$check) {
                        $check_flag = false;
                        break;
                    }
                }
            }
            if (isset($receiver_ms_user_id) && !empty($receiver_ms_user_id)) {

                //insert for each $receiver_ms_user_id
                $check_flag = true;
                for ($i = 0; $i < count($receiver_ms_user_id); $i++) {
                    $check    =   $this->add_notification($code[0]['Code'], $message, $message_type, $due_date, $menu_id, $sender_id, "", $receiver_ms_user_id[$i], $controller_name, $ms_user_type, $table_name);
                    if (!$check) {
                        $check_flag = false;
                        break;
                    }
                }
            }
            if ($check_flag) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * Function to save notifications into table
     * @author Anju
     */
    protected function add_notification($code, $message, $message_type, $due_date, $menu_id, $sender_id, $receiver_user_id = "", $receiver_ms_user_id = "", $controller_name = "", $ms_user_type = "MS", $table_name = "Notifications")
    {

        $menu_id                =   isset($menu_id) && $menu_id != "" ? $menu_id : NULL;
        $due_date               =   isset($due_date) && $due_date != "" ? $due_date : NULL;
        $receiver_user_id       =   isset($receiver_user_id) && $receiver_user_id != "" ? $receiver_user_id : NULL;
        $receiver_ms_user_id    =   isset($receiver_ms_user_id) && $receiver_ms_user_id != "" ? $receiver_ms_user_id : NULL;
        $controller_name        =   isset($controller_name) && $controller_name != "" ? $controller_name : NULL;
        $date                   =   date('Y-m-d H:i:s');

        $data   =   array(
            "Code"              =>  $code,
            "Notification"      =>  $message,
            "NotificationType"  =>  $message_type,
            "DueDate"           =>  $due_date,
            "Menu_ID"           =>  $menu_id,
            "ReceiverUser_ID"   =>  $receiver_user_id,
            "ReceiverMSUser_ID" =>  $receiver_ms_user_id,
            "CreatedBy"         =>  $sender_id,
            "CreatedOn"         =>  $date,
            "Status"            =>  "new",
            "ControllerName"    =>  $controller_name,
            "MSUserType"        =>  $ms_user_type
        );

        $notification_id    =   $this->ci->Base_model->saveData($table_name, $data);

        if (isset($notification_id) && $notification_id != "") {
            return $notification_id;
        } else {
            return false;
        }
    }

    /**
     * Create todo record
     * @param type $user_id
     * @param type $message
     * @param type $priority
     * @return boolean
     */
    public function add_todolist($user_id, $message, $priority)
    {
        return true;
    }

    /**
     * 
     * @param type $to
     * @param type $subject
     * @param string $body
     * @param string $cc
     * @param type $attachment array( 
     *                                  "file_fullname" => <File Full name including path>, 
     *                                  "file_shortname" => <Short name for showing>,
     *                                  "mime_type" => <File type, not mandatory>
     *                          )
     * @return boolean
     */
    function sendEmail($to, $subject, $body, $cc = '', $attachment = array())
    {

        //	Set To address
        if ($this->ci->config->item('send_test_emails_only') === true) {
            $to = $this->ci->config->item('testing_email');

            //	If test emails, no need of Cc
            if (!empty($cc)) {
                $cc = '';
            }
        }

        //	Set From address & name
        $from = $this->ci->config->item('sender_address');
        $from_name = $this->ci->config->item('sender_name');

        //	Set disclaimer
        if ($this->ci->config->item('email_disclaimer') != '') {
            //$body .= '<br/><br/><br/>' . $this->config->item('email_disclaimer');
        }

        if (_SEND_MAILS) {
            $this->ci->load->library('email');
            $this->ci->email->to($to);
            $this->ci->email->from($from, $from_name);

            //	Set CC address
            if (!empty($cc)) {
                $this->ci->email->cc($cc);
            }

            //	Set subject & body
            $this->ci->email->subject($subject);
            $this->ci->email->message($body);

            //	Set Attachments
            if (!empty($attachment)) {
                foreach ($attachment as $att_file) {
                    if ($att_file['mime_type'] != "")
                        $this->ci->email->attach($att_file['file_fullname'], 'attachment', $att_file['file_shortname'], $att_file['mime_type']);
                    else
                        $this->ci->email->attach($att_file['file_fullname'], 'attachment', $att_file['file_shortname']);
                }
            }

            //	Send mail
            $mail_status = $this->ci->email->send(FALSE);

            if (_SMTP_DEBUG_MODE) {
                echo '<br/>' . $this->ci->email->print_debugger() . '<br/>';
                exit;
            }

            $this->ci->email->clear();

            if ($mail_status) {
                $this->logEmails($to, $subject, $body, $from, $from_name, $cc, $attachment, 'Success');
                return true;
            } else {
                $this->logEmails($to, $subject, $body, $from, $from_name, $cc, $attachment, 'Failed');
                return false;
            }
        } else {
            $this->logEmails($to, $subject, $body, $from, $from_name, $cc, $attachment, 'Failed');
            return true;
        }
    }

    /**
     * Function for uploading files
     * @param type $file_upload_field_name
     * @param type $new_file_name
     * @param type $path_to_upload
     * @return type
     */
    function fileUpload($file_upload_field_name, $new_file_name, $path_to_upload, $allowed_types = "_ALLOWED_TYPES")
    {

        $config['upload_path'] = $this->ci->config->item("_UPLOAD_PATH") . $path_to_upload;
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $config['allowed_types'] = $this->ci->config->item($allowed_types);

        $this->ci->load->library('upload', $config);

        $_FILES[$file_upload_field_name]['name'] = $new_file_name . "." . $this->ci->upload->get_extension($_FILES[$file_upload_field_name]['name']);


        if (!$this->ci->upload->do_upload($file_upload_field_name)) {
            return array("STATUS" => "FAILED", "MESSAGE" => "File upload failed." . $this->ci->upload->display_errors());
        } else {
            $file_upload_data = $this->ci->upload->data();
            return array("STATUS" => "SUCCESS", "FILE_NAME" => $file_upload_data['file_name']);
        }
    }



    function sendSMS($sender_id, $message, $receiver_user_id = "", $receiver_ms_user_id = "")
    {
        return true;
    }

    function logEmails($to, $subject, $body, $from, $from_name, $cc, $attachment, $status)
    {
        $insert_data = array(
            'ToMail' => $to,
            'Subject' => $subject,
            'MessageBody' => $body,
            'FromMail' => $from,
            'FromName' => $from_name,
            'CcMail' => $cc,
            'Attachement' => '',
            'CreatedOn' => date('Y-m-d'),
            'Status' => $status
        );
        $mail_id = $this->ci->Base_model->saveData("FailedMails", $insert_data);

        if ($mail_id) {
            if (!empty($attachment)) {
                foreach ($attachment as $att_file) {
                    $sub_arr = array(
                        'Mail_ID' => $mail_id,
                        'FileName' => $att_file['file_fullname'],
                        'ShortName' => $att_file['file_shortname'],
                        'MimeType' => $att_file['mime_type']
                    );
                    $mail_id = $this->ci->Base_model->saveData("FailedMailAttachments", $sub_arr);
                }
            }
        }
    }
    /*
     * to create default photo albums
     */
    function generateDefaultPhotoAlbums($name, $description, $user_id)
    {



        $ev_arrays['f_name']    =   'name';
        $ev_arrays['f_value']   =   $name;
        $ev_arrays['is_arabic'] =   1;
        $main_arr[]             =   $ev_arrays;
        $ev_arrays              =   array();

        $ev_arrays['f_name']    =   'description';
        $ev_arrays['f_value']   =   $description;
        $ev_arrays['is_arabic'] =   1;
        $main_arr[]             =   $ev_arrays;
        $ev_arrays              =   array();

        $ev_arrays['f_name']    =   'status';
        $ev_arrays['f_value']   =   'active';
        $ev_arrays['is_arabic'] =   0;
        $main_arr[]             =   $ev_arrays;
        $ev_arrays              =   array();

        $ev_arrays['f_name']    =   'created_at';
        $ev_arrays['f_value']   =   date('Y-m-d');
        $ev_arrays['is_arabic'] =   0;
        $main_arr[]             =   $ev_arrays;
        $ev_arrays              =   array();

        $ev_arrays['f_name']    =   'created_by';
        $ev_arrays['f_value']   =   $user_id;
        $ev_arrays['is_arabic'] =   0;
        $main_arr[]             =   $ev_arrays;
        $ev_arrays              =   array();

        $ins                    =   $this->ci->Base_model->saveArabicData('albums', $main_arr);
        return $ins;
    }
    /*to rest session
     * @param array
     * @return bool
     */
    function resetSessions($reset_array = array())
    {
        //echo "<pre>";print_r($_SESSION);die;

        if (isset($reset_array['first_name']) && trim($reset_array['first_name']) != '') {
            $this->ci->session->set_userdata('first_name', $reset_array['first_name']);
        }

        if (isset($reset_array['last_name']) && trim($reset_array['last_name']) != '') {
            $this->ci->session->set_userdata('first_name', $reset_array['']);
        }

        //        if(isset($reset_array['']) && trim($reset_array['']) != ''){
        //            $this->ci->session->set_userdata('first_name',$reset_array['']);
        //        }
        //        
        //        if(isset($reset_array['']) && trim($reset_array['']) != ''){
        //            $this->ci->session->set_userdata('first_name',$reset_array['']);
        //        }
        //        
        //        if(isset($reset_array['']) && trim($reset_array['']) != ''){
        //            $this->ci->session->set_userdata('first_name',$reset_array['']);
        //        }
        //        
        //        if(isset($reset_array['']) && trim($reset_array['']) != ''){
        //            $this->ci->session->set_userdata('first_name',$reset_array['']);
        //        }
        //        
        //        if(isset($reset_array['']) && trim($reset_array['']) != ''){
        //            $this->ci->session->set_userdata('first_name',$reset_array['']);
        //        }
        //        
        //        if(isset($reset_array['']) && trim($reset_array['']) != ''){
        //            $this->ci->session->set_userdata('first_name',$reset_array['']);
        //        }


        return true;
    }
}
