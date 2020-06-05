<?php

class Contents_Maker {
	
	// property init
	protected $admin_user          = '';
	protected $admin_pass          = '';
	protected $domain_name         = '';
	
	protected $login_url           = '';
	protected $admin_url           = '';
	protected $session_id          = '';
	protected $token               = '';
	
	protected $pdo                 = '';
	
	
	// html tag addon property
	protected $html_tag            = 0;
	
	
	// attachment addon property
	protected $jpg                 = 1;
	protected $png                 = 1;
	protected $gif                 = 1;
	protected $upload_max_size     = 0;
	
	protected $resize_width_small  = 200;
	protected $resize_height_small = 200;
	
	
	
	
	// public construct
	public function __construct() {
		
		include( dirname( __FILE__ ) .'/config.php' );
		
		$this->admin_user     = $cm_admin_user;
		$this->admin_pass     = $cm_admin_pass;
		$this->domain_name    = $cm_domain_name;
		
		$this->login_url      = '//'.$_SERVER['HTTP_HOST'].dirname( $_SERVER['SCRIPT_NAME'] ).'/login.php';
		$this->admin_url      = '//'.$_SERVER['HTTP_HOST'].dirname( $_SERVER['SCRIPT_NAME'] ).'/admin.php';
		$this->session_id     = htmlspecialchars( session_id(), ENT_QUOTES, 'UTF-8' );
		$this->token          = sha1( $this->session_id );
		
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/html-tag/tag-config.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/html-tag/tag-config.php' );
			include( dirname( __FILE__ ) .'/../addon/html-tag/config-include.php' );
		}
		
		if ( file_exists( dirname( __FILE__ ) .'/../addon/attachment/attachment-config.php' ) ) {
			include( dirname( __FILE__ ) .'/../addon/attachment/attachment-config.php' );
			include( dirname( __FILE__ ) .'/../addon/attachment/config-include.php' );
		}
		
		
		try {
			$this->pdo = new PDO( "sqlite:". dirname( __FILE__ ) ."/../db/contents_maker.sqlite3" );
		} catch ( PDOException $e ) {
			exit( 'データベース接続に失敗しました。'.$e->getMessage() );
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `cm_post`"
			."( "
			."`id` INTEGER PRIMARY KEY AUTOINCREMENT, "
			."`time` DATETIME, "
			."`date` VARCHAR(255), "
			."`contents` TEXT, "
			."`small` VARCHAR(255), "
			."`reserve` VARCHAR(255), "
			."`row` INT"
			." );";
		
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();
		
	}
	
	
	
	
	// public javascript_action_check
	public function javascript_action_check() {
		
		if ( ! ( isset( $_POST['javascript_action'] ) && $_POST['javascript_action'] === 'true' ) ) {
			echo 'spam_failed-0001';
			exit;
		}
		
	}
	
	
	
	
	// public referer_check
	public function referer_check() {
		
		if ( $this->domain_name !== '' ) {
			if ( strpos( $_SERVER['HTTP_REFERER'], $this->domain_name ) === false ) {
				echo 'spam_failed-0002';
				exit;
			}
		}
		
	}
	
	
	
	
	// public session_check
	public function session_check() {
		
		if ( ! ( isset( $_SESSION['contents_maker_login'] ) && $_SESSION['contents_maker_login'] === 'contents_maker_login_ok' ) ) {
			echo 'spam_failed-0003';
			exit;
		}
		
	}
	
	
	
	
	// public token_check
	public function token_check() {
		
		if ( ! ( isset( $_POST['token'] ) && $_POST['token'] === $this->token ) ) {
			echo 'spam_failed-0004';
			exit;
		}
		
	}
	
	
	
	
	// public get_news
	public function get_news( $login ) {
		
		$stmt = $this->pdo->prepare( "SELECT * FROM cm_post ORDER BY row DESC" );
		$stmt->execute();
		
		
		echo <<<EOM


<div id="information">
EOM;
		
		
		while ( $row = $stmt->fetch() ) {
			
			$time_difference = 0;
			$span_delete     = '';
			$span_edit       = '';
			$span_reserve    = '';
			$image_html      = '';
			$data_order      = '';
			
			
			if ( file_exists( dirname( __FILE__ ) .'/../addon/reserve/time-difference.php' ) ) {
				include( dirname( __FILE__ ) .'/../addon/reserve/time-difference.php' );
			}
			
			
			if ( $login !== true ) {
				
				if ( (int)$time_difference < 0 ) {
					continue;
				}
				
			} else {
				
				$span_delete = '<span class="delete" data-delete="'.$row['id'].'">削除する</span>';
				
				if ( file_exists( dirname( __FILE__ ) .'/../addon/edit/span-edit.php' ) ) {
					include( dirname( __FILE__ ) .'/../addon/edit/span-edit.php' );
				}
				
				if ( file_exists( dirname( __FILE__ ) .'/../addon/reserve/span-reserve.php' ) ) {
					include( dirname( __FILE__ ) .'/../addon/reserve/span-reserve.php' );
				}
				
			}
			
			
			if ( file_exists( dirname( __FILE__ ) .'/../addon/attachment/image-html.php' ) ) {
				include( dirname( __FILE__ ) .'/../addon/attachment/image-html.php' );
			}
			
			if ( file_exists( dirname( __FILE__ ) .'/../addon/sort/data-order.php' ) ) {
				include( dirname( __FILE__ ) .'/../addon/sort/data-order.php' );
			}
			
			
			echo <<<EOM

	<div{$data_order}>
		{$image_html}<dl>
			<dt>{$span_edit}{$span_delete}{$row['date']}{$span_reserve}</dt>
			<dd>{$row['contents']}</dd>
		</dl>
	</div>
EOM;
			
		}
		
		
		echo <<<EOM

</div>
EOM;
		
	}
	
}

?>