<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Base_model extends CI_Model {
    
    /**
     * Inserting data into a specified table
     * @param string $table Table name
     * @param array $data Array of data, index must be same as table fields
     * @return type Last inserted id
     */
    public function saveData($table, $data) {
        return $this->db->insert($table, $data)?$this->db->insert_id():0;
    }
	
	
	  /**
     * Save table data with arabic values
     * @param type $table
     * @param array $data array('f_name' => <>, 'f_value' => <>, 'is_arabic' => 1)
     * @return type Last inserted id
     */
    public function saveArabicData($table, $data) {
		
        foreach ($data as $value) {
            if($value['is_arabic'])
                $this->db->set($value['f_name'], "N'".$value['f_value']."'",FALSE);
            else 
                $this->db->set($value['f_name'], $value['f_value']);
        }
        return $this->db->insert($table)?$this->db->insert_id():0;
    }
    
	
	  /**
     * Update table data with arabic values
     * @param type $table
     * @param array $data array('f_name' => <>, 'f_value' => <>, 'is_arabic' => 1)
     * @return type true/false
     */
    public function updateArabicData($table, $data, $condition) {
		
        foreach ($data as $value) {
            if($value['is_arabic'])
                $this->db->set($value['f_name'], "N'".$value['f_value']."'",FALSE);
            else 
                $this->db->set($value['f_name'], $value['f_value']);
        }
		$this->db->where($condition);
         $report = $this->db->update($table);
      
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Update datas in a specified table with a specific condition
     * @param string $table Table name
     * @param array $data Array of data to be update, index must be same as table fields
     * @param array $condition Update condition
     * @return bool true/false 
     */
    public function updateData($table, $data, $condition) {
        $report = $this->db->update($table, $data, $condition);
        
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Select specific datas from table with join and conditions
     * @param string $select fields to be select
     * @param string $table table name
     * @param array $join join tables with condition. Eg:- array('table' => '<Table name>', 'condition' => '<Join condition>', 'type' =>'<Type of join Left/Right: not mandatory>')
     * @param array $condition data selection condition, not mandatory
     * @param string $group group by field, not mandatory
     * @param string $order order by field, not mandatory
     * @return array result as array
     */
	 function getData($select, $table, $join=NULL, $condition=NULL, $group=NULL, $order=NULL, $limit=NULL, $like_condition=NULL, $distinct=NULL, $or_like=NULL) {
        $this->db->select($select);
        $this->db->from($table);
        if($join != NULL && count($join) > 0) {
            foreach ($join as $join_data) {
                if($join_data['type'] != '')
                    $this->db->join($join_data['table'], $join_data['condition'], $join_data['type']);
                else
                    $this->db->join($join_data['table'], $join_data['condition']);
            }
        }
        if($condition != "") $this->db->where($condition);
		if($like_condition != "") $this->db->like($like_condition);
        if($group != "") $this->db->group_by($group);
        if($order != "") $this->db->order_by($order);
        if($limit != "") $this->db->limit($limit);
        if($distinct!=NULL && $distinct==TRUE) $this->db->distinct();
        if($or_like != NULL && is_array($or_like)) {
            foreach ($or_like as $key => $or_val) {
                foreach ($or_like[$key] as $or_val) {
                    $this->db->or_like($key, $or_val);
                }
            }
        }
        $query = $this->db->get(); 
        return $query->result_array();
    }
    
    /**
     * Delete datas from a table with specific condition
     * @param string $table Table name
     * @param array $condition Condition for deletion
     * @return boolean true/false
     */
    function deleteData($table, $condition) {
        $data = array('Status' => 'deleted');
        $report = $this->db->update($table, $data, $condition);
        
        if ($report !== 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Run sql queries
     * @param string $sql SQL query
     * @return type
     */
    function query($sql){
        return $this->db->query($sql);
    }
	
	/**
     * Inserting data into a specified table
     * @param string $table Table name
     * @param array $data Array of data, index must be same as table fields
     * @return type Last inserted id
	 * author:joby
     */
    public function saveBatchData($table, $data) {
        return $this->db->insert_batch($table, $data)?1:0;
    }
}