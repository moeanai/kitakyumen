<?php

class Contents_Maker_Login_Js {
	
	// public construct
	public function __construct() {
		
		header( 'Content-Type: application/javascript' );
		
		
		echo <<<EOM
(function( $ ) {
	
	function hidden_append( name, value, element ){
		
		$( '<input />' )
			.attr({
				type: 'hidden',
				id: name,
				name: name,
				value: value
			})
			.appendTo( element );
		
	}
	
	
	
	
	function login_check() {
		
		var error = 0;
		var user  = $( 'input#user' );
		var pass  = $( 'input#pass' );
		
		
		if ( user.val() === '' ) {
			user.nextAll( 'span' ).text( 'ユーザー名が入力されていません。' );
			error++;
		} else {
			user.nextAll( 'span' ).text( '' );
		}
		
		if ( pass.val() === '' ) {
			pass.nextAll( 'span' ).text( 'パスワードが入力されていません。' );
			error++;
		} else {
			pass.nextAll( 'span' ).text( '' );
		}
		
		
		if ( error === 0 ) {
			
			hidden_append( 'javascript_action', true, $( 'form#login-form p.submit' ) );
			
			$.ajax({
				type: $( 'form#login-form' ).attr( 'method' ),
				url: $( 'form#login-form' ).attr( 'action' ),
				cache: false,
				dataType: 'text',
				data: $( 'form#login-form' ).serialize(),
				
				success: function( res ) {
					var response = res.split( ',' );
					if( response[0] === 'login_success' ){
						$( 'form#login-form' ).fadeOut( 'normal', function() {
							window.location.href = response[1];
						});
					} else {
						user.nextAll( 'span' ).text( 'ユーザー名またはパスワードが違います。' );
						pass.nextAll( 'span' ).text( 'ユーザー名またはパスワードが違います。' );
						$( 'input#javascript_action' ).remove();
					}
				},
				
				error: function( res ) {
					window.alert( 'Ajax通信が失敗しました。\\nページの再読み込みをしてからもう一度お試しください。' );
				}
			});
			
		}
		
	}
	
	
	
	
	$( 'input#user' ).after( '<span></span>' );
	$( 'input#pass' ).after( '<span></span>' );
	
	$( 'input#login-button' ).on( 'click', login_check );
	
})( jQuery );
EOM;
		
	}
	
}

?>