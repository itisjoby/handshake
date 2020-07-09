<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class DataEasyAccess {

    public $ci;

    public function __construct() {
        $this->ci = &get_instance();
    }

    function getUserFullDetails($user_id) {

        $content       = array();
        $details_query = "select distinct u.id,u.username,u.first_name,u.last_name,u.dob,u.email,u.additional_email1,u.additional_email2,u.additional_email3,u.phone_number,
                                            u.additonal_phone_number1,u.additional_phone_number2,u.additional_phone_number3,u.religion,rs.name as relationship_status,
                                            u.current_city,u.home_town,u.bio,u.about_me,edu.institution_name,edu.course_name,edu.id as edu_id,edu.date_from,edu.date_to,skills.id as skill_id,
                                            skills.skill_name,wrk.id as wrk_id,wrk.company_name,wrk.position,wrk.date_from as wrk_from,wrk.date_to as wrk_to,fam.id as family_id,
                                            concat(fm.first_name,' ',fm.last_name) as family_mem_name,fam.name as fam_relation,po.link,cpo.link as coverpic
                                            from users u
                                            left join education_details edu on edu.user_id=u.id and edu.status='active'
                                            left join relationship_status rs on rs.id=u.relationship_status and rs.status='active'
                                            left join work_details wrk on wrk.user_id=u.id and wrk.status='active'
                                            left join professional_skills skills on skills.created_by=u.id and skills.status='active'
                                            left join family fam on fam.created_by=u.id and fam.status='active'
                                            left join users fm on fm.id=fam.user_to and u.status='active'
                                            left join posts po on po.id=u.profile_pic_id and po.status='active' and po.type='photo'
                                            left join posts cpo on cpo.id=u.cover_pic_id and cpo.status='active' and cpo.type='photo'
                                            where u.id=" . $user_id;
        $result        = $this->ci->Base_model->query($details_query)->result_array();

        if (!empty($result)) {
            $basic_temp_details['id']                       = $result[0]['id'];
            $basic_temp_details['username']                 = $result[0]['username'];
            $basic_temp_details['first_name']               = $result[0]['first_name'];
            $basic_temp_details['last_name']                = $result[0]['last_name'];
            $basic_temp_details['dob']                      = $result[0]['dob'];
            $basic_temp_details['email']                    = $result[0]['email'];
            $basic_temp_details['additional_email1']        = $result[0]['additional_email1'];
            $basic_temp_details['additional_email2']        = $result[0]['additional_email2'];
            $basic_temp_details['additional_email3']        = $result[0]['additional_email3'];
            $basic_temp_details['phone_number']             = $result[0]['phone_number'];
            $basic_temp_details['additonal_phone_number1']  = $result[0]['additonal_phone_number1'];
            $basic_temp_details['additional_phone_number2'] = $result[0]['additional_phone_number2'];
            $basic_temp_details['additional_phone_number3'] = $result[0]['additional_phone_number3'];
            $basic_temp_details['religion']                 = $result[0]['religion'];
            $basic_temp_details['relationship_status']      = $result[0]['relationship_status'];
            $basic_temp_details['current_city']             = $result[0]['current_city'];
            $basic_temp_details['home_town']                = $result[0]['home_town'];
            $basic_temp_details['bio']                      = $result[0]['bio'];
            $basic_temp_details['about_me']                 = $result[0]['about_me'];
            $basic_temp_details['pro_pic_url']              = $result[0]['link'];
            $basic_temp_details['coverpic_url']             = $result[0]['coverpic'];
            $basic_details []                               = $basic_temp_details;


            foreach ($result as $key => $val) {
                //isolating family details from result data
                $family_temp_details['family_id']       = $val['family_id'];
                $family_temp_details['family_mem_name'] = $val['family_mem_name'];
                $family_temp_details['fam_relation']    = $val['fam_relation'];
                $family_details[$val['family_id']]      = $family_temp_details;

                //isolating education details from result data
                $edu_temp_details['edu_id']           = $val['edu_id'];
                $edu_temp_details['institution_name'] = $val['institution_name'];
                $edu_temp_details['course_name']      = $val['course_name'];
                $edu_temp_details['date_from']        = $val['date_from'];
                $edu_temp_details['date_to']          = $val['date_to'];
                $edu_details[$val['edu_id']]          = $edu_temp_details;

                //isolating work details
                $work_temp_details['wrk_id']       = $val['wrk_id'];
                $work_temp_details['company_name'] = $val['company_name'];
                $work_temp_details['position']     = $val['position'];
                $work_temp_details['wrk_from']     = $val['wrk_from'];
                $work_temp_details['wrk_to']       = $val['wrk_to'];
                $work_details[$val['wrk_id']]      = $work_temp_details;

                //isolating skills
                $skills_temp_details['skill_id']   = $val['skill_id'];
                $skills_temp_details['skill_name'] = $val['skill_name'];
                $skill_details[$val['skill_id']]   = $skills_temp_details;
            }
            $content['basic_details']  = $basic_details;
            $content['work_details']   = $work_details;
            $content['skills_details'] = $skill_details;
            $content['edu_details']    = $edu_details;
        }
        return $content;
    }

    function PeopleYouMayKnow($user_id) {
        /*
         * =>people who in same country,speak same language ,from same province and have a common age range are filtered first
         * 
         * then we find the probability of knowledge
         * =>like places they share together,school,work etc...
         * =>people tend to check profile of people they know so if the count of visits,
         *  go mysteriously high there is a high % this 2 know each other.
         * =>if we mine the frequent locations this people roam or login. where together we could find people he me met
         * 
         * 
         * we find [tom's] friends.[charles and ruben].if they both know [abella] then [tom] may also know abella
         * 
         * we merge these results to find  best matching profiles.and show it.
         * 
         */
        $matching_results = $this->GetMatchingProfiles($user_id);
        if (!empty($matching_results)) {
            $user_details = $this->getBasicData($matching_results);
            if (!empty($user_details)) {
                return $user_details;
            }
        }
        return false;
    }

    function GetMatchingProfiles($user_id) {
        $matching_ids = [];
        $users_query  = "select distinct u.dob,u.country,u.province,u.current_city,u.home_town,ed.institution_name,wd.company_name,l.name as language
                            from users u 
                            left join education_details ed on ed.user_id=u.id and ed.status='active'
                            left join work_details wd on wd.user_id=u.id and wd.status='active'
                            left join languages l on l.created_by=u.id  and l.status='active'
                            where u.id=" . $user_id;
        $users_data   = $this->ci->Base_model->query($users_query)->result_array();


        if (!empty($users_data) && is_array($users_data) && count($users_data) > 0) {

            $top_cover    = "select top 10 final.id,(final.institution_priority+final.town_priority+final.work_priority+final.city_priority) as priority
            from (";
            $outer_select = " select list.id";
            $select       = " from( select distinct u.id ";
            $join         = " from users u left join friends f on ((f.requester_id=u.id and f.acceptor_id=" . $user_id . ") or (f.requester_id=" . $user_id . " and f.acceptor_id=u.id)) and f.status!='removed'";
            $where        = " where ";





            $dob              = $users_data[0]['dob'];
            $country          = $users_data[0]['country'];
            $province         = $users_data[0]['province'];
            $current_city     = $users_data[0]['current_city'];
            $home_town        = $users_data[0]['home_town'];
            $institution_name = array_values(array_unique(array_column($users_data, "institution_name")));
            $company_name     = array_values(array_unique(array_column($users_data, "company_name")));
            $language         = array_values(array_unique(array_column($users_data, "language")));

            if ($dob != '') {
                $min_date = date('Y-m-d', strtotime('-15 years', strtotime($dob)));
                $max_date = date('Y-m-d', strtotime('+15 years', strtotime($dob)));

                $where .= " cast(u.dob as date)>'" . $min_date . "' and cast(u.dob as date)<'" . $max_date . "' and ";
            }

            if ($country != '') {
                $where .= " u.country='" . $country . "' and ";
            }

            if ($province != '') {
                $where .= " u.province='" . $province . "' and ";
            }
            if ($home_town != '') {
                $outer_select .= ",max(list.town_priority) as town_priority";
                $select       .= ",case when u.home_town='" . $home_town . "' then 1 else 0 end as town_priority";
            } else {
                $outer_select .= ",0 as town_priority";
            }
            if ($current_city != '') {
                $outer_select .= ",max(list.city_priority) as city_priority";
                $select       .= ",case when u.current_city='" . $current_city . "' then 1 else 0 end as city_priority";
            } else {
                $outer_select .= ",0 as city_priority";
            }

            if (!empty($institution_name)) {
                $outer_select .= ",max(list.institution_priority) as institution_priority";
                $select       .= ",case when ed.institution_name in ('" . implode("', '", $institution_name) . "') then 1 else 0 end as institution_priority";
                $join         .= " left join education_details ed on ed.user_id=u.id  ";
            } else {
                $outer_select .= ",0 as institution_priority";
            }

            if (!empty($company_name)) {
                $outer_select .= ",max(list.work_priority) as work_priority";
                $select       .= ",case when wd.company_name in ('" . implode("', '", $company_name) . "') then 1 else 0 end as work_priority";
                $join         .= " left join work_details wd on wd.user_id=u.id  ";
            } else {
                $outer_select .= ",0 as work_priority";
            }
            if (!empty($language) && (isset($language[0])) && $language[0] != '') {
                $where .= " l.name in ('" . implode("', '", $language) . "') and ";
                $join  .= " left join languages l on l.created_by=u.id  ";
            }
            $where       .= " f.id is null and  u.id!=" . $user_id;
            $end_wrapper = " ) list group by list.id )final order by priority desc";
            $query       = $top_cover . $outer_select . $select . $join . $where . $end_wrapper;
        }
        $matching_data = $this->ci->Base_model->query($query)->result_array();
        if (!empty($matching_data)) {
            $matching_ids = array_values(array_unique(array_column($matching_data, "id")));
        }
        return $matching_ids;
    }

    function getBasicData($user_id) {
        if (is_array($user_id)) {
            $users_query = "select distinct u.id,u.username,u.first_name,u.last_name,po.link
                            from users u 
                            left join posts po on po.id=u.profile_pic_id and po.status='active' and po.type='photo'
                            where u.id in ('" . implode("', '", $user_id) . "')";
        } else {
            $users_query = "select distinct u.id,u.username,u.first_name,u.last_name,po.link
                            from users u 
                            left join posts po on po.id=u.profile_pic_id and po.status='active' and po.type='photo'
                            where u.id=" . $user_id;
        }
        $users_data = $this->ci->Base_model->query($users_query)->result_array();
        return $users_data;
    }

    function getFriendRequests($user_id) {

        $query      = "select distinct u.id,u.username,u.first_name,u.last_name,po.link,f.id  as request_id
                        from friends f 
                        join users u on u.id=f.requester_id
                        join posts po on po.id=u.profile_pic_id
                        where f.acceptor_id=" . $user_id . " and f.status='pending'";
        $users_data = $this->ci->Base_model->query($query)->result_array();
        return $users_data;
    }

    function getMyFriendList($user_id) {
        $query      = "select distinct u.id,u.username,u.first_name,u.last_name,po.link  from(
                            select case when f.acceptor_id=" . $user_id . " then  f.requester_id else f.acceptor_id end as id
                            from users u
                            join friends f on (f.acceptor_id=u.id or f.requester_id=u.id) and f.status='accepted'
                            where u.id=" . $user_id . "
                            )friend
                            join users u on u.id=friend.id and u.status='active'
                            join posts po on po.id=u.profile_pic_id";
        $users_data = $this->ci->Base_model->query($query)->result_array();
        return $users_data;
    }

    function getPendingFriendList($user_id) {
        $query      = "select distinct u.id,u.username,u.first_name,u.last_name,po.link  from(
                            select case when f.acceptor_id=" . $user_id . " then  f.requester_id else f.acceptor_id end as id
                            from users u
                            join friends f on (f.acceptor_id=u.id or f.requester_id=u.id) and f.status='pending'
                            where u.id=" . $user_id . "
                            )friend
                            join users u on u.id=friend.id and u.status='active'
                            join posts po on po.id=u.profile_pic_id";
        $users_data = $this->ci->Base_model->query($query)->result_array();
        return $users_data;
    }

    function getUserAlbums($user_id) {
        $query      = "SELECT a.*
                        FROM USERS u
                        left join albums a on a.created_by=u.id and a.status='active'
                        where u.id=" . $user_id;
        $users_data = $this->ci->Base_model->query($query)->result_array();
        return $users_data;
    }

    function getUploadedFiles($user_id) {
        $query      = "select u.id,p.id,p.link,p.caption,al.name,al.description
                        from users u
                        join posts p on p.created_by=u.id and p.type='photo' and p.link!='' and p.status='active'
                        join albums al on al.id=p.album_id
                        where u.id=" . $user_id;
        $users_data = $this->ci->Base_model->query($query)->result_array();
        return $users_data;
    }

}
