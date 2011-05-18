<?php
function br2nl( $string ){
	 $result = str_replace("<br />","",$string);
	 $result = str_replace("<br/>","",$result);
	 return $result;
}

function mb_ucfirst($str, $encoding){ 
    $str[0] = mb_strtoupper($str[0], $encoding); 
    return $str; 
} 


function encrypt($sData, $sKey='mysecretkey'){
    $sResult = '';
    for($i=0;$i<strlen($sData);$i++){
        $sChar    = substr($sData, $i, 1);
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
        $sChar    = chr(ord($sChar) + ord($sKeyChar));
        $sResult .= $sChar;
    }
    return encode_base64($sResult);
}

function decrypt($sData, $sKey='mysecretkey'){
    $sResult = '';
    $sData   = decode_base64($sData);
    for($i=0;$i<strlen($sData);$i++){
        $sChar    = substr($sData, $i, 1);
        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
        $sChar    = chr(ord($sChar) - ord($sKeyChar));
        $sResult .= $sChar;
    }
    return $sResult;
}


function encode_base64($sData){
    $sBase64 = base64_encode($sData);
    return strtr($sBase64, '+/', '-_');
}

function decode_base64($sData){
    $sBase64 = strtr($sData, '-_', '+/');
    return base64_decode($sBase64);
}  

function fixEncoding($in_str)
{
	if( is_utf8( $in_str ) ){
		return $in_str;
	}
	else{
		return utf8_encode($in_str);
	}
} // fixEncoding

function println( $string ){
	echo $string."<br/>\n";
}

function numToLeadingZero($num){
	$result = $num;
	if(strlen($num) == 1)
	$result = "0".$num;
	return $result;
}

function getHtml( $url ){
	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, $url );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 5 );
	curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $curl, CURLOPT_MAXREDIRS, 20 );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	$str = curl_exec($curl);
	$html = null;
	if( $str !== false ){
		$html = str_get_html( $str );
	}
	curl_close( $curl );
	return $html;
}

function cleanString( $string ){
	return trim( preg_replace("/\s+/"," ",str_replace("\xC2\xA0", " ", str_ireplace("&nbsp;", " ", $string))) );
}

// Returns true if $string is valid UTF-8 and false otherwise.
function is_utf8($string) {
  
   // From http://w3.org/International/questions/qa-forms-utf-8.html
   return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
   )*$%xs', $string);
  
} // function is_utf8

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
		if(preg_match("/(?i)märts(?-i)/", $monthname))
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
		if(preg_match("/(?i)septmeber(?-i)/", $monthname))
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
