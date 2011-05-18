<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
	<head>
		<title>Tartu päevapraed</title>
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
		<META HTTP-EQUIV="Expires" CONTENT="-1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="google-site-verification" content="lGZbKxbRLU2M0YafULPIGS1hNC4DBlFUZWMllJnF6x4" />
		<meta content="Tartu päevapraed, päevapraad igaks päevaks!" name="Description"/>
		<meta name="Description" content="Söögikohtade päevapakkumised Tartus." />
		<link href="styles.css?24" rel="stylesheet" type="text/css"/>
		<link rel="shortcut icon" href="favicon.ico">
		<script type="text/javascript" src="json2.js"></script>
		<script type="text/javascript" src="yui-min.js"></script>
		<script type="text/javascript" src="main.js?18"></script>
	</head>
	
	<?php
	include_once("common.php");
	include_once("utils.php");
	mysql_connect(DBconnection::$dbhost, DBconnection::$dbuser, DBconnection::$dbpass);
	@mysql_select_db(DBconnection::$dbname) or die( "Unable to select database");
	mysql_set_charset("utf8");
	$query = "select d.code, d.name, d.url, ds.date, ds.food, ds.changedate, ds.info from diners d left join datefoods ds ".
			"on d.code = ds.code and ds.date = DATE_FORMAT(curdate(), '%Y-%m-%d') where d.enabled = 1 order by d.name";
	$dinersOneDay = mysql_query($query);
	$query = "select d.code, d.name, d.url, ds.date, ds.food, ds.changedate, ds.info from diners d left join datefoods ds ".
			"on d.code = ds.code where d.enabled = 1 and ds.date > curdate() order by d.name, ds.date";
	$dinersAllDays = mysql_query($query);
	$query = "select code, name from filters";
	$filters = mysql_query($query);
	$query = "select code, keyword from filterkeywords order by code";
	$filterKeywords = mysql_query($query);
	mysql_close();
	$today = date( "Y-m-d" );
	function getUpdated( $changedate ){
		$datediff = floor( ( strtotime( "tomorrow ".date( "Y-m-d" ) ) - strtotime( $changedate ) ) / 3600 / 24 );
		$changetime = " ".date( "G:i", strtotime( $changedate ) );
		if( $datediff == 0 ){
			$changedate = "täna";
		}
		elseif( $datediff == 1){
			$changedate = "eile";
		}
		else{
			$changedate = $datediff." päeva tagasi";
			$changetime = null;
		}
		$updated = "(uuendatud ".$changedate.$changetime.")";
		return $updated;
	}
	
	function getResults( $diner, $resource ){
		$rows = array();
		while($row = mysql_fetch_array( $resource, MYSQL_ASSOC )){
			if( $row["code"] != $diner ){
				continue;
			}
			array_push( $rows, $row );
		}
		if(mysql_num_rows(  $resource ) != 0){
			mysql_data_seek($resource, 0);
		}
		return $rows;
	}
	
	/**
	 * @param $displayList list
	 * @param $exclude true to exclude $displayList, false to display only $displayList
	 */
	function echoDinerFoods( array $displayList = null, $exclude = true, $favorites = false ){
		global $dinersOneDay;
		if( $displayList != null && $exclude == false){
			foreach( $displayList as $element ){
				while($row = mysql_fetch_array($dinersOneDay, MYSQL_ASSOC)){
					if( $element != $row["code"] ){
						continue;
					}
					$futuredata = false;
					if($row["food"] == null){
						$updated = null;
						if(date("D") == "Sat" || date("D") == "Sun"){
							$food = "Nädalavahetusel päevapraad puudub";
						}
						else{
							$food = "Hetkel info puudub";
						}
					}
					else {
						$food = $row["food"];
						$updated = getUpdated( $row["changedate"] );
					}
					renderFoodelement( $row, $food, $updated, $row["food"] != null ? true : false, $favorites);
					
				}
				mysql_data_seek($dinersOneDay, 0);
			}
		}
				
		if( $exclude == true ){
			while($row = mysql_fetch_array($dinersOneDay, MYSQL_ASSOC)){
				if( $displayList != null ){
					if( in_array( $row["code"], $displayList ) ){
						continue;
					}
				}
				$futuredata = false;
				if($row["food"] == null){
					continue;
				}
				else {
					$food = $row["food"];
					$updated = getUpdated( $row["changedate"] );
				}
				renderFoodelement( $row, $food, $updated, true, $favorites);
				
			}
			mysql_data_seek($dinersOneDay, 0);
			
			while($row = mysql_fetch_array($dinersOneDay, MYSQL_ASSOC)){
				if( $displayList != null ){
					if( in_array( $row["code"], $displayList ) ){
						if( $exclude ){
							continue;
						}
					}
					elseif( !$exclude ){
						continue;
					}
				}
				$futuredata = false;
				if($row["food"] == null){
					$updated = null;
					if(date("D") == "Sat" || date("D") == "Sun"){
						$food = "Nädalavahetusel päevapraad puudub";
					}
					else{
						$food = "Hetkel info puudub";
					}
				}
				else {
					continue;
				}
				renderFoodelement( $row, $food, $updated, false, $favorites );
			}
			mysql_data_seek($dinersOneDay, 0);
		}
	}
	
	function renderFoodelement( $row, $food, $updated, $hasInfo, $favorites ){
		global $dinersAllDays;
		$results = getResults( $row["code"], $dinersAllDays );
		echo('<!-- FOODELEMENT START-->
		<div id="'.$row["code"].'_ALL" class="static" '.
				'data-json=\'{"code":"'.$row["code"].'","name":"'.$row["name"].'","hasInfo":'.($hasInfo == true ? 1 : 0).','.
						'"isFavorite":'.($favorites == true ? 1 : 0).'}\'>		
		<div id="'.$row["code"].'" class="spacer"><img src="images/spacer.gif"/></div>
		<div class="main_left_all">
			<div class="main_left_top'.($favorites == true ? "_personal" : null ).'" id="'.$row["code"].'_TOP_STANDARD">
				<div class="main_left_top_highlight'.($favorites == true ? "_personal" : null ).'" id="'.$row["code"].'_TOP"></div>
			</div>
			<div class="main_left_center'.($favorites == true ? "_personal" : null ).'" id="'.$row["code"].'_CENTER_STANDARD">
				<div class="main_left_center_body">
					<div class="float_left">
						<!--p class="diner">'.$row["name"].'</p-->
						<p class="food" id="'.$row["code"].'_FOOD">'.$food.'</p>');
						if( $row["info"] != null ){
							echo( '<p class="food_info">'.$row["info"].'</p>' );
						}
					echo('
					</div>
					<div class="float_upcomming_right">');
						if( $row["url"] == null ){
							echo('<p class="diner_no_link">'.$row["name"].'</a>');
						}else{
							echo('<a class="diner_link" href="http://'.$row["url"].'">'.$row["name"].'</a>');
						}
						if( count( $results ) > 0 ){
							echo('<p class="upcomming_title">Tulevased pakkumised:</p>');	
						}
						echo('<div class="clear_float"></div>
						<a class="diner_link_small" href="http://'.$row["url"].'">'.$row["url"].'</a>');
						echo('<div class="clear_float"></div>');
						foreach( $results as $rownum=>$innerrow ){
							if( $rownum >= 4 ){
								break;
							}
							if($innerrow["food"] != null){
								$fetchedDate = strtotime($innerrow["date"]);
								$month = getEstMonthName( date( "m", $fetchedDate ) );
								$day = date( "d", $fetchedDate );
								$weekDay = getEstWeekdayLetter( date( "D", $fetchedDate ) );
								$offset = floor( ( $fetchedDate - time() ) / 3600 / 24 ) + 1;
								$offsetName = null;
								if( $rownum == 1 ){
									echo('<div class="upcomming" id="'.$row["code"].'_FUTURE_OUTER">'.
									'<div id="'.$row["code"].'_FUTURE_INNER">');
									$futuredata = true;
								}
								if( $rownum == 0){
									if( $offset == 1 ){
										$offsetName = " (homme)";
									}
									elseif( $offset == 2 ){
										$offsetName = " (ülehomme)";
									}
									else{
										$offsetName = " (".$offset." päeva pärast)";
									}
								}
								echo('<p class="upcomming_date">'.$weekDay.', '.$day.' '.$month.$offsetName.'</p>');
								echo('<p class="upcomming">'.$innerrow["food"].'</p>');
							}
						}
						if( $futuredata ){
							echo('</div></div>
							<a class="expand" id="'.$row["code"].'_EXPAND" href="javascript:doResize(\''.$row["code"].'\')">rohkem..</a>
							<div class="clear_float"></div>');
						}
					echo('
					</div>
					<div class="clear_float"></div>
					<p class="updated">'.$updated.'</p>
					<a href="javascript:doFavorite(\''.$row["code"].'\')" class="favorite" id="'.$row["code"].($favorites == true ? "_REMOVE" : "_ADD" ).'">'.
						($favorites == true ? "[Eemalda]" : "[Muuda lemmikuks]" ).'
					</a>
					<div class="clear_float"></div>
				</div>
				<div class="main_left_center_highlight'.($favorites == true ? "_personal" : null ).'" id="'.$row["code"].'_CENTER"></div>
			</div>
			<div class="main_left_bottom'.($favorites == true ? "_personal" : null ).'" id="'.$row["code"].'_BOTTOM_STANDARD">
				<div class="main_left_bottom_highlight'.($favorites == true ? "_personal" : null ).'" id="'.$row["code"].'_BOTTOM"></div>
			</div>
		</div>
		</div>
		<!-- FOODELEMENT END-->	
		');
	}
		
	?>
	
	<body>
		<?php
		$allDiners = array();
		while($row = mysql_fetch_array($dinersOneDay, MYSQL_ASSOC)){
  			array_push( $allDiners, $row["code"] );
  		}
  		mysql_data_seek($dinersOneDay, 0);
		echo( '<div id="ALL_DINERS" class="hidden" data-json=\''.json_encode( $allDiners ).'\'></div>' );
  		?>
		<div class="main">
			<!-- header -->
			<div class="float_container">
				<div class="header_text">
					<h1 class="header_title">
						Tartu päevapraed
					</h1>
					<h2 class="header_info">
						Tänased ja tulevased päevapakkumised Tartu kesklinna söögikohtades
					</h2>
					<div class="clear_float"></div>
				</div>
				<img src="images/ForkPlateKnife.png?1" class="header_image"/>
				<div class="clear_float"></div>
			</div>
			<div class="float_container">
				<div class="float_left">
					<div class="nav_body">
						<div>
							<p class="dropdown_label">Navigeerimine:</p>
							<form id="navForm" class="standard_form">
								<span class="nav_fix">
								<select size="1" id="navSelect" onChange="doNav()" class="navselect">
  									<option selected="selected">Vali söögikoht</option>
  									<?php
  									while($row = mysql_fetch_array($dinersOneDay, MYSQL_ASSOC)){
  										echo ('<option value="'.$row["code"].'">'.$row["name"].'</option>');
  									}
  									mysql_data_seek($dinersOneDay, 0);
  									?>
								</select>
								</span>
							</form>
						</div>
						<div>
							<?php
							$filtersArray = array();
							while($row = mysql_fetch_array($filters, MYSQL_ASSOC)){
								$filterKeywordsArray = array();
								while($innerrow = mysql_fetch_array($filterKeywords, MYSQL_ASSOC)){
									if( $innerrow["code"] == $row["code"] ){
										array_push( $filterKeywordsArray, $innerrow["keyword"] );
									}
								}
								mysql_data_seek($filterKeywords, 0);
								$filtersArray[$row["code"]] = $filterKeywordsArray;
							}
							mysql_data_seek($filters, 0);
							echo( '<div id="FILTER_DATA" class="hidden" data-json=\''.json_encode( $filtersArray ).'\'></div>' );
							?>
							<p class="dropdown_label">Filter:</p>
							<form id="FILTER_FORM" class="standard_form">
								<?php
								while($row = mysql_fetch_array($filters, MYSQL_ASSOC)){
									echo( '<p class="filter_label"><a href="javascript:filterClickLink(\''.$row["code"].'\')">'.$row["name"].'</a><input id="'.$row["code"].'_FILTER'.
										'" class="filter" type="checkbox" onClick="javascript:filterClick(\''.$row["code"].'\')" name="'.$row["code"].'"/></p>' );	
								}
								?>
							</form>
						</div>
					</div>
				</div>
				<div class="header_banner">
					<object width="485" height="80">
						<param name="movie" value="banners/NOHA1.swf" loop="true" quality="high">
						<embed src="banners/NOHA1.swf" width="485" height="80" loop="true" quality="high"/>
					</object>
				</div>
				<div class="clear_float"></div>
			</div>
			<div class="spacer"></div>
			<div class="like_button">
				<script src="http://connect.facebook.net/et_EE/all.js#xfbml=1"></script><fb:like href="http://www.facebook.com/pages/Tartu-Paevapraed/179457692076223" layout="button_count" show_faces="false" width="450"></fb:like>
			</div>
			<div class="spacer"></div>
			<!-- body -->
			<div class="main_body">
				<div class="banner_column">
				<!-- banner stuff -->
				</div>
				<!-- Isiklikud section -->
				<div class="section">
					<div class="content_upper_personal"></div>
					<div class="content_center_personal" id="favorites">
						<?php
						$favoritesList = array();
						if( isset( $_COOKIE["favorites"] ) ){
							if(get_magic_quotes_gpc() == true){
								foreach($_COOKIE as $key => $value) {
	   								$_COOKIE[$key] = stripslashes($value);
	  							}
							}
						 	$favoritesList = json_decode( $_COOKIE["favorites"] );
						 	if( count( $favoritesList ) > 0 ){
						 		$favorites = true;
						 	}
						 	else{
								$favorites = false;
							}
						}
						?>
						<div class="float_container">
							<div class="float_left">
								<p class="large_label">Lemmikud</p>
							</div>
							<a id="REMOVE_ALL" class="<?php echo $favorites == true ? "remove_all" : "hidden" ?>" href="javascript:removeAll()">
								[Eemalda kõik]
							</a>
							<div class="clear_float"></div>
						</div>
						<p class="<?php echo $favorites == false ? "content_info_small_light" : "hidden" ?>" id="FAVORITES_INFO">
							Nupuga 'Muuda lemmikuks' saad liigutada Sinu jaoks tähtsad söögikohad siia sektsiooni et edaspidi 
							lehte avades näha kõigepealt just Sulle kasulikku infot!									
						</p>
						<?php
							if( $favorites ){
								echoDinerFoods( $favoritesList, false, true );
							}
						?>
					</div>
					<div class="content_lower_personal"></div>
				</div>
				<!-- main section -->
				<div class="content">
					<div class="content_upper"></div>
					<div class="content_center" id="nonfavorites">
						<div class="float_container">
							<div class="float_left">
								<p class="large_label">Viimati lisandunud:</p>
								<p class="content_info_small_inline">Ränduri pubi, Yakuza sushi bar, Volga restoran, Parviiz, Kinohvik</p>
							</div>
							<div class="clear_float"></div>
						</div>
						<?php echoDinerFoods( $favoritesList, true, false ); ?>
					</div>
					<div class="content_lower"></div>
				</div>
				<div class="clear_float"></div>
			</div>
			<div class="line"></div>
			<div class="float_container">
				<div class="float_left">
					<p class="footer">
					Teie söögikoht puudub? Tasuta liitumine, kontakt, muud ettepanekud: info@paevapraed.com või 
					<a href="http://www.facebook.com/pages/Tartu-Paevapraed/179457692076223">Tartu Päevapraed Facebookis</a>
					</p>
				</div>
				<div class="float_right">
					<p class="footer">paevapraed.com 2010</p>
				</div>
			</div>
			<div class="clear_float"></div>
		</div>
		
		
	<!-- Start of StatCounter Code --> 
	<script type="text/javascript">
	var sc_project=5480790; 
	var sc_invisible=1; 
	var sc_partition=60; 
	var sc_click_stat=1; 
	var sc_security="73df8ec3"; 
	</script>

	<script type="text/javascript"
	src="http://www.statcounter.com/counter/counter.js"></script><noscript><div
	class="statcounter"><a title="free hit counter"
	href="http://www.statcounter.com/" target="_blank"><img
	class="statcounter"
	src="http://c.statcounter.com/5480790/0/73df8ec3/1/"
	alt="free hit counter" ></a></div></noscript>
	<!-- End of StatCounter Code -->
	<HEAD>
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
		<META HTTP-EQUIV="Expires" CONTENT="-1">
	</HEAD>
	</body>
</html>
