<?php

abstract class Model {
	private $registry;
    protected $table = "";
	
	public function __construct($registry, $name) {
		$this->registry = $registry;
        $this->table = $name;
	}	
	public function __get($key) {
		return $this->registry->$key;
	}	
	public function __set($key, $value) {
		$this->registry->$key = $value;
	}
    
    public function query($sql){
		$query = $this->db->query($sql);
		return $query;
    }
    
    public function addItem($data = array()) {
        $sql = "INSERT INTO `".$this->table."`";
        $columns = "";
        $values = "";
        
        if(!empty($data)) {
			$count = count($data);
			foreach($data as $key => $value) {
				$columns .= "`$key`";
				$values .= "'$value'";
                
				$count--;
				if($count > 0){
                    $columns .= ", ";
                    $values .= ", ";
                }
			}
		} else {
            return false;
        }
        
        $sql .= "(".$columns.") VALUES (".$values.")";
            
		$this->db->query($sql);
		return $sql;
	}
    public function getItems($column = array(), $data = array(), $nodata = array(), $sort = array(), $options = array()) {
		$sql = "SELECT ";
		
        if(empty($column)){
            $sql .= " * ";
        } else{
            $count = count($data);
			foreach($column as $key => $value) {
				$sql .= "'" . $value . "'";
				
				$count--;
				if($count > 0) $sql .= ", ";
			}
        }
        
        $sql .= " FROM `".$this->table."` ";
        
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $value . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
        
        if(!empty($nodata)) {
			$count = count($nodata);
            if(empty($data)){
                $sql .= " WHERE";
            }
			foreach($nodata as $key => $value) {
				$sql .= " $key != '" . $value . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
        
		if(!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach($sort as $key => $value) {
				$sql .= " $key " . $value;
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		
		if(!empty($options)) {
			if ($options['start'] < 0) {
				$options['start'] = 0;
			}
			if ($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
        
		$query = $this->db->query($sql);
        
		return $query;
	}
	public function deleteItems($data = array(), $nodata = array()) {
		$sql = "DELETE FROM `".$this->table."`";
        
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $value . "'";				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
        
        if(!empty($nodata)) {
			$count = count($nodata);
            if(empty($data)){
                $sql .= " WHERE";
            }
			foreach($nodata as $key => $value) {
				$sql .= " $key != '" . $value . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
        
		$query = $this->db->query($sql);
		return $sql;
	}
	public function updateUsers($new_data = array(), $data = array(), $nodata = array()) {
		$sql = "UPDATE `".$this->table."`";
		
        if(!empty($new_data)) {
			$count = count($new_data);
			$sql .= " SET";
			foreach($new_data as $key => $value) {
				$sql .= " $key = '" . $value . "'";
                
				$count--;
				if($count > 0) $sql .= ", ";
			}
		} else{
            return false;
        }
        
        if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $value . "'";
                
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
        
        if(!empty($nodata)) {
			$count = count($nodata);
            if(empty($data)){
                $sql .= " WHERE";
            }
			foreach($nodata as $key => $value) {
				$sql .= " $key != '" . $value . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
        
		$query = $this->db->query($sql);
        
		return $sql;
	}
    
    public function getMax($column = 'id'){
        $sql = "SELECT MAX(".$column.") as 'max' FROM `".$this->table."`";
		$query = $this->db->query($sql);
		return $query;
    }    
    public function getCount($column = array(), $group_by = false){
        $sql = "SELECT";
        
        if(!empty($column)){
            foreach($column as $key => $value){
                $sql .= " `" . $value . "`,";
            }
        }
        
        $sql .= " count(id) as 'count' FROM ".$this->table."`";
        
        if($group_by){
            $sql .= " GROUP BY ".$group_by;
        }
        
		$query = $this->db->query($sql);
		return $query;
    }
}
?>
