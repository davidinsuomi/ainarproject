var allDiners = new Array();
var filtersArray = new Array();
var filtersDinersArray = new Array();

/** initialization start **/
/***********************************************************************************************/
YUI().use('node-base', function(Y) {
	function init() {
		allDiners = JSON.parse( document.getElementById( "ALL_DINERS" ).getAttribute("data-json") );
		filtersArray = JSON.parse( document.getElementById( "FILTER_DATA" ).getAttribute("data-json") );
		for( var i in filtersArray ){
			filtersDinersArray[i] = new Array();
		}
		
		document.getElementById("FILTER_FORM").reset();
	}
	Y.on("domready", init);
});
/** initialization end **/
/***********************************************************************************************/

function filterClickLink( code ){
	if( !document.getElementById( code + "_FILTER").checked ){
		document.getElementById( code + "_FILTER").checked = true;
	}
	else {
		document.getElementById( code + "_FILTER").checked = false;
	}
	filterClick( code );
}

function filterClick( code ){
	if( document.getElementById( code + "_FILTER").checked ){
		for( var i in filtersArray[code] ){
			for( var j in allDiners ){
				if( document.getElementById( allDiners[j] + "_FOOD" ).innerHTML.toLowerCase().indexOf( filtersArray[code][i] ) != -1 ){
					if( indexOf( filtersDinersArray[code], allDiners[j] ) == -1 ){
						filtersDinersArray[code].push( allDiners[j] );
					}
				}
			}
		}
	}
	else {
		filtersDinersArray[code] = new Array();
	}
	var allUnselected = true;
	for( var i in filtersArray ){
		if( document.getElementById( i + "_FILTER").checked ){
			allUnselected = false;
		}
	}
	for( var i in allDiners ){
		var visible = false;
		for( var j in filtersDinersArray ){
			if( indexOf( filtersDinersArray[j], allDiners[i] ) != -1 ){
				visible = true;
				continue;
			}
		}
		if( visible || allUnselected ){
			document.getElementById( allDiners[i] + "_ALL" ).style.display = "";
			changeOptions( allDiners[i], true );
		}
		else{
			changeOptions( allDiners[i], false );
			document.getElementById( allDiners[i] + "_ALL" ).style.display = "none";
		}
	}
}

/** doFavorite start **/
function removeAll(){
	for( var i in allDiners ){
		tempObj = JSON.parse( document.getElementById( allDiners[i] + "_ALL" ).getAttribute("data-json") );
		if( tempObj.isFavorite == 1 ){
			removeFavorite( allDiners[i] );
		}
	}
}

function doFavorite( code ){
	if( document.getElementById( code + "_ADD" ) != null ){
		addFavorite( code );
	}
	else if( document.getElementById( code + "_REMOVE" ) != null ){
		removeFavorite( code );
	}	
}

function addFavorite( code ){
	var currentProp = JSON.parse( document.getElementById( code + "_ALL" ).getAttribute("data-json") );
	var favorites = document.getElementById( "favorites" );
	var select = document.getElementById("navSelect");
	var currentDiner = document.getElementById( code + "_ALL" );
	var addLink = document.getElementById( code + "_ADD" );
	var cookieArray = Array();
	try{
		cookieArray = JSON.parse( getCookie( "favorites" ) );
		if( cookieArray == null || !isArray( cookieArray ) ){
			cookieArray = new Array();
		}
	}
	catch( e ){
	}
	finally{
		if( cookieArray != null && indexOf( cookieArray, code ) < 0){
			cookieArray.push( code );
			setCookie( "favorites", JSON.stringify( cookieArray ), new Date( 4102444800000 ), null, null, false );
		}
	}
	document.getElementById( "FAVORITES_INFO" ).className= "hidden";
	document.getElementById( "REMOVE_ALL" ).className= "remove_all";
	favorites.appendChild( currentDiner );
	changeImagesToFavorite( code );
	addLink.innerHTML = "[Eemalda]";
	addLink.id = code + '_REMOVE';
	currentProp.isFavorite = 1;
	document.getElementById( code + "_ALL" ).setAttribute( "data-json", JSON.stringify( currentProp ) );
}

function removeFavorite( code ){
	var currentProp = JSON.parse( document.getElementById( code + "_ALL" ).getAttribute("data-json") );
	var favorites = document.getElementById( "favorites" );
	var select = document.getElementById("navSelect");
	var currentDiner = document.getElementById( code + "_ALL" );
	var removeLink = document.getElementById( code + "_REMOVE" );
	var cookieArray = Array();
	try{
		cookieArray = JSON.parse( getCookie( "favorites" ) );
		if( cookieArray != null && isArray( cookieArray ) ){
			cookieArray.splice( indexOf( cookieArray, code), 1 );
			setCookie( "favorites", JSON.stringify( cookieArray ), new Date( 4102444800000 ), null, null, false );
		}
	}
	catch( e ){
	}
	var hasInfoDinerIds = new Array();
	var hasNotInfoDinerIds = new Array();
	for( var i in allDiners ){
		tempObj = JSON.parse( document.getElementById( allDiners[i] + "_ALL" ).getAttribute("data-json"));
		if( tempObj.isFavorite == 0 ){
			if( tempObj.hasInfo == 1 ){
				hasInfoDinerIds.push( new Array( tempObj.code, tempObj.name ) );
			}
			else{
				hasNotInfoDinerIds.push( new Array( tempObj.code, tempObj.name ) );
			}
		}
	}
	if( currentProp.hasInfo == 1 ){
		hasInfoDinerIds.push( new Array( currentProp.code, currentProp.name ) );
	}
	else {
		hasNotInfoDinerIds.push( new Array( currentProp.code, currentProp.name ) );
	}
	hasInfoDinerIds.sort( twoDimArraySortFunc );
	hasNotInfoDinerIds.sort( twoDimArraySortFunc );
	var allDinerIds = hasInfoDinerIds.concat( hasNotInfoDinerIds );
	
	if( indexOfTwoDimFirst( allDinerIds, code ) == allDinerIds.length - 1){
		document.getElementById( "nonfavorites" ).appendChild( currentDiner );
	}
	else{
		document.getElementById( "nonfavorites" ).insertBefore( 
				currentDiner,
				document.getElementById( allDinerIds[indexOfTwoDimFirst( allDinerIds, code ) + 1][0] + "_ALL" ) 
				);
	}
	changeImagesToStandard( code );
	removeLink.innerHTML = "[Muuda lemmikuks]";
	removeLink.id = code + '_ADD';
	currentProp.isFavorite = 0;
	document.getElementById( code + "_ALL" ).setAttribute( "data-json", JSON.stringify( currentProp ) );
	if( cookieArray == null || cookieArray.length == 0 ){
		document.getElementById( "FAVORITES_INFO" ).className= "content_info_small_light";
		document.getElementById( "REMOVE_ALL" ).className= "hidden";
	}
}

function changeImagesToFavorite( code ){
	document.getElementById( code + "_TOP_STANDARD" ).className = "main_left_top_personal";
	document.getElementById( code + "_TOP" ).className = "main_left_top_highlight_personal";
	document.getElementById( code + "_CENTER_STANDARD" ).className = "main_left_center_personal";
	document.getElementById( code + "_CENTER" ).className = "main_left_center_highlight_personal";
	document.getElementById( code + "_BOTTOM_STANDARD" ).className = "main_left_bottom_personal";
	document.getElementById( code + "_BOTTOM" ).className = "main_left_bottom_highlight_personal";
}

function changeImagesToStandard( code ){
	document.getElementById( code + "_TOP_STANDARD" ).className = "main_left_top";
	document.getElementById( code + "_TOP" ).className = "main_left_top_highlight";
	document.getElementById( code + "_CENTER_STANDARD" ).className = "main_left_center";
	document.getElementById( code + "_CENTER" ).className = "main_left_center_highlight";
	document.getElementById( code + "_BOTTOM_STANDARD" ).className = "main_left_bottom";
	document.getElementById( code + "_BOTTOM" ).className = "main_left_bottom_highlight";
}

function indexOfTwoDimFirst( array, element ){
	for( var i = 0; i < array.length; i++ ){
		if( array[i][0] == element ){
			return i;
		}
	}
	return -1;
}

function indexOf( array, element ){
	for( var i = 0; i < array.length; i++ ){
		if( array[i] == element ){
			return i;
		}
	}
	return -1;
}

function isArray(obj) {
	   if (obj.constructor.toString().indexOf("Array") == -1)
	      return false;
	   else
	      return true;
}

function twoDimArraySortFunc(a,b) {
	// Note that each thing we are passed is an array, so we don't compare the things
	// we're passed; instead, we compare their second column
	if (a[1]<b[1]) return -1;
	if (a[1]>b[1]) return 1;
	return 0;
	}

function changeOptions( value, add ){
	var currentProp = JSON.parse( document.getElementById( value + "_ALL" ).getAttribute("data-json") );
	var text = currentProp.name;
	var select = document.getElementById("navSelect");
	if ( add == false){
		for( var i = 1; i < select.options.length; i++ ){
			if( select.options[i] != null ){
				if( select.options[i].value == value ){
					select.remove( i );
					break;
				}
			}
		}
	}
	if( add == true ){
		var allOptions = new Array;
		for( var i = 1; i < select.options.length; i++ ){
			allOptions.push( new Array( select.options[i].value, select.options[i].text ) );
		}
		if( indexOfTwoDimFirst(allOptions, value) == -1 ){
			allOptions.push( new Array( value, text ) );
			allOptions.sort( twoDimArraySortFunc );
			var newOption = new Option( text, value );
			select.options.add( newOption, indexOfTwoDimFirst(allOptions, value) + 1 );
		}
	}
}

function getCookie (name) {
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen) {
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg) {
			return getCookieVal (j);
		}
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) break; 
	}
	return null;
}

function getCookieVal (offset) {
	var endstr = document.cookie.indexOf(";", offset);
	if (endstr == -1) { endstr = document.cookie.length; }
	return unescape(document.cookie.substring(offset, endstr));
}

function deleteCookie (name,path,domain) {
	if (GetCookie(name)) {
		document.cookie = name + "=" +
		((path) ? "; path=" + path : "") +
		((domain) ? "; domain=" + domain : "") +
		"; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

function setCookie (name,value,expires,path,domain,secure) {
	document.cookie = name + "=" + escape (value) +
	((expires) ? "; expires=" + expires.toGMTString() : "") +
	((path) ? "; path=" + path : "") +
	((domain) ? "; domain=" + domain : "") +
	((secure) ? "; secure" : "");
}
/** doFavorite end **/


function doNav(){
	var select = document.getElementById("navSelect");
	var form = document.getElementById("navForm");
	var changed = select[select.selectedIndex].value;
	
	var changedDivTop = document.getElementById(changed + "_TOP");
	var changedDivCenter = document.getElementById(changed + "_CENTER");
	var changedDivBottom = document.getElementById(changed + "_BOTTOM");

	window.location.hash = changed; 	
	form.reset();
	window.focus();
	
	changedDivTop.style.opacity=1;
	changedDivTop.style.filter = 'alpha(opacity=100)';
	changedDivCenter.style.opacity=1;
	changedDivCenter.style.filter = 'alpha(opacity=100)';
	changedDivBottom.style.opacity=1;
	changedDivBottom.style.filter = 'alpha(opacity=100)';
	
	window.setTimeout(function(){unHighlight(changed)}, 2000);
}

function unHighlight(changed){
	var changedDivTop = document.getElementById(changed + "_TOP");
	var changedDivCenter = document.getElementById(changed + "_CENTER");
	var changedDivBottom = document.getElementById(changed + "_BOTTOM");
	
	changedDivTop.style.opacity=0;
	changedDivTop.style.filter = 'alpha(opacity=0)';
	changedDivCenter.style.opacity=0;
	changedDivCenter.style.filter = 'alpha(opacity=0)';
	changedDivBottom.style.opacity=0;
	changedDivBottom.style.filter = 'alpha(opacity=0)';
}

/*
function doResize(code){
	var outerDiv = document.getElementById(code + "_FUTURE_OUTER");
	var innerDiv = document.getElementById(code + "_FUTURE_INNER");
	var expandLink = document.getElementById(code + "_EXPAND");
	var collapseLink = document.getElementById(code + "_COLLAPSE");
	var fps = 25;
	var duration = 0.2; //seconds
	var step = innerDiv.offsetHeight / ( duration * fps );
	if(expandLink != null){
		expandLink.blur();
		var intervalId = setInterval( function(){expand(intervalId, outerDiv, innerDiv, expandLink, code, step)}, 1000 / fps );
	}
	if(collapseLink != null){
		collapseLink.blur();
		var intervalId = setInterval( function(){collapse(intervalId, outerDiv, collapseLink, code, step)}, 1000 / fps );
	}
}
*/


function doResize(code){
	var outerDiv = document.getElementById(code + "_FUTURE_OUTER");
	var innerDiv = document.getElementById(code + "_FUTURE_INNER");
	var expandLink = document.getElementById(code + "_EXPAND");
	var collapseLink = document.getElementById(code + "_COLLAPSE");
	var fps = 25;
	var duration = 0.2; //seconds
	var step = innerDiv.offsetHeight / ( duration * fps );
	if(expandLink != null){
		expandLink.blur();
		expand(outerDiv, innerDiv, expandLink, code)
	}
	if(collapseLink != null){
		collapseLink.blur();
		collapse(outerDiv, innerDiv, collapseLink, code)
	}
}

function expand(outerDiv, innerDiv, link, code){
	var extraSpacing = 3;
	var newHeight = innerDiv.offsetHeight + extraSpacing
	outerDiv.style.height = newHeight + 'px';
	window.scrollBy(0, newHeight / 2);
	link.innerHTML = 'vähem..';
	link.id = code + '_COLLAPSE';
}

function collapse(outerDiv, innerDiv, link, code){
	var newHeight = 0;
	outerDiv.style.height = 0 + 'px';
	window.scrollBy(0,-(innerDiv.offsetHeight / 2));
	link.innerHTML = 'rohkem..';
	link.id = code + '_EXPAND';
}

/*
function expand(intervalId, outerDiv, innerDiv, link, code, increment){
	var extraSpacing = 3;
	var newHeight = outerDiv.clientHeight + increment;
	if(newHeight >= innerDiv.offsetHeight + extraSpacing){
		increment = ( increment - ( newHeight - innerDiv.offsetHeight ) + extraSpacing) / 2;
		window.scrollBy(0,increment);
		outerDiv.style.height = innerDiv.offsetHeight + extraSpacing + 'px';
		link.innerHTML = 'vähem..';
		link.id = code + '_COLLAPSE';
		clearInterval(intervalId);
	}
	else{
		window.scrollBy(0,increment / 2);
		outerDiv.style.height = newHeight + 'px';
	}
}
*/

/*
function collapse(intervalId, outerDiv, link, code, decrement){
	var newHeight = outerDiv.clientHeight - decrement;
	if(newHeight <= 0){
		decrement = ( decrement + newHeight ) / 2;
		window.scrollBy(0,-decrement);
		outerDiv.style.height = 0 + 'px';
		link.innerHTML = 'rohkem..';
		link.id = code + '_EXPAND';
		clearInterval(intervalId);
	}
	else{
		window.scrollBy(0,-decrement / 2);
		outerDiv.style.height = newHeight + 'px';
	}
}
*/