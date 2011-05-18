<?php
include_once("simple_html_dom.php");
include_once("classes.php");
include_once("conf.php");

set_error_handler('myErrorHandler');
register_shutdown_function('fatalErrorShutdownHandler');

foreach(getDinerCodes() as $element){
	echo $element."<br>\n";
	try{
		$diner = getDiner($element);
		writeDiner($diner);
	}
	catch(Exception $e){
		echo "<b>".$e."</b><br/>\n";
	}
	echo "<br/>\n";
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
		$query = "insert into datefoods (code, date, food, info) values('".$diner->getCode()."','".$element->getDate()."','".$element->getFood()."','".$element->getInfo()."')";
		mysql_query($query);
	}

	mysql_close();
}

function getDinerCodes(){
	mysql_connect(DBconnection::$dbhost, DBconnection::$dbuser, DBconnection::$dbpass);
	@mysql_select_db(DBconnection::$dbname) or die( "Unable to select database");
	
	$results = array();
	
	$query = "select code from diners";
	$queryresult = mysql_query($query);
	mysql_close();
	
	while($row = mysql_fetch_row($queryresult)){
		array_push($results, $row[0]);
	}
	
	return $results;
}

function getDiner($dinercode){
	$diner = new Diner($dinercode);
	//Break and New Line
	$bnl = "<br/>\n";
	
	if($dinercode == "PYSS"){
		$html = file_get_html("http://pyss.ee/?page=28");
		$item = $html->find("p", 0);
		$item = explode("<br />", $item->innertext);
		
		foreach($item as $element){
			$element = trim(strip_tags($element));
			preg_match("/\S+/", $element, $montharray);
			$month = EstDates::getMonthFromMonthShortName($montharray[0]);
			if($month != null){
				echo $element.$bnl;
				preg_match("/\d+/", $montharray[0], $dayarray);
				$day = numToLeadingZero($dayarray[0]);
				$datefood = new DateFood($month."-".$day);
				echo $datefood->getDate().$bnl;
				$food = substr($element, strlen($montharray[0]) + 1);
				echo $food.$bnl;
				$datefood->setFood($food);
				$diner->addDateFood($datefood);
			}
			
		}
	}
	
	if($dinercode == "PATTAYA" || $dinercode == "AURA"){
		$html = file_get_html("http://www.pattaya.ee/new/?D=31");
		$item = $html->find("div[class=hinnakiriLabel]");
		
		if($item == null)
			throw new Exception("No data retrieved");
			
		foreach($item as $element){
			echo $element->plaintext."<br/>\n";
			preg_match_all("/\d+/", $element->plaintext, $date);
			$date = $date[0][1]."-".$date[0][0];
			echo $date."<br/>\n";
			$food = preg_replace("/[A-Z], *\d+.\d+ */", "", $element->plaintext);
			$price = $element->parent()->next_sibling()->plaintext;
			$price = preg_match("/\d+.*/", $price, $pricearray);
			echo $food."<br/>\n";
			echo $pricearray[0]."<br/>\n";
			$price = $pricearray[0];
			$html = str_get_html($element->parent()->parent()->next_sibling()->innertext());
			$soupfoodarray = $html->find("text");
			echo $soupfoodarray[0]."<br/>\n";
			
			$soupfood = utf8_encode($soupfoodarray[0]);
			$food = utf8_encode($food);
			
			if($dinercode == "PATTAYA"){
				$diner->addDateFood(new DateFood($date, $food." ".$price."<br/>".$soupfood));
			}
			if($dinercode == "AURA"){
				$diner->addDateFood(new DateFood($date, $food." (38.-)"));
			}
		}
	}
	
	if($dinercode == "VILDE"){
		$html = file_get_html("http://www.vilde.ee/?page=23");
		$item = $html->find("p[class=sisu] strong", 0);
		
		if($item == null)
			throw new Exception("No data retrieved");
		
		$datesections = preg_split("/(<(.*?)>)/", $item, null, PREG_SPLIT_NO_EMPTY);
		$datesections = array_reverse($datesections);
		foreach($datesections as $element){
			echo "x ".$element."<br>\n";
		}
		
		$item = $datesections[0];
		preg_match("/\d+[\W\s]+\w+/", $item, $itemarray);
		echo $itemarray[0]."<br>\n";
		
		preg_match("/\d+/", $itemarray[0], $dayarray);
		echo $dayarray[0]."<br>\n";
		echo numToLeadingZero($dayarray[0])."<br>\n";
		$day = numToLeadingZero($dayarray[0]);
		foreach(EstDates::$abr as $elementKey => $element){
			if(preg_match("/(?i)".$element."(?-i)/", $itemarray[0]) == 1){
				$month = $elementKey;
				break;
			}
		}
		echo $month."<br>\n";
		
		$needleitem = str_get_html($html->find("p[class=sisu] strong", 0)->innertext)->find("text");
		$searchitem = str_get_html($html->find("p[class=sisu]", 0)->innertext)->find("text");
			
		foreach($needleitem as $needleelement){
			foreach($searchitem as $searchelementkey => $searchelement){
				if(strcmp($searchelement, $needleelement) == 0){
					array_splice($searchitem, $searchelementkey, 1);
				}
			}
		}
	
		$item = array();	
		echo count($searchitem)."<br>\n";	
		for($i = 0; $i < count($searchitem) - 3; $i++){
			array_push($item, $searchitem[$i]);
		}
		
		$item = implode("<br>", $item);
		echo $item;
			
		$diner->addDateFood(new DateFood($month."-".$day, $item));
	}
	
	if($dinercode == "PLACE"){
		
		$html = file_get_html("http://www.bcplace.ee/paevamenuu.html");
		$item = $html->find("div[class=date]");
		
		if($item == null)
			throw new Exception("No data retrieved");
			
		$info = mb_strtolower(utf8_encode($html->find("h2", 0)->innertext), "UTF-8");
		echo $info."<br/>";
	
		foreach($item as $superelement){
			echo $superelement->plaintext."<br>";
			preg_match("/\d+/", $superelement->plaintext, $itemarray);
			$day = numToLeadingZero($itemarray[0]);
			echo "day - ".$day."<br>";
			
			foreach(EstDates::$abr as $elementKey => $element){
				if(preg_match("/".$element."/", $superelement->plaintext) == 1){
					$month = numToLeadingZero($elementKey);
					break;
				}
			}
			echo "month - ".$month."<br/>";
			$food = trim(preg_replace("/ +/", " ", $superelement->next_sibling()->first_child()->innertext));
			$food = utf8_encode($food);
			$food = str_replace("\xC2\x9A", "&scaron;", $food);
			echo $food."<br>";
			
			$diner->addDateFood(new DateFood($month."-".$day, $food, $info));
		}
	}
	
	if($dinercode == "TREHV"){
		$html = file_get_html("http://www.trehv.ee/index.php?option=com_content&view=article&id=52&Itemid=63");
		$item = $html->find("h3");
		
		foreach($item as $element){
			echo $element->plaintext."<br/>\n";
			if(preg_match("/\d\d\.\d\d/", $element->plaintext) == 1){
				echo "** ";
				preg_match_all("/\d\d/", $element->plaintext, $datearray);
				
				$date = $datearray[0][1]."-".$datearray[0][0];
				echo $date."<br/>\n";
				$foodelement = $element->next_sibling()->firstChild();
				//tetet
				$element = null;
				$element->next_sibling()->firstChild();
				//testteet
				$firstfood = null;
				$foodarray = array();				
				while($foodelement != null){
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
				array_push($resultitem, trim(preg_replace("/ +/", " ", $element->plaintext))." ".trim(preg_replace("/ +/", " ", $priceitem[$elementkey]->plaintext)));
			}
			$resultitem = implode("<br/>", $resultitem);
			echo $resultitem."<br/>\n";
				
			$diner->addDateFood(new DateFood(date("m")."-".date("d"), $resultitem));
		}
	}
	
	if($dinercode == "PANG"){
		
		$html = file_get_html("http://pang.ee");
		$item = $html->find("p[align=center]");
		$arrayitem = array();
		$infostart = false;
		foreach($item as $element){
			echo $element->plaintext."<br/>\n";
			if($infostart == true){
				if(preg_match("/\d+.-/", $element->plaintext) == 1){
					$fooditem = implode(" ja ", $arrayitem)." ".$element->plaintext;
					break;
				}
				array_push($arrayitem, utf8_encode($element->plaintext));
				continue;
			}
			if(preg_match("/T.nane.+p.evapakkumine/", $element->plaintext) == 1){
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
	}
	
	if($dinercode == "KAPRIIS"){
		
		$html = file_get_html("http://www.kapriis.ee");
		$item = $html->find("font[size=4] font[color=#ff6600]");
		$info = trim(preg_replace("/&nbsp;/", "", $item[0]->innertext, 1));
		echo $info."<br/>\n";
		if($item[1] != null){
			echo $item[1]->plaintext."<br/>\n";
			$info = $info."<br/>".$item[1]->plaintext;
		}
		
		$item = $html->find("td font");
		$arrayitem = array();
		$infostarted = false;
		
		foreach($item as $elementkey => $element){
			if(EstDates::getWeekDayFromDayName($element->plaintext) != null){
				if($infostarted == false){
					$infostarted = true;
					$firstDishTitle = strtolower($item[$elementkey - 2]->plaintext);
					$secondDishTitle = strtolower($item[$elementkey - 1]->plaintext);
					echo $firstDishTitle." ".$secondDishTitle."<br/>\n";
				}
				
				$date = preg_replace("/[^0-9]+/", "-", $element->parent()->next_sibling()->plaintext);
				echo "**  ".$element->plaintext."<br/>\n";
				echo $date."<br/>\n";
				$date = substr($date, 3, 2)."-".substr($date, 0, 2);
				echo $date."<br/>\n";
				$firstDish = utf8_encode(str_ireplace("&nbsp;", "", trim($element->parent()->next_sibling()->next_sibling()->plaintext)));
				$secondDish = utf8_encode(str_ireplace("&nbsp;", "", trim($element->parent()->next_sibling()->next_sibling()->next_sibling()->plaintext)));
				$firstDish = trim($firstDish);
				$secondDish = trim($secondDish);
				echo $firstDish." ".$secondDish."<br/>\n";
				if($firstDish != "" || $secondDish != ""){
					/*
					$diner->addDateFood(new DateFood($date, $firstDishTitle.": ".$firstDish."<br/>".
					$secondDishTitle.": ".$secondDish, $info));
					*/
					$break = "<br/>";
					if( $firstDish == "" || $secondDish == "" ){
						$break = null;
					}
					$diner->addDateFood(new DateFood($date, $firstDish.$break.$secondDish, $info));			
				}
				
			}
		}
		if($infostarted == false)
			throw new Exception("No data retrieved");			
	}
	
	if($dinercode == "GLAM"){
		$html = file_get_html("http://glam.ee/index.php?module=Page&id=1");
		$item = $html->find("p");
		
		$infostart = false;
		$foods = array();
		foreach($item as $elementkey => $element){
			$text = trim($element->plaintext);
			if($infostart == true){
				if($text == ""){
					break;
				}
				echo $text."<br/>\n";
				$text = str_ireplace("&nbsp;", " ", $text);
				$resulttext = preg_replace("/ +/", " ", $text);
				echo $resulttext."<br/>\n";
				$texts = explode(" ", $resulttext);
				array_splice($texts, 0, 1);
				$food = implode(" ", $texts);
				echo $food."<br/>\n";
				array_push($foods, new DateFood(null, $food));
			}
			if($elementkey > 0 && preg_match("/P&Auml;EVAPRAAD/", $item[$elementkey - 1]) == 1 &&
			preg_match("/\d\d.\d\d.*-.*\d\d.\d\d/", $text) == 1){
				$infostart = true;
				$dateinfo = $text;
				$info = trim($item[$elementkey - 1]->plaintext);
				echo $info."<br/>\n";
				echo $dateinfo."<br/>\n";
			}
		}
		if($infostart == false){
			throw new Exception("No data retrieved");
		}
		$startdate = array();
		$dateinfoarray = explode("-", $dateinfo);
		$startdate[0] = substr(trim($dateinfoarray[0]), 3, 2);
		$startdate[1] = substr(trim($dateinfoarray[0]), 0, 2);
		echo $startdate[0]."-".$startdate[1]."<br/>\n";
		$enddate = array();
		$enddate[0] = substr(trim($dateinfoarray[1]), 3, 2);
		$enddate[1] = substr(trim($dateinfoarray[1]), 0, 2);
		echo $enddate[0]."-".$enddate[1]."<br/>\n";
		
		$foodcount = count($foods);
		for($i =$foodcount - 1; $i > -1; $i--){
			if($startdate[0] == $enddate[0]){
				$date = $startdate[0]."-".numToLeadingZero($startdate[1] + $i);
			}
			else{
				if($enddate[1] - ($foodcount - 1 - $i) > 0){
					$date = $enddate[0]."-".numToLeadingZero($enddate[1] - ($foodcount - 1 - $i));
				}
				else{
					$date = $startdate[0]."-".numToLeadingZero($startdate[1] + $i);
				}
			}
			echo $date."<br/>\n";
			$food = $foods[$i];
			$food->setDate($date);
			$food->setInfo(ucfirst(strtolower($info)));
			$diner->addDateFood($food);
		}
	}
	
	if($dinercode == "SUUDLEVAD"){
		$html = file_get_html("http://www.suudlevadtudengid.ee/index.php/page,1");
		
		if($html == null)
			throw new Exception("No data retrieved");
		
		$item = $html->find("p");
		
		$daystart = false;
		$foods = array();
		$dayfood = null;
		$currentfood = null;
		foreach($item as $element){
			$innerhtml = str_get_html($element->innertext());
			$inneritem = $innerhtml->find("text");
			if(strtolower(trim($element->plaintext)) == "&nbsp;" && $daystart == true){
				$daystart = false;
				$temp = substr_replace($dayfood, "**", strrpos($dayfood, ".-"), 2);
				$dayfood = substr_replace($dayfood, ".-<br/>", strrpos($temp, ".-"), 2);
				echo $temp.$bnl;
				$temp = $dayfood;
				$dayfood = preg_replace("/[\s]+,/", ",", $temp);
				echo $dayfood.$bnl;
				$currentfood->setFood($dayfood);
				$diner->addDateFood($currentfood);
				$dayfood = null;
				echo "*".$bnl;
			}
			foreach($inneritem as $innerelement){
				if($daystart == true){
					echo $innerelement.$bnl;
					$newfood = trim(str_ireplace("&nbsp;", " ", $innerelement));
					$dayfood = $dayfood." ".$newfood;
				}
				$daystartarray = explode(" ", trim($innerelement));
				//echo "*".$daystartarray[0]."*".$daystartarray[1]."*".$daystartarray[2].$bnl;
				if(EstDates::getWeekdayFromDayName($daystartarray[0]) != null &&
				preg_match("/\d+/", $daystartarray[1], $days) == 1 &&
				EstDates::getMonthFromMonthName($daystartarray[2]) != null){
					echo $innerelement.$bnl;
					$daystart = true;
					$month = EstDates::getMonthFromMonthName($daystartarray[2]);
					$currentfood = new DateFood($month."-".numToLeadingZero($days[0]));
					echo $currentfood->getDate().$bnl;
				}
			}
		}
		foreach($diner->getDateFoods() as $foodelement){
			echo $foodelement->getFood().$bnl;
		}
	}
	
	if($dinercode == "KROOKS"){
		$html = file_get_html("http://www.krooks.ee/web/go.php?id=2&keel=ee");
		$item = $html->find("span div div span span span span");
		
		
		
		foreach($item as $element){
			$currenttext = $element->plaintext;
			if(preg_match("/P&Auml;EVAPAKKUMINE/", $currenttext) == 1){
				echo "*** ".$currenttext."<br/>\n";
				preg_match("/\d\d\.\d\d/", $currenttext, $datearray);
				preg_match_all("/\d\d/", $datearray[0], $arraydate);
				$date = $arraydate[0][1]."-".$arraydate[0][0];
				echo $date."<br/>\n";
				if(($arraydate[0][1] == "" && $arraydate[0][0] != "") ||
				($arraydate[0][1] != "" && $arraydate[0][0] == "")){
					throw new Exception("Invalid date data received");
				}
				if($arraydate[0][1] == "" && $arraydate[0][0] == ""){
					break;
				}
				$textitem = preg_replace("/[\s\S]*\d\d\d\d */", "", $currenttext);
				echo $textitem."<br/>\n";
				preg_match_all("/[^\d]+\d\d.-/", $textitem, $foodarray);
				$foods = array();
				
				if($foodarray == null){
					throw new Exception("No data retrieved");
				}
				
				foreach($foodarray[0] as $foodelement){
					array_push($foods, trim($foodelement));
					echo $foodelement."<br>\n";
				}
				$food = implode("<br/>", $foods);
				$temp = $food;
				$food = str_ireplace("&nbsp;", "", $temp);
				$temp = $food;
				$food = preg_replace("/^ *-* */", "", $temp);
				echo $food."<br/>\n";
				$diner->addDateFood(new DateFood($date, $food));
			}
			
		}
	}	
	
	return $diner;
}


function fatalErrorShutdownHandler()
{
  $last_error = error_get_last();
  if ($last_error['type'] === E_ERROR) {
    // fatal error
    myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
}

function myErrorHandler($code, $message, $file, $line) {
	throw new Exception("probleem");
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
?>