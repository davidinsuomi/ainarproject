<?php
if( !session_start() ){
	die( "Error, can not start session" );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Päevaprae info sisestamine</title>
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
		<META HTTP-EQUIV="Expires" CONTENT="-1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="submitstyles.css?1" rel="stylesheet" type="text/css"/>
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<?php
	include_once("common.php");
	
	if( !empty( $_POST['password'] ) ){
		$_SESSION['password'] = $_POST['password'];
	}
	
	$password = $_SESSION['password'];
	
	$dinerCode = strtoupper( $_GET["code"] ); 
	
	// set session timeout period in seconds
	$inactive = 600;
	
	// mode definitions
	define("LOGIN", "login");
	define("PRE_LOGIN_ERROR", "preLoginError");
	define("MAIN", "main");
	
	// set the initial mode to LOGIN
	$mode = LOGIN;
	
	try{
		if( !isset( $dinerCode ) || $dinerCode == "" ){
			$error = "Söögikoht on määramata!";
			$mode = PRE_LOGIN_ERROR;
			throw new Exception();
		}
		
		$dinerDbPassword = getDinerDbPassword( $dinerCode );
		if( !isset( $dinerDbPassword ) ){
			$error = "Söögikoha info ei ole uuendatav.";
			$mode = PRE_LOGIN_ERROR;
			throw new Exception();
		} 
		
		if( !isset( $password ) ){
			$mode = LOGIN;
			throw new Exception();
		}
		
		if( $password != $dinerDbPassword ){
			$error = "Vale parool.";
			$mode = LOGIN;
			throw new Exception();
		}
		
		if(isset($_SESSION['timeout']) ) {
			$session_life = time() - $_SESSION['timeout'];
			if($session_life > $inactive){
				$mode = LOGIN;
				throw new Exception();
			}
		}
		
		$mode = MAIN;
		$_SESSION['timeout'] = time();
		
	} catch( Exception $e ){
		session_destroy();
	}
	
	renderBody();
		
	function getDinerDbPassword( $dinerCode ){
		mysql_connect(DBconnection::$dbhost, DBconnection::$dbuser, DBconnection::$dbpass);
		@mysql_select_db(DBconnection::$dbname) or die( "Unable to select database");

		$query = "select password from diners where code = '".$dinerCode."'";
		$queryresult = mysql_query($query);
		mysql_close();
		
		$password = null;
		
		if($row = mysql_fetch_row($queryresult)){
			$password = $row[0];
		}

		return $password;
	}
	
	function renderBody(){
		global $error, $dinerCode, $mode;
		
		if( $mode == LOGIN ){
			echo('
				<body onload="document.password_form.password.focus();">
			');
		} elseif( $mode == MAIN ){
			echo('
				<body class="main_body">
			');
		}

		if( isset( $error ) ){
			echo('
			<div class="error_text">'.$error.'</div>
			');
		}
		
		if( $mode == LOGIN ){
			echo('
			<div class="login_frame">
				<div class="login_password_text">'.ucfirst( strtolower( $dinerCode ) ).'. Parool:</div>
				<form name="password_form" method="post" action="submit.php?code='.strtolower( $dinerCode ).'">
					<input class="password" type="password" size="20" name="password">
				</form>
			</div>
			');
		} elseif( $mode == MAIN ){
			echo('
			<div class="navbar">
				<a class="navbar_link" href="submit.php?code='.strtolower( $dinerCode ).'&page=1">INFO SISESTAMINE</a>
				<a class="navbar_link" href="submit.php?code='.strtolower( $dinerCode ).'&page=2">PAROOLIVAHETUS</a>
			</div>
			');
			
			echo('
			<div class="content">
				<form name="save_info_form" method="post" action="submit.php?code='.strtolower( $dinerCode ).'">
					<input type="submit" size="20" class="submit_info_form" value="Salvesta kõik"/>
					<label class="food_area_label"">2010-15-01</label>
					<textarea name="food0" cols="40" rows="5"></textarea>
				</form>
			</div>
			');
		}
	
		echo('
		</body>
		');
	}
	?>
</html>