<?php
// i used example json file as database not mysql database if you want to change just put data in db and change feaching codes
class DataBase{

	public $discounts=0;
	public $dbArray;
	public $dbObject;
	
	function init(){
		$this->dbObject = json_decode(file_get_contents("exampleDB.json"));
		$this->dbArray = json_decode(file_get_contents("exampleDB.json"),true);
	}
	
	function get_item($table,$key,$value){

		if(is_array($this->dbArray[$table]) and is_array($this->dbArray)){
			foreach($this->dbArray[$table] as $k=>$v){
					if(strtolower($value)==strtolower($v[$key])){
						return $v;
					}	
			}
		}
		return false;
	}
	
	function insert_item($table,$newarr){
		$this->init();
		$oldFile=file_get_contents("exampleDB.json");
		if(is_array($this->dbArray[$table]) and is_array($this->dbArray)){
			$this->dbArray[$table]=array_merge($this->dbArray[$table],[$newarr]);
		}
		if(file_put_contents("exampleDB.json",json_encode($this->dbArray))){
			return ok;
		}else{
			file_put_contents("exampleDB.json",$oldFile);
			return false;
		}
	}
	
	function delete_item($table,$key,$item){
		$this->init();
		$oldFile=file_get_contents("exampleDB.json");
		if(is_array($this->dbArray[$table]) and is_array($this->dbArray)){
			foreach($this->dbArray[$table] as $k=>$v){
					if($v[$key] === $item){
						continue;
					}
					$modifiedArray[$k]=$v;
			}
			$this->dbArray[$table] = $modifiedArray;
		}
		if(file_put_contents("exampleDB.json",json_encode($this->dbArray))){
			return ok;
		}else{
			file_put_contents("exampleDB.json",$oldFile);
			return false;
		}
	}
	
}


?>