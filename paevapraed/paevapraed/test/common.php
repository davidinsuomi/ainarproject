<?php
function fixEncoding($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
} // fixEncoding


function getEstWeekday($day){
	if($day == "Mon")
		return "esmasp&auml;ev";
	if($day == "Tue")
		return "teisip&auml;ev";
	if($day == "Wed")
		return "kolmap&auml;ev";
	if($day == "Thu")
		return "neljap&auml;ev";
	if($day == "Fri")
		return "reede";
	if($day == "Sat")
		return "laup&auml;ev";
	if($day == "Sun")
		return "pühap&auml;ev";
	
	return null;
}

function getEstWeekdayLetter($day){
	if($day == "Mon")
		return "E";
	if($day == "Tue")
		return "T";
	if($day == "Wed")
		return "K";
	if($day == "Thu")
		return "N";
	if($day == "Fri")
		return "R";
	if($day == "Sat")
		return "L";
	if($day == "Sun")
		return "P";
	
	return null;
}

function getEstMonthName($monthnum){
	if($monthnum == 1)
		return "jaanuar";
	if($monthnum == 2)
		return "veebruar";
	if($monthnum == 3)
		return "m&auml;rts";
	if($monthnum == 4)
		return "aprill";
	if($monthnum == 5)
		return "mai";
	if($monthnum == 6)
		return "juuni";
	if($monthnum == 7)
		return "juuli";
	if($monthnum == 8)
		return "august";
	if($monthnum == 9)
		return "september";
	if($monthnum == 10)
		return "oktoober";
	if($monthnum == 11)
		return "november";
	if($monthnum == 12)
		return "detsember";
	
	return null;
}

function getYear($date){
	if(substr($date, 0, 2) == 1 && date("n") == 12){
		$year = date("Y") + 1;
	}
	elseif(substr($date, 0, 2) == 12 && date("n") == 1){
		$year = date("Y") - 1;
	}
	else{
		$year = date("Y");
	}
	return $year;
}

class EstDates{
	public static $abr = array(
		"01" => "jaanuar",
		"02" => "veebruar",
		"03" => "m&auml;rts",
		"04" => "april",
		"05" => "mai",
		"06" => "juuni",
		"07" => "juuli",
		"08" => "august",
		"09" => "septemb",
		"10" => "oktoob",
		"11" => "novemb",
		"12" => "detsemb"
	);
	
	public static function getMonthFromMonthName($monthname){
		if(preg_match("/(?i)jaanuar(?-i)/", $monthname))
			return "01";
		if(preg_match("/(?i)veebruar(?-i)/", $monthname))
			return "02";
		if(preg_match("/(?i)m&auml;rts(?-i)/", $monthname))
			return "03";
		if(preg_match("/(?i)m.rts(?-i)/", $monthname))
			return "03";
		if(preg_match("/(?i)april(?-i)/", $monthname))
			return "04";
		if(preg_match("/(?i)mai(?-i)/", $monthname))
			return "05";
		if(preg_match("/(?i)juuni(?-i)/", $monthname))
			return "06";
		if(preg_match("/(?i)juuli(?-i)/", $monthname))
			return "07";
		if(preg_match("/(?i)august(?-i)/", $monthname))
			return "08";
		if(preg_match("/(?i)septemb(?-i)/", $monthname))
			return "09";
		if(preg_match("/(?i)oktoob(?-i)/", $monthname))
			return "10";
		if(preg_match("/(?i)novemb(?-i)/", $monthname))
			return "11";
		if(preg_match("/(?i)detsemb(?-i)/", $monthname))
			return "12";
			
		return null;
	}
	
	public static function getMonthFromMonthShortName($monthname){
		if(preg_match("/(?i)jaan(?-i)/", $monthname))
			return "01";
		if(preg_match("/(?i)veebr(?-i)/", $monthname))
			return "02";
		if(preg_match("/(?i)m&auml;rts(?-i)/", $monthname))
			return "03";
		if(preg_match("/(?i)m.rts(?-i)/", $monthname))
			return "03";
		if(preg_match("/(?i)apr(?-i)/", $monthname))
			return "04";
		if(preg_match("/(?i)mai(?-i)/", $monthname))
			return "05";
		if(preg_match("/(?i)jun(?-i)/", $monthname))
			return "06";
		if(preg_match("/(?i)juun(?-i)/", $monthname))
			return "06";
		if(preg_match("/(?i)jul(?-i)/", $monthname))
			return "07";
		if(preg_match("/(?i)juul(?-i)/", $monthname))
			return "07";
		if(preg_match("/(?i)aug(?-i)/", $monthname))
			return "08";
		if(preg_match("/(?i)sept(?-i)/", $monthname))
			return "09";
		if(preg_match("/(?i)okt(?-i)/", $monthname))
			return "10";
		if(preg_match("/(?i)nov(?-i)/", $monthname))
			return "11";
		if(preg_match("/(?i)dets(?-i)/", $monthname))
			return "12";
			
		return null;
	}
	
	public static function getWeekdayFromDayName($dayname){
		
		if(preg_match("/(?i)esmasp(?-i)/", $dayname))
			return "Mon";
		if(preg_match("/(?i)teisip(?-i)/", $dayname))
			return "Tue";	
		if(preg_match("/(?i)kolmap(?-i)/", $dayname))
			return "Wed";
		if(preg_match("/(?i)neljap(?-i)/", $dayname))
			return "Thu";
		if(preg_match("/(?i)reede(?-i)/", $dayname))
			return "Fri";
		if(preg_match("/(?i)laup(?-i)/", $dayname))
			return "Sat";
		if(preg_match("/(?i)pühap(?-i)/", $dayname))
			return "Sun";
		 if(preg_match("/(?i)p&uuml;hap(?-i)/", $dayname))
			return "Sun";
		return null;
	}
	
	public static function getWeekdayFromDayNameFirst($dayname){
		
		if(preg_match("/^(?i)esmasp(?-i)/", $dayname))
			return "Mon";
		if(preg_match("/^(?i)teisip(?-i)/", $dayname))
			return "Tue";	
		if(preg_match("/^(?i)kolmap(?-i)/", $dayname))
			return "Wed";
		if(preg_match("/^(?i)neljap(?-i)/", $dayname))
			return "Thu";
		if(preg_match("/^(?i)reede(?-i)/", $dayname))
			return "Fri";
		if(preg_match("/^(?i)laup(?-i)/", $dayname))
			return "Sat";
		if(preg_match("/^(?i)pühap(?-i)/", $dayname))
			return "Sun";
		 if(preg_match("/^(?i)p&uuml;hap(?-i)/", $dayname))
			return "Sun";
		return null;
	}
	
	public static function getWeekdayFromDayLetterUpper($dayname){
		
		if($dayname == "E")
			return "Mon";
		if($dayname == "T")
			return "Tue";	
		if($dayname == "K")
			return "Wed";
		if($dayname == "N")
			return "Thu";
		if($dayname == "R")
			return "Fri";
		if($dayname == "L")
			return "Sat";
		if($dayname == "P")
			return "Sun";
		
		return null;
	}
}
?>
