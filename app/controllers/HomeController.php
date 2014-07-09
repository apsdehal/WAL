<?php

 class HomeController{

 	/**
 	 * @var $mwConfig A variable for holding an instance of MWOAuthClientCOnfig
 	 * @var $cmrToken Token received generated by passing secret and consume_token to
 	 * 					to OAuthClient class
 	 * @var $client Instance of MWOAuthClass that is generated after passing both above vars into it
 	 * 				Used to interact with Wikimedia's OAuth API 	
 	 */	
 	private $mwClient;
 	private $tool;

  	public function __construct(){
 		$this->tool = 'wikidata-annotation-tool';
		global $botmode;
 		$this->botmode = $botmode;

 	}
	
 	/**
 	 * Function to handle the get request made to the server where this app reside
 	 * Since the App is built in a RESTful manner thats why this function is here
 	 * Initiaites the client and makes the redirect to and fro the wikimedia OAuth clientS
 	 * Also return the user info, <= todo after testing
 	 */	
 	public function get(){
 		$this->mwClient = new MW_OAuth(	'Wikidata-Annotation', 'wikidata', 'www' );

 		$this->checkRedirect();

		$this->out = array ( 'error' => 'OK' , 'data' => array() );
 		
		switch ( isset( $_GET['action'] ) ? $_GET['action'] : '' ) {
			case 'authorize':
				$this->mwClient->doAuthorizationRedirect();
				exit ( 0 ) ;
				return;
			
			case 'remove_claim' :
				$this->removeClaim() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'set_claims':
				$this->setClaims() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'merge_items':
				$this->mergeItems() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'set_label':
				$this->setLabel() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'set_string':
				$this->setString() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'get_rights':
				$this->getRights() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'logout':
				$this->logout() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;
			
			case 'set_date':
				$this->setDateClaim() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'create_item_from_page':
				$this->createItemFromPage() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'delete':
				$this->deletePage() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'add_row': // Adds a text row to a non-item page
				$this->addRow() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;
				
			case 'append' :
				$this->appendText() ;
				if ( $this->botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;
		}
 	}

 	public function analyzeGetRequests(){
 		if(isset($_GET['action'])){
 			unset($_GET['action']);
 		}
 		return $_GET;
 	}

 	public function setRedirect(){
 		session_start();
 		$_SESSION['redirect_to'] = $_SERVER['HTTP_REFERER'];
 		session_write_close();
 	}

 	public function checkRedirect(){
 		session_start();
 		if(isset($_SESSION['redirect_to']) && $_SESSION['redirect_to']){
 			$redirect = $_SESSION['redirect_to'];
  			unset($_SESSION['redirect_to']);
  			var_dump($_SESSION);
 			header('Location:' . $redirect);
 		}
 	}

 	public function ensureAuth () {
		$ch = null;

		// First fetch the username
		$res = $this->mwClient->doApiQuery( array(
			'format' => 'json',
			'action' => 'query',
			'meta' => 'userinfo',
		), $ch );

		if ( isset( $res->error->code ) && $res->error->code === 'mwoauth-invalid-authorization' ) {
			// We're not authorized!
			$msg = 'You haven\'t authorized this application yet! Go <a target="_blank" href="' . htmlspecialchars( $_SERVER['SCRIPT_NAME'] ) . '?action=authorize">here</a> to do that, then reload this page.' ;
			if ( $this->botmode ) $this->out['error'] = $msg ;
			else echo $msg . '<hr>';
			return false ;
		}

		if ( !isset( $res->query->userinfo ) ) {
			$msg = 'Bad API response[1]: <pre>' . htmlspecialchars( var_export( $res, 1 ) ) . '</pre>' ;
			if ( $this->botmode ) {
				$this->out['error'] = $msg ;
				return false ;
			} else {
				header( "HTTP/1.1 500 Internal Server Error" );
				echo $msg;
				exit(0);
			}
		}
		if ( isset( $res->query->userinfo->anon ) ) {
			$msg = 'Not logged in. (How did that happen?)' ;
			if ( $this->botmode ) {
				$this->out['error'] = $msg ;
				return false ;
			} else {
				header( "HTTP/1.1 500 Internal Server Error" );
				echo $msg;
				exit(0);
			}
		}
		
		return true ;
	}

	public function setLabel () {		
		// https://tools.wmflabs.org/widar/index.php?action=set_label&q=Q1980313&lang=en&label=New+Bach+monument+in+Leipzig&botmode=1

		if ( !$this->ensureAuth() ) return ;
		show_header() ;

		$q = get_request ( 'q' , '' ) ;
		$lang = get_request ( 'lang' , '' ) ;
		$label = get_request ( 'label' , '' ) ;
		
		if ( $q == '' or $lang == '' or $label == '' ) {
			$msg = "Needs q, lang, label" ;
			if ( $this->botmode ) $this->out['error'] = $msg ;
			else print "<pre>$msg</pre>" ;
			return ;
		}

		if ( !$this->mwClient->setLabel ( $q , $label , $lang ) ) {
			$msg = "Problem setting label" ;
			if ( $this->botmode ) $this->out['error'] = $msg ;
			else print "<pre>$msg</pre>" ;
		}
	}
 
 }