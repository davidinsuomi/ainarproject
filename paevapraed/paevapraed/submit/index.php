<?php
// set session timeout period in seconds
$inactive = 2592000;

session_set_cookie_params( $inactive );
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
		<link href="css/submitstyles.css?1" rel="stylesheet" type="text/css"/>
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<?php
	include_once( "../common.php" );
	include_once( "../utils.php" );
	include_once( "../classes.php" );
	
	error_reporting(E_ALL);
	
	// obfuscation string for password
	define( "OBFUSCATION", "sdf23r23rwef23kl" );
	
	// obfuscation string for code encoding
	define( "CODESALT", "df76d7e7f8df8" );
	
	// define modes
	define( "LOGIN", "login" );
	define( "PRE_LOGIN_ERROR", "preLoginError" );
	define( "MAIN_ENTER_INFO", "p1" );
	define( "MAIN_CHANGE_PASSWORD", "p2" );
	define( "LOGOUT", "p3" );
	
	// define actions
	define( "UPDATE_FOOD_INFO", "updateFoodInfo" );
	define( "CHANGE_PASSWORD", "changePassword" );
	
	// define number of datefood days
	define( "DATEFOOD_COUNT", 35 );
		
	$registry = new Registry();
	// set the initial mode to LOGIN
	$registry->mode = LOGIN;
	
	if( !empty( $_GET["code"] ) ){
		$dinerCode = decrypt( $_GET["code"], CODESALT );
	}
	
	if( isset( $_SESSION['code'] ) ){
		if( $_SESSION['code'] != $dinerCode ){
			session_unset();
		}
	}
	
	if( !empty( $_POST['password'] ) ){
		$_SESSION['password'] = md5( $_POST['password'].OBFUSCATION );
	}
	if( isset( $_SESSION['password'] ) ){
		$password = $_SESSION['password'];
	}
	
	try{	
		if( empty( $dinerCode ) ){
			$registry->error = "Söögikoht on määramata!";
			$registry->mode = PRE_LOGIN_ERROR;
			throw new Exception();
		}
				
		$registry->diner = readDiner( $dinerCode );
								
		$dinerDbPassword = $registry->diner->getPassword();

		if( !isset( $dinerDbPassword ) ){
			$registry->error = "Söögikoha info ei ole uuendatav.";
			$registry->mode = PRE_LOGIN_ERROR;
			throw new Exception();
		} 
		
		$_SESSION['code'] = $dinerCode;
		
		if( !isset( $password ) ){
			$registry->mode = LOGIN;
			throw new Exception();
		}
		
		if( $password != $dinerDbPassword ){
			$registry->error = "Vale parool.";
			$registry->mode = LOGIN;
			throw new Exception();
		}
		
		if(isset($_SESSION['timeout']) ) {
			$session_life = time() - $_SESSION['timeout'];
			if($session_life > $inactive){
				$registry->mode = LOGIN;
				throw new Exception();
			}
		}
		
		$_SESSION['timeout'] = time();

		// process page selection
		processPageSelection( $registry );

		// proccess actions
		processActions( $registry );

	} catch( Exception $e ){
		session_destroy();
	}
		
	renderBody( $registry );
		
	/* ---------------------------------------------------------------------------------------------*/
		
	function isLoggedInMode( $mode ){
		switch( $mode ){
			case MAIN_ENTER_INFO:
			case MAIN_CHANGE_PASSWORD: return true;
			default: return false; 
		}
	}

	function processPageSelection( $registry ){
		$registry->mode = MAIN_ENTER_INFO;
		if( !empty( $_GET["page"] ) ){
			$registry->mode = $_GET["page"];
		}
		
		if( $registry->mode == LOGOUT ){
			session_destroy();
			$registry->mode = LOGIN;
		}
	}
	
	function processActions( $registry ){
		$action = null;
		if( !empty( $_POST['action'] ) ){
			$action = $_POST['action'];
		}
		switch( $action ){
			case UPDATE_FOOD_INFO : {
				if( !empty( $_POST['defaultInfo'] ) ){
					$registry->diner->setDefaultInfo( nl2br( trim( $_POST['defaultInfo'] ) ) );
				} else{
					$registry->diner->setDefaultInfo( null );
				}

				$registry->diner->clearDateFoods();
				for( $i = 0; $i < DATEFOOD_COUNT; $i++ ){
					if( !empty( $_POST['food'.$i] ) && !empty( $_POST['date'.$i] ) ){
						$dateFood = new DateFood();
						$dateFood->setFood( nl2br( trim( $_POST['food'.$i] ) ) );
						$dateFood->setDate( $_POST['date'.$i] );
						$dateFood->setInfo( $registry->diner->getDefaultInfo() );
						$registry->diner->addDateFood( $dateFood );
					}
				}
				writeDiner( $registry->diner );
				$registry->info = "Muudatused salvestatud!";

			}
			break;
				
			case CHANGE_PASSWORD : {
				if( !empty( $_POST['oldPassword'] ) ){
					$oldPassword = $_POST['oldPassword'];
				}
				
				if( !empty( $_POST['newPassword1'] ) ){
					$newPassword1 = $_POST['newPassword1'];
				}

				if( !empty( $_POST['newPassword2'] ) ){
					$newPassword2 = $_POST['newPassword2'];
				}
				
				if( !isset( $oldPassword ) ){
					$registry->error = "Puudub vana parool!";
				} elseif( !isset( $newPassword1 ) ){
					$registry->error = "Puudub uus parool!";
				} elseif( !isset( $newPassword2 ) ){
					$registry->error = "Puudub uus parool teist korda!";
				} elseif( md5( $oldPassword.OBFUSCATION ) != $registry->diner->getPassword() ){
					$registry->error = "Vale parool!";
				} elseif( $newPassword1 != $newPassword2){
					$registry->error = "Uus parool ja uus parool teist korda erinevad!";
				} elseif( strlen( $newPassword1 ) < 6 ){
					$registry->error = "Uue parooli miinimumpikkus 6 märki!";
				} else{
					$registry->diner->setPassword( md5( $newPassword1.OBFUSCATION ) );
					writeDiner( $registry->diner );
					$_SESSION['password'] = $registry->diner->getPassword();
					$registry->info = "Parool vahetatud!";
				}
			}
			break;
		}
	}
		
	function renderInfo( $registry ){
		if( isset( $registry->info ) ){
			echo('
			<div class="info_text">'.$registry->info.'</div>
			');
		}
		if( isset( $registry->error ) ){
			echo('
			<div class="error_text">'.$registry->error.'</div>
			');
		}
	}
	
	function renderBody( $registry ){
		
		if( $registry->mode == LOGIN ){
			echo('
				<body onload="document.password_form.password.focus();">
			');
		}
		if( isLoggedInMode( $registry->mode ) ){
			if( $registry->mode == MAIN_ENTER_INFO ){
				echo('
				<body class="main_body" onload="document.save_datefood_form.food0.focus();">
				');
			} else{
				echo('
				<body class="main_body">
				');
			}
		}

		if( $registry->mode == PRE_LOGIN_ERROR ){
			renderInfo( $registry );
		}
		
		if( $registry->mode == LOGIN ){
			renderInfo( $registry );
			echo('
			<div class="login_frame">
				<div class="login_password_text">'.ucfirst( strtolower( $registry->diner->getCode() ) ).'. Parool:</div>
				<form name="password_form" method="post" action="?code='.encrypt( $registry->diner->getCode(), CODESALT ).'">
					<input class="password" type="password" size="20" name="password">
				</form>
			</div>
			');
		} 
		
		if( isLoggedInMode( $registry->mode ) ){
			
			echo('
			<div class="navbar">
				<a class="navbar_link" href="?code='.encrypt( $registry->diner->getCode(), CODESALT ).'&page=p1">INFO SISESTAMINE</a>
				<a class="navbar_link" href="?code='.encrypt( $registry->diner->getCode(), CODESALT ).'&page=p2">PAROOLI VAHETUS</a>
				<a class="navbar_link" href="?code='.encrypt( $registry->diner->getCode(), CODESALT ).'&page=p3">LOGI VÄLJA</a>
			</div>
			');
			
			echo('
			<div class="content">
			');
			renderInfo( $registry );
			
			if( $registry->mode == MAIN_ENTER_INFO ){
					
				// initialise values
				$time = time();
				$food = null;
					
				echo('
					<form name="save_datefood_form" method="post" action="?code='.encrypt( $registry->diner->getCode(), CODESALT ).'&page=p1">
						<input type="hidden" name="action" value="'.UPDATE_FOOD_INFO.'"/>	
						<input type="submit" size="20" class="submit_content" value="Salvesta kõik"/>
						<label class="food_area_label"">Üldine lisainfo, kehtib iga päeva kohta</label>
						<textarea class="info_food_textarea" name="defaultInfo" cols="40" rows="5">'.br2nl( $registry->diner->getDefaultInfo() ).'</textarea>
				');
					
				for( $i = 0; $i < DATEFOOD_COUNT; $i++ ){
					$dateFood = $registry->diner->getDateFood( date( "Y-m-d", $time ) );
					if( $dateFood != null ){
						$food = br2nl( $dateFood->getFood() );
					} else{
						$food = null;
					}
					if( Date( "D", $time ) != "Sat" && Date( "D", $time ) != "Sun" ){
						echo('
						<label class="food_area_label"">'.
							Date( "d.m.Y", $time ).', '.getEstWeekday( Date( "D", $time ) ).
						'</label>
						<textarea class="info_food_textarea" name="food'.$i.'" cols="40" rows="5">'.$food.'</textarea>
						<input type="hidden" name="date'.$i.'" value="'.date( "Y-m-d", $time ).'"/>
					');
					}

					$time = mktime(	0, 0, 0, date( "m", $time ), date( "d", $time )+1, date( "Y", $time ) );
				}
				echo('
					</form>
				');
			} elseif( $registry->mode == MAIN_CHANGE_PASSWORD ){
				echo('
				<form name="change_password" method="post" action="?code='.encrypt( $registry->diner->getCode(), CODESALT ).'&page=p2"">
					<input type="hidden" name="action" value="'.CHANGE_PASSWORD.'"/>	
					<label class="change_password_label">Vana parool</label>
					<input class="change_password_input" type="password" name="oldPassword">
					<label class="change_password_label">Uus parool</label>
					<input class="change_password_input" type="password" name="newPassword1">
					<label class="change_password_label">Uus parool veel kord</label>
					<input class="change_password_input" type="password" name="newPassword2">
					<input type="submit" name="submitPasswordChange" class="submit_content" value="OK"/>
				</form>
				');
			}
			
			echo('
			</div>
			');

		}
		
		echo('
		</body>
		');
	}
	?>
</html>