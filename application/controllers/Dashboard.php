<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        //$this->functions->resetSessions();
        $user_id                     = $this->session->userdata("user_id");
        $this->load->library("DataEasyAccess");
        $data['people_you_may_know'] = $this->dataeasyaccess->PeopleYouMayKnow($user_id);
        $data['friend_requests']     = $this->dataeasyaccess->getFriendRequests($user_id);

        $data['latest_posts'] = $this->fetchLatestPost();
        $data['page']         = 'dashboard';
        $data['page_title']   = 'Dashboard';

        $data['action']   = "home";
        $data['js_files'] = array('dashboard.js');
        $this->load->view('layout/layout', $data);
    }

    function fetchLatestPost() {
        $user_id           = $this->session->userdata("user_id");
        $query_latest_post = "select top 5 p.*,u.id as uid,u.first_name,u.last_name,propic.link as propic,
                                (select 1 as priority from posts tp where p.created_by=tp.created_by and p.created_at=tp.created_at and p.id!=tp.id) as priority,
                                (select count(pr.id) from post_reactions pr where pr.post_id=p.id and pr.status='active' ) as likes,
                                (select count(pc.id) from post_comments pc where pc.post_id=p.id and pc.comment_id is null and pc.status='active') as comments,
                                STUFF((select distinct ',' + pr.type from post_reactions pr where pr.post_id=p.id and pr.status='active' FOR XML PATH ('')), 1, 1, '')  as reactions,
                                (select top 1 pr.id from post_reactions pr where pr.post_id=p.id and pr.created_by=" . $user_id . " and pr.status='active') as liked
                                from posts p
                                join users u on u.id=p.created_by
                                left join posts propic on propic.id=u.profile_pic_id
                                order by p.created_at desc,priority asc";
        $result            = $this->Base_model->query($query_latest_post)->result_array();
        if (empty($result)) {
            $result = array();
        }
        return $result;
        // echo "<pre>";print_r($result);die;
    }

}
