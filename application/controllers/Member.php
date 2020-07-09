<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {



        $data['page']       = 'dashboard';
        $data['page_title'] = 'Dashboard';

        $data['action']   = "home";
        $data['js_files'] = array('dashboard.js');
        $this->load->view('layout/layout', $data);
    }

    public function getUserProfile($user_id = '') {


        $data = array();

        if (!isset($user_id) || trim($user_id) == '') {
            $user_id = $this->session->userdata('user_id');
        }

        $this->load->library("DataEasyAccess");
        $data['content'] = $this->dataeasyaccess->getUserFullDetails($user_id);
        $data['files']   = $this->dataeasyaccess->getUploadedFiles($user_id);


        $data['page']       = 'view_profile';
        $data['page_title'] = 'Profile';

        $data['action']   = "home";
        $data['js_files'] = array('view_profile.js');
        $this->load->view('layout/layout', $data);
    }

    public function getEditProfile($user_id = '') {

        $data = array();

        if (!isset($user_id) || trim($user_id) == '') {
            $user_id = $this->session->userdata('user_id');
        }
        $this->load->library("DataEasyAccess");
        $data['content'] = $this->dataeasyaccess->getUserFullDetails($user_id);


        $data['page']       = 'edit_profile';
        $data['page_title'] = 'Edit Profile';

        $data['action']   = "home";
        $data['js_files'] = array('edit_profile.js');
        $this->load->view('layout/layout', $data);
    }

    function updateUser() {

        $user_id = $this->session->userdata('user_id');

        $file_uploaded = $this->upload();
        $this->db->trans_begin();
        if ($file_uploaded && trim($file_uploaded) != '') {
            $album_query = "select id from albums where created_by=" . $user_id;
            $album_arr   = $this->Base_model->query($album_query)->result_array();

            if (empty($album_arr)) {
                $this->db->trans_rollback();
                echo json_encode(array("status" => 0, "message" => 'Failed! Unable to locate profile album .Profile updation failed. Please try again later'));
                die;
            }
            $album_id                      = $album_arr[0]['id'];
            $profile_pic_arr['type']       = 'photo';
            $profile_pic_arr['caption']    = '';
            $profile_pic_arr['link']       = $file_uploaded;
            $profile_pic_arr['album_id']   = $album_id;
            $profile_pic_arr['status']     = 'active';
            $profile_pic_arr['created_at'] = date("Y-m-d H:i:s");
            $profile_pic_arr['created_by'] = $user_id;

            $profile_pic_up = $this->Base_model->saveData('posts', $profile_pic_arr);
            if (!$profile_pic_up) {
                $this->db->trans_rollback();
                echo json_encode(array("status" => 0, "message" => 'Failed! Profile updation failed. Please try again later'));
                die;
            }
        }





        $form_data                       = $this->input->post();
        $user_arr['first_name']          = (isset($form_data['first_name']) && $form_data['first_name'] != '') ? trim($form_data['first_name']) : null;
        $user_arr['last_name']           = (isset($form_data['last_name']) && $form_data['last_name'] != '') ? trim($form_data['last_name']) : null;
        $user_arr['dob']                 = (isset($form_data['dob']) && $form_data['dob'] != '') ? trim($form_data['dob']) : null;
        $user_arr['email']               = (isset($form_data['email']) && $form_data['email'] != '') ? trim($form_data['email']) : null;
        $user_arr['phone_number']        = (isset($form_data['phone_number']) && $form_data['phone_number'] != '') ? trim($form_data['phone_number']) : null;
        $user_arr['religion']            = (isset($form_data['religion']) && $form_data['religion'] != '') ? trim($form_data['religion']) : null;
        $user_arr['relationship_status'] = (isset($form_data['relation_ship_status']) && $form_data['relation_ship_status'] != '') ? trim($form_data['relation_ship_status']) : null;
        $user_arr['current_city']        = (isset($form_data['current_city']) && $form_data['current_city'] != '') ? trim($form_data['current_city']) : null;
        $user_arr['home_town']           = (isset($form_data['home_town']) && $form_data['home_town'] != '') ? trim($form_data['home_town']) : null;
        $user_arr['bio']                 = (isset($form_data['bio']) && $form_data['bio'] != '') ? trim($form_data['bio']) : null;
        $user_arr['about_me']            = (isset($form_data['about_me']) && $form_data['about_me'] != '') ? trim($form_data['about_me']) : null;
        if (isset($profile_pic_up) && trim($profile_pic_up) != '') {
            $user_arr['profile_pic_id'] = $profile_pic_up;
        }

        $up_user = $this->Base_model->updateData('users', $user_arr, array("id" => $user_id));

        if (!$up_user) {
            $this->db->trans_rollback();
            echo json_encode(array("status" => 0, "message" => 'Failed! Profile updation failed. Please try again later'));
            die;
        }

        $this->db->trans_commit();

        echo json_encode(array("status" => 1, "message" => 'Profile updated successfully'));
        die;
    }

    function upload() {

        if (isset($_FILES['file']['name'])) {
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
        return false;
    }

    public function samples() {


        $data = array();



        $data['page']       = 'samples';
        $data['page_title'] = 'Profile';

        $data['action']   = "home";
        $data['js_files'] = array('view_profile.js');
        $this->load->view('layout/layout', $data);
    }

    function addPost() {


        $form_data = $this->input->post();

        $created_at = date("Y-m-d H:i:s");
        $user_id    = $this->session->userdata('user_id');

        $this->db->trans_begin();


        //FILE UPLOAD BEGINS
        if (isset($_FILES['file']['name']) && count($_FILES['file']['name']) > 0) {

            //get album_id
            $selected_album = "";
            if ($selected_album == "") {
                $selected_album = $this->getAlbum($user_id);
            }


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





                        $ev_arrays['f_name']    = 'type';
                        $ev_arrays['f_value']   = 'photo';
                        $ev_arrays['is_arabic'] = 0;
                        $main_arr[]             = $ev_arrays;
                        $ev_arrays              = array();

                        $ev_arrays['f_name']    = 'caption';
                        $ev_arrays['f_value']   = (isset($form_data['caption']) && trim($form_data['caption']) != '') ? $form_data['caption'] : "";
                        $ev_arrays['is_arabic'] = 1;
                        $main_arr[]             = $ev_arrays;
                        $ev_arrays              = array();

                        $ev_arrays['f_name']    = 'link';
                        $ev_arrays['f_value']   = $file_upload['FILE_NAME'];
                        $ev_arrays['is_arabic'] = 0;
                        $main_arr[]             = $ev_arrays;
                        $ev_arrays              = array();

                        $ev_arrays['f_name']    = 'album_id';
                        $ev_arrays['f_value']   = $selected_album;
                        $ev_arrays['is_arabic'] = 0;
                        $main_arr[]             = $ev_arrays;
                        $ev_arrays              = array();


                        $ev_arrays['f_name']    = 'status';
                        $ev_arrays['f_value']   = 'active';
                        $ev_arrays['is_arabic'] = 0;
                        $main_arr[]             = $ev_arrays;
                        $ev_arrays              = array();


                        $ev_arrays['f_name']    = 'created_at';
                        $ev_arrays['f_value']   = $created_at;
                        $ev_arrays['is_arabic'] = 0;
                        $main_arr[]             = $ev_arrays;
                        $ev_arrays              = array();

                        $ev_arrays['f_name']    = 'created_by';
                        $ev_arrays['f_value']   = $user_id;
                        $ev_arrays['is_arabic'] = 0;
                        $main_arr[]             = $ev_arrays;
                        $ev_arrays              = array();



                        $ins = $this->Base_model->saveArabicData('posts', $main_arr);
                        if (!$ins) {
                            $this->db->trans_rollback();
                            echo json_encode(array("status" => 0, "message" => 'Failed! some thing gone wrong. Please try again later'));
                            die;
                        }
                    }
                }
            }
        } else {
            //text only
            $ev_arrays['f_name']    = 'type';
            $ev_arrays['f_value']   = 'other';
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();

            $ev_arrays['f_name']    = 'caption';
            $ev_arrays['f_value']   = $form_data['caption'];
            $ev_arrays['is_arabic'] = 1;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();

            $ev_arrays['f_name']    = 'link';
            $ev_arrays['f_value']   = null;
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();

            $ev_arrays['f_name']    = 'album_id';
            $ev_arrays['f_value']   = null;
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();


            $ev_arrays['f_name']    = 'status';
            $ev_arrays['f_value']   = 'active';
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();


            $ev_arrays['f_name']    = 'created_at';
            $ev_arrays['f_value']   = $created_at;
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();

            $ev_arrays['f_name']    = 'created_by';
            $ev_arrays['f_value']   = $user_id;
            $ev_arrays['is_arabic'] = 0;
            $main_arr[]             = $ev_arrays;
            $ev_arrays              = array();



            $ins = $this->Base_model->saveArabicData('posts', $main_arr);

            if (!$ins) {
                $this->db->trans_rollback();
                echo json_encode(array("status" => 0, "message" => 'Failed! some thing gone wrong. Please try again later'));
                die;
            }
        }


//file upload ends

        $this->db->trans_commit();
        echo json_encode(array("status" => 1, "message" => "Your post shared successfully!..."));

        die;
    }

    function sendFriendRequest() {
        $user_id     = $this->session->userdata('user_id');
        $target_user = $this->input->post("target_user");


        $array['requester_id'] = $user_id;
        $array['acceptor_id']  = $target_user;
        $array['status']       = "pending";

        $array['created_at'] = date("Y-m-d H:i:s");
        ;

        $this->db->trans_begin();
        $requested = $this->Base_model->saveData("friends", $array);
        if ($requested) {
            $this->db->trans_commit();
            echo json_encode(array("status" => 1, "message" => "Request sented successfully"));
            die;
        }
        $this->db->trans_rollback();
        echo json_encode(array("status" => 0, "message" => 'Failed! Unable to add friend. Please try again later'));
        die;
    }

    /*
     * accept or reject friend request
     */

    function respondFriendRequest() {
        $user_id    = $this->session->userdata('user_id');
        $request_id = $this->input->post("request_id");
        $response   = $this->input->post("response");

        if ($response == 'accepted') {
            $array['status'] = "accepted";
        } else {
            $array['status'] = "rejected";
        }

        $array['accepted_at'] = date("Y-m-d H:i:s");

        $condition = array("id" => $request_id);
        $this->db->trans_begin();
        $requested = $this->Base_model->updateData("friends", $array, $condition);
        if ($requested) {
            $this->db->trans_commit();
            echo json_encode(array("status" => 1, "message" => "Request " . $response . " successfully"));
            die;
        }
        $this->db->trans_rollback();
        echo json_encode(array("status" => 0, "message" => 'Failed! Unable to ' . $response . ' request. Please try again later'));
        die;
    }

    function getFriendRequests() {
        $user_id    = $this->session->userdata('user_id');
        $query      = "select distinct u.id,u.username,u.first_name,u.last_name,po.link,f.id  as request_id
                        from friends f 
                        join users u on u.id=f.requester_id
                        join posts po on po.id=u.profile_pic_id
                        where f.acceptor_id=" . $user_id . " and f.status='pending'";
        $users_data = $this->Base_model->query($query)->result_array();
        return $users_data;
    }

    function unFriend() {
        $user_id    = $this->session->userdata('user_id');
        $request_id = $this->input->post("request_id");
        $q          = "update friends set status='removed',removed_at='" . date("Y-m-d H:i:s") . "' where (acceptor_id=" . $user_id . " and requester_id=" . $request_id . ") or (acceptor_id=" . $request_id . " and requester_id=" . $user_id . ")";
        $update     = $this->Base_model->query($q);
        if ($update) {
            echo json_encode(array("status" => 1, "message" => 'Success! User removed from friends'));
            die;
        } else {
            echo json_encode(array("status" => 0, "message" => 'Failed! Unable to unfriend user. Please try again later'));
            die;
        }
    }

    function removeSentFriendRequest() {
        $user_id    = $this->session->userdata('user_id');
        $request_id = $this->input->post("request_id");
        $q          = "update friends set status='removed',removed_at='" . date("Y-m-d H:i:s") . "' where (acceptor_id=" . $user_id . " and requester_id=" . $request_id . ") or (acceptor_id=" . $request_id . " and requester_id=" . $user_id . ") and status='pending'";
        $update     = $this->Base_model->query($q);
        if ($update) {
            echo json_encode(array("status" => 1, "message" => 'Success! Request removed successfully'));
            die;
        } else {
            echo json_encode(array("status" => 0, "message" => 'Failed! Unable to unfriend user. Please try again later'));
            die;
        }
    }

    /*
     * function to get albumid
     */

    function getAlbum($user_id) {
        $this->load->library("DataEasyAccess");
        $albums = $this->dataeasyaccess->getUserAlbums($user_id);
        if (!empty($albums)) {
            if ($albums[0]['name'] != 'Profile Photos') {
                return $albums[0]['id'];
            } else {
                if (isset($albums[1]['name'])) {
                    return $albums[1]['id'];
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array("status" => 0, "message" => 'Failed! Unable to identify album to upload. if you have deleted all albums please create a new one and try again'));
                    die;
                }
            }
        } else {
            //generating default albums begins
            $albums_list = $this->config->item("default_albums");
            foreach ($albums_list as $key => $val) {
                $this->functions->generateDefaultPhotoAlbums($val['name'], $val['description'], $ins_id);
            }
            $this->getAlbum($user_id);

            //album generation ends
        }
    }

    function like() {
        $user_id       = $this->session->userdata('user_id');
        $post_id       = $this->input->post("post_id");
        $q             = "select pr.id from post_reactions pr where pr.post_id=" . $post_id . " and pr.created_by=" . $user_id . " and pr.status='active'";
        $existing_like = $this->Base_model->query($q)->result_array();
        if (!empty($existing_like)) {
            //unlike
            $array['status'] = 'deleted';
            $unlike          = $this->Base_model->updateData('post_reactions', $array, array("id" => $existing_like[0]['id']));

            if (!$unlike) {

                echo json_encode(array("status" => 0, "message" => 'Failed! unliking failed. Please try again later'));
                die;
            }
            $action = 'unlike';
        } else {
            //like
            $array['type']       = 'like';
            $array['post_id']    = $post_id;
            $array['coment_id']  = null;
            $array['status']     = 'active';
            $array['created_at'] = date("Y-m-d H:i:s");
            $array['created_by'] = $user_id;
            $like                = $this->Base_model->saveData('post_reactions', $array);
            if (!$like) {

                echo json_encode(array("status" => 0, "message" => 'Failed! liking failed. Please try again later'));
                die;
            }
            $action = 'like';
        }
        echo json_encode(array("status" => 1, "message" => 'success! action performed successfully', 'action' => $action));
        die;
    }

    function generateColage() {
        $url1 = base_url('uploads/photos/Doc_2019_06_27_10_36010_.jpg');
        $url2 = base_url('uploads/photos/front_20190609225236_.jpg');
        $new  = imagecreatetruecolor(400, 600); // Create our canvas
// Our static positions...
        $pos  = array(array(0, 0), array(200, 0), array(0, 200), array(200, 200), array(0, 400), array(200, 400));

        $height = 200;
        $width  = 200;

        $white = imagecolorallocate($new, 255, 255, 255); // Gives us a white background
// Now, define the source images
        $src[] = $url1;
        $src[] = $url2;
        $src[] = $url1;
        $src[] = $url2;
        $src[] = $url1;
        $src[] = $url2;
        // $src   = array('images/image1.gif', 'images/image2.gif', 'images/image3.gif', 'images/image4.gif', 'images/image5.gif', 'images/image6.gif');

        foreach ($src as $key => $image) {
            $old = imagecreatefromjpeg($image);
            imagecopymerge($new, $old, $pos[$key][0], $pos[$key][1], 0, 0, $width, $height, 100);
        }

        header('Content-Type: image/gif');
        ImageGIF($new);
    }

}
