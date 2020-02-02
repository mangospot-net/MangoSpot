<?php
class Connect extends PDO{
	private $sql;
	private $host;
	private $database;
	private $user;
	private $paswod;
	private $result;
	public function __construct(){
		$this->sql=TYPE;
		$this->host=DB_HOST;
		$this->database=DB_DATABASE;
		$this->user=DB_USER;
		$this->paswod=DB_PASSWORD;
		$config = $this->sql.':dbname='.$this->database.";host=".$this->host;
		parent::__construct($config,$this->user, $this->paswod);
	}
	public function Insert($table, $rows=null, $type=false){
		$insert = 'insert into '.$table;
		$row = null; 
		$value=null;
		foreach($rows as $key => $item){
			$row .= $this->sql == 'pgsql' && $type ? ',"'.$key.'"' : ",".$key;
			$value .=", :".$key;
		}
		$insert .="(".substr($row,1).")";
		$insert .="values(".substr($value,1).")";
		$parent = parent::prepare($insert);
		$parent->execute($rows);
		$counts = $parent->rowCount();
		return $counts;
	}
	public function Update($table, $field = null, $where = null){
		 $update = 'UPDATE '.$table.' SET ';
		 $set=null; $value=null;
		 foreach($field as $key => $values){
			 $set .= ', '.$key. ' = :'.$key;
			 $value .= ', ":'.$key.'":"'.$values.'"';
		 }
		 $update .= substr(trim($set),1);
		 $json = '{'.substr($value,1).'}';
		 $param = json_decode($json,true);
		 if($where != null){
		    $update .= ' WHERE '.$where;
		 }
		 $query = parent::prepare($update);
		 $query->execute($param);
		 $rowcount = $query->rowCount();
         return $rowcount;
    }
	public function Change($table, $field = null ,$where = null){
		 $update = 'UPDATE '.$table.' SET ';
		 $set=null; $value=null;
		 foreach($field as $key => $item){
			 $set .= ', '.$key. ' = :'.$key;
			 $value .= ', ":'.$key.'":"'.$item.'"';
		 }
		 $update .= substr(trim($set),1);
		 $json = '{'.substr($value,1).'}';
		 $param = json_decode($json,true);
		 
		 if($where != null){
		    $update .= ' WHERE '.$where;
		 }
		 
		 $query = parent::prepare($update);
		 $query->execute($field);
		 $rowcount = $query->rowCount();
         return $rowcount;
		 
    }
	public function Delete($table,$where=null){
		$delete = 'delete from '.$table;
		$array = array();
		$par = null;
		foreach($where as $key => $value){
			$array[] = "$key = :$key";
			$par .= ', ":'.$key.'":"'.$value.'"';
		}
		$delete .= ' where '.implode(' and ',$array);
		$json = "{".substr($par,1)."}";
		$param = json_decode($json,true);
		$query = parent::prepare($delete);
		$query->execute($param);
		$rowc = $query->rowCount();
		return $rowc;
	}
	public function Show($table, $rows, $where = null, $order = null, $limit = null){
		$show = 'select '.$rows.' from '.$table;
		if($where != null){
			$show .= ' where '.$where;
		}
		if($order != null){
			$show .= ' order by '.$order;
		}
		if($limit != null){
			$show .= 'limit '.$limit;
		}
		$query = parent::prepare($show);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $this->result = $row;
	}
	public function Select($table, $rows, $where = null, $order = null, $limit = null){
		$show = 'select '.$rows.' from '.$table;
		if($where != null){
			$show .= ' where '.$where;
		}
		if($order != null){
			$show .= ' order by '.$order;
		}
		if($limit != null){
			$show .= ' limit '.$limit;
		}
		$query = parent::prepare($show);
		$query->execute();
		$query->setFetchMode(PDO::FETCH_ASSOC);
		return $query;
	}
	public function Table($table, $from, $where, $columns, $group = null){
		$requestData = $_REQUEST;
		$totalData = $this->Show($table, "count(*) as total", $where);
		$totalFiltered = $totalData['total'];
		$totalColumn = count($requestData['columns']);
		$sql = $where;
		$search = array();
		if(!empty($requestData['search']['value'])){
			foreach($columns as $colums){
				if($this->sql == 'pgsql'){
					$search[] ="lower($colums::text) LIKE '%".strtolower($requestData['search']['value'])."%' ";
				} else {
					$search[] ="lower($colums) LIKE '%".strtolower($requestData['search']['value'])."%' ";
				}
			}
			$sql.= " AND (".implode(' OR ', $search).")";
		}
		for($i=0; $i<$totalColumn; $i++){
			if( !empty($requestData['columns'][$i]['search']['value']) ){ 
				$sql.= " AND $columns[$i] = '".$requestData['columns'][$i]['search']['value']."' ";
			}
		}
		if($group != null){
			$sql.= " GROUP BY ".$group;
		}
		$query = $this->Show($table, "count(*) as total", $sql);
		$totalFiltered = $query['total'];
		if(is_array($requestData['order'])){
			$order = (array_key_exists($requestData['order'][0]['column'], $columns) ? $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'] : "");
		} else {
			$order = (!empty($requestData['order']) ? $requestData['order']." ".$requestData['dir'] : "");
		}
		$limit = ($requestData['length'] != '-1' ? $requestData['length']." OFFSET ".$requestData['start']." " : "");
		$users = $this->Select($table, $from, $sql, $order, $limit);
		$data = array();
		foreach($users as $row) { 
			$data[] = $row;
		}
		$json_data = array(
			"draw"            => intval((!empty($requestData['draw']) ? $requestData['draw'] : 0) ),
			"recordsTotal"    => intval( $totalData['total'] ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $data
		);
		return $json_data;
	}
	public function Create($table, $field=null){
		$create = 'CREATE TABLE '.$table.' (';
		$implode = implode(',', $field);
		$create .= $implode;
		$create .= ')';
		$query = parent::exec($create);
		return true;
	}
	public function Reset($table, $where=null){
		$reset = 'delete from '.$table;
		if($where != null){
			parent::exec('ALTER SEQUENCE '.$table.'_'.$where.'_seq RESTART WITH 1');
		}
		$query = parent::prepare($reset);
		$query->execute();
		$count = $query->rowCount();
		return $count;
	}
	public function Drop($table=null){
		$drop = 'DROP TABLE '.$table;
		$query = parent::exec($drop);
		return true;
	}
	public function Custom($table=null){
		$query = parent::prepare('select '.$table);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $this->result = $row;
	}
	public function getResult(){
		return $this->result;
	}
}
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'function.php');
?>
