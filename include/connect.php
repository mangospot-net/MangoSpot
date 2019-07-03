<?php
class Basuki extends PDO{
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
		$bsk = $this->sql.':dbname='.$this->database.";host=".$this->host;
		parent::__construct($bsk,$this->user, $this->paswod);
	}
	public function Tambah($tabel,$rows=null){
		$tambah = 'insert into '.$tabel;
		$row = null; 
		$value=null;
		foreach($rows as $key => $nilai){
			$row .=",".$key;
			$value .=", :".$key;
		}
		$tambah .="(".substr($row,1).")";
		$tambah .="values(".substr($value,1).")";
		$rmh = parent::prepare($tambah);
		$rmh->execute($rows);
		$rowc = $rmh->rowCount();
		return $rowc;
	}
	public function Ubah($tabel, $fild = null ,$where = null){
		 $update = 'UPDATE '.$tabel.' SET ';
		 $set=null; $value=null;
		 foreach($fild as $key => $values){
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
	public function Ganti($tabel, $fild = null ,$where = null){
		 $update = 'UPDATE '.$tabel.' SET ';
		 $set=null; $value=null;
		 foreach($fild as $key => $values)
		 {
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
		 $query->execute($fild);
		 $rowcount = $query->rowCount();
         return $rowcount;
		 
    }
	public function Hapus($tabel,$where=null){
		$hapus = 'delete from '.$tabel;
		$lis = array();
		$par = null;
		foreach($where as $key => $value){
			$lis[] = "$key = :$key";
			$par .= ', ":'.$key.'":"'.$value.'"';
		}
		$hapus .= ' where '.implode(' and ',$lis);
		$json = "{".substr($par,1)."}";
		$param = json_decode($json,true);
		$query = parent::prepare($hapus);
		$query->execute($param);
		$rowc = $query->rowCount();
		return $rowc;
	}
	public function Tampil($tabel, $rows, $where = null, $order = null, $limit = null){
		$tampil = 'select '.$rows.' from '.$tabel;
		if($where != null){
			$tampil .= ' where '.$where;
		}
		if($order != null){
			$tampil .= ' order by '.$order;
		}
		if($limit != null){
			$tampil .= 'limit '.$limit;
		}
		$query = parent::prepare($tampil);
		$query->execute();
		$lihat = array();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $this->result = $row;
	}
	public function Create($tabel, $fild=null){
		$creat = 'CREATE TABLE '.$tabel.' (';
		$imp = implode(',',$fild);
		$creat .= $imp;
		$creat .= ')';
		$query = parent::exec($creat);
		return true;
	}
	
	public function Reset($tabel, $where=null){
		$reset = 'delete from '.$tabel;
		if($where != null){
			parent::exec('ALTER SEQUENCE '.$tabel.'_'.$where.'_seq RESTART WITH 1');
		}
		$query = parent::prepare($reset);
		$query->execute();
		$count = $query->rowCount();
		return $count;
	}
	public function Drop($tabel=null){
		$busak = 'DROP TABLE '.$tabel;
		$query = parent::exec($busak);
		return true;
	}
	public function View($tabel, $rows, $where = null, $order = null, $limit = null){
		$tampil = 'select '.$rows.' from '.$tabel;
		if($where != null){
			$tampil .= ' where '.$where;
		}
		if($order != null){
			$tampil .= ' order by '.$order;
		}
		if($limit != null){
			$tampil .= ' limit '.$limit;
		}
		$query = parent::prepare($tampil);
		$query->execute();
		$query->setFetchMode(PDO::FETCH_ASSOC);
		return $query;
	}
	public function getResult(){
		return $this->result;
	}
}
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'fungsi.php');
?>