<?php
class Diner{
	private $code;
	private $datefoods;

	public function Diner($code){
		$this->code = $code;
		$this->datefoods = array();
	}

	public function addDateFood($datefood){
		array_push($this->datefoods, $datefood);
	}

	public function getDateFoods(){
		return $this->datefoods;
	}

	public function getCode(){		
		return $this->code;
	}
	
	public function addInfo( $info ){
		foreach( $this->datefoods as $key=>$element ){
			$this->datefoods[$key]->setInfo( $info );
		}
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
}
?>