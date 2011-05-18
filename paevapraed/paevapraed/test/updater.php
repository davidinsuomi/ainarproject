<?php
include_once("simple_html_dom.php");
include_once("classes.php");
include_once("common.php");
include_once("conf.php");

set_time_limit( 40 );

error_reporting(E_ALL);
register_shutdown_function("fatalErrorShutdownHandler");

$dinerindex = 0;
$updaterstarted = false;
$timeExceeded = false;
$starttime = time();

main();

function main(){
	global $dinerindex, $updaterstarted, $starttime, $timeExceeded;
	$dinercodes = getDinerCodes();
	for(null; $dinerindex < count($dinercodes); null){
		$dinerindex++;
		$updaterstarted = true;
		echo $dinercodes[$dinerindex-1]."<br>\n";
		try{
			// exit if ran more than 3 minutes
			$exectime = time() - $starttime;
			if( $exectime > 180 ){
				$timeExceeded = true;
				exit( "max excectution time exeeded:". $exectime."<br/>\n");
			}
			
			$diner = getDiner($dinercodes[$dinerindex-1]);
			usleep( 1000 );
			writeDiner($diner);
		}
		catch(Exception $e){
			echo "<b>".$e."</b><br/>\n";
		}
		echo "<br/>\n";
	}
	$exectime = time() - $starttime;
	echo "script executiontime: ".$exectime." seconds"."<br/>\n";
}

function writeDiner($diner){
	mysql_connect(DBconnection::$dbhost, DBconnection::$dbuser, DBconnection::$dbpass);
	@mysql_select_db(DBconnection::$dbname) or die( "Unable to select database");
	mysql_set_charset("utf8");
	if(count($diner->getDateFoods()) > 0){
		$query = "delete from datefoods where code='".$diner->getCode()."'";
		mysql_query($query);
	}
	
	foreach($diner->getDateFoods() as $element){	
		$query = "insert into datefoods (code, date, food, info) ".
				"values('".$diner->getCode()."','".$element->getDate()."','".$element->getFood()."','".$element->getInfo()."')";
		mysql_query($query);
	}

	mysql_close();
}

function readDiner($dinercode){
	mysql_connect(DBconnection::$dbhost, DBconnection::$dbuser, DBconnection::$dbpass);
	@mysql_select_db(DBconnection::$dbname) or die( "Unable to select database");
	mysql_set_charset("utf8");
	$query = "select date, food, info from datefoods where code = '".$dinercode."' order by date";
	$results = mysql_query($query);
	mysql_close();
	$diner = new Diner($dinercode);
	while($row = mysql_fetch_array($results, MYSQL_ASSOC)){
		$diner->addDateFood(new DateFood($row["date"], $row["food"], $row["info"]));
	}
	return $diner;
}

function getDinerCodes(){
	mysql_connect(DBconnection::$dbhost, DBconnection::$dbuser, DBconnection::$dbpass);
	@mysql_select_db(DBconnection::$dbname) or die( "Unable to select database");
	
	$results = array();
	
	$query = "select code from diners where updateenabled = 1";
	$queryresult = mysql_query($query);
	mysql_close();
	
	while($row = mysql_fetch_row($queryresult)){
		array_push($results, $row[0]);
	}
	
	return $results;
}

function getDiner($dinercode){
	$diner = new Diner($dinercode);
	if(isset($_GET["debug"])){
		$debug = $_GET["debug"];
	}
	else{
		$debug = null;
	}
	//Break and New Line
	$bnl = "<br/>\n";
	
	if( $debug != null && $debug != $dinercode ){
		return $diner;
	}
	
	if($dinercode == "AURA"){
		$url = "http://aurakohvik.ee";
		$html = file_get_html($url);
		$item = $html->find("div[class=s5_sn_1_text]", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$foodArray = array();
		$infostarted = false;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if( $element == null ){
				continue;
			}
			if( preg_match( "/(?i)p.+evapraad(?-i)/", $element ) || preg_match( "/(?i)p.+evasupp(?-i)/", $element ) ){
				$infostarted = true;
				echo "infostarted".$bnl;
				continue;
			}
			if( $infostarted == true ){
				array_push( $foodArray, $element );
				echo "foodelement: ".$element;
			}
		}
		$food = null;
		if( count( $foodArray ) > 0 ){
			$food = implode( "<br/>", $foodArray );
		}
		$date = date( "Y-m-d" );
		echo "date: ".$date.$bnl;
		echo "food: ".$food.$bnl;
		$oldDiner = readDiner( $dinercode );
		$newDiner = new Diner( $dinercode );
		$newDiner->addDateFood( new DateFood( $date, $food ) );
		if( hasNewFoods( $oldDiner, $newDiner ) ){
			$diner = $newDiner;
		}
	}
	
	if($dinercode == "MOKA"){
		$url = "http://www.moka.ee/menyy.html";
		$html = file_get_html($url);
		$item = $html->find("div[id=contentMain]", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$foodArray = array();
		$date = null;
		$food = null;
		$tempDiner = new Diner( $dinercode );
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if( ( $weekday = EstDates::getWeekdayFromDayName( $element ) ) != null ){
				$date = getDateFromWeekday( $weekday );
				echo "date: ".$date.$bnl;
				$foodArray = array();
				$food = null;
				continue;
			}
			if( $element != null && $date != null ){
				array_push( $foodArray, preg_replace( "/^\s*-\s*/", "", $element ) );
				echo "foodelement added :".$element.$bnl;
				continue; 
			}
			if( $element == null && $date != null){
				$food = implode( "<br/>", $foodArray );
				if( preg_match( "/\(.*\)/", $food, $temp ) == 1 ){
					$temp[0] = preg_replace( "/<br\/>/", "", $temp[0] );
					$food = preg_replace( "/\(.*\)/", $temp[0], $food );
					
				}
				$food = preg_replace( "/\W*,\W*/", ", ", fixEncoding( $food ) );
				$tempDiner->addDateFood( new DateFood( $date, $food ) );
				echo "datefood added: ".$date." ".$food.$bnl;
				$foodArray = array();
				$date = null;
				$food = null;
			}
		}
		
		if( hasNewFoods( readDiner( $dinercode ), $tempDiner ) ){
			echo "writing new datefoods".$bnl;
			$diner = $tempDiner;
		}
	}
	
	if($dinercode == "PYSS"){
		$html = file_get_html("http://pyss.ee/?page=28");
		$item = $html->find("p", 0);
		if($item != null){
			$item = explode("<br />", $item->innertext);
		}
		
		$foodStarted = false;
		$infoarray = array();
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(strip_tags($element));
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			echo $element.$bnl;
			if(!$foodStarted && (preg_match("/^suur/", strtolower($element)) == 1 ||
			preg_match("/^v.+ike/", strtolower($element)) == 1 || 
			preg_match("/^pisi/", strtolower($element)) == 1)){
				array_push($infoarray, $element);
				echo "info: ".$element.$bnl;
			}
			$montharray = array();
			preg_match("/\S+/", $element, $montharray);
			$month = null;
			if(count($montharray) > 0){
				$month = EstDates::getMonthFromMonthShortName($montharray[0]);
			}
			if($month != null){
				$foodStarted = true;
				preg_match("/\d+/", $montharray[0], $dayarray);
				$day = numToLeadingZero($dayarray[0]);
				$datefood = new DateFood($month."-".$day);
				echo $datefood->getDate().$bnl;
				$food = substr($element, strlen($montharray[0]) + 1);
				echo "food: ".$food.$bnl;
				$datefood->setFood($food);
				$info = implode("<br/>", $infoarray);
				$datefood->setInfo($info);
				$diner->addDateFood($datefood);
			}
			
		}
		addYearDiner($diner);
	}
	
	if($dinercode == "PATTAYA"){
		$html = file_get_html("http://www.pattaya.ee/new/?D=31");
		$item = $html->find("div[class=hinnakiriLabel]");
		
		if($item == null)
			throw new Exception("No data retrieved");
			
		foreach($item as $element){
			usleep( 1000 );
			if($element == null){
				continue;
			}
			echo $element->plaintext."<br/>\n";
			preg_match_all("/\d+/", $element->plaintext, $date);
			$date = $date[0][1]."-".$date[0][0];
			echo $date."<br/>\n";
			$food = preg_replace("/[A-Z], *\d+.\d+ */", "", $element->plaintext);
			if($element->parent() != null){
				if($element->parent()->next_sibling() != null){
					$price = $element->parent()->next_sibling()->plaintext;
				}
			}			
			$price = preg_match("/\d+.*/", $price, $pricearray);
			echo $food."<br/>\n";
			echo $pricearray[0]."<br/>\n";
			$price = $pricearray[0];
			if($element->parent() != null){
				if($element->parent()->parent() != null){
					if($element->parent()->parent()->next_sibling() != null){
						$html = str_get_html($element->parent()->parent()->next_sibling()->innertext());
					}
				}
			}
			$soupfoodarray = $html->find("text");
			echo $soupfoodarray[0]."<br/>\n";
			
			$soupfood = trim(str_ireplace("&nbsp;", " ", utf8_encode($soupfoodarray[0])));
			$food = trim(str_ireplace("&nbsp;", " ", utf8_encode($food)));
			
			if($dinercode == "PATTAYA" && ($food != null || $soupfood != null)){
				$diner->addDateFood(new DateFood($date, $food." ".$price."<br/>".$soupfood));
			}
			if($dinercode == "AURA" && $food != null ){
				$diner->addDateFood(new DateFood($date, $food." 38.-"));
			}
		}
		addYearDiner($diner);
	}
	
	if($dinercode == "VILDE"){
		$html = file_get_html("http://www.vilde.ee/?page=23");
		$item = $html->find("div[id=sisu]", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$datesection = null;
		$foodsection = array();
		$foodavailable = false;
		$emptyFoodCount = 0;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			echo "x ".$element.$bnl;
			if($datesection != null && $element != null && !$foodavailable){
				$foodavailable = true;
				echo "FOODAVAILABLE set true".$bnl;
			}
			if($foodavailable == true && $element == null && 
				count($foodsection) > 0 && $emptyFoodCount < 3){
				$emptyFoodCount++;
				echo "EMPTYFOODCOUNT: ".$emptyFoodCount.$bnl;
				continue;
			}
			if($foodavailable == true && $element == null && $emptyFoodCount >= 3){
				break;
			}
			if($foodavailable && $element != null){
				if(preg_match("/evakomplekte pakume/", $element)==1){
					break;
				}
				array_push($foodsection, $element);
				$emptyFoodCount = 0;
				echo "food: ".$element.$bnl;
			}
			if(preg_match("/[Tt].*na, *\d/", $element)==1){
				$datesection = $element;
				echo "date: ".$datesection.$bnl;
			}
		}
		
		if($datesection == null){
			throw new Exception("No date found");
		}
		if(count($foodsection) == 0){
			throw new Exception("No food found");
		}
		
		preg_match("/\d+[\W\s]+\w+/", $datesection, $itemarray);
		echo $itemarray[0]."<br>\n";
		
		preg_match("/\d+/", $itemarray[0], $dayarray);
		echo $dayarray[0]."<br>\n";
		echo numToLeadingZero($dayarray[0])."<br>\n";
		$day = numToLeadingZero($dayarray[0]);
		$month = EstDates::getMonthFromMonthName( $datesection );
		echo $month."<br>\n";
		$food = implode("<br/>", $foodsection);
		echo $food.$bnl;	
		if( $day != null && $month != null && $food != null){
			$diner->addDateFood(new DateFood($month."-".$day, $food));
			addYearDiner($diner);
		}
		else{
			echo "something is null: day: ".$day." month: ".$month." food: ".$food.$bnl;
		}
	}
	
	if($dinercode == "PLACE"){
		
		$html = file_get_html("http://www.bcplace.ee/paevamenuu.html");
		$item = $html->find("div[class=date]");
		
		if($item == null)
			throw new Exception("No data retrieved");
		
		$h2 = $html->find("h2", 0);	
		if($h2 != null){
			$info = mb_strtolower(utf8_encode($h2->innertext), "UTF-8");
		}
		echo $info."<br/>";
		$date = null;
		$newDiner = new Diner( $dinercode );
		foreach($item as $superelement){
			usleep( 1000 );
			if($superelement == null){
				continue;
			}
			if( $date == null ){
				$food = null;
				$weekday = null;
				echo $superelement->plaintext."<br>";
				$weekday = EstDates::getWeekdayFromDayName( $superelement->plaintext );
				if($weekday != null){
					echo "weekday - ".$weekday."<br>";
					$date = getDateFromWeekday( $weekday );
					echo "date: ".$date.$bnl;
				}
			}
			if($superelement != null){
				if($superelement->next_sibling() != null){
					if($superelement->next_sibling()->first_child() != null){
						$food = trim(preg_replace("/ +/", " ", $superelement->next_sibling()->first_child()->innertext()));
					}
				}
			}
			if($food == null){
				throw new Exception("No food found");
			}
			$food = utf8_encode($food);
			$food = str_replace("\xC2\x9A", "&scaron;", $food);
			echo "food: ".$food."<br>";
			
			$newDiner->addDateFood(new DateFood($date, $food, $info));
			$date = null;
		}
		
		$oldDiner = readDiner( $dinercode );
		if( hasNewFoods( $oldDiner, $newDiner ) ){
			$diner = $newDiner;
		}
	}
	
	if($dinercode == "TREHV"){
		$html = file_get_html("http://www.trehv.ee/index.php?option=com_content&view=article&id=52&Itemid=63");
		$item = $html->find("h3");
		
		foreach($item as $element){
			usleep( 1000 );
			if($element == null){
				continue;
			}
			echo $element->plaintext."<br/>\n";
			if(preg_match("/\d\d\.\d\d/", $element->plaintext) == 1){
				echo "** ";
				preg_match_all("/\d\d/", $element->plaintext, $datearray);
				
				$date = $datearray[0][1]."-".$datearray[0][0];
				echo $date."<br/>\n";
				if($element->next_sibling() != null){
					$foodelement = $element->next_sibling()->firstChild();
				}
				$firstfood = null;
				$foodarray = array();				
				while($foodelement != null){
					usleep( 1000 );
					if($foodelement->plaintext != ""){
						echo $foodelement->plaintext."<br/>\n";
						if($firstfood != null){
							echo $firstfood." ".$foodelement->plaintext."<br/>\n";
							array_push($foodarray, $firstfood." ".$foodelement->plaintext);
							$firstfood = null;
						}
						else{
							$firstfood = $foodelement->plaintext;
						}
					}
					
					$foodelement = $foodelement->next_sibling();
				}
				
				$food = implode("<br/>", $foodarray);
				echo $food."<br/>\n";
				$diner->addDateFood(new DateFood($date, $food));
				
			}
		}
		addYearDiner($diner);
	}
	
	if($dinercode == "TARTU"){
		if(date("G") >= 9){
			$html = file_get_html("http://tartukohvik.ee");
			$fooditem = $html->find("td[class=daily_special_name]");
			$priceitem = $html->find("td[class=daily_special_price]");
			$resultitem = array();
		
			if($fooditem == null || $priceitem == null)
				throw new Exception("No data retrieved");
		
			foreach($fooditem as $elementkey => $element){
				usleep( 1000 );
				if($element == null || $priceitem[$elementkey] == null){
					continue;
				}
				array_push($resultitem, trim(preg_replace("/ +/", " ", $element->plaintext))." ".trim(preg_replace("/ +/", " ", $priceitem[$elementkey]->plaintext)));
			}
			$resultitem = implode("<br/>", $resultitem);
			echo $resultitem."<br/>\n";
			
			$oldDiner = readDiner( $dinercode );
			$newDiner = new Diner( $dinercode );
			$newDiner->addDateFood(new DateFood(date("m")."-".date("d"), $resultitem));
			addYearDiner($newDiner);
			if( hasNewFoods( $oldDiner, $newDiner ) ){
				$diner = $newDiner;
			}
		}
	}
	
	if($dinercode == "PANG"){
		
		$html = file_get_html("http://pang.ee");
		$item = $html->find("p[align=center]");
		$arrayitem = array();
		$infostart = false;
		$fooditem = null;
		$siteday = null;
		foreach($item as $element){
			usleep( 1000 );
			if($element == null){
				continue;
			}
			echo $element->plaintext."<br/>\n";
			if($infostart == true){
				if(preg_match("/\d+.-/", $element->plaintext) == 1){
					$fooditem = implode(" ja ", $arrayitem)." ".$element->plaintext;
					break;
				}
				array_push($arrayitem, utf8_encode($element->plaintext));
				continue;
			}
			if(preg_match("/T.nane.+pakkumine/", $element->plaintext) == 1){
				$siteday = EstDates::getWeekDayFromDayName($element->plaintext);
				
				if($siteday == null)
					throw new Exception("No data retrieved");
				
				echo "*** ".$siteday."****<br/>\n";
				$infostart = true;
			}
		}
		
		echo $fooditem."<br/>\n";
		if(date("D") == $siteday){
			$diner->addDateFood(new DateFood(date("m")."-".date("d"), $fooditem));
		}
		addYearDiner($diner);				
	}
	
	if($dinercode == "KAPRIIS"){
		
		$html = file_get_html("http://www.kapriis.ee");
		$item = $html->find("font[size=4] font[color=#ff6600]");
		$info = null;
		foreach($item as $element){
			usleep( 1000 );
			if($element == null){
				continue;
			}
			$infoCandidate = trim(preg_replace("/&nbsp;/", "", $element->innertext, 1));
			if(preg_match("/^P.*evapakkumine/", $infoCandidate) == 1){
				echo $infoCandidate.$bnl;
				if($info == null){
					$info = $infoCandidate;
				}
				else{
					$info = $info."<br/>".$infoCandidate;
				}
			}
		}
		echo $info."<br/>\n";
		
		$item = $html->find("td");
		$date = null;
		$firstDishTitle = null;
		$secondDishTitle= null;
		$infostarted = false;
		
		for($i = 0; $i < count( $item ); $i++ ){
			usleep( 1000 );
			if($item[$i] == null){
				continue;
			}
			$element = trim(str_ireplace("&nbsp;", " ", $item[$i]->plaintext));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if( $element == "PRAAD" && $infostarted == false ){
				$i++;
				if( $item[$i] != null ){
					$element = trim(str_ireplace("&nbsp;", " ", $item[$i]->plaintext));
					if( $element == "PASTA" ){
						$infostarted = true;
						continue;
					}
				}
				continue;
			}
			if( EstDates::getWeekDayFromDayName( $element ) != null && $infostarted == true ){
				$i++;
				if( $item[$i] != null ){
					$element = trim(str_ireplace("&nbsp;", " ", $item[$i]->plaintext));
					if( preg_match( "/\d+\.\d+\.\d+/", $element ) == 1 ){
						$date = date( "Y-m-d", strtotime( $element ) );
						echo "date: ".$date.$bnl;
						if( $item[$i+1] != null ){
							$i++;
							$element = trim(str_ireplace("&nbsp;", " ", $item[$i]->plaintext));
							$firstDishTitle = utf8_encode($element);
							echo "firstdish: ".$firstDishTitle.$bnl;
						}
						if( $item[$i+1] != null ){
							$i++;
							$element = trim(str_ireplace("&nbsp;", " ", $item[$i]->plaintext));
							$secondDishTitle = utf8_encode($element);
							echo "seconddish: ".$secondDishTitle.$bnl;
						}
						
						$break = "<br/>";
						if( $firstDishTitle == null || $secondDishTitle == null ){
							$break = null;
						}
						if( $firstDishTitle != null || $secondDishTitle != null ){
							$diner->addDateFood(new DateFood($date, $firstDishTitle.$break.$secondDishTitle, $info));
						}
					}
				}
			}
		}	
	}
	
	if($dinercode == "GLAM"){
		$html = file_get_html("http://glam.ee");
		
		if($html == null)
			throw new Exception("No data retrieved");
			
		$item = $html->find("div[id=content]0", 0);
		
		if($item == null){
			throw new Exception("No tags found");
		}
		
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		
		$previous = null;
		$infostarted = false;
		$info = null;
		$startdate = null;
		$enddate = null;
		$foodfound = false;
		$noFoodCount = 0;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
//			echo $element.$bnl;
			if(preg_match("/P&Auml;EVAPRAAD/", $element) == 1 && 
			preg_match("/\d\d.\d\d.*-.*\d\d.\d\d/", $previous) == 1){
				$startdatearray = array();
				$dateinfoarray = explode("-", $previous);
				$startdatearray[0] = substr(trim($dateinfoarray[0]), 3, 2);
				$startdatearray[1] = substr(trim($dateinfoarray[0]), 0, 2);
				if($startdatearray[1] != null && $startdatearray[0] != null){
					$startdate = $startdatearray[0]."-".$startdatearray[1];
					$startdate = getyear($startdate)."-".$startdate;
				}
				else{
					throw new Exception("Starddate not found");
				}
//				$enddatearray = array();
//				$enddatearray[0] = substr(trim($dateinfoarray[1]), 3, 2);
//				$enddatearray[1] = substr(trim($dateinfoarray[1]), 0, 2);
//				if($enddatearray[1] != null && $enddatearray[0] != null){
//					$enddate = $enddatearray[0]."-".$enddatearray[1];
//					$enddate = getyear($enddate)."-".$enddate;
//				}
//				else{
//					throw new Exception("Enddate not found");
//				}
				
				$info = $element;
				$infostarted = true;
				echo "infostarted = true".$bnl;
				echo "startdate: ".$startdate.$bnl;
				echo "enddate: ".$enddate.$bnl;
			}
			if($infostarted){
				$weekday = null;
				$weekday = EstDates::getWeekdayFromDayName($element);
				if($element != null && $weekday != null){
					$food = preg_replace("/^\S+\s+/", "", $element);
					echo "food: ".$food.$bnl;
					$epochTime = strtotime($weekday." ".$startdate);
					$date = date("Y", $epochTime)."-".date("m", $epochTime)."-".date("d", $epochTime);
					echo "date: ".$date.$bnl;
					$diner->addDateFood(new DateFood($date, $food, $info));
					$foodfound = true;
					$noFoodCount = 0;
				}
				if($element == null && $foodfound){
					$noFoodCount++;
				}
			}
			if($element != null){
				$previous = $element;
			}
			if($noFoodCount > 3){
				break;
			}
		}
		echo $bnl;
	}
	
	if($dinercode == "SUUDLEVAD"){
		$html = file_get_html("http://www.suudlevadtudengid.ee/index.php/page,1");
		
		if($html == null)
			throw new Exception("No data retrieved");
		
		$item = $html->find("div[class=eripakkumised]", 0);
		
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$date = null;
		$food = null;
		$noFoodCount = 0;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			$temp_date_info = preg_replace("/,\s*/", ", ", $element);
			$temp_date_info = preg_replace("/\.\s*/", ". ", $temp_date_info);
			$daystartarray = explode(" ", trim($temp_date_info));
			//echo "*".$daystartarray[0]."*".$daystartarray[1]."*".$daystartarray[2].$bnl;
			if(EstDates::getWeekdayFromDayName($daystartarray[0]) != null &&
				preg_match("/\d+/", $daystartarray[1], $days) == 1 &&
					EstDates::getMonthFromMonthName($daystartarray[2]) != null){
				echo $element.$bnl;
				// write previously found datefood
				if( $date != null && $food != null){
					echo "**Writing datefood :".$date.$bnl;
					$diner->addDateFood( new DateFood( $date, $food ) );
					$date = null;
					$food = null;
					$noFoodCount = 0;
				}
				$month = EstDates::getMonthFromMonthName($daystartarray[2]);
				$day = numToLeadingZero($days[0]);
				if($month != null && $day != 0){
					$date = $month."-".$day;
					$date = getYear($date)."-".$date;
					echo "month: ".$month." day: ".$day.$bnl;
					echo $date.$bnl;
					continue;
				}
			}
			if($date != null){
				if( $element == null && $food != null ){
					$noFoodCount++;
				}
				if($element != null){
					if( $noFoodCount <= 8 ){
						$noFoodCount = 0;
						if( preg_match( "/hinnad sisaldavad/", strtolower( $element ) ) == 1 ){
							// write previously found datefood
							if( $date != null && $food != null){
								echo "**Writing datefood :".$date.$bnl;
								$diner->addDateFood( new DateFood( $date, $food ) );
							}
							break;
						}
						$lastChar = substr( $food, strlen( $food ) - 1 );
						if( $food == null ){
							$food = $element;
						}
						elseif( $lastChar == "-" )
						{
							$food = $food."<br/>".$element;
						}
						else{
							$food = $food." ".$element;
						}
						echo "food: ".$food.$bnl;
						
					}
					if( $noFoodCount > 8 ){
						// write previously found datefood
						if( $date != null && $food != null){
							echo "**Writing datefood :".$date.$bnl;
							$diner->addDateFood( new DateFood( $date, $food ) );
							$date = null;
							$food = null;
							$noFoodCount = 0;
						}
					}
				}
			}
		}
	}
	
	if($dinercode == "KROOKS"){
		$html = file_get_html("http://www.krooks.ee/web/go.php?id=2&keel=ee");
		$item = $html->find("div[id=tekst]", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		
		// find dateAndFood rows
		$dateAndFood = null;
		$checkpointReached = false;
		$infostart = false;
		$noFoodCount = 0;
		$breakabale = false;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			echo $element.$bnl;
			if(preg_match("/p.+evapakkumine/",strtolower($element)) == 1){
				$checkpointReached = true;
				echo $element.$bnl;
			}
			if(preg_match("/\d+\.\d+/",$element) == 1 && $checkpointReached){
				$infostart = true;
			}
			if(preg_match("/hommikumen/", strtolower($element)) == 1){
				break;
			}
			if(preg_match("/kuupakkum/", strtolower($element)) == 1){
				break;
			}
			if($infostart && $element != null){
				if($dateAndFood != null){
					$breakable = true;
				}
				$dateAndFood = $dateAndFood." ".$element;
				$noFoodCount = 0;
				echo $element.$bnl;
				if(preg_match("/^\d/", $element) ==1 ){
					break;
				}
			}
			if($infostart && $element == null && $dateAndFood != null){
				if($noFoodCount < 3){
					$noFoodCount++;
					echo "nofoodcount: ".$noFoodCount.$bnl;
				}
			}
			if($noFoodCount >= 3){
				break;
			}
		}
		
		// extract date
		preg_match("/\d\d\.\d\d/", $dateAndFood, $datearray);
		preg_match_all("/\d\d/", $datearray[0], $arraydate);
		$date = $arraydate[0][1]."-".$arraydate[0][0];
		echo $date."<br/>\n";
		if(($arraydate[0][1] == "" && $arraydate[0][0] != "") ||
		($arraydate[0][1] != "" && $arraydate[0][0] == "")){
			throw new Exception("Invalid date data received");
		}
		
		// extract food
		$food = preg_replace("/.*\d+\.*\d+\.*\d+/", "", $dateAndFood, 1);
		$food = trim(str_ireplace("&nbsp;", " ", $food));
		echo $food."<br/>\n";
		if(preg_match("/^\d\S*/", $food, $price) == 1){
			$food = preg_replace("/^\d\S*/", "", $food);
			if($food != null){
				$food = $food." ".$price[0];
			}
			echo $food.$bnl;
		}
		
		// add to diner
		if($food != null && $date != null){
			$diner->addDateFood(new DateFood($date, $food));
			addYearDiner($diner);
		}
	}
	
	if($dinercode == "UT"){
		$baseurl = "kohvik.ut.ee";
		$url = "http://www.".$baseurl;
		$html = file_get_html($url);
		if( $html == null ){
			throw new Exception( "url ".$url." not found" );
		}
		$item = $html->find("a");
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		
		$links = array();
		foreach($item as $element){
			usleep( 1000 );
			if(preg_match("/siit/", $element->plaintext) == 1){
				echo $element->plaintext." ".$element->href.$bnl;
				if( preg_match( $baseurl, $element->href ) == 1 ){
					array_push($links, $element->href);
				}
				else{
					array_push($links, $url.$element->href);
				}
			}
		}
		
		$item = $html->find("body", 0);
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$checkpoint1 = false;
		$checkpoint2 = false;
		$info = null;
		foreach($item as $element){
			usleep( 1000 );
			//echo $element.$bnl;
			if(!$checkpoint1 && preg_match("/Igan.+dalase.*p.+evapraadide.*men.+leiad/", $element)){
				echo "checkpoint1 found: ".$element.$bnl;
				$checkpoint1 = true;
				continue;
			}
			if(!$checkpoint2 && $checkpoint1 && $element != null){
				if(preg_match("/^siit/", $element)){
					echo "checkpoint2 found: ".$element.$bnl;
					$checkpoint2 = true;
					continue;
				}
				else{
					echo "checkoint2 not found, no food info will be retrieved".$bnl;
					break;
				}
			}
			if($checkpoint2 && $element != null){
				$info = utf8_encode($element);
				echo "info: ".$info.$bnl;
				break;
			}
		}
		
		foreach($links as $linkelement){
			usleep( 1000 );
			$html = file_get_html($linkelement);
			if($html == null){
				continue;
			}
			$item = $html->find("body", 0);
			if( $item == null ){
				continue;
			}
			// most important splitter
			$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
			$menustart = false;
			$foundDate = false;
			$dayContent = 0;
			$date = null;
			$food = array();			
			foreach($item as $element){
				usleep( 1000 );
				// show all lines
				//echo $element.$bnl;
				if(preg_match("/p.+evapraadide men/", strtolower($element)) == 1){
					echo $element.$bnl;
					$menustart = true;
				}
				if(!$menustart){
					continue;
				}
				if(($weekday = EstDates::getWeekdayFromDayLetterUpper($element)) != null){
					if($date != null && count($food) > 0){
						$diner->addDateFood(new DateFood($date, implode("<br/>", $food), $info));
					}
					echo $weekday.$bnl;
					if(date("N") > date("N", strtotime($weekday))){
						$millis = strtotime("last ".$weekday);
					}
					else{
						$millis = strtotime($weekday);
					}
					$date = date( "Y", $millis)."-".date( "m", $millis)."-".date( "d", $millis);
					$food = array();
					echo $date.$bnl;
					$foundDate = true;
					$dayContent = 0;
					continue;
				}
				if($foundDate && $element == null){
					$dayContent++;
					continue;
				}
				if($foundDate && $dayContent <= 6 && $element != null){
					$dayContent = 0;
					$currentfood = trim(str_ireplace("&nbsp;", " ", utf8_encode(str_replace("\xA0", " ", $element))));
					if( $currentfood != "" ){
						array_push($food, $currentfood);
						echo "food: ".$currentfood.$bnl;
					}
					continue;
				}
				if($dayContent > 10){
					if($date != null && count($food) > 0){
						$diner->addDateFood(new DateFood($date, implode("<br/>", $food), $info));
					}
					break;
				}
			}
			if($menustart){
				break;
			}
		}
		cleandiner($diner);
		$readDiner = readDiner($dinercode);
		$readDateFoods = $readDiner->getDateFoods();
		$oldDataCount = 0;
		if(count($readDateFoods) == count($diner->getDateFoods())){
			foreach($diner->getDateFoods() as $dateFoodKey => $dateFood){
				usleep( 1000 );
				if($dateFood->getFood() == $readDateFoods[$dateFoodKey]->getFood() && 
					$dateFood->getDate() != $readDateFoods[$dateFoodKey]->getDate()){
					$oldDataCount++;
				}
			}
			if(count($readDateFoods) == $oldDataCount){
				$diner = new Diner($dinercode);
				echo "Not updating data, new information not available".$bnl;
			}
		}
	}
	
	if($dinercode == "ENTRI"){
		$url = "http://www.entri.ee";
		$html = file_get_html($url);
		$item = $html->find("body", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		
		$date = null;
		$food = null;
		$noFoodCount = 0;
		$infostarted = false;
		$monthDayCandidate = null;
		$month = null;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if( $element != null ){
				if( !$infostarted ){
					if( $month == null && $date == null ){
						if( preg_match( "/\d\d?/", $element, $temp ) == 1 ){
							$monthDayCandidate = $temp[0];
							echo "monthdaycandiadte :".$monthDayCandidate.$bnl;
						}
					}
					$month = null;
					$month = EstDates::getMonthFromMonthName($element);
					if( $monthDayCandidate != null && $month != null && $date == null){
						$date = $month."-".$monthDayCandidate;
						$date = getYear($date)."-".$date;
						echo "dateinfo: ".$element.", ".$month.$bnl;
						echo "date :".$date.$bnl;
						continue;
					}
					//				elseif( preg_replace("/\d\d.+\S/", $element, $element) == $element && $month != null && $date == null ){
					//					preg_match("/\d+/", $element, $day);
					//					$date = $month."-".$day[0];
					//					if( $day[0] == null ){
					//						throw new Exception("Monthday not found");
					//					}
					//					$date = getYear($date)."-".$date;
					//					echo "dateinfo: ".$element.", ".$month.$bnl;
					//					echo "date :".$date.$bnl;
					//				}
					if( $date != null ){
						if( preg_match("/kell/", strtolower($element)) == 1 ){
							$infostarted = true;
							echo "infostarted = true".$bnl;
						}
						else{
							$date = null;
							$monthDayCandidate = null;
							$month = null;
						}
						continue;
						
					}
				}
			}
			if($infostarted){
				if($element != null){
					$noFoodCount = 0;
					if(preg_match("/^\d+\.\d/", $element) == 1){
						$food = $food." ".$element;
					}
					else{
						if($food == null){
							$food = $element;
						}
						else{
							$food = $food."<br/>".$element;
						}
					}
					echo "food: ".$food.$bnl.$bnl;
				}
				if($element == null && $food != null){
					$noFoodCount++;
				}
			}
			if($noFoodCount > 10){
				break;
			}
		}
		$diner->addDateFood(new DateFood($date, $food));
		echo "datefood added :".$date." ".$food.$bnl;
	}
	
	if($dinercode == "TRUFFE"){
		$url = "http://truffe.ee/main.php";
		$html = file_get_html($url);
		$item = $html->find("body", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		
		// most important splitter
		$tempitem = $item->innertext();
		$tempitem2 = preg_split("/(<(.*?)>)/", $tempitem, null);
		$item = array();
		foreach( $tempitem2 as $element ){
			usleep( 1000 );
			$itemarray = preg_split("/\s(?=[ETKNR]\s*:)/", $element, null);
			foreach( $itemarray as $itemarrayelement ){
				usleep( 1000 );
				array_push( $item, $itemarrayelement );
			}
		}
		$infostart = false;
		$noFoodCount = 0;
		$date = null;
		$food = null;
		$newDiner = new Diner($dinercode);
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if(!$infostart && preg_match("/p.+evapraed/", strtolower($element)) == 1){
				$infostart = true;
				echo $element.$bnl;
				echo "infostart = true".$bnl;
				continue;
			}
			if($infostart){
				if($element != null){
					if(preg_match("/^.(?=[\s:])/", $element, $tempweekday) == 0){
						break;
					}
					$weekday = EstDates::getWeekdayFromDayLetterUpper($tempweekday[0]);
					echo $element.$bnl;
					echo "weekday: ".$weekday.$bnl;
					if($weekday != null){
						if(preg_match("/p.+evapakkumine/", strtolower($element)) == 1){
							break;
						}
						$date = getDateFromWeekday($weekday);
						$food = preg_replace("/^.[\s:]+/", "", $element);
						echo "date: ".$date.$bnl;
						echo "food: ".$food.$bnl;
						$newDiner->addDateFood(new DateFood($date, $food));						
					}
				}
				if($element == null && $date != null){
					$noFoodCount++;
				}
			}
			if(date("D", strtotime($date)) != "Fri"){
				if($noFoodCount >= 2 ){
					break;
				}
			}
			else{
				if($noFoodCount >= 1 ){
					break;
				}
			}
		}
		$oldDiner = readDiner($dinercode);
		if(hasNewFoods($oldDiner, $newDiner)){
			$diner = $newDiner;
		}
	}
	
	if($dinercode == "BARCLAY"){
		$url = "http://www.restoranbarclay.ee/";
		$html = file_get_html($url);
		$item = $html->find("div[id=content]", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", removeEmptyTags($item->innertext()), null);
		
		$infoStarted = false;
		$infoArray = array();
		$noInfoCount = 0;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if(preg_match("/p.+evamen.*hinnad/", strtolower($element)) == 1){
				echo "price info start true".$bnl;
				$infoStarted = true;
				continue;
			}
			if(preg_match("/^supp/", strtolower($element)) == 1){
				echo "info: ".$element.$bnl;
				array_push($infoArray, $element);
				$noInfoCount = 0;
			}
			if(preg_match("/^praad/", strtolower($element)) == 1){
				echo "info: ".$element.$bnl;
				array_push($infoArray, $element);
				$noInfoCount = 0;
			}
			if(preg_match("/^dessert/", strtolower($element)) == 1){
				echo "info: ".$element.$bnl;
				array_push($infoArray, $element);
				$noInfoCount = 0;
			}
			if($element == null && count($infoArray) > 0){
				$noInfoCount++;
			}
			if($noInfoCount > 4){
				break;
			}
		}
		
		$info = null;
		if(count($infoArray) > 0){
			$info = implode("<br/>", $infoArray);
		}
		
		$noFoodCount = 0;
		$date = null;
		$food = null;
		$dateFoodAdded = false;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			};
			if($element != null){
				if(preg_match("/^\d\d?\.\d\d?/", $element, $temp) == 1){
					if(!$dateFoodAdded && $food != null){
						// add food
						echo "dateFood added".$bnl;
						$diner->addDateFood(new DateFood($date, $food, $info));
						// added food
						$dateFoodAdded = true;
						$noFoodCount = 0;
						$food = null;
						$date = null;
						// add food
					}
					echo $element.$bnl;
					echo "date found: ";
					$temp = explode(".",$element);
					$date = $temp[1]."-".$temp[0];
					echo $date." ";
					$date = getYear($date)."-".$date;
					echo $date.$bnl;
					$noFoodCount = 0;
				}
				elseif($dateFoodAdded && $date == null){
					break;
				}
			}
			if($element == null && $date != null && !$dateFoodAdded){
				$noFoodCount++;
			}
			if($date != null && $element != null && $noFoodCount <= 31){
				if(preg_match("/men.*/", strtolower($element)) == 1){
					$noFoodCount = 1000;
				}
				else{
					$noFoodCount = 0;
					$dateFoodAdded = false;
					if($food == null){
						$food = preg_replace("/^\d\d?\.\d\d?/", "", $element);
						$food = preg_replace("/\s*-\s*/", "", $food);
					}
					else{
						$food = $food.$element;
					}
					echo "food: ".$food.$bnl;
				}
			}
			if($noFoodCount > 31 && !$dateFoodAdded){
				// add food
				echo "dateFood added".$bnl;
				$diner->addDateFood(new DateFood($date, $food, $info));
				// added food
				$dateFoodAdded = true;
				$noFoodCount = 0;
				$food = null;
				$date = null;
				// add food
			}				
		}
	}
	
	if($dinercode == "ATLANTIS"){
		$url = "http://www.atlantis.ee/?sisu=tekst&mid=3&lang=est";
		$html = file_get_html($url);
		$item = $html->find("div[class=parempool]", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		
		$infoStarted = false;
		$foodInfoStarted = false;
		$infoArray = array();
		$date = null;
		$food = null;
		$info = null;
		$tempDiner = new Diner($dinercode);
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if(preg_match("/restorani p.+evapakkumine:/", strtolower($element)) == 1 &&
			$infoStarted == false){
				echo $element.$bnl;
				echo "infostarted = true";
				$infoStarted = true;
				continue;
			}			
			if($infoStarted && $element != null){
				if(preg_match("/^\d+\.\d+/", $element, $temparray) == 1){
					echo $element.$bnl;
					$foodInfoStarted = true;
					$temp = explode(".", $temparray[0]);
					$date = $temp[1]."-".$temp[0];
					echo "date: ".$date.$bnl;
					$date = getYear($date)."-".$date;
					echo "date: ".$date.$bnl;
					$food = preg_replace("/^\d+\.\d+/", "", $element);
					echo "food: ".$food.$bnl;
					$tempDiner->addDateFood(new DateFood($date, $food));	
				}
				else{
					if(preg_match("/komplekt/", $element) == 1){
						array_push($infoArray, $element);
						echo "info: ".$element.$bnl;
					}
				}
			}
		}
		
		$info = implode("<br/>", $infoArray);
		foreach($tempDiner->getDateFoods() as $element){
			usleep( 1000 );
			$food = $element->getFood();
			$date = $element->getDate();
			if($food != null && $date != null){
				$diner->addDateFood(new DateFood($date, $food, $info));
			}
		}
	}
	
	if($dinercode == "KOTKA"){
		$url = "http://www.kotkakelder.ee";
		$html = file_get_html($url);
		$item = $html->find("div[id=right]", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		
		$previous = null;
		$infostarted = false;
		$date = null;
		$foodarray = array();
		$prev_food = null;
		foreach($item as $element){
			usleep( 1000 );
			$element = str_ireplace("&nbsp;", " ", str_replace("\xC2\xA0", " ", $element));
			$element = trim(preg_replace("/\s+/"," ",$element));
//			echo  $element.$bnl;
			if($element != null && preg_match("/^hind/", strtolower($element)) == 1 &&
			preg_match("/^p.+evapakkumine/", strtolower($previous)) == 1 && !$infostarted){
				echo $element.$bnl;
				$infostarted = true;
				echo "infostarted = true".$bnl;
				continue;
			}
			if($element != null && !$infostarted){
				$previous = $element;
			}
			if($infostarted && $element != null &&
			preg_match("/^\d+\.\d+/", $element, $temparray) == 1){
				echo $element.$bnl;
				$temp = explode(".", $temparray[0]);
				$date = $temp[1]."-".$temp[0];
				echo "date: ".$date.$bnl;
				$date = getYear($date)."-".$date;
				echo "date: ".$date.$bnl;
				continue;
			}
			if($element != null && $date != null){
				if($prev_food != null && preg_match_all("/\d+/", $element, $temparray, PREG_OFFSET_CAPTURE) > 0){
					$match_length = strlen($temparray[0][count($temparray[0])-1][0]);
					$match_offset = $temparray[0][count($temparray[0])-1][1];
					$position_end = strlen($element) - $match_length - $match_offset;
					if($position_end < 4){
						echo "food: ".$prev_food." ".$element.$bnl;
						array_push($foodarray, $prev_food." ".$element);
						$prev_food = null;
						continue;
					}
					else{
						break;
					}
				}
				if(preg_match_all("/\d+/", $element, $temparray, PREG_OFFSET_CAPTURE) == 1){
					$match_length = strlen($temparray[0][0][0]);
					$match_offset = $temparray[0][0][1];
					$position_end = strlen($element) - $match_length - $match_offset;
					if($position_end < 4){
						array_push($foodarray, $element);
						$prev_food = null;
						echo "food: ".$element.$bnl;
						continue;
					}
				}
				if(preg_match("/\d+/", $element) == 0){
					$prev_food = $element;
					echo "previous_food: ".$element.$bnl;
					continue;
				}
				else{
					break;
				}
			}
		}
		$diner->addDateFood(new DateFood($date, implode("<br/>", $foodarray)));
	}
	
	if($dinercode == "NOIR"){
		$url = "http://www.cafenoir.ee/noir/";
		$html = file_get_html($url);
		$item = $html->find("body", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// remove empty tags
		$result = $item->innertext();
		// $result = removeEmptyTags($item->innertext());
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $result, null);
		$date = null;
		$noFoodCount = 0;
		$foodArray = array();
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if($date == null && preg_match("/p.+evapakkumine\s+\d+/", strtolower($element)) == 1){
				echo $element.$bnl;
				if(preg_match("/\d+\.\d+\.\d+/", $element, $datedata) == 1){
					$datearray = explode(".", $datedata[0]);
					if(count($datearray) >= 3){
						$date = "20".$datearray[2]."-".$datearray[1]."-".$datearray[0];
						echo "date: ".$date.$bnl;
						continue;
					}
					else{
						throw new Exception("Unexpected date fromat");
					}
				}
				else
				{
					throw new Exception("Unexpected date fromat");
				}
			}
			if($date != null){
				if($element != null){
					if($noFoodCount <= 3){
						array_push($foodArray, $element);
						echo "food: ".$element.$bnl;
						$noFoodCount = 0;
					}
				}
				if($element == null && count($foodArray) > 0){
					$noFoodCount++;
				}
			}
			if($noFoodCount > 3){
				break;
			}
		}
		$food = implode("<br/>", $foodArray);
		$food = preg_replace( "/\<br\/\>(?=\d)/", " ", $food );
		echo $bnl."allfoods: ".$food.$bnl;
		$diner->addDateFood(new DateFood($date, $food));
	}
	
	if($dinercode == "AMPS"){
		$url = "http://www.ampsnaps.infopluss.ee/";
		$html = file_get_html($url);
		$item = $html->find("body", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$noFoodCount = 0;
		$infostart = false;
		$foodArray = array();
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if( preg_match( "/^P.+EVAPAKKUMINE/", $element ) == 1 && $infostart == false ){
				echo $element.$bnl;
				$infostart = true;
				echo "infostart = true".$bnl;
				continue;
			}
			if( $infostart == true ){
				if( $noFoodCount > 6 || preg_match( "/^KAALUJ.+LGIJATELE/", $element ) == 1 ){
					$date = date( "Y-m-d" );
					$food = implode( "<br/>", $foodArray );
					$newDiner = new Diner( $dinercode );
					$newDiner->addDateFood( new DateFood( $date, $food ) );
					$oldDiner = readDiner( $dinercode );
					if( hasNewFoods( $oldDiner, $newDiner ) ){
						$diner = $newDiner;
						echo "datefood added :".$date." ".$food.$bnl;
					}
					break;
				}
				if( $element != null ){
					if( $noFoodCount <= 6 ){
						$tempFood = substr( $element, 0, 1 ).mb_strtolower( substr( $element, 1 ), "UTF-8" ); 
						array_push( $foodArray, $tempFood );
						$noFoodCount = 0;
						echo "food: ".$tempFood.$bnl.$bnl;
						continue;
					}
				}
				elseif( count( $foodArray ) > 0 ){
					$noFoodCount++;
				}
			}
		}
	}
	
	if($dinercode == "ASIANCHEF"){
		$url = "http://asianchef.ee/index.php?option=com_content&view=article&id=71%3".
				"Aasian-chef-paeevapakkumised&catid=35%3Amenueue&Itemid=2&lang=et";
		$html = file_get_html($url);
		$item = $html->find("body", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$infostart = false;
		$date = null;
		$food = null;
		$info = null;
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if( preg_match( "/Asian Chef p.+evapakkumised/", $element ) == 1 && $infostart == false ){
				echo $element.$bnl;
				$infostart = true;
				echo "infostart = true".$bnl;
				continue;
			}
			if( $infostart ){
				if( $element != null ){
					if( $date == null ){
						if( preg_match( "/\d+\.\d+/", $element, $tempdate ) ){
							echo $element.$bnl;
							$tempdate = explode( ".", $tempdate[0] );
							$date = $tempdate[1]."-".$tempdate[0];
							$date = getYear( $date )."-".$date;
							echo "date: ".$date.$bnl;
							continue;
						}
						if( preg_match( "/^Hind/", $element, $tempindfo ) ){
							$info = $element;
							echo "info: ".$info.$bnl;
							break;
						}
					}
					if( $date != null ){
						echo $element.$bnl;
						$food = mb_strtoupper( substr( $element, 0, 1), "UTF-8" ).substr( $element, 1 );
						echo "food: ".$food.$bnl;
						$diner->addDateFood( new DateFood( $date, $food ) );
						echo "datefood added".$bnl;
						$date = null;
						$food = null;
						continue;
					}
					

				}
			}
		}
		$diner->addInfo( $info );
	}
	
	if($dinercode == "JAMJAM"){
		$url = "http://www.jamjam.ee/?page=6";
		$html = file_get_html($url);
		$item = $html->find("body", 0);
		
		if($item == null){
			throw new Exception("Specified tag not found");
		}
		// most important splitter
		$item = preg_split("/(<(.*?)>)/", $item->innertext(), null);
		$foodInfoStarted = false;
		$infoStarted = false;
		$infoArray = array();
		$date = null;
		$food = null;
		$info = null;
		$weekDay = null;
		$noFoodCount = 0;
		$tempDiner = new Diner($dinercode);
		foreach($item as $element){
			usleep( 1000 );
			$element = trim(str_ireplace("&nbsp;", " ", $element));
			if($debug == $dinercode){
				echo $element.$bnl;
			}
			if(preg_match("/\d+\s*[.-]{1}\s*\d+\s*[.-]{1}\s*\d+\s*[.-]{1}\s*\d+/", strtolower($element)) == 1 &&
			$infoStarted == false){
				echo $element.$bnl;
				echo "infostarted = true".$bnl;
				$infoStarted = true;
				continue;
			}			
			if( $infoStarted && $element != null && $weekDay == null ){
				if( ( $weekDay = EstDates::getWeekdayFromDayNameFirst( $element ) ) != null ){
					$foodInfoStarted = true;
					$date = getDateFromWeekDay( $weekDay );
					echo "date: ".$date.$bnl;
					echo $element.$bnl;
				}
				elseif( !$foodInfoStarted ){
					$infoStarted = false;
					echo "infostarted = false".$bnl;
					$date = null;
				}
				elseif( count( $infoArray ) < 2 ){
					array_push( $infoArray, $element );
					echo "info: ".$element.$bnl;
				}
				continue;
			}
			if( $foodInfoStarted && $date != null ){		
				if(  $element != null ){
					$food = $element;
					echo "food: ".$food.$bnl;
					$tempDiner->addDateFood(new DateFood($date, $food));
					$date = null;
					$food = null;
					$weekDay = null;
					continue;
				}
			}	
		}	
		$info = implode("<br/>", $infoArray);
		$food = null;
		$date = null;
		$newDiner = new Diner( $dinercode );
		foreach($tempDiner->getDateFoods() as $element){
			usleep( 1000 );
			$food = $element->getFood();
			$date = $element->getDate();
			if($food != null && $date != null){
				$newDiner->addDateFood(new DateFood($date, $food, $info));
			}
		}
		$oldDiner = readDiner( $dinercode );
		if( hasNewFoods( $oldDiner, $newDiner ) ){
			$diner = $newDiner;
		}
		
	}
	
	// clean away all extra spaces
	cleanDiner($diner);
	return $diner;
}

function numToLeadingZero($num){
	$result = $num;
	if(strlen($num) == 1)
		$result = "0".$num;
	return $result; 
}

function array_remove_key($key)
{
  $args  = func_get_args();
  return array_diff_key($args[0],array_flip(array_slice($args,1)));
}

function cleanDiner($diner){
	$resultDiner = new Diner($diner->getCode());
	foreach($diner->getDateFoods() as $element){	
		usleep( 1000 );
		$food = trim(preg_replace("/\s+/"," ",str_ireplace("&nbsp;", " ", $element->getFood())));
		$element->setFood($food);
		$info = trim(preg_replace("/\s+/"," ",str_ireplace("&nbsp;", " ", $element->getInfo())));
		$element->setInfo($info);
	
		$resultDiner->addDateFood($element);
	}
	$diner = $resultDiner;
}

function addYearDiner($diner){
	$resultDiner = new Diner($diner->getCode());
	foreach($diner->getDateFoods() as $element){
		usleep( 1000 );
		$element->setDate(getYear($element->getDate())."-".$element->getDate());
		$resultDiner->addDateFood($element);
	}
	$diner = $resultDiner;	
}

function removeEmptyTags($input){
		$result = $input;
		$input = null;
		while($input != $result){
			$input = $result;
			$result = preg_replace('#<\s*(\w+)[^>]*>(\s*(?i)&nbsp;(?-i)\s*)*<\s*/\s*\1\s*>#im', "", $input);
			usleep( 1000 );
		}
		return $result;	
}

function getDateFromWeekday($weekday){
	if(date("N") > date("N", strtotime($weekday))){
		$millis = strtotime("last ".$weekday);
	}
	else{
		$millis = strtotime($weekday);
	}
	$date = date( "Y", $millis)."-".date( "m", $millis)."-".date( "d", $millis);
	return $date;
}

function hasNewFoods($oldDiner, $newDiner){
	if( $oldDiner == null ){
		return true;
	}
	cleandiner($newDiner);
	$oldDateFoods = $oldDiner->getDateFoods();
	$oldDataCount = 0;
	if(count($oldDateFoods) == count($newDiner->getDateFoods())){
		foreach($newDiner->getDateFoods() as $dateFoodKey => $dateFood){
			usleep( 1000 );
			if($dateFood->getFood() == $oldDateFoods[$dateFoodKey]->getFood() && 
				$dateFood->getDate() != $oldDateFoods[$dateFoodKey]->getDate()){
				$oldDataCount++;
			}
		}
		if(count($oldDateFoods) == $oldDataCount){
			echo "Not updating data, new information not available\n<br/>";
			return false;
		}
	}
	return true;	
}

function fatalErrorShutdownHandler(){
	echo "Fatal error catcher working..."."<br/>\n";
	global $updaterstarted, $starttime, $timeExceeded;
	if( $updaterstarted == true && $timeExceeded == false ){
		main();
	}
	else{
		$exectime = time() - $starttime;
		echo "script executiontime: ".$exectime." seconds"."<br/>\n";
	}
}
?>