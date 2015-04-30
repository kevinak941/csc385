<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * EPTA
 *
 * PHP version 5.3
 *
 * @category    Models
 * @package     EPTA
 * @author      Kevin Kern <kevinak941@gmail.com>
 * @copyright   2014-2015 Kevin Kern all rights reserved
 * @version     1.0
 * @description Base Model that provides simple CRUD methods to all children
 */

class KAK_Model extends CI_Model {
	private $_primary_table = '';
	protected $_fields = array();
	private $_required_fields = array();
	private $_default_values = array();
	
	/**
	 * Constructor
	 * Sets primary table, fields, required fields, and default values
	 */
	public function __construct($table, $fields, $required, $default) {
		parent::__construct(); 
		//Set model variables
		$this->_primary_table = $table;
		$this->_fields = $fields;
		$this->_required_fields = $required;
		if($default === NULL)
			$this->_default_values = array();
		else
			$this->_default_values = $default;
	}
	
	/**
	 * Inserts row into database
	 * @param ($fields) Array of fields to set and their values
	 */
	public function insert($fields = array()) {
		if( ! $this->_required($this->_required_fields, $fields)) return FALSE;
		
		$fields = $this->_default($this->_default_values, $fields);
		
		foreach($this->_fields as $check) {
			if( isset($fields[$check])) {
				if($fields[$check] == 'NOW()') {
					$this->db->set($check, $fields[$check], FALSE);
				} else if($fields[$check] == 'NULL') {
					$this->db->set($check, NULL);
				} else {
					$this->db->set($check, $fields[$check]);
				}
            }
		}
		$this->db->insert($this->_primary_table);
		return $this->db->insert_id();
	}
	
	/**
	 * Deletes rows from the database
	 * @param ($options) Array of fields to use as WHERE in statement
	 */
	public function delete($options = array()) 
	{
		foreach($this->_fields as $check) {
			if( isset($options[$check]))
				$this->db->where($check, $options[$check]);
		}
		$this->db->delete($this->_primary_table);
	}
	
	/**
	 * Updates a row in the database
	 * @param ($fields) The fields and values of what to update
	 * @param ($where) Used for the where statement to determine what needs updating
	 */
	public function update($fields = array(), $where = array()) 
	{
		//if( ! $this->_required($this->_required_fields, $fields)) return FALSE;
		
		foreach($this->_fields as $check) {
			if( isset($fields[$check])) {
				if($fields[$check] == 'NOW()') {
					$this->db->set($check, $fields[$check], FALSE);
				} else if($fields[$check] == 'NULL') {
					$this->db->set($check, NULL);
				} else {
					$this->db->set($check, $fields[$check]);
				}
			}
		}
		
		foreach($where as $key => $statement) {
			if($where[$key] == 'NULL') {
				$this->db->where($check.' IS NULL', null);
			} else if($where[$key] == 'NOT NULL') {
				$this->db->where($check.' IS NOT NULL', null);
			} else {
                switch($key) {
                    case "in":
                        foreach($where[$key] as $key => $where)
                            $this->db->where_in($key, $where);
                        break;
                    default:
                        $this->db->where($key, $statement);
                }
            }
		}
			
		$this->db->update($this->_primary_table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Gets rows from the database
	 * @param ($options) Array of fields and other options
	 * @return (Array) Array of results
	 */
	public function get($options = array()) 
	{
		$fields = $this->_default(array('sort_direction' => 'ASC'), $options);
		
		foreach($this->_fields as $check) {
			if(isset($options[$this->_primary_table.".".$check]))
				$check = $this->_primary_table.".".$check;
			if(isset($options[$check]) || isset($options[$this->_primary_table.".".$check])) {
				if($options[$check] == 'NULL') {
					$this->db->where($check.' IS NULL', null);
				} else {
					$this->db->where($check, $options[$check]);
				}
			}
		}
		
		if(isset($options['where'])) {
			foreach($options['where'] as $key => $where) {
				if($key == 'NULL') {
					$this->db->where($where.' IS NULL', null);
				} else {
					$this->db->where($key, $where);
				}
			}
		}
        
        if(isset($options['like'])) {
			foreach($options['like'] as $key => $where) {
				$this->db->like($key, $where);
			}
		}
		
		if(isset($options['not'])) {
			foreach($options['not'] as $key => $where) {
				if($key == 'NULL') {
					$this->db->where($where.' IS NOT NULL', null);
				} else {
					$this->db->where($key.' !=', $where);
				}
			}
		}
		
		if(isset($options['in'])) {
			foreach($options['in'] as $key => $where)
				$this->db->where_in($key, $where);
		}
		
		if(isset($options['not_in'])) {
			foreach($options['not_in'] as $key => $where)
				$this->db->where_not_in($key, $where);
		}
		
		if(isset($options['select'])) {
			foreach($options['select'] as $value) 
			{
				$this->db->select($value, FALSE);
			}
		}
		
		if(isset($options['limit']) && isset($options['offset']))
			$this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit']))
			$this->db->limit($options['limit']);
			
		if(isset($options['join'])) {
			foreach($options['join'] as $key => $value) {
				if($key != 'join_type') {
					if(isset($options['join']['join_type'])) {
						$this->db->join($key, $value, $options['join']['join_type']);
					} else
						$this->db->join($key, $value);
				}
			}
		}
		
		if(isset($options['left_join'])) {
			foreach($options['left_join'] as $key => $value) {
				$this->db->join($key, $value, 'left');
			}
		}
		
		if(isset($options['group_by'])) {
			foreach($options['group_by'] as $value) {
				$this->db->group_by($value);
			}
		}
		
		if(isset($options['order_by'])) {
			foreach($options['order_by'] as $key => $value) {
				$this->db->order_by($key, $value);
			}
		}
		
		if(isset($options['having'])) {
			foreach($options['having'] as $value) {
				$this->db->having($value);
			}
		}
		
		
		
		$query = $this->db->get($this->_primary_table);
		if(isset($options['count']) && ($options['count'] == TRUE || $options['count'] === 1))
			return $query->num_rows();
		if($query->num_rows() > 0) {
			if(isset($options['single']) && $options['single'] == TRUE) {
				$result = $query->row_array();
			} else {
				$result = $query->result_array();
				if(isset($options['array_key'])) {
					$output = array();
					foreach($result as $key	=>	$row) {
						if(isset($row[$options['array_key']]))
							$output[$row[$options['array_key']]] = $row;
					}
					$result = $output;
				}
			}
			return $result;
		}
		else
			return FALSE;
	}
	
	/**
	 * Checks if a row exists
	 * @param ($options) fields to test
	 * @return (TRUE) If a result was found
	 * @return (FALSE) If no result was found
	 */
	public function exists($options = array()) 
	{
		foreach($this->_fields as $check) 
		{
			if(isset($options[$check]))
				$this->db->where($check, $options[$check]);
		}
		
		$query = $this->db->get($this->_primary_table);
		if($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}
	
	public function get_column($column_name, $options = array())
	{	
		if(isset($options[$column_name]))
			$this->db->select($column_name);
		
		foreach($this->_fields as $check) 
		{
			if(isset($options[$check]))
				$this->db->where($check, $options[$check]);
		}
		
		$query = $this->db->get($this->_primary_table);
		if($query->num_rows() > 0)
			return $query->row()->$column_name;
		else
			return FALSE;
	}
	
	public function count($options = array())
	{
		$fields = $this->_default(array('sort_direction' => 'ASC'), $options);
		
		foreach($this->_fields as $check) {
			if(isset($options[$this->_primary_table.".".$check]))
				$check = $this->_primary_table.".".$check;
			if(isset($options[$check]) || isset($options[$this->_primary_table.".".$check])) {
				if($options[$check] == 'NULL') {
					$this->db->where($check.' IS NULL', null);
				} else {
					$this->db->where($check, $options[$check]);
				}
			}
		}
		
		if(isset($options['where'])) {
			foreach($options['where'] as $key => $where) {
				if($key == 'NULL') {
					$this->db->where($where.' IS NULL', null);
				} else {
					$this->db->where($key, $where);
				}
			}
		}
		
		if(isset($options['select'])) {
			foreach($options['select'] as $value) 
			{
				$this->db->select($value, FALSE);
			}
		}
		
		if(isset($options['limit']) && isset($options['offset']))
			$this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit']))
			$this->db->limit($options['limit']);
			
		if(isset($options['join'])) {
			foreach($options['join'] as $key => $value) {
				if($key != 'join_type') {
					if(isset($options['join']['join_type'])) {
						$this->db->join($key, $value, $options['join']['join_type']);
					} else
						$this->db->join($key, $value);
				}
			}
		}
		
		if(isset($options['left_join'])) {
			foreach($options['left_join'] as $key => $value) {
				$this->db->join($key, $value, 'left');
			}
		}
		
		if(isset($options['group_by'])) {
			foreach($options['group_by'] as $value) {
				$this->db->group_by($value);
			}
		}
		
		if(isset($options['order_by'])) {
			foreach($options['order_by'] as $key => $value) {
				$this->db->order_by($key, $value);
			}
		}
		
		if(isset($options['having'])) {
			foreach($options['having'] as $value) {
				$this->db->having($value);
			}
		}
		
		
		
		$query = $this->db->get($this->_primary_table);
		return $query->num_rows();
	}
	
	private function _required($required, $data) 
	{
		if(isset($required)) {
			foreach($required as $field) {
				if( ! isset($data[$field]))
					return FALSE;
			}
		}
		return TRUE;
	}
	
	private function _default($defaults, $options) {
		return array_merge($defaults, $options);
	}

	private function _update_timestamp($data, $field) {
		$data[$field] = date('Y-m-d H:i:s');
		return $data;
	}
	
}

/* End of file KAK_Model.php */
/* Location: ./application/core/KAK_Model.php */