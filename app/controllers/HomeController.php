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

 		$this->addHeaders();

		$this->out = array ( 'error' => 'OK' , 'data' => array() );

		$botmode = isset ( $_REQUEST['botmode'] ) ;

		if ( $botmode ) {
			header ( 'application/json' ) ; // text/plain
		} else {
			error_reporting(E_ERROR|E_CORE_ERROR|E_ALL|E_COMPILE_ERROR);
			ini_set('display_errors', 'On');
		}

 		
		switch ( isset( $_GET['action'] ) ? $_GET['action'] : '' ) {
			case 'authorize':
				$oa->doAuthorizationRedirect();
				exit ( 0 ) ;
				return;
			
			case 'remove_claim' :
				removeClaim() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'set_claims':
				setClaims() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'merge_items':
				mergeItems() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'set_label':
				setLabel() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'set_string':
				setString() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'get_rights':
				getRights() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'logout':
				logout() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;
			
			case 'set_date':
				setDateClaim() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'create_item_from_page':
				createItemFromPage() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'delete':
				deletePage() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;

			case 'add_row': // Adds a text row to a non-item page
				addRow() ;
				if ( $botmode ) bot_out() ;
				else print get_common_footer() ;
				exit ( 0 ) ;
				return ;
				
			case 'append' :
				appendText() ;
				if ( $botmode ) bot_out() ;
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

 	public function addHeaders(){
		header('Content-type: application/json');
		header('Access-Control-Allow-Origin: *'); 
		header('Access-Control-Allow-Headers:Content-Type, X-Requested-With, Accept');
		header('Access-Control-Allow-Methods:HEAD, GET, POST, PUT, DELETE, OPTIONS');
 	}
 
 }