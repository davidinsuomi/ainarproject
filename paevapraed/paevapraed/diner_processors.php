<?php
include_once("utils.php");
include_once("classes.php");

class DinerParviiz{
	
	public static function processDiner( $diner, $debug ){
		$soupUrl = "http://parviiz.ee/admin/soups.xml";
		$mealUrl = "http://parviiz.ee/admin/mainMeals.xml";
		$soupHtml = getHtml( $soupUrl );
		$mealHtml = getHtml( $mealUrl );
		
		$mealInfo = null;
		$soupInfo = null;
		$mealDateFoods = array();
		$soupDateFoods = array();
		if( $mealHtml == null || $soupHtml == null ){
			throw new Exception( "Some info not available" );
		}
		$mealInfo = implode( "</br>", self::getInfoArray( $mealHtml, "Praad" ) );
		$mealDateFoods = self::getDateFoods( $mealHtml );
		$soupInfo = implode( "</br>", self::getInfoArray( $soupHtml, "Supp" ) );
		$soupDateFoods = self::getDateFoods( $soupHtml );
	
		foreach( $mealDateFoods as $dateFood ){
			self::verifyNotNull( $dateFood );
			usleep( 1000 );
			$foods = array();
			array_push( $foods, $dateFood->getFood() );
			$soupDateFood = $soupDateFoods[$dateFood->getDate()];
			self::verifyNotNull( $soupDateFood );
			array_push( $foods, $soupDateFood->getFood() );
			$dateFood->setFood( implode( $foods, "</br>" ) );
			$diner->addDateFood( $dateFood );
			println( "dateFood: ".$diner->getDateFood( $dateFood->getDate() ) );
		}
		$info = $mealInfo."</br>".$soupInfo;
		$diner->addInfo( $info );
		
		return $diner;
	}
	
	private static function verifyNotNull( $element ){
		if( $element == null ){
			throw new Exception( "Specified tag not found" );
		}
	}
	
	private static function getInfoArray( $html, $type ){
		$menu = $html->find( "menu", 0 );
		self::verifyNotNull( $menu );
		$prices = $menu->find( "prices" );
		self::verifyNotNull( $prices );
		$infoArray = array();
		foreach( $prices as $price ){
			self::verifyNotNull( $price );
			$unit = $price->unit;
			if( $unit == "eur" ){
				$info = $type." ".( $price->size == "large" ? "suur" : "väike" )." ".$price->plaintext." ".$unit;
				println( "info: ".$info );
				array_push( $infoArray, $info );
			}
		}
		return $infoArray;
	}
	
	private static function getDateFoods( $html ){
		$menu = $html->find( "menu", 0 );
		self::verifyNotNull( $menu );
		$meals = $menu->find( "meal" );
		self::verifyNotNull( $meals );
		$dateFoods = array();
		foreach( $meals as $meal ){
			usleep( 1000 );
			$dateFood = new DateFood();
			self::verifyNotNull( $meal );
			$name = $meal->find( "name", 0 );
			self::verifyNotNull( $name );
			$food = $name->plaintext;
			$dateFood->setFood( $food );
			println( "food: ".$food );
			$date = $meal->find( "date", 0 );
			self::verifyNotNull( $date );
			$day = $date->find( "day", 0 );
			self::verifyNotNull( $day );
			$monthName = $date->find( "month", 0 );
			self::verifyNotNull( $monthName );
			$month = EstDates::getMonthFromMonthName( $monthName );
			self::verifyNotNull( $month );
			$dateString = $month."-".trim( $day->innertext );
			$dateString = getYear( $dateString )."-".$dateString;
			$dateFood->setDate( $dateString );
			println( "date: ".$dateString );
			$dateFoods[$dateString] = $dateFood;
		}
		return $dateFoods;
	}
	
}

class DinerKahkukas{
	
	public static function processDiner( $diner, $debug ){
		$url = "http://www.kahkukas.ee";
		$html = getHtml( $url );
		if( $html == null ){
			throw new Exception("No data retrieved");
		}
		$item = $html->find("div[id=tpmod-left]", 0);

		if($item == null){
			throw new Exception("Specified tag not found");
		}
		$result = $item->innertext();
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $result, null);
		$infoStartedLevel = 0;
		$noFoodCount = 0;
		$infoArray = array();
		$foodArray = array();
		$dateFoods = array();
		$date = null;
		foreach($item as $element){
			usleep( 1000 );
			$element = cleanString( $element );
			if( $debug ){
				echo println( $element );
			}
			if( preg_match( "/^N.*DALASUPP/", $element ) ){
				$infoStartedLevel = 4;
				println( "infoStartedLevel: ". $infoStartedLevel );
				continue;
			}
			if( $infoStartedLevel >= 0 && $infoStartedLevel <= 2 ){
				$month = EstDates::getMonthFromMonthName( $element );
				if( $month != null ){
					if( preg_match( "/^\d\d?/", $element, $dayArray ) );
					$day = $dayArray[0];
					$day = numToLeadingZero( $day );
					$date = $month."-".$day;
					$date = getYear( $date )."-".$date;
					$infoStartedLevel = 1;
					println( "date: ".$date );
					println( "infoStartedLevel: ". $infoStartedLevel );
					continue;
				}
			}
			if( $infoStartedLevel == 1 ){
				if( $element != null ){
					$foodArray = self::addFood( $foodArray, $element );
					$infoStartedLevel = 2;
					println( "infoStartedLevel: ". $infoStartedLevel );
					continue;
				}
			}
			if( $infoStartedLevel == 2 ){
				if( $noFoodCount >= 2 ){
					$dateFood = new DateFood( $date, implode( $foodArray, "</br>") );
					println( "dateFood: ".$dateFood );
					array_push( $dateFoods, $dateFood );
					
					$foodArray = array();
					$infoStartedLevel = 0;
					println( "infoStartedLevel: ". $infoStartedLevel );
					$noFoodCount = 0;
				} else if( $element == 0 ){
					$noFoodCount++;
				} else{
					$foodArray = self::addFood( $foodArray, $element );
				}
				continue;
			}
			if( $infoStartedLevel == 4 ){
				if( $element != null ){
					$soup = mb_strtolower( $element, "UTF-8" );
					$soup = mb_ucfirst( $soup, "UTF-8" );
					println( "soup: ".$soup );
					foreach( $dateFoods as $dateFood ){
						$dateFood->setFood( $dateFood->getFood()."</br>".$soup );
						println( "dateFood: ".$dateFood );
						$diner->addDateFood( $dateFood );
					}
				}
			}
		}
		return $diner;
	}
	
	private static function addFood( $array, $element ){
		$food = mb_strtolower( $element, "UTF-8" );
		$food = mb_ucfirst( $food, "UTF-8" );
		array_push( $array, $food );
		println( "food: ".$food );
		return $array;
	}
}

class DinerTrehv{

	public static function processDiner( $diner, $debug ){
		$url = "http://www.trehv.ee";
		$html = getHtml( $url );
		if( $html == null ){
			throw new Exception("No data retrieved");
		}
		$item = $html->find("div[id=events-list]", 0);

		if($item == null){
			throw new Exception("Specified tag not found");
		}
		$result = $item->innertext();
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $result, null);
		$level = 0;
		$noFoodCount = 0;
		$infoArray = array();
		$foodArray = array();
		$date = null;
		foreach($item as $element){
			usleep( 1000 );
			$element = cleanString( $element );
			if( $debug ){
				echo println( $element );
			}
			if( $level == 0 ){
				if( $element != null ){
					if( preg_match( "/N.*DALA\sMEN.*/", $element ) ){
						continue;
					} else{
						$info = $element;
						println( "info: ".$info );
						array_push( $infoArray, $info );
						$level = 1;
						continue;
					}
				}
			}
			if( $level == 1 ){
				if( $element != null ){
					if( preg_match("/\d\d?[,\.]\d\d?/", $element, $dateInfo ) ){
						preg_match_all( "/\d\d?/", $dateInfo[0], $dateInfo );
						$month = $dateInfo[0][1];
						$day = $dateInfo[0][0];
						$date = $month."-".$day;
						$date = getYear( $date )."-".$date;
						println( "date: ".$date );
						$level = 2;
						continue;
					}
				}
			}
			if( $level == 2 ){
				if( $element != null ){
					array_push( $foodArray, $element );
					println( "food: ".$element );
					$noFoodCount = 0;
				} else{
					$noFoodCount++;
				}
				if( $noFoodCount > 3 ){
					$noFoodCount = 0;
					$level = 1;
					$diner = self::addDateFood( $diner, $date, $infoArray, $foodArray );
					$date = null;
					$foodArray = array();
				}
			}
		}
		return $diner;
	}
	
	private static function addDateFood( $diner, $date, $infoArray, $foodArray ){
		$info = implode( "</br>", $infoArray );
		
		$previousFood = null;
		$resultFoodArray = array();
		foreach( $foodArray as $foodElement ){
			if( preg_match( "/^\d/", $foodElement ) ){
				array_push( $resultFoodArray, $previousFood." ".$foodElement );
				$previousFood = null;
				continue;
			} elseif( $previousFood != null ){
				array_push( $resultFoodArray, $previousFood );
			}
			$previousFood = $foodElement;
		}
		$food = implode( "</br>", $resultFoodArray );
		$dateFood = new DateFood( $date, $food, $info );
		println( "dateFood: ".$dateFood );
		$diner->addDateFood( $dateFood );
		return $diner;
	}
}
?>