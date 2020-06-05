
/*--------------------------------------------------------------
	
	Script Name : Contents Maker
	Author      : FIRSTSTEP - Motohiro Tani
	Author URL  : https://www.1-firststep.com
	Create Date : 2015/05/20
	Version     : 5.1
	Last Update : 2019/01/18
	
--------------------------------------------------------------*/


function contents_maker() {
	
	var news = document.getElementById( 'news' );
	var js   = document.getElementById( 'contents-maker-js' );
	var href = js.getAttribute( 'src' ).replace( /js\/contents-maker\.js/gi, 'php/index.php' );
	
	var xhr = new XMLHttpRequest();
	xhr.open( 'GET', href, true );
	
	xhr.onreadystatechange = function() {
		
		if ( xhr.readyState === 4 && xhr.status === 200 ) {
			news.innerHTML = xhr.responseText;
		} else {
			news.innerHTML = '新着情報取得中...'
		}
	}
	
	xhr.send( null );
	
}




function addEventSet( element, listener, fn ) {
	try {
		element.addEventListener( listener, fn, false );
	} catch( e ) {
		element.attachEvent( 'on' + listener, fn );
	}
}

addEventSet( window, 'load', function() {
	contents_maker();
});