<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ChatLibrary {

    public $ci;

    public function __construct() {
        $this->ci = &get_instance();
    }

    function getMessageHistory($reciever_id,$user_id){
        $query  =   "select top 10 * from messages m
                    where (m.sender_id=".$reciever_id." and m.reciever_id=".$user_id.") or (m.sender_id=".$user_id." and m.reciever_id=".$reciever_id.")
                    order by m.sent_time desc";
        $result =   $this->ci->Base_model->query($query)->result_array();
        return $result;
    }
    
    function getGroupMessageHistory($group_id,$user_id){
        $query  =   "select top 10 * from messages m
                    join msg_groups mg on m.is_private=0 and mg.id=m.reciever_id
                    join msg_participants mp on mp.group_id=mg.id and mp.user_id=".$user_id." and m.sent_time <  case when mp.status='deleted' then mp.message_paused_on  else CURRENT_TIMESTAMP end
                    where mg.id=".$group_id." and m.sent_time>mp.added_on and m.sent_time > mp.msgcleared_on
                    order by m.sent_time desc";
        $result =   $this->ci->Base_model->query($query)->result_array();
        return $result;
    }
    function getChatHistory($user_id){
        $query_ids  =   "select distinct case when m.sender_id=".$user_id." and m.is_private=1 then concat(m.reciever_id,'') when  m.is_private=0 then concat(m.reciever_id,'#') else concat(m.sender_id,'') end as ids
                    from messages m
                    left join msg_groups mg on m.is_private=0 and mg.id=m.reciever_id
                    left join msg_participants mp on mp.group_id=mg.id and mp.user_id=".$user_id." 
                    where m.sender_id=".$user_id." or (m.reciever_id=".$user_id." or mp.user_id=".$user_id.")
                    ";
        $id_list =   $this->ci->Base_model->query($query_ids)->result_array();
        
        $ids    =   array_column($id_list,"ids");
        //now we have all chats ids
        
        $query="select mg.id,mg.name as chatname,mg.icon as link,0 as is_private
                    ,(select top 1 m.message from messages m where m.reciever_id=mg.id and m.is_private=0 and m.sent_time <  case when mp.status='deleted' then mp.message_paused_on  else CURRENT_TIMESTAMP end and m.sent_time>mp.added_on and m.sent_time > mp.msgcleared_on order by m.sent_time desc) as last_msg
                    ,(select top 1 m.sent_time from messages m where m.reciever_id=mg.id and m.is_private=0 and m.sent_time <  case when mp.status='deleted' then mp.message_paused_on  else CURRENT_TIMESTAMP end and m.sent_time>mp.added_on and m.sent_time > mp.msgcleared_on order by m.sent_time desc) as last_msg_date 
                    ,(select sum(countanalyze.unread) as unread
                    from
                    (select case when sh.id is null then 1 else 0 end as unread
                    from msg_groups mgt
                    join msg_participants mpt on mpt.group_id=mgt.id 
                    join messages m on m.reciever_id=mgt.id and m.sender_id!=mp.user_id and m.sent_time <  case when mpt.status='deleted' then mpt.message_paused_on  else CURRENT_TIMESTAMP end and m.sent_time>mpt.added_on and m.sent_time > mpt.msgcleared_on
                    left join msg_seen_history sh on sh.msg_id=m.id and sh.group_id=mgt.id and sh.user_id=mpt.user_id
                    where mgt.id=1 and mpt.user_id=mp.user_id
                    )countanalyze) as unread
                    from msg_groups mg
                    join msg_participants mp on mp.group_id=mg.id 
                    where concat(mg.id,'#') in ('" . implode("', '", $ids) . "') and mp.user_id=".$user_id."

                    union 

                    select u.id,concat(u.first_name,' ',u.last_name) as chatname,p.link,1 as is_private,(select top 1 m.message from messages m where (m.reciever_id=u.id or m.sender_id=u.id) and m.is_private=1 order by m.sent_time desc) as last_msg
                    ,(select top 1 m.sent_time from messages m where (m.reciever_id=u.id or m.sender_id=u.id) and m.is_private=1 order by m.sent_time desc) as last_msg_date ,
                    (select sum(countanalyze.unread) as unread from (
                        select case when read_time is null then 1 else 0 end as unread from messages
                        where (sender_id=u.id) and is_private=1)countanalyze
                    ) as unread

                    from users u 
                    left join posts p on p.id=u.profile_pic_id
                    where concat(u.id,'') in ('" . implode("', '", $ids) . "')";
        
        $result =   $this->ci->Base_model->query($query)->result_array();
        //echo "<pre>";print_r($result);die;
        return $result;
    }
    function getGroupDetails($group_id){
        $query  =   "select * from msg_groups where id=".$group_id;
        $result =   $this->ci->Base_model->query($query)->result_array();
        return $result;
    }
    function getGroupMembers($group_id){
        $q                         = "select distinct u.id,u.username,u.first_name,u.last_name,po.link from msg_groups mg
            join msg_participants mp on mp.group_id=mg.id and mp.status='active'
            join users u on u.id=mp.user_id and u.status='active'
            join posts po on po.id=u.profile_pic_id
            where mg.id=" . $group_id;
        
        $data = $this->ci->Base_model->query($q)->result_array();
        return $data;
    }

}
