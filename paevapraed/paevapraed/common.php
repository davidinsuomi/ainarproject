<?php
include_once("conf.php");

function connectToDb(){
	mysql_connect(DBconnection::$dbhost, DBconnection::$dbuser, DBconnection::$dbpass);
	@mysql_select_db(DBconnection::$dbname) or die( "Unable to select database");
	mysql_set_charset("utf8");
}

function readDiner($dinerCode){
	$diner = new Diner($dinerCode);
	connectToDb();
	
	$query = "select defaultinfo, password from diners where code = '".$dinerCode."'";
	$results = mysql_query($query);
	if( $row = mysql_fetch_array($results, MYSQL_ASSOC) ){
		$diner->setPassword( $row["password"] );
		$diner->setDefaultInfo( $row["defaultinfo"] );
	}
	
	$query = "select date, food, info from datefoods where code = '".$dinerCode."' order by date";
	$results = mysql_query($query);
	
	mysql_close();
	while($row = mysql_fetch_array($results, MYSQL_ASSOC)){
		$diner->addDateFood(new DateFood($row["date"], $row["food"], $row["info"]));
	}
	
	return $diner;
}

function writeDiner($diner){
	connectToDb();

	$query = "delete from datefoods where code='".$diner->getCode()."'";
	mysql_query($query);

	
	foreach($diner->getDateFoods() as $element){
		$query = "insert into datefoods (code, date, food, info) ".
				"values('".$diner->getCode()."','".$element->getDate()."','".$element->getFood()."','".$element->getInfo()."')";
		mysql_query($query);
	}
	
	$query = "update diners set defaultinfo='".$diner->getDefaultInfo()."', password='".$diner->getPassword().
			"' where code = '".$diner->getCode()."'";
	mysql_query($query);

	mysql_close();
}

?>