<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($post_id) {

        //$this->functions->resetSessions();
        $user_id = $this->session->userdata("user_id");
        $this->load->library("DataEasyAccess");

        $data['page']       = 'posts';
        $data['page_title'] = 'Dashboard';

        $data['latest_posts'] = $this->fetchPost($post_id);
        $data['latest_cmnts'] = $this->getPostComments($post_id);

        $data['action']   = "home";
        $data['js_files'] = array('posts.js');
        $this->load->view('layout/layout', $data);
    }

    function fetchPost($id) {
        $user_id           = $this->session->userdata("user_id");
        $query_latest_post = "select  p.*,u.first_name,u.last_name,propic.link as propic,
                                (select 1 as priority from posts tp where p.created_by=tp.created_by and p.created_at=tp.created_at and p.id!=tp.id) as priority,
                                (select count(pr.id) from post_reactions pr where pr.post_id=p.id and pr.status='active' ) as likes,
                                (select count(pc.id) from post_comments pc where pc.post_id=p.id and pc.comment_id is null and pc.status='active') as comments,
                                STUFF((select distinct ',' + pr.type from post_reactions pr where pr.post_id=p.id and pr.status='active' FOR XML PATH ('')), 1, 1, '')  as reactions,
                                (select top 1 pr.id from post_reactions pr where pr.post_id=p.id and pr.created_by=" . $user_id . " and pr.status='active') as liked
                                from posts p
                                join users u on u.id=p.created_by
                                left join posts propic on propic.id=u.profile_pic_id
                                where p.id=" . $id . "
                                order by p.created_at desc,priority asc";
        $result            = $this->Base_model->query($query_latest_post)->result_array();
        if (empty($result)) {
            $result = array();
        }
        return $result;
        // echo "<pre>";print_r($result);die;
    }

    function addPostComment() {
        $user_id                    = $this->session->userdata("user_id");
        $post_id                    = $this->input->post("post_id");
        $comment                    = $this->input->post("comment");
        $array['commented_against'] = 'post';
        $array['comment']           = $comment;
        $array['post_id']           = $post_id;
        $array['status']            = 'active';
        $array['created_at']        = date("Y-m-d H:i:s");
        $array['created_by']        = $user_id;
        $result                     = $this->Base_model->saveData("post_comments", $array);
        if (!$result) {

            echo json_encode(array("status" => 0, "message" => 'Failed! commenting failed. Please try again later'));
            die;
        }
        echo json_encode(array("status" => 1, "message" => 'success! commented successfully'));
        die;
    }

    function getPostComments($post_id) {
        $q      =   "select top 5 pc.*,u.first_name,u.last_name,propic.link as propic
                    from post_comments pc
                    join users u on u.id=pc.created_by
                    left join posts propic on propic.id=u.profile_pic_id
                    where pc.post_id=".$post_id." and pc.status='active' and pc.commented_against='post'
                    order by pc.created_at desc";
        $result = $this->Base_model->query($q)->result_array();
        return $result;
    }

}
