<?php

/*--------------------------------------------------------------
	
	Script Name : Contents Maker
	Author      : FIRSTSTEP - Motohiro Tani
	Author URL  : https://www.1-firststep.com
	Create Date : 2015/05/20
	Version     : 5.1
	Last Update : 2019/01/18
	
--------------------------------------------------------------*/


session_start();
error_reporting( E_ALL );




mb_language( 'ja' );
mb_internal_encoding( 'UTF-8' );




if ( isset( $_POST['logout'] ) && $_POST['logout'] !== '' ) {
	include( dirname(__FILE__) .'/class.contents-maker-login.php' );
	$contents_maker_login = new Contents_Maker_Login();
	
	$contents_maker_login->javascript_action_check();
	$contents_maker_login->referer_check();
	$contents_maker_login->session_check();
	$contents_maker_login->logout_check();
	exit;
}




if ( isset( $_POST['order'] ) && $_POST['order'] !== '' ) {
	include( dirname(__FILE__) .'/class.contents-maker-write.php' );
	$contents_maker_write = new Contents_Maker_Write();
	
	if ( file_exists( dirname( __FILE__ ) .'/../addon/sort/sort.php' ) ) {
		include( dirname( __FILE__ ) .'/../addon/sort/sort.php' );
	}
}




if ( isset( $_POST['edit-number'] ) && $_POST['edit-number'] !== '' ) {
	include( dirname(__FILE__) .'/class.contents-maker-write.php' );
	$contents_maker_write = new Contents_Maker_Write();
	
	$contents_maker_write->javascript_action_check();
	$contents_maker_write->referer_check();
	$contents_maker_write->session_check();
	$contents_maker_write->token_check();
	$contents_maker_write->post_check();
	$contents_maker_write->contents_edit();
	exit;
}




if ( ( isset( $_POST['date'] ) && $_POST['date'] !== '' ) || ( isset( $_POST['contents'] ) && $_POST['contents'] !== '' ) ) {
	include( dirname(__FILE__) .'/class.contents-maker-write.php' );
	$contents_maker_write = new Contents_Maker_Write();
	
	$contents_maker_write->javascript_action_check();
	$contents_maker_write->referer_check();
	$contents_maker_write->session_check();
	$contents_maker_write->token_check();
	$contents_maker_write->post_check();
	$contents_maker_write->contents_write();
	exit;
}




if ( isset( $_POST['delete-number'] ) && $_POST['delete-number'] !== '' ) {
	include( dirname(__FILE__) .'/class.contents-maker-write.php' );
	$contents_maker_write = new Contents_Maker_Write();
	
	$contents_maker_write->javascript_action_check();
	$contents_maker_write->referer_check();
	$contents_maker_write->session_check();
	$contents_maker_write->token_check();
	$contents_maker_write->contents_delete();
	exit;
}




if ( isset( $_SESSION['contents_maker_login'] ) && $_SESSION['contents_maker_login'] === 'contents_maker_login_ok' ) {
	include( dirname(__FILE__) .'/class.contents-maker-admin.php' );
	$contents_maker_admin = new Contents_Maker_Admin();
	
	$contents_maker_admin->html_header();
	$contents_maker_admin->header();
	$contents_maker_admin->contents_form();
	$contents_maker_admin->url_form();
	$contents_maker_admin->get_news( true );
	$contents_maker_admin->footer();
} else {
	header( 'Location: login.php' );
}








?>