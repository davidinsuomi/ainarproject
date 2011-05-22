<?php
class Registry{
	private $keyValues = array();
	
	public function __set( $key, $value ){
		$this->keyValues[$key] = $value;
	}
	
	public function __get( $key ){
		return $this->keyValues[$key];
	}
	
	public function __isset( $key ){
		return array_key_exists( $key, $this->keyValues );
	}
}

class Diner{
	private $code;
	private $datefoods;
	private $defaultInfo;
	private $password;

	public function Diner($code){
		$this->code = $code;
		$this->datefoods = array();
	}

	public function addDateFood($datefood){
		array_push($this->datefoods, $datefood);
	}
	
	public function addDateFoods( $datefoods ){
		$this->datefoods = $datefoods;
	}
	
	public function clearDateFoods(){
		$this->datefoods = array();
	}
	
	public function getDateFoods(){
		return $this->datefoods;
	}
	
	public function getDateFood( $date ){
		foreach( $this->datefoods as $key=>$dateFood ){
			if( $this->datefoods[$key]->getDate() == $date ){
				return $dateFood;
			}
		}
		return null;
	}

	public function getCode(){		
		return $this->code;
	}
	
	public function addInfo( $info ){
		foreach( $this->datefoods as $key=>$element ){
			$this->datefoods[$key]->setInfo( $info );
		}
	}
	
	public function getDefaultInfo(){
		return $this->defaultInfo;
	}
	
	public function getPassword(){
		return $this->password;
	}
	
	public function setDefaultInfo( $defaultInfo ){
		$this->defaultInfo = $defaultInfo;
	}
	
	public function setPassword( $password ){
		$this->password = $password;
	}
}

class DateFood{
	private $date;
	private $food;
	private $info;

	public function DateFood($date = null, $food = null, $info = null){		
		$this->date = $date;
		$this->food = $food;
		$this->info = $info;
	}
	
	public function getDate(){		
		return $this->date;
	}

	public function getFood(){		
		return $this->food;
	}
	
	public function getInfo(){		
		return $this->info;
	}
	
	public function setDate($date){		
		$this->date = $date;
	}

	public function setFood($food){		
		$this->food = $food;
	}
	
	public function setInfo($info){		
		$this->info = $info;
	}
	
	public function __toString()
    {
        return "Date ".$this->date.", Food: ".$this->food.", Info: ".$this->info;
    }
	
}
?>